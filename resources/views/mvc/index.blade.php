@extends('layouts.new')
@section('content')
@if(Session::has('id'))
    <font size="6px" color="red">用户中心</font>
    @else
    <font size="6px" color="#00008b">游客中心</font>
    @endif
@endsection
