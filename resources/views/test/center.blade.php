@extends('layouts.new')


@section('content')
    <div class="container">
        @if(Session::has('id'))
            <h1 style="color: red">用户中心</h1>
        @else
            <h1 style="color: red">游客中心</h1>
        @endif
    </div>

    <p>
        <button type="submit" class="btn btn-default"><a href="/cart/goods">商品区</a></button>
        <a href="/cart" class="btn btn-default">进入购物车</a>
    </p>
@endsection