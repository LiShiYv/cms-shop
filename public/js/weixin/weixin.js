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
