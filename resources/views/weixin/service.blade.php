@extends('layouts.new')

@section('content')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form>
    <h1>客服</h1>
    {{csrf_field()}}

    <textarea type="text" name="weixin"  cols="60" rows="20">聊天记录：</textarea>
     请输入：<input type="text" name="weixin" id="weixin" >
    <button type="submit" class="btn btn-default" id="text">发送</button>
</form>
</body>
</html>
@endsection
@section('footer')
    @parent
    <script src="{{URL::asset('/js/weixin/weixin.js')}}"></script>
@endsection
