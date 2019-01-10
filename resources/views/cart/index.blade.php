@extends('layouts.new')

@section('content')

        <table border="1"  class="table table-bordered">
        <tr>
            <td>ID</td> <td>商品名称</td> <td>金额</td> <td>添加时间</td> <td>数量</td><td>操作</td>
        </tr>
            @foreach($list as $k=>$v)
                <tr cart_id="{{$v['cart_id']}}">

                    <td>{{$v['cart_id']}}</td><td>{{$v['goods_name']}}</td><td>{{$v['goods_price']}}</td><td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td> <td>{{$v['goods_num']}}</td><td> <a href="/cart/del2/{{$v['cart_id']}}" >删除</a>  <a class="del_goods">删除2</a></td>
                </tr>
               <hr>

            @endforeach

        </table>
        <a href="/order/add" id="submit_order" > 提交订单 </a>


@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/goods/goods.js')}}"></script>
@endsection