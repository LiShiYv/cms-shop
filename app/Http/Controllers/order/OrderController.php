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
    public function __construct()
    {
        $this->middleware('auth');
    }
    //下单
    public function add(Request $request){
        //查询购物车
        $cart_goods=CartModel::where(['id'=>session()->get('id')])->orderBy('id','desc')->get()->toArray();
        if(empty($cart_goods)){
            die('购物车还没有商品');
        }
        //查询单个商品的信息
        $order_amount =0;
        foreach($cart_goods as $k=>$v){
            $goods_info=GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goods_info['goods_num']=$v['goods_num'];
            $list[]=$goods_info;
            //计算订单价格=数量乘以单个商品的价格
            $order_amount+=$goods_info['goods_price']*$v['goods_num'];
        }
    //生成订单号
        $order_sn=OrderModel::getModelOrder();
        //echo $order_sn;
        $data=[
            'order_sn' =>$order_sn,
            'id' =>session()->get('id'),
            'reg_time'=>time(),


            'order_amount'=>$order_amount
        ];
        $oid=OrderModel::insertGetId($data);
        //echo $oid;exit;
        if(!$oid){
            echo ('生成失败，请重试');
        }
        header('Refresh:2;url=/order');
        echo '下单成功,订单号：'.$order_sn .'';

        CartModel::where(['id'=>session()->get('id')])->delete();
    }
    public function ordershow(){
        $uid = session()->get('id');
        if(!empty($uid)){
            $deta= OrderModel::where(['id' => $uid,'is_del'=>1,'is_pay'=>1])->get()->all();
            // print_r($detail);exit;
            // print_r($v);exit;
            $data = [
                'title'     => '表单展示',
                'deta'      => $deta,


            ];
            //  print_r($data);exit;
            return view('cart.order',$data)->with('deta',$deta);
        }else{
            die('您还没有登录 请先登录');
        }
    }



    public function orderdel($o_id){

        $del = OrderModel::where(['o_id' => $o_id])->update(['is_del'=>2]);
        if ($del) {

            header("Refresh:3;url=/order");
            echo '删除成功';
        }
    }
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
        $order_sn=OrderModel::getModelOrder();
        $data=[
            'order_sn' =>$order_sn,
            'id' =>session()->get('id'),
            'reg_time'=>time(),

            'order_amount'=>$order_amount
        ];
        $oid=OrderModel::insertGetId($data);
        if($oid){
            CartModel::where(['id'=>session()->get('id')])->delete();
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
public function fly(){
        echo 111;
}


}