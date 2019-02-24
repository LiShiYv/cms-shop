<html lang="en">
    <head>
        <h1>客服</h1>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <script src="/js/jquery-1.12.4.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
         <form>
            <table>
               <tr>
                   <td>聊天记录</td>
                   <td><div style="width:400px;height:500px;overflow:auto;border: solid black 1px" id="content"></div></td>
               </tr>
                <input type="hidden" class="openid" value="{{$user_info['openid']}}">
                <input type="hidden" class="nickname" value="{{$user_info['nickname']}}">
                <tr>
                    <td>请输入：<input type="text" id="weixin"></td>
                </tr>
                <tr>
                    <td>
                        <input type="button" class="btn btn-default" id="test" value="发送">
                    </td>
                </tr>
            </table>
         </form>
    </body>
    </html>
<script>
    $(function(){
        $('#test').click(function() {
            _this=$(this);
            var weixin=$('#weixin').val();
            //console.log(weixin);
            var openid=$('.openid').val();
            //console.log(show_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url     :   '{{url("admin/weixin/service")}}',
                type    :   'post',
                data    :   {weixin:weixin,openid:openid},
                dataType:   'json',
                success :   function(res){
                    if(res.code==0){
                        var _weixin="<h6>小智客服&nbsp;：&nbsp;"+weixin+"</h6>"
                        $('#content').append(_weixin);
                        $('#weixin').val('');
                    }else{
                        alert(res);
                    }
                }
            });
        })
    });
</script>

