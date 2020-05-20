<?php
namespace app\store\controller\goods;

use app\store\controller\Controller;
use app\store\model\Category as CategoryModel;
use Config;

class Category extends Controller
{
    /**
     * 商品分类列表
     * @return mixed
     */
    public function index()
    {
        $model = new CategoryModel();
        $list = $model->getCacheTree();
        return $this->fetch('index',compact('list'));
    }

    /**
     * 添加分类
     * @return array|mixed
     */
    public function add()
    {
        $model = new CategoryModel();
        if(!$this->request->isAjax()){
            //获取分类
            $list = $model->getCacheTree();
            return $this->fetch('add',compact('list'));
        }
        //新增
        if($model->add($this->postData('category'))){
            return $this->renderSuccess('添加成功',url('goods.category/index'));
        }
        $error =$model->getError()?:'添加失败';
        return $this->renderError($error);
    }

    /**
     * 修改分类
     * @param $category_id
     * @return array|mixed
     */
    public function edit($category_id)
    {
        $model = CategoryModel::get($category_id,['image']);
        if(!$this->request->isAjax()){
            $list = $model->getCacheTree();
            return $this->fetch('edit',compact('model','list'));
        }
        if($model->edit($this->postData('category'))){
            return $this->renderSuccess('更新成功', url('goods.category/index'));
        }
        $error = $model->getError()?:'更新失败';
        return $this->renderError($error);
    }

    /**
     * 删除分类
     * @param $category_id
     * @return array
     */
    public function delete($category_id)
    {
        $model = CategoryModel::get($category_id);
        if(!$model->remove($category_id)) {
            $error = $model->getError()?:'删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }
}