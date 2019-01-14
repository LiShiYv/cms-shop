$(".del_order").click(function(del){
    del.preventDefault();
   // alert(111);
    var _this=$(this);
    var o_id=_this.parents('tr').attr('o_id');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        url :'/order/del1',
        type :'post',
        data :{o_id:o_id},
        dataType :'json',
        success :function(del){
            if(del.error==301){
                window.location.href=url;
            }else{
                alert('取消成功');
                _this.parents('tr').remove();
            }
        }
    })

})