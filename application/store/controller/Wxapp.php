<?php

namespace app\store\controller;

use app\store\model\Wxapp as WxappModel;
use app\store\model\WxappNavbar as WxappNavbarModel;

class Wxapp extends Controller
{
    public function setting()
    {
        $wxapp = WxappModel::detail();
        if($this->request->isAjax()){
            $data = $this->postData('wxapp');
            if($wxapp->edit($data)){
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError('更新失败');
        }
        return $this->fetch('setting',compact('wxapp'));
    }

    public function tabbar(){
        $model = WxappNavbarModel::detail();
        if(!$this->request->isAjax()){
            return $this->fetch('tabbar',compact('model'));
        }
        $data = $this->postData('tabbar');
        if(!$model->edit($data)){
            return $this->renderError('更新失败');
        }
        return $this->renderSuccess('更新成功');
    }
}