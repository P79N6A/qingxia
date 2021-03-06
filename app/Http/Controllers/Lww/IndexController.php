<?php

namespace App\Http\Controllers\Lww;

use App\BookVersionType;
use App\Http\Controllers\OssController;
use App\LwwBook;
use App\LwwBookChapter;
use App\LwwBookMp3;
use App\LwwBookPage;
use App\LwwBookPageTimupos;
use App\LwwTimuOcrResult;
use App\OneModel\AOnlyBook;
use App\OneModel\AThreadBook;
use App\OneModel\AThreadChapter;
use App\OneModel\AWorkbook;
use App\PrePluginWorkbookKnow;
use App\PrePluginWorkbookQuestion;
use App\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

require_once app_path('Http/Controllers/Libs/baiduocr/AipOcr.php');
use AipOcr;

class IndexController extends Controller
{
    public function index()
    {
      $data['subject_id'] = 0;
      $data['grade_id'] = 0;
      $data['word'] = '';

      $data['all_user'] = json_encode(User::all(['id','name as text']));
        $data['all_book'] = LwwBook::where('hide',0)->join('book_version_type', 'a_book.version_id', 'book_version_type.id')
            ->join('users','a_book.uid','users.id')
            ->join('sort', 'a_book.sort_id', 'sort.id')
            ->select(['a_book.*','users.name as username','book_version_type.name as version_name', 'sort.name as sort_name'])->orderBy('addtime', 'desc')
            ->paginate(10);

        $bookids = collect($data['all_book']->items())->pluck('id');
        #dd(collect($data['all_book']->items())->pluck('id'));

        $a = LwwBookChapter::whereIn('bookid',$bookids)->where('pages','<>',null)->orderBy('id','asc')->select('bookid','pages')->get();
        foreach ($data['all_book'] as $key=>$value){
          	foreach ($a as $chapter){
          		if($value->id===$chapter->bookid){
          			if(isset($chapter->pages)){
				          $data['all_book'][$key]['max_page'] = array_last(explode(',',$chapter->pages));
			          }else{
				          $data['all_book'][$key]['max_page'] = 0;
			          }
		          }
	          }
	        $max_now_page = LwwBookPageTimupos::where('bookid',$value->id)->max('pageid');
	        if(isset($max_now_page)){
		        $data['all_book'][$key]['max_page_now'] =$max_now_page;
	        }else{
		        $data['all_book'][$key]['max_page_now'] = 0;
	        }
        }

        //$data['version'] = BookVersionType::all();
        return view('lww.index', compact('data'));
    }

    public function index_search($subject_id=0,$grade_id=0,$word=''){
      $data['all_user'] = json_encode(User::all(['id','name as text']));
      $data['subject_id'] = intval($subject_id);
      $data['grade_id'] = intval($grade_id);
      $data['word'] = $word;
      $data['all_book'] = LwwBook::where('is_del',0)->join('book_version_type', 'a_book.version_id', 'book_version_type.id')
        ->join('users','a_book.uid','users.id')
        ->join('sort', 'a_book.sort_id', 'sort.id')
        ->where(function ($query) use($subject_id,$grade_id,$word){
          $subject_id = intval($subject_id);
          $grade_id = intval($grade_id);
          if($subject_id>0){
            $query->where('a_book.subject_id',$subject_id);
          }
          if($grade_id>0){
            $query->where('a_book.grade_id',$grade_id);
          }
          if($word){
            $query->where('a_book.bookname','like','%'.$word.'%');
          }
        })
        ->select(['a_book.*','users.name as username','book_version_type.name as version_name', 'sort.name as sort_name'])->orderBy('addtime', 'desc')
        ->paginate(10);
      //$data['version'] = BookVersionType::all();
      return view('lww.index', compact('data'));
    }

    public function add($id = 0)
    {
        $data['book_id'] = $id;
        if ($data['book_id'] != 0) {
            $data['now_book'] = LwwBook::where('a_book.id', $data['book_id'])
                ->join('sort', 'a_book.sort_id', 'sort.id')
                ->select(['a_book.*', 'sort.name as sort_name'])
                ->first()
                ->toJson();
        }
        $data['version'] = BookVersionType::all('id', 'name');
        return view('lww.add', compact('data'));
    }

  /**
   * @param $bookid
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function chapter($bookid,$year=2018,$volume=1,$single_book_id)
    {
        $data['single_book_id'] = $single_book_id;
        $oss = new OssController();
        $file_arr = [];
        /* $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => 'all_book_pages/'.$bookid.'/pages/','max-keys'=>1000]);
        #dd($all_img->getObjectList());
        $files = [];
        foreach ($all_img->getObjectList() as $img){
            $img_url = $img->getKey();
            if($img_url!='all_book_pages/'.$bookid.'/pages/'){
                $files[] = $img_url;
            }
        }

        $f = new Filesystem();
        foreach ($files as $key=>$file){
            if($f->extension($file)=='jpg') {
                $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));
                $file_arr[intval($now_file)] = basename($file);
            }
        }
        ksort($file_arr); */
        $data['volume_id'] = $volume;
        $data['book_id'] = $bookid;
        $data['year'] = $year;
//        $data['all_chapter'] = LwwBookChapter::from('a_book_chapter as c')
//            ->where('c.bookid', $bookid)->select(['c.*','m.id as mp3id'])->orderBy('c.chapter', 'asc')->get();
       /* $file_arr = [];
        $f = new Filesystem();
        foreach ($files as $key=>$file){
            if($f->extension($file)=='jpg') {
                $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));
                $file_arr[intval($now_file)] = basename($file);
            }
        }
        ksort($file_arr);*/
        //dd($file_arr);

        /*$files = Storage::files('all_book_pages/'.$bookid.'/pages');






      $audios = Storage::files('all_book_pages/'.$bookid.'/musics');

      if(empty($audios)){
        $data['has_audio'] = 0;
      }else{
        $data['has_audio'] = 1;
      };*/
//
//        foreach ($data['all_chapter'] as $key=>$value){
//          if($value->voice){
//            $data['all_chapter'][$key]['mp3id'] = $value->id;
//          }
//        }


//        $data['all_voice'] = LwwBookChapter::from('a_book_chapter as c')->join('a_book_mp3 as m','c.id','m.chapterid')->where('bookid', $bookid)->select()->get();
//        dd($data['all_voice']);


        #$data['all_pages'] = collect($file_arr);
        $re=AThreadChapter::where(['onlyid'=>$bookid,'status'=>0,'year'=>$year,'volume_id'=>$volume])->orderBy('lev','asc')->orderBy('num','asc')->select()->get();

        if(count($re)<1){
            return redirect()->route('one_lww_chapter',[$bookid,$year,$volume]);
        }
        foreach($re as $k=>$v){
            $data['chapter_list'][$v->volume_id][]=$v;
        }
//        foreach($data['chapter_list'] as $k=>$v){
//            $now_book_id = AThreadBook::where(['onlyid'=>$bookid])->select('a_book_'.$k)->first();
//
//            if($now_book_id){
//                $now_book_id = $now_book_id->toArray();
//                if(intval($now_book_id['a_book_'.$k])<=0){
//                    $now_book_year_id = $bookid.config('workbook.school_year').$k;
//                    AThreadBook::where(['onlyid'=>$bookid])->update(['a_book_'.$k=>$now_book_year_id]);
//                }
//            }else{
//                $now_book_year_id = $bookid.config('workbook.school_year').$k;
//                AThreadBook::where(['onlyid'=>$bookid])->update(['a_book_'.$k=>$now_book_year_id]);
//            }
//        }

//        $data['book']=AThreadBook::where(['onlyid'=>$bookid])->select('a_book_1','a_book_2','a_book_3','name')->first();
        $all_img = [];
        foreach($data['chapter_list'] as $k=>$v){
                $arr=$this->getTree($v);
                $data['chapter_list'][$k]=$this->has_parent($arr);
                $now_book_path = 'all_book_pages/'.get_bookid_array_path($data['book_id'],$data['year'],$data['volume_id']).'/pages/';
                $all_img= $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => $now_book_path,'max-keys'=>1000]);

                $files = [];
                foreach ($all_img->getObjectList() as $img){
                    $img_url = $img->getKey();
                    if($img_url!=$now_book_path){
                        $files[] = $img_url;
                    }
                }
                $file_arr = [];
                $f = new Filesystem();
                foreach ($files as $key=>$file){
                    if($f->extension($file)=='jpg') {
                        $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));
                        $file_arr[intval($now_file)] = basename($file);
                    }
                }
                ksort($file_arr);
                $data['all_pages'][$k] = $file_arr;

        }

//        $re2=LwwBook::where(['thread_book_id'=>$bookid])->select()->get();
//
//        foreach($re2 as $k=>$v){
//            $data['booklist'][$v['volumes_id']][]=$v;
//        }



      //dd($data['book']);
        //dd($data);
      //dd($data['booklist']);
      return view('lww.chapter', compact('data'));
    }

    function object_array($array) {
        if(is_object($array)) {
            $array = $array->toArray();
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }


    function getTree($array){
        $array=$this->object_array($array);
        $refer = [];
        $tree = [];
        foreach($array as $k => $v){

            $refer[$v['id']] = & $array[$k];
        }
        foreach($array as $k => $v){
            //$v=$v->toArray();
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

    function has_parent($array){
        //dd($array);
        $items = [];
        foreach($array as $k=>$v){
            if(isset($v['child'])){
                $arr=$v['child'];
                $v['child']=1;
                $items[]=$v;
                $items=array_merge($items,$this->has_parent($arr));
            }else{
                $v['child']=0;
                $items[]=$v;
            }

        //if(!empty($arr)) $this->has_parent($arr);
        }
        //print_r($items);
        return $items;
    }

    public function add_chapter($chapter_id = 0)
    {
        $data['chapter_id'] = $chapter_id;
        return view('lww.add_chapter', compact('data'));
    }

    //单页试题管理
    public function show_page($book_id, $chapter_id=0)
    {

        //爱奇艺视频access_token
        $get_access_token = json_decode(file_get_contents('https://openapi.iqiyi.com/api/iqiyi/authorize?client_id=45dad714d8ab40c0a7ee2c6b2d5a7c49&client_secret=5d31c3797b779530288f9459bef315ef'), true);

        $data['access_token'] = $get_access_token['data']['access_token'];
        $data['book_id'] = $book_id;
        $data['chapter_id'] = $chapter_id;
        $data['bookinfo'] = AOnlyBook::where(['onlyid'=>substr($book_id,0,-3)])->select(['grade_id','subject_id'])->first();

        $pages_now = AThreadChapter::where('id', intval($chapter_id))->select('pages', 'name as chaptername')->first();

        if(!$pages_now || !$pages_now->pages){
            //未分配页码，设置chapter_id=0,pages为全部页码
            //dd('未分配页码');

            $oss = new OssController();
            $book_page_path = 'all_book_pages/'.get_bookid_path($book_id).'/pages';
            $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => $book_page_path,'max-keys'=>1000]);

            $page_all = [];
            foreach ($all_img->getObjectList() as $img){
                $img_url = str_replace($book_page_path, '', $img->getKey());
                $img_explode = explode('.', $img_url);
                if($img_url!=='' && count($img_explode)==2){
                    $page_all[] = $img_explode[0];
                }
            }
            sort($page_all);

            if(count($page_all)==0){
                return redirect()->route('lww_upload_page',[$data['book_id']])->with('error', '图片未上传,请联系本地批量上传或在本页面手动上传');
            }

            $data['chaptername'] = '暂未分章节';

        }else{
            $data['chaptername'] = $pages_now->chaptername;

            $page_all = explode(',', $pages_now->pages);
            //更新未分页章节所属页码解析
            LwwBookPageTimupos::where(['bookid'=>$book_id,'chapterid'=>0])->whereIn('pageid',$page_all)->update(['chapterid'=>$chapter_id]);
            LwwBookQuestion::where(['bookid'=>$book_id,'chapterid'=>0])->whereIn('pageid',$page_all)->update(['chapterid'=>$chapter_id]);
        }

        foreach ($page_all as $key=>$value){
          //$img_size = getimagesize(storage_path('app/public/all_book_pages/'.$book_id.'/pages/'.$value.'.jpg'));
            try{
                $img_size = getimagesize(Storage::url('all_book_pages/'.get_bookid_path($book_id).'/pages/'.$value.'.jpg'));
            }catch (\Exception $e){
                dd('图片未匹配');
            }
          $data['all_pages'][$key]['id'] = $value;
          $data['all_pages'][$key]['page'] = $value;
          $data['all_pages'][$key]['img'] = Storage::url('all_book_pages/'.get_bookid_path($book_id).'/pages/'.$value.'.jpg').'?t='.time();
          $data['all_pages'][$key]['width'] = $img_size[0];
          $data['all_pages'][$key]['height'] = $img_size[1];
        }




      $data['all_answers'] = [];
      $all_answers_now = Storage::files('all_book_pages/'.get_bookid_path($book_id).'/answers/');

      if(!empty($all_answers_now)){
        $f = new Filesystem();
        foreach ($all_answers_now as $key=>$value){
          if($f->extension($value)=='jpg'){
            $now_file = substr(basename($value),0,-(strlen($f->extension($value))+1));
            $file_arr[intval($now_file)] = 'all_book_pages/'.get_bookid_path($book_id).'/pages/'.basename($value);
              $data['all_answers'][$now_file] = Storage::url("{$value}");
          }
        }
        ksort($data['all_answers']);
      }

        return view('lww.show_page', compact('data'));
    }


    //页面上传管理
    public function upload_page($book_id,$volume_id=0)
    {
        $volume_id = intval($volume_id);
        $data['real_book_id'] = $book_id;

        #$re=AThreadChapter::where(['pid'=>$book_id,'status'=>0,'volume_id'=>$volume_id])->orderBy('lev','asc')->orderBy('num','asc')->select()->get();

        #if($volume_id>=0 && $volume_id<=3){
        #    $book_id = $book_id.config('workbook.school_year').$volume_id;
        #
        #}

        $data['book_id'] = $book_id;
        $data['volume_id'] = intval($volume_id);
        $oss = new OssController();

        $book_path = 'all_book_pages/'.get_bookid_path($book_id).'/pages/';

        $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => $book_path,'max-keys'=>1000]);

        #dd($all_img->getObjectList());
        $files = [];
        foreach ($all_img->getObjectList() as $img){
            $img_url = $img->getKey();
            if($img_url!==$book_path){
                $files[] = $img_url;
            }
        }


        #$files = Storage::files('all_book_pages/'.$book_id.'/pages');
        //$data['all_pages'] = LwwBookPage::where('bookid',$book_id)->select()->orderBy('page','asc')->paginate(30);
        if(count($files)>0){

            $f = new Filesystem();
            foreach ($files as $key=>$file){
                if(in_array($f->extension($file), ['jpg','png','gif'])){
                    $now_file = substr(basename($file),0,-(strlen($f->extension($file))+1));
                    $file_arr[intval($now_file)] = $book_path.basename($file);
                }
            }

            ksort($file_arr);
            $data['all_pages'] = $file_arr;
        }else{
            $data['all_pages'] = [];
        }



        return view('lww.upload_page',compact('data'));
    }

    //ocr结果处理
    public function ocr_results()
    {
      set_time_limit(0);
      $now_timu = LwwBookPageTimupos::select('bookid','chapterid','timu_page','pageid','id','sort','timuid')->orderBy('id','asc')->skip(200)->take(500)->get();

      $all_timu = collect($now_timu)->groupBy('timuid');
      $aipOcr = new AipOcr(config('workbook.APP_ID'), config('workbook.API_KEY'), config('workbook.SECRET_KEY'));
      $data = [];
      $search_result = [];

      foreach($all_timu as $timu) {
        foreach ($timu as $key => $pic) {
//            $img_now = file_get_contents(\Storage::url('all_book_pages/' . $pic->bookid . '/cut_pages/' . $pic->timu_page . '/' . $pic->sort . '_' . $pic->id . '.jpg'));
          if (is_file(storage_path('app/public/all_book_pages/' . get_bookid_path($pic->bookid) . '/cut_pages/' . $pic->timu_page . '/' . $pic->sort . '_' . $pic->id . '.jpg'))) {
            $data[$pic->timuid][$key] = $aipOcr->basicGeneral(file_get_contents(storage_path('app/public/all_book_pages/' . get_bookid_path($pic->bookid) . '/cut_pages/' . $pic->timu_page . '/' . $pic->sort . '_' . $pic->id . '.jpg')));
          }
        }
        if (!empty($data[$timu[0]->timuid])) {
          $result = [];
          foreach ($data[$timu[0]->timuid] as $key => $value) {
            $result[$timu[0]->timuid] = '';
            if ($value['words_result_num'] > 0) {
              $result[$timu[0]->timuid] .= collect($value['words_result'])->implode('words', ' ');
//                $now_timu = file_get_contents('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word='.urlencode($words_now));
//                $search_result[$key] =$now_timu;
            }
          }
          if(!empty($result)){
            foreach ($result as $key1=>$value1){
              $ocr_result['timuid'] = $key1;
              $ocr_result['result'] = $value1;
              $ocr_result['questions'] = $this->search_words($value1);
              try{
                LwwTimuOcrResult::create($ocr_result);
              } catch (\Exception $e) {
                print $e;
              }
            }
          }
        }
      }



//      if(!empty($data)){
//        $result = [];
//        foreach ($data as $key=>$value){
//          $result[$key] = '';
//          if(!empty($value)){
//            foreach ($value as $key1=>$value1){
//              if($value1['words_result_num']>0){
//                $result[$key] .= collect($value1['words_result'])->implode('words', ' ');
////                $now_timu = file_get_contents('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word='.urlencode($words_now));
////                $search_result[$key] =$now_timu;
//              }
//            }
//          }
//        }
//        if(!empty($result)){
//          foreach ($result as $key1=>$value1){
//            $ocr_result['timuid'] = $key1;
//            $ocr_result['result'] = $value1;
//            $ocr_result['questions'] = $this->search_words($value1);
//            try{
//              LwwTimuOcrResult::create($ocr_result);
//            } catch (\Exception $e) {
//              print $e;
//            }
//
//          }
//        }
//        return response()->json(['status'=>1]);
//      }else{
//        return response()->json(['status'=>0]);
//      }

    }

    public function search_words($word_now)
    {
      $word_now = mb_substr($word_now,0,15);
      $now_timu = file_get_contents('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word='.urlencode($word_now));

      return $now_timu;
    }


    //单页点读管理
    public function diandu_page($book_id,$chapter_id){
      $book_id = intval($book_id);
      $chapter_id = intval($chapter_id);
      $data['book_id'] = $book_id;
      $data['chapter_id'] = $chapter_id;
      $data['bookinfo'] = LwwBook::where('a_book.id', $data['book_id'])
        ->join('book_version_type', 'a_book.version_id', 'book_version_type.id')
        ->select(['a_book.*', 'book_version_type.name as version_name'])
        ->first();
      $pages_now = LwwBookChapter::where('id', $chapter_id)->where('bookid', $book_id)->select('pages','chaptername')->first();
      $data['chaptername'] = $pages_now->chaptername;
      $page_all = explode(',', $pages_now->pages);
      foreach ($page_all as $key=>$value){
        //$img_size = getimagesize(storage_path('app/public/all_book_pages/'.$book_id.'/pages/'.$value.'.jpg'));
        $img_size = getimagesize(Storage::url('all_book_pages/'.get_bookid_path($book_id).'/pages/'.$value.'.jpg'));
        $data['all_pages'][$key]['id'] = $value;
        $data['all_pages'][$key]['page'] = $value;
        //$data['all_pages'][$key]['img'] = Storage::url('storage/all_book_pages/'.$book_id.'/pages/'.$value.'.jpg').'?t='.time();
          $data['all_pages'][$key]['img'] = Storage::url('all_book_pages/'.get_bookid_path($book_id).'/pages/'.$value.'.jpg').'?t='.time();
        //$data['all_pages'][$key]['img'] = asset('storage/all_book_pages/'.$book_id.'/pages/'.$value.'.jpg');
        $data['all_pages'][$key]['width'] = $img_size[0];
        $data['all_pages'][$key]['height'] = $img_size[1];
      }

      if($data['bookinfo']->real_id){
        $data['chapters'] =  PrePluginWorkbookKnow::where('lxc_id',$data['bookinfo']->real_id)->select(['id','chapter_name'])
          ->orderBy('id','asc')
          ->get();
      }

      $data['audios'] = Storage::allFiles('all_book_pages/'.get_bookid_path($book_id).'/musics');

      return view('lww.diandu_page', compact('data'));
    }
}
