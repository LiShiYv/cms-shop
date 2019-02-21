<?php

namespace App\Admin\Controllers;

use App\Model\WeixinType;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
class MessageController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    protected $redis_weixin_access_token = 'str:weixin_access_token';
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinType);

        $grid->id('Id');
//        $grid->openid('Openid');
//        $grid->add_time('Add time');
//        $grid->msg_type('Msg type');
//        $grid->media_id('Media id');
//        $grid->format('Format');
//        $grid->msg_id('Msg id');
//        $grid->local_file_name('Local file name');
//        $grid->local_file_path('Local file path');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WeixinType::findOrFail($id));

        $show->id('Id');
//        $show->openid('Openid');
//        $show->add_time('Add time');
//        $show->msg_type('Msg type');
//        $show->media_id('Media id');
//        $show->format('Format');
//        $show->msg_id('Msg id');
//        $show->local_file_name('Local file name');
//        $show->local_file_path('Local file path');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinType);

//        $form->text('openid', 'Openid');
//        $form->number('add_time', 'Add time');
//        $form->text('msg_type', 'Msg type');
//        $form->text('media_id', 'Media id');
//        $form->text('format', 'Format');
//        $form->text('msg_id', 'Msg id');
//        $form->text('local_file_name', 'Local file name');
//        $form->text('local_file_path', 'Local file path');
        $form->textarea('content', 'TEXT(信息不能重复输入)');
        return $form;
    }

    public function type(Request $request)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $this->getWXAccessToken();
        //echo $url;echo '</br>';
        //2 请求微信接口
       $content=$request->input('content');
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = [
            "filter" => [
                "is_to_all" => true,

            ],
            "text" => [
                "content" => $content
            ],
            "msgtype" => "text"
        ];
        //var_dump($data);
        $body = json_encode($data, JSON_UNESCAPED_UNICODE);      //处理中文编码
        $r = $client->request('POST', $url, [
            'body' => $body
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(), true);
        echo '<pre>';
        print_r($response_arr);
        echo '</pre>';

        if ($response_arr['errcode'] == 0) {
            echo "群发成功";
        } else {
            echo "群发失败，请重试";
            echo '</br>';


        }
    }
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
    // 刷新access_token

    public function refreshToken()
    {
        Redis::del($this->redis_weixin_access_token);
        echo $this->getWXAccessToken();
    }
}
