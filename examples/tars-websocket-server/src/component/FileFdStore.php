<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/9/11
 * Time: 下午11:06
 */

namespace WebsocketServer\component;


use Tars\Utils;

class FileFdStore implements FdStore
{
    // 文件里面是用 | 分割的fd
    private static $filePath = "/usr/local/app/tars/tarsnode/data/PHPTest.WebsocketServer/data/";


    public static function addFdByKey($key, $fd)
    {
        $fileName = self::$filePath . $key;
        if(file_exists($fileName)) {
            $fds = file_get_contents($fileName);
            $fds .= "|";
            $fds .= $fd;
            file_put_contents($fileName, $fds);
        }
        else {
            file_put_contents($fileName, $fd);
        }
    }

    public static function delFdByKey($key, $fd)
    {
        $fileName = self::$filePath . $key;
        if(file_exists($fileName)) {
            $fds = file_get_contents($fileName);
            $fdList = explode("|", $fds);
            $i = 0;
            for(; $i < count($fdList); $i++) {
                if($fdList[$i] == $fd) {
                    unset($fdList[$i]);
                    break;
                }
            }

            $fds = implode("|", $fdList);

            file_put_contents($fileName, $fds);
        }
    }

    public static function getFdsByKey($key)
    {
        $fileName = self::$filePath . $key;
        if(file_exists($fileName)) {
            $fds = file_get_contents($fileName);
            $fdList = explode("|", $fds);
            return $fdList;
        }
        else {
            return [];
        }
    }
}