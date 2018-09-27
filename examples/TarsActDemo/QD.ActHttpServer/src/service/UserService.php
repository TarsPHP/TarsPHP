<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 16:16
 */

namespace HttpServer\service;

use HttpServer\conf\Code;
use HttpServer\exception\ActivityException;
use HttpServer\model\UserInfoModel;

class UserService extends BaseService
{
    public static function login($nickname, $password)
    {
        $ret = UserInfoModel::login($nickname, $password);
        if (!$ret) {
            throw new ActivityException(Code::CODE_LOGIN_FAILED);
        }

        return $ret;
    }
}