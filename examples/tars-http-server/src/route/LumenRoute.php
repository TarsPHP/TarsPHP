<?php
/**
 * Created by PhpStorm.
 * User: chengxiaoli
 * Date: 2019/7/22
 * Time: 下午7:39
 */

namespace HttpServer\route;

use Illuminate\Container\Container;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Http\Request as IlluminateRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tars\core\Request as TarsRequest;
use Tars\core\Response as TarsResponse;
use Tars\route\Route;

class LumenRoute implements Route
{
    protected $tarsRequest;
    
    protected $tarsResponse;
    
    protected $illuminateRequest;
    
    protected $illuminateResponse;
    
    
    /**
     * @param TarsRequest $request
     * @param TarsResponse $response
     */
    public function dispatch(TarsRequest $request, TarsResponse $response)
    {
        // 实例化服务器容器，注册事件，路由服务提供者
        $app = new Container;
        with(new EventServiceProvider($app))->register();
        with(new RoutingServiceProvider($app))->register();

        // 加载路由 路由文件的位置是否支持可配置？
        require __DIR__.'/../../../../../app/Http/routes.php';

        // 将 TarsRequest 转为 LumenRequest
        $illuminateRequest = $this->makeRequest($request);
        
        // 路由
        $illuminateResponse = $app['router']->dispatch($illuminateRequest);
    
        // 将 LumenResponse 转为 TarsResponse
        $this->send($illuminateResponse, $response);
    }
    
    private function makeRequest(TarsRequest $tarsRequest)
    {
        list($get, $post, $cookie, $files, $server, $content) = self::toIlluminateParameters($tarsRequest);
        return $this->createIlluminateRequest($get, $post, $cookie, $files, $server, $content);
    }
    
    protected static function toIlluminateParameters(TarsRequest $request)
    {
        $get = isset($request->data['get']) ? $request->data['get'] : [];
        $post = isset($request->data['post']) ? (is_array($request->data['post']) ? $request->data['post'] : []) : [];
        $cookie = isset($request->data['cookie']) ? $request->data['cookie'] : [];
        $files = isset($request->data['files']) ? $request->data['files'] : [];
        $header = isset($request->data['header']) ? $request->data['header'] : [];
        $server = isset($request->data['server']) ? $request->data['server'] : [];
        $server = self::transformServerParameters($server, $header);
        $content = $request->data['post'] ?
            (is_array($request->data['post']) ? http_build_query($request->data['post']) : $request->data['post']) :
            null;
        return [$get, $post, $cookie, $files, $server, $content];
    }
    
    protected static function transformServerParameters(array $server, array $header)
    {
        $__SERVER = [];
        foreach ($server as $key => $value) {
            $key = strtoupper($key);
            $__SERVER[$key] = $value;
        }
        foreach ($header as $key => $value) {
            $key = str_replace('-', '_', $key);
            $key = strtoupper($key);
            
            if (! in_array($key, ['REMOTE_ADDR', 'SERVER_PORT', 'HTTPS'])) {
                $key = 'HTTP_' . $key;
            }
            $__SERVER[$key] = $value;
        }
        return $__SERVER;
    }
    
    protected function createIlluminateRequest($get, $post, $cookie, $files, $server, $content = null)
    {
        IlluminateRequest::enableHttpMethodParameterOverride();
        /*
        |--------------------------------------------------------------------------
        | Copy from \Symfony\Component\HttpFoundation\Request::createFromGlobals().
        |--------------------------------------------------------------------------
        |
        | With the php's bug #66606, the php's built-in web server
        | stores the Content-Type and Content-Length header values in
        | HTTP_CONTENT_TYPE and HTTP_CONTENT_LENGTH fields.
        |
        */
        if ('cli-server' === PHP_SAPI) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $server)) {
                $server['CONTENT_LENGTH'] = $server['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $server)) {
                $server['CONTENT_TYPE'] = $server['HTTP_CONTENT_TYPE'];
            }
        }
        $request = new SymfonyRequest($get, $post, [], $cookie, $files, $server, $content);
        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), array('PUT', 'DELETE', 'PATCH'))
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }
        return IlluminateRequest::createFromBase($request);
    }
    
    protected function send($illuminateResponse, $tarsResponse)
    {
        if (! $illuminateResponse instanceof SymfonyResponse) {
            $content = (string) $illuminateResponse;
            $illuminateResponse = new IlluminateResponse($content);
        }
        $this->sendHeaders($illuminateResponse, $tarsResponse);
        $this->sendContent($illuminateResponse, $tarsResponse);
    }
    
    protected function sendHeaders($illuminateResponse, &$tarsResponse)
    {
        /* RFC2616 - 14.18 says all Responses need to have a Date */
        if (! $illuminateResponse->headers->has('Date')) {
            $illuminateResponse->setDate(\DateTime::createFromFormat('U', time()));
        }
        // headers
        foreach ($illuminateResponse->headers->allPreserveCaseWithoutCookies() as $name => $values) {
            foreach ($values as $value) {
                $tarsResponse->header($name, $value);
            }
        }
        // status
        $tarsResponse->status($illuminateResponse->getStatusCode());
        // cookies
        foreach ($illuminateResponse->headers->getCookies() as $cookie) {
            $method = $cookie->isRaw() ? 'rawcookie' : 'cookie';
            
            $tarsResponse->resource->$method(
                $cookie->getName(), $cookie->getValue(),
                $cookie->getExpiresTime(), $cookie->getPath(),
                $cookie->getDomain(), $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }
    }
    
    protected function sendContent($illuminateResponse, &$tarsResponse)
    {
        if ($illuminateResponse instanceof StreamedResponse) {
            $illuminateResponse->sendContent();
        } elseif ($illuminateResponse instanceof BinaryFileResponse) {
            $tarsResponse->resource->sendfile($illuminateResponse->getFile()->getPathname());
        } else {
            $tarsResponse->resource->end($illuminateResponse->getContent());
        }
    }
    
}
