<?php
/**
 * Created by PhpStorm.
 * User: liujingfeng.a
 * Date: 2018/9/13
 * Time: 11:40
 */

namespace HttpServer\handler;


use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Tars\client\CommunicatorConfig;
use Tars\log\LogServant;

class TarsHandler extends AbstractProcessingHandler
{
    protected $app = 'Undefined';
    protected $server = 'Undefined';
    protected $dateFormat = '%Y%m%d';

    private $logServant;
    private $logConf;

    public function __construct(CommunicatorConfig $config, $servantName = "tars.tarslog.LogObj", $level = Logger::WARNING, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->logConf = $config;
        $this->logServant = new LogServant($config, $servantName);

        $moduleName = $this->logConf->getModuleName();
        $moduleData = explode('.', $moduleName);
        $this->app = $moduleData ? $moduleData[0] : $this->app;
        $this->server = isset($moduleData[1]) ? $moduleData[1] : $this->server;
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param string $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }


    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     * @throws \Exception
     */
    protected function write(array $record)
    {
        $this->logServant->logger($this->app, $this->server, $record['channel'], $this->dateFormat, [$record['formatted']]);
    }
}