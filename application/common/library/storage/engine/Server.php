<?php

namespace app\common\library\storage\engine;

use think\Exception;
use Request;

/**
 * 存储引擎抽象类
 * Class Server
 * @package app\common\library\storage\engine
 */
abstract class Server
{
    protected $file;
    protected $erroe;
    protected $fileName;
    protected $fileInfo;

    /**
     * 构造函数
     * Server constructor.
     * @throws Exception
     */
    protected function __construct()
    {
        $request = Request::instance();
        $this->file = $request->file('iFile');
        if(empty($this->file)){
            throw new Exception('未找到上传文件的信息');
        }
        $this->fileName = $this->buildSaveName();
        $this->fileInfo = $this->file->getInfo();
    }

    /**
     * 文件上传
     * @return mixed
     */
    abstract protected function upload();

    /**
     * 返回上传后的路径
     * @return mixed
     */
    abstract public function getFileName();

    /**
     * 返回文件信息
     * @return mixed
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * 生成保存文件名
     * @return string
     */
    private function buildSaveName()
    {
        $realPath = $this->file->getRealPath();
        $ext = pathinfo($this->file->getInfo('name'),PATHINFO_EXTENSION);
        return date('YmdHis') . substr(md5($realPath),0,5)
            .str_pad(rand(0,999),4,'0',STR_PAD_LEFT)
            .'.'.$ext;
    }
}