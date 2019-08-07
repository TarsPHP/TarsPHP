<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2019/8/2
 * Time: 22:09
 */
namespace Server\impl;

use Helloworld\HelloReply;
use Helloworld\HelloRequest;
use Protocol\PHPTest\PHPPbServer\GreeterServant;

class GreeterServantImpl implements GreeterServant
{
    /**
     * @param \Helloworld\HelloRequest $inParam
     * @param \Helloworld\HelloReply $outParam =out=
     * @return void
     */
    public function SayHello(HelloRequest $inParam,HelloReply &$outParam)
    {
        $outParam->setMessage("This is Tars pb server, your msg is: " . $inParam->getName());
    }
}
