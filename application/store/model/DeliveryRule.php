<?php

namespace app\store\model;

use app\common\model\DeliveryRule as DeliveryRuleModel;

/**
 * 配送模板区域及运费模型
 * Class DeliveryRule
 * @package app\store\model
 */
class DeliveryRule extends DeliveryRuleModel
{
    protected $append = ['region_content'];

    static $regionAll;
    static $regionTree;

    public function getRegionContentAttr($value,$data)
    {
        $regionIds = explode(',',$data['region']);
        if(count($regionIds) === 373)return '全国';

        if(empty(self::$regionAll)){
            self::$regionAll = Region::getCacheAll();
            self::$regionTree = Region::getCacheTree();
        }

        $alreadyTree = [];
        foreach ($regionIds as $regionId){
            $alreadyTree[self::$regionAll[$regionId]['pid']][] = $regionId;
        }
        $str = '';
        foreach ($alreadyTree as $provinceId => $citys) {
            $str .= self::$regionTree[$provinceId]['name'];
            if (count($citys) !== count(self::$regionTree[$provinceId]['city'])) {
                $cityStr = '';
                foreach ($citys as $cityId)
                    $cityStr .= self::$regionTree[$provinceId]['city'][$cityId]['name'];
                $str .= ' (<span class="am-link-muted">' . mb_substr($cityStr, 0, -1, 'utf-8') . '</span>)';
            }
            $str .= '、';
        }
            return mb_substr($str, 0, -1, 'utf-8');
    }
}
