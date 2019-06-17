<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/29
 * Time: 16:24
 */

namespace Server\service;

use Server\conf\Code;
use Server\exception\BusinessException;
use Server\model\DbModel\UserInfoDbModel;
use Server\protocol\QD\UserService\UserObj\classes\User;

class UserInfoService
{
    public static function getUserInfoByIds($usersId, &$info, &$outArray = null)
    {
        if (empty($usersId)) {
            return;
        }

        /** @var UserInfoDbModel[] $objs */
        $usersObj = UserInfoDbModel::getUserInfoByIds($usersId);
        if (empty($usersObj)) {
            return;
        }

        $usersObjWithIndex = [];
        foreach ($usersObj as $oneObj) {
            $usersObjWithIndex[$oneObj->id] = $oneObj;
        }

        foreach ($usersId as $userId) {
            if (isset($usersObjWithIndex[$userId])) {
                $tmp = new User();
                $tmp->userId = $usersObjWithIndex[$userId]->id;
                $tmp->nickname = $usersObjWithIndex[$userId]->nickname;
                $tmp->avatar = $usersObjWithIndex[$userId]->avatar;
                $tmp->createTime = strtotime($usersObjWithIndex[$userId]->createTime);
                $outArray[] = $tmp;
                $info->pushBack([$userId => $tmp]);
            }
        }

        return;
    }

    public static function checkSession($userId, $sessionKey)
    {
        if ('key' . $userId != $sessionKey) {
            throw new BusinessException(Code::USER_INFO_CHECK_SESSION_FAILED);
        }
    }

    public static function login($nickname, $password)
    {
        $obj = UserInfoDbModel::findOneWithPassword($nickname, md5($password));
        if (!$obj) {
            throw new BusinessException(Code::USER_INFO_INVALID_PASSWORD);
        }

        return ['userId' => $obj->id, 'key' => 'key' . $obj->id];
    }
}
