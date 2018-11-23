<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/15
 * Time: 下午3:50
 */

namespace App\Http\Controllers\PartTimeWork;

use App\OnlineModel\AWorkbook1010;
use App\User;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\OssController;
use Illuminate\Filesystem\Filesystem;
use App\LwwModel\AThreadChapter;
use App\OnlineModel\PartTimeLog;
use Auth;



class PartTimeWorkController extends Controller
{

    public function booklist($status=0){
        $re=Auth::user()->part_time;
        if($re){
            $data['list']=PartTimeLog::where('part_time_uid','=',auth::id())
                ->where(function($query)use($status){
                    if($status==0) $query->where('id','>',0);
                    if($status==1) $query->where('done_at','=',null);
                    if($status==2) $query->where('done_at','!=',null);
                })->paginate(20);
            foreach($data['list'] as $k=>$v){
                $data['list'][$k]['bookinfo']=AWorkbook1010::where(['onlyid'=>$v['onlyid'],'version_year'=>2018,'volumes_id'=>1])->select('id','cover','bookname')->first();
            }
            $data['status']=$status;
            //dd($data);
            return view("part_time_work.booklist_parttime",['data'=>$data]);
        }else{
            $data['list']=PartTimeLog::where('teacher_uid','=',auth::id())
                ->where(function($query)use($status){
                    if($status==0) $query->where('id','>',0);
                    if($status==1) $query->where('done_at','=',null);
                    if($status==2) $query->where('done_at','!=',null);
                })->paginate(20);
            foreach($data['list'] as $k=>$v){
                $data['list'][$k]['bookinfo']=AWorkbook1010::where(['onlyid'=>$v['onlyid'],'version_year'=>2018,'volumes_id'=>1])->select('id','cover','bookname')->first();
                $data['list'][$k]['part_time_name']=User::where(['id'=>$v['part_time_uid']])->first()->name;
            }
            $data['status']=$status;
            //dd($data);
            return view("part_time_work.booklist_teacher",['data'=>$data]);
        }

    }

    public function book($bookid){
        $re=AWorkbook1010::where(['id'=>$bookid])->select('id','onlyid','volumes_id','version_year','bookname')->first();
        $volume=$re->volumes_id;
        $onlyid=$re->onlyid;
        $year = $re->version_year;
        $oss = new OssController();
        $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => 'all_book_pages/'.get_bookid_array_path($onlyid,$year,$volume).'/pages/','max-keys'=>1000]);
        #dd($all_img->getObjectList());
        $files = [];
        foreach ($all_img->getObjectList() as $img){
            $img_url = $img->getKey();
            if($img_url!='all_book_pages/'.get_bookid_array_path($onlyid,$year,$volume).'/pages/'){
                $files[] = $img_url;
            }
        }
        $file_arr = [];
        $f = new Filesystem();
        foreach ($files as $key=>$file){
            if($f->extension($file)=='jpg') {
                $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));
                $file_arr[intval($now_file)] = $file;
            }
        }
        ksort($file_arr);
        //dd($file_arr);
        $data['chapters']=AThreadChapter::where(['onlyid'=>$onlyid,'volume_id'=>$volume,'year'=>$year,'status'=>0])->orderBy('lev','asc')->orderBy('num','asc')->select('id','name','num','lev','parent_id')->get();
        $data['chapter_arr']=$this->get_childchapter($this->getTree($data['chapters']->toArray()));
        $data['bookname']=$re->bookname;
        $data['content']=$file_arr;
        $data['bookid']=$bookid;
        //dd($data['chapter_arr'][0]);
        return view("part_time_work.book",['data'=>$data]);
    }


    public function book_success(Request $request){
        $bookid=intval($request->bookid);
        $onlyid=AWorkbook1010::where(['id'=>$bookid])->select('onlyid')->first()->onlyid;
        $re=PartTimeLog::where(['part_time_uid'=>auth::id(),'onlyid'=>$onlyid])->update(['done_at'=>date('Y-m-d H:i:s')]);
        return return_json(['status'=>$re]);
    }

    public function part_time_confirm(Request $request){
        $id=intval($request->id);
        $re=PartTimeLog::where(['id'=>$id])->update(['confirm_at'=>date('Y-m-d H:i:s')]);
        return return_json(['status'=>$re]);
    }


    function getTree($array){
        $refer = [];
        $tree = [];
        foreach($array as $k => $v){
            $refer[$v['id']] = & $array[$k];
        }
        //dd($refer);
        foreach($array as $k => $v){
            $pid = $v['parent_id'];
            if($pid == 0){
                $tree[] = & $array[$k];
            }else{
                if(isset($refer[$pid])){
                    $refer[$pid]['child'][] = & $array[$k];
                }
            }

        }
        return $tree;
    }

    public function get_childchapter($array){
        $re=[];
        foreach($array as $k=>$v){
            if(!isset($v['child'])){
                $re[]=$v;
            }else{
                foreach($v['child'] as $k2=>$v2){
                    if(!isset($v2['child'])){
                        $re[]=$v2;
                    }else{
                        foreach($v2['child'] as $k3=>$v3){
                            if(!isset($v3['child'])){
                                $re[]=$v3;
                            }else{
                                foreach($v3['child'] as $k4=>$v4){
                                    if(!isset($v4['child'])){
                                        $re[]=$v4;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $re;
    }


}