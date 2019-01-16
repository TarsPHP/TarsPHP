<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 17:22
 */

namespace HttpServer\model;


use HttpServer\conf\Code;
use HttpServer\exception\ActivityException;
use HttpServer\protocol\QD\UserService\UserObj\classes\CommonInParam;
use HttpServer\protocol\QD\UserService\UserObj\classes\CommonOutParam;
use HttpServer\protocol\QD\UserService\UserObj\UserServiceServant;

class UserInfoModel extends BaseModel
{
    protected static function getCommonIn()
    {
        $commonIn = new CommonInParam();
        $commonIn->userId = 0;
        $commonIn->appId = 1;
        $commonIn->areaId = 10;
        $commonIn->userIp = '';
        $commonIn->serverIp = '127.0.0.1';

        return $commonIn;
    }

    public static function getUsersInfoByIds($ids)
    {
        try {
            $commonOut = new CommonOutParam();
            $servant = new UserServiceServant(self::getConfig());
            $servant->getUsersInfoByIds(self::getCommonIn(), $ids, $commonOut, $info);

            if ($commonOut->code !== 0) {
                //business error
                //TODO log
                throw new ActivityException(Code::USER_INFO_MODEL_GET_INFO_FAILED, $commonOut->code);
            }

            return $info;
        } catch (ActivityException $e) {
            throw $e;
        } catch (\Exception $e) {
            //TODO log
            throw new ActivityException(Code::USER_INFO_MODEL_CONNECT_ERROR, $e->getMessage() . $e->getCode());
        }
    }

    public static function checkSession($id, $key)
    {
        try {
            $commonOut = new CommonOutParam();
            $servant = new UserServiceServant(self::getConfig());
            $servant->checkSession(self::getCommonIn(), $id, $key, $commonOut);

            if ($commonOut->code !== 0) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            //TODO log
            return false;
        }
    }

    public static function login($id, $password)
    {
        try {
            $commonOut = new CommonOutParam();
            $servant = new UserServiceServant(self::getConfig());
            $servant->login(self::getCommonIn(), $id, $password, $commonOut, $userId, $sessionKey);

            if ($commonOut->code !== 0) {
                //TODO log
                return false;
            }

            return ['userId' => $userId, 'key' => $sessionKey];
        } catch (\Exception $e) {
            //TODO log
            return false;
        }
    }
}