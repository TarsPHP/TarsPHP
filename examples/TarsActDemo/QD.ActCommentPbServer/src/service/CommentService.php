<?php

namespace Server\service;
use Server\component\BaseTrait;
use Protocol\QD\ActCommentPbServer\CommonInParam;
use Protocol\QD\ActCommentPbServer\QueryParam;
use Protocol\QD\ActCommentPbServer\SimpleComment;

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

        $comment->setCreateTime(time());
        $comment->setId($id);
        if (empty($comment->getUserId())) {
            $comment->setUserId($inParam->getUserId());
        }

        $commentArray = [
            'id' => $comment->getId(),
            'activityId' => $comment->getActivityId(),
            'userId' => $comment->getUserId(),
            'content' => $comment->getContent(),
            'title' => $comment->getTitle(),
            'ext1' => $comment->getExt1(),
            'createTime' => $comment->getCreateTime(),
        ];

        $redis->hMset(self::getCommentContext($id), $commentArray);

        $indexKey = self::getIndexKey($comment->getActivityId());
        $redis->rPush($indexKey, $id);
    }

    public static function getComment(QueryParam $queryParam, &$list)
    {
        $redis = self::getRedis();

        $indexKey = self::getIndexKey($queryParam->getActivityId());
        $ids = $redis->lRange($indexKey, ($queryParam->getPage() - 1) * $queryParam->getSize(), $queryParam->getPage() * $queryParam->getSize() - 1);
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
            $outStruct->setId((int)$row['id']);
            $outStruct->setUserId((int)$row['userId']);
            $outStruct->setActivityId((int)$row['activityId']);
            $outStruct->setContent($row['content']);
            $outStruct->setTitle($row['title']);
            $outStruct->setCreateTime((int)$row['createTime']);
            $outStruct->setExt1($row['ext1']);

            $list[] = $outStruct;
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
