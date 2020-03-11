<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午4:58.
 */

namespace Server\impl;

use Server\servant\PHPTest\PHPServer\obj\TestTafServiceServant;
use Server\servant\PHPTest\PHPServer\obj\classes\LotofTags;
use Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct;
use Server\servant\PHPTest\PHPServer\obj\classes\OutStruct;
use Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct;
use Tars\App;
use Tars\client\CommunicatorConfig;
use Tars\monitor\PropertyFWrapper;
use Server\conf\ENVConf;

class PHPServerServantImpl implements TestTafServiceServant
{
    public function testTafServer()
    {
        App::getLogger()->info('testTafServer test');
    }
    /**
     * @param struct $tags    \Server\servant\PHPTest\PHPServer\obj\classes\LotofTags
     * @param struct $outtags \Server\servant\PHPTest\PHPServer\obj\classes\LotofTags =out=
     *
     * @return int
     */
    public function testLofofTags(LotofTags $tags, LotofTags &$outtags)
    {
        App::getLogger()->info('testLofofTags tags:'.var_export($tags, true));
        $outtags->count = 100;

        return 999;
    }
    /**
     * @param string $name
     * @param string $outGreetings =out=
     */
    public function sayHelloWorld($name, &$outGreetings)
    {
        App::getLogger()->info('sayHelloWorld name:'.$name);
        $outGreetings = 'hello world!';
    }
    /**
     * @param bool   $a
     * @param int    $b
     * @param string $c
     * @param bool   $d =out=
     * @param int    $e =out=
     * @param string $f =out=
     *
     * @return int
     */
    public function testBasic($a, $b, $c, &$d, &$e, &$f)
    {
        App::getLogger()->info('testBasic a:'.$a);
        App::getLogger()->info('testBasic b:'.$b);
        App::getLogger()->info('testBasic c:'.$c);

        $d = false;
        $e = 111;
        $f = 'here i am';

        return -99;
    }
    /**
     * @param long   $a
     * @param struct $b \Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct
     * @param struct $d \Server\servant\PHPTest\PHPServer\obj\classes\OutStruct =out=
     *
     * @return string
     */
    public function testStruct($a, SimpleStruct $b, OutStruct &$d)
    {
        App::getLogger()->info('testStruct a:'.$a);
        App::getLogger()->info('testStruct b:'.var_export($b, true));

        $d->id = 10000;

        return 'hahaha';
    }
    /**
     * @param short  $a
     * @param struct $b  \Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct
     * @param map    $m1 \TARS_Map(\TARS::STRING,\TARS::STRING)
     * @param struct $d  \Server\servant\PHPTest\PHPServer\obj\classes\OutStruct =out=
     * @param map    $m2 \TARS_Map(\TARS::INT32,\Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct) =out=
     *
     * @return string
     */
    public function testMap($a, SimpleStruct $b, $m1, OutStruct &$d, &$m2)
    {
        App::getLogger()->info('testMap a:'.$a);
        App::getLogger()->info('testMap b:'.var_export($b, true));
        App::getLogger()->info('testMap m1:'.var_export($m1, true));

        $d->page = 1024;
        $simpleStruct = new SimpleStruct();
        $simpleStruct->id = 888;

        $m2->push_back([1 => $simpleStruct]);

        return 'success';
    }
    /**
     * @param int    $a
     * @param vector $v1 \TARS_Vector(\TARS::STRING)
     * @param vector $v2 \TARS_Vector(\Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct)
     * @param vector $v3 \TARS_Vector(\TARS::INT32) =out=
     * @param vector $v4 \TARS_Vector(\Server\servant\PHPTest\PHPServer\obj\classes\OutStruct) =out=
     *
     * @return string
     */
    public function testVector($a, $v1, $v2, &$v3, &$v4)
    {
        App::getLogger()->info('testVector a:'.$a);
        App::getLogger()->info('testVector v1:'.var_export($v1, true));
        App::getLogger()->info('testVector v2:'.var_export($v2, true));

        $v3->pushBack(111);
        $v3->pushBack(222);

        $outStruct = new OutStruct();
        $outStruct->id = 888;
        $v4->pushBack($outStruct);

        $v4->pushBack($outStruct);

        return 'not very happy';
    }
    /**
     * @return struct \Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct
     */
    public function testReturn()
    {
        App::getLogger()->info('testReturn:');

        $simpleStruct = new SimpleStruct();
        $simpleStruct->id = 888;

        return $simpleStruct;
    }
    /**
     * @return map \TARS_Map(\TARS::STRING,\TARS::STRING)
     */
    public function testReturn2()
    {
        App::getLogger()->info('testReturn2:');

        $map = new \TARS_Map(\TARS::STRING, \TARS::STRING);
        $map->pushBack(['test', 'test']);

        return $map;
    }
    /**
     * @param struct $cs   \Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct
     * @param vector $vcs  \TARS_Vector(\Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct)
     * @param struct $ocs  \Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct =out=
     * @param vector $ovcs \TARS_Vector(\Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct) =out=
     *
     * @return vector \TARS_Vector(\Server\servant\PHPTest\PHPServer\obj\classes\SimpleStruct)
     */
    public function testComplicatedStruct(ComplicatedStruct $cs, $vcs, ComplicatedStruct &$ocs, &$ovcs)
    {
        App::getLogger()->info('testComplicatedStruct: cs'.var_export($cs, true));
        App::getLogger()->info('testComplicatedStruct: vcs'.var_export($vcs, true));

        $ocs = new ComplicatedStruct();
        $ocs->str = 'sss';
        $sim = new SimpleStruct();
        $sim->id = 888;
        $ocs->ss->pushBack($sim);

        $ovcs->pushBack($ocs);

        $result = new \TARS_Vector(new SimpleStruct());
        $simpleStruct = new SimpleStruct();
        $simpleStruct->id = 888;
        $result->pushBack($simpleStruct);

        return $result;
    }
    /**
     * @param map $mcs  \TARS_Map(\TARS::STRING,\Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct)
     * @param map $omcs \TARS_Map(\TARS::STRING,\Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct) =out=
     *
     * @return map \TARS_Map(\TARS::STRING,\Server\servant\PHPTest\PHPServer\obj\classes\ComplicatedStruct)
     */
    public function testComplicatedMap($mcs, &$omcs)
    {
        App::getLogger()->info('testComplicatedMap: mcs'.var_export($mcs, true));

        $com = new ComplicatedStruct();
        $com->str = 'sss';
        $sim = new SimpleStruct();
        $sim->id = 888;
        $com->ss->pushBack($sim);

        $omcs->pushBack(['key' => $com]);

        return $omcs;
    }
    /**
     * @param short  $a
     * @param bool   $b1  =out=
     * @param int    $in2 =out=
     * @param struct $d   \Server\servant\PHPTest\PHPServer\obj\classes\OutStruct =out=
     * @param vector $v3  \TARS_Vector(\Server\servant\PHPTest\PHPServer\obj\classes\OutStruct) =out=
     * @param vector $v4  \TARS_Vector(\TARS::INT32) =out=
     *
     * @return int
     */
    public function testEmpty($a, &$b1, &$in2, OutStruct &$d, &$v3, &$v4)
    {
        App::getLogger()->info('testEmpty a:'.$a);

        return -99;
    }

    /**
     * @return int
     */
    public function testProperty()
    {
        $property = new PropertyFWrapper(ENVConf::getLocator(), 2, 'PHPTest.PHPServer');
        $property->monitorProperty('127.0.0.1', 'test', 'MAX', 1);
        $property->monitorProperty('127.0.0.1', 'test', 'COUNT', 1);
        $property->monitorProperty('127.0.0.1', 'test', 'MIN', 3);
    }
}
