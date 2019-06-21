<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午2:51.
 */

// 以namespace的方式,在psr4的框架下对代码进行加载
return [
    'obj' => [
        'home-api' => '\Server\servant\PHPTest\PHPServer\obj\TestTafServiceServant',
        'home-class' => '\Server\impl\PHPServerServantImpl',
        'protocolName' => 'tars', //http, json, tars or other
        'serverType' => 'tcp', //http(no_tars default), websocket, tcp(tars default), udp
    ],
];
