<?php

namespace App\Http\Middleware;

use Closure;

class CheckCookies
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
        if(!$request->session()->get('u_token')){
            header('Refresh:2;url=/userlogin');
            echo '请先登录';
            exit;
        }
        return $next($request);
    }
}