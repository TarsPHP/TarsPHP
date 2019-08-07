<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/15
 * Time: 17:23
 */

namespace HttpServer\model;


use HttpServer\conf\Code;
use HttpServer\exception\ActivityException;

use Protocol\QD\ActCommentPbServer\CommentObjServant;
use Protocol\QD\ActCommentPbServer\CountRequest;
use Protocol\QD\ActCommentPbServer\CountResponse;
use Protocol\QD\ActCommentPbServer\CreateRequest;
use Protocol\QD\ActCommentPbServer\CreateResponse;
use Protocol\QD\ActCommentPbServer\CommonInParam;
use Protocol\QD\ActCommentPbServer\GetRequest;
use Protocol\QD\ActCommentPbServer\GetResponse;
use Protocol\QD\ActCommentPbServer\PingRequest;
use Protocol\QD\ActCommentPbServer\PingResponse;
use Protocol\QD\ActCommentPbServer\QueryParam;
use Protocol\QD\ActCommentPbServer\SimpleComment;

class ActCommentPbModel extends BaseModel
{
    protected static function getCommonIn()
    {
        $commonIn = new CommonInParam();
        $commonIn->setUserId(0);
        $commonIn->setAppId(1);
        $commonIn->setAreaId(10);
        $commonIn->setUserIp('');
        $commonIn->setServerIp('127.0.0.1');

        return $commonIn;
    }

    public static function createComment($userId, $activityId, $title, $content)
    {
        try {
            $commonIn = self::getCommonIn();
            $commonIn->setUserId($userId);

            $comment = new SimpleComment();
            $comment->setActivityId($activityId);
            $comment->setTitle($title);
            $comment->setContent($content);

            $inParam = new CreateRequest();
            $inParam->setInParam($commonIn);
            $inParam->setComment($comment);

            $outParam = new CreateResponse();

            $conf = self::getConfig();
            $conf->setSocketMode(4);

            $servant = new CommentObjServant($conf);
            $servant->createComment($inParam, $outParam);

            if ($outParam->getOutParam()->getCode() != 0) {
                //business error
                //TODO log
                throw new ActivityException(Code::COMMENT_MODEL_CREATE_FAILED, $outParam->getOutParam()->getCode());
            }

            return true;
        } catch (ActivityException $e) {
            throw $e;
        } catch (\Exception $e) {
            //TODO log
            throw new ActivityException(Code::COMMENT_MODEL_CONNECT_ERROR, $e->getMessage() . $e->getCode());
        }
    }

    public static function getComment($activityId, $page, $size)
    {
        try {
            $commonIn = self::getCommonIn();

            $query = new QueryParam();
            $query->setActivityId($activityId);
            $query->setPage($page);
            $query->setSize($size);
            $query->setOrderType(1);

            $inParam = new GetRequest();
            $inParam->setInParam($commonIn);
            $inParam->setQueryParam($query);

            $outParam = new GetResponse();

            $conf = self::getConfig();
            $conf->setSocketMode(4);

            $servant = new CommentObjServant($conf);
            $servant->getComment($inParam, $outParam);

            if ($outParam->getOutParam()->getCode() != 0) {
                //business error
                //TODO log
                throw new ActivityException(Code::COMMENT_MODEL_CREATE_FAILED, $outParam->getOutParam()->getCode());
            }

            $lists = $outParam->getList();
            $out = [];
            foreach ($lists as $list) {
                $out[] = [
                    'id' => $list->getId(),
                    'activityId' => $list->getActivityId(),
                    'userId' => $list->getUserId(),
                    'content' => $list->getContent(),
                    'title' => $list->getTitle(),
                    'ext1' => $list->getExt1(),
                    'createTime' => $list->getCreateTime(),
                ];
            }

            return $out;
        } catch (ActivityException $e) {
            throw $e;
        } catch (\Exception $e) {
            //TODO log
            throw new ActivityException(Code::COMMENT_MODEL_CONNECT_ERROR, $e->getMessage() . $e->getCode());
        }
    }

    public static function ping()
    {
        $inParam = new PingRequest();
        $outParam = new PingResponse();

        $conf = self::getConfig();
        $conf->setSocketMode(4);

        $servant = new CommentObjServant($conf);
        $servant->ping($inParam, $outParam);
    }

    public static function getCount()
    {
        $inParam = new CountRequest();
        $outParam = new CountResponse();

        $conf = self::getConfig();
        $conf->setSocketMode(4);

        $servant = new CommentObjServant($conf);
        $servant->getCommentCount($inParam, $outParam);

        return $outParam->getCount();
    }
}
