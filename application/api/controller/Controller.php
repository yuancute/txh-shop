<?php
namespace app\api\controller;

use app\common\exception\BaseException;
use think\Controller as ThinkController;
use app\api\model\User as UserModel;

class Controller extends ThinkController
{
    const JSON_SUCCESS_STATUS = 1;
    const JSON_ERROR_STATUS = 0;
    //小程序id
    protected $wxapp_id;
    //基类初始化
    public function initialize()
    {
        $this->wxapp_id = $this->getWxappId();
    }

    //获取小程序id
    private function getWxappId()
    {
        if(!$wxapp_id = $this->request->param('wxapp_id')){
            throw new BaseException(['code'=>-1,'msg'=>'缺少必要参数：wxapp_id']);
        }
        return $wxapp_id;
    }

    protected function getUser()
    {
        if(!$token = $this->request->param('token')){
            throw new BaseException(['code'=>-1,'msg'=>'缺少必要的参数：token']);
        }
        if(!$user = UserModel::getUser($token)){
            throw  new BaseException(['code'=>-1,'msg'=>'没有找到用户']);
        }
        return $user;
    }

    //返回封装的额API数据到客户端
    protected function renderJson($code = self::JSON_SUCCESS_STATUS,$msg ='',$data=[])
    {
        return json(compact('code','msg','data'));
    }

    //返回操作成功json
    protected function renderSuccess($data = [],$msg = 'success')
    {
        return $this->renderJson(self::JSON_SUCCESS_STATUS,$msg,$data);
    }

    //返回操作失败json
    protected function renderError($msg = 'error',$data = [])
    {
        return $this->renderJson(self::JSON_ERROR_STATUS,$msg,$data);
    }
    //获取post数据（数组）
    protected function postData($key)
    {
        return $this->request->post($key,'/a');
    }
}