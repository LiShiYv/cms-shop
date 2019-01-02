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
class User extends Controller{
    public function test(){
        echo __FILE__;
    }
    public function add(){
        $data=[
            'root' => str_random(5).'@163.com',
            'age' => str_random(2),
            'sex' =>str_random(5)
        ];
        $add=Cmsmodel::insert($data);
        //var_dump($data);
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
}