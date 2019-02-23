<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods',GoodsController::class);
    $router->resource('/User',UserController::class);
    $router->resource('/weixin',WeixinController::class);
    $router->resource('/media',WxmediaController::class);
    $router->resource('/type',TypeController::class);
    $router->resource('/message',MessageController::class);
    $router->post('/message','MessageController@type');
    $router->post('/type','TypeController@formTest');
    $router->get('/weixin/service{show_id}','WeixinController@service');
});
