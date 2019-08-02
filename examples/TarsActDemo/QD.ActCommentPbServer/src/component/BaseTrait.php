<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2016/9/21
 * Time: 14:10
 */

namespace Server\component;


use Exception;
use Redis;
use Server\conf\ENVConf;

trait BaseTrait
{
    protected static $redisInstance = null;

    /**
     * @param string $conf
     * @return redis mixed
     */
    public static function getRedis($conf = 'default')
    {
        if (empty(self::$redisInstance[$conf])) {
            $config = ENVConf::getRedisConf($conf);
            self::$redisInstance[$conf] = new Redis();
            self::$redisInstance[$conf]->connect($config['host'], $config['port'], $config['timeout']);
            self::$redisInstance[$conf]->auth($config['password']);
        } else {
            try {
                //TODO check again
                if (!(self::$redisInstance[$conf]->ping())) {
                    unset(self::$redisInstance[$conf]);
                    return self::getRedis($conf);
                }
            } catch (Exception $e) {
                unset(self::$redisInstance[$conf]);
                return self::getRedis($conf);
            }
        }

        return self::$redisInstance[$conf];
    }

}