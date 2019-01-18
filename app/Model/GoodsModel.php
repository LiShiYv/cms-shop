<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/1/8
 * Time: 16:33
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    protected $table='goodsmodel';
    public $timestamps = false;
    public $primaryKey = 'goods_id';



    //获取某字段时 格式化 该字段的值
    public function getGoodsPriceAttribute($GoodsPrice)
    {
        return $GoodsPrice / 100;
    }

    //获取某字段时 格式化 该字段的值
    public function getStoreAttribute($store)
    {
        return '>' . $store .' <';
    }

}