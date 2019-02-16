

<form action="/loginadd" method="post">
    {{csrf_field()}}
    <p> <input type="text" name="u_name"><p>
    <p><input type="password" name="pwd"><p>
    <input type="submit" value="登录">

</form>
