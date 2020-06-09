<?php

namespace app\store\controller;

use app\store\model\Setting as SettingModel;

class Setting extends Controller
{
    /**
     * 商城设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function store()
    {
        return $this->updateEvent('store');
    }

    /**
     * 交易设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function trade()
    {
        return $this->updateEvent('trade');
    }

    /**
     * 短信通知
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function sms()
    {
        return $this->updateEvent('sms');
    }

    /**
     * 上传设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function storage()
    {
        return $this->updateEvent('storage');
    }

    /**
     * 更新商城设置事件
     * @param $key
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function updateEvent($key)
    {
        if(!$this->request->isAjax()){
            $values = SettingModel::getItem($key);
            return $this->fetch($key,compact('values'));
        };
        $model = new SettingModel;
        if($model->edit($key,$this->postData($key)))
        {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }
}
