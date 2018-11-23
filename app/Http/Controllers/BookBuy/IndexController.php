<?php

namespace App\Http\Controllers\BookBuy;

use App\BookNeedBuy;
use App\BookNewAdd;
use App\BookToBuy;
use App\BookVersionType;
use App\HdBook;
use App\HdUserBook;
use App\Workbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    protected $status;
    protected $now_uid;
    public function __construct()
    {
      $this->middleware(function($request,$next){
        $this->now_uid = Auth::id();
        return $next($request);
      });
      $this->status = [0=>'准备购买',1=>'等待收货',2=>'购买完成',3=>'需要退换'];
    }

    protected function get_publish($isbn){
      $version_first = substr($isbn, 4, 1);
      if ($version_first == 0) {
        $book_version = substr($isbn, 4, 2);
      } else if ($version_first == 1 || $version_first == 2 || $version_first == 3) {
        $book_version = substr($isbn, 4, 3);
      } else if ($version_first == 5) {
        $book_version = substr($isbn, 4, 4);
      } else if ($version_first == 8) {
        $book_version = substr($isbn, 4, 5);
      }else if($version_first == 9){
        $book_version = substr($isbn, 4, 6);
      }else{
        $book_version = '999999';
      }
      return $book_version;
    }


  public function index()
    {
      $data['status'] = $this->status;
      $data['all'] = Workbook::from('a_workbook_1010 as x')->join('book_version_type', 'x.version_id', 'book_version_type.id')
        ->join('sort', 'x.sort', 'sort.id')
      ->select('x.id','x.version_year','x.bookname','x.grade_id','x.subject_id','x.volumes_id','x.version_id','x.sort','x.addtime','book_version_type.name as version_name', 'sort.name as sort_name')->orderBy('addtime','desc')->paginate(20);
      $data['version'] = json_encode(BookVersionType::all('id','name'),JSON_UNESCAPED_UNICODE);
      return view('book_buy.index',compact('data'));
    }

    public function wait()
    {
      $data['status'] = $this->status;
      $data['all_books'] = BookNeedBuy::where('status',0)->select('book_id','isbn','version_id','cover_photo_thumbnail','book_name','grade_id','subject_id','volume_id','uid','id','created_at')->take(100)->orderBy('isbn','asc')->get();


//      foreach ($data['all_books'] as $book) {
//        if ($book->book_id > 1000000) {
//          $id_now = $book->book_id-1000000;
//          $data_now = HdUserBook::where('id', $id_now)->first();
//          $new['book_name'] = $data_now['book_name'];
//          $new['grade_id'] = $data_now['grade_id'];
//          $new['subject_id'] = $data_now['subject_id'];
//          $new['volume_id'] = $data_now['volumes'];
//          $new['version_id'] = $data_now['book_version_id'];
//          $new['isbn'] = $data_now['bar_code'];
//          $new['cover_photo_thumbnail'] = $data_now['cover_photo'];
//          BookNeedBuy::where('book_id', $book->book_id)->update($new);
//        } else {
//          $data_now = HdBook::where('id', $book->book_id)->first();
//          $new['book_name'] = $data_now['name'];
//          $new['grade_id'] = $data_now['grade_id'];
//          $new['subject_id'] = $data_now['subject_id'];
//          $new['volume_id'] = $data_now['volumes'];
//          $new['version_id'] = $data_now['book_version_id'];
//          $new['isbn'] = $data_now['bar_code'];
//          $new['cover_photo_thumbnail'] = $data_now['cover_photo'];
//          BookNeedBuy::where('book_id', $book->book_id)->update($new);
//
//        }
//      }

//      foreach ($all_wait_book as $key=> $book){
//        if($book->book_id>1000000){
//          $now_id = $book->book_id-1000000;
//          $all_wait_book[$key]['book_info'] = HdUserBook::where('id',$now_id)->select('bar_code','version','cover_photo_thumb as cover_photo_thumbnail','book_name','grade_id','subject_id','volumes')->first();
//        }else{
//          $all_wait_book[$key]['book_info'] = HdBook::where('id',$book->book_id)->select('bar_code','version','cover_photo_thumbnail','name as book_name','grade_id','subject_id','volumes')->first();
//        }
//      }
//      dd($all_wait_book);
//
//      $data['all_books'] = $all_wait_book;
//      foreach ($data['all_books'] as $value){
//        if(!$value['book_info']){
//          dd($value);
//        }
//      }




      foreach ($data['all_books'] as $key=>$value){
          $data['all_bar_code'][] = $value->isbn;
      }

//      $data['all_books'] = BookNeedBuy::from('xx_book_need_buy as b')
//          ->join('a_workbook_1010 as x','b.book_id','x.id')
//        ->where('b.status',0)
//        ->select('b.book_id','x.isbn','x.version_year','x.cover_photo_thumbnail','x.bookname','x.grade_id','x.subject_id','x.volumes_id','b.created_at')->paginate(100);

//      foreach ($data['all_books'] as $key => $value){
//          $data['all_bar_code'][] = $value->isbn;
//      }
        if(!isset($data['all_bar_code'])){
          die('暂无待购买练习册');
        }
        $store = BookToBuy::whereIn('bar_code',$data['all_bar_code'])->select('shop_id','bar_code','shop_name','img','url','price')->orderBy('price','asc')->get();
        $store = collect($store)->groupBy('shop_id');


        foreach ($store as $shopid=>$books){
          $data['all_shops'][$shopid] = collect($books)->groupBy('bar_code');
        }

      $data['all_shops'] = collect($data['all_shops'])->sortByDesc(function ($sort){
        return count($sort);
      });



      return view('book_buy.wait',compact('data'));
    }

    public function detail($id)
    {
      //练习册基本信息
      //练习册相同isbn数量  练习册求助数量   练习册同系列情况    练习册添加时间   练习册处理人   练习册答案情况

      $data['book'] = BookNeedBuy::from('xx_book_need_buy as x')->join('book_version_type', 'x.version', 'book_version_type.id')
        ->join('users','x.uid','users.id')
        ->join('sort', 'x.sort', 'sort.id')
        ->where('x.id',$id)->select('x.version_year','x.isbn','x.name','x.grade','x.subject','x.volume','x.version','x.sort','x.uid','x.created_at','x.from','x.status','users.name as username','book_version_type.name as version_name', 'sort.name as sort_name')->first();
      $data['store'] = BookToBuy::where('bar_code',$data['book']->isbn)->select()->orderBy('price','asc')->get();
      $data['store'] = collect($data['store'])->groupBy('shop_id')->sortByDesc('price');
      if($data['book']->grade<10){
        $grade_now = '0'.$data['book']->grade;
      }else{
        $grade_now = $data['book']->grade;
      }
      if($data['book']->subject<10){
        $subject_now = '0'.$data['book']->subject;
      }else{
        $subject_now = $data['book']->subject;
      }
      if($data['book']->volume<10){
        $volume_now = '0'.$data['book']->volume;
      }else{
        $volume_now = $data['book']->volume;
      }
      if($data['book']->version<10){
        $version_now = '0'.$data['book']->version;
      }else{
        $version_now = $data['book']->version;
      }
      $data['sort'] = Workbook::where(['sort'=>$data['book']->sort,'grade_id'=>$grade_now,'subject_id'=>$subject_now,'volumes_id'=>$volume_now,'version_id'=>$version_now])->select()->orderBy('version_year','asc')->get();

      return view('book_buy.detail',compact('data'));
    }


  public function done()
  {
    $data['status'] = $this->status;
    $data['all'] = BookNeedBuy::from('xx_book_need_buy as b')
      ->join('users as u','b.uid','u.id')
      ->join('book_version_type', 'b.version_id', 'book_version_type.id')
      ->where('b.status','>',0)
      ->select('b.book_id','b.status','b.book_name','b.grade_id','b.subject_id','b.volume_id','b.version_id','u.name','book_version_type.name as version_name')->paginate(20);


    $data['new'] = BookNewAdd::from('xx_book_new_add as b')
      ->join('users as u','b.uid','u.id')
      ->join('book_version_type', 'b.version_id', 'book_version_type.id')
      ->join('sort', 'b.sort_id', 'sort.id')

      ->select('b.book_id','b.id','b.status','b.version_year','b.name','b.grade_id','b.subject_id','b.volume_id','b.version_id','u.name as username','b.created_at','book_version_type.name as version_name', 'sort.name as sort_name')
      ->orderBy('created_at','desc')
      ->paginate(20);

    $data['version'] = BookVersionType::all('id','name');
    $data['username'] = Auth::user()->name;


    return view('book_buy.done',compact('data'));
  }

}
