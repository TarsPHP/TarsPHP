<?php

namespace Protocol\QD\ActCommentPbServer;

use Tars\client\CommunicatorConfig;
use Tars\client\Communicator;
use Tars\client\grpc\GrpcRequestPacket;
use Tars\client\grpc\GrpcResponsePacket;

class CommentObjServant
{
	protected $_communicator;
	protected $_iTimeout;
	public $_servantName = "QD.ActCommentPbServer.CommentObj";
	public $_basePath = "/protocol.QD.ActCommentPbServer.CommentObj/";

	public function __construct(CommunicatorConfig $config) {
		try {
			$config->setServantName($this->_servantName);
			$this->_communicator = new Communicator($config);
			$this->_iTimeout = empty($config->getAsyncInvokeTimeout())?2:$config->getAsyncInvokeTimeout();
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function ping(PingRequest $inParam,PingResponse &$outParam)
	{
		try {
			$requestPacket = new GrpcRequestPacket();
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$requestPacket->_basePath = $this->_basePath;
			$requestPacket->_sBuffer = $inParam->serializeToString();

			$responsePacket = new GrpcResponsePacket();
			$sBuffer = $this->_communicator->invoke($requestPacket, $this->_iTimeout, $responsePacket);

			$outParam = new PingResponse();
			$outParam->mergeFromString($sBuffer);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function getCommentCount(CountRequest $inParam,CountResponse &$outParam)
	{
		try {
			$requestPacket = new GrpcRequestPacket();
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$requestPacket->_basePath = $this->_basePath;
			$requestPacket->_sBuffer = $inParam->serializeToString();

			$responsePacket = new GrpcResponsePacket();
			$sBuffer = $this->_communicator->invoke($requestPacket, $this->_iTimeout, $responsePacket);

			$outParam = new CountResponse();
			$outParam->mergeFromString($sBuffer);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function createComment(CreateRequest $inParam,CreateResponse &$outParam)
	{
		try {
			$requestPacket = new GrpcRequestPacket();
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$requestPacket->_basePath = $this->_basePath;
			$requestPacket->_sBuffer = $inParam->serializeToString();

			$responsePacket = new GrpcResponsePacket();
			$sBuffer = $this->_communicator->invoke($requestPacket, $this->_iTimeout, $responsePacket);

			$outParam = new CreateResponse();
			$outParam->mergeFromString($sBuffer);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function getComment(GetRequest $inParam,GetResponse &$outParam)
	{
		try {
			$requestPacket = new GrpcRequestPacket();
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$requestPacket->_basePath = $this->_basePath;
			$requestPacket->_sBuffer = $inParam->serializeToString();

			$responsePacket = new GrpcResponsePacket();
			$sBuffer = $this->_communicator->invoke($requestPacket, $this->_iTimeout, $responsePacket);

			$outParam = new GetResponse();
			$outParam->mergeFromString($sBuffer);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

}

