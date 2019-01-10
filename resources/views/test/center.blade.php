@extends('layouts.new')


@section('content')

    <b><font size="3px" color="#00008b">欢迎 </font><font size="3px" color="red">{{$_COOKIE['u_name']}}</font></b>
    <p>
        <button type="submit" class="btn btn-default"><a href="/cart/goods">商品区</a></button>
        <a href="/cart" class="btn btn-default">进入购物车</a>
    </p>
@endsection