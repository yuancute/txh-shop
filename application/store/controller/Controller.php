<?php
namespace app\store\controller;

use Session;
use Request;
use Config;
use app\store\model\Setting;

class Controller extends \think\Controller
{
    //商家登录信息
    protected $store;

    //当前控制器
    protected $controller = '';

    //当前方法
    protected $action = '';

    //当前路由uri
    protected $routeUri='';

    //当前路由分组
    protected $group = '';

    //登录验证白名单
    protected $allowAllAction=[
        //登录页面
        'login/login',
    ];

    //无需全局layout
    protected $notLayoutAction = [
        //登录页面
        'login/login',
    ];

    /*
     * 后台初始化
     */
    public function initialize()
    {
        //商家登录信息
        $this->store = Session::get('txh_store');
        //当前路由信息
        $this->getRouteInfo();
        //验证登录
        $this->checkLogin();
        //全局layout
        $this->layout();

    }

    /*
     * 解析当前路由参数
     */
    protected function getRouteInfo()
    {
        // 控制器名称
        $this->controller = toUnderScore(Request::controller());
        // 方法名称
        $this->action = Request::action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = $this->controller . '/' . $this->action;
    }

    /*
     * 检查是否登录
     */
    private function checkLogin()
    {
        if(in_array($this->routeUri,$this->allowAllAction)){
            return true;
        }
        if(empty($this->store)
            ||(int)$this->store['is_login'] !==1
            ||!isset($this->store['wxapp'])
            ||empty($this->store['wxapp'])
        ){
            $this->redirect('login/login');
        }
        return true;
    }


    /*
     * 全局layout
     */
    private function layout()
    {
        if(!in_array($this->routeUri,$this->notLayoutAction)){
            //输出到view
            $this->assign([
                'base_url'=> base_url(),
                'store_url'=>url('/store'),
                'group'=>$this->group,
                'menus'=>$this->menus(),
                'store'=>$this->store,
                'setting'=>Setting::getAll()?:null,
            ]);
        }
    }

    /*
     * 后台菜单配置
     */

    private function menus()
    {
        //var_dump(Config::get('menus.layout_name'));
        foreach ($data = Config::get('menus.') as $group=>$first){
            $data[$group]['active'] = $group === $this->group;
            // 遍历：二级菜单
            if (isset($first['submenu'])) {
                foreach ($first['submenu'] as $secondKey => $second) {
                    // 二级菜单所有uri
                    $secondUris = [];
                    if (isset($second['submenu'])) {
                        // 遍历：三级菜单
                        foreach ($second['submenu'] as $thirdKey => $third) {
                            $thirdUris = [];
                            if (isset($third['uris'])) {
                                $secondUris = array_merge($secondUris, $third['uris']);
                                $thirdUris = array_merge($thirdUris, $third['uris']);
                            } else {
                                $secondUris[] = $third['index'];
                                $thirdUris[] = $third['index'];
                            }
                            $data[$group]['submenu'][$secondKey]['submenu'][$thirdKey]['active'] = in_array($this->routeUri, $thirdUris);
                        }
                    } else {
                        if (isset($second['uris']))
                            $secondUris = array_merge($secondUris, $second['uris']);
                        else
                            $secondUris[] = $second['index'];
                    }
                    // 二级菜单：active
                    !isset($data[$group]['submenu'][$secondKey]['active'])
                    && $data[$group]['submenu'][$secondKey]['active'] = in_array($this->routeUri, $secondUris);
                }
            }
        }
        return $data;
    }

    /*
     * 返回封装的数据到客户端
     */
    protected function renderJson($code='1',$msg='',$url='',$data=[]){
        return compact('code','msg','url','data');
    }
    /*
     * 返回操作成功json
     */
    protected function renderSuccess($msg='success',$url='',$data=[]){
        return $this->renderJson(1,$msg,$url,$data);
    }

    /*
     * 返回操作失败json
     */
    protected function renderError($msg='error',$url='',$data=[]){
        return $this->renderJson(1,$msg,$url,$data);
    }
    /*
     * 返回post数据（数组）
     */
    public  function postData($key){
        return $this->request->post($key.'/a');
    }
}