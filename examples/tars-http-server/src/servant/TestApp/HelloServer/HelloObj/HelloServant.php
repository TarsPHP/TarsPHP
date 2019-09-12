<?php

namespace HttpServer\servant\TestApp\HelloServer\HelloObj;

use Tars\client\CommunicatorConfig;
use Tars\client\Communicator;
use Tars\client\RequestPacket;
use Tars\client\TUPAPIWrapper;

class HelloServant {
	protected $_communicator;
	protected $_iVersion;
	protected $_iTimeout;
	public $_servantName = "TestApp.HelloServer.HelloObj";
	public $_contexts = [];
	public $_statuses = [];

	public function __construct(CommunicatorConfig $config) {
		try {
			$config->setServantName($this->_servantName);
			$this->_communicator = new Communicator($config);
			$this->_iVersion = $config->getIVersion();
			$this->_iTimeout = empty($config->getAsyncInvokeTimeout())?2:$config->getAsyncInvokeTimeout();
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function hello($no,$name) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$requestPacket->_contexts = $this->_contexts;
			$requestPacket->_statuses = $this->_statuses;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("no",1,$no,$this->_iVersion);
			$encodeBufs['no'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("name",2,$name,$this->_iVersion);
			$encodeBufs['name'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			return TUPAPIWrapper::getString("",0,$sBuffer,$this->_iVersion);

		}
		catch (\Exception $e) {
			throw $e;
		}
	}

}

