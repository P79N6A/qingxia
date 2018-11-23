<?php

namespace App\Http\Controllers\ManageNew;

use App\AnswerModel\AWorkbook1010Cip;
use App\AWorkbookMain;
use App\AWorkbookNew;
use App\Sort;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManageIsbnController extends Controller
{
    public function index($type='pending')
    {
        $uid = \Auth::id();
        $number = -1;
        if($uid===5){
            $number = 0;
        }elseif($uid===8){
            $number = 1;
        }elseif($uid===11){
            $number = 2;
        }elseif($uid===14){
            $number = 3;
        }elseif($uid===17){
            $number = 4;
        }else{
            $number = -1;
        }
        $whereRaw = "id>0";
        if ($number != -1) {
            //$whereRaw = "id%5=$number";
        }


        if($type==='done')
        {
            $all_isbn = AWorkbookMain::where([['hdid','>=','170000'],['isbn','=',''],])->whereRaw($whereRaw)->select(['id','bookname','grade_id','subject_id','volumes_id','version_id','cover','cip_photo','isbn','sort'])->orderBy('id','desc')->paginate(20);
            foreach ($all_isbn as $isbn){
                $isbn->isbn = substr_replace($isbn->isbn, '-', 3, 0);
                if($isbn->isbn[5]<=3){
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 5, 0);
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 9, 0);
                }
                if($isbn->isbn[5]>3 and $isbn->isbn[6]<=5){
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 5, 0);
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 10, 0);
                }
                if($isbn->isbn[5]==8){
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 5, 0);
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 11, 0);
                }
                if($isbn->isbn[5]==9){
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 5, 0);
                    $isbn->isbn = substr_replace($isbn->isbn, '-', 12, 0);
                }
                $isbn->isbn = substr_replace($isbn->isbn, '-', -1, 0);
            }
        }else{
            //SELECT id,bookname,cover,cover_photo FROM `a_workbook_1010` where hdid>170000 and isbn='' and cip_photo is null and bookname not like '%寒假%'
            $all_isbn = AWorkbookMain::where([['hdid','>=','170000'],])->whereRaw($whereRaw)->select(['id','bookname','grade_id','subject_id','volumes_id','version_id','cover','cip_photo','isbn','sort'])->orderBy('id','desc')->paginate(20);
        }


        foreach ($all_isbn as $key => $isbn)
        {
            if($isbn->sort>0){
                $data['grade_id']= $isbn->grade_id;
                $data['subject_id']= $isbn->subject_id;
                $data['volumes_id']= $isbn->volumes_id;
                $data['version_id']= $isbn->version_id;
                $all_isbn[$key]['other_isbn'] = AWorkbookMain::where($data)->select('id','bookname','isbn')->get();
            }else{
                $now_all_name = cache('all_version_now')->pluck('name');
                foreach (['2018年','2017年','上册','下册','全一册','人教PEP版','人教pep版'] as $value){
                    $now_all_name = $now_all_name->push($value);
                }

//              dd(cache('all_version_now')->pluck('name')->merege(['2018年','2017年','数学','语文','英语','物理','化学','历史','地理','生物','政治','科学','综合','语文','数学','英语','物理','化学','地理','历史','政治','生物','科学','一年级','二年级','三年级','四年级','五年级','六年级','七年级','八年级','九年级','高一','高二','高三','高中必修','高中选修','上册','下册','全一册',])->all());
               $isbn->new_bookname =str_replace($now_all_name->all(), '', $isbn->bookname);
                foreach (['一年级','二年级','三年级','四年级','五年级','六年级','七年级','八年级','九年级','高一','高二','高三','高中必修','高中选修','数学','语文','英语','物理','化学','历史','地理','生物','政治','科学','综合',] as $grade){
                    if(strpos($isbn->new_bookname, $grade)>0){
                        $isbn->new_bookname = substr($isbn->new_bookname,0, strpos($isbn->new_bookname, $grade));
                    }
                }

            }
        }
        $all_distinct_name = $all_isbn->pluck('new_bookname')->unique();

        $all_like_book = [];
        foreach ($all_distinct_name as $key=> $value){
            $all_like_book[$key]['sorts'] = \App\AWorkbook1010::where([['sort','>',0],['bookname','like','%'.$value.'%']])->with('has_sort:id,name')->select(DB::raw('distinct sort as sort'))->get();
            $all_like_book[$key]['name'] = $value;
        }


        return view('manage_new.isbn_new',['data'=>$all_isbn,'type'=>$type,'all_like_book'=>$all_like_book]);
    }

    public function api(Request $request,$type)
    {
        $book_id = $request->book_id;
        if($type==='modify_isbn'){
            $isbn = str_replace('-', '', $request->isbn);
            if(!check_isbn($isbn)){
                return response()->json(['status'=>0]);
            }
            $sort = intval($request->sort_id);
            if(AWorkbookMain::where(['id'=>$book_id])->update(['isbn'=>$isbn,'sort'=>$sort])){
                return response()->json(['status'=>1]);
            }
        }
        else if($type==='get_isbn'){
            $sort_id = $request->sort_id;
            $isbn = AWorkbookMain::find($book_id);
            $data['grade_id']= $isbn->grade_id;
            $data['subject_id']= $isbn->subject_id;
            $data['volumes_id']= $isbn->volumes_id;
            $data['version_id']= $isbn->version_id;
            $data['sort'] = $sort_id;
            $all_isbn = AWorkbookMain::where($data)->select('id','bookname','isbn')->get();
            return response()->json(['status'=>1,'data'=>$all_isbn]);
        }
        else if($type==='save_isbn'){
            $isbn = str_replace('-', '', $request->isbn);
            if(!check_isbn($isbn)){
                return response()->json(['status'=>0]);
            }
            if(AWorkbook1010Cip::where(['id'=>$book_id])->update(['isbn'=>$isbn])){
                return response()->json(['status'=>1]);
            }
        }else if($type==='save_new_isbn'){
            $isbn = str_replace('-', '', $request->isbn);
            if(!check_isbn($isbn)){
                return response()->json(['status'=>0]);
            }
            if(AWorkbookNew::where(['id'=>$book_id])->update(['isbn'=>$isbn])){
                return response()->json(['status'=>1]);
            }
        }
        return response()->json(['status'=>0]);
    }
}
