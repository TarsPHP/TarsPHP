<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/16
 * Time: 15:12
 */

namespace Server\impl;


use Protocol\QD\ActCommentPbServer\CommonOutParam;
use Protocol\QD\ActCommentPbServer\CountRequest;
use Protocol\QD\ActCommentPbServer\CountResponse;
use Protocol\QD\ActCommentPbServer\CreateRequest;
use Protocol\QD\ActCommentPbServer\CreateResponse;
use Protocol\QD\ActCommentPbServer\GetRequest;
use Protocol\QD\ActCommentPbServer\GetResponse;
use Protocol\QD\ActCommentPbServer\CommentObjServant;
use Protocol\QD\ActCommentPbServer\PingRequest;
use Protocol\QD\ActCommentPbServer\PingResponse;
use Server\conf\Code;
use Server\exception\BusinessException;
use Server\service\CommentService;

class ActCommentServerImpl implements CommentObjServant
{
    /**
     * @param \Protocol\QD\ActCommentPbServer\PingRequest $inParam
     * @param \Protocol\QD\ActCommentPbServer\PingResponse $outParam =out=
     * @return void
     */
    public function ping(PingRequest $inParam,PingResponse &$outParam)
    {
        $outParam = new PingResponse();
    }

    /**
     * @param \Protocol\QD\ActCommentPbServer\PingRequest $inParam
     * @param \Protocol\QD\ActCommentPbServer\CountResponse $outParam =out=
     * @return void
     */
    public function getCommentCount(CountRequest $inParam,CountResponse &$outParam)
    {
        $outParam->setCount(rand(1, 100));
    }

    /**
     * @param \Protocol\QD\ActCommentPbServer\CreateRequest $inParam
     * @param \Protocol\QD\ActCommentPbServer\CreateResponse $outParam =out=
     * @return void
     */
    public function createComment(CreateRequest $inParam, CreateResponse &$outParam)
    {
        $commonOut = new CommonOutParam();
        try {
            CommentService::createComment($inParam->getInParam(), $inParam->getComment());
            self::setCommonOutSuccess($commonOut);
            $outParam->setOutParam($commonOut);
        } catch (\Exception $e) {
            self::setCommonOutByException($commonOut, $e);
            $outParam->setOutParam($commonOut);
        }
    }

    /**
     * @param \Protocol\QD\ActCommentPbServer\GetRequest $inParam
     * @param \Protocol\QD\ActCommentPbServer\GetResponse $outParam =out=
     * @return void
     */
    public function getComment(GetRequest $inParam, GetResponse &$outParam)
    {
        $commonOut = new CommonOutParam();
        try {
            CommentService::getComment($inParam->getQueryParam(), $list);
            self::setCommonOutSuccess($commonOut);
            $outParam->setOutParam($commonOut);
            $outParam->setList($list);
        } catch (\Exception $e) {
            self::setCommonOutByException($outParam, $e);
            $outParam->setOutParam($commonOut);
        }
    }

    /**
     * @param CommonOutParam $outParam
     */
    protected function setCommonOutSuccess(&$outParam)
    {
        $outParam->setCode(0);
        $outParam->setMessage('success');
    }

    protected function setCommonOutByException(&$outParam, $e)
    {
        if (get_class($e) == BusinessException::class) {
            var_dump((string) $e); //TODO::相当于log了 需要调整一下
            $outParam->setCode($e->getCode());
            $outParam->setMessage($e->getMessage());
        } else {
            $outParam->setCode(Code::CODE_FAIL);
            $outParam->setMessage("System error, check log file");
        }
    }

}
