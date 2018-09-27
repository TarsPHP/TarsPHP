<?php
namespace Server\model\DbModel;
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2017/6/22
 * Time: 13:44
 */

/**
 * @property int $id
 * @property int $act_id
 * @property string $nickname
 * @property int $age
 * @property int $status
 * @property string $avatar
 * @property int $createTime
 * Class UserInfoDbModel
 */
class UserInfoDbModel extends BaseDbModel
{
    protected $dbTable = 'user_info';

    public $returnType = 'Object';

    protected $dbFields = [
        'id' => ['int'],
        'nickname' => ['text'],
        'age' => ['int'],
        'status' => ['int'],
        'avatar' => ['text'],
        'createTime' => ['datetime'],
    ];

    public static function getUserInfoByIds($ids)
    {
        return self::where('id', $ids, 'in')->get();
    }

    public static function findOneWithPassword($nickname, $password)
    {
        return self::where('nickname', $nickname, '=')->where('password', $password, '=')->getOne();
    }
}
