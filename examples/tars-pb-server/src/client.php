<?php
require_once __DIR__ . '/vendor/autoload.php';

use \Protocol\QD\ActCommentPbServer\GetRequest;
use \Protocol\QD\ActCommentPbServer\CommonInParam;
use \Protocol\QD\ActCommentPbServer\QueryParam;
use \Protocol\QD\ActCommentPbServer\GetResponse;

class TestGrpcClient
{
    public static function callGrpc($ip, $port, $path, $requestBuf)
    {
        $cli = new Swoole\Coroutine\Http2\Client($ip, $port, false);
        $cli->connect();
        $req = new swoole_http2_request;
        $req->method = 'POST';
        $req->path = $path;
        $req->headers = [
            "user-agent" => 'grpc-c/7.0.0 (linux; chttp2; gale)',
            "content-type" => "application/grpc",
            "grpc-accept-encoding" => "identity,deflate,gzip",
            "accept-encoding" => "identity,gzip",
            "te" => "trailers",
        ];
        $req->pipeline = false;
        $req->data = $requestBuf;

        $cli->send($req);
        $response = $cli->recv();
        return $response->data;
    }

    public static function main()
    {
        $commonIn = new CommonInParam();
        $commonIn->setUserId(0);
        $commonIn->setAppId(1);
        $commonIn->setAreaId(10);
        $commonIn->setServerIp('127.0.0.1');
        $commonIn->setUserIp('');

        $query = new QueryParam();
        $query->setActivityId(123);
        $query->setPage(1);
        $query->setSize(10);
        $query->setOrderType(1);

        $request = new GetRequest();
        $request->setInParam($commonIn);
        $request->setQueryParam($query);


        $requestBuf = $request->serializeToString();
        $packBuf = pack('CN', 0, strlen($requestBuf)) . $requestBuf;

        go(function () use ($packBuf){
            $path = "/protocol.QD.ActCommentServer.CommentObj/getComment";
            $ret = self::callGrpc('127.0.0.1', 10008, $path, $packBuf); //这里注意要修改成你服务在tars上绑定的ip 127.0.0.1不一定可以
            $response = new GetResponse();
            $response->mergeFromString(substr($ret, 5));
            foreach ($response->getList() as $row) {
                var_dump($row->getContent());
            }
        });
    }
}

TestGrpcClient::main();
