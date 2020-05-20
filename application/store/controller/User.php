<?php

namespace app\store\controller;

use app\store\model\User as UserModel;

class User extends Controller
{
    /**
     * 用户列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $model = new UserModel;
        $list = $model->getList();
        return $this->fetch('index',compact('list'));
    }
}