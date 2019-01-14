@extends('layouts.new')

@section('content')

        <table border="1"  class="table table-bordered">
        <tr>
            <td>ID</td> <td>商品名称</td> <td>金额</td> <td>添加时间</td> <td>数量</td><td>操作</td>
        </tr>
            @foreach($list as $k=>$v)
                <tr cart_id="{{$v['cart_id']}}" goods_num="{{$v['goods_num']}}" goods_price="{{$v['goods_price']}}">

                    <td>  {{$v['cart_id']}}</td><td>{{$v['goods_name']}}</td><td>{{$v['goods_price']}}</td> <td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td> <td>{{$v['goods_num']}}</td><td> <a href="/cart/del2/{{$v['cart_id']}}" class="btn btn-info">删除</a> <a class="add_order btn btn-primary"> 下单 </a> <a class="del_goods  btn btn-info">删除2</a></td></tr>
               <hr>

            @endforeach

        </table>
        <a href="/order/add" class="btn btn-success" > 一键下单 </a>




@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/cart/cart.js')}}"></script>
@endsection