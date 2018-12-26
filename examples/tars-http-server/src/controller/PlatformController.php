<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/8
 * Time: 下午2:42.
 */

namespace HttpServer\controller;

use HttpServer\component\Controller;
use Tars\App;

class PlatformController extends Controller
{

    /**
     * 测试直接获取平台的配置
     * curl "http://172.17.0.3:20001/platform/testTarsConfig"
     */
    public function actionTestTarsConfig() {
        $tarsConfig = App::getTarsConfig();

        $tarsServerConfig = $tarsConfig['tars']['application']['server'];
        $tarsClientConfig = $tarsConfig['tars']['application']['client'];

        // server侧的配置举例
        $application = $tarsServerConfig['app'];
        $serverName = $tarsServerConfig['server'];

        $host = empty($tarsServerConfig['listen'][0]['bIp'])
            ? $tarsServerConfig['listen'][0]['sHost']
            : $tarsServerConfig['listen'][0]['bIp'];
        $port = $tarsServerConfig['listen'][0]['iPort'];

        $setting = $tarsServerConfig['setting'];

        $protocolName = $tarsServerConfig['protocolName'];
        $servType = $tarsServerConfig['servType'];
        $worker_num = $setting['worker_num'];

        // client侧的配置举例
        $locator = $tarsClientConfig['locator'];
        $interval           =   $tarsClientConfig['report-interval'];
        $statServantName    =   $tarsClientConfig['stat'];


        // 请求结束
        $this->response->send("tarsConfig:". json_encode($tarsConfig, JSON_UNESCAPED_SLASHES));
    }

    /**
     * 测试server附带的日志服务
     * curl "http://172.17.0.3:20001/platform/testLogger"
     * 请检查logPath下的文件
     */
    public function actionTestLogger() {
        App::getLogger()->debug("Test debug");
        App::getLogger()->info("Test info");
        App::getLogger()->notice("Test notice");
        App::getLogger()->warning("Test warning");
        App::getLogger()->error("Test error");
        App::getLogger()->critical("Test critical");
        // 请求结束
        $this->response->send("Test logger finish");
    }

    /**
     * 测试从平台上拉取配置
     * 1. 预先在平台上的服务配置TAB,新建一个名为test.conf的配置文件
     * 2. 输入任意配置内容
     * curl "http://172.17.0.3:20001/platform/testConfig"
     */
    public function actionTestConfig() {
        $tarsConfig = App::getTarsConfig();
        $tarsServerConfig = $tarsConfig['tars']['application']['server'];
        $tarsClientConfig = $tarsConfig['tars']['application']['client'];

        $application = $tarsServerConfig['app'];
        $serverName = $tarsServerConfig['server'];

        // 拉取平台上的名为test.conf的配置文件
        App::getConfigF()->loadConfig($application, $serverName, "test.conf", $configContent);
        // 请求结束
        $this->response->send("Fetch config Content:" . $configContent);
    }
}
