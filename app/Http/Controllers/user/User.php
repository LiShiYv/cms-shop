<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2018/12/29
 * Time: 9:17
 */
namespace  App\Http\Controllers\User;
use App\Model\Cmsmodel;

use Illuminate\Routing\Controller;
use DB;
class User extends Controller{
    public function test(){
        var_dump('$_GET');echo '<br>';
        var_dump('$_POST');echo'<br>';
    }
    public function add(){
        $data=[
            'root' => str_random(5).'@163.com',
            'age' => str_random(2),
            'sex' =>str_random(5)
        ];
        $add=Cmsmodel::insert($data);
        var_dump($data);
    }
    public function update($id){
        $data=[
            'sex' => str_random(4).'@qq.com',
        ];
        $where=[
            'id'=>$id
        ];
        $update=Cmsmodel::where($where)->update($data);

       var_dump($update);
    }
    public function delete($id){
            $where=[
                'id'=>$id
            ];
          $delete=Cmsmodel::where($where)->delete();
         var_dump($delete);
    }
    public function list(){
        $list=Cmsmodel::all();
       $data=[
           'list'=>$list
       ];
       return view('user.index',$data);

    }
    public function world1(){
        echo __METHOD__;
    }
    public function hello2()
    {
        echo __METHOD__;
        header('Location:/world2');
    }
    public function world2()
    {
        header('Location:http://www.baidu.com');
    }

    public function md($m,$d)
    {
        echo 'm: '.$m;echo '<br>';
        echo 'd: '.$d;echo '<br>';
    }
}