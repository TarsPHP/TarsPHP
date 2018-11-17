<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/8
 * Time: 下午2:42.
 */

namespace HttpServer\controller;

use HttpServer\component\BusinessController;
use HttpServer\model\UserInfoModel;
use HttpServer\service\UserService;

class IndexController extends BusinessController
{
    public function actionIndex()
    {
        $this->header('Access-Control-Allow-Origin', '*');
        $userId = self::getUserId();
        if ($userId) {
            $userInfo = UserInfoModel::getUsersInfoByIds([$userId]);
            $ret = [
                'isLogin' => 1,
                'userInfo' => $userInfo[$userId],
            ];
        } else {
            $ret = [
                'isLogin' => 0,
                'userInfo' => null,
            ];
        }

        $this->sendSuccess($ret);
    }

    public function actionLogin()
    {
        $this->header('Access-Control-Allow-Origin', '*');
        $nickname = self::getPost('nickname');
        $password = self::getPost('password');

        $ret = UserService::login($nickname, $password);

        $domain = self::getRequestData('header', 'host');
        $time = time() + 7200;
        $this->cookie('key', $ret['key'], $time, '/', $domain);
        $this->cookie('userId', $ret['userId'], $time, '/', $domain);

        $this->sendSuccess();
    }
}
