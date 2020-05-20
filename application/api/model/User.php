<?php
namespace  app\api\model;
use app\common\exception\BaseException;
use app\common\library\wechat\WxUser;
use app\common\model\User as UserModel;
use app\common\model\Wxapp;
use Cache;

class User extends UserModel
{
    private $token;

    protected $hidden = [
        'wxapp_id',
        'create_time',
        'update_time'
    ];

    public static function getUser($token)
    {
        //var_dump(Cache::get($token));
        return self::detail(['open_id' =>Cache::get($token)['openid']]);
    }

    /**
     * 用户登录
     */
    public function login($post)
    {
        //获取微信登录的session_key
        $sessionKey = $this->wxLogin($post['wxapp_id'],$post['code']);
        //自动注册用户
        $userInfo = json_decode($post['user_info'],true);
        $userId = $this->register($sessionKey['openid'],$userInfo);
        //var_dump($userId);
        //成出token
        $this->token = $this->setToken($sessionKey['openid']);
        //记录缓存，7天
        Cache::set($this->token,$sessionKey,86400*7);
        //var_dump($this->token);
        return $userId;
    }
    /**
     * 获取微信登录的session_key
     */
    private function wxLogin($wxappId,$code)
    {
        //获取当前小程序信息
        $wxapp  = Wxapp::detail($wxappId);
        //var_dump($wxapp);
        if(empty($wxapp['app_id'] || empty($wxapp['app_secret']))){
            throw new BaseException(['msg'=>'请到小程序后台设置']);
        }
        //微信登录（获取session_key）

        $WxUser = new WxUser($wxapp['app_id'],$wxapp['app_secret']);
        if(!$session = $WxUser->sessionKey($code)){
            throw new BaseException(['msg'=>$WxUser->getError()]);
        }
        return $session;
    }
    //获取token
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 生成用户认证的token
     * @param $openId
     * @return string
     */
    private function setToken($openId)
    {
        $wxapp_id = self::$wxapp_id;
        //生成不会重复的随机字符串
        $guid = getGuidV4();
        //当前时间戳（毫秒）
        $timeStamp = microtime(true);
        //自定义一个盐
        $salt = 'token_salt';
        return md5("{$wxapp_id}_{$timeStamp}_{$openId}_{$guid}_{$salt}");
    }

    /**
     * 自动注册用户
     * @param $open_id
     * @param $userInfo
     * @return mixed
     * @throws BaseException
     */
    public function register($open_id,$userInfo)
    {
        if(!$user = self::get(['open_id'=>$open_id])) {
            $user = $this;
            $userInfo['open_id'] = $open_id;
            $userInfo['wxapp_id'] = self::$wxapp_id;
        }
        $userInfo['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickName']);
        if(!$user->allowField(true)->save($userInfo)){
            throw new BaseException(['mag'=>'用户注册失败']);
        }
        return $user['user_id'];
    }
}