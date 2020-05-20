<?php
namespace app\store\model;

use app\common\model\Goods as GoodsModel;
use Db;
use think\Exception;

class Goods extends GoodsModel
{
    public function add($data = [])
    {
        if(!isset($data['images']) || empty($data['images'])){
            $this->error = '请上传图片';
            return false;
        }
        $data['content'] = isset($data['content'])?$data['content']:'';
        $data['wxapp_id'] = $data['spec']['wxapp_id'] = self::$wxapp_id;

        Db::startTrans();
        try{
            $this->allowField(true)->save($data);
            $this->addGoodsSpec($data);
            $this->addGoodsImages($data['images']);
            Db::commit();
            return true;
        }catch (\Exception $e){
            Db::rollback();
        }
        return false;
    }

    public function edit($data)
    {
        if (!isset($data['images']) || empty($data['images'])) {
            $this->error = '请上传商品图片';
            return false;
        }
        $data['content'] = isset($data['content']) ? $data['content'] : '';
        $data['wxapp_id'] = $data['spec']['wxapp_id'] = self::$wxapp_id;
        // 开启事务
        Db::startTrans();
        try {
            // 保存商品
            $this->allowField(true)->save($data);
            // 商品规格
            $this->addGoodsSpec($data, true);
            // 商品图片
            $this->addGoodsImages($data['images']);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function remove()
    {
        Db::startTrans();
        try{
            (new GoodsSpec)->removeAll($this['goods_id']);
            $this->image()->delete();
            $this->delete();
            Db::commit();
            return true;
        }catch (\Exception $e){
            $this->error = $e->getMessage();
            Db:rollback();
            return false;
        }
    }

    private function addGoodsSpec($data,$isUpdate = false)
    {
        //更新模式：先删除所有规格
        $model = new GoodsSpec;
        $isUpdate && $model->removeAll($this['goods_id']);
        //添加规格数据
        if($data['spec_type']=='10'){
            $this->spec()->save($data['spec']);
        }else if($data['spec_type']=='20'){
            //添加商品与规格关系记录
            $model->addGoodsSpecRel($this['goods_id'],$data['spec_many']['spec_attr']);
            //添加商品sku
            $model->addSkuList($this['goods_id'],$data['spec_many']['spec_list']);
        }
    }

    private function addGoodsImages($images)
    {
        $this->image()->delete();
        $data = array_map(function ($image_id){
           return [
               'image_id'=>$image_id,
               'wxapp_id'=>self::$wxapp_id,
           ];
        },$images);
        return $this->image()->saveAll($data);
    }
}
