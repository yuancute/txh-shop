<?php

namespace app\common\exception;

use think\Exception;

class BaseException extends Exception
{

    public $code = 0;
    public $message = 'invalid params';

    public function __construct($params = [])
    {
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->message = $params['msg'];
        }
    }
}