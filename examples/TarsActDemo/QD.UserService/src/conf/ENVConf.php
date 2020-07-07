<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/17
 * Time: 下午4:15.
 */

namespace Server\conf;

use Tars\App;
use Tars\config\ConfigServant;
use Tars\client\CommunicatorConfig;

class ENVConf
{
    public static $socketMode = 2;

    protected static $dbConf = null;

    public static function getTarsConf()
    {
        return App::getTarsConfig();
    }

    public static function getLocator()
    {
        $tarsConfig = App::getTarsConfig();
        $tarsClientConfig = $tarsConfig['tars']['application']['client'];
        $locator = $tarsClientConfig['locator'];
        return $locator;
    }

    public static function getDbConf()
    {
        //这里使用了tars平台的配置下发功能，不喜欢的也可以直接写死配置在这里，像 QD.ActCommentServer的EnvConf 一样
        //在tar平台上，找到QD.UserService 点击服务配置，添加配置，文件名db.json，内容：
        //[
        //  {
        //    "host": "mysql.tarsActDemo.local", //这是是你的mysql地址, json不能有注释，这个要去掉
        //    "port": 3306,
        //    "username": "root",
        //    "password": "password",
        //    "db": "tars_test",
        //    "charset": "utf-8",
        //    "instanceName": "default"
        //  }
        //]

        if (self::$dbConf == null) {
            $config = new CommunicatorConfig();
            $config->setLocator(self::getLocator()); //这里配置的是tars主控地址
            $config->setModuleName("QD.UserService"); //主调名字用于显示再主调上报中。
            $config->setCharsetName("UTF-8"); //字符集

            $configServant = new ConfigServant($config);
            $result = $configServant->loadConfig("QD", 'UserService', 'db.json', $dbConfStr);
            //TODO 需要判断$result 是否正常
            $conf = json_decode($dbConfStr, true);
            if (!empty($conf)) {
                self::$dbConf = $conf;
            }
        }

        return self::$dbConf;
    }
}
