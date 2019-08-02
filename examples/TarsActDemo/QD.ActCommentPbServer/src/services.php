<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午2:51.
 */

// 以namespace的方式,在psr4的框架下对代码进行加载
return [
    'CommentObj' => [
        'home-api' => '\Protocol\QD\ActCommentPbServer\CommentObjServant',
        'home-class' => '\Server\impl\ActCommentServerImpl',
        'protocolName' => 'pb', //http, json, tars or other
        'serverType' => 'grpc', //http(no_tars default), websocket, tcp(tars default), udp
    ],
];
