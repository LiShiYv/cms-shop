<?php

namespace App\Http\Controllers\Pay;

use App\Model\OrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AustController extends Controller
{
    //

    public function index(){

    }

    /**
     * 订单支付
     *
     */
    public function order($o_id){
        //查询订单-
        $order_info = OrderModel::where(['o_id'=>$o_id])->first();
        if(!$order_info){
            die("订单 ".$o_id. "不存在！");
        }
        //检查订单状态 是否已支付 已过期 已删除
        if($order_info->pay_time > 0){
            die("此订单已被支付，无法再次支付");
        }

        //调起支付宝支付



        header('Refresh:2;url=/pay/alipay/test');
        echo '正在跳转 支付';

    }
}
