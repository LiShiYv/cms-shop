@extends('layouts.new')


@section('content')
<html lang="en">
<head>
    <h1>客服</h1>
    <h2>正在和“<font color="red">{{$nickname}}</font>”聊天中</h2>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<form>
    <table>
        <tr>
            <td>聊天记录</td>
            <td><div style="width:600px;height:700px;overflow:auto;border: solid black 1px" id="content"></div></td>
        </tr>
        <input type="hidden" class="openid" value="{{$openid}}">
        <input type="hidden" class="nickname" value="{{$nickname}}">
        <tr>
            <td>请输入：<input type="text" id="weixin"></td>
        </tr>
        <tr>
            <td>
                <input type="button" class="btn btn-default" id="text" value="发送">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
@endsection
@section('footer')
    @parent
    <script src="{{URL::asset('/js/weixin/weixin.js')}}"></script>
@endsection