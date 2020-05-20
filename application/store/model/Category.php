<?php
namespace app\store\model;

use app\common\model\Category as CategoryModel;
use Cache;

class Category extends CategoryModel
{
    public function add($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        $this->deleteCache();
        return $this->allowField(true)->save($data);
    }

    public function edit($data)
    {
        $data['wxapp_id'] = self::$wxapp_id;
        $this->deleteCache();
        return $this->allowField(true)->save($data);
    }

    public function remove($category_id)
    {
        if($goodsCount = (new Goods)->where(compact('category_id'))->count()){
            $this->error = '该分类存在'.$goodsCount.'个商品，不允许删除';
            return false;
        }
        $this->deleteCache();
        return $this->delete();
    }

    private function deleteCache()
    {
        return Cache::rm('category_'.self::$wxapp_id);
    }
}