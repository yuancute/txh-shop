<?php
namespace app\common\library\wechat;

/**
 * 微信小程序用户管理类
 */

class WxUser
{
    private $appId;
    private $appSecret;

    private $error;

    public function __construct($appId,$appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * 获取sessionKey
     */
    public function SessionKey($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = [
            'appid'=>$this->appId,
            'secret'=>$this->appSecret,
            'grant_type' =>'authorization_code',
            'js_code'=>$code
        ];
        $result = json_decode(curl($url,$data),true);
        if(isset($result['errcode'])){
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }

    public function getError()
    {
        return $this->error;
    }
}