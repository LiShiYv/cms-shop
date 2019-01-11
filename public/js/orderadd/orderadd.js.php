$('#submit_order').click(function(t){
    t.preventDefault();
    var _this=$(this);
    var cart_id=_this.parents('tr').attr('cart_id');
    var goods_num=_this.parents('tr').attr('goods_num');
    var goods_price=_this.parents('tr').attr('goods_price');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN':$('meta[name="csxf-token"]').attr('content')
        },
        url :'/order/add2',
        type :'post',
        data :{cart_id:cart_id,goods_num:goods_num,goods_price:goods_price},
        dataType :'json',
        success :function(add){
            if(add.error==301){
                window.location.href=url;
            }else{
                alert("提交成功");
                _this.parents('tr').remove();
            }
        }
    })
})
})