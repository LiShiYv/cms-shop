<?php

namespace App\Admin\Controllers;

use App\Model\WeixinUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class WeixinController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
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
        $show_id=$_GET['show_id'];
//        return $content
//            ->header('Create')
//            ->description('description')
//            ->body($this->form());
        return $content
            ->header('Create')
            ->description('description')
            ->body(view('weixin.wxservice',['show_id'=>$show_id])->render());

    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinUser);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->openid('Openid');
        $grid->add_time('Add time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });

        $grid->nickname('Nickname');
        $grid->sex('Sex')->display(function($sex){
            if($sex==0){
                $sexs='未知';
            }elseif($sex==1){
                $sexs='男';
            }elseif($sex==2){
                $sexs='女';
            }
            return $sexs;
        });
        $grid->headimgurl('Headimgurl')->display(function($img){
            return "<img src='$img'>";
        });

        $grid->subscribe_time('Subscribe time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });

        $grid->actions(function ($actions) {
            // append一个操作
            $key=$actions->getKey();
            $actions->prepend('<a href="/admin/weixin/create?show_id='.$key.'"><i class="fa fa-paper-plane"></i></a>');

        });

        return $grid;
    }
public function wxservice(){
    echo '<pre>';print_r($_POST);echo '</pre>';echo '<hr>';

}
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WeixinUser::findOrFail($id));

        $show->id('Id');
        $show->uid('Uid');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time');

        return $show;
    }

     /* Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinUser);

        $form->number('uid', 'Uid');
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->switch('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');

        return $form;
    }
}
