<?php
namespace app\api\controller;

use app\api\model\Category as CategoryModel;

class Category extends Controller
{
    /**
     * 全部分类
     */
    public function lists()
    {
        $list = array_values(CategoryModel::getCacheTree());
        return $this->renderSuccess(compact('list'));
    }
}