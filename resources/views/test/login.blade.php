
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
        </div>
    </div>
</form>
@endsection