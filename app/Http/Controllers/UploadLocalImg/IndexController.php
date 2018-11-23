<?php

namespace App\Http\Controllers\UploadLocalImg;

use App\LocalModel\LocalImage;
use App\OnlineModel\AOnlyBook;
use App\OnlineModel\AWorkbook1010;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    protected $request;


    protected function get_dir($dir_now)
    {

        $all_directory = \File::directories($dir_now);
        if(count($all_directory)>0){
            foreach ($all_directory as $sub_dir){
//                if(LocalImage::where('path_name',$sub_dir)->count()==0){
//                    $data['path_name'] = str_replace('\\\\QINGXIA23\\book', '', $sub_dir);
//                    $now_parent = LocalImage::where(['path_name'=>str_replace('\\\\QINGXIA23\\book', '',$dir_now)])->first();
//                    if($now_parent){
//                        $data['parent_id'] = $now_parent->id;
//                    }
//                    LocalImage::create($data);
//                }
                $this->get_dir($sub_dir);
            }
        }else{
            LocalImage::where('path_name',$dir_now)->update(['last_dir'=>1]);
        }

    }

    //当前模块

    public function local_dispatch(Request $request,$type='index')
    {
        $this->request = $request;
        if(!in_array($type, ['index','ajax_flush_dict','upload_img','get_onlyid_info'])){
            abort(404);
        }else{
            return $this->$type();
        }
    }

    //首页
    public function index()
    {

        $data['all_directories'] = LocalImage::where(['parent_id'=>0])->select('path_name','id','status')->orderBy('path_name','asc')->paginate(20);
        return view('local_img.index',compact('data'));
    }

    //刷新本地目录入库
    public function ajax_flush_dict()
    {
        var_dump('test_1');
    }

    //上传图片
    public function upload_img()
    {
        $onlyid = $this->request->onlyid;
        $path_name = $this->request->now_path;

        if($onlyid==''){
            return return_json_err();
        }

        if(LocalImage::where(['onlyid'=>$onlyid,'path_name'=>$path_name])->update(['status'=>1])){
            return return_json();
        }else{
            return return_json_err();
        }
        //$all_files = \File::allFiles('\\QINGXIA23')=

    }

    public function get_onlyid_info()
    {
        $onlyid = $this->request->onlyid;
        //dd(AWorkbook1010::where(['onlyid'=>$onlyid,'version_year'=>2018,'volumes_id'=>1])->toSql());
        $now_info = AOnlyBook::where(['onlyid'=>$onlyid])->first();
        //dd($now_info);

        return return_json([$now_info]);
    }

    public function test_1()
    {
        var_dump('test_1');
    }

    public function test_2()
    {
        var_dump('test_2');
    }
}
