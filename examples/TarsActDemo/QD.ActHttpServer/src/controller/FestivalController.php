<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/8
 * Time: 下午2:42.
 */

namespace HttpServer\controller;

use HttpServer\conf\Code;
use HttpServer\exception\ActivityException;
use HttpServer\component\BusinessController;
use HttpServer\model\UserInfoModel;
use HttpServer\service\FestivalService;

class FestivalController extends BusinessController
{
    public function actionIndex()
    {
        $this->sendSuccess('Hello Word');
    }

    public function actionGetBullet()
    {
        $this->header('Access-Control-Allow-Origin', '*');
        $page = self::getGet('page', 1);
        $size = self::getGet('size', 10);

        $list = FestivalService::getBullet($page, $size);

        $this->sendSuccess($list);
    }

    public function actionCreateBullet()
    {
        $this->header('Access-Control-Allow-Origin', '*');
        self::checkIsLogin(true);

        $content = self::getPost('content');
        if (empty($content)) {
            throw new ActivityException(Code::CODE_INVALID_PARAM);
        }

        $ret = FestivalService::createBullet(self::getUserId(), $content);

        $this->sendSuccess($ret);
    }

    public function actionPing()
    {
        FestivalService::ping();
        $this->sendSuccess();
    }

    public function actionGetCommentCount()
    {
        $count = FestivalService::getCount();
        $this->sendSuccess($count);
    }
}
