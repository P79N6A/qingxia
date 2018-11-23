<?php

namespace App\Http\Controllers\NewBuy;

use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewBoughtReturn;
use App\LocalModel\NewBuy\NewOnly;
use App\Sort;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoughtRecordController extends Controller
{
    public function bought_list($sort=-1,$only_id=-1,$subject_id=-1,$grade_id=-1,$version_id=-1,$status=-1,$start='2011-1-1',$end='2011-1-1')
    {
        $data['buy_status_id'] = [0=>['text'=>'待购买','color'=>'bg-red disabled'],1=>['text'=>'已匹配','color'=>'bg-yellow disabled'],2=>['text'=>'已有','color'=>'bg-teal disabled'],3=>['text'=>'退货','color'=>'bg-yellow-active'],4=>['text'=>'已录','color'=>'bg-purple disabled'],5=>['text'=>'已上','color'=>'bg-green-active'],6=>['text'=>'已买','color'=>'bg-blue-active']];

        $where[] = ['a_book_bought_record.id','>',0];
        if($sort>-1){
            $where[] = ['a_book_bought_record.sort',$sort];
        }
        if($only_id!=-1){
            $where[] = ['a_book_bought_record.only_id',$only_id];
        }
        if($subject_id!=-1){
            $where[] = ['o.subject_id',$subject_id];
        }
        if($grade_id!=-1){
            $where[] = ['o.grade_id',$grade_id];
        }
        if($version_id!=-1){
            $where[] = ['o.version_id',$version_id];
        }
        if($status!=-1){
            $where[] = ['a_book_bought_record.status',$status];
        }

        if($start!='2011-1-1'){
           $where[]=['bought_at','>=',$start];
        }
        if($end!='2011-1-1'){
            $where[]=['bought_at','<',$end];
        }


        $data['sort'] = $sort;
        $data['only_id'] = $only_id;
        $data['subject_id'] = $subject_id;
        $data['grade_id'] = $grade_id;
        $data['version_id'] = $version_id;
        $data['status'] = $status;
        $data['start']=$start;
        $data['end']=$end;
        if($data['sort']!=-1){
            $data['all_version'] = NewOnly::where(['sort'=>$sort])->select(DB::raw('distinct version_id as version_id'))->with('hasVersion:id,name')->get();
        }else{
            $data['all_version'] = [];
        }

        $data['all_record'] = NewBoughtRecord::from('a_book_bought_record')->leftJoin('a_workbook_only as o','a_book_bought_record.only_id','o.id')->where($where)->where(function ($query){
            $query->where(['o.volumes_id'=>cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id])->orWhere(['o.volumes_id'=>3]);
        })->where(['a_book_bought_record.version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->select('only_id','o.sort','o.isbn','a_book_bought_record.shop_id','a_book_bought_record.goods_id','a_book_bought_record.goods_price','a_book_bought_record.goods_fee','a_book_bought_record.goods_according_to','a_book_bought_record.created_at','a_book_bought_record.bought_at','a_book_bought_record.arrived_at','a_book_bought_record.isbn','a_book_bought_record.status','a_book_bought_record.uid','a_book_bought_record.bought_uid','a_book_bought_record.book_page','a_book_bought_record.answer_page','a_book_bought_record.answer_status','a_book_bought_record.analyze_status','a_book_bought_record.analyze_start_at','a_book_bought_record.analyze_end_at','a_book_bought_record.analyze_uid')->with('hasOnlyDetail:id,newname')->with(['hasGoodsDetail'=>function($query){$query->where('detail_url','>',0)->select('id','nick','shopLink','title','pic_url','detail_url');}])->with('hasSort:id,name')->with('hasReturn:id,only_id,sort')->with('hasNewBook:id,from_only_id')->withCount(['hasFound'=>function($query){
            $query->where('status','>=',0);
        }])->orderBy('o.sort','asc')->orderBy('o.grade_id','asc')->orderBy('o.subject_id','asc')->orderBy('a_book_bought_record.status','asc')->orderBy('a_book_bought_record.updated_at','asc')->paginate(20);

        foreach($data['all_record'] as $k=>$v){
            if(empty($v->isbn)){
                $data['all_record'][$k]['searchnum']=0;
            }else{
                $re=DB::connection('mysql_local')->table('a_tongji_search_isbn_temp2')->where(['isbn'=>$v->isbn])->select('searchnum')->first();
                if($re){
                    $data['all_record'][$k]['searchnum']=$re->searchnum;
                }
            }
        }
        //dd($data);
        return view('new_buy.bought_list',compact('data'));
    }

    public function return_list($sort=-1,$only_id=-1)
    {
        $where[] = ['id','>',0];
        if($sort!=-1){
            $where[] = ['sort',$sort];
        }
        if($only_id!=-1){
            $where[] = ['only_id',$only_id];
        }
        $data['all_record'] = NewBoughtReturn::where(['volumes_id'=>cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where($where)->select('only_id','sort','shop_id','goods_id','goods_price','goods_fee','goods_according_to','created_at','bought_at','uid','returned_at')->with('hasOnlyDetail:id,newname')->with('hasGoodsDetail:id,nick,shopLink,title,pic_url,detail_url')->orderBy('grade_id','asc')->orderBy('subject_id','asc')->orderBy('updated_at','asc')->paginate(20);

        return view('new_buy.return_list',compact('data'));
    }
}
