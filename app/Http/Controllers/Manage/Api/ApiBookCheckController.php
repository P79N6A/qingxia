<?php

namespace App\Http\Controllers\Manage\Api;

use App\AWorkbook1010AnswerCheck;
use App\AWorkbook1010Check;
use App\BookVersionType;
use App\ZoneAnswerPath;
use App\ZoneSelfAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiBookCheckController extends Controller
{
  protected $o_uid;
    public function __construct()
    {
      $this->middleware(function ($request, $next) {
        $this->o_uid = Auth::id();
        return $next($request);
      });
    }

  public function check(Request $request){
        $p['o_uid'] = $request->user()->id;
        $book_id =  $request->get('book_id');
        $this->validate($request, [
            'book_id'=>'required|integer',
        ]);
        $type = $request->get('type');
        $now_book = ZoneSelfAnswer::find($book_id);


        if($type == 'check_true'){
            $a_check = new AWorkbook1010Check();
            if($a_check::where('bookname',$request->get('bookname'))->count()>0){
                return response()->json(array('status'=>0,'msg'=>'通过失败，已有同名练习册审核通过，请更改名称'));
            }
            $a_check->bookname =$request->get('bookname');
            $a_check->isbn =$request->get('isbn');
            $a_check->cover =$request->get('cover');
            $a_check->rating =0;
            $a_check->hdid = $now_book->id;
            $a_check->grade_id =$request->get('grade_id');
            $a_check->subject_id =$request->get('subject_id');
            $a_check->volumes_id =$request->get('volume_id');
            $a_check->version_id =$request->get('version_id');
            $a_check->version_year =$request->get('version_year');
            $a_check->press_id =$request->get('press_id')?$request->get('press_id'):0;
            $a_check->sort =$request->get('sort_id')?$request->get('sort_id'):-1;
            $a_check->bookcode=md5($a_check->bookname.$a_check->grade_id.$a_check->subject_id.$a_check->press_id.$a_check->volumes_id);
            $a_check->o_uid = $p['o_uid'];

            if($a_check->save()){
                $now_answer = ZoneAnswerPath::where('answer_id',$book_id)->select('answer_img','num')->orderBy('num','asc')->orderBy('create_time','asc')->get()->toArray();
                foreach ($now_answer as $key=> $value){
                    $a_check_answer = new AWorkbook1010AnswerCheck();
                    $a_check_answer->bookid = $a_check->id;
                    $a_check_answer->book = $a_check->bookcode;
                    $a_check_answer->text = $value['num'];
                    $a_check_answer->chapter_id = $key+1;
                    $a_check_answer->textname = '第'.intval($key+1).'页';
                    $a_check_answer->answer = $value['answer_img'];
                    $a_check_answer->hdid = $now_book->id;
                    $a_check_answer->md5answer = md5($value['answer_img']);
                    $a_check_answer->save();
                }
                $p['has_check'] = 1;
                if($now_book->update($p)){

                  ZoneSelfAnswer::where(['bar_code'=>$request->get('isbn'),'book_version_id'=>$request->get('version_id'),'subject_id'=>$request->get('subject_id'),'grade_id'=>$request->get('grade_id'),'volumes'=>$request->get('volume_id'),'has_check'=>0])->update(['has_check'=>2,'o_uid'=>$this->o_uid]);
                    return response()->json(array('status'=>1,'msg'=>'操作成功'));
                }
            }
        }else{
            $p['has_check'] = 2;
            if($now_book->update($p)){
                return response()->json(array('status'=>1,'msg'=>'操作成功'));
            }
        }
        return response()->json(array('status'=>0,'msg'=>'操作失败'));
    }

    public function add(Request $request,$page=1,$grade_id=0,$subject_id=0,$start_time='',$end_time='',$isbn=0)
    {
        if ($start_time == '') {
            $max_date = ZoneSelfAnswer::max('create_time');
            $now_day = substr($max_date, 0, 10);
        } else {
            $is_date = strtotime($start_time) ? strtotime($start_time) : false;
            if (!$is_date) {
                $max_date = ZoneSelfAnswer::max('create_time');
                $now_day = substr($max_date, 0, 10);
            } else {
                $now_day = substr($start_time, 0, 10);
            }
        }
        if ($end_time != '') {
            $is_date = strtotime($end_time) ? strtotime($end_time) : false;
            if ($is_date) {
                $end_time = substr($end_time, 0, 10);
            }
        }
        $data['grade_id'] = intval($grade_id);
        $data['subject_id'] = intval($subject_id);
        $data['isbn'] = intval($isbn);
        $data['start_time'] = $start_time;
        $data['end_time'] = $end_time;
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
                if($data['end_time']){
                    $query->where('zone_self_answer.create_time','<',$data['end_time'].' 00:00:00');
                }
            })
            ->join('zone_answer_path','zone_self_answer.id','zone_answer_path.answer_id')
            ->groupBy('zone_self_answer.id','book_id','cover_photo',
                'cover_photo_thumb','cip_photo','book_name','subject_id','grade_id','volumes','bar_code','version','book_version_id',
                'zone_self_answer.create_time')
            ->orderBy('answer_total','ASC')
            ->orderBy('zone_self_answer.create_time','DESC')
            ->skip(($page-1)*10+9)->first();

            $data['book_answer'] = $data['book_now']->answers;
            foreach ($data['book_answer'] as $key=>$value){
                $data['book_answer'][$key]['answer_img'] = config('workbook.zone_img_head').auth_url('/zone/answer/'.$value->answer_img);
            }
            $data['book_cover_photo'] = config("app.zone_img_head").auth_url("/zone/answer_cover/".$data['book_now']->cover_photo);
            $data['book_cip_photo'] = auth_url("/zone/answer_cip/".$data['book_now']->cip_photo);

//            $data['book_now'][$key]->cover_photo = $this->auth_url('/zone/answer_cover/'.$value->cover_photo);
//            $data['book_now'][$key]->cover_photo_thumb = $this->auth_url('/zone/answer_cover_thumb/'.$value->cover_photo_thumb);

//        $book_add = '<div class="row" data-id="'.$data['book_now']->id.'"><div class="col-md-5">'.'
//                               <span class="pull-left col-md-6">'.
//                           '<a class="thumbnail show-cover" data-target="#photo_modal" data-toggle="modal">'.
//                                '<img class="cover-photo lazy-load" data-img="'.$data['book_now']->cover_photo.'"'.
//                                     'data-original="'.config("app.zone_img_head").auth_url("/zone/answer_cover/".$data['book_now']->cover_photo).'"/></a>'.
//                                     '<a data-target="#photo_modal" data-toggle="modal" class="btn btn-success show-cip"'.
//                                        'data-cip="'.auth_url("/zone/answer_cip/".$data['book_now']->cip_photo).'">查看cip</a>'.
//                                '<a class="btn btn-success" target="_blank"'.
//                                   'href="https://s.taobao.com/search?q='.$data['book_now']->bar_code.'">淘宝搜索</a>'.
//                                '</span><span class="pull-left text-center col-md-6 operate-box"'.
//                                          'data-id="'.$data['book_now']->id.'">'.
//                                '<div class="input-group"><input class="form-control bookname" value="'.$data['book_now']->id.'"/>'.
//                                    '<a class="input-group-addon btn btn-primary make_bookname">生成</a></div>'.
//                                '<div class="input-group" style="width: 100%">
//                                    ';
//        $subject_now = '<select class="form-control subject_sel" style="width:33.3%">';
//        foreach (config('workbook.subject') as $key1=>$value1){
//            if($key1==$book_add->subject_id){
//                $selected="selected";
//            }else{
//                $selected = '';
//            }
//            $subject_now .='<option value="'.$key1.'" '.$selected.' >'.$value1.'</option>';
//        }
//
//        $subject_now .='</select>';
//
//        $grade_now = '<select class="form-control grade_sel" style="width:33.3%">';
//        foreach (config('workbook.grade') as $key1=>$value1){
//            if($key1==$book_add->grade_id){
//                $selected="selected";
//            }else{
//                $selected = '';
//            }
//            $subject_now .='<option value="'.$key1.'" '.$selected.' >'.$value1.'</option>';
//        }
//
//        $grade_now .='</select>';
//
//        $book_add = $book_add.$subject_now.$grade_now;
//
//        $book_add .= '</div>
//                                <div class="input-group">
//                                <input class="form-control isbn"
//                                       value="'.$book_add->bar_code.'"/>
//                                    <span class="input-group-addon">isbn</span>
//                                </div>';
//        $volume_now = ''
//                                <div class="input-group">
//                                    <input style="width:40%" class="form-control version_year" value="{{ $value[\'version\'] }}">
//                                    <select class="form-control volume_sel" style="width:60%">
//                                        @foreach(config(\'app.volumes\') as $key1=>$value1)
//                                            <option value="{{ $key1 }}"
//                                                    @if($key1==$value[\'volumes\']) selected="selected"@endif>{{ $value1 }}</option>
//                                        @endforeach
//                                </select>
//                                </div>
//                                <div class="input-group">
//                                    <select style="width: 100%;" data-name="press_id" class="form-control press_select">
//
//                                    </select>
//                                    <span class="input-group-addon">出版社</span>
//                                </div>
//                                <div class="input-group">
//                                <select data-name="sort" class="form-control sort_select">
//
//                                </select>
//                                    <span class="input-group-addon">sort</span>
//                                </div>
//                                <input class="form-control" disabled value="{{ $value[\'create_time\'] }}"/>
//
//                                <hr>
//                                 <a class="btn btn-primary btn-block check_true">通过</a>
//                                <a class="btn btn-danger btn-block check_false">不通过</a>
//                            </span>
//                                </div>
//                                <div data-id="{{ $value[\'id\'] }}" class="cover-box"
//                                     style="overflow-y: auto;display: flex">
//                                    @foreach($data[\'book_answer\'][$key] as $key1=>$value1)
//                                        <a class="thumbnail show-answer" data-id="{{ $key1 }}"
//                                           data-target="#answer_modal" data-toggle="modal"><img
//                                                    class="img-responsive cover-img lazy-load"
//                                                    data-original="{{ config(\'app.zone_img_head\').auth_url(\'/zone/answer/\'.$value1->answer_img) }}"/></a>
//                                    @endforeach
//                                </div>
//                            </div>'


        return response()->json($data);

    }

    public function more_isbn($total=0)
    {
      $data = ZoneSelfAnswer::from('zone_self_answer as s')->join('zone_answer_path as a','s.id','a.answer_id')
        ->where(['s.book_id'=>0,'s.has_check'=>0])
        ->where('s.bar_code','>',0)
        ->select('s.bar_code',DB::raw('count(bar_code) as num'))
        ->groupBy('s.bar_code')
        ->orderBy('num','desc')
        ->skip($total)
        ->take(20)
        ->get();
//      $data = ZoneSelfAnswer::where('bar_code','>',0)->where('book_id','=',0)->where('has_check',0)->select('bar_code',DB::raw('count(bar_code) as num'))->groupBy('bar_code')->orderBy('num','desc')->skip($total)->take(20)->get();
      return response()->json($data);
    }

    public function all_not_pass(Request $request)
    {
      $grade_id = intval($request->get('grade_id'));
      $subject_id = intval($request->get('subject_id'));
      $volumes = intval($request->get('volumes'));
      $book_version_id = intval($request->get('book_version_id'));
      $isbn = $request->get('isbn');
      ZoneSelfAnswer::where(['bar_code'=>$isbn,'book_version_id'=>$book_version_id,'subject_id'=>$subject_id,'grade_id'=>$grade_id,'volumes'=>$volumes,'has_check'=>0])->update(['has_check'=>2,'o_uid'=>$this->o_uid]);
      return response()->json(['status'=>1]);
    }

    public function isbn_not_pass(Request $request){
      $isbn = $request->get('isbn');
      ZoneSelfAnswer::where(['bar_code'=>$isbn,'has_check'=>0])->update(['has_check'=>2,'o_uid'=>$this->o_uid]);
      return response()->json(['status'=>1]);
    }
}
