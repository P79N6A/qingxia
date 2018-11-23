<?php

namespace App\Console\Commands;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\OssController;
use App\LocalModel\AWorkbook1010Test;
use App\LocalModel\LocalImage;
use App\OnlineModel\AOnlyBook;
use App\OnlineModel\ASubSort;
use App\OnlineModel\ATongjiHotBook;
use App\OnlineModel\AWorkbook1010;
use App\TempModel\TestGoogleOcrLogs;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorHTML;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test some function';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    protected function update_onlybook_isbn($onlyid,$isbn)
    {
        $isbn = str_replace('-', '', $isbn);
        $isbn_arr = explode('|', $isbn);
        if(is_array($isbn_arr) && count($isbn_arr)>1){
            foreach ($isbn_arr as $isbn_single){
                $this->update_onlybook_isbn($onlyid,$isbn_single);
            }
        }else{
            $now_isbn = AOnlyBook::where(['onlyid'=>$onlyid])->first(['isbn']);
            if($now_isbn && strpos($now_isbn->isbn, $isbn)===false){
                AOnlyBook::where(['onlyid'=>$onlyid])->update(['isbn'=>$now_isbn->isbn.'|'.$isbn]);
            }
        }
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $oss = new OssController();
//        $all_img = $oss->getOssClient()->listObjects('daanpic', ['delimiter' => '/', 'prefix' =>'all_book_pages/','max-keys'=>1000 ]);

            $options = [
                'delimiter' => '/',
                'prefix'    => 'all_book_pages/',
                'max-keys'  => '1000',
                'marker'    => '',
            ];

            $fileListInfo = $oss->getOssClient()->listObjects('daanpic', $options);

            $nextMarker = $fileListInfo->getNextMarker();
            $options = [
                'delimiter' => '/',
                'prefix'    => 'all_book_pages/',
                'max-keys'  => '1000',
                'marker'    => $nextMarker,
            ];

            $fileListInfo = $oss->getOssClient()->listObjects('daanpic', $options);

            $dirItem = $fileListInfo->getPrefixList();
            #$fileList[] = $fileItem;
            #$dirList[] = $dirItem;
            foreach ($dirItem as $img_dir){
                $only_id = explode('/', $img_dir->getPrefix())[1];
                var_dump($only_id);
                if(strlen($only_id)==13){
                    AWorkbook1010::where(['onlyid'=>$only_id,'version_year'=>'2018','volumes_id'=>1])->where('id','>',1100000)->update(['content_status'=>3]);
                }
            }




//        foreach ($all_img->getPrefixList() as $img_dir){
//
//            $only_id = explode('/', $img_dir->getPrefix())[1];
//            var_dump($only_id);
//            if(strlen($only_id)==13){
//                AWorkbook1010::where(['onlyid'=>$only_id,'version_year'=>'2018','volumes_id'=>1])->where('id','>',1100000)->update(['content_status'=>3]);
//            }
//        }

//        $all_num = DB::connection('mysql_local')->table('a_tongji_hotbook29')->select('isbn','searchnum')->get();
//        foreach ($all_num as $num){
//            var_dump($num);
//            AOnlyBook::where('isbn','like','%'.$num->isbn.'%')->increment('searchnum', $num->searchnum);
//        }
//        dd($all_num);




//        $a = AWorkbook1010::where([['newname','学海风暴九年级数学上册人教版'],['onlyid','!=',0]])->whereRaw('LENGTH(onlyid)=5')->first();
//        dd($a);
//        dd('qwe');



        $all_isbn = AWorkbook1010::where([['id','>','1231430']])->select('id','isbn','onlyid')->orderBy('id','desc')->get();
        foreach ($all_isbn as $isbn){
            if(strlen($isbn->onlyid)==13){
                if(AOnlyBook::where([['onlyid',$isbn->onlyid],['isbn','like','%'.$isbn->isbn.'%']])->count()==0){
                    var_dump($isbn);
                    $this->update_onlybook_isbn($isbn->onlyid,$isbn->isbn);
                }
            }
        }

        dd('111');
        $all_files = \File::allFiles(public_path('google'));

        foreach ($all_files as $file){
            $file_name = public_path('google_img/'.explode('.', $file->getFilename())[0].'.jpg');
            $img_file_now = getimagesize($file_name);
            $data['md5'] = md5_file($file_name);
            $data['width'] = $img_file_now[0];
            $data['height'] = $img_file_now[1];
            $data['imgpath'] = $file_name;
            $data['ocr_result_json_path'] = $file->getPathName();
            $all_json_file = json_decode(file_get_contents($file));
            $now_long_text = collect($all_json_file->textAnnotations)->pluck('description')->sortByDesc(function ($value) {
                return strlen($value);
            })->filter(function ($value1, $key1) {
                return ($key1 > 0 and mb_strlen($value1)>5);
            })->take(10)->toArray();

            $data['long_words'] =  implode('|', $now_long_text);
            TestGoogleOcrLogs::create($data);
            //Log
        }
        dd($all_files);






//        echo preg_replace($pattern, $replacement, $string);
//        $last_message = 'src="htp://asdqweqwe/qweqwe"';
//        $end_message = preg_replace_callback ('/src="(.*?)"/i',function($matches){
//            if(starts_with($matches[1], 'http://')){
//                return 'src="'.$matches[1].'"';
//            }else{
//                return 'src="http://www.05wang.com/'.$matches[1].'"';
//            }
//        },$last_message);

        dd('qwe');


        $book_id = '0031106021000181';
        $path = substr($book_id, 0, -3).'/'.substr($book_id, -3,-1).'/'.substr($book_id, -1);
        $book_path = 'all_book_pages/'.$path.'/pages/';

        $oss = new OssController();
        $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => $book_path,'max-keys'=>1000]);


        $files = [];
        foreach ($all_img->getObjectList() as $img){
            $img_url = str_replace($book_path, '', $img->getKey());
            $img_explode = explode('.', $img_url);
            if($img_url!=='' && count($img_explode)==2){
                $files[] = $img_explode[0];
            }
        }
        sort($files);



        dd(json_decode(file_get_contents('http://handler.05wang.com/api/htm2pic/put_thread_pic/151834')));

//        $image = new ImageController();
//        $image->test();


        dd(explode('|', '988|899'));


        //to local onlyid
        $all_book = AWorkbook1010Test::where([['id','>=',1231257],['onlyid','0']])->select('*')->get();

        foreach ($all_book as $book){
            $book = $book->toArray();
            $now_new_name = str_replace('2018年', '', $book['bookname']);
//            $book['sort']=44;
//            $book['grade_id']=8;
//            $book['subject_id']=3;
//            $book['version_id']=3;
            $online_onlyid = AWorkbook1010::where([['newname',$now_new_name],['id','<=',1231257],['onlyid','!=',0],['onlyid','like','0%']])->first(['onlyid']);

            if($online_onlyid){
                $book['onlyid'] = $online_onlyid->onlyid;
            }else{
                $now_ssort_id = 0;
                $now_ssort = ASubSort::where([['sort_id',$book['sort']],['ssort_name','!=','']])->select('ssort_id','ssort_name')->get();
                foreach ($now_ssort as $ssort){
                    if(strpos($now_new_name, $ssort->ssort_name)!==false){
                        $now_ssort_id = $ssort->ssort_id;
                    }
                }
                $book['onlyid'] = str_pad($book['sort'],5,"0",STR_PAD_LEFT).str_pad($book['grade_id'],2,"0",STR_PAD_LEFT).str_pad($book['subject_id'],2,"0",STR_PAD_LEFT).str_pad($book['version_id'],2,"0",STR_PAD_LEFT).str_pad($now_ssort_id,2,"0",STR_PAD_LEFT);

            }

            AWorkbook1010::where(['id'=>$book['id']])->update(['onlyid'=>$book['onlyid']]);
        }

    }

    public function test_png()
    {
        $image = new ImageManager(array('driver' => 'gd'));

        $now_img = $image->make(public_path("23d3e041e09517fa9871670c3f8ea675.png"));
        #$now_img->encode('jpg', 10);
        $now_img->save(public_path('1.jpg'));
        file_put_contents(public_path('123.png'), file_get_contents(public_path('1.jpg')));
        #$now_img->encode('png', 10);
        #$now_img->save(public_path('23d3e041e09517fa9871670c3f8ea675.jpg'));
    }
}
