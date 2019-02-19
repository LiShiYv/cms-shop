<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;

class WeixinController extends Controller
{
    //

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token



    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent()
    {
        $data = file_get_contents("php://input");


        //解析XML
        $xml = simplexml_load_string($data);
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);        //将 xml字符串 转换成对象
       // var_dump($data);
        $event = $xml->Event;                       //事件类型
        //var_dump($xml);echo '<hr>';
        $openid = $xml->FromUserName;               //用户openid
        $sub_time = $xml->CreateTime;           //扫码关注时间
        //获取用户信息
        $user_info = $this->getUserInfo($openid);

        if($event=='subscribe'){

            //保存用户信息
            $u = WeixinUser::where(['openid'=>$openid])->first();

            //echo 'openid: '.$openid;echo '</br>';
            //echo '$sub_time: ' . $sub_time;

           // echo '<pre>';print_r($user_info);echo '</pre>';

           // var_dump($u);die;
          //
            if($u){       //用户不存在
                // '用户已存在';

                $user_where=['openid'=> $openid];
                $user_update=[
                    'nickname'          => $user_info['nickname'],
                    'sex'               => $user_info['sex'],
                    'headimgurl'        => $user_info['headimgurl'],
                    'subscribe_time'    => $sub_time,
                ];
                $res=WeixinUser::where($user_where)->update($user_update);

            }else{
                //用户不存在

                $user_data = [
                    'openid'            => $openid,
                    'add_time'          => time(),
                    'nickname'          => $user_info['nickname'],
                    'sex'               => $user_info['sex'],
                    'headimgurl'        => $user_info['headimgurl'],
                    'subscribe_time'    => $sub_time,
                ];

                $id = WeixinUser::insertGetId($user_data);      //保存用户信息
               // var_dump($id);
            }
        }

    }




    /**
     * 接收事件推送
     */

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }




//创建菜单
public function wxMenu(){
        //1.获取菜单接口
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();
    echo $url;echo '</br>';
    //2.请求微信接口
    $client = new GuzzleHttp\Client(['base_uri'=>$url]);
    $data =[
        'button'  =>[
            [
                "type" =>"view",
                "name " =>"未凉",
                "url"  => "https://www.baidu.com",
            ]
        ]
    ];
$w =$client->request('POST',$url,[
    'body'=>json_encode($data)
]);
var_dump($data);
//3.解析微信接口返回的信息
    $response_arr = json_decode($w->getBody(),true);
    var_dump($response_arr);
    if($response_arr['errcode'] == 0){
        echo "菜单创建成功";
    }else{
        echo "菜单创建失败，请重试";echo '</br>';
        echo $response_arr['errmsg'];

    }
}

}
