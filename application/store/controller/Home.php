<?php
namespace app\store\controller;

use app\api\model\Order;

class Home extends Controller
{
    public function index()
    {
        return $this->fetch('index');
    }
}