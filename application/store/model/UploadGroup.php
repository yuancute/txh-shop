<?php

namespace app\store\model;

use app\common\model\UploadGroup as UploadGroupModel;

/**
 * 文件库分组模型
 * Class UploadGroup
 * @package app\store\model
 */
class UploadGroup extends UploadGroupModel
{
    /**
     * 获取分组列表
     * @param string $group_type
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($group_type ='image')
    {
        return $this->where(compact('$group_type'))
            ->order(['sort'=>'asc'])->select();
    }

    /**
     * 添加新纪录
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        $data['sort'] = 100;
        return $this->save($data);
    }

    /**
     * 更新记录
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        return $this->allowField(true)->save($data)?:false;
    }

    /**
     * 删除记录
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function remove()
    {
        $model = new UploadFile;
        $model->where(['group_id'=>$this['group_id']])->update(['group_id'=>0]);
        return $this->delete();
    }
}
