<?php
namespace app\store\controller;

use app\store\model\Category;
use app\store\model\Delivery;
use app\store\model\Goods as GoodsModel;

class Goods extends Controller
{
    /**
     * 商品列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        //phpinfo();return;
        //return GoodsModel::detail(3);
        $model = new GoodsModel();
        $list = $model->getList();
        return $this->fetch('index',compact('list'));
    }

    /**
     * 商品添加
     * @return array|mixed
     */
    public function add()
    {

        if(!$this->request->isAjax()){
            $category = Category::getCacheTree();
            $delivery = Delivery::getAll();
            return $this->fetch('add',compact('category','delivery'));
        }
        //var_dump($this->postData('goods'));return;
        $model = new GoodsModel;
        if($model->add($this->postData('goods'))){
            return $this->renderSuccess('添加成功',url('goods/index'));
        }
        $error = $model->getError()?:'添加失败';
        return $this->renderError($error);
    }

    /**
     * 商品编辑
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($goods_id)
    {
        $model = GoodsModel::detail($goods_id);
        if(!$this->request->isAjax()){
            $category = Category::getCacheTree();
            $delivery = Delivery::getAll();
            $specData = 'null';
            if($model['spec_type']==20){
                $specData = json_encode($model->getManySpecData($model['spec_rel'],$model['spec']));
                //return $specData;
            }
            return $this->fetch('edit',compact('model', 'category', 'delivery', 'specData'));
        }
        if($model->edit($this->postData('goods'))){
            return $this->renderSuccess('更新成功',url('goods/index'));
        }
        $error = $model->getError()?:'更新失败';
        return $this->renderError($error);
    }

    /**
     * 删除商品
     * @param $goods_id
     * @return array
     */
    public function delete($goods_id)
    {
        $model = GoodsModel::get($goods_id);
        if(!$model->remove()){
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}