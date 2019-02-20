<?php

namespace App\Http\Controllers\WeiXin;

use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

class WeiXinController extends Controller
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
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);

        $event = $xml->Event;                       //事件类型
        $openid = $xml->FromUserName;               //用户openid


        // 处理用户发送消息
        if(isset($xml->MsgType)){
            if($xml->MsgType=='text'){            //用户发送文本消息
                $msg = $xml->Content;
                $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. $msg. date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response;
            }elseif($xml->MsgType=='image'){       //用户发送图片信息
                //视业务需求是否需要下载保存图片
                if(1){  //下载图片素材
                    $this->dlWxImg($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' .'当前时间是'. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;
                }
            }elseif($xml->MsgType=='voice'){
                $this->dlVoice($xml->MediaId);
                $xml_response1 = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . '当前时间是'.date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response1;


            }elseif($xml->MsgType=='video'){
                $this->dlVideo($xml->MediaId);
                $xml_response2 = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.' 当前时间是'. date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response2;
            }elseif($xml->MsgType=='event'){
//判断事件类型
                if($event=='subscribe'){                        //扫码关注事件

                    $sub_time = $xml->CreateTime;               //扫码关注时间


                    // echo 'openid: '.$openid;echo '</br>';
                    // echo '$sub_time: ' . $sub_time;

                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);
                    //  echo '<pre>';print_r($user_info);echo '</pre>';

                    //保存用户信息
                    $u = WeixinUser::where(['openid'=>$openid])->first();
                    //var_dump($u);die;
                    if($u){       //用户不存在
                        echo '用户已存在';
                    }else{
                        $user_data = [
                            'openid'            => $openid,
                            'add_time'          => time(),
                            'nickname'          => $user_info['nickname'],
                            'sex'               => $user_info['sex'],
                            'headimgurl'        => $user_info['headimgurl'],
                            'subscribe_time'    => $sub_time,
                        ];

                        $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                        //  var_dump($id);
                    }
                }elseif($event=='CLICK'){               //click 菜单
                    if($xml->EventKey=='kefu01'){       // 根据 EventKey判断菜单
                        $this->kefu01($openid,$xml->ToUserName);
                    }
                }


            }
            }

          
        }




    /**
     * 客服处理
     * @param $openid   用户openid
     * @param $from     开发者公众号id 非 APPID
     */
    public function kefu01($openid,$from)
    {
        // 文本消息
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$from.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. 'Hello World, 现在时间'. date('Y-m-d H:i:s') .']]></Content></xml>';
        echo $xml_response;
    }

    //下载图片素材


    public function dlWxImg($media_id)
    {
        $this->imgdlVoicedlVideo($pramn='image',$media_id);

    }


     //下载语音文件


    public function dlVoice($media_id)
    {
        $this->imgdlVoicedlVideo($pramn='voice',$media_id);
    }
    //下载视频文件

public function dlVideo($media_id){
    $this->imgdlVoicedlVideo($pramn='video',$media_id);
}
    public function imgdlVoicedlVideo($pramn,$media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;
        //  echo '<pre>';print_r($url);echo '</pre>';
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        // echo '<pre>';print_r($h);echo '</pre>';
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);

        $wx_image_path = 'wx/'.$pramn.'/'.$file_name;
        //保存图片
        $r = Storage::disk('local')->put($wx_image_path,$response->getBody());
        if($r){     //保存成功

        }else{      //保存失败

        }
    }


    /**
     * 接收事件推送
     */
    public function validToken()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        //echo $_GET['echostr'];
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

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
        $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }

    /**
     * 创建服务号菜单
     */
    public function wxMenu(){
        //echo __METHOD__;
        // 1 获取access_token 拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();
        //echo $url;echo '</br>';

        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);

        $data = [
            "button"    => [
                [
                    "name"=>"未凉",
                    "sub_button"=>[
                        [
                            "type"  => "view",      // view类型 跳转指定 URL
                            "name"  => "首页",
                            "url"   => "http://lsy.52self.cn"
                        ],
                        [
                            "type" => "view",
                            "name" =>"个人网站",
                            "url" => "https://jk17970220.m.icoc.bz"
                        ],
                        [
                            "type"=>"view",
                            "name"=>"娱乐中心",
                            "url" =>"https://wan.sogou.com",
                        ],

                    ]
                ],

                [
                    "type"  => "click",      // click类型
                    "name"  => "客服01",
                    "key"   => "kefu01"
                ],
                [
                    "type"  => "view",      // view类型 跳转指定 URL
                    "name"  => "百度一下",
                    "url"   => "https://www.baidu.com"
                ]
            ],
        ];

        $body = json_encode($data,JSON_UNESCAPED_UNICODE);      //处理中文编码
        $r = $client->request('POST', $url, [
            'body' => $body
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(),true);
        //echo '<pre>';print_r($response_arr);echo '</pre>';

        if($response_arr['errcode'] == 0){
            echo "菜单创建成功";
        }else{
            echo "菜单创建失败，请重试";echo '</br>';
            echo $response_arr['errmsg'];

        }



    }

public function wxType()
{
    $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->getWXAccessToken();

    //echo $url;echo '</br>';
    //2 请求微信接口
    $client = new GuzzleHttp\Client(['base_uri' => $url]);
    $data=[
        "filter"=>[
            "is_to_all"=>true,
            "tag_id"=>2
        ],
        "text"=>[
            "content"=>"嗨皮"
        ],
        "msgtype"=>"text"
    ];
    //var_dump($data);

    $r = $client->request('POST', $url, [
        'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
    ]);
    // 3 解析微信接口返回信息

    $response_arr = json_decode($r->getBody(),true);
print_r($response_arr);

    if($response_arr['errcode'] == 0){
        echo "群发成功";
    }else{
        echo "群发失败，请重试";echo '</br>';


    }
}

     // 刷新access_token

    public function refreshToken()
    {
        Redis::del($this->redis_weixin_access_token);
        echo $this->getWXAccessToken();
    }


}
