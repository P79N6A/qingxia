<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/12
 * Time: 上午9:32
 */

namespace App\Http\Controllers\Chart;


use App\Http\Controllers\Controller;
use App\PreCommonMember;

class RegChartController extends Controller
{
    public function index($start='',$end=''){
        $start = (checkDateFormat($start))?strtotime($start):strtotime(date("Y-m-d",strtotime("-29 day")));
        $end = (checkDateFormat($end))?strtotime($end):time();
        $datas =PreCommonMember::select([\DB::raw("FROM_UNIXTIME(regdate,'%Y-%m-%d') as rgDate"),\DB::raw("count(uid) as scount ")])
            ->where([['regdate','>=',$start],['regdate','<=',$end]])
            ->groupBy("rgDate")
            ->orderBy("rgDate","desc")
            ->get();
        return view("chart.tongji_reg.index",['datas'=>$datas,'start'=>date("Y/m/d",$start),'end'=>date("Y/m/d",$end)]);
    }

    public function ajax($start='',$end=''){
        $start = (checkDateFormat($start))?strtotime($start):strtotime(date("Y-m-d",strtotime("-29 day")));
        $end = (checkDateFormat($end))?strtotime($end):time();
        $datas =PreCommonMember::select([\DB::raw("FROM_UNIXTIME(regdate,'%Y-%m-%d') as rgDate"),\DB::raw("count(uid) as scount ")])
            ->where([['regdate','>=',$start],['regdate','<=',$end]])
            ->groupBy("rgDate")
            ->orderBy("rgDate","desc")
            ->get();
        if(count($datas)<=0){
            exit("<h3><i class=\"fa fa-warning text-yellow\"></i> 没有数据</h3>");
        }
        return view("chart.tongji_reg.ajax",['datas'=>$datas]);
    }
}