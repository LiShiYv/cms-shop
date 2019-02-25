$("#text").click(function(e){
    e.preventDefault();
    _this=$(this);
    var weixin= $("#weixin").val();
    var openid=$('.openid').val();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/weixin/wxservice',
        type    :   'post',
        data    :   {weixin:weixin,openid:openid},
        dataType:   'json',
        success :   function(res){
            if(res.code==0){
                var _weixin="<h6>未凉客服&nbsp;：&nbsp;"+weixin+"</h6>"
                $('#content').append(_weixin);
                $('#weixin').val('');
            }else{
                alert(res);
            }
        }
    });

});
setInterval(function () {
    var openid=$('.openid').val();
    var nickname=$('.nickname').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        contentType : "application/x-www-form-urlencoded; charset=UTF-8",
        url:  '/weixin/wxservices',
        type:'post',
        data:{openid:openid},
        dataType:'json',
        success:function (res) {
            $('#content').html('');
            $.each(res,function (i,n) {
                if(n['nickname']=='未凉客服'){
                    _weixinnew="<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;未凉客服&nbsp;: &nbsp;"+n['text']+"</h5>"
                }else{
                    _weixinnew="<h5>"+nickname+"&nbsp;: &nbsp;"+n['text']+"</h5>"
                }

                $('#content').append(_weixinnew)
            })
            /* console.log(res.recorddata);
             _newcontent=res.recorddata;
             $('#content').html(_newcontent);*/
        }
    })
},1000)

