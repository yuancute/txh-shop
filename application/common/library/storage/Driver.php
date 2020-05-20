<?php

namespace app\common\library\storage;

use think\Exception;

/**
 * 存储模块驱动
 * Class driver
 * @package app\common\library\storage
 */
class Driver
{

    private $config;
    private $engine;

    public function __construct($config)
    {
        $this->config = $config;
        $this->engine = $this->getEngineClass();
    }

    public function upload()
    {
        return $this->engine->upload();

    }

    public function getError()
    {
        return $this->engine->getError();
    }

    public function getFileName()
    {
        return $this->engine->getFileName();
    }

    public function getFileInfo()
    {
        return $this->engine->getFileInfo();
    }

    private function getEngineClass()
    {
        $engineName = $this->config['default'];
        $classSpace = __NAMESPACE__.'\\engine\\'.ucfirst($engineName);
        if(!class_exists($classSpace)){
            throw new Exception('未找到存储引擎类：' . $engineName);
        }
        $config = isset($this->config['engineName'])?$this->config['engine'][$engineName]:[];
        return new $classSpace($config);
    }
}
