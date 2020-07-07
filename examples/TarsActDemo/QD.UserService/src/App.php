<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2019/6/17
 * Time: 15:43
 */

namespace Server;

use MysqliDb;
use Server\conf\ENVConf;

class App
{
    public static $hasInitDb = false;

    public static function initDb()
    {
        if (self::$hasInitDb) {
            return true;
        }

        $dbsConf = ENVConf::getDbConf();
        if (empty($dbsConf)) {
            var_dump('db conf is empty, check ENVConf.php, set db.json on tars web site');
            return false;
        }

        $db = new MysqliDb($dbsConf[0]);
        foreach ($dbsConf as $config) {
            $db->addConnection($config['instanceName'], $config);
        }

        self::$hasInitDb = true;
    }
}
