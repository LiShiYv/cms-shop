<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/2/18
 * Time: 13:56
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WeixinUser extends Model
{
    public $table = 'p_wx_users';
    public $timestamps = false;
}