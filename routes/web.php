<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   // echo date('Y-m-d H:i:s' );
   // echo '<pre>';print_r($_COOKIE);echo '</pre>';
    return view('welcome');
});
Route::get('user','user\User@test');
Route::get('vip/{id}','vip\vip@vip');
Route::get('user/add','user\User@add');
Route::get('user/update/{id}','user\User@update');
Route::get('user/update/{id}','user\User@update');
Route::get('user/delete/{id}','user\User@delete');
Route::get('/month/{m}/date/{d}','user\User@md');
//路由跳转
//Route::redirect('/hello1','/world1',301);
//Route::get('/world1','Test\TestController@world1');

//Route::get('hello2','Test\TestController@hello2');
//Route::get('world2','Test\TestController@world2');
//view视图
//Route::view('/mvc','mvc');
//Route::view('/error','error',['code'=>40300]);


// Query Builder
//Route::get('/query/get','Test\TestController@query1');
//Route::get('/query/where','Test\TestController@query2');


//Route::match(['get','post'],'/test/abc','Test\TestController@abc');
//Route::any('/test/abc','Test\TestController@abc');


Route::get('/view/test1','Test\TestController@viewtest1');
//Route::get('/view/test2','Test\TestController@viewtest2');
//用户注册
Route::get('/userreg','Test\TestController@reg');
Route::post('/userregs','Test\TestController@toReg');
//用户登录
Route::get('/userlogin','Test\TestController@users');
Route::post('/userlogins','Test\TestController@userAdd');
Route::get('/center','Test\TestController@center');        //个人中心
//引入静态文件
//首页
Route::get('/mvc/test1','Mvc\MvcController@test1');
Route::get('mvc/test2','Mvc\MvcController@test2');
//用户退出
Route::get('/userquit','Test\TestController@quit');
//cookie
//Cookie
//Route::get('/test/cookie1','Test\TestController@cookieTest1');
//Route::get('/test/cookie2','Test\TestController@cookieTest2');
//Route::get('/test/session','Test\TestController@sessionTest');
//Route::get('/test/mid1','Test\TestController@mid1')->middleware('check.uid');        //中间件测试
//Route::get('/test/check_cookie','Test\TestController@checkCookie')->middleware('check.cookie');        //中间件测试
//购物车
//Route::get('/cart','Cart\IndexController@index')->middleware('check.uid');
Route::get('/cart','Cart\CartController@cart');
Route::get('/cart/add/{goods_id}','Cart\CartController@add');      //添加商品
Route::get('/cart/del/{goods_id}','Cart\CartController@del');
Route::post('/cart/add2','Cart\CartController@add2');
Route::get('/cart/del2/{goods_id}','Cart\CartController@del2');
Route::post('/cart/del1','Cart\CartController@del1');
//商品展示
Route::get('/cart/goods','Test\TestController@show');
//商品详情
Route::get('/goods/{goods_id}','Goods\GoodsController@good');
//下单
Route::get('/order/add','order\OrderController@add');
Route::get('/order','order\OrderController@ordershow');
Route::get('/order/orderdel/{o_id}','order\OrderController@orderdel');
Route::post('/order/del1','order\OrderController@del1');
Route::post('/order/add2','order\OrderController@add2');
//支付
Route::get('/pay/order/{o_id}','Pay\AlipayController@pay');         //订单支付
Route::post('/pay/alipay/notify','Pay\AlipayController@notify');        //支付宝 通知回调
Route::get('/pay/alipay/suys','Pay\AlipayController@suys'); //支付宝 异步通知
Route::get('/pay/alipay/test','Pay\AlipayController@test');         //测试

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
