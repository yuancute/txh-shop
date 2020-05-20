<?php
namespace app\common\model;

class StoreUser extends BaseModel
{
    protected $name = 'store_user';
    protected $pk   = 'store_user_id';

    /*
     * 关联小程序表
     */
    public function wxapp(){
        return $this->belongsTo('Wxapp');
    }
}