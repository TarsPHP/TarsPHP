<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 15:42
 */

namespace Server\conf;


class Code
{
    //1、0~1000作为通用提示信息码
    const CODE_SUC = 0;
    const CODE_FAIL = 1;
    const CODE_TIME_OUT = 2;
    const CODE_REDIS_FAIL = 3;
    const CODE_DISABLE_CALL = 4;
    const CODE_TOO_FAST = 5;
    const CODE_CSRF_FAILED = 6;
    const ERR_DB_CONFIG_NOT_FOUND = 7;

    const CODE_UNLOGIN = 1000;
    const CODE_INVALID_PARAM = 1007;

    //1000 - 100000， 100一个model


    public static function getMsg($code)
    {
        return self::$msgs[$code];
    }

    public static function getAjaxMsg($code)
    {
        return isset(self::$msgs[$code]) ? self::$msgs[$code] : self::$msgs[self::CODE_FAIL];
    }

    public static $msgs = [
        self::CODE_SUC => "成功",
        self::CODE_FAIL => '失败',
        self::CODE_TIME_OUT => '请求超时了，请稍后再试',
        self::CODE_REDIS_FAIL => 'Redis请求超时了，请稍后再试',
        self::CODE_DISABLE_CALL => '已经下线不再允许访问',
        self::CODE_TOO_FAST => '您的操作过于频繁啦',
        self::CODE_CSRF_FAILED => 'Csrf token failed',
        self::ERR_DB_CONFIG_NOT_FOUND => '没有找到对应的数据配置',

        self::CODE_UNLOGIN => '未登录',
        self::CODE_INVALID_PARAM => '参数错误',

    ];
}