<?php

namespace App\OnlineModel;

use App\MyModel\ATongjiCorrect;
use App\MyModel\ATongjiOneday;
use App\MyModel\ATongjiStayArea;
use Illuminate\Database\Eloquent\Model;

class AWorkbook1010 extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_workbook_1010';
    public $timestamps = false;
    protected $guarded = array();


    public function hasOnly()
    {
        return $this->hasOne('App\OnlineModel\AOnlyBook','onlyid','onlyid');
    }

    public function hasAnswers()
    {
        return $this->hasMany('App\OnlineModel\AWorkbookAnswer','bookid','id')->orderBy('text','asc');
    }

    public function hasSort()
    {
        return $this->hasOne('App\OnlineModel\Sort','id','sort');
    }

    public function hasHot()
    {
        return $this->hasOne('App\OnlineModel\ATongjiHotbook29','isbn','isbn');
    }

    //书本热门管理
    public function getHotBook($where=[],$uptime=0,$downtime=0)
    {
        if($uptime==0){
            $uptime=date('Y-m-d',time());
        }
        if($downtime==0){
            $downtime=date('Y-m-d',time());
        }
        $info=\App\OnlineModel\AWorkbook1010::from('a_workbook_1010')
            ->where($where)
            ->select('id','bookname','onlyid','isbn')
//            ->orderBy('sum_stay','desc')
            ->paginate(30);
//        dd($info);
        $info=$this->getsum($info,$uptime,$downtime);//数据小计
//        dd($info);
        return $info;
    }

    //数据小计
    public function getsum($info,$uptime,$downtime)
    {
//        dd($info);
        foreach($info as $k=>$v){
            $a_tongji_oneday=new ATongjiOneday();
            $data=$a_tongji_oneday::where('date','>=',$uptime)
                ->where('date','<=',$downtime)
                ->where('isbn',$v->isbn)
                ->get();
            $collect_count=0;$searchnum=0;$sharenum=0;$bad_evaluate=0;$good_evaluate=0;
            foreach($data as $a=>$b){
                $collect_count+=$b->collect_count;
                $searchnum+=$b->searchnum;
                $sharenum+=$b->sharenum;
                $bad_evaluate+=$b->bad_evaluate;
                $good_evaluate+=$b->good_evaluate;

            }
            $v->sum_collect_count=$collect_count;
            $v->sum_searchnum=$searchnum;
            $v->sum_sharenum=$sharenum;
            $v->sum_bad_evaluate=$bad_evaluate;
            $v->sum_good_evaluate=$good_evaluate;

            //小计停留人数
            $stay=0;
            $a_tongji_stay_area=new ATongjiStayArea();
            $index=$a_tongji_stay_area::where('date','>=',$uptime)
                ->where('date','<=',$downtime)
                ->where('isbn',$v->isbn)
                ->get();
            foreach($index as $c=>$d){
                $stay+=$d->num;
            }
            $v->sum_stay=$stay;

            //小计纠错
            $correct=0;
            $a_tongji_correct=new ATongjiCorrect();
            $infos=$a_tongji_correct::where('date','>=',$uptime)
                ->where('date','<=',$downtime)
                ->where('isbn',$v->isbn)
                ->count();
            $v->sum_correct=$infos;
            //即时数据入库
            $model=AWorkbook1010::where('id',$v->id)->update([
                'sum_collect_count'=>$collect_count,
                'sum_searchnum'=>$searchnum,
                'sum_sharenum'=>$sharenum,
                'sum_bad_evaluate'=>$bad_evaluate,
                'sum_good_evaluate'=>$good_evaluate,
                'sum_stay'=>$stay,
                'sum_correct'=>$infos
            ]);
        }
        return $info;
    }
}
