<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 20:05
 */

namespace HttpServer\component;


use HttpServer\model\UserInfoModel;

class UserSession
{
    public $userId = null;
    public $key = null;
    public $isLogin = false;

    public function __construct($cookie)
    {
        if (isset($cookie['userId']) && isset($cookie['key'])) {
            $this->isLogin = UserInfoModel::checkSession($cookie['userId'], $cookie['key']);

            if ($this->isLogin) {
                $this->userId = $cookie['userId'];
                $this->key = $cookie['key'];
            }
        }
    }
}