<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/2/18
 * Time: 13:56
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WxUser extends Model
{
    public $table = 'wx_users';
    public $timestamps = false;
}