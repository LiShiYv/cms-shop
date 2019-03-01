<?php

namespace App\Http\Controllers\WeiXin;



use App\Model\WeixinType;
use App\Model\WeixinUser;
use App\Model\WxUserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeixinMedia;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

class WeiXinController extends Controller
{
    //

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    protected $redis_weixin_jsapi_ticket = 'str:weixin_jsapi_ticket';     //微信 jsapi_ticket




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
                //存入数据库
                $data=[
                    'text'=>$msg,
                    'add_time'=>time(),
                    'openid'=>$openid,
                    'nickname'=>$openid,
                ];
                $res=WeixinType::insert($data);
            }elseif($xml->MsgType=='image'){       //用户发送图片信息
                //视业务需求是否需要下载保存图片
                if(1){  //下载图片素材
                    $file_name=$this->dlWxImg($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' .'当前时间是'. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;
                    //写入数据库
                    $data = [
                        'openid'    => $openid,
                        'add_time'  => time(),
                        'msg_type'  => 'image',
                        'media_id'  => $xml->MediaId,
                        'format'    => $xml->Format,
                        'msg_id'    => $xml->MsgId,
                        'local_file_name'   => $file_name
                    ];

                    $m_id = WeixinMedia::insertGetId($data);
                    var_dump($m_id);
                }
            }elseif($xml->MsgType=='voice'){
                $file_name=$this->dlVoice($xml->MediaId);
                $xml_response1 = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . '当前时间是'.date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response1;
                //写入数据库
                $data = [
                    'openid'    => $openid,
                    'add_time'  => time(),
                    'msg_type'  => 'voice',
                    'media_id'  => $xml->MediaId,
                    'format'    => $xml->Format,
                    'msg_id'    => $xml->MsgId,
                    'local_file_name'   => $file_name
                ];

                $m_id = WeixinMedia::insertGetId($data);
                var_dump($m_id);


            }elseif($xml->MsgType=='video'){
               $file_name=$this->dlVideo($xml->MediaId);
                $xml_response2 = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.' 当前时间是'. date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response2;
                //写入数据库
                $data = [
                    'openid'    => $openid,
                    'add_time'  => time(),
                    'msg_type'  => 'video',
                    'media_id'  => $xml->MediaId,
                    'format'    => $xml->Format,
                    'msg_id'    => $xml->MsgId,
                    'local_file_name'   => $file_name
                ];

                $m_id = WeixinMedia::insertGetId($data);
                var_dump($m_id);
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
        $file_name=$this->imgdlVoicedlVideo($pramn='image',$media_id);
    return $file_name;
    }


     //下载语音文件


    public function dlVoice($media_id)
    {
        $file_name=$this->imgdlVoicedlVideo($pramn='voice',$media_id);
        return $file_name;
    }
    //下载视频文件

public function dlVideo($media_id){
    $file_name=$this->imgdlVoicedlVideo($pramn='video',$media_id);
    return $file_name;
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
        return $file_name;
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
            "content"=>"上课考试111"
        ],
        "msgtype"=>"text"
    ];
    //var_dump($data);
    $body = json_encode($data,JSON_UNESCAPED_UNICODE);      //处理中文编码
    $r = $client->request('POST', $url, [
        'body' => $body
    ]);

    // 3 解析微信接口返回信息

    $response_arr = json_decode($r->getBody(),true);
    echo '<pre>';print_r($response_arr);echo '</pre>';

    if($response_arr['errcode'] == 0){
        echo "群发成功";
    }else{
        echo "群发失败，请重试";echo '</br>';


    }
}
//获取永久素材
public function file(){
    return view('weixin.weixin');
}
//上传素材
    public function formMaterialTest($file_path)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->getWXAccessToken().'&type=image';
        $client = new GuzzleHttp\Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => fopen($file_path, 'r')
                ],
            ]
        ]);
        $body = $response->getBody();
        echo $body;echo '<hr>';
        $d = json_decode($body,true);
        echo '<pre>';print_r($d);echo '</pre>';
    }
//获取素材
    public function formList()
    {
        $client = new GuzzleHttp\Client();
        $type = $_GET['type'];
        $offset = $_GET['offset'];

        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->getWXAccessToken();

        $body = [
            "type"      => $type,
            "offset"    => $offset,
            "count"     => 20
        ];
        $response = $client->request('POST', $url, [
            'body' => json_encode($body)
        ]);

        $body = $response->getBody();
        echo $body;echo '<hr>';
        $arr = json_decode($response->getBody(),true);
        echo '<pre>';print_r($arr);echo '</pre>';


    }

public function formTest(Request $request){
    //echo '<pre>';print_r($_POST);echo '</pre>';echo '<hr>';
    //echo '<pre>';print_r($_FILES);echo '</pre>';echo '<hr>';
    //保存文件
    $img_file = $request->file('media');
    //echo '<pre>';print_r($img_file);echo '</pre>';echo '<hr>';

    $img_origin_name = $img_file->getClientOriginalName();
    echo 'originName: '.$img_origin_name;echo '</br>';
    $file_ext = $img_file->getClientOriginalExtension();          //获取文件扩展名
    echo 'ext: '.$file_ext;echo '</br>';

    //重命名
    $new_file_name = str_random(15). '.'.$file_ext;
    echo 'new_file_name: '.$new_file_name;echo '</br>';

    //文件保存路径


    //保存文件
    $save_file_path = $request->media->storeAs('form_test',$new_file_name);       //返回保存成功之后的文件路径

    echo 'save_file_path: '.$save_file_path;echo '<hr>';

    //上传至微信永久素材
    $this->formMaterialTest($save_file_path);
}

     // 刷新access_token

    public function refreshToken()
    {
        Redis::del($this->redis_weixin_access_token);
        echo $this->getWXAccessToken();
    }

public function formService(){
    $data=[
        'openid'=>'oF5pn6PkNHZjgUOf-BTJWgdMyWd8',
        'nickname'=>'绯夜'
    ];
        return view('weixin.service',$data);
}
public function wxService(Request $request){

    $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->getWXAccessToken();
    $content=$request->input('weixin');
    $openid=$request->input('openid');
    $client = new GuzzleHttp\Client(['base_uri' => $url]);
    $data = [
        "touser"=>$openid,
        "msgtype"=>"text",
        "text"=>[
            "content"=>$content
        ]
    ];
    var_dump($data);
    $body = json_encode($data, JSON_UNESCAPED_UNICODE);      //处理中文编码
    $r = $client->request('POST', $url, [
        'body' => $body
    ]);

    // 3 解析微信接口返回信息

    $response_arr = json_decode($r->getBody(), true);
//    echo '<pre>';
//    print_r($response_arr);
//    echo '</pre>';

    if ($response_arr['errcode'] == 0) {
        //存入数据库
        $data=[
            'text'=>$content,
            'add_time'=>time(),
            'openid'=>$openid,
            'nickname'=>'未凉客服'

        ];
        $res=WeixinType::insert($data);
        $arr=[
            'code'=>0,
            'msg'=>'发送成功',
        ];
    }else{
        $arr=[
            'code'=>1,
            'msg'=>$response_arr['errmsg'],
        ];
    }
    echo json_encode($arr);

    }
    public function WeixinText(Request $request){
        $openid=$request->input('openid');
        $new=WeixinType::orderBy('add_time','asc')->where(['openid'=>$openid])->get();
        echo json_encode($new);

    }

public function weiXinLogin(Request $request){
    $code = $_GET['code'];
    //2 用code换取access_token 请求接口

    $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
    $token_json = file_get_contents($token_url);
    $token_arr = json_decode($token_json,true);
    $access_token = $token_arr['access_token'];
    $openid = $token_arr['openid'];
    // 3  获取用户信息
    $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    $user_json = file_get_contents($user_info_url);
    $user_arr = json_decode($user_json,true);
    var_dump($user_arr);
    $openidWhere=[
        'wx_openid'=>$user_arr['openid']
    ];
   // var_dump($openidWhere);
    $order=WxUserModel::where($openidWhere)->first();
//var_dump($order);die;
    if($order){
        //用户已存在
        $update=[

        'wx_nickname'=>$user_arr['nickname'],
        'wx_sex'=>$user_arr['sex'],
        'wx_language'=>$user_arr['language'],
        'wx_headimgurl'=>$user_arr['headimgurl'],

        'wx_unionid'=>$user_arr['unionid'],
        'wx_openid'=>$user_arr['openid'],
        'upp_time'=>time()
        ];
        var_dump($update);
        WxUserModel::where($openidWhere)->update($update);
        $user_id=$order['id'];
        $request->session()->put('id',$user_id);
        header('refresh:2;url=/center');
    }else{
        $WeixinDate=[
            'wx_nickname'=>$user_arr['nickname'],
            'wx_sex'=>$user_arr['sex'],
            'wx_language'=>$user_arr['language'],
            'wx_headimgurl'=>$user_arr['headimgurl'],

            'wx_unionid'=>$user_arr['unionid'],
            'wx_openid'=>$user_arr['openid'],
            'add_time'=>time()

        ];
        var_dump($user_arr);
        $user_id=WxUserModel::insertGetId($WeixinDate);
        $request->session()->put('id',$user_id);
        header('refresh:2;url=/center');

    }

}
public function config(){
        $configjs=[
          'appid'=>env('WEIXIN_APPID'),
            'timestamp'=>time(),
            'noncestr'=>str_random(10),
           //
        ];
        $sign=$this->wxSigns($configjs);
        $configjs['sign']=$sign;
        $data=[
            'configjs'=>$configjs
        ];
        return view('weixin.test',$data);
}
public function wxSigns($param){
    $current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     //当前调用 jsapi的 url
    $ticket = $this->getJsapiTicket();
    $str =  'jsapi_ticket='.$ticket.'&noncestr='.$param['noncestr']. '&timestamp='. $param['timestamp']. '&url='.$current_url;
    $signature=sha1($str);
    return $signature;
}
//获取 jsapi_ticket
public function getJsapiTicket(){
        $ticket=Redis::get($this->redis_weixin_jsapi_ticket);
        if(!$ticket){
            $access_token='19_N-b23N99Gvr79BN_Oi4NaOdoiBdMCEGHVIfYYXIkRCVnfFSXh6E2_OVrTrYgjlDxK66l-kMeOQBpAnpxmoo6qhNB3ffHqLyatAggv-lfR6HlvIhUmeNyx4ytUQcBGKhAJARWA';
            $ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $ticket_info = file_get_contents($ticket_url);
            $ticket_arr = json_decode($ticket_info,true);

            if(isset($ticket_arr['ticket'])){
                $ticket = $ticket_arr['ticket'];
                Redis::set($this->redis_weixin_jsapi_ticket,$ticket);
                Redis::setTimeout($this->redis_weixin_jsapi_ticket,3600);       //设置过期时间 3600s
            }
        }
    return $ticket;
}
}
