<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 15:42
 */

namespace HttpServer\conf;


class Code
{
    //1、0~1000作为通用提示信息码
    const CODE_SUC = 0;
    const CODE_FAIL = 1;
    const CODE_TIME_OUT = 2;
    const CODE_REDIS_FAIL = 3;
    const CODE_LOGIN_FAILED = 4;

    const CODE_UNLOGIN = 1000;
    const CODE_INVALID_PARAM = 1007;

    //1000 - 100000， 100一个model
    const USER_INFO_MODEL_CONNECT_ERROR = 1101;
    const USER_INFO_MODEL_GET_INFO_FAILED = 1102;
    const USER_INFO_MODEL_CHECK_SESSION_FAILED = 1103;

    const COMMENT_MODEL_CONNECT_ERROR = 1201;
    const COMMENT_MODEL_CREATE_FAILED = 1202;


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
        self::CODE_LOGIN_FAILED => '登录失败，userId或密码错误',

        self::CODE_UNLOGIN => '未登录',
        self::CODE_INVALID_PARAM => '参数错误',

        self::USER_INFO_MODEL_CONNECT_ERROR => '调用taf失败，通讯异常',
        self::USER_INFO_MODEL_GET_INFO_FAILED => '获取用户信息失败',
        self::USER_INFO_MODEL_CHECK_SESSION_FAILED => '登录态检查失败',

        self::COMMENT_MODEL_CONNECT_ERROR => '调用taf失败，通讯异常',
        self::COMMENT_MODEL_CREATE_FAILED => '创建评论失败',
    ];
}