<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/17
 * Time: 下午4:15.
 */

namespace Server\conf;

use Tars\App;

class ENVConf
{
    public static $socketMode = 2;

    public static function getTarsConf()
    {
        return App::getTarsConfig();
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
