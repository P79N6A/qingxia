<?php

namespace App\Http\Controllers\Manage;

use App\AWorkbook1010Check;
use App\BookVersionType;
use App\ZoneAnswerPath;
use App\ZoneSelfAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BookCheckController extends Controller
{
    public function index(Request $request,$grade_id=0,$subject_id=0,$volumes=999,$book_version_id=999,$start_time='',$end_time='',$isbn=0){

        if($start_time==''){
            $max_date = ZoneSelfAnswer::max('create_time');
            $now_day = substr($max_date,0,10);
        }else{
            $is_date = strtotime($start_time)?strtotime($start_time):false;
            if(!$is_date){
                $max_date = ZoneSelfAnswer::max('create_time');
                $now_day = substr($max_date,0,10);
            }else{
                $now_day = substr($start_time,0,10);
            }
        }
        if($end_time!=''){
            $is_date = strtotime($end_time)?strtotime($end_time):false;
            if($is_date){
                $end_time = substr($end_time,0,10);
            }
        }


        $data['grade_id'] = intval($grade_id);
        $data['subject_id'] = intval($subject_id);
        $data['volumes'] = intval($volumes);
        $data['book_version_id'] = intval($book_version_id);
        $data['isbn'] = $isbn;
        $data['start_time'] = $start_time?$start_time:$now_day;
        $data['end_time'] = $end_time?$end_time:$now_day;


      $data['isbn_all'] = ZoneSelfAnswer::from('zone_self_answer as s')->join('zone_answer_path as a','s.id','a.answer_id')
        ->where(['s.book_id'=>0,'s.has_check'=>0])
        ->where('s.bar_code','>',0)
//        ->where('s.isbn_done',0)
        ->select('s.bar_code',DB::raw('count(bar_code) as num'))
        ->groupBy('s.bar_code')
        ->orderBy('num','desc')
        ->take(100)
        ->get();

//      set_time_limit(0);
//
//      $data['all_isbn_done']  = AWorkbook1010Check::from('a_workbook_1010_check as c')->where('isbn','<>',NULL)
//        ->join('zone_self_answer as a','a.bar_code','c.isbn')
//        ->where('a.isbn_done',0)
//
//        ->select(DB::raw('distinct c.isbn as isbn'))
//        ->groupBy('isbn')
//        ->orderBy('isbn','asc')
//
//        ->chunk(500,function ($res){
//          foreach($res as $key=>$value){
//            ZoneSelfAnswer::where('bar_code',$value->isbn)->update(['isbn_done'=>1]);
//          }
//        });
//      dd($data['all_isbn_done']);





//      $data['isbn_all'] = ZoneSelfAnswer::where('bar_code','<>','')->where('book_id','=',0)->where('has_check',0)->select('bar_code',DB::raw('count(bar_code) as num'))->groupBy('bar_code')->orderBy('num','desc')->take(20)->get();



        $data['book_now'] = ZoneSelfAnswer::select(['zone_self_answer.id','book_id','cover_photo',
            'cover_photo_thumb','cip_photo','book_name','subject_id','grade_id','volumes','bar_code','version','book_version_id',
            'zone_self_answer.create_time',DB::raw('count(zone_self_answer.id) as answer_total')])
            ->where('book_id','=',0)
            ->where('has_check',0)
            ->where('zone_self_answer.create_time','>=',$now_day.' 00:00:00')
            ->where(function ($query) use ($data){
                if($data['isbn']>0 and starts_with($data['isbn'],'9787')){
                    $query->where('bar_code','=',$data['isbn']);
                }
                if($data['grade_id']>0){
                    $query->where('grade_id','=',$data['grade_id']);
                }
                if($data['subject_id']>0){
                    $query->where('subject_id','=',$data['subject_id']);
                }
              if($data['volumes']!=999){
                $query->where('volumes','=',$data['volumes']);
              }
              if($data['book_version_id']!=999){
                $query->where('book_version_id','=',$data['book_version_id']);
              }
                if($data['end_time']){
                    $query->where('zone_self_answer.create_time','<',$data['end_time'].' 00:00:00');
                }
            })
            ->join('zone_answer_path','zone_self_answer.id','zone_answer_path.answer_id')
            ->groupBy('zone_self_answer.id','book_id','cover_photo',
                'cover_photo_thumb','cip_photo','book_name','subject_id','grade_id','volumes','bar_code','version','book_version_id',
                'zone_self_answer.create_time')
            ->orderBy('answer_total','DESC')
            ->orderBy('zone_self_answer.create_time','DESC')
            ->paginate(10);

      if(starts_with($data['isbn'],'9787')){
        $now_group_sql = ZoneSelfAnswer::from('zone_self_answer as s')->select(['s.id','subject_id','grade_id','volumes','book_version_id'])
          ->where('book_id','=',0)
          ->where('has_check',0)
          ->where('s.create_time','>=',$now_day.' 00:00:00')
          ->where(function ($query) use ($data){
            if($data['isbn']>0 and starts_with($data['isbn'],'9787')){
              $query->where('bar_code','=',$data['isbn']);
            }
            if($data['end_time']){
              $query->where('s.create_time','<',$data['end_time'].' 00:00:00');
            }
          })
          ->join('zone_answer_path','s.id','zone_answer_path.answer_id')
          ->groupBy('s.id','subject_id','grade_id','volumes','book_version_id')
          ->get();
        foreach ($now_group_sql as $key =>$value){
          $now_group[$key]['group'] = $value['grade_id'].'|'.$value['subject_id'].'|'.$value['volumes'].'|'.$value['book_version_id'];
        }
        if(isset($now_group)){
          $data['now_group'] = collect($now_group)->groupBy('group');

          $data['now_group'] = $data['now_group']->sortByDesc(function ($now_group ,$key) {
            return count($now_group);
          });
        }

      }




        foreach ($data['book_now'] as $key=>$value){
            $data['book_answer'][$key] = $value->answers;

//            $data['book_now'][$key]->cover_photo = $this->auth_url('/zone/answer_cover/'.$value->cover_photo);
//            $data['book_now'][$key]->cover_photo_thumb = $this->auth_url('/zone/answer_cover_thumb/'.$value->cover_photo_thumb);
        }
        $data['version'] = BookVersionType::all('id','name');



        return view('manage.book_check',compact(['user','data']));
    }
}
