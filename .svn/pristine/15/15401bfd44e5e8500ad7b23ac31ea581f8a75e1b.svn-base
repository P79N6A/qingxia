<?php

namespace App\Console\Commands;

use App\Http\Controllers\OssController;
use App\LocalModel\LocalImage;
use App\OnlineModel\AWorkbook1010;
use Illuminate\Console\Command;

class PregLocalImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:preg_local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '整理匹配本地文件夹属性';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->upload_img();
//        $all_folder = LocalImage::where('status',0)->select(['id','path_name','preg_sort','preg_subject','preg_grade','preg_volume','preg_version'])->get();
////        foreach ($all_folder as $folder){
////            $now['preg_grade'] =  0;
////            $now['preg_subject'] = 0;
////            $now['preg_volume'] = 0;
////            $now['preg_version'] = 0;
////
////            $now_name = $folder->path_name;
////
////            foreach (config('workbook.grade') as $key=>$grade){
////
////                if(strpos($now_name, $grade)!==false){
////                    $now['preg_grade'] = $key;
////                }
////            }
////            foreach (config('workbook.subject_1010') as $key=>$subject){
////
////                if(strpos($now_name, $subject)!==false){
////                    $now['preg_subject'] = $key;
////                }
////            }
////            foreach (config('workbook.volumes') as $key=>$volume){
////                if(strpos($now_name, $volume)!==false){
////                    $now['preg_volume'] = $key;
////                }
////            }
////            foreach (cache('all_version_now') as $key=>$version){
////                if(strpos($now_name, $version->name)!==false){
////                    $now['preg_version'] = $version->id;
////                }
////            }
////            #$now_only_id = AWorkbook1010::where(['grade'=>$now['preg_grade']])
////            LocalImage::where(['id'=>$folder->id])->update($now);
////        }
//
//        foreach ($all_folder as $folder){
//            if($folder->preg_sort>=0 && $folder->preg_subject>0 && $folder->preg_grade>0 && $folder->preg_volume>0 && $folder->preg_version>-1){
//                $all_record = AWorkbook1010::where(['version_year'=>'2018','grade_id'=>$folder->preg_grade,'subject_id'=>$folder->preg_subject,'volumes_id'=>$folder->preg_volume,'version_id'=>$folder->preg_version,'sort'=>$folder->preg_sort])->select(['onlyid'])->get();
//                $onlyid = $all_record->pluck('onlyid');
//                $onlyid = implode('|', $onlyid->unique()->toArray());
//                LocalImage::where(['id'=>$folder->id])->update(['onlyid'=>$onlyid]);
//            }
//        }

    }

    public function upload_img()
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit',-1);
        $all_folder = LocalImage::where(['status'=>1,'done'=>0])->select('id','onlyid','path_name')->orderBy('id','asc')->get();
        $oss = new OssController();
        foreach ($all_folder as $folder){
            $book_path = $folder->onlyid.'/18/1/pages/';
            $local_path = '\\\\Qingxia23\\\\book'.$folder->path_name;

            $all_files = \File::allFiles($local_path);
            foreach ($all_files as $file){

                $new_name = ltrim(str_replace('img','',$file->getFileName()), '0');
                $new_full_name = $file->getPath().'\\'.$new_name;
                rename($file, $new_full_name);
                if(in_array(str_slug($file->getExtension()), ['jpg','png','gif','jpeg'])){
                    $oss->uploadfile('all_book_pages/' .$book_path.$new_name, $new_full_name);
                }
            }
            LocalImage::where(['id'=>$folder->id])->update(['done'=>1]);
        }

    }
}
