$("#weixin").click(function(e){
    e.preventDefault();
    var weixin= $("#weixin").val();


    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/weixin/wxservice',
        type    :   'post',
        data    :   {weixin:weixin},
        dataType:   'json',
        success :   function(d){
            if(d.error!==0){
                alert(d.msg);
                window.location.href='/weixin/service';
            }else{
                window.location.href=d.url;
            }
        }
    });
});
