<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/5/8
 * Time: 下午2:43.
 */

namespace Server\component;

use HttpServer\conf\Code;
use Exception;
use HttpServer\exception\ActivityException;
use Server\App;
use Tars\core\Request;
use Tars\core\Response;
use Swoole\Coroutine;

class BusinessController extends Controller
{
    use BaseTrait;

    public static $controller = [];

    public $session = null;

    protected $postData = null;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        self::saveController($this);
        App::initDb();
        $this->processPost(true);
    }

    public function sendSuccess($data = [])
    {
        $ret = [
            'code' => 0,
            'msg' => 'success',
            'data' => $data,
        ];

        $this->send($ret);
    }

    public function send($data)
    {
        self::unsetController();
        $this->header('Content-Type', 'application/json');

        if (!isset($data['code'])) {
            $code = Code::CODE_FAIL;
            $res = array('code' => $code, 'msg' => Code::getAjaxMsg($code));
            $this->sendRaw(json_encode($res));
            return;
        } else {
            $data['msg'] = isset($data['msg']) ? $data['msg'] : Code::getAjaxMsg($data['code']);
            $data = json_encode($data);
            $this->sendRaw($data);
            return;
        }
    }

    public function run($fun)
    {
        try {
            $this->$fun();
        } catch (\Exception $e) {
            $this->sendByException($e);
        }
    }

    protected function sendByException($e)
    {
        if (get_class($e) == ActivityException::class) {
            $ret = [
                'code' => $e->getCode(),
                'msg' => $e->getMessage(),
                'trace' => $e->getTraceAsString(), //TODO only dev env
            ];
        } else {
            $ret = [
                'code' => Code::CODE_FAIL,
                'msg' => "System error, check log file",
            ];
        }

        $this->send($ret);
    }

    protected static function saveController($controller)
    {
        $id = Coroutine::getuid();
        self::$controller[$id] = $controller;
    }

    protected static function unsetController()
    {
        $id = Coroutine::getuid();
        unset(self::$controller[$id]);
    }

    protected function processPost($force = false)
    {
        if ($this->postData === null || $force) {
            if (isset($this->request->data['header']['content-type']) && strpos($this->request->data['header']['content-type'], 'json') !== false) {
                $this->postData = json_decode($this->request->data['post'], true);
            } else {
                $this->postData = $this->request->data['post'];
            }
        }
    }
}
