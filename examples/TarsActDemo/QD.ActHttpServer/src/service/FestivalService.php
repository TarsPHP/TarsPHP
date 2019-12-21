<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 16:16
 */

namespace HttpServer\service;

use HttpServer\model\ActCommentGoModel;
use HttpServer\model\ActCommentModel;
use HttpServer\model\ActCommentPbModel;
use HttpServer\model\UserInfoModel;

class FestivalService extends BaseService
{
    const ACTIVITY_ID = 123;

    public static function getBullet($page, $size)
    {
        $list = ActCommentModel::getComment(self::ACTIVITY_ID, $page, $size); //use tars
//        $list = ActCommentPbModel::getComment(self::ACTIVITY_ID, $page, $size); //use protobuf
//        $list = ActCommentGoModel::getComment(self::ACTIVITY_ID, $page, $size); //use go

        $usersId = array_column($list, 'userId');
        $usersInfo = UserInfoModel::getUsersInfoByIds($usersId);

        foreach ($list as &$row) {
            $row['user'] = isset($usersInfo[$row['userId']]) ? $usersInfo[$row['userId']] : null;
        }

        return $list;
    }

    public static function createBullet($userId, $message)
    {
        $ret = ActCommentModel::createComment($userId, self::ACTIVITY_ID, '', $message); //use tars
//        $ret = ActCommentPbModel::createComment($userId, self::ACTIVITY_ID, '', $message); //use protobuf
//        $ret = ActCommentGoModel::createComment($userId, self::ACTIVITY_ID, '', $message); //use go
        return $ret;
    }

    public static function ping()
    {
        return ActCommentModel::ping();
//        return ActCommentPbModel::ping();
//        return ActCommentGoModel::ping();
    }

    public static function getCount()
    {
        return ActCommentModel::getCount();
//        return ActCommentPbModel::getCount();
//        return ActCommentGoModel::getCount();
    }
}
