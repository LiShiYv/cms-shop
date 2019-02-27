<?php

namespace App\Http\Controllers\vip;

use Illuminate\Http\Request;
use App\Model\Cmsmodel;
use App\Http\Controllers\Controller;

class vip extends Controller
{
    //
    public function vip(){
      return view('vip.index');
    }
    public function file(Request $request){
        $file=$request->file('pdf');
        $etx=$file->extension();
        if($etx !='pdf'){
            die('请上传PDF格式');
        }
        $res=$file->storeAs(date('Ymd'),str_random(5).'.pdf');
        if($res){
            echo '上传成功';
        }
    }
    public function goodsList(){
        $list=Cmsmodel::paginate(2);
        $data=[
            'list'=>$list
        ];
        return view('vip.list',$data);
    }
}
