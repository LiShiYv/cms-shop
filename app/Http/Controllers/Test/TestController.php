<?php

namespace App\Http\Controllers\Test;

    use App\Model\Cmsmodel;
    use App\Model\GoodsModel;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Session;


class TestController extends Controller
{
//    public function __construct()
//       {
//           $this->middleware('auth');
//      }



    public function reg(){
        return view('test.reg');
        return view('layouts.new');
    }
    public function toreg(Request $request){
       // echo __METHOD__;
        $u_name = $request->input('u_name');

        $w = Cmsmodel::where(['u_name'=>$u_name])->first();
        if($w){
            die("用户名已存在");

        }
        $pass1=$request->input('u_pwd');
        $pass2=$request->input('u_pwd1');
        if($pass1 !==$pass2){
            die( '密码必须保持一致');
        };
        $pass = password_hash($pass1,PASSWORD_BCRYPT);
       // echo '<pre>';print_r($_POST);echo '</pre>';
        $data=[
            'u_name' => $request->input('u_name'),
            'u_email' =>$request->input('u_email'),

            'age' =>$request->input('u_age'),
            'pwd'=>$pass,
            'reg_time' =>time()
        ];
        $id=Cmsmodel::insertGetId($data);
        //var_dump($id);
        if($id){
            setcookie('u_name',$u_name,time()+86400,'/','lsy.52self.cn',false,true);

            setcookie('id',$id,time()+86400,'/','lsy.52self.cn',false,true);
            header("Refresh:3;url=/center");
            echo '注册成功 正在跳转';
        }else{
            echo '注册失败';
            header("Refresh:3;url=userreg");
        }
    }
    //登录
    public function users(){
        return view('test.login');
    }
    public function userAdd(Request $request)
    {
        // echo __METHOD__;
        // echo '<pre>';print_r($_POST);echo '</pre>';
        $pass = $request->input('u_pwd');
        $root=$request->input('u_name');

        $id2 = Cmsmodel::where(['u_name'=>$root])->first();
        //var_dump($id2);
            if($id2){
                if(password_verify($pass,$id2->pwd)){
                    $token = substr(md5(time().mt_rand(1,99999)),10,10);
                    setcookie('token',$token,time()+86400,'/','lsy.52self.cn',false,true);
                    setcookie('u_name',$id2->u_name,time()+86400,'/','lsy.52self.cn',false,true);
                    setcookie('id',$id2->id,time()+86400,'/','lsy.52self.cn',false,true);
                    $request->session()->put('u_token',$token);
                    $request->session()->put('u_name',$id2->u_name);
                    $request->session()->put('id',$id2->id);

                    header("Refresh:3;url=/center");
                    echo '登录成功';
                } else {
                    die('密码或用户名错误');

                }
            }else{
                die('用户不存在');
            }

        }

    public function center(Request $request)
    {

            return view('test.center');

    }


        //echo 'token: '.$request->session()->get('u_token'); echo '</br>';
     //   echo '<pre>';print_r($request->session()->get('u_token'));echo '</pre>';

        //echo '<pre>';print_r($_COOKIE);echo '</pre>';
       // if(empty($_COOKIE['u_name'])){
           // header('Refresh:2;url=userlogin');
          //  echo '请先登录';
          //  exit;
        //}//else{



   // }
    public function show(Request $request){

     $detail= GoodsModel::all()->toArray();
    // print_r($detail);exit;
        // print_r($v);exit;
         $data = [
         'title'     => '商品展示',
           'detail'      => $detail
         ];
        //  print_r($data);exit;
         return view('cart.goods',$data)->with('detail',$detail);
        // echo '<pre>';print_r($detail);echo '</pre>';
        }
        public function quit(Request $request){
        $request->session()->pull('id',null);
            $request->session()->pull('u_name',null);
            $request->session()->pull('u_token',null);
            echo '已退出';
        header('Refresh:2;url=/mvc/test1');

}
        //}


}
