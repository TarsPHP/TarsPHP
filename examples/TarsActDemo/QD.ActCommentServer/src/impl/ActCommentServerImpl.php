<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/16
 * Time: 15:12
 */

namespace Server\impl;


use Server\conf\Code;
use Server\exception\BusinessException;
use Server\protocol\QD\ActCommentServer\CommentObj\ActCommentServiceServant;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonOutParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\QueryParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment;
use Server\service\CommentService;

class ActCommentServerImpl implements ActCommentServiceServant
{
    /**
     * @return int
     */
    public function ping()
    {
        return 1;
    }

    /**
     * @param int $count =out=
     * @return void
     */
    public function getCount(&$count)
    {
        $count = rand(1, 100);
    }

    /**
     * @param struct $inParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam
     * @param struct $comment \Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment
     * @param struct $outParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonOutParam =out=
     * @return void
     */
    public function createComment(CommonInParam $inParam, SimpleComment $comment, CommonOutParam &$outParam)
    {
        try {
            CommentService::createComment($inParam, $comment);
            self::setCommonOutSuccess($outParam);
        } catch (\Exception $e) {
            self::setCommonOutByException($outParam, $e);
        }
    }

    /**
     * @param struct $inParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam
     * @param struct $queryParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\QueryParam
     * @param struct $outParam \Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonOutParam =out=
     * @param vector $list \TARS_Vector(\Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment) =out=
     * @return void
     */
    public function getComment(CommonInParam $inParam, QueryParam $queryParam, CommonOutParam &$outParam, &$list)
    {
        try {
            CommentService::getComment($queryParam, $list);
            self::setCommonOutSuccess($outParam);
        } catch (\Exception $e) {
            self::setCommonOutByException($outParam, $e);
        }
    }

    protected function setCommonOutSuccess(&$outParam)
    {
        $outParam->code = 0;
        $outParam->message = 'success';
    }

    protected function setCommonOutByException(&$outParam, $e)
    {
        if (get_class($e) == BusinessException::class) {
            var_dump((string) $e); //TODO::相当于log了 需要调整一下
            $outParam->code = $e->getCode();
            $outParam->message = $e->getMessage();
        } else {
            $outParam->code = Code::CODE_FAIL;
            $outParam->message = "System error, check log file";
        }
    }

}
