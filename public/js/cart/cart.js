$(".del_goods").click(function(d){
    d.preventDefault();
    var _this=$(this);
    var cart_id =_this.parents('tr').attr('cart_id');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url :'/cart/del1',
        type : 'post',
        data : {cart_id:cart_id},
        dataType :'json',
        success : function(del1){
            if(del1.error==301){
                window.location.href=url;
            }else{
                alert('删除成功');
                _this.parents('tr').remove();
            }
        }
    })
})
