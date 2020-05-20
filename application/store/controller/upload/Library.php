<?php

namespace app\store\controller\upload;

use app\store\controller\Controller;
use app\store\model\UploadFile as UploadFileModel;
use app\store\model\UploadGroup as UploadGroupModel;

class Library extends Controller
{
    /**
     * 文件库列表
     * @param string $type
     * @param int $group_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function fileList($type = 'image',$group_id = -1)
    {
        $group_list = (new UploadGroupModel())->getList($type);
        $file_list = (new UploadFileModel())->getList(intval($group_id),$type);
        return $this->renderSuccess('success','',compact('group_list','file_list'));
    }

    /**
     * 添加分组
     * @param $group_name
     * @param string $group_type
     * @return array
     */
    public function addGroup($group_name,$group_type = 'image')
    {
        $model = new UploadGroupModel;
        if($model->add(compact('group_name','group_type'))){
            $group_id = $model->getLastInsID();
            return $this->renderSuccess('添加成功','',compact('group_id', 'group_name'));
        }
        $error = $model->getError()?:'添加失败';
        return $this->renderError($error);
    }

    /**
     * 修改分组
     * @param $group_id
     * @param $group_name
     * @return array
     * @throws \think\exception\DbException
     */
    public function editGroup($group_id,$group_name)
    {
        $model  = UploadGroupModel::detail($group_id);
        if($model->edit(compact('group_name'))){
            return $this->renderSuccess('修改成功');
        }
        $error = $model->getError()?:'修改失败';
        return $this->renderError($error);
    }

    /**
     * 删除分组
     * @param $group_id
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function deleteGroup($group_id)
    {
        $model = UploadGroupModel::detail($group_id);
        if($model->remove()){
            return $this->renderSuccess('删除成功');
        }
        $error = $model->getError()?:'删除失败';
        return $this->renderError($error);
    }

    /**
     * 批量删除文件
     * @param $fileIds
     * @return array
     */
    public function deleteFiles($fileIds)
    {
        $model = new UploadFileModel;
        if($model->softDelte($fileIds)){
            return $this->renderSuccess('删除成功');
        }
        $error = $model->getError()?:'删除失败';
        return $this->renderError($error);
    }

    /**
     * 批量移动文件
     * @param $group_id
     * @param $fileIds
     * @return array
     */
    public function moveFiles($group_id,$fileIds){
        $model = new UploadFileModel;
        if($model->moveGroup($group_id,$fileIds)) {
            return $this->renderSuccess('移动成功');
        }
        $error = $model->getError()?:'移动失败';
        return $this->renderError($error);
    }

}
