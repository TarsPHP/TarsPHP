<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2016/9/21
 * Time: 14:10
 */

namespace Server\component;

use HttpServer\conf\Code;
use HttpServer\exception\ActivityException;

trait BaseTrait
{
    public static function getController($throwException = true)
    {
        $id = \Swoole\Coroutine::getuid();
        if (isset(BusinessController::$controller[$id])) {
            return BusinessController::$controller[$id];
        } else {
            return null;
        }
    }

    public static function getRequestData($key1 = null, $key2 = null, $default = null)
    {
        if (!self::getController(false)) {
            return $default;
        }

        if ($key1 == null) {
            return self::getController()->getRequest()->data;
        }

        if (!isset(self::getController()->getRequest()->data[$key1])) {
            return $default;
        }

        if ($key2 == null) {
            return self::getController()->getRequest()->data[$key1];
        }

        if (!isset(self::getController()->getRequest()->data[$key1][$key2])) {
            return $default;
        }

        return self::getController()->getRequest()->data[$key1][$key2];
    }

    public static function getGet($key, $defaultVal = null)
    {
        $out = self::getRequestData('get', $key);

        return $out === null ? $defaultVal : $out;
    }

    public static function getPost($key, $defaultVal = null)
    {
        if (isset(self::getController()->postData[$key])) {
            return self::getController()->postData[$key];
        } else {
            return $defaultVal;
        }
    }

    public static function getUserId()
    {
        $controller = self::getController();
        if ($controller != null && $controller->session != null && $controller->session->isLogin) {
            return $controller->session->userId;
        } else {
            return false;
        }
    }

    public static function checkIsLogin($throwException = false)
    {
        $userId = self::getUserId();
        if ($userId === false) {
            if ($throwException) {
                throw new ActivityException(Code::CODE_UNLOGIN);
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}
