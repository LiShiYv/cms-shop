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
                   <td>聊天记录：<textarea type="text" name="weixin"  cols="60" rows="20"></textarea></td>
               </tr>
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
        $('#weixin').click(function() {
            var _this = $(this);

            var weixin = _this.text();
            console.log(weixin);
        })
    });
</script>

