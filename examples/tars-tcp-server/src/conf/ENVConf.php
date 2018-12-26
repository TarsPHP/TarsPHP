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
    /**
     * @return mixed
     * 获取当前环境的主控配置
     */
    public static function getLocator() {
        $tarsConfig = App::getTarsConfig();

        $tarsClientConfig = $tarsConfig['tars']['application']['client'];

        $locator = $tarsClientConfig['locator'];

        return $locator;
    }

    public static $socketMode = 2;

    public static function getTarsConf()
    {
        return App::getTarsConfig();
    }
}
