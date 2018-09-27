<?php

namespace Server\model\DbModel;

use dbObject;
use MysqliDb;
use ReflectionProperty;

/**
 * Created by PhpStorm.
 * User: liujingfeng.a
 * Date: 2017/12/26
 * Time: 17:28.
 */
class BaseDbModel extends dbObject
{
    protected $dbConnInstanceName = 'default';

    public function __construct($data = null)
    {
        parent::__construct($data);

        $db = clone MysqliDb::getInstance();
        $db->connection($this->dbConnInstanceName);
        $refProperty = new ReflectionProperty(dbObject::class, 'db');
        $refProperty->setAccessible(true);
        $refProperty->setValue($this, $db);
    }

    public static function getOneById($id)
    {
        return self::where('id', $id, '=')->getOne();
    }

    public static function getOneArrayById($id)
    {
        $ret = self::where('id', $id, '=')->getOne();
        if (empty($ret)) {
            return null;
        } else {
            return $ret->toArray();
        }
    }
}
