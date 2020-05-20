<?php
namespace app\store\controller;

use app\store\model\UploadFile;
use app\common\library\storage\Driver as StorageDriver;
use app\store\model\Setting as SettingModel;

class Upload extends Controller
{
    private $config;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->config = SettingModel::getItem('storage');
    }

    public function image($group_id = -1)
    {
        // 实例化存储驱动
        $StorageDriver = new StorageDriver($this->config);
        // 上传图片
        if (!$StorageDriver->upload())
            return json(['code' => 0, 'msg' => '图片上传失败' . $StorageDriver->getError()]);
        // 图片上传路径
        $fileName = $StorageDriver->getFileName();
        // 图片信息
        $fileInfo = $StorageDriver->getFileInfo();
        // 添加文件库记录
        $uploadFile = $this->addUploadFile($group_id, $fileName, $fileInfo, 'image');
        // 图片上传成功
        return json(['code' => 1, 'msg' => '图片上传成功', 'data' => $uploadFile]);

    }

    private function addUploadFile($group_id,$fileName,$fileInfo,$fileType)
    {
        //存储引擎
        $storage = $this->config['default'];
        //存储域名
        $fileUrl = isset($this->config['engine'][$storage])?$this->config['engine'][$storage]['domain'] : '';
        //添加文件库
        $model = new UploadFile;
        $model->add([
            'group_id' => $group_id > 0 ? (int)$group_id : 0,
            'storage' => $storage,
            'file_url' => $fileUrl,
            'file_name' => $fileName,
            'file_size' => $fileInfo['size'],
            'file_type' => $fileType,
            'extension' => pathinfo($fileInfo['name'], PATHINFO_EXTENSION),
        ]);
        return $model;
    }
}
