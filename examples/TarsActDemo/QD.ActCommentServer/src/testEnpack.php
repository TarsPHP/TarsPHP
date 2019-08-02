<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2019/8/2
 * Time: 17:34
 */
require_once __DIR__ . '/vendor/autoload.php';

use Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\QueryParam;

$respBuf = base64_decode("CAACBgdpblBhcmFtHQAAFAoAARAKLDYARgkxMjcuMC4wLjELBgpxdWVyeVBhcmFtHQAACgoAexABIAowAQs=");

$t = microtime(true) * 1000;
$i = 0;
do {
    $commonIn = new CommonInParam();
    $commonIn->userId = 0;
    $commonIn->appId = 1;
    $commonIn->areaId = 10;
    $commonIn->serverIp = '127.0.0.1';
    $commonIn->userIp = '';
    $buf1 = \TUPAPI::putStruct("inParam", $commonIn);

    $query = new QueryParam();
    $query->activityId = 123;
    $query->page = 1;
    $query->size = 10;
    $query->orderType = 1;
    $buf2 = \TUPAPI::putStruct("queryParam", $query);

    $commonIn2 = new CommonInParam();
    $ret = \TUPAPI::getStruct("inParam", $commonIn2, $respBuf);

    $query2 = new QueryParam();
    $ret = \TUPAPI::getStruct("queryParam", $query2, $respBuf);

    $i++;
} while ($i < 10000);

$t2 = microtime(true) * 1000;
var_dump("time is " . ($t2 - $t));

var_dump(strlen($buf1) + strlen($buf2));
