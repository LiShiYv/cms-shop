<?php
/**
 * Created by PhpStorm.
 * User: 李师雨
 * Date: 2019/1/23
 * Time: 8:43
 */

namespace App\Http\Controllers\login;
use App\Model\CmsModel;
use Illuminate\Http\Request;

class loginIndexController
{
 public function login(){
     return view('login.index');

 }
 public function loginadd(Request $request)
 {
     $name=$request->input('u_name');
    //echo $name;
     $pwd=$request->input('pwd');
     //echo $pwd;die;
     //echo $_POST;die;
    //$res=CmsModel::where(['id'=>$id]);
     $id2 = Cmsmodel::where(['u_name'=>$name])->first();
     //var_dump($id2);
     if($id2){
         if(password_verify($pwd,$id2->pwd)){
             $token = substr(md5(time().mt_rand(1,99999)),10,10);
             setcookie('token',$token,time()+86400,'/','cms.com',false,true);
             setcookie('u_name',$id2->u_name,time()+86400,'/','cms.com',false,true);
             setcookie('id',$id2->id,time()+86400,'/','cms.com',false,true);
             $request->session()->put('u_token',$token);
             $request->session()->put('u_name',$id2->u_name);
             $request->session()->put('id',$id2->id);

             header("Refresh:3;url=/show");
             echo '登录成功';
         } else {
             die('密码或用户名错误');

         }
     }else{
         die('用户不存在');
     }

 }
//展示
public function show(){
     $res=CmsModel::all();
     $data=[
         'res'=>$res
     ];
     return view('login.list',$data);
}
//修改
public function update($id){
 $data=CmsModel::where(['id'=>$id])->first()->toArray();

 $list=[
     'data'=>$data
 ];
//dump($list);exit;
 return view('login.update',$list);

     //echo $id;die;
}
public function updateup(){
   $id=$_POST['id'];
  // echo $id;exit;
     $where=[
         'id'=>$id
     ];

    $data=[
        'u_name'=>$_POST['u_name'],
        'pwd'=>password_hash($_POST['pwd'],PASSWORD_BCRYPT),

        //'up_time'=>time(),
    ];

    $res=CmsModel::where($where)->update($data);
    if($res){
        echo '修改成功';
    }else{
        echo '修改失败';
    }
}
}