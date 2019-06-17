<?php
/**
 * Created by PhpStorm.
 * User: zhangyong
 * Date: 2018/8/16
 * Time: 15:29
 */

namespace Server\impl;


use Server\App;
use Server\conf\Code;
use Server\exception\BusinessException;
use Server\protocol\QD\UserService\UserObj\classes\CommonInParam;
use Server\protocol\QD\UserService\UserObj\classes\CommonOutParam;
use Server\protocol\QD\UserService\UserObj\UserServiceServant;
use Server\service\UserInfoService;

class UserServiceImpl implements UserServiceServant
{
    public function __construct()
    {
        App::initDb();
    }

    /**
     * @return int
     */
    public function ping()
    {
        return 1;
    }

    /**
     * @param struct $inParam \Server\protocol\QD\UserService\UserObj\classes\CommonInParam
     * @param vector $usersId \TARS_Vector(\TARS::INT64)
     * @param struct $outParam \Server\protocol\QD\UserService\UserObj\classes\CommonOutParam =out=
     * @param map $info \TARS_Map(\TARS::INT64,\Server\protocol\QD\UserService\UserObj\classes\User) =out=
     * @return void
     */
    public function getUsersInfoByIds(CommonInParam $inParam, $usersId, CommonOutParam &$outParam, &$info)
    {
        try {
            UserInfoService::getUserInfoByIds($usersId, $info);
            $this->setCommonOutSuccess($outParam);
        } catch (\Exception $e) {
            $this->setCommonOutByException($outParam, $e);
        }
    }

    /**
     * @param struct $imParam \Server\protocol\QD\UserService\UserObj\classes\CommonInParam
     * @param long $userId
     * @param string $sessionKey
     * @param struct $outParam \Server\protocol\QD\UserService\UserObj\classes\CommonOutParam =out=
     * @return void
     */
    public function checkSession(CommonInParam $imParam, $userId, $sessionKey, CommonOutParam &$outParam)
    {
        try {
            UserInfoService::checkSession($userId, $sessionKey);
            $this->setCommonOutSuccess($outParam);
        } catch (\Exception $e) {
            $this->setCommonOutByException($outParam, $e);
        }
    }

    /**
     * @param struct $inParam \Server\protocol\QD\UserService\UserObj\classes\CommonInParam
     * @param string $nickname
     * @param string $password
     * @param struct $outParam \Server\protocol\QD\UserService\UserObj\classes\CommonOutParam =out=
     * @param long $userId =out=
     * @param string $sessionKey =out=
     * @return void
     */
    public function login(CommonInParam $inParam, $nickname, $password, CommonOutParam &$outParam, &$userId, &$sessionKey)
    {
        try {
            $ret = UserInfoService::login($nickname, $password);
            $userId = $ret['userId'];
            $sessionKey = $ret['key'];
            $this->setCommonOutSuccess($outParam);
        } catch (\Exception $e) {
            $this->setCommonOutByException($outParam, $e);
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
