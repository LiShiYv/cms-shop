<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/1/8
 * Time: 16:40
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\GoodsModel;
class CartModel extends Model
{
//商品的id
    public $table = 'cartmodel';
    public $timestamps = false;
public function goodsInfo($goods_id){
    return GoodsModel::where(['goods_id'=>$goods_id])->get();
}
}