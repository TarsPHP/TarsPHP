<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/8
 * Time: 下午2:42.
 */

namespace HttpServer\controller;

use HttpServer\component\Controller;
use HttpServer\conf\ENVConf;
use HttpServer\handler\TarsHandler;
use HttpServer\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct;
use HttpServer\servant\PHPTest\PHPServer\obj\classes\LotofTags;
use HttpServer\servant\PHPTest\PHPServer\obj\classes\OutStruct;
use HttpServer\servant\PHPTest\PHPServer\obj\classes\SimpleStruct;
use HttpServer\servant\PHPTest\PHPServer\obj\TestTafServiceServant;
use HttpServer\servant\TestApp\HelloServer\HelloObj\HelloServant;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Tars\App;
use Tars\client\CommunicatorConfig;

class IndexController extends Controller
{
    // curl "node_ip:port/Index/index" -i
    public function actionIndex()
    {
        $this->cookie('key', 1, 10000000, '/', 'www.github.com');
        $this->sendRaw('success');
    }

    // curl "node_ip:port/Index/testHeader" -i
    public function actionTestHeader()
    {
        $this->header('test', 1111);
    }

    // curl "node_ip:port/Index/testStatus" -i
    public function actionTestStatus()
    {
        $this->status(401);
    }

    // Get请求
    // curl "node_ip:port/Index/get?param=1111&c=d&d=e&e=f" -i
    public function actionGet()
    {
        $param = $this->request->data['get']['param'];

        $this->sendRaw('success:'.$param);
    }

    // Post请求
    // curl -d "user=admin&passwd=12345678"  "node_ip:port/Index/post1" -i
    public function actionPost1()
    {
        // 对于content-type为application/x-www-form-urlencoded 的form data
        $admin = $this->request->data['post']['user'];

        $this->sendRaw('success:'.$admin);
    }

    // Post请求
    //curl -H "Content-Type:application/json" -X POST -d '{"user": "admin", "passwd":"12345678"}' "node_ip:port/Index/post2" -i
    public function actionPost2()
    {
        // 对于对于content-type为application/json
        $json = $this->request->data['post'];
        $admin = json_decode($json, true)['user'];

        $this->sendRaw('success:'.$admin);
    }

    // Get请求
    // curl -F "image=@profile.jpeg" -F "phone=123456789"  "node_ip:port/Index/file" -i
    public function actionFile()
    {
        $fileName = $this->request->data['files']['image']['name'];
        $type = $this->request->data['files']['image']['type'];
        $tmp_name = $this->request->data['files']['image']['tmp_name'];
        $size = $this->request->data['files']['image']['size'];

        $this->sendRaw('success:'.var_export($this->request->data['files']['image'], true));
    }

    // curl "node_ip:port/Index/TestProperty?a=b" -i
    public function actionTestProperty()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);
        $result = $servant->testProperty();

        $this->sendRaw('result:'.$result);
    }

    // curl "node_ip:port/Index/TestLotofTags?a=b" -i
    public function actionTestLotofTags()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);
        $tags = new LotofTags();
        $tags->id = 999;
        $outTags = new LotofTags();

        $result = $servant->testLofofTags($tags, $outTags);

        $this->sendRaw(json_encode($outTags, JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestBasic?a=b" -i
    public function actionTestBasic()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);
        $ret = $servant->testBasic(true, 1, '333', $d, $e, $f);

        $this->sendRaw(json_encode([$d, $e, $f, $ret], JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestStruct?a=b" -i
    public function actionTestStruct()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);

        $b = new SimpleStruct();
        $b->count = 1098;

        $d = new OutStruct();

        $str = $servant->testStruct(100, $b, $d);

        $this->sendRaw(json_encode([$d, $str], JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestMap?a=b" -i
    public function actionTestMap()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        App::getLogger()->debug("config" . var_export($config,true));

        $servant = new TestTafServiceServant($config);

        $b = new SimpleStruct();
        $b->count = 1098;

        $m1 = ['a' => 'b'];

        $d = new OutStruct();
        $result = $servant->testMap(88, $b, $m1, $d, $m2);

        $this->sendRaw('result:'.$result . ' : ' .json_encode(compact('d', 'm2')));
    }

    // curl "node_ip:port/Index/TestVector?a=b" -i
    public function actionTestVector()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);
        $v1 = ['hahaha'];

        $simpleStruct = new SimpleStruct();
        $simpleStruct->count = 1098;
        $v2 = [$simpleStruct];

        $result = $servant->testVector(999, $v1, $v2, $v3, $v4);

        $this->sendRaw(json_encode([$v3, $v4], JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestReturn?a=b" -i
    public function actionTestReturn()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);
        $simpleStruct = $servant->testReturn();

        $this->sendRaw(json_encode($simpleStruct, JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestReturn2?a=b" -i
    public function actionTestReturn2()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);
        $map = $servant->testReturn2();

        $this->sendRaw(json_encode($map, JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestComplicatedStruct?a=b" -i
    public function actionTestComplicatedStruct()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);

        $cs = new ComplicatedStruct();
        $cs->str = '111';
        $b = new SimpleStruct();
        $b->count = 1098;
        $cs->mss->pushBack(['a' => $b]);
        $cs->rs = $b;

        $vcs = [$cs];
        $ocs = new ComplicatedStruct();

        $result = $servant->testComplicatedStruct($cs, $vcs, $ocs, $ovcs);

        $this->sendRaw('result:'.json_encode(compact('result', 'ocs', 'ovcs')));
    }

    // curl "node_ip:port/Index/TestComplicatedMap?a=b" -i
    public function actionTestComplicatedMap()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);

        $cs = new ComplicatedStruct();
        $cs->str = '111';
        $b = new SimpleStruct();
        $b->count = 1098;
        $cs->mss->pushBack(['a' => $b]);
        $cs->rs = $b;

        $mcs = ['test' => $cs];
        $result = $servant->testComplicatedMap($mcs, $omcs);

        $this->sendRaw(json_encode($omcs, JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestEmpty?a=b" -i
    public function actionTestEmpty()
    {
        $config = new CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new TestTafServiceServant($config);

        $d = new OutStruct();
        $ret = $servant->testEmpty(111, $b1, $in2, $d, $v3, $v4);

        $this->sendRaw(json_encode([$ret], JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestConf?a=b" -i
    public function actionTestConf()
    {
        $tarsConf = ENVConf::getTarsConf();
        $this->sendRaw(json_encode($tarsConf, JSON_UNESCAPED_UNICODE));
    }

    // curl "node_ip:port/Index/TestRemoteLog?a=b" -i
    public function actionTestRemoteLog()
    {
        $config = new \Tars\client\CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setCharsetName('UTF-8');

        $logServant = new \Tars\log\LogServant($config);
        $logServant->logger('PHPTest', 'PHPHttpServer', 'ted.log', '%Y%m%d', ['hahahahaha']);

        $this->sendRaw('wrote hahahahaha to ted.log');
    }

    // curl "node_ip:port/Index/TestMonolog?a=b" -i
    public function actionTestMonolog()
    {
        $config = new \Tars\client\CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('PHPTest.PHPHttpServer');
        $config->setCharsetName('UTF-8');

        $logger = new Logger("tars_logger");
        $tarsHandler = new \HttpServer\handler\TarsHandler($config);
        //local log
        $streamHandler = new StreamHandler(ENVConf::getLogPath() . "/" . __CLASS__  . ".log");

        $logger->pushHandler($tarsHandler);
        $logger->pushHandler($streamHandler);

        $array = [
            "key1" => "value1",
            "key2" => "value2",
            "key3" => "value3"
        ];
        $logger->debug("add a debug message", $array);
        $logger->info("add a info message", $array);
        $logger->notice("add a notice message", $array);
        $logger->warning("add a warning message", $array);
        $logger->error("add a error message", $array);
        $logger->critical("add a critical message", $array);
        $logger->emergency("add a emergency message", $array);
        $this->sendRaw(json_encode(['code' => 0], JSON_UNESCAPED_UNICODE));
    }

    /**
     * 要测试这个方法需要部署java的example服务，需要和java通讯，服务是 TestApp.HelloServer.HelloObj
     * @throws \Exception
     */
    public function actionTestJava()
    {
        $config = new \Tars\client\CommunicatorConfig();
        $config->setLocator(ENVConf::getLocator());
        $config->setModuleName('TestApp.HelloServer');
        $config->setCharsetName('UTF-8');
        $config->setSocketMode(ENVConf::$socketMode);

        $servant = new HelloServant($config);

        $str = $servant->hello(1, 'test');

        $this->sendRaw(json_encode($str, JSON_UNESCAPED_UNICODE));
    }
}
