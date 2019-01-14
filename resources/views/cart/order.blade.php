@extends('layouts.new')
@section('content')

    <b><font size="7px" color="#ffe4c4">欢迎 </font></font><font size="5px" color="red">{{$_COOKIE['u_name']}}</font></b>
    <p>这里是 订单
    <table border="1"  class="table table-bordered">
        <thead>
        <td>ID</td><td>订单号</td><td>金额</td><td>下单时间</td><td>操作</td>
        </thead>
        <tbody>
        @foreach($deta as $v)
            <tr o_id="{{$v['o_id']}}">
                <td><?php echo $v['o_id']?></td>
                <td>{{$v['order_sn']}}</td>
                <td>{{$v['order_amount']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td>
                <td><a class="del_order btn btn-warning " >取消订单1</a><a href="/order/orderdel/{{$v['o_id']}}"><font color="red" class="btn btn-info">取消订单</font></a> <a href="/pay/order/{{$v['o_id']}}" class="btn btn-warning"><font color="#ffd700">立即支付</font></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
@section('footer')
    @parent
    <script src="{{URL::asset('/js/order/order.js')}}"></script>
@endsection
