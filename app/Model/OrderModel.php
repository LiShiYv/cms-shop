<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model{
    public $table='ordermodel';
    public $timestamps=false;
    //自动生成订单号
    public static function getModelOrder(){
        return rand(0214).rand(11111,99999).rand(22222,99999);
}
}