<?php

namespace app\store\controller;

use app\store\model\Order as OrderModel;

class Order extends Controller
{
    /**
     * 待发货订单列表
     * @return mixed
     */
    public function delivery_list()
    {
        return $this->getList('待发货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 10
        ]);
    }

    /**
     * 待收货列表
     * @return mixed
     */
    public function receipt_list()
    {
        return $this->getList('待收货订单列表', [
            'pay_status' => 20,
            'delivery_status' => 20,
            'receipt_status' => 10
        ]);
    }

    /**
     * 待付款列表
     * @return mixed
     */
    public function pay_list()
    {
        return $this->getList('待付款订单列表', ['pay_status' => 10, 'order_status' => 10]);
    }

    /**
     * 已完成订单列表
     * @return mixed
     */
    public function complete_list()
    {
        return $this->getList('已完成订单列表', ['order_status' => 30]);
    }

    /**
     * 已取消订单列表
     * @return mixed
     */
    public function cancel_list()
    {
        return $this->getList('已取消订单列表', ['order_status' => 20]);
    }

    /**
     * 所有订单列表
     * @return mixed
     */
    public function all_list()
    {
        return $this->getList('全部订单列表');
    }

    /**
     * 获取订单列表数据
     * @param $title
     * @param array $filter
     * @return mixed
     */
    private function getList($title,$filter=[])
    {
        $model = new OrderModel;
        $list = $model->getList($filter);
        return $this->fetch('index',compact('title','list'));
    }

    /**
     * 订单详情
     * @param $order_id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail($order_id)
    {
       $detail = OrderModel::detail($order_id);
        return $this->fetch('detail',compact('detail'));
    }

    /**
     * 确认发货
     * @param $order_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delivery($order_id)
    {
        $model = OrderModel::detail($order_id);
        if($model->delivery($this->postData('order'))){
            return $this->renderSuccess('发货成功');
        }
        $error = $model->getError()?:'发货失败';
        return $this->renderError($error);
    }

}