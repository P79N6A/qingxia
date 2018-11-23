<?php

namespace App\Http\Controllers\ManageNew;

use App\ATongjiSearchIsbnNew;
use App\OnlineModel\AWorkbook1010;
use App\LocalModel\ATongjiSearchIsbn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class OtherThingController extends Controller
{
    public function temp_isbn($sort=-1)
    {

//        if(!in_array($uid, [19,20,21,22,23,27,28])){
//            $uid = \Auth::id();
//        }
//
//        $number = -1;


            #select distinct sort,count(sort) from a_tongji_search_isbn_hot_copy group by sort
//        $a = ATongjiSearchIsbnNew::groupBy('sort')->select(\DB::raw('distinct sort as sort'))->get();
//        $all_num = $a->split(7);



//        if($uid==2){
//            $now_sort = $a->pluck('sort');
//        }elseif($uid==5){
//            $now_sort = $all_num[0]->pluck('sort');
//        }elseif($uid==29){
//            $now_sort = $all_num[0]->pluck('sort');
//        }elseif($uid==19){
//            $now_sort = $all_num[0]->pluck('sort');
//        }elseif($uid==20){
//            $now_sort = $all_num[1]->pluck('sort');
//        }elseif($uid==21){
//            $now_sort = $all_num[2]->pluck('sort');
//        }elseif($uid==22){
//            $now_sort = $all_num[3]->pluck('sort');
//        }elseif($uid==23){
//            $now_sort = $all_num[4]->pluck('sort');
//        }elseif($uid==28){
//            $now_sort = $all_num[5]->pluck('sort');
//        }elseif($uid==27){
//            $now_sort = $all_num[6]->pluck('sort');
//        }
//        else{
//            $now_sort = -1;
//        }
//        $whereRaw = "1=1";
//        if ($number != -1) {
//            $whereRaw = "id%5=$number";
//        }

        //whereRaw($whereRaw)->
        //->whereIn('n.sort',$now_sort)
//        $data['all_isbn'] = ATongjiSearchIsbnNew::from('a_tongji_search_isbn_hot_copy as n')->LeftJoin('a_tongji_sort as s','n.sort','s.sort')->whereIn('n.sort',$now_sort])->where([['n.result','!=','2018'],['n.taobao','=',1]])->select('n.id','n.isbn','n.sort','n.searchnum','n.taobao','n.found','n.other_found','n.days','s.searchrate')->orderBy('n.searchrate','desc')->orderBy('n.searchnum','desc')->with('has_isbn_detail:id,isbn,bookname,print_description')->with('has_need_book:id,isbn,cover_img,sort_name')->paginate(20);
        $data['sort'] = $sort;
        $data['all_isbn'] = ATongjiSearchIsbnNew::from('a_tongji_search_isbn_hot_copy as n')->LeftJoin('a_tongji_sort as s','n.sort','s.sort')->where(function ($query) use ($sort){
            if($sort>-1){
                return $query->where('n.sort',$sort);
            }else{
                return $query->where('n.id','>',0);
            }
        })->where([['n.result','!=','2018'],['n.sort','>=',0],['last_found',0],['done',0],['grade_id','>=',3],['grade_id','<=',9],['description','not like','%下册%']])->select('n.id','n.isbn','n.sort','n.searchnum','n.taobao','n.found','n.other_found','n.days','s.searchrate','n.description')->orderBy('n.searchnum','desc')->with('has_need_book:id,isbn,cover_img,sort_name')->paginate(20);

        return view('manage_new.other.temp_isbn',compact('data'));
    }

    public function found_about(Request $request,$type='found')
    {
        if($type==='found'){
            $now_field = 'found';
        }else{
            $now_field = 'other_found';
        }

        $found = 0;
        $isbn = $request->isbn;

        $now_found = ATongjiSearchIsbnNew::where(['isbn'=>$isbn])->select($now_field)->first();
        if($type==='found'){
            if($now_found->found==0){
                $found=1;
            }else{
                $found=0;
            }
        }else{
            if($now_found->other_found==0){
                $found=1;
            }else{
                $found=0;
            }
        }
        if(ATongjiSearchIsbnNew::where(['isbn'=>$isbn])->update([$now_field=>$found])){
            return return_json();
        }else{
            return return_json_err();
        }
    }

    public function save_online_isbn(Request $request)
    {
        $now_id = $request->book_id;
        $isbn = str_replace('-', '', $request->isbn);
        $now_isbn = AWorkbook1010::where('id',$now_id)->first(['isbn']);

        if(strpos($now_isbn->isbn, $isbn)===false){
            $final_isbn = $now_isbn->isbn.'|'.$isbn;
            if(AWorkbook1010::where('id',$now_id)->update(['isbn'=>$final_isbn])){
                return return_json();
            }
        }else{
            return return_json_err(0,'已有该isbn');
        }
        return return_json_err(0,'新增失败');
    }
}
