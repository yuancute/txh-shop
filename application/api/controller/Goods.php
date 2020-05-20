<?php

namespace app\api\controller;

use app\api\model\Goods as GoodsModel;

/**
 * 商品控制器
 * Class Goods
 * @package app\api\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @param $category_id
     * @param $search
     * @param $sortType
     * @param $sortPrice
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function lists($category_id, $search, $sortType, $sortPrice)
    {
        $model = new GoodsModel;
        $list = $model->getList(10, $category_id, $search, $sortType, $sortPrice);
        !$list->isEmpty() && $list->hidden(['category', 'content']);
        return $this->renderSuccess(compact('list'));

    }

    /**
     * 商品详情
     * @param $goods_id
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function detail($goods_id)
    {
        //商品详情
        $detail = GoodsModel::detail($goods_id);
        if (!$detail || $detail['goods_status']['value'] != 10) {
            return $this->renderError('很抱歉，商品信息不存在');
        }
        //规格信息
        $specData = $detail['spec_type'] == 20 ? $detail->getManySpecData($detail['spec_rel'], $detail['spec']) : null;

        return $this->renderSuccess(compact('detail', 'specData'));
    }
}