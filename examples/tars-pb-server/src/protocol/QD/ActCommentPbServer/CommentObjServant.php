<?php

namespace Protocol\QD\ActCommentPbServer;

use Protocol\QD\ActCommentPbServer\CreateRequest;
use Protocol\QD\ActCommentPbServer\CreateResponse;
use Protocol\QD\ActCommentPbServer\GetRequest;
use Protocol\QD\ActCommentPbServer\GetResponse;
interface CommentObjServant {
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

