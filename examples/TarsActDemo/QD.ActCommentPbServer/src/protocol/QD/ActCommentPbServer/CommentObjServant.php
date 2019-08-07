<?php

namespace Protocol\QD\ActCommentPbServer;

use Protocol\QD\ActCommentPbServer\PingRequest;
use Protocol\QD\ActCommentPbServer\PingResponse;
use Protocol\QD\ActCommentPbServer\CountRequest;
use Protocol\QD\ActCommentPbServer\CountResponse;
use Protocol\QD\ActCommentPbServer\CreateRequest;
use Protocol\QD\ActCommentPbServer\CreateResponse;
use Protocol\QD\ActCommentPbServer\GetRequest;
use Protocol\QD\ActCommentPbServer\GetResponse;
interface CommentObjServant {
	/**
	 * @param \Protocol\QD\ActCommentPbServer\PingRequest $inParam
	 * @param \Protocol\QD\ActCommentPbServer\PingResponse $outParam =out=
	 * @return void
	 */
	public function ping(PingRequest $inParam,PingResponse &$outParam);

	/**
	 * @param \Protocol\QD\ActCommentPbServer\CountRequest $inParam
	 * @param \Protocol\QD\ActCommentPbServer\CountResponse $outParam =out=
	 * @return void
	 */
	public function getCommentCount(CountRequest $inParam,CountResponse &$outParam);

	/**
	 * @param \Protocol\QD\ActCommentPbServer\CreateRequest $inParam
	 * @param \Protocol\QD\ActCommentPbServer\CreateResponse $outParam =out=
	 * @return void
	 */
	public function createComment(CreateRequest $inParam,CreateResponse &$outParam);

	/**
	 * @param \Protocol\QD\ActCommentPbServer\GetRequest $inParam
	 * @param \Protocol\QD\ActCommentPbServer\GetResponse $outParam =out=
	 * @return void
	 */
	public function getComment(GetRequest $inParam,GetResponse &$outParam);

}

