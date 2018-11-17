<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 16:16
 */

namespace HttpServer\service;

use HttpServer\model\ActCommentModel;
use HttpServer\model\UserInfoModel;

class FestivalService extends BaseService
{
    const ACTIVITY_ID = 123;

    public static function getBullet($page, $size)
    {
        $list = ActCommentModel::getComment(self::ACTIVITY_ID, $page, $size);

        $usersId = array_column($list, 'userId');
        $usersInfo = UserInfoModel::getUsersInfoByIds($usersId);

        foreach ($list as &$row) {
            $row['user'] = isset($usersInfo[$row['userId']]) ? $usersInfo[$row['userId']] : null;
        }

        return $list;
    }

    public static function createBullet($userId, $message)
    {
        $ret = ActCommentModel::createComment($userId, self::ACTIVITY_ID, '', $message);
        return $ret;
    }
}