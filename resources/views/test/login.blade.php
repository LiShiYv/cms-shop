
@extends('layouts.new')


@section('content')

<form  action="/userlogins" method="post">
    <h1>登录</h1>
    {{csrf_field()}}

    <div class="form-group" style="width: 360px;" >
        <label for="inputEmail3" class="col-sm-2 control-label">账号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="inputEmail3" placeholder="请输入昵称" name="u_name">
        </div>
    </div>
    <div class="form-group" style="width:360px;">
        <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="inputPassword3" placeholder="请输入密码" name="u_pwd">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">提交</button>
            <a href="https://open.weixin.qq.com/connect/qrconnect?appid=wxe24f70961302b5a5&amp;redirect_uri=http%3a%2f%2fmall.77sc.com.cn%2fweixin.php%3fr1%3dhttp%3a%2f%2flsy.52self.cn%2fweixin%2fgetcode&amp;response_type=code&amp;scope=snsapi_login&amp;state=STATE#wechat_redirect"><font  class="btn btn-default" color="#006400">微信登录</font></a>
        </div>
    </div>
</form>
@endsection