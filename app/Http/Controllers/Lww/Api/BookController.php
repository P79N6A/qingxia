<?php

namespace App\Http\Controllers\Lww\Api;

use App\ABook1010;
use App\ABookOcrRecord;
use App\Http\Controllers\OssController;
use App\LwwBook;
use App\LwwBookChapter;
use App\LwwBookPage;
//use App\LwwBookPageDiandupos;
use App\LwwBookPageTimupos;
use App\LwwBookQuestion;
use App\OneModel\AThreadBook;
use App\OneModel\AThreadChapter;
use App\OneModel\PreForumPost;
use App\OnlineModel\AWorkbook1010;
use App\OnlineModel\AWorkbookAnswer;
use App\PrePluginWorkbookQuestion;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;
use SebastianBergmann\GlobalState\RuntimeException;
use Symfony\Component\HttpFoundation\File\File;


require_once app_path('Http/Controllers/Libs/baiduocr/AipOcr.php');

use AipOcr;



class BookController extends Controller
{
  protected $now_uid;

  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $this->now_uid = Auth::id();
      return $next($request);
    });
  }

  public function search_book(Request $request)
  {
    $type_now = '';
    $search_type = $request->get('type');
    if ($search_type == 'book_isbn') {
      $type_now = '-type-isbn';
    }
    $word = $request->get('word');
    exit(file_get_contents('http://www.1010jiajiao.com/html5app/api/search/word-' . $word . '-index-zuoyeben' . $type_now));
  }

  //更换练习册处理人
  public function change_lxc_user(Request $request)
  {
    $book_id = $request->now_book_id;
    $data['uid'] = $request->now_uid;
    $r = LwwBookPageTimupos::where(['bookid'=>$book_id])->update($data);
    LwwBookQuestion::where(['bookid'=>$book_id])->update($data);
    if($r){
      $R = ['status'=>1,'msg'=>'操作成功'];
    }else{
      $R = ['status'=>0,'msg'=>'操作失败'];
    }
    exit(json_encode($R));
  }


  //课本检查
  public function verify_about(Request $request)
  {
    $type = $request->get('type');
    $R = [];
    $id = intval($request->get('book_id'));
    $time = date('Y-m-d H:i:s',time());
    switch ($type){
      case 'verify_submit':
        $update['verify_status'] = 1;
        $update['verify_submit_time'] = $time;
        $s = LwwBook::where(['id'=>$id,'uid'=>$this->now_uid,'verify_status'=>0])->update($update);
        if($s){
          $R = ['status'=>1,'msg'=>'操作成功','time'=>$time];
        }else{
          $R = ['status'=>0,'msg'=>'操作失败'];
        }
        break;
      case 'verify_start':
        $uid = intval($request->get('uid'));
        $update['verify_status'] = 2;
        $update['verify_start_time'] = $time;
        $s = LwwBook::where(['id'=>$id,'uid'=>$uid,'verify_status'=>1])->update($update);
        if($s){
          $R = ['status'=>1,'msg'=>'操作成功','time'=>$time];
        }else{
          $R = ['status'=>0,'msg'=>'操作失败'];
        }
        break;

      case 'verify_reject':
        $uid = intval($request->get('uid'));
        $update['verify_status'] = 0;
        $update['verify_start_time'] = $update['verify_submit_time'] = null;
        $s = LwwBook::where(['id'=>$id,'uid'=>$uid,'verify_status'=>2])->update($update);
        if($s){
          $R = ['status'=>1,'msg'=>'操作成功','time'=>$time];
        }else{
          $R = ['status'=>0,'msg'=>'操作失败'];
        }
        break;

      case 'verify_end':
        //审核完毕后上传当前练习册文件夹所有内容
        set_time_limit(0);
        $uid = intval($request->get('uid'));
        $update['verify_status'] = 3;
        $update['verify_end_time'] = $time;
        $s = LwwBook::where(['id'=>$id,'uid'=>$uid,'verify_status'=>2])->update($update);
        if($s){
          ignore_user_abort(true);
          $all_cut_pages = LwwBookPageTimupos::where('bookid',$id)->select(['pageid','timu_page','sort','id'])->get();
          $all_pic = [];
          foreach ($all_cut_pages as $value){
            $all_pic[] = "all_book_pages/".get_bookid_path($id)."/cut_pages/{$value->timu_page}/{$value->sort}_{$value->id}.jpg";
          }
          $all_now_pic = Storage::allFiles("all_book_pages/".get_bookid_path($id)."/cut_pages/");

          $not_need_pics = collect($all_now_pic)->diff($all_pic);
          $oss = new OssController();
          foreach ($not_need_pics as $pic){
            $s = Storage::delete($pic);
            $oss->delete($pic);
          }
//          $files = Storage::allFiles("all_book_pages/{$id}/");
//          foreach ($files as $file){
//            $utf8_file = iconv('gbk','utf-8',$file);
//            $oss->save($utf8_file,file_get_contents(storage_path("app/public/{$file}")));
//          }
          $R = ['status'=>1,'msg'=>'操作成功','time'=>$time];
        }else{
          $R = ['status'=>0,'msg'=>'操作失败'];
        }
        break;
    }
    return response()->json($R);
  }

  public function add(Request $request)
  {
//    $this->validate($request, [
//      'book_id' => 'required|integer',
//      'book_version_year' => 'required|integer',
//      'book_grade' => 'required|integer',
//      'book_subject' => 'required|integer',
//      'book_volume' => 'required|integer',
//      'book_version' => 'required|integer',
//      'book_sort' => 'required',
//      'book_type' => 'required'
//    ]);
//
//    $version = $request->get('book_version');
//    $grade = $request->get('book_grade');
//    $subject = $request->get('book_subject');
//    $volume = $request->get('book_volume');
//
//    if ($request->get('book_id') > 0) {
//      $book = LwwBook::findOrFail($request->get('book_id'));
//    } else {
//      $book = new LwwBook();
//      $book->uid = $this->now_uid;
//    }
//    $book_type = explode(',', $request->get('book_type'));
//    if (in_array(1, $book_type)) {
//      $book->jiexi = 1;
//    } else {
//      $book->jiexi = 0;
//    }
//    if (in_array(2, $book_type)) {
//      $book->diandu = 1;
//    } else {
//      $book->diandu = 0;
//    }
//    if (in_array(3, $book_type)) {
//      $book->gendu = 1;
//    } else {
//      $book->gendu = 0;
//    }
//    if (in_array(4, $book_type)) {
//      $book->tingxie = 1;
//    } else {
//      $book->tingxie = 0;
//    }
//    $book->bookname = $request->get('book_name');
//    $book->isbn = $request->get('book_isbn');
//    $book->cover = $request->get('book_img');
//    $book->sort_id = $request->get('book_sort');
//    $book->real_id = intval($request->get('book_real_id'));
//    $book->grade_id = $grade;
//    $book->subject_id = $subject;
//    $book->volumes_id = $volume;
//    $book->version_id = $version;
//    $sort_version = strlen($version) > 1 ? $version : '0' . $version;
//    $sort_grade = strlen($grade) > 1 ? $grade : '0' . $grade;
//    $sort_subject = strlen($subject) > 1 ? $subject : '0' . $subject;
//    $sort_volume = strlen($volume) > 1 ? $volume : '0' . $volume;
//    $book->booksort = $sort_version . $sort_subject . $sort_grade . $sort_volume;
//
//    $book->version_year = $request->get('book_version_year');
//
//    $book->status = $request->get('book_status');
//    if ($book->save()) {
//        $new_id = $book->id;
//        $oss = new OssController();
//
//      if (!is_dir(storage_path('app/public/all_book_pages/' . $new_id))) {
//        mkdir(storage_path('app/public/all_book_pages/' . $new_id));
//        mkdir(storage_path('app/public/all_book_pages/' . $new_id . '/pages'));
//        mkdir(storage_path('app/public/all_book_pages/' . $new_id) . '/cut_pages');
//
//        $book_info_now = LwwBook::from('a_book')->where('a_book.id', $new_id)
//          ->lefTjoin('book_version_type', 'a_book.version_id', 'book_version_type.id')
//          ->lefTjoin('sort', 'a_book.sort_id', 'sort.id')
//          ->lefTjoin('users', 'a_book.uid', 'users.id')
//          ->select(['a_book.*', 'book_version_type.name as version_name', 'sort.name as sort_name', 'users.name as username'])
//          ->first();
//        $book_info['name'] = $book_info_now->bookname;
//        $book_info['grade'] = config('workbook.grade')[$book_info_now->grade_id];
//        $book_info['subject'] = config('workbook.subject_1010')[$book_info_now->subject_id];
//        $book_info['volumes'] = config('workbook.volumes')[$book_info_now->volumes_id];
//        $book_info['version_year'] = $book_info_now->version_year;
//        $book_info['version_name'] = $book_info_now->version_name;
//        $book_info['sort_name'] = $book_info_now->sort_name;
//        $book_info['isbn'] = $book_info_now->isbn;
//        $book_info['add_time'] = $book_info_now->addtime;
//        $book_info['username'] = $book_info_now->username;
//        $now_book_info = json_encode($book_info, JSON_UNESCAPED_UNICODE);
//        Storage::put('all_book_pages/' . $new_id . '/book_info.json', $now_book_info);
//        //$oss->uploaddir($oss_pre_path, $localdir);
//      }
//
//      return response()->json(['status' => 1, 'msg' => '操作成功']);
//    } else {
//      return response()->json(['status' => 0, 'msg' => '操作失败']);
//    }
  }

  public function get_check(Request $request)
  {
    $id = intval($request->get('book_id'));
    $R = ['status' => 1, 'msg' => '可以入库'];
    if ($id != 0) {
      $has_it = LwwBook::where('real_id', $id)->count();
      if ($has_it != 0) {
        $R = ['status' => 0, 'msg' => '已添加,不能重复添加'];
      }
    }
    return response()->json($R);
  }

  public function get_chapter($book_id)
  {
    $book_now = LwwBook::find($book_id);
    if ($book_now) {
      $book_chapters = $book_now->chapters()->orderBy('chapter', 'asc')->get();
      $now_key_1 = -1;
      $now_key_2 = -1;
      $now_key_3 = -1;
      $now_key_4 = 0;
      $now_key_5 = 0;
      $now_key_6 = 0;
      $book_first_chapters = '';
      $book_new = array();

      if (count($book_chapters) > 0) {
        foreach ($book_chapters as $key => $value) {
          if (strlen($value->chapter) == 10) {
            $now_key_1 += 1;
            $now_key_2 = -1;
            $book_new[$now_key_1]['id'] = $value->chapter;
            $book_new[$now_key_1]['text'] = $value->chaptername;
          } elseif (strlen($value->chapter) == 12) {
            $now_key_2 += 1;
            $now_key_3 = -1;
            $book_new[$now_key_1]['children'][$now_key_2] = array('id' => $value->chapter, 'text' => $value->chaptername);
          } elseif (strlen($value->chapter) == 14) {
            $now_key_3 += 1;
            $now_key_4 = -1;
            $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3] = array('id' => $value->chapter, 'text' => $value->chaptername);
          } elseif (strlen($value->chapter) == 16) {
            $now_key_4 += 1;
            $now_key_5 = -1;
            $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3]['children'][$now_key_4] = array('id' => $value->chapter, 'text' => $value->chaptername);
          } elseif (strlen($value->chapter) == 18) {
            $now_key_5 += 1;
            $book_new[$now_key_1]['children'][$now_key_2]['children'][$now_key_3]['children'][$now_key_4]['children'][$now_key_5] = array('id' => $value->chapter, 'text' => $value->chaptername);
          }
        }
        //$book_first_chapters = $book_now->chapters()->orderBy('chapter','asc')->select('chaptername')->first()->chaptername;
      }
      //$chapter_root = !empty($book_first_chapters)?$book_first_chapters:'新建章节';
      $final_chapter_all = array("id" => "0", "text" => "$book_now->bookname", "type" => "root", "state" => array("opened" => true), "children" => $book_new);

      return response()->json($final_chapter_all);

    }
  }

  public function set_chapter(Request $request)
  {

    $get_book_id = $request->get('id');
    $booksort_now = LwwBook::find($get_book_id)->booksort;
    $get_chapters = $request->get('chapters');
    $chapter_now = json_decode($get_chapters);
    $time_now = date('Y-m-d H:i:s', time());
    $now_key_1 = 0;
    $now_key_2 = 0;
    $now_key_3 = 0;
    $now_key_4 = 0;
    $now_key_5 = 0;
    $now_key_6 = 0;
    $chapter_final = [];
    foreach ($chapter_now as $key => $value) {
      if ($value->level == 1) {
        $chapter_final[$key]['chapter'] = $booksort_now;
      } elseif ($value->level == 2) {
        $now_key_1 += 1;
        $now_key_2 = 0;
        $in_key_1 = strlen($now_key_1) > 1 ? $now_key_1 : '0' . $now_key_1;
        $chapter_final[$key]['chapter'] = $booksort_now . $in_key_1;

      } elseif ($value->level == 3) {
        $now_key_2 += 1;
        $now_key_3 = 0;
        $in_key_1 = strlen($now_key_1) > 1 ? $now_key_1 : '0' . $now_key_1;
        $in_key_2 = strlen($now_key_2) > 1 ? $now_key_2 : '0' . $now_key_2;
        $chapter_final[$key]['chapter'] = $booksort_now . $in_key_1 . $in_key_2;
      } elseif ($value->level == 4) {
        $now_key_3 += 1;
        $now_key_4 = 0;
        $in_key_1 = strlen($now_key_1) > 1 ? $now_key_1 : '0' . $now_key_1;
        $in_key_2 = strlen($now_key_2) > 1 ? $now_key_2 : '0' . $now_key_2;
        $in_key_3 = strlen($now_key_3) > 1 ? $now_key_3 : '0' . $now_key_3;
        $chapter_final[$key]['chapter'] = $booksort_now . $in_key_1 . $in_key_2 . $in_key_3;
      } elseif ($value->level == 5) {
        $now_key_4 += 1;
        $now_key_5 = 0;
        $in_key_1 = strlen($now_key_1) > 1 ? $now_key_1 : '0' . $now_key_1;
        $in_key_2 = strlen($now_key_2) > 1 ? $now_key_2 : '0' . $now_key_2;
        $in_key_3 = strlen($now_key_3) > 1 ? $now_key_3 : '0' . $now_key_3;
        $in_key_4 = strlen($now_key_4) > 1 ? $now_key_4 : '0' . $now_key_4;
        $chapter_final[$key]['chapter'][] = $booksort_now . $in_key_1 . $in_key_2 . $in_key_3 . $in_key_4;
      } elseif ($value->level == 6) {
        $now_key_5 += 1;
        $now_key_6 = 0;
        $in_key_1 = strlen($now_key_1) > 1 ? $now_key_1 : '0' . $now_key_1;
        $in_key_2 = strlen($now_key_2) > 1 ? $now_key_2 : '0' . $now_key_2;
        $in_key_3 = strlen($now_key_3) > 1 ? $now_key_3 : '0' . $now_key_3;
        $in_key_4 = strlen($now_key_4) > 1 ? $now_key_4 : '0' . $now_key_4;
        $in_key_5 = strlen($now_key_5) > 1 ? $now_key_5 : '0' . $now_key_5;
        $chapter_final[$key]['chapter'] = $booksort_now . $in_key_1 . $in_key_2 . $in_key_3 . $in_key_4;
      } elseif ($value->level == 7) {
        $now_key_6 += 1;
        $in_key_1 = strlen($now_key_1) > 1 ? $now_key_1 : '0' . $now_key_1;
        $in_key_2 = strlen($now_key_2) > 1 ? $now_key_2 : '0' . $now_key_2;
        $in_key_3 = strlen($now_key_3) > 1 ? $now_key_3 : '0' . $now_key_3;
        $in_key_4 = strlen($now_key_4) > 1 ? $now_key_4 : '0' . $now_key_4;
        $in_key_5 = strlen($now_key_5) > 1 ? $now_key_5 : '0' . $now_key_5;
        $in_key_6 = strlen($now_key_5) > 1 ? $now_key_6 : '0' . $now_key_6;
        $chapter_final[$key]['chapter'] = $booksort_now . $in_key_1 . $in_key_2 . $in_key_3 . $in_key_4 . $in_key_5 . $in_key_6;
      }
      $chapter_final[$key]['bookid'] = $get_book_id;
      $chapter_final[$key]['booksort'] = $booksort_now;
      $chapter_final[$key]['chaptername'] = $value->text;
      $chapter_final[$key]['addtime'] = $time_now;
      $chapter_final[$key]['uid'] = $this->now_uid;
    }

    LwwBookChapter::where('bookid', $get_book_id)->where('booksort', $booksort_now)->delete();
    if (LwwBookChapter::insert($chapter_final)) {
      return response()->json(['status' => 1, 'msg' => '更新成功']);
    } else {
      return response()->json(['status' => 0, 'msg' => '更新失败']);
    }

  }

  //通过课本生成章节
  public function make_chapter(Request $request)
  {
    $book_id = $request->get('book_id');
    $book_sort = LwwBook::find($book_id)->booksort;
    $book_now = ABook1010::where('booksort', $book_sort)->first();
//      $workbook_id = $request->get('workbook_id');
//      $book_now = ABook1010::find($book_id);

    $chapters_now = $book_now->chapters()->orderBy('chapter', 'asc')->select(DB::raw('distinct chapter'), 'id', 'booksort', 'chaptername')->get();
    $chapters_now = collect($chapters_now)->groupBy('chapter');

    foreach ($chapters_now as $key => $value) {
      $data[$key]['bookid'] = $book_id;
      $data[$key]['booksort'] = $book_sort;
      $data[$key]['chapter'] = $value[0]->chapter;
      $data[$key]['chaptername'] = $value[0]->chaptername;
      $data[$key]['addtime'] = date('Y-m-d H:i:s', time());
      $data[$key]['uid'] = $this->now_uid;
    }

    LwwBookChapter::where('bookid', $book_id)->delete();
    if (LwwBookChapter::insert($data)) {
      return response()->json(['status' => 1, 'msg' => '生成成功']);
    } else {
      return response()->json(['status' => 0, 'msg' => '生成失败']);
    }
  }

  public function edit_page(Request $request)
  {
    $act = $request->get('a');
    $R = array();
    switch ($act) {
      case 'editorsave':
        ignore_user_abort();
        $page_id = intval($request->get('now_page_id'));
        $bookid = $request->get('bookid');
        $chapterid = intval($request->get('chapterid'));
        $now_width = intval($request->get('width'));
        $now_height = intval($request->get('height'));
        $a = explode(',', $request->get('data'));
//        $size_info = getimagesize(storage_path('app/public/all_book_pages/' . $bookid . '/pages/' . $page_id . '.jpg'));
//        $im = imagecreatefromjpeg(storage_path('app/public/all_book_pages/' . $bookid . '/pages/' . $page_id . '.jpg'));

        $size_info = getimagesize(Storage::url( 'all_book_pages/' .get_bookid_path($bookid) . '/pages/' . $page_id . '.jpg?t='.time()));
        $im = imagecreatefromjpeg(Storage::url('all_book_pages/' . get_bookid_path($bookid) . '/pages/' . $page_id . '.jpg?t='.time()));
        $real_width = $size_info[0];
        $real_height = $size_info[1];
        $has_id = [];
        $oss = new OssController();
        foreach ($a as $v) {
          $b = explode(' ', $v);
          if (count($b) == 6) {
            $ret = array();
            $ret['bookid'] = $bookid;
            $ret['pageid'] = $page_id;
            $ret['chapterid'] = $chapterid;
            $ret['pleft'] = round($b[0] / $now_width, 5);
            $ret['ptop'] = round($b[1] / $now_height, 5);
            $ret['pwidth'] = round($b[2] / $now_width, 5);
            $ret['pheight'] = round($b[3] / $now_height, 5);
//                        if(is_numeric($b[4])){
//                          $b[4] = intval($b[4]);
//                        }else{
//                          $s = explode('_',$b[4]);
//                          if(!is_array($s) or intval($s[0])<0 or intval($s[1])<0 or count($s)!=2 or intval($s[0])<=0 or intval($s[1])<=0){
//                            $R['suc']=0;
//                            return response()->json($R);
//                          }
//
//                        }
            $ret['sort'] = intval($b[4]);
            $ret['timu_page'] = intval($b[5]);
            $ret['uid'] = $this->now_uid;
            $ret['timuid'] = $bookid . sprintf("%03d", $ret['timu_page']) . sprintf("%02d", $ret['sort']);
            $new_img_width = $ret['pwidth'] * $real_width;
            $new_img_height = $ret['pheight'] * $real_height;
            $newim = imagecreatetruecolor($new_img_width, $new_img_height);

            $now = imagecopyresampled($newim, $im, 0, 0, $ret['pleft'] * $real_width, $ret['ptop'] * $real_height, $ret['pwidth'] * $real_width, $ret['pheight'] * $real_height, $new_img_width, $new_img_height);
            if (!$now) {
              $R['suc'] = 0;
              return response()->json($R);
            }
//                      $s= imagejpeg($newim);
//                      dd($s);

            if (!is_dir('//QINGXIA23/www/analysis/' . get_bookid_path($bookid) . '/cut_pages/' . $page_id)) {
              mkdir('//QINGXIA23/www/analysis/' . get_bookid_path($bookid) . '/cut_pages/' . $page_id,0777,true);
            }
            if (!is_dir('//QINGXIA23/www/analysis/' . get_bookid_path($bookid) . '/cut_pages/' . $b[5])) {
              mkdir('//QINGXIA23/www/analysis/' . get_bookid_path($bookid) . '/cut_pages/' . $b[5],0777,true);
            }
            //Storage::put('test_'.$pageid.'.jpg',$s->stream());

              //bookid   pageid chapterid  sort  timu_page  timuid
              $check['bookid'] = $ret['bookid'];
              $check['pageid'] = $ret['pageid'];
              $check['chapterid'] = $ret['chapterid'];
              $check['sort'] = $ret['sort'];
              $check['timu_page'] = $ret['timu_page'];
              $check['timuid'] = $ret['timuid'];

              $has_timupos = LwwBookPageTimupos::where($check)->whereNull('mp3')->first();
              if($has_timupos){
                  LwwBookPageTimupos::where(['id'=>$has_timupos->id])->update($ret);
                  $s = $has_timupos;
              }else{
                  $s = LwwBookPageTimupos::create($ret);
              }
              $has_id[] = $s->id;
              $storage_path = '//QINGXIA23/www/analysis/' . get_bookid_path($bookid) . '/cut_pages/' . $b[5] . '/' . $ret['sort'] . '_' . $s->id . '.jpg';
              imagejpeg($newim, $storage_path);
              $oss->uploadfile('all_book_pages/' . get_bookid_path($bookid) . '/cut_pages/' . $b[5] . '/' . $ret['sort'] . '_' . $s->id . '.jpg', $storage_path);
          }
        }

         LwwBookPageTimupos::where(['pageid' => $page_id, 'chapterid' => $chapterid, 'bookid' => $bookid])->whereNull('mp3')->whereNotIn('id', $has_id)->delete();

        $R['suc'] = 1;
        return response()->json($R);
      case 'editorload':
        $pageid = intval($request->get('pageid'));
        $bookid = $request->get('bookid');
        $chapterid = intval($request->get('chapterid'));
//                $R = LwwBookPageTimupos::from('a_book_page_timupos as t')
//                  ->LeftJoin('a_book_question as q','t.timuid','q.timuid')
//                    ->where('t.bookid',$bookid)
//                    ->where('t.chapterid',$chapterid)
//                    ->where('t.pageid',$pageid)
//                    ->select('t.id','t.timuid','timu_page','sort','pleft','ptop','pwidth','pheight','t.uid', 'q.question_type', 'q.question', 'q.answer','q.answer_normal','q.analysis','q.remark','q.created_at')
//                    ->orderBy('t.timu_page','asc')
//                    ->orderBy('t.sort','asc')
//                    ->get();

        //线框和题目分开取值。。。
        $R['pos'] = LwwBookPageTimupos::where('bookid', $bookid)
          ->where('chapterid', $chapterid)
          ->where('pageid', $pageid)
          ->whereNull('mp3')
          ->select('id', 'timuid', 'pageid', 'timu_page', 'sort', 'pleft', 'ptop', 'pwidth', 'pheight', 'uid','video_id')
          ->orderBy('sort', 'asc')
          ->get();

        $timu_sql = LwwBookPageTimupos::from('a_book_page_timupos as t')
          ->leftJoin('a_book_question as q', function ($join) {
            $join->on('t.timuid', '=', 'q.timuid');
            $join->on('t.chapterid', '=', 'q.chapterid');
          })
          ->where('t.bookid', $bookid)
          ->where('t.chapterid', $chapterid)
          ->whereNull('t.mp3')
          ->where('t.timu_page', $pageid)
          ->select('t.sort', 't.id', 't.uid', 't.timu_page', 't.timuid', 'q.question_type', 'q.question', 'q.answer', 'q.answer_new','q.answer_normal', 'q.analysis', 'q.remark', 'q.created_at','t.video_id')
          ->orderBy('t.sort', 'asc')
          ->orderBy('t.id', 'asc')
          ->get();

//        foreach ($timu_sql as $timu){
//          $timu->question = preg_replace_array('/\<span class=\"answer_now\">(.*?)\<\/span\>/', explode(',|,', $timu->answer_new), $timu->question);
//        }
//          $allsub=array(21=>'czdl',22=>'czhx',23=>'czls',24=>'czsw',25=>'czsx',26=>'czwl',27=>'czyw',28=>'czyy',29=>'czzz',31=>'gzdl',32=>'gzhx',33=>'gzls',34=>'gzsw',35=>'gzsx',36=>'gzwl',37=>'gzyw',38=>'gzyy',39=>'gzzz',15=>'xxsx',17=>'xxyw',18=>'xxyy');
//          foreach ($timu_sql as $key=>$timu){
//              $all_search_ids = file_get_contents('http://192.168.0.112/jiajiaot/api/search/question?word='.base64_encode(mb_substr(strip_tags($timu->question),0,20)));
//              $all_search_ids = json_decode($all_search_ids);
//              if($all_search_ids->code==0)
//              {
//                  $res = [];
//                  foreach($all_search_ids->result as $k=>$r)
//                  {
//                      $v = $r->id;
//                      if($v>100000000){
//                          $subid = intval($v/100000000);
//                          if(!isset($allsub[$subid])){
//                              $res[$k] = [];
//                          }else{
//                              $sub = $allsub[$subid];
//                              $v =$v-$subid*100000000;
//                              $res[$k] = DB::connection('mysql_main_rds_jiajiao')->table('mo_'.$sub)->where('id',$v)->first(['question','answer']);
//                              $res[$k]->title = $res[$k]->question;
//                              $res[$k]->parse = $res[$k]->answer;
//
//                          }
//                      }else{
//                          $res[$k] = DB::connection('mysql_main_rds_tiku')->table('questions')->where('id',$v)->first(['title','parse']);
//                      }
//
//                  }
//                  $timu_sql[$key]['timu_jiexi'] = $res;
//              }
//          }




        $R['timu'] = collect($timu_sql)->groupBy('timuid');


//                foreach ($R as $key=>$value){
//                    $R[$key]['cut_pic'] = asset('storage/all_book_pages/'.$bookid.'/cut_pages/'.$value->timu_page.'/'.$value->sort.'_'.$value->id.'.jpg');
//                }
        exit(json_encode($R, JSON_UNESCAPED_UNICODE));
        break;


        case 'get_timu_jiexi':
            $question = $request->now_search;
            $all_search_ids = file_get_contents('http://www.1010jiajiao.com/api/search/question?word='.base64_encode(mb_substr(urldecode($question),0,30)));
            $all_search_ids = json_decode($all_search_ids);
            $res = [];
            if($all_search_ids->code==0)
            {
                $allsub=array(21=>'czdl',22=>'czhx',23=>'czls',24=>'czsw',25=>'czsx',26=>'czwl',27=>'czyw',28=>'czyy',29=>'czzz',31=>'gzdl',32=>'gzhx',33=>'gzls',34=>'gzsw',35=>'gzsx',36=>'gzwl',37=>'gzyw',38=>'gzyy',39=>'gzzz',15=>'xxsx',17=>'xxyw',18=>'xxyy');
                foreach($all_search_ids->result as $k=>$r)
                {
                    $v = $r->id;
                    if($v>100000000){
                        $subid = intval($v/100000000);
                        if(!isset($allsub[$subid])){
                            $res[$k] = [];
                        }else{
                            $sub = $allsub[$subid];
                            $v =$v-$subid*100000000;
                            $res[$k] = DB::connection('mysql_main_rds_jiajiao')->table('mo_'.$sub)->where('id',$v)->first(['id','question as title','answer as parse']);
                        }
                    }else{
                        $res[$k] = DB::connection('mysql_main_rds_tiku')->table('questions')->where('id',$v)->first(['id','title','parse']);
                    }

                }

            }
            return response()->json($res);
            break;

    }
  }

  //设置章节对应页码
  public function set_chapter_page(Request $request)
  {
    $this->validate($request, [
      'book_id' => 'required|integer',
      'chapter_id' => 'required|integer',
      'start_page' => 'required|integer',
      'end_page' => 'required|integer',
    ]);
    $start_page = $request->get('start_page');
    $end_page = $request->get('end_page');
    if ($end_page < $start_page) {
      return response()->json(['status' => 0, 'msg' => '页码有误']);
    }
    for ($i = $start_page; $i <= $end_page; $i++) {
      $page_arr[] = $i;
    }
    $page_str = collect($page_arr)->implode(',');
    $page_update = LwwBookChapter::where(['id' => $request->get('chapter_id'), 'bookid' => $request->get('book_id')])
      ->update(['pages' => $page_str]);
    if ($page_update) {
      return response()->json(['status' => 1, 'msg' => '更新成功', 'page_str' => $page_str]);
    }
    return response()->json(['status' => 0, 'msg' => '更新失败']);
  }

  //设置页码
    public function save_chapter_page(Request $request)
    {
        $chapter_id = intval($request->chapter_id);
        $pages =$request->pages;
        $page_str='';
        foreach($pages as $v){
            $page_str.=$v.',';
        }
        $page_str = rtrim($page_str,',');
        $page_update = AThreadChapter::where(['id' => $chapter_id])
            ->update(['pages' => $page_str]);
        if ($page_update) {
            return response()->json(['status' => 1, 'msg' => '更新成功', 'page_str' => $page_str]);
        }
        return response()->json(['status' => 0, 'msg' => '更新失败']);
    }

    public function save_answer_chapter_page(Request $request)
    {
        $chapter_id = intval($request->chapter_id);
        $pid_now =$request->pages;
        $pid_first = $pid_now[0];
//        $page_str='';
//        foreach($pages as $v){
//            $page_str.=$v.',';
//        }
//        $page_str = rtrim($page_str,',');

        AThreadChapter::where(['id' => $chapter_id])
            ->update(['pid_pages' => $pid_first]);
        PreForumPost::where(['pid'=>$pid_first])->update(['tid'=>$chapter_id]);
        return return_json(['page_str' => $pid_first]);
    }

  //设置上传页面页码
  public function set_pages_order(Request $request)
  {
    $all_pages = collect($request->all());
    foreach ($all_pages as $page) {
      LwwBookPage::where('id', $page['id'])->update(['page' => $page['page']]);
    }
    return response()->json(['status' => 1, 'msg' => '更新成功']);
  }

  //更新页码
  public function set_pages_number(Request $request)
  {
    $book_id = intval($request->get('book_id'));
    $files = Storage::files('all_book_pages/' . get_bookid_path($book_id) . '/pages/');
    $f = new Filesystem();

    foreach ($files as $file) {
      $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));
      if (strlen($now_file) == 5) {

        if (starts_with($now_file, '1')) {
          $new_name = intval(substr($now_file, 1, 4));
          Storage::move($file, 'all_book_pages/' . get_bookid_path($book_id) . '/pages/' . $new_name . '.' . $f->extension($file));
        }
        if (starts_with($now_file, '0')) {
          $new_name = intval(substr($now_file, 1, 4));
          Storage::move($file, 'all_book_pages/' . get_bookid_path($book_id) . '/pages/' . $new_name . '.' . $f->extension($file));
        }
      }
    }
    return response()->json(['status' => 1, 'msg' => '操作成功']);
  }

  //设置缩放比例
  public function set_image_size(Request $request)
  {
    $book_id = intval($request->get('book_id'));
    $data['scale'] = $request->get('scale');
    $data['width'] = $request->get('width');
    $data['height'] = $request->get('height');
    $updated = LwwBook::where('id', $book_id)->update($data);
    if ($updated) {
      return response()->json(['status' => 1, 'msg' => '操作成功']);
    }
    return response()->json(['status' => 0, 'msg' => '操作失败']);
//      $files = Storage::files('all_book_pages/'.$book_id.'/pages/');
//      if(intval($request->get('width'))>0){
//        $images_size = [intval($request->get('width')),intval($request->get('height'))];
//      }else{
//        $imgsrc = storage_path('app/public/all_book_pages/'.$book_id.'/pages/1.jpg');
//        $images_size = getimagesize($imgsrc);
//      }
//
//      foreach($files as $file){
//        $newim = imagecreate($images_size[0], $images_size[1]);
//        $imgnow = storage_path('app/public/'.$file);
//        $imgnow_source = imagecreatefromjpeg($imgnow);
//        $imgnow_size = getimagesize($imgnow);
//        imagecopyresampled($newim, $imgnow_source, 0, 0, 0, 0,$images_size[0], $images_size[1],$imgnow_size[0],$imgnow_size[1]);
//        imagejpeg($newim,storage_path('app/public/'.$file));
//      }
//      return response()->json(['status'=>1,'msg'=>'操作成功']);

  }

  //删除页码
  public function del_page(Request $request)
  {
    $this->validate($request, [
      'id' => 'integer|required',
      'book_id' => 'integer|required',
    ]);
    $data['id'] = intval($request->get('id'));
    $data['book_id'] = intval($request->get('book_id'));
    $page_del = LwwBookPage::where(['id' => $data['id'], 'bookid' => $data['book_id']])->delete();
    if ($page_del) {
      return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
    return response()->json(['status' => 0, 'msg' => '删除失败']);
  }


  public function del_page_online(Request $request)
  {
        $book_url = $request->img_path;
        $oss = new OssController();
        $oss->delete($book_url);
        return response()->json(['status' => 1, 'msg' => '删除成功']);
  }

  //识别图片
  public function ocr_page(Request $request)
  {
    ignore_user_abort(true);
    $bookid = $request->get('bookid');
    $chapterid = intval($request->get('chapterid'));
    $pageid = intval($request->get('pageid'));
    $now_timu = LwwBookPageTimupos::where(['bookid' => $bookid, 'chapterid' => $chapterid, 'timu_page' => $pageid])
      ->where('mp3', null)->select('id', 'sort', 'timuid')->orderBy('sort', 'asc')->orderBy('id', 'asc')->get();

    if (empty($now_timu)) {
      return response()->json(['status' => 0, 'msg' => '暂无结果']);
    }
    $all_timu = collect($now_timu)->groupBy('timuid');
    $aipOcr = new AipOcr(config('workbook.APP_ID'), config('workbook.API_KEY'), config('workbook.SECRET_KEY'));
    $data = [];
    $search_result = [];
    foreach ($all_timu as $timu) {
      foreach ($timu as $key => $pic) {
        if (ABookOcrRecord::where(['bookid' => $bookid, 'chapterid' => $chapterid, 'timuid' => $pic->timuid])->count() > 0) {
          continue;
        }
        ABookOcrRecord::updateOrCreate(['bookid' => $bookid, 'chapterid' => $chapterid, 'timuid' => $pic->timuid], ['uid' => $this->now_uid, 'words_result' => '']);
//        $data[$pic->timuid][$key] = $aipOcr->basicGeneral(file_get_contents(storage_path('app/public/all_book_pages/' . $bookid . '/cut_pages/' . $pageid . '/' . $pic->sort . '_' . $pic->id . '.jpg')));
          $data[$pic->timuid][$key] = $aipOcr->basicGeneral(file_get_contents(Storage::url('all_book_pages/' . get_bookid_path($bookid) . '/cut_pages/' . $pageid . '/' . $pic->sort . '_' . $pic->id . '.jpg')));
      }
    }

    if (!empty($data)) {
      foreach ($data as $key => $value) {
        if (!empty($value)) {
          ABookOcrRecord::updateOrCreate(['bookid' => $bookid, 'chapterid' => $chapterid, 'timuid' => $key], ['uid' => $this->now_uid, 'words_result' => json_encode($value, JSON_UNESCAPED_UNICODE)]);
          $words_all = '';
          foreach ($value as $key1 => $value1) {
            if ($value1['words_result_num'] > 0) {
              $words_now = collect($value1['words_result'])->implode('words', ' ');
              $words_all .= $words_now;
              $now_timu = $this->get_search_timu(urlencode($words_now));
              $search_result[$key] = $now_timu;
            }
          }
          LwwBookQuestion::updateOrCreate(['bookid' => $bookid, 'chapterid' => $chapterid, 'timuid' => $key, 'pageid' => $pageid], ['uid' => $this->now_uid, 'question' => $words_all]);
        }
      }

      return response()->json(['status' => 1, 'data' => $data, 'search_result' => $search_result]);
    } else {
      return response()->json(['status' => 0, 'msg' => '暂无结果']);
    }

  }

  public function get_search_timu($word)
  {
    $s = file_get_contents('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word=' . $word);
    return $s;
  }

  public function get_chapter_timu(Request $request)
  {
    $chapter_id = intval($request->get('chapter_id'));
    $chapter_timu = PrePluginWorkbookQuestion::where('chapterid', $chapter_id)->select('id', 'question_type', 'question', 'answer')->get();
    return response()->json(['questions' => $chapter_timu]);
  }

  //点读
  public function diandu_edit(Request $request)
  {
    $act = $request->get('a');
    $R = array();
    switch ($act) {
      case 'editorsave':
        $page_id = intval($request->get('now_page_id'));
        $bookid = $request->get('bookid');
        $chapterid = intval($request->get('chapterid'));
        $now_width = intval($request->get('width'));
        $now_height = intval($request->get('height'));
        $a = explode(',', $request->get('data'));
        $has_id= [];
        foreach ($a as $v) {
          $b = explode(' ', $v);
          if (count($b) == 6) {
            $ret = array();
            $ret['bookid'] = $bookid;
            $ret['pageid'] = $page_id;
            $ret['chapterid'] = $chapterid;
            $ret['mp3'] = 1;
            $ret['pleft'] = round($b[0] / $now_width, 5);
            $ret['ptop'] = round($b[1] / $now_height, 5);
            $ret['pwidth'] = round($b[2] / $now_width, 5);
            $ret['pheight'] = round($b[3] / $now_height, 5);

            $ret['sort'] = intval($b[4]);
            $ret['timu_page'] = intval($b[5]);
            $ret['uid'] = $this->now_uid;
              //bookid   pageid chapterid  sort  timu_page  timuid
              $check['bookid'] = $ret['bookid'];
              $check['pageid'] = $ret['pageid'];
              $check['chapterid'] = $ret['chapterid'];
              $check['sort'] = $ret['sort'];
              $check['timu_page'] = $ret['timu_page'];
              $check['timuid'] = $ret['timuid'];
              $check['mp3'] = 1;
              $has_timupos = LwwBookPageTimupos::where($check)->first();
              if($has_timupos){
                  LwwBookPageTimupos::where(['id'=>$has_timupos->id])->update($ret);
                  $s = $has_timupos;
              }else{
                  $s = LwwBookPageTimupos::create($ret);
              }
              $has_id[] = $s->id;

          }
        }
          LwwBookPageTimupos::where(['pageid' => $page_id, 'chapterid' => $chapterid, 'bookid' => $bookid])->whereNull('mp3')->whereNotIn('id', $has_id)->delete();
        $R['suc'] = 1;
        return response()->json($R);
      case 'editorload':
        $pageid = intval($request->get('pageid'));
        $bookid = $request->get('bookid');
        $chapterid = intval($request->get('chapterid'));
//                $R = LwwBookPageTimupos::from('a_book_page_timupos as t')
//                  ->LeftJoin('a_book_question as q','t.timuid','q.timuid')
//                    ->where('t.bookid',$bookid)
//                    ->where('t.chapterid',$chapterid)
//                    ->where('t.pageid',$pageid)
//                    ->select('t.id','t.timuid','timu_page','sort','pleft','ptop','pwidth','pheight','t.uid', 'q.question_type', 'q.question', 'q.answer','q.answer_normal','q.analysis','q.remark','q.created_at')
//                    ->orderBy('t.timu_page','asc')
//                    ->orderBy('t.sort','asc')
//                    ->get();

        $R['pos'] = LwwBookPageTimupos::where('bookid', $bookid)
          ->where('chapterid', $chapterid)
          ->where('pageid', $pageid)
          ->whereNotNull('mp3')
          ->select('id', 'timuid', 'timu_page', 'sort', 'pleft', 'ptop', 'pwidth', 'pheight', 'uid', 'austart', 'auend', 'mp3','video_id')
          ->orderBy('sort', 'asc')
          ->get();

        exit(json_encode($R, JSON_UNESCAPED_UNICODE));
    }
  }

  public function diandu(Request $request)
  {
    $R = array();
    switch ($request->get('type')) {
      case 'post_voice':
        $id = intval($request->get('id'));
        $new['mp3'] = $request->get('mp3');
        $new['auend'] = $request->get('end_time');
        $new['austart'] = $request->get('start_time');
        if (LwwBookPageTimupos::where(['id' => $id])->update($new)) {
          $R = ['status' => 1, 'msg' => '设置成功'];
        } else {
          $R = ['status' => 0, 'msg' => '设置失败'];
        }
        break;
    }
    exit(json_encode($R, JSON_UNESCAPED_UNICODE));
  }

    //05网a_thread_book关联a_book的id
   public function update_bookid(Request $request){
        $volume=intval($request->volume);
        $bookid=$request->bookid;
        $a_bookid=intval($request->a_bookid);
        $id_update=AThreadBook::where(['id'=>$bookid])->update(['a_book_'.$volume=>$a_bookid]);

           if ($id_update) {
               return response()->json(['status' => 1, 'msg' => '更新成功']);
           }
           return response()->json(['status' => 0, 'msg' => '更新失败']);
   }

    //获取书本图片
    public function get_bookimgs(Request $request){
        $volume=intval($request->volume);
        $bookid=$request->bookid;
        $year = $request->year;
        $oss = new OssController();
        $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => 'all_book_pages/'.get_bookid_array_path($bookid,$year,$volume).'/pages/','max-keys'=>1000]);

        $files = [];
        foreach ($all_img->getObjectList() as $img){
            $img_url = $img->getKey();
            if($img_url!='all_book_pages/'.get_bookid_array_path($bookid,$year,$volume).'/pages/'){
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
        return $file_arr;
    }


    public function get_answer_bookimgs(Request $request)
    {
        $now_bok_id = $request->now_book_id;
        if($now_bok_id){
            $all_pages = PreForumPost::where([['bookid',$now_bok_id],['invisible','>',-1],['position',1]])->select(['tid','pid','message_html','page'])->orderBy('page','asc')->get();
            foreach ($all_pages as $page){
                $page->message_html = preg_replace_callback ('/src="(.*?)"/i',function($matches){
                    if(starts_with($matches[1], 'http://')){
                        return 'src="'.$matches[1].'"';
                    }else{
                        return 'src="http://www.05wang.com/'.$matches[1].'"';
                    }
                },$page->message_html);
            }
            return return_json(['all_pages'=>$all_pages]);
        }else{
            return return_json_err(0,'暂无最新答案页');
        }

    }


    //规则升级课本
    public function upgrade_year(Request $request)
    {
        $upgrade_book_id = $request->upgrade_book_id;
        $new_year = intval(substr($upgrade_book_id,-3,-1))+1;

        $new_book_id = substr_replace($upgrade_book_id,$new_year,-3,-1);

        $this->upgrade_book($upgrade_book_id,$new_book_id);

    }

    public function set_year(Request $request)
    {
        $book_id = $request->book_id;
        $show_version_year = $request->now_show_year;
        $now_volume = intval(substr($show_version_year,-1));
        if(AThreadBook::where(['id'=>$book_id])->update(['a_book_'.$now_volume=>$book_id.$show_version_year])){
            return return_json();
        }
    }

    public function set_jiexi_done(Request $request)
    {
        $book_id = $request->book_id;
        $chapter_id = $request->chapter_id;

        $now_status = AThreadChapter::where(['id'=>$chapter_id])->select('has_jiexi')->first('has_jiexi');

        $real_status = 1;
        if($now_status){
            $real_status =  $now_status->has_jiexi==0?1:0;
        }

        if(AThreadChapter::where(['id'=>$chapter_id])->update(['has_jiexi'=>$real_status])){

            $tid=AThreadChapter::where(['id'=>$chapter_id])->first(['thread_id']);

            //$message=PreForumPost::where(['tid'=>$tid->thread_id,'position'=>1])->first(['message_html']);


            $all_html = LwwBookQuestion::where(['bookid'=>$book_id,'chapterid'=>$chapter_id])->select('analysis')->get();
            if($all_html){
                $all_html = $all_html->pluck('analysis');
                $now_html = implode('<br/>', $all_html->toArray());
            }else{
                $now_html = '';
            }
            $old_html = '';
            $old_html_get = PreForumPost::where(['tid'=>$tid->thread_id,'position'=>1])->first(['message_html']);
            if($old_html_get){
                $old_html = $old_html_get->message_html;
            }
            if(strpos($old_html, $now_html)!==false){
                $final_html = $old_html;
            }else{
                $final_html = $old_html.'<br/>'.$now_html;
            }
            PreForumPost::where(['tid'=>$tid->thread_id,'position'=>1])->update(['message_html'=>$final_html]);

            return return_json();
        }


    }


    public function upgrade_book($old_id,$new_id)
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $has_start = LwwBookPageTimupos::where(['bookid'=>$new_id])->count();
        if($has_start>0){
            return false;
        }

        $all_timupos = LwwBookPageTimupos::where(['bookid'=>$old_id])->select(['*'])->get();
        foreach ($all_timupos as $timu){
            $timu_arr = $timu->toArray();
            $timu_arr['bookid'] = $new_id;
            $timu_arr['timuid'] = $new_id.substr($timu->timuid, strlen($timu->bookid));
            $timu_arr['update_from_id'] = $timu->id;
            $timu_arr['chapterid'] = 0;
            unset($timu_arr['id']);
            $s = LwwBookPageTimupos::create($timu_arr);
            $oss = new OssController();
            try{
                $oss->getOssClient()->copyObject('daanpic', "all_book_pages/".get_bookid_path($old_id)."/cut_pages/{$timu->timu_page}/{$timu->sort}_{$timu->id}.jpg", 'daanpic', "all_book_pages/".get_bookid_path($new_id)."/cut_pages/{$timu->timu_page}/{$timu->sort}_{$s->id}.jpg");
            }catch (\Exception $e){
                var_dump('not move');
            }

        }

        $all_question = LwwBookQuestion::where('bookid',$old_id)->select(['*'])->get();
        foreach ($all_question as $question){
            $question_arr = $question->toArray();
            $question_arr['bookid'] = $new_id;
            $question_arr['timuid'] = $new_id.substr($question->timuid, strlen($question->bookid));
            $question_arr['update_from_id'] = $question->id;
            $question_arr['chapterid'] = 0;
            unset($question_arr['id']);
            LwwBookQuestion::create($question_arr);

        }
        $this->move_tree($old_id,$new_id,'all_book_pages/'.get_bookid_path($old_id).'/');

    }


    private function move_tree($old_key,$new_key,$start_path)
    {
        $oss = new OssController();

        $all_img = $oss->getOssClient()->listObjects('daanpic', ['delimiter' => '/', 'prefix' =>$start_path, 'max-keys' => 1000]);
        //var_dump($all_img);die;
        foreach ($all_img->getObjectList() as $imgs) {
            if($imgs->getKey()==$start_path) continue;
            if ($imgs->getSize() > 0) {
                $img_key = $imgs->getKey();
                $img_new_key = str_replace('/' . $old_key . '/', '/' . $new_key . '/', $img_key);
                $oss->getOssClient()->copyObject('daanpic', $imgs->getKey(), 'daanpic', $img_new_key);
            }
        }

        foreach ($all_img->getPrefixList() as $img_path){
            if($img_path->getPrefix()=='all_book_pages/'.$old_key.'/cut_pages/') continue;
            $this->move_tree($old_key, $new_key, $img_path->getPrefix());
        }
    }




//    //05网a_thread_book关联a_book的id
//   public function update_bookid(Request $request){
//        $volume=intval($request->volume);
//        $bookid=intval($request->bookid);
//        $a_bookid=intval($request->a_bookid);
//        $id_update=AThreadBook::where(['id'=>$bookid])->update(['a_book_'.$volume=>$a_bookid]);
//
//           if ($id_update) {
//               return response()->json(['status' => 1, 'msg' => '更新成功']);
//           }
//           return response()->json(['status' => 0, 'msg' => '更新失败']);
//   }
//
//    //获取书本图片
//    public function get_bookimgs(Request $request){
//        $volume=intval($request->volume);
//        $bookid=intval($request->bookid);
//        $str='a_book_'.$volume;
//        $re=AThreadBook::where(['id'=>$bookid])->select($str)->first();
//        if(empty($re->$str)) return response()->json(['status' => 0, 'msg' => '未找到对应目录']);
//        $oss = new OssController();
//        $all_img = $oss->getOssClient()->listObjects('daanpic',['delimiter' => '/', 'prefix' => 'all_book_pages/'.$re->$str.'/pages/','max-keys'=>1000]);
//        #dd($all_img->getObjectList());
//        $files = [];
//        foreach ($all_img->getObjectList() as $img){
//            $img_url = $img->getKey();
//            if($img_url!='all_book_pages/'.$re->$str.'/pages/'){
//                $files[] = $img_url;
//            }
//        }
//
//        $file_arr = [];
//        $f = new Filesystem();
//        foreach ($files as $key=>$file){
//            if($f->extension($file)=='jpg') {
//                $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));
//                $file_arr[intval($now_file)] = $file;
//            }
//        }
//        ksort($file_arr);
//        return $file_arr;
//    }
}