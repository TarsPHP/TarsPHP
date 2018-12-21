<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/9/2
 * Time: 下午5:41
 */

namespace WebsocketServer\message;

use WebsocketServer\component\FileFdStore;

class ExampleWebsocket{

    /**
     * @param $wsServer
     * @param $frame
     * 定义一种简单的数据结构
     * cmd|data
     * 比如 subscribe|roomNum
     * unsubscribe|roomNum
     * sendByRoom|{"roomNum":1,"message":"Nice to see u guys!"}
     * broadcast|{jsondata}
     */
    public function onMessage($wsServer, $frame) {

        error_log("receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n");

        $data = $frame->data;

        // 进行协议解析
        $args = explode("|", $data);

        $cmd = $args[0];

        switch ($cmd) {
            case "ping" :{
                $wsServer->push($frame->fd, "pong");

                break;
            }
            case "subscribe" :{
                $roomNum = $args[1];

                error_log("subscribe: roomNum:" . $roomNum . " fd:".$frame->fd);

                FileFdStore::addFdByKey($roomNum, $frame->fd);

                $wsServer->push($frame->fd, "Subscribe roomNum:".$roomNum." success!");

                break;
            }
            case "unsubscribe" :{
                $roomNum = $args[1];

                error_log("unsubscribe: roomNum:" . $roomNum . " fd:".$frame->fd);

                FileFdStore::delFdByKey($roomNum, $frame->fd);

                $wsServer->push($frame->fd, "Unsubscribe roomNum:".$roomNum." success!");

                break;
            }
            case "sendByRoom" :{
                $info = json_decode($args[1],true);
                $roomNum = $info['roomNum'];
                $message = $info['message'];

                // 从room中获取部分的fd
                $fds = FileFdStore::getFdsByKey($roomNum);

                error_log("sendByRoom: roomNum:" . $roomNum . " message:" . $message . " fds:".var_export($fds,true));

                // 批量的向room中进行广播
                foreach ($fds as $fd) {
                    $wsServer->push(intval($fd), $message);
                }

                $wsServer->push($frame->fd, "SendByRoom success!");

                break;
            }
            case "broadcast" :{

                $info = json_decode($args[1],true);
                $message = $info['message'];

                $fds = $wsServer->connections;

                error_log("broadcast: " . $message . " fds:".var_export($fds,true));

                // 批量的向room中进行广播
                foreach ($fds as $fd) {
                    $wsServer->push($fd, $message);
                }

                $wsServer->push($frame->fd, "Broadcast success!");

                break;
            }
            default:
                $wsServer->push($frame->fd, "Rsp from server: this is server");
                break;
        }


    }

    public function onClose($ws, $fd) {
        error_log("Connection closed with fd:" . $fd);
    }
}