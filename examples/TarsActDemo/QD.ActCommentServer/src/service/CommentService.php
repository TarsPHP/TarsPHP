<?php

namespace Server\service;
use Server\component\BaseTrait;
use Server\component\Utils;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\CommonInParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\QueryParam;
use Server\protocol\QD\ActCommentServer\CommentObj\classes\SimpleComment;

/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/29
 * Time: 18:00
 */
class CommentService
{
    use BaseTrait;

    public static function createComment(CommonInParam $inParam, SimpleComment $comment)
    {
        $redis = self::getRedis();
        $id = $redis->incrBy(self::getCommentIdKey(), 1);

        $comment->createTime = time();
        $comment->id = $id;
        if (empty($comment->userId)) {
            $comment->userId = $inParam->userId;
        }

        $commentArray = Utils::objToArrayForTars($comment);

        $redis->hMset(self::getCommentContext($id), $commentArray);

        $indexKey = self::getIndexKey($comment->activityId);
        $redis->rPush($indexKey, $id);
    }

    public static function getComment(QueryParam $queryParam, &$list)
    {
        $redis = self::getRedis();

        $indexKey = self::getIndexKey($queryParam->activityId);
        $ids = $redis->lRange($indexKey, ($queryParam->page - 1) * $queryParam->size, $queryParam->page * $queryParam->size - 1);
        if (empty($ids)) {
            return;
        }

        $p = $redis->multi(\Redis::PIPELINE);
        foreach ($ids as $id) {
            $key = self::getCommentContext($id);
            $p->hGetAll($key);
        }
        $ret = $p->exec();

        foreach ($ret as $row) {
            $outStruct = new SimpleComment();
            $outStruct->id = (int)$row['id'];
            $outStruct->userId = (int)$row['userId'];
            $outStruct->activityId = (int)$row['activityId'];
            $outStruct->content = $row['content'];
            $outStruct->title = $row['title'];
            $outStruct->createTime = (int)$row['createTime'];
            $outStruct->ext1 = $row['ext1'];

            $list->pushBack($outStruct);
        }

        return;
    }


    protected function getCommentIdKey()
    {
        return 'comment_id';
    }

    protected function getCommentContext($id)
    {
        return 'comment_c_' . $id;
    }

    protected function getIndexKey($activityId)
    {
        return 'index_act_id_' . $activityId;
    }
}
