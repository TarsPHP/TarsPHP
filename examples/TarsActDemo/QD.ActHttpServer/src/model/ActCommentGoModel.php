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
use HttpServer\protocol\QD\ActCommentGoService\CommentObj\ActCommentServiceServant;
use HttpServer\protocol\QD\ActCommentGoService\CommentObj\classes\CommonInParam;
use HttpServer\protocol\QD\ActCommentGoService\CommentObj\classes\CommonOutParam;
use HttpServer\protocol\QD\ActCommentGoService\CommentObj\classes\QueryParam;
use HttpServer\protocol\QD\ActCommentGoService\CommentObj\classes\SimpleComment;

class ActCommentGoModel extends BaseModel
{
    protected static function getCommonIn()
    {
        $commonIn = new CommonInParam();
        $commonIn->userId = 0;
        $commonIn->appId = 1;
        $commonIn->areaId = 10;
        $commonIn->userIp = '';
        $commonIn->serverIp = '127.0.0.1';

        return $commonIn;
    }

    public static function createComment($userId, $activityId, $title, $content)
    {
        try {
            $commonIn = self::getCommonIn();
            $commonIn->userId = $userId;

            $comment = new SimpleComment();
            $comment->activityId = $activityId;
            $comment->title = $title;
            $comment->content = $content;

            $commonOut = new CommonOutParam();

            $servant = new ActCommentServiceServant(self::getConfig());
            $servant->createComment($commonIn, $comment, $commonOut);

            if ($commonOut->code != 0) {
                //business error
                //TODO log
                throw new ActivityException(Code::COMMENT_MODEL_CREATE_FAILED, $commonOut->code);
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
            $query->activityId = $activityId;
            $query->page = $page;
            $query->size = $size;
            $query->orderType = 1;

            $commonOut = new CommonOutParam();

            $servant = new ActCommentServiceServant(self::getConfig());
            $servant->getComment($commonIn, $query, $commonOut, $list);

            if ($commonOut->code != 0) {
                //business error
                //TODO log
                throw new ActivityException(Code::COMMENT_MODEL_CREATE_FAILED, $commonOut->code);
            }

            return $list;
        } catch (ActivityException $e) {
            throw $e;
        } catch (\Exception $e) {
            //TODO log
            throw new ActivityException(Code::COMMENT_MODEL_CONNECT_ERROR, $e->getMessage() . $e->getCode());
        }
    }

    public static function ping()
    {
        $servant = new ActCommentServiceServant(self::getConfig());
        $servant->ping();
    }


    public static function getCount()
    {
        $servant = new ActCommentServiceServant(self::getConfig());
        $servant->getCount($count);
        return $count;
    }
}
