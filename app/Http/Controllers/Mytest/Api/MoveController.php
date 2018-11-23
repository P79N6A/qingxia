<?php

namespace App\Http\Controllers\Mytest\Api;

use App\BookVersionType;
use App\MyModel\Local_img_upload_logs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class MoveController extends Controller
{
    public function move()
    {
        $data=request()->all();
        //1.获取参数
        $sort=$data['sort'];
        $sortinfo=DB::connection('mysql_local')->table('sort')->find($sort);
        $sortname=$sortinfo->name;//系列名称
        $sort_path=$sort."_".$sortname;
        $subject=$data['subject'];
        $subjectname=config('workbook.subject_1010')[$subject];//科目名称
        $grade=$data['grade'];
        $gradename=config('workbook.grade')[$grade];//年级名称
        $volume=$data['volume'];
        $volumename=config('workbook.volumes')[$volume];
        $version=$data['version'];
        $mulu=$data['mulu'];//原目录名
        $newmulu=str_replace('\\','/',$mulu);
        $id=$data['id'];
        if(!((DB::connection('mysql_local')->table('local_img_upload_logs')->find($id)->movetime)==null)){
            return $code=json_encode(['status'=>'olddata']);
        };
        $all_version=Cache::rememberForever('all_version_now',function(){
            return BookVersionType::all('id','name');
        });
        foreach($all_version as $v){
            if($v['id']==$version){
                $versionname=$v['name'];//版本名称
            }
        }
        if(empty($data['from_id'])){
            return $code=json_encode(['status'=>'nodata']);
        }
        $from_id=$data['from_id'];
        $bookname=$data['bookname'];
        $fromname=$bookname."_".$from_id;
        $path='\\\\QINGXIA23/book';
        $topath='\\\\QINGXIA23/book4_new';

        //2.移动文件
//        $dir= iconv("UTF-8", "GBK", $path.$newmulu);
//        $toDir=iconv("UTF-8", "GBK", $topath."/".$sort_path."/".$fromname."/pages");
//        $dir= iconv("UTF-8", "GBK",'\\\\QINGXIA23/book/test/源目录');
//        $toDir= iconv("UTF-8", "GBK",'\\\\QINGXIA23/book/test/目的目录/b');
        $dir = $path.$newmulu;
        $toDir = $topath."/".$sort_path."/".$fromname."/pages";
//        dd(is_dir($toDir));
        if((!is_dir($dir))||(!is_dir($toDir))){
            DB::connection('mysql_local')->table('local_img_upload_logs')->where(['id'=>$id])->update(['no_from_id'=>1]);
            return $code=json_encode(['status'=>'path_problem']);
        }
        if (!is_dir($toDir)){
            mkdir($toDir);
        }
        $data=$this->copyF($dir,$toDir);

        //3.修改数据

        $time=date('Y-m-d H:i:s',time());
//        dd($time);

        if($data==200){
        DB::connection('mysql_local')->table('local_img_upload_logs')->where(['id'=>$id])->update(['done'=>1,'movetime'=>$time]);
            $code=json_encode(['status'=>'success']);
            DB::connection('mysql_local')->table('local_img_upload_logs')->where(['id'=>$id])->update(['done'=>1,'movetime'=>$time]);
        }else{
            $code=json_encode(['status'=>'failed']);
        }
        return $code;
    }
    
    public function updatefile()
    {
        //筛选最下级目录
        $logs=new Local_img_upload_logs();
        $alldata=$logs::all();
        foreach($alldata as $v){
            $id=$v['id'];
            $result=$logs::where(['parent_id'=>$id])->get();
            if($result->isEmpty()){
                $res=$logs::where(['id'=>$v['id']])->update(['last_path'=>1]);
            }
        }
        return json_encode(['status'=>'success']);
    }

    //移动文件
    public function copyF($dir,$toDir)
    {
//        $dir = '\\\\QINGXIA23\\book\\test\\源目录';
//        $toDir = '\\\\QINGXIA23\\book\\test\\目的目录\\b';
        \File::copyDirectory($dir,$toDir);
        return 200;
    }
}
