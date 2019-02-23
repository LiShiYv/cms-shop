<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use Encore\Admin\Form;

use App\Model\Cmsmodel;
class UserController extends Controller{
    public function index(Content $content){
        return $content
            ->header('用户管理')
            ->description('用户列表')
            ->body($this->grid());
    }
    protected  function grid(){
        $grid = new Grid(new Cmsmodel());
        $grid->id('ID');
        $grid->u_name('昵称');
        $grid->age('年龄');
        $grid->u_email('邮箱');
        $grid->reg_time('注册时间')->display(function($time){
            return date('Y-m-d H:i:s',$time);

        });
        return $grid;

    }
    public function edit($id, Content $content){
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }
    public function create(Content $content){
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());

    }
    public function update($id){
        // echo 111;exit;
        $where=[
            'id'=>$id

        ];
        $data=[
            'u_name'=>$_POST['u_name'],
            'u_email'=>$_POST['u_email'],
            'age'=>$_POST['age'],
            //'up_time'=>time(),
        ];
        $res=Cmsmodel::where($where)->update($data);
        if($res===false){
            $response = [
                'status' => false,
                'message'   => '修改失败'
            ];
            return $response;
        }else{
            $response = [
                'status' => true,
                'message'   => '修改成改',
            ];
            return $response;
        }

    }
    protected function detail($id)
    {
        $show = new Show(Cmsmodel::findOrFail($id));

        $show->id('用户ID');
        $show->u_name('用户昵称');
        $show->pay_time('添加时间');
        $show->age('年龄');

        $show->u_email('邮箱');
        //$show->created_at('Created at');
        //  $show->updated_at('Updated at');

        return $show;
    }

    //删除
    public function destroy($id)
    {
        $where=[
            'id'=>$id
        ];
        $res=Cmsmodel::where($where)->update(['is_del'=>2]);
        if($res){
            $response = [
                'status' => true,
                'message'   => '删除成功'
            ];
            return $response;
        }else{
            $response = [
                'status' => false,
                'message'   => '删除失败'
            ];
            return $response;
        }

    }
    protected function form(){
        $form=new Form(new Cmsmodel());
        $form->display('id','ID');
        $form->text('u_name','昵称');
        $form->text('age','年龄');
        $form->email('u_email','Email');

      //  $form->file('agess');
        return $form;
    }

}
