<form action="/loginupdate" method="post">
    {{csrf_field()}}


  <input type="hidden" name="id" value="{{$data['id']}}">
    <p> <input type="text" name="u_name" value="{{$data['u_name']}}"><p>
    <p><input type="password" name="pwd" value="{{$data['pwd']}}"><p>
        <input type="submit" value="修改">

</form>