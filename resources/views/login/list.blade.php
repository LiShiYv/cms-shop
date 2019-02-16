<table border="1">

    <tr>
        <td>用户ID</td>
        <td>用户姓名</td>
        <td>操作</td>
    </tr>
    @foreach($res as $v)
    <tr>
        <td>{{$v['id']}}</td>
        <td>{{$v['u_name']}}</td>
        <td><a href="/update/{{$v['id']}}">修改</a></td>
    </tr>
   @endforeach
</table>