<?php
namespace app\store\model;

use app\common\model\Delivery as DeliveryModel;

class Delivery extends DeliveryModel
{
    public function add($data)
    {
        if(!isset($data['rule']) || empty($data['rule'])){
            $this->error = '请选择可配送区域';
            return false;
        }
        $data['wxapp_id'] = self::$wxapp_id;
        if($this->allowField(true)->save($data)){
            return $this->createDeliverRule($data['rule']);
        }
    }

    public function edit($data)
    {
        if(!isset($data['rule']) || empty($data['rule'])){
            $this->error = '请选择可配送区域';
            return false;
        }
        if($this->allowField(true)->save($data))
        {
            return $this->createDeliverRule($data['rule']);
        }
    }

    public function remove()
    {
        if($goodsCount = (new Goods)->where(['delivery_id'=>$this['delivery_id']])->count()){
        $this->error = '该模板被' . $goodsCount . '个商品使用，不允许删除';
        return false;
        }
        $this->rule()->delete();
        return $this->delete();
    }

    private function createDeliverRule($data)
    {
        $save = [];
        $count = count($data['region']);
        for($i=0;$i<$count;$i++) {
            $save[] = [
                'region' => $data['region'][$i],
                'first' => $data['first'][$i],
                'first_fee' => $data['first_fee'][$i],
                'additional' => $data['additional'][$i],
                'additional_fee' => $data['additional_fee'][$i],
                'wxapp_id' => self::$wxapp_id
            ];
        }
        $this->rule()->delete();
        return $this->rule()->saveAll($save);
    }
}