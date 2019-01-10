<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/1/8
 * Time: 14:30
 */

namespace App\Http\Middleware;

use Closure;
class CheckCookiesId
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty($_COOKIE['id'])){
            header('Refresh:2;url=/userlogin');
            echo 'No ID ，请先登录';echo '</br>';
        }
        return $next($request);
    }


}