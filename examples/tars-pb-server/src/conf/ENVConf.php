<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/17
 * Time: 下午4:15.
 */

namespace Server\conf;

use Tars\App;

class ENVConf
{
    public static $socketMode = 2;

    public static function getTarsConf()
    {
        return App::getTarsConfig();
    }
}
