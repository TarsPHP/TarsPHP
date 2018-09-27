<?php

namespace HttpServer\protocol\QD\UserService\UserObj;

use Tars\client\CommunicatorConfig;
use Tars\client\Communicator;
use Tars\client\RequestPacket;
use Tars\client\TUPAPIWrapper;

use HttpServer\protocol\QD\UserService\UserObj\classes\CommonInParam;
use HttpServer\protocol\QD\UserService\UserObj\classes\CommonOutParam;
use HttpServer\protocol\QD\UserService\UserObj\classes\User;
class UserServiceServant {
	protected $_communicator;
	protected $_iVersion;
	protected $_iTimeout;
	public $_servantName = "QD.UserService.UserObj";

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

	public function ping() {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			return TUPAPIWrapper::getInt32("",0,$sBuffer,$this->_iVersion);

		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function getUsersInfoByIds(CommonInParam $inParam,$usersId,CommonOutParam &$outParam,&$info) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParam",1,$inParam,$this->_iVersion);
			$encodeBufs['inParam'] = $__buffer;
			$usersId_vec = new \TARS_Vector(\TARS::INT64);
			foreach($usersId as $singleusersId) {
				$usersId_vec->pushBack($singleusersId);
			}
			$__buffer = TUPAPIWrapper::putVector("usersId",2,$usersId_vec,$this->_iVersion);
			$encodeBufs['usersId'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParam",3,$outParam,$sBuffer,$this->_iVersion);
			$info = TUPAPIWrapper::getMap("info",4,new \TARS_Map(\TARS::INT64,new User()),$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function checkSession(CommonInParam $inParam,$userId,$sessionKey,CommonOutParam &$outParam) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParam",1,$inParam,$this->_iVersion);
			$encodeBufs['inParam'] = $__buffer;
			$__buffer = TUPAPIWrapper::putInt64("userId",2,$userId,$this->_iVersion);
			$encodeBufs['userId'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("sessionKey",3,$sessionKey,$this->_iVersion);
			$encodeBufs['sessionKey'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParam",4,$outParam,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function login(CommonInParam $inParam,$nickname,$password,CommonOutParam &$outParam,&$userId,&$sessionKey) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParam",1,$inParam,$this->_iVersion);
			$encodeBufs['inParam'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("nickname",2,$nickname,$this->_iVersion);
			$encodeBufs['nickname'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("password",3,$password,$this->_iVersion);
			$encodeBufs['password'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParam",4,$outParam,$sBuffer,$this->_iVersion);
			$userId = TUPAPIWrapper::getInt64("userId",5,$sBuffer,$this->_iVersion);
			$sessionKey = TUPAPIWrapper::getString("sessionKey",6,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

}

