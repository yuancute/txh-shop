<?php
namespace app\store\controller;
use Config;
use app\store\model\StoreUser;
use think\facade\Session;

class Login extends Controller
{
    //后台登陆
    public function login()
    {
        if($this->request->isAjax()) {
            $model = new StoreUser;
            if ($model->login($this->request->post('User'))) {
                return $this->renderSuccess('登录成功', url('home/index'));
            }
            return $this->renderError($model->getError() ?: '登录失败');
        }
        $this->view->engine->layout(false);
        return $this->fetch('login');
    }
    //退出登录
    public function logout()
    {
        Session::clear('txh_store');
        $this->redirect('login/login');
    }
}