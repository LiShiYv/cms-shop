<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/1/9
 * Time: 10:47
 */

namespace App\Http\Controllers\Goods;

use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class GoodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function good($goods_id)
    {
        $uid = session()->get('id');
        if (!empty($uid)) {

            $goods = GoodsModel::where(['goods_id' => $goods_id])->first();

            //商品不存在
            if (!$goods) {
                header('Refresh:2;url=/cart/goods');
                echo '商品未找到,正在跳转至首页 请稍等...';
                exit;
            }

            $data = [
                'goods' => $goods
            ];
            return view('goods.index', $data);
        } else{
            header('Refresh:2;url=/userlogin');
            die('您还没有登录 请先登录');
        }


    }
}