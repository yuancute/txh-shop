<?php

namespace app\store\model;

use app\common\model\UploadFile as UploadFileModel;

/**
 * 文件库模型
 * Class UploadFile
 * @package app\store\model
 */
class UploadFile extends UploadFileModel
{
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->save($data);
    }

    public function softDelte($fileIds)
    {
        return $this->where('file_id','in',$fileIds)
            ->update(['is_delete'=>1]);
    }

    public function moveGroup($group_id,$fileIds)
    {
        return $this->where('file_id','in',$fileIds)
            ->update(['group_id'=>$group_id]);
    }
}
