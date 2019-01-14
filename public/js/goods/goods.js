$("#add_cart_btn").click(function(e){
    e.preventDefault();
    var goods_num = $("#goods_num").val();
    var goods_id = $("#goods_id").val();

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/cart/add2',
        type    :   'post',
        data    :   {goods_id:goods_id,goods_num:goods_num},
        dataType:   'json',
        success :   function(d){
            if(d.error!==301){
                alert(d.msg);
                window.location.href='/cart';
            }else{
                window.location.href=d.url;
            }
        }
    });
});
