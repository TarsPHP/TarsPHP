<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/9/11
 * Time: 下午11:05
 */

namespace WebsocketServer\component;


interface FdStore
{
    public static function addFdByKey($key, $fd);


    public static function delFdByKey($key, $fd);


    public static function getFdsByKey($key);

}