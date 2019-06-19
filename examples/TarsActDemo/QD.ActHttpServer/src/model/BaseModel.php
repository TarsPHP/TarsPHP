<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 17:30
 */

namespace HttpServer\model;


use HttpServer\conf\ENVConf;
use Tars\client\CommunicatorConfig;

class BaseModel
{
    public static function getConfig()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getTarsConf()['tars']['application']['client']['locator']);
        $config->setModuleName('QD.ActHttpServer');
        $config->setSocketMode(3);

        return $config;
    }
}
