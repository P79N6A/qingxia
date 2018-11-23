<?php

namespace App\Http\Controllers\NewBuy;

use App\LocalModel\NewBuy\NewBoughtRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalyzeRecordController extends Controller
{
    public function analyze_list($sort=-1,$only_id=-1,$subject_id=-1,$order='')
    {

        if($order=='collect'){
            $orderRaw = '(o.collect2018+o.collect2017+o.collect2016+o.collect2015+o.collect2014)  desc';
        }else{
            $orderRaw = 'a.sort asc,a.grade_id asc,a.subject_id asc,a.status asc,a.updated_at asc';
        }

        $data['buy_status_id'] = [0=>['text'=>'待购买','color'=>'bg-red disabled'],1=>['text'=>'已匹配','color'=>'bg-yellow disabled'],2=>['text'=>'已有','color'=>'bg-teal disabled'],3=>['text'=>'退货','color'=>'bg-yellow-active'],4=>['text'=>'已录','color'=>'bg-purple disabled'],5=>['text'=>'已上','color'=>'bg-green-active'],6=>['text'=>'已买','color'=>'bg-blue-active']];

        $where[] = ['a.id','>',0];
        if($sort!=-1){
            $where[] = ['a.sort',$sort];
        }
        if($only_id!=-1){
            $where[] = ['a.only_id',$only_id];
        }
        if($subject_id!=-1){
            $where[] = ['a.subject_id',$subject_id];
        }
        $data['sort'] = $sort;
        $data['only_id'] = $only_id;
        $data['subject_id'] = $subject_id;


        $data['all_record'] = NewBoughtRecord::from('a_book_bought_record as a')->leftJoin('a_workbook_only as o','a.only_id','o.id')->where($where)->where('answer_status','>',1)->where(function ($query){
            $query->where(['a.volumes_id'=>cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id])->orWhere(['a.volumes_id'=>3]);
        })->where(['a.version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->select('a.only_id','a.sort','shop_id','goods_id','goods_price','goods_fee','goods_according_to','a.created_at','bought_at','a.status','uid','book_page','answer_page','answer_status','analyze_status','analyze_start_at','analyze_end_at','analyze_uid')->with('hasOnlyDetail:id,newname,collect2018,collect2017,collect2016,collect2015,collect2014,hd2014,hd2015,hd2016')->orderByRaw($orderRaw)->paginate(20);

        return view('new_buy.analyze_list',compact('data'));
    }
}
