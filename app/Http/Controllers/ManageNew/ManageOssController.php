<?php

namespace App\Http\Controllers\ManageNew;

use App\AnswerModel\AWorkbook1010Cip;
use App\AnswerModel\AWorkbookAnswerCip;
use App\AWorkbook1010;
use App\AWorkbookMain;
use App\AWorkbookNew;
use App\LocalModel\IsbnTemp;
use App\Sort;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ManageOssController extends Controller
{
    public function index($type='isbn_null',$start_time='',$end_time='',$single_id=0)
    {
    //苏蕾，张连荣、、肖高萍、.印娜、张玲莉
        if($start_time==''){
            $data['start'] = date('Y-m-d',time()-86400);
        }else{
            $data['start'] = $start_time;
        }
        if($end_time==''){
            $data['end'] = date('Y-m-d',time()-86400);
        }else{
            $data['end'] = $end_time;
        }
        $start_time = $data['start'].' 00:00:00';
        $end_time = $data['end'].' 23:59:59';

        $whereRaw = '1=1';
        $where_type = [];
        if($type==='isbn_null'){
            $where_type = [['isbn',null]];
        }else if($type==='isbn_problem'){
            $where_type = [['cip_photo','!=',null],['thumbaddtime','>',0]];
            $whereRaw .= ' and cip_time>thumbaddtime';
        }else if($type==='answer_problem'){
            $where_type = [['tid','like','%|%']];
        }else if($type==='book_problem'){
            $where_type = [['tid','like','%|%']];
        }else if ($type==='sort_null'){
            $where_type = [['sort',-1]];
        }else{
            $type = 'isbn_null';
            $where_type = [['isbn',null]];
        }
        $where_time = [['addtime','>',$start_time],['addtime','<',$end_time]];

        Cache::forget('now_cip_data');
        $now_isbn = Cache::remember('now_cip_data', 360, function () use ($where_type,$where_time){
            return AWorkbook1010Cip::where($where_time)->where($where_type)->select('id')->orderBy('addtime','asc')->get();
        });


        $now_sort_split = [];
        foreach ($now_isbn->split(3) as $key=>$value){
            foreach ($value as $book){
                $now_sort_split[$key][] =$book->id;
            }
        }

        $uid = \Auth::id();
        $in_where = [];
        if($uid===17) {
            $in_where = $now_sort_split[0];
        }elseif($uid===19){
            $in_where = $now_sort_split[1];
        }elseif($uid===20){
            $in_where = $now_sort_split[2];
        }
        else{
            $in_where = [];
        }
        if($single_id>0){
            $whereRaw = 'id='.$single_id;//.' and cip_photo not like "%|%"'
        }
        $all_isbn = [];
        $all_like_answers = [];
        if($type==='done')
        {
            $update = ['update_uid','>',0];
            if($single_id>0){
                $now_book = AWorkbook1010Cip::find($single_id);

                if($now_book->update_uid>0){
                    $type='done';
                    $update = ['update_uid','>',0];
                }else{
                    $type='pending';
                    $update = ['update_uid','=',0];
                }
            }


            $answer_cip = new AWorkbookAnswerCip();
            $all_isbn = AWorkbook1010Cip::where([['id','>',0],$update])->whereRaw($whereRaw)->select(['id','bookname','grade_id','subject_id','volumes_id','version_id','cover_photo','cip_photo','isbn','sort','hdid','updated_at','verified_at','answer_not_complete'])->orderBy('updated_at','desc')->orderBy('addtime','asc')->paginate(20);

            foreach ($all_isbn as $key => $item){
                //9787563380145
                $press = get_press($all_isbn[$key]->isbn);
                $all_isbn[$key]['related_sort'] = Sort::where(['version'=>$press])->orWhere('version','like',$press.'|%')->orWhere('version','like','%|'.$press)->orWhere('version','like','%|'.$press.'|%')->select('id','name')->get();
                $all_isbn[$key]->isbn = convert_isbn($item->isbn);
                $all_isbn[$key]['answers'] = $answer_cip->get_normal_answer($item->hdid);
                $all_isbn[$key]['offical_isbn'] = [];
                $all_isbn[$key]['related_isbn'] = [];
                $all_isbn[$key]['related_sort'] = [];
                if(strlen($all_isbn[$key]->isbn)!=17){
                    $all_isbn[$key]->isbn = '978-7-';
                }
            }
        }
        else{
            if ($type==='answer_problem'){
                $all_tid_sql= AWorkbookAnswerCip::where($where_time)->where($where_type)->select('id','tid')->orderBy('addtime','asc')->orderBy('thumbtime','asc')->get();
                $unique_tids = $all_tid_sql->pluck('tid')->unique();
                $all_like_answers = [];
                foreach ($unique_tids as $key => $tid_now){
                    $single_answers =$real_tids = [];
                    foreach (explode('|', $tid_now) as $tid){
                        if(!in_array($tid, $real_tids)){
                            $real_tids[] = $tid;
                        }
                    }
                    foreach ($real_tids as $key1=>$tid){
                        $single_answers[$key1] = AWorkbookAnswerCip::where(function ($query) use ($tid){
                            return $query->where('tid',$tid)->orWhere('tid','like','%|'.$tid.'%')->orWhere('tid','like','%'.$tid.'|%');
                        })->select('id','tid','answer','addtime')->orderBy('addtime','asc')->orderBy('thumbtime','asc')->get();
                    }
                    $all_like_answers[$tid_now] = collect($single_answers)->collapse()->unique();
                }

//                $all_tids = $all_tid_sql->pluck('tid');
//                $real_tids = [];
//                foreach ($all_tids as $tids){
//                    foreach (explode('|', $tids) as $tid){
//                        if(!in_array($tid, $real_tids)){
//                            $real_tids[] = $tid;
//                        }
//                    }
//                }
//                foreach ($real_tids as $key=>$tid){
//                    $all_like_answers[$key]['normal_answer'] = AWorkbookAnswerCip::where('tid',$tid)->select()->orderBy('text','asc')->orderBy('addtime','asc')->orderBy('thumbtime','asc')->get();
//                    $all_like_answers[$key]['like_answer'] = AWorkbookAnswerCip::where('tid','like','%|'.$tid.'%')->orWhere('tid','like','%'.$tid.'|%')->select()->orderBy('addtime','asc')->orderBy('thumbtime','asc')->get();
//                }
            }else{
                $answer_cip = new AWorkbookAnswerCip();
                if(count($in_where)>0){
                    $all_isbn = AWorkbook1010Cip::where($where_time)->where($where_type)->whereIn('id',$in_where)->whereRaw($whereRaw)->select(['id','bookname','grade_id','subject_id','volumes_id','version_id','cover_photo','cip_photo','isbn','sort','hdid','tid','answer_not_complete'])->orderBy('addtime','asc')->paginate(10);
                }else{
                    $all_isbn = AWorkbook1010Cip::where($where_time)->where($where_type)->whereRaw($whereRaw)->select(['id','bookname','grade_id','subject_id','volumes_id','version_id','cover_photo','cip_photo','isbn','sort','hdid','tid','answer_not_complete'])->orderBy('addtime','asc')->paginate(10);
                }
                foreach ($all_isbn as $key => $item){

                    if($type==='book_problem'){
                        $all_isbn[$key]['answers'] = [];
                        $now_tids = explode('|', $item->tid);
                        $now_tids_len = count($now_tids);
                        $now_other_answer = [];
                        for($i=0;$i<$now_tids_len;$i++){
                            $now_other_answer[] = $answer_cip->get_normal_answer($now_tids[$i]);
                        }
                        $all_isbn[$key]['answers_other'] = $now_other_answer;
                    }else{
                        if(strpos($item->tid, '|')===false){
                            $all_isbn[$key]['answers'] = $answer_cip->get_like_answer($item->tid);
                            $all_isbn[$key]['answers_other'] = [];
                        }else{
                            $answer_tids = explode('|', $item->tid);
                            $answer_len = count($answer_tids);
                            $all_isbn[$key]['answers'] = $answer_cip->get_like_answer($answer_tids[0]);
                            $now_other_answer = [];
                            for($i=1;$i<=$answer_len-1;$i++){
                                $now_other_answer[] = $answer_cip->get_like_answer($answer_tids[$i]);
                            }
                            $all_isbn[$key]['answers_other'] = $now_other_answer;
                        }
                    }
                    if(strlen($all_isbn[$key]->isbn)==13){
                        $all_isbn[$key]['offical_isbn'] = AWorkbook1010::where('isbn',$all_isbn[$key]->isbn)->select('id','bookname','grade_id','volumes_id','subject_id','version_id')->get();
                        $all_isbn[$key]['related_isbn'] = [];
                        $all_isbn[$key]['search_isbn'] = IsbnTemp::where(['sort'=>$item->sort,'grade_id'=>$item->grade_id,'subject_id'=>$item->subject_id,'volumes_id'=>$item->volumes_id,'version_id'=>$item->version_id,])->select('id','bookname','isbn')->get();
                        $press = get_press($all_isbn[$key]->isbn);
                        $all_isbn[$key]['related_sort'] = Sort::where(['version'=>$press])->orWhere('version','like',$press.'|%')->orWhere('version','like','%|'.$press)->orWhere('version','like','%|'.$press.'|%')->select('id','name')->get();
                        $all_isbn[$key]->isbn = convert_isbn($all_isbn[$key]->isbn);
                        if(strlen($all_isbn[$key]->isbn)!=17){
                            $all_isbn[$key]->isbn = '978-7-';
                        }
                    }else{
                        $all_isbn[$key]['offical_isbn'] = [];
                        $all_isbn[$key]['related_isbn'] = [];
                        $all_isbn[$key]['related_sort'] = [];
                        $all_isbn[$key]['search_isbn'] = IsbnTemp::where(['sort'=>$item->sort,'grade_id'=>$item->grade_id,'subject_id'=>$item->subject_id,'volumes_id'=>$item->volumes_id,'version_id'=>$item->version_id,])->select('id','bookname','isbn','cover_photo')->get();
                    }
                }

            }

        }

        $data['all_isbn'] = $all_isbn;
        $data['all_like_answers'] = $all_like_answers;
        $data['type'] = $type;
        $data['other'] = $now_sort_split;


        return view('manage_new.oss_new',['data'=>$data]);
    }

    public function status($start='',$end='')
    {

        if($start==''){
            $data['start'] = date('Y-m-d',time());
        }else{
            $data['start'] = $start;
        }
        if($end==''){
            $data['end'] = date('Y-m-d',time()+86400);
        }else{
            $data['end'] = $end;
        }

        $query_start = $data['start'].' 00:00:00';
        $query_end = $data['end'].' 23:59:59';
        $all_books = AWorkbook1010Cip::where([['updated_at','>=',$query_start],['updated_at','<=',$query_end]])->select('id','bookname','update_uid','verified_at','updated_at','answer_not_complete')->with('has_user:id,name')->get();

        $data['all_books'] = $all_books->groupBy('update_uid')->transform(function ($key,$item){
            return $key->sortByDesc('updated_at');
        });
        return view('manage_new.oss_status',['data'=>$data]);


//        AWorkbook1010Cip::where('update_uid','>',0)->select(DB::raw('distinct update_uid'));
//
//        return view('manage_new.oss_status');
    }

    public function api(Request $request,$type)
    {
        switch($type){

            case 'isbn_check':
                $isbn = $request->isbn;
                if(!check_isbn(str_replace(['-','|'], '', $isbn))){
                    return return_json_err();
                }
                break;

            case 'confirm_done':
                //now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all
                $now_id = $request->now_id;
                $book['bookname'] = $request->bookname;
                $book['version_year'] = $request->version_year;
                $book['sort'] = $request->sort_id;
                $book['grade_id'] = $request->grade_id;
                $book['subject_id'] = $request->subject_id;
                $book['volumes_id'] = $request->volume_id;
                $book['version_id'] = $request->version_id;
                $book['isbn'] = str_replace(['-','|'], '', $request->isbn);
                if(\Auth::id()<=5){
                    if($now_id%6===0){
                        $book['update_uid'] = 8;
                    }elseif($now_id%6===1){
                        $book['update_uid'] = 11;
                    }elseif($now_id%6===2){
                        $book['update_uid'] = 17;
                    }elseif($now_id%6===3){
                        $book['update_uid'] = 18;
                    }elseif($now_id%6===4){
                        $book['update_uid'] = 19;
                    }elseif($now_id%6===5){
                        $book['update_uid'] = 20;
                    }
                }else{
                    $book['update_uid'] = \Auth::id();
                }
                $book['updated_at'] = date('Y-m-d H:i:s',time());
                $answer['answer_all'] = $request->answer_all;

                if(!check_isbn($book['isbn'])){
                    return response()->json(['status'=>0,'msg'=>'isbn验证不通过']);
                }
                if($book['sort']<=0){
                    return response()->json(['status'=>0,'msg'=>'请填写系列']);
                }
                if(empty($answer['answer_all'])){
                    return response()->json(['status'=>0,'msg'=>'答案为空']);
                }

                $book['bookcode'] = md5($book['bookname'].$book['version_year'].$book['sort'].$book['grade_id'].$book['subject_id'].$book['volumes_id'].$book['version_id'].$book['isbn'].'from_oss');

                DB::transaction(function() use ($now_id,$book,$answer){
                    $now_press = get_press($book['isbn']);
                    $sort_now = Sort::find($book['sort']);
                    if($sort_now){
                        if(strlen($sort_now->version)==0){
                            $sort_now->version = $now_press;
                        }else{
                            if(strpos($sort_now->version, '|'.$now_press)<=0){
                                $sort_now->version = $sort_now->version.'|'.$now_press;
                            }
                        }
                        $sort_now->save();
                    }
                    AWorkbook1010Cip::where(['id'=>$now_id])->update($book);
                    $hdid = AWorkbook1010Cip::find($now_id)->hdid;
                    foreach ($answer['answer_all'] as $key=>$value){
//                        $now['text'] = $key+1;
//                        $now['textname'] = '第'.$now['text'].'页';
                        $now['bookid'] = $now_id;
                        $now['book'] = $book['bookcode'];
                        $now['tid'] = $hdid;
                        AWorkbookAnswerCip::where(['id'=>$value])->update($now);
                    }
                });
                break;
            case 'confirm_answer_done':
                //now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all
                $now_id = $request->now_id;
                $now_tid = $request->now_tid;

                $book['update_uid'] = \Auth::id();
                $book['updated_at'] = date('Y-m-d H:i:s',time());
                $answer['answer_all'] = $request->answer_all;
                if(empty($answer['answer_all'])){
                    return response()->json(['status'=>0,'msg'=>'答案为空']);
                }

                DB::transaction(function() use ($now_id,$now_tid,$book,$answer){
                    if($now_tid>0){
                        $book['tid'] = $now_tid;
                    }

                    AWorkbook1010Cip::where(['id'=>$now_id])->update($book);
                    foreach ($answer['answer_all'] as $key=>$value){
                        $now['text'] = $key+1;
                        $now['textname'] = '第'.$now['text'].'页';
                        //$now['tid'] = $tid;
                        AWorkbookAnswerCip::where(['id'=>$value])->update($now);
                    }
                    //需要检测顺序
//                    $all_like_books = AWorkbook1010Cip::where(function ($query) use ($tid){
//                        return $query->where('tid','like','%'.$tid.'|%')->orWhere('tid','like','%|'.$tid.'%');
//                    })->select('id','tid')->get();
//                    foreach ($all_like_books as $answer_now){
//                        $answer_now_tid = str_replace([$tid.'|','|'.$tid], '', $answer_now->tid);
//                        AWorkbook1010Cip::where('id',$answer_now->id)->update(['tid'=>$answer_now_tid]);
//                    }
                });
                return response()->json(['status'=>1]);
                break;
//            case 'confirm_like_answer':
//                $tid = $request->tid;
//                $answer_ids = $request->answers_del;
//                if(!$tid){
//                    return response()->json(['status'=>0]);
//                }
//                if(AWorkbookAnswerCip::whereIn('id',$answer_ids)->update(['tid'=>$tid])){
//                    $all_other_likes = AWorkbookAnswerCip::where('tid','like','%|'.$tid.'%')->orWhere('tid','like','%'.$tid.'|%')->select('id','tid')->get();
//                    foreach ($all_other_likes as $other){
//                        $now_tid = str_replace(['|'.$tid,$tid.'|'], '', $other->tid);
//                        AWorkbookAnswerCip::where(['id'=>$other->id])->update(['tid'=>$now_tid]);
//                    }
//                    return response()->json(['status'=>1]);
//                }else{
//                    return response()->json(['status'=>0]);
//                }
//                break;

            case 'confirm_single_done':
                $tid = $request->now_tid;
                $ids = $request->now_ids;
                $now_related = $request->now_related;
                if(count($ids)<1 || $tid<0 || strpos($now_related, '|')===false){
                    return response()->json(['status'=>0]);
                }
                if(AWorkbookAnswerCip::whereIn('id',$ids)->update(['tid'=>$tid])){
                    $all_like_answers = AWorkbookAnswerCip::where('tid',$now_related)->select('id','tid')->get();
                    foreach ($all_like_answers as $answer_now){
                        $answer_now_tid = str_replace([$tid.'|','|'.$tid], '', $answer_now->tid);
                        AWorkbook1010Cip::where('id',$answer_now->id)->update(['tid'=>$answer_now_tid]);
                    }
                    return response()->json(['status'=>1]);
                }else{
                    return response()->json(['status'=>0]);
                }

                break;
            case 'mix_tids':
                $tids = $request->now_tids;
                if(strpos($tids, ',')<=0){
                    return response()->json(['status'=>0]);
                }
                $tid_result = str_replace(',', '|', $tids);
                foreach (explode(',', $tids) as $tid){
                    AWorkbookAnswerCip::where('tid',$tid)->update(['tid'=>$tid_result]);
                }
                return response()->json(['status'=>1]);
                break;

            case 'get_related_sort':
                $isbn = $request->isbn;
                $press = get_press(str_replace(['-','|'], '', $isbn));
                $related_sort = Sort::where(['version'=>$press])->orWhere('version','like',$press.'|%')->orWhere('version','like','%|'.$press)->orWhere('version','like','%|'.$press.'|%')->select('id','name')->get();
                return response()->json(['status'=>1,'related_sort'=>$related_sort]);

                break;
            case 'get_related_book':
                $condition['sort'] = $request->sort_id;
                $condition['grade_id'] = $request->grade_id;
                $condition['subject_id'] = $request->subject_id;
                $related_book = AWorkbookNew::where($condition)->select('id','newname')->orderBy('version_year','desc')->get();
                if(count($related_book)>0){
                    foreach ($related_book as $book){
                        $book->newname = str_replace(['上册'], '下册', $book->newname);
                        $book->newname = str_replace(['全一册','全一册上'], '全一册下', $book->newname);
                    }
                }
                return response()->json(['status'=>1,'related_book'=>$related_book]);
                break;
            case 'verify_done':
                $book_id = $request->now_id;
                $now_book = AWorkbook1010Cip::find($book_id);
                if($now_book->updated_at){
                    AWorkbook1010Cip::where('id',$book_id)->update(['verified_at'=>date('Y-m-d H:i:s',time())]);
                }else{
                    if($now_book->id%6===0){
                        $now_book->update_uid = 8;
                    }elseif($now_book->id%6===1){
                        $now_book->update_uid = 11;
                    }elseif($now_book->id%6===2){
                        $now_book->update_uid = 17;
                    }elseif($now_book->id%6===3){
                        $now_book->update_uid = 18;
                    }elseif($now_book->id%6===4){
                        $now_book->update_uid = 19;
                    }elseif($now_book->id%6===5){
                        $now_book->update_uid = 20;
                    }

                    $now_book->updated_at = date('Y-m-d H:i:s',time()-3600);
                    $now_book->verified_at = date('Y-m-d H:i:s',time());
                    $now_book->save();
                }
                break;
            case 'mark_answer':
                $now_id = $request->now_id;
                $book_now = AWorkbook1010Cip::find($now_id);
                $book_now->answer_not_complete = $book_now->answer_not_complete>0?0:1;
                $book_now->save();
                break;
            case 'save_sort':
                $book_id = $request->book_id;
                $sort = $request->sort_id;
                if($book_id<=0 || $sort<=0 || !AWorkbook1010Cip::where('id',$book_id)->update(['sort'=>$sort])){
                    return response()->json(['status'=>0]);
                }

                break;

            case 'choose_answer':
                $id = $request->now_id;
                $tid = $request->now_tid;
                $now_info = AWorkbook1010Cip::find($id);
                $other_tid = str_replace([$tid.'|','|'.$tid], '', $now_info->tid);
                if($other_tid && AWorkbook1010Cip::where('id',$id)->update(['tid'=>$tid])){
                    $all_like_books = AWorkbook1010Cip::where(function ($query) use ($tid){
                        return $query->where('tid','like','%'.$tid.'|%')->orWhere('tid','like','%|'.$tid.'%');
                    })->select('id','tid')->get();
                    foreach ($all_like_books as $book_now){
                        $answer_now_tid = str_replace([$tid.'|','|'.$tid], '', $book_now->tid);
                        AWorkbook1010Cip::where('id',$book_now->id)->update(['tid'=>$answer_now_tid]);
                    }
                    return response()->json(['status'=>1,'other_id'=>$other_tid]);
//                    $now_related = AWorkbook1010Cip::where('tid','like','%'.$other_tid.'%')->select('id','tid')->get();
//                    foreach ($now_related as $item){
//                        if(strpos($item->tid, '|')!=false){
//                            if(AWorkbook1010Cip::where('id',$item->id)->update(['tid'=>$other_tid])){
//                                return response()->json(['status'=>1,'other_id'=>$item->id]);
//                            }
//                        }
//                    }
                }

        }
        return response()->json(['status'=>1]);
    }
}
