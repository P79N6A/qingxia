<?php

namespace App\Http\Controllers\Temp;

use App\Temp\BookChapter;
use App\Temp\Courseware;
use App\Temp\CoursewareCategory;
use App\Temp\CoursewarePage;
use App\Temp\TestKjzhan;
use App\Temp\TestSingle;
use App\Temp\TestSinglePage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Flysystem\Filesystem;
use Ramsey\Uuid\Uuid;

class CoursewareController extends Controller
{
    public function index()
    {
        ignore_user_abort();
        set_time_limit(0);


        $edtion = [14=>"人教新版",15=>"北师大新版",16=>"华师大新版",17=>"苏科新版",18=>"湘教新版",19=>"青岛新版",20=>"浙教新版",21=>"冀教新版",22=>"沪科新版",23=>"鲁教五四新版",24=>"北京课改新版",25=>"沪教新版",27=>"人教五四新版",1=>"人教版",2=>"北师大版",3=>"华师大版",4=>"浙教版",5=>"湘教版",6=>"苏科版",7=>"冀教版",8=>"沪科版",9=>"北京课改版",10=>"鲁教五四版",11=>"沪教版",12=>"青岛版",13=>"人教五四版"];





        $arrs = ['chinese_book','chinese2_book','chinese3_book'];
        //,'chinese2_book','chinese3_book','english_book','english2_book','english3_book','history_book','history2_book','hs_bio_book','hs_chemistry_book','hs_geography_book','hs_math_book','hs_physics_book','ls_math_book','ms_bio_book','ms_chemistry_book','ms_geography_book','ms_math_book','ms_physics_book','politics_book','politics2_book'
        //'chinese_category','chinese2_category','chinese3_category','english_category','english2_category','english3_category','history_category','history2_category'
        //,'hs_bio_category','hs_chemistry_category','hs_geography_category','hs_math_category','hs_physics_category','ls_math_category','ms_bio_category','ms_chemistry_category','ms_geography_category','ms_math_category','ms_physics_category','politics_category','politics2_category'


        foreach ($arrs as $arr){
            $all_books = \DB::connection('mysql_local')->table($arr)->select('');

        }



//        foreach ($arrs as $arr){
//            $all_chapter = \DB::connection('mysql_local')->table($arr)->select('id','pid','bookid','name','seq','desc')->get();
//            foreach ($all_chapter as $chapter){
//                $data['uuid'] = $chapter->id;
//                $data['parent_uuid'] = $chapter->pid;
//                $data['book_id'] = $chapter->bookid;
//                $data['sort'] = $chapter->seq;
//                $data['description'] = $chapter->desc;
//                $data['title'] = $chapter->name;
//                BookChapter::create($data);
//            }
//        }











//
//        $kjz_k12 = ['1s','1x','2s','2x','3s','3x','4s','4x','5s','5x','6s','6x','7s','7x','8s','8x','9s','9x'];
//        $kjz  =['1s','1x','2s','2x','3s','3x','4s','4x','5s','5x','6s','6x','7s','7x','8s','8x','9s','9x','banhui','beishidashuxue','dili','donghua','dzkb','flash','gaokao','gaokaokaoshiti','huaxue','jiaoan','jiazhanghui','jihua','jingsai','kxja','langdu','lishi','mp3','pingyu','plus','ppt','qita','ruanjian','shengwu','shijuan','shiti1s','shiti1x','shiti2s','shiti2x','shiti3s','shiti3x','shiti4s','shiti4x','shiti5s','shiti5x','shiti6s','shiti6x','shiti7s','shiti7x','shiti8s','shiti8x','shiti9s','shiti9x','shuxue','sujiao','waiyan','whsdja','wuli','xiaoshengchu','xiaoshengchushiti','xxja','yingyu','youeryuan','yuwen','yyja','zhengzhi','zhongkao','zhongkaoshiti','zhuchi','zongjie','zuowen'];
//
//
//
//
//        $all_single =  TestSingle::where('status',2)->select('id','type','sid')->take(1000)->get();
//
//        foreach ($all_single as $single){
//            $original_record = TestKjzhan::find($single->sid,['id','href']);
//
//            $courseware['title'] = $original_record->title;
//            $courseware['uuid'] = Uuid::uuid4()->toString();
//
//            $single_pages = TestSinglePage::where(['pid'=>$single->sid,'ptype'=>$single->type])->select('page_index','page_text')->get();
//            foreach ($single_pages as $page){
//
//                $courseware_page['uuid'] = Uuid::uuid4()->toString();
//                $courseware_page['courseware_id'] = $courseware['uuid'];
//                $courseware_page['page_index'] = $page->page_index;
//                $courseware_page['page_img'] = 111;
//                $courseware_page['page_text'] = $page->page_text;
//                $now_dir = public_path('courseware/'.date('Y-m-d').'/');
//                if(!is_dir($now_dir)){
//                    mkdir($now_dir,0777,true);
//                }
//                $now_img_cover = $now_dir.Uuid::uuid4()->toString().'.jpg';
//                \File::copy('F:/'.$single->type.'/'.$single->sid.'/page_all/'.$page->page_index.'.jpg', $now_img_cover);
//                $center = new \stojg\crop\CropCenter($now_img_cover);
//                $croppedImage = $center->resizeAndCrop(1000,1333);
//                $croppedImage->writeimage($now_dir);
//                dd($courseware_page);
//                CoursewarePage::create($courseware_page);
//            }
//            Courseware::create();
//        }
    }
}
