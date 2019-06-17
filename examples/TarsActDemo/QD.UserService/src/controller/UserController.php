<?php

namespace Server\controller;

use Server\component\BusinessController;
use Server\component\Utils;
use Server\service\UserInfoService;

use \Server\protocol\QD\UserService\UserObj\classes\User;

class UserController extends BusinessController
{
    public function actionIndex()
    {
        $this->sendSuccess('hello word');
    }

    public function actionGetUsersInfoByIds()
    {
        $ids = self::getGet('ids');
        $usersId = explode(',', $ids);

        $info = new \TARS_Map(\TARS::INT64, new User);
        UserInfoService::getUserInfoByIds($usersId, $info, $outArray);

        $this->sendSuccess(Utils::objToArrayForTars($outArray));
    }
}
