<?php
namespace app\api\model;

use app\common\model\Category as CategoryModel;

class Category extends CategoryModel
{
    protected $hidden = [
        'wxapp_id',
        'update_time'
    ];
}