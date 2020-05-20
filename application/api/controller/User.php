<?php
namespace app\api\controller;

use app\api\model\User as UserModel;
use app\api\model\Order as OrderModel;

class User extends Controller
{
    /**
     * 用户自动登录
     */
    public function login()
    {
        $model = new UserModel();
        $user_id = $model->login($this->request->post());
        $token = $model->getToken();
        return $this->renderSuccess(compact('user_id','token'));
    }

    public function detail()
    {
        //当前用户信息
        $userInfo = $this->getUser();
        //订单总数
        $model = new OrderModel();
        $orderCount = [
            'payment'=>$model->getCount($userInfo['user_id'],'payment'),
            'delivery' => $model->getCount($userInfo['user_id'], 'delivery'),
            'received' => $model->getCount($userInfo['user_id'], 'received'),
        ];

        return $this->renderSuccess(compact('userInfo','orderCount'));
    }
}