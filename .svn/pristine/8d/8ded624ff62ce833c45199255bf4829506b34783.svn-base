<?php

namespace App\Http\Controllers\NewBuy;

use App\AWorkbookNew;
use App\LocalModel\NewBuy\New1010;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewGoodsTrue;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\NewBuy\NewSort;
use App\LocalModel\NewBuy\NewSortSearchName;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookListController extends Controller
{

    public function index($sort=-1,$volumes_id=0,$word=''){
        $data['sort'] = intval($sort);
        $data['volumes_id'] = intval($volumes_id);
        $data['word'] = $word;


        $data['list']=NewOnly::where(function ($query) use($sort,$volumes_id,$word){
                $query->where('id','>',0);
                $sort = intval($sort);
                $volumes_id = intval($volumes_id);
                if($sort>=0){
                    $query->where('sort',$sort);
                }
                if($volumes_id>0){
                    $query->where('volumes_id',$volumes_id);
                }
                if($word){
                    $query->where('newname','like','%'.$word.'%');
                }
            })
            ->select('id','newname','collect2015','collect2016','collect2017','collect2018','isbn')
            ->paginate(20);

        foreach($data['list'] as $k=>$v){
            $re=NewBoughtRecord::where(["only_id"=>$v->id])->select('status','answer_status')->first();
            if(!$re){
                $data['list'][$k]['status']=-1;
                $data['list'][$k]['answer_status']=-1;
            }else{
                $data['list'][$k]['status']=$re->status;
                $data['list'][$k]['answer_status']=$re->answer_status;
            }
            if(empty($v->isbn)){
                $data['list'][$k]['searchnum']=0;
            }else{
                $re=DB::connection('mysql_local')->table('a_tongji_search_isbn_temp2')->where(['isbn'=>$v->isbn])->select('searchnum')->first();
                if($re){
                    $data['list'][$k]['searchnum']=$re->searchnum;
                }
            }
        }

        return view('new_buy.book_list',compact('data'));
    }





}
