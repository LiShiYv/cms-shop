<?php

namespace App\Admin\Controllers;

use App\Model\GoodsModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsController extends Controller
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
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($goods_id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($goods_id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($goods_id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($goods_id));
    }
    public function update($goods_id){
      // echo 111;exit;
        $where=[
            'goods_id'=>$goods_id

        ];
        $data=[
          'goods_name'=>$_POST['goods_name'],
          'goods_price'=>$_POST['goods_price']*100,
          'store'=>$_POST['store'],
          //'up_time'=>time(),
        ];
        $res=GoodsModel::where($where)->update($data);
        if($res===false){
            $response = [
                'status' => false,
                'message'   => '修改失败'
            ];
            return $response;
        }else{
             $response = [
                'status' => true,
                'message'   => '修改成功'
            ];
            return $response;
        }

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
        $grid = new Grid(new GoodsModel);

        $grid->model()->orderBy('goods_id','desc')->where(['is_del'=>1]);
        $grid->goods_id('ID');
        $grid->goods_name('商品名称');
        //$grid->add_time('Add time');
        $grid->store('库存');
        //$grid->cat_id('Cat id');
        $grid->goods_price('价格');
      // $grid->created_at('添加时间');
        //$grid->updated_at('Updated at');

        return $grid;
    }
    public function destroy($goods_id)
    {
        $where=[
            'goods_id'=>$goods_id

        ];
        $res=GoodsModel::where($where)->update(['is_del'=>2]);
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
    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($goods_id)
    {
        $show = new Show(GoodsModel::findOrFail($goods_id));

        $show->goods_id('商品ID');
        $show->goods_name('商品名称');
        $show->pay_time('添加时间');
        $show->store('库存');
        $show->cat_id('购物车ID');
        $show->price('库存');
        //$show->created_at('Created at');
      //  $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GoodsModel);

        $form->text('goods_name', '商品名称');
        //$form->number('add_time', 'Add time');
        $form->number('store', '库存');
        //$form->number('cat_id', 'Cat id');
        $form->number('goods_price', '价格');
        $form->summernote('content');
        return $form;
    }


}

