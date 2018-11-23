<?php

namespace App\Http\Controllers\NewAnswerAudit;


use App\PreMAnswerUserAward;

use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use ZipArchive;


class UserAwardController extends Controller
{

    public function userlist($status=0,$start='',$end='')
    {

        $data['start']=$start==''?date("Y-m-d",strtotime("-1 week")):$start;
        $data['end']=$end==''?date("Y-m-d"):$end;

        $data['list']=PreMAnswerUserAward::where(function($query) use($data,$status){
                if($status!=2){
                    $query->where(['status'=>$status]);
                }else{
                    $query->where(['status'=>$status]);
                    $query->where("award_date",">",$data['start']);
                    $query->where("award_date","<=",$data['end'].'23:59:59');
                }
            })
            ->select(DB::raw('any_value(id) as id'),'uid',DB::raw('sum(award) as award'),DB::raw('any_value(add_date) as add_date'),
                DB::raw('any_value(shenqing_date) as shenqing_date'),DB::raw('any_value(award_date) as award_date'),DB::raw('any_value(qq) as qq')
                )
            ->groupBy('uid')
            ->paginate(20);
        $data['zongji']=0;
        foreach($data['list'] as $k=>$v){
            $data['zongji']+=$v->award;
        }
        //dd($data);
        $data['status']=$status;
        return view('new_answer_audit.userlist',compact('data'));
    }

    public function award_user(Request $request){
        $uid=intval($request->uid);
        $re=PreMAnswerUserAward::where(['uid'=>$uid,'status'=>1])
            ->update(['status'=>2,'award_date'=>date('Y-m-d H:i:s')]);
        exit(\GuzzleHttp\json_encode(['status'=>$re]));
    }

    public function award_show_answer(Request $request){
        $uid=intval($request->uid);
        $data=PreMAnswerUserAward::where(['uid'=>$uid,'status'=>1])
            ->select('bookid')->get();
        exit(\GuzzleHttp\json_encode(['status'=>1,'data'=>$data]));
    }

}
