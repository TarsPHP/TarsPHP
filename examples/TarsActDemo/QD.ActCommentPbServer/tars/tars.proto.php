<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午3:43.
 */

return array(
    'appName' => 'QD',
    'serverName' => 'ActCommentPbServer',
    'objName' => 'CommentObj',
    'withServant' => true, //决定是服务端,还是客户端的自动生成
    'tarsFiles' => array(
        './actComment.proto',
    ),
    'dstPath' => '../src/protocol', //这里指定的是 impl 基础interface 生成的位置
    'protocDstPath' => '../src', //这里指定的是 protoc 生成的问题
    'namespacePrefix' => 'Protocol',
);
