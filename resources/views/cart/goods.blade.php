@extends('layouts.new')
@section('content')
@if(Session::has('id'))
    <p><font size="6px" color="aqua">欢迎 </font><font size="6px" color="#fafad2">{{$_COOKIE['u_name']}}</font></p>
    <p>这里是 商品
    <table border="1"  class="table table-bordered">
        <thead>
        <td>ID</td><td>商品名称</td><td>商品价格</td><td>上架时间</td><td>商品详情</td>
        </thead>
        <tbody>
        @foreach($detail as $v)
            <tr>
                <td><?php echo $v['goods_id']?></td>
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['goods_price']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td>
                <td><a href="/goods/{{$v['goods_id']}}" class="btn btn-warning">商品详情</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <p>这里是 商品
    <table border="1"  class="table table-bordered">
        <thead>
        <td>ID</td><td>商品名称</td><td>商品价格</td><td>上架时间</td><td>商品详情</td>
        </thead>
        <tbody>
        @foreach($detail as $v)
            <tr>
                <td><?php echo $v['goods_id']?></td>
                <td>{{$v['goods_name']}}</td>
                <td>{{$v['goods_price']}}</td>
                <td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td>
                <td><a href="/goods/{{$v['goods_id']}} " class="btn btn-inverse">商品详情</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
@endsection


