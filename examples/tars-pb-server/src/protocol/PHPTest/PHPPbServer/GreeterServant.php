<?php

namespace Protocol\PHPTest\PHPPbServer;

use Helloworld\HelloRequest;
use Helloworld\HelloReply;
interface GreeterServant {
	/**
	 * @param \Helloworld\HelloRequest $inParam
	 * @param \Helloworld\HelloReply $outParam =out=
	 * @return void
	 */
	public function SayHello(HelloRequest $inParam,HelloReply &$outParam);

}

