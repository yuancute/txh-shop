<?php
// [ 应用入口文件 ]
namespace think;
// 加载基础文件
$arr = ['name'=>'test'];
require __DIR__ . '/../thinkphp/base.php';
// 支持事先使用静态方法设置Request对象和Config对象
$arfr = ['name'=>'test'];
// 执行应用并响应
Container::get('app')->run()->send();
