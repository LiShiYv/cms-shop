
@extends('layouts.new')


@section('content')

    <form  action="/userregs" method="post">
        <h1>注册</h1>
        {{csrf_field()}}

        <div class="form-group" >
            <label for="inputEmail3" class="col-sm-2 control-label">昵称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" placeholder="请输入昵称" name="u_name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="请输入密码" name="u_pwd">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label"><font size="1px;">确认密码</font></label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="请输入一次密码" name="u_pwd1">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" placeholder="请输入邮箱" name="u_email">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">年龄</label>
            <div class="col-sm-10">
                <select class="form-control" name="u_age">
                    <option>请选择</option>
                    @for($i=16;$i<=50;$i++){

                    <option>{{$i}}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>
@endsection

