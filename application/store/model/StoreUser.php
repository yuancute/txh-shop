<?php
namespace app\store\model;
use app\common\model\StoreUser as StoreUserModel;
use Session;

class StoreUser extends StoreUserModel
{
    /*
     * 商家登录
     */
    public function login($data)
    {
        //验证密码
        if(!$user = self::useGlobalScope(false)->with(['wxapp'])->where([
            'user_name'=>$data['user_name'],
            'password'=>txh_hash($data['password'])
        ])->find()){
            $this->error = '登录失败，账号或者密码错误';
            return false;
        }
        //保存登录状态
        Session::set('txh_store',[
            'user'=>[
                'store_user_id'=>$user['store_user_id'],
                'user_name'=> $user['user_name'],
            ],
            'wxapp'=>$user['wxapp']->toarray(),
            'is_login'=> true
        ]);
        return true;
    }


}