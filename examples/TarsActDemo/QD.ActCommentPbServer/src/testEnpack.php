<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2019/8/2
 * Time: 17:34
 */
require_once __DIR__ . '/vendor/autoload.php';

use \Protocol\QD\ActCommentPbServer\CommonInParam;
use \Protocol\QD\ActCommentPbServer\QueryParam;

$t = microtime(true) * 1000;
$i = 0;
do {
    $commonIn = new CommonInParam();
    $commonIn->setUserId(0);
    $commonIn->setAppId(1);
    $commonIn->setAreaId(10);
    $commonIn->setServerIp('127.0.0.1');
    $commonIn->setUserIp('');
    $buf1 = $commonIn->serializeToString();

    $commonIn2 = new CommonInParam();
    $commonIn2->mergeFromString($buf1);


    $query = new QueryParam();
    $query->setActivityId(123);
    $query->setPage(1);
    $query->setSize(10);
    $query->setOrderType(1);
    $buf2 = $query->serializeToString();

    $query2 = new QueryParam();
    $query2->mergeFromString($buf2);

    $i++;
} while ($i < 10000);

$t2 = microtime(true) * 1000;
var_dump("time is " . ($t2 - $t));

var_dump(strlen($buf1) + strlen($buf2));

