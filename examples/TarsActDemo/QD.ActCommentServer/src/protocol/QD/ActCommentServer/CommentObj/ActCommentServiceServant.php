<?php

namespace Server\protocol\QD\ActCommentServer\CommentObj;

use Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonOutParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\QueryParam;
interface ActCommentServiceServant {
	/**
	 * @return int
	 */
	public function ping();
	/**
	 * @param int $count =out=
	 * @return void
	 */
	public function getCount(&$count);
	/**
	 * @param struct $inParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam
	 * @param struct $comment \Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment
	 * @param struct $outParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonOutParam =out=
	 * @return void
	 */
	public function createComment(CommonInParam $inParam,SimpleComment $comment,CommonOutParam &$outParam);
	/**
	 * @param struct $inParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam
	 * @param struct $queryParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\QueryParam
	 * @param struct $outParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonOutParam =out=
	 * @param vector $list \TARS_Vector(\Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment) =out=
	 * @return void
	 */
	public function getComment(CommonInParam $inParam,QueryParam $queryParam,CommonOutParam &$outParam,&$list);
}

