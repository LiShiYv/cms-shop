<html lang="en">
    <head>
        <h1>客服</h1>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <script src="js/jquery-1.12.4.min.js"></script>
        {{csrf_field()}}
    </head>
    <body>
         <form>
            <table>
               <tr>
                   <td>聊天记录：<div type="text" name="weixin" style="width: 50px;height: 60px" ></div></td>
               </tr>
                <input type="hidden" name="show_id" id="show_id">
                <tr>
                    <td>请输入：<input type="text" name="weixin" id="weixin" ></td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" class="btn btn-default" id="test">发送</button>
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
            var show_id=$('#show_id').val();
            //console.log(show_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url     :   '{{'/weixin/service'}}',
                type    :   'post',
                data    :   {weixin:weixin,show_id:show_id},
                dataType:   'json',
                success :   function(d){
                    if(d.error!==0){
                        alert(d.msg);
                      //  window.location.href='/weixin/service';
                    }else{
                        window.location.href=d.url;
                    }
                }
            });
        })
    });
</script>

