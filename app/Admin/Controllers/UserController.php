<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use Encore\Admin\Form;

use App\Model\CmsModel;
class UserController extends Controller{
    public function index(Content $content){
        return $content
            ->header('用户管理')
            ->description('用户列表')
            ->body($this->grid());
    }
    protected  function grid(){
        $grid = new Grid(new CmsModel());
        $grid->id('ID');
        $grid->u_name('昵称');
        $grid->age('年龄');
        $grid->u_email('邮箱');
        $grid->reg_time('注册时间')->display(function($time){
            return date('Y-m-d H:i:s',$time);

        });
        return $grid;

    }
    public function edit($id){
        echo __METHOD__;
    }
    public function create(Content $content){
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());

    }
    //删除
    public function destroy($id)
    {

        $response = [
            'status' => true,
            'message'   => 'ok'
        ];
        return $response;
    }
    protected function form(){
        $form=new Form(new CmsModel());
        $form->display('id','ID');
        $form->text('u_name','昵称');
        $form->text('age','年龄');
        $form->email('u_email','Email');

        return $form;
    }

}
