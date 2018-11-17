<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/17
 * Time: 下午4:15.
 */

namespace Server\conf;

class ENVConf
{
    public static $locator
        = 'tars.tarsregistry.QueryObj@tcp -h 172.16.0.161 -p 17890'; //TODO 这里应该不用定义的

    public static $socketMode = 2;

    public static function getTarsConf()
    {
        $table = $_SERVER->table;
        $result = $table->get('tars:php:tarsConf');
        $tarsConf = unserialize($result['tarsConfig']);

        return $tarsConf;
    }

    public static function getRedisConf($conf = 'default')
    {
        //TODO::这里需要改成你们自己redis的ip
        return [
            'host' => 'redis.tarsActDemo.local',
            'password' => '',
            'port' => '6379',
            'timeout' => 3,
        ];
    }
}
