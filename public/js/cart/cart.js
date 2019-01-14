$(".del_goods").click(function(d) {
    d.preventDefault();
    var _this = $(this);
    var cart_id = _this.parents('tr').attr('cart_id');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/cart/del1',
        type: 'post',
        data: {cart_id: cart_id},
        dataType: 'json',
        success: function (del1) {
            if (del1.error == 301) {
                window.location.href = url;
            } else {
                alert('取消成功');
                _this.parents('tr').remove();
            }
        }
    });
})
    $(".add_order").click(function(rr){
        //alert(111);
        rr.preventDefault();
        var _this=$(this);
        var cart_id=_this.parents('tr').attr('cart_id');
        var goods_num=_this.parents('tr').attr('goods_num');
        var goods_price=_this.parents('tr').attr('goods_price');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url :'/order/add2',
            type :'post',
            data :{cart_id:cart_id,goods_num:goods_num,goods_price:goods_price},
            dataType :'json',
            success :function(add){
                if(add.error!==301){
                    alert("下单成功");
                    window.location.href='/order';
                    _this.parents('tr').remove();
                }else{
                    window.location.href=url;

                }
            }
        })
    });

