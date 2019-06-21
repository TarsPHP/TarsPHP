<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午2:51.
 */

// 以namespace的方式,在psr4的框架下对代码进行加载
return [
    'HttpObj' => [
        'namespaceName' => 'Server\\',
        'monitorStoreConf' => [
            //使用redis缓存主调上报信息
            //'className' => Tars\monitor\cache\RedisStoreCache::class,
            //'config' => [
            // 'host' => '127.0.0.1',
            // 'port' => 6379,
            // 'password' => ':'
            //],
            //使用swoole_table缓存主调上报信息（默认）
            'className' => Tars\monitor\cache\SwooleTableStoreCache::class,
            'config' => [
                'size' => 40960
            ]
        ],
        'protocolName' => 'http', //http, json, tars or other
        'serverType' => 'http', //http(no_tars default), webSocket, tcp(tars default), udp
    ],
    'UserObj' => [
        'home-api' => 'Server\protocol\QD\UserService\UserObj\UserServiceServant',
        'home-class' => '\Server\impl\UserServiceImpl',
        'protocolName' => 'tars', //http, json, tars or other
        'serverType' => 'tcp', //http(no_tars default), websocket, tcp(tars default), udp
    ],
];
