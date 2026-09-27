<?php
// Run each example in a fresh process because generated class names overlap.
$source = $argv[1];
require $source . '/vendor/autoload.php';
$map = json_decode(file_get_contents($source . '/composer.json'), true)['autoload']['psr-4'];
spl_autoload_register(function ($class) use ($source, $map) {
    foreach ($map as $prefix => $directory) {
        if (strpos($class, $prefix) === 0) {
            $path = $source . '/' . $directory . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (is_file($path)) {
                require $path;
            }
        }
    }
});
function check($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
$count = 0;
$paths = array_merge(glob($source . '/Helloworld/*.php') ?: [], glob($source . '/protocol/QD/ActCommentPbServer/*.php') ?: []);
foreach ($paths as $path) {
    $text = file_get_contents($path);
    if (!preg_match('/namespace ([^;]+);/', $text, $namespace) || strpos($text, 'extends \\Google\\Protobuf\\Internal\\Message') === false) {
        continue;
    }
    $class = $namespace[1] . '\\' . basename($path, '.php');
    $original = new $class();
    $copy = new $class();
    $copy->mergeFromString($original->serializeToString());
    check($copy->serializeToJsonString() === $original->serializeToJsonString(), 'Empty round trip: ' . $class);
    $count++;
}
if (is_dir($source . '/Helloworld')) {
    $request = new Helloworld\HelloRequest(['name' => 'protobuf 安全升级']);
    $copy = new Helloworld\HelloRequest();
    $copy->mergeFromString($request->serializeToString());
    check($copy->getName() === $request->getName(), 'HelloRequest round trip');
    $reply = new Helloworld\HelloReply(['message' => 'Hello world']);
    $copy = new Helloworld\HelloReply();
    $copy->mergeFromString($reply->serializeToString());
    check($copy->getMessage() === $reply->getMessage(), 'HelloReply round trip');
} else {
    $comment = new Protocol\QD\ActCommentPbServer\SimpleComment([
        'id' => -1, 'activityId' => 1234567890123, 'userId' => 42,
        'content' => '安全升级', 'title' => 'round trip', 'ext1' => 'test', 'createTime' => 1790467200,
    ]);
    $request = new Protocol\QD\ActCommentPbServer\CreateRequest();
    $request->setComment($comment);
    $copy = new Protocol\QD\ActCommentPbServer\CreateRequest();
    $copy->mergeFromString($request->serializeToString());
    check($copy->serializeToJsonString() === $request->serializeToJsonString(), 'Nested message round trip');
    $response = new Protocol\QD\ActCommentPbServer\GetResponse();
    $response->setList([$comment]);
    $copy = new Protocol\QD\ActCommentPbServer\GetResponse();
    $copy->mergeFromString($response->serializeToString());
    check($copy->serializeToJsonString() === $response->serializeToJsonString(), 'Repeated message round trip');
}
check($count > 0, 'No generated messages tested');
echo basename(dirname($source)), ': ', $count, " generated messages and populated round trips passed\n";
