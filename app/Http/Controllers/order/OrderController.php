<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/1/9
 * Time: 18:17
 */

namespace App\Http\Controllers\order;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Model\OrderModel;
use App\Http\Controllers\Controller;
class OrderController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
//    //下单

    public function ordershow(){
        $uid = session()->get('id');
        //if(!empty($uid)){
        $deta=OrderModel::where(['id'=>$uid,'is_pay'=>1,'is_del'=>1])->paginate(5);
            // print_r($detail);exit;
            // print_r($v);exit;
            $data = [
                'title'     => '表单展示',
                'deta'      => $deta,


            ];
            //  print_r($data);exit;
            return view('cart.order',$data)->with('deta',$deta);
        }//else{
           // die('您还没有登录 请先登录');
       // }
    //}




    public function del1(Request $request){
            //
        $o_id=$request->input('o_id');
        $del1=OrderModel::where(['o_id'=>$o_id])->update(['is_del'=>2]);
        if($del1){
            $response=[
                'error'=>0,
                'msg'=>'删除成功',
            ];
            return $response;
        }else{
            $response=[
                'error'=>5003,
                'msg'=>'删除失败',
            ];
            return $response;
        }
    }
    public function add2(Request $request){
        $cart_id = $request->input('cart_id');
        $goods_num = $request->input('goods_num');
        $goods_price=$request->input('goods_price');
        $order_amount=$goods_num*$goods_price;
        $id=session()->get('id');
        $goods_id=$request->input('goods_id');
        $order_sn=OrderModel::getModelOrder();
            $data=[
                'order_sn'=>$order_sn,
                'id'=>$id,
                'goods_id'=>$goods_id,
                'goods_num'=>$goods_num,
                'order_amount'=>$order_amount,
                'reg_time'=>time(),
        ];
        $oid=OrderModel::insertGetId($data);
        if($oid){
            CartModel::where(['id'=>$id,'cart_id'=>$cart_id])->update(['is_delete'=>2]);
            $response=[
                'error'=>0,
                'msg'=>'下单成功',
            ];
            return $response;
        }else{
            $response=[
                'error'=>5003,
                'msg'=>'下单失败',
            ];
            return $response;
        }
    }
    //三表联查 订单详情


}