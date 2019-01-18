<?php

namespace App\Http\Controllers\Cart;
use App\Model\CartModel;
use App\Model\GoodsModel;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class CartController extends Controller
{
    public $id;

    public function __construct()
    {

     $this->middleware('auth');
    }



    public function cart(Request $request)
    {
        // $goods=session()->get('cart_goods');
        //var_dump($goods);
        //if(empty($goods)){
        //   echo '购物车太空的';
        //  }else{
        //  foreach($goods as $k=>$v){
        //  echo 'Goods ID: '.$v;echo '</br>';
        //     $detail =GoodsModel::where(['goods_id'=>$v])->first()->get();
        //  print_r($v);exit;
        ///  $data = [
        ///   'title'     => '商品展示',
        //   'detail'      => $detail
        //  ];
        //  print_r($data);exit;
        //  return view('cart.goods',$data)->with('detail',$detail);
        // echo '<pre>';print_r($detail);echo '</pre>';
        // }

        //}
        $uid = $this->id;
        if (!empty($uid)) {
            $cart_goods = CartModel::where(['id' => $uid])->get()->toArray();


            //echo $id;exit;
            // $cart_goods=CartModel::where(['id'=>$id])->get()->toArray();
//echo 111;exit;
            //print_r($cart_goods);exit;
            if (empty($cart_goods)) {
                header("Refresh:3;url=/goods/1");
                die('购物车太空了');
            }
            //echo $uid;exit;

            // echo 11;exit;
            if ($cart_goods) {
                //获取最新的信息
                foreach ($cart_goods as $k => $v) {
                    $goods_info = GoodsModel::where(['goods_id' => $v['goods_id']])->first()->toArray();

                    //print_r($goods_info);exit;
                    $goods_info['cart_id'] = $v['cart_id'];
                    $goods_info['goods_num'] = $v['goods_num'];
                    //echo '<pre>';print_r($goods_info);echo '</pre>';
                    $list[] = $goods_info;
                }
            }

            $data = [
                'list' => $list
            ];
            return view('cart.index', $data);
        } else {
            header("Refresh:3;url=/userlogin");
            die('请先登录');
        }
    }

    //添加购物车
    public function add($goods_id)
    {
        $re = GoodsModel::where(['goods_id' => $goods_id])->first();
        if (empty($re)) {
            echo '非法操作';
            die;
        }
        $cart_goods = session()->get('cart_goods');
        //查看是否已在购物车中
        if (!empty($cart_goods)) {
            if (in_array($goods_id, $cart_goods)) {
                echo '已在购物车';

            }
        }
        session()->push('cart_goods', $goods_id);
        //减库存
        $where = ['goods_id' => $goods_id];
        $store = GoodsModel::where($where)->value('store');
        if ($store <= 0) {
            echo '库存不足';
            exit;
        }
        $rz = GoodsModel::where(['goods_id' => $goods_id])->decrement('store');
        if ($rz) {
            echo '提交成功';
        }
    }

    public function add2(Request $request)
    {
        $uid = $this->id;
        if (!empty($uid)) {


            $goods_id = $request->input('goods_id');
            $num = $request->input('goods_num');

            //写入购物车表
            $data = [
                'goods_id' => $goods_id,
                'goods_num' => $num,
                'reg_time' => time(),
                'id' => session()->get('id'),
                'token' => session()->get('u_token')
            ];

            $cid = CartModel::insertGetId($data);
            if (!$cid) {
                $response = [
                    'errno' => 5002,
                    'msg' => '添加购物车失败，请重试'
                ];
                return $response;
            }


            $response = [
                'error' => 0,
                'msg' => '添加成功'
            ];
            return $response;
        } else {
            header("Refresh:3;url=/userlogin");
            die('请先登录');
        }
    }
    //删除购物车
    public function del($goods_id)
    {
        $re = GoodsModel::where(['goods_id' => $goods_id])->first();
        if (empty($re)) {
            echo '非法操作';
            die;
        }
        //判断 商品是否存在
        $goods = session()->get('cart_goods');
        // echo '<pre>';print_r($goods);echo '</pre>';
        if (in_array($goods_id, $goods)) {
            //删除
            foreach ($goods as $k => $v) {
                if ($goods_id == $v) {
                    session()->pull('cart_goods.' . $k);
                }

            }
        } else {
            //不在购物车中
            die('购物车没有商品');
        }
    }

    public function del2($cart_id)
    {
        $del = CartModel::where(['id' => $this->id, 'cart_id' => $cart_id])->delete();
        if ($del) {
            header("Refresh:3;url=/cart");
            echo '删除成功';
        }
    }

    public function del1(Request $request)
    {
        $cart_id = $request->input('cart_id');

        //判断
        $del1 = CartModel::where(['id' => $this->id, 'cart_id' => $cart_id])->delete();
        if ($del1) {
            $response = [
                'error' => 0,
                'msg' => '删除成功'
            ];
            return $response;
        } else {
            $response = [
                'error' => 5003,
                'msg' => '删除失败'
            ];
            return $response;
        }

    }

    public function del6(Request $request)
    {
        $cart_id = $request->input('cart_id');

        //判断
        $del1 = CartModel::where(['id' => $this->id, 'cart_id' => $cart_id])->delete();
        if ($del1) {
            $response = [
                'error' => 0,
                'msg' => '删除成功'
            ];
            return $response;
        } else {
            $response = [
                'error' => 5003,
                'msg' => '删除失败'
            ];
            return $response;
        }

    }
}