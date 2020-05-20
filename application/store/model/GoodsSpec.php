<?php
namespace app\store\model;

use app\common\model\GoodsSpec as GoodsSpecModel;

class GoodsSpec extends GoodsSpecModel
{
    public function removeAll($goods_id)
    {
        $model = new GoodsSpecRel;
        $model->where(['goods_id'=>$goods_id])->delete();
        return $this->where(['goods_id'=>$goods_id])->delete();
    }

    public function addSkuList($goods_id,$spec_list)
    {
        $data = [];
        foreach ($spec_list as $item){
            $data[] = array_merge($item['form'],[
                'spec_sku_id'=>$item['spec_sku_id'],
                'goods_id'=>$goods_id,
                'wxapp_id'=>self::$wxapp_id
            ]);
        }
        return $this->saveAll($data);
    }

    public function addGoodsSpecRel($goods_id,$spec_attr)
    {
        $data = [];
        array_map(function ($val) use (&$data, $goods_id) {
            array_map(function ($item) use (&$val, &$data, $goods_id) {
                $data[] = [
                    'goods_id' => $goods_id,
                    'spec_id' => $val['group_id'],
                    'spec_value_id' => $item['item_id'],
                    'wxapp_id' => self::$wxapp_id,
                ];
            }, $val['spec_items']);
        }, $spec_attr);
        $model = new GoodsSpecRel;
        return $model->saveAll($data);
    }
}
