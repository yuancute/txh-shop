<?php

namespace app\api\controller;

use app\api\model\UserAddress;

class Address extends Controller
{
    /**获取地址列表
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     */
    public function lists()
    {
        $user  = $this->getUser();
        $model = new UserAddress;
        $list  = $model->getList($user['user_id']);
        return $this->renderSuccess([
            'list'       => $list,
            'default_id' => $user['address_id'],
        ]);
    }

    /**
     * 添加地址
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     */
    public function add()
    {
        $model = new UserAddress;
        if($model->add($this->getUser(),$this->request->post())){
            return $this->renderSuccess([],'添加成功');
        }
        return $this->renderError('添加失败');
    }

    /**
     * 获取地址详情
     * @param $address_id
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail($address_id)
    {
        $user = $this->getUser();
        $detail = UserAddress::detail($user['user_id'],$address_id);
        $region = array_values($detail['region']);
        return $this->renderSuccess(compact('detail','region'));
    }

    /**
     * 修改地址
     * @param $address_id
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function edit($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'],$address_id);
        if($model->edit($this->request->post())){
            return $this->renderSuccess([],'更新成功');
        }
        return $this->renderError('更新失败');
    }

    /**
     * 设为默认地址
     * @param $address_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function setDefault($address_id) {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'], $address_id);
        if ($model->setDefault($user)) {
            return $this->renderSuccess([], '设置成功');
        }
        return $this->renderError('设置失败');
    }

    /**
     * 删除用户地址
     * @param $address_id
     * @return \think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function delete($address_id)
    {
        $user = $this->getUser();
        $model = UserAddress::detail($user['user_id'],$address_id);
        if($model->remove($user)){
            return $this->renderSuccess([],'删除成功');
        }
        return $this->renderError('删除失败');
    }
}
