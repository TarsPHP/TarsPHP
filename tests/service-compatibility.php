<?php
// Run after composer install, with Swoole 4.8, phptars, and Redis enabled.
require __DIR__ . '/protobuf-compatibility.php';

class TestResponseResource
{
    public $headers = [];
    public $trailers = [];
    public $body;
    public $status = 200;

    public function header($key, $value) { $this->headers[strtolower($key)] = $value; }
    public function trailer($key, $value) { $this->trailers[$key] = $value; }
    public function end($body) { $this->body = $body; }
    public function status($status) { $this->status = $status; }
}

function rpc($method, $input)
{
    global $source;
    $service = array_values(require $source . '/services.php')[0];
    $protocol = new Tars\protocol\PBProtocol();
    $reflection = new ReflectionMethod($service['home-api'], $method);
    $info = $protocol->parseAnnotation($reflection->getDocComment());
    $payload = $input->serializeToString();
    $request = new Tars\core\Request();
    $request->data = ['server' => ['request_uri' => '/compatibility.Service/' . $method]];
    $request->reqBuf = pack('CN', 0, strlen($payload)) . $payload;
    $request->paramInfos = [$method => $info];
    $response = new Tars\core\Response();
    $response->servType = 'grpc';
    $response->resource = new TestResponseResource();
    $routed = $protocol->route($request, $response);
    $implementation = new $service['home-class']();
    $args = $routed['args'];
    $implementation->{$routed['sFuncName']}($args[0], $args[1]);
    $wire = $protocol->packRsp($info, $routed['unpackResult'], $args, null);
    check($response->resource->trailers['grpc-status'] === '0', 'gRPC status');
    check(unpack('Nlength', substr($wire, 1, 4))['length'] === strlen($wire) - 5, 'gRPC frame length');
    $output = new $info['outParams'][0]['type']();
    $output->mergeFromString(substr($wire, 5));
    return $output;
}

// Exercise the installed framework's Swoole aliases and native TARS objects.
$cache = new Tars\monitor\cache\SwooleTableStoreCache(['size' => 64]);
$cache->set('compatibility', ['count' => 3]);
check($cache->getField('compatibility', 'count') === 3, 'Swoole monitor cache');
$routes = new Tars\registry\RouteTable(['size' => 64]);
$routes->setRouteInfo('compatibility', ['host' => '127.0.0.1', 'port' => 12345]);
check($routes->getRouteInfo('compatibility')['routeInfo']['port'] === 12345, 'Swoole route cache');
$serverInfo = new Tars\report\ServerInfo();
$serverInfo->application = 'Compatibility';
$serverInfo->serverName = 'Example';
$serverInfo->pid = 123;
$serverInfo->adapter = 'TestAdapter';
check(strlen(TUPAPI::putStruct('serverInfo', $serverInfo)) > 0, 'Native TARS platform struct');

if (is_dir($source . '/Helloworld')) {
    $reply = rpc('SayHello', new Helloworld\HelloRequest(['name' => 'PHP 8.1 安全升级']));
    check($reply->getMessage() === 'This is Tars pb server, your msg is: PHP 8.1 安全升级', 'Greeter response');
} elseif (is_dir($source . '/controller')) {
    $request = new Tars\core\Request();
    $request->namespaceName = 'HttpServer\\';
    $request->data = [
        'server' => ['request_uri' => '/index/index', 'request_method' => 'GET'],
        'header' => [], 'cookie' => [], 'post' => [], 'get' => [],
    ];
    $response = new Tars\core\Response();
    $response->servType = 'http';
    $response->resource = new TestResponseResource();
    (new Tars\route\DefaultRoute())->dispatch($request, $response);
    $body = json_decode($response->resource->body, true, 512, JSON_THROW_ON_ERROR);
    check($response->resource->status === 200 && $body['code'] === 0, 'HTTP controller response');
    check($body['data'] === ['isLogin' => 0, 'userInfo' => null], 'Anonymous HTTP session');
} else {
    rpc('ping', new Protocol\QD\ActCommentPbServer\PingRequest());
    $count = rpc('getCommentCount', new Protocol\QD\ActCommentPbServer\CountRequest());
    check($count->getCount() >= 1 && $count->getCount() <= 100, 'Comment count response');

    // Use a loopback test Redis with a unique key prefix; never read ENVConf or flush a database.
    $redis = new Redis();
    check($redis->connect('127.0.0.1', (int)(getenv('TEST_REDIS_PORT') ?: 6379), 2), 'Test Redis connection');
    $redis->setOption(Redis::OPT_PREFIX, 'tars-compat-' . bin2hex(random_bytes(8)) . ':');
    $property = new ReflectionProperty(Server\service\CommentService::class, 'redisInstance');
    $property->setAccessible(true);
    // Prevent BaseTrait's reconnect branch from reading application ENVConf on test connection loss.
    $storage = new class($redis) {
        private $redis;
        public function __construct($redis) { $this->redis = $redis; }
        public function ping() { return true; }
        public function __call($method, $arguments) { return $this->redis->$method(...$arguments); }
    };
    $property->setValue(null, ['default' => $storage]);
    try {
        $query = new Protocol\QD\ActCommentPbServer\QueryParam(['activityId' => 42, 'page' => 1, 'size' => 10]);
        $get = new Protocol\QD\ActCommentPbServer\GetRequest(['queryParam' => $query]);
        $empty = rpc('getComment', $get);
        check($empty->getOutParam()->getCode() === 0 && count($empty->getList()) === 0, 'Empty comment query');
        $comment = new Protocol\QD\ActCommentPbServer\SimpleComment([
            'activityId' => 42, 'content' => 'PHP 8.1 评论', 'title' => 'compatibility', 'ext1' => 'test',
        ]);
        $create = new Protocol\QD\ActCommentPbServer\CreateRequest([
            'inParam' => new Protocol\QD\ActCommentPbServer\CommonInParam(['userId' => 7]),
            'comment' => $comment,
        ]);
        check(rpc('createComment', $create)->getOutParam()->getCode() === 0, 'Create comment');
        $result = rpc('getComment', $get);
        check($result->getOutParam()->getCode() === 0 && count($result->getList()) === 1, 'Stored comment query');
        $stored = $result->getList()[0];
        check($stored->getId() === 1 && $stored->getUserId() === 7, 'Comment ID and default user');
        check($stored->getContent() === $comment->getContent() && $stored->getCreateTime() > 0, 'Stored comment fields');
        // A storage exception must become a protobuf business error, not a PHP fatal error.
        $property->setValue(null, ['default' => new class {
            public function ping() { return true; }
            public function lRange($key, $start, $end) { throw new RuntimeException('Test storage failure'); }
        }]);
        check(rpc('getComment', $get)->getOutParam()->getCode() !== 0, 'Comment storage error response');
    } finally {
        $property->setValue(null, null);
        $redis->del('comment_id', 'comment_c_1', 'index_act_id_42');
        $redis->close();
    }
}
echo basename(dirname($source)), ": service compatibility passed\n";
