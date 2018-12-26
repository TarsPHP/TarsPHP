<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/8
 * Time: 下午2:42.
 */

namespace WebsocketServer\controller;

use Tars\App;
use WebsocketServer\component\Controller;
use WebsocketServer\component\FileFdStore;

class IndexController extends Controller
{

    public function actionIndex() {
        $this->sendRaw("index success!!");

    }

    public function actionPushByRoom()
    {
        $roomNum = $this->request->data['get']['roomNum'];

        // message既可以是前端提交的, 也可以是通过一些接口拉取的
        $message = $this->request->data['get']['message'];

        // 从room中获取部分的fd
        $fds = FileFdStore::getFdsByKey($roomNum);

        // 批量的向room中进行广播
        foreach ($fds as $fd) {
            $this->server->push($fd, $message);
        }

        // 请求结束
        $this->response->send("pushBy room success");
    }

    public function actionPushAll()
    {
        // message既可以是前端提交的, 也可以是通过一些接口拉取的
        $message = $this->request->data['get']['message'];


        // server中获取全部的fd
        $fds = $this->server->connections;

        // 批量的向room中进行广播
        foreach ($fds as $fd) {
            $this->server->push($fd, $message);
        }

        // 请求结束
        $this->response->send("pushBy all success");
    }
}
