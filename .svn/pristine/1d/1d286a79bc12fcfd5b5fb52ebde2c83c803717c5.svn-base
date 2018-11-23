<?php

namespace App\Http\Controllers\Baidu;

use App\AWorkbook1010;
use App\AWorkbookMain;
use App\AWorkbookRds;
use App\Baidu;
use App\BaiduNew;
use App\BaiduHash;
use App\BaiduNewDaan;
use App\BookVersionType;
use App\MoShitiJiexi;
use App\MoXitiJiexi;
use App\PreMWorkbookUser;
use App\Volume;
use App\WorkbookAnswer;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Log;
use Mockery\Exception;

class BaiduController extends Controller
{

  protected $data;
  protected $where;
  protected $other;
  //答案列表
  public function index($time = 0, $grade_id = 0, $subject_id = 0, $volume_id = 0, $version_id = -1, $sort_id = -999)
  {
    $this->time_about($time);
    $data = $this->data;
    $where = $this->where;

    if ($grade_id > 0) {
      $where[] = ['grade_id', '=', $grade_id];
    }
    if ($subject_id > 0) {
      $where[] = ['subject_id', '=', $subject_id];
    }
    if ($volume_id > 0) {
      $where[] = ['volume_id', '=', $volume_id];
    }
    if ($version_id >= 0) {
      $where[] = ['version_id', '=', $version_id];
    }
    if ($sort_id > -1) {
      $where[] = ['sort_id', '=', $sort_id];
    }
    $data['grade_id'] = $grade_id;
    $data['subject_id'] = $subject_id;
    $data['volume_id'] = $volume_id;
    $data['version_id'] = $version_id;
    $data['sort_id'] = $sort_id;

    $where[] = ['book_id', '>', 0];


    $data['all_version'] = Cache::remember('all_version', 120, function () {
      return BookVersionType::all(['id', 'name', 'press_name', 'press_alias', 'district']);
    });

    $data['all_volumes'] = Cache::remember('all_volumes', 120, function () {
      return Volume::all(['id', 'volumes']);
    });

    $data['all_version'] = collect($data['all_version']);
    $data['all_volumes'] = collect($data['all_volumes']);


    foreach ($data['all_version'] as $key => $value) {
      $version_array[$key]['id'] = $value->id;
      $version_array[$key]['text'] = $value->name;
    }
    foreach ($data['all_volumes'] as $key => $value) {
      $volume_array[$key]['id'] = $value->id;
      $volume_array[$key]['text'] = $value->volumes;
    }
    foreach (config('workbook.grade') as $key => $value) {
      if ($key > 0) {
        $grade_array[$key - 1]['id'] = $key;
        $grade_array[$key - 1]['text'] = $value;
      }
    }
    foreach (config('workbook.subject_1010') as $key => $value) {
      if ($key > 0) {
        $subject_array[$key - 1]['id'] = $key;
        $subject_array[$key - 1]['text'] = $value;
      }
    }

    $data['version_select'] = json_encode($version_array);
    $data['subject_select'] = json_encode($subject_array);
    $data['grade_select'] = json_encode($grade_array);
    $data['volume_select'] = json_encode($volume_array);


    $data['total'] = BaiduNewDaan::where($where)->select('book_id', DB::raw('sum(visit_count) as all_pv'), DB::raw('sum(visitor_count) as all_uv'), DB::raw('sum(new_visitor_count) as new_uv'), DB::raw('avg(new_visitor_ratio) as new_visitor_ratio'), DB::raw('sum(ip_count) as ip_count'), DB::raw('sum(out_pv_count) as out_pv_count'), DB::raw('avg(bounce_ratio) as bounce_ratio'), DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(avg_visit_pages) as avg_visit_pages'),DB::raw('min(book_name) as book_name'), DB::raw('min(grade_id) as grade_id'), DB::raw('min(subject_id) as subject_id'), DB::raw('min(volume_id) as volume_id'), DB::raw('min(version_id) as version_id'), DB::raw('min(sort_id) as sort_id'))->with('has_book:id,isbn,bookname,collect_count,version_year')->with('has_book.has_hd_book:id,concern_num')->with('has_user_book:id,isbn,sort_name')->with('has_sort:id,name')->groupBy('book_id')->orderBy('all_pv', 'desc')->paginate(10);
    foreach ($data['total'] as $key => $book){
        $book_id = $book->book_id;
        if($book_id<10000000){
            $now_book = AWorkbook1010::find($book_id);
            $query['grade_id'] = $now_book->grade_id;
            $query['subject_id'] = $now_book->subject_id;
            $query['volumes_id'] = $now_book->volumes_id;
            $query['version_id'] = $now_book->version_id;
            $query['sort'] = $now_book->sort;
            $data['total'][$key]['related_book'] = AWorkbook1010::where($query)->select(['id','bookname','collect_count','version_year','hdid'])->with('has_hd_book:id,concern_num')->orderBy('version_year','desc')->orderBy('id','desc')->get();
        }else{

            $now_book = PreMWorkbookUser::find($book_id);
            $query['grade_id'] = $now_book->grade_id;
            $query['subject_id'] = $now_book->subject_id;
            $query['volumes_id'] = $now_book->volumes_id;
            $query['version_id'] = $now_book->version_id;
            $query['sort_id'] = $now_book->sort_id;
            $data['total'][$key]['related_book'] = PreMWorkbookUser::where($query)->select(['id','sort_name as bookname','need_num as collect_count','banci as version_year'])->orderBy('version_year','desc')->orderBy('id','desc')->get();
        }
    }

	$data['total_sort'] = BaiduNewDaan::where($where)->select('sort_id',DB::raw('sum(visit_count) as all_pv'))->groupBy('sort_id')->with('has_sort:id,name')->orderBy('all_pv','desc')->take(200)->get();

    return view('baidu.index', compact('data'));
  }

  //试题列表
  public function question($time=0,$type='xxyw')
  {
    $this->time_about($time);
    $data = $this->data;
    $where = $this->where;
    $data['all_type']= ['xxyw'=>'小学语文','xxsx'=>'小学数学','xxyy'=>'小学英语','czyw'=>'初中语文','czsx'=>'初中数学','czyy'=>'初中英语','czwl'=>'初中物理','czhx'=>'初中化学','czsw'=>'初中生物','czdl'=>'初中地理','czls'=>'初中历史','czzz'=>'初中政治','gzyw'=>'高中语文','gzsx'=>'高中数学','gzyy'=>'高中英语','gzwl'=>'高中物理','gzhx'=>'高中化学','gzsw'=>'高中生物','gzdl'=>'高中地理','gzls'=>'高中历史','gzzz'=>'高中政治'];

    if($type!==0 && in_array($type, array_keys($data['all_type']))){
      $data['now_type'] = $type;
      $data['type_name'] = $data['all_type'][$type];
      $where[] = ['shiti_type','=',$type];
    }else{
      $data['now_type'] = 'xxyw';
      $data['type_name'] = '小学语文';
    }


    $where[] = ['shiti_id','<>',''];
    $now_table = 'baidu_new_'.$data['now_type'];
    $data['total'] = DB::connection('mysql_local')->table($now_table)->where($where)->select('shiti_id','shiti_type', DB::raw('sum(visit_count) as all_pv'), DB::raw('sum(visitor_count) as all_uv'), DB::raw('sum(new_visitor_count) as new_uv'), DB::raw('avg(new_visitor_ratio) as new_visitor_ratio'), DB::raw('sum(ip_count) as ip_count'), DB::raw('sum(out_pv_count) as out_pv_count'), DB::raw('avg(bounce_ratio) as bounce_ratio'), DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(avg_visit_pages) as avg_visit_pages'),DB::raw('min(url) as url'))->groupBy('shiti_type','shiti_id')->orderBy('all_pv', 'desc')->paginate(20);

    $all_shiti_type = collect($data['total']->items())->groupBy('shiti_type');


    foreach ($all_shiti_type as $key => $value){
      $now_all_shiti = collect($value)->pluck('shiti_id');
      $data['all_shiti'][$key] = DB::connection('mysql_main_jiajiao')->table('mo_'.$key)->whereIn('md5id',$now_all_shiti)->where('url','not like','%jyeoo%')->select('md5id','question','answer')->get();
      $data['all_jiexi'][$key] = DB::connection('mysql_main_jiajiao')->table('mo_shiti_jiexi')->where('shiti_type',$key)->whereIn('shiti_md5id',$now_all_shiti)->select('uid','shiti_md5id','question','answer','analysis')->get();
    }



    return view('baidu.question', compact('data'));
  }

  //无答案试题列表
  public function question_no_answer($status='1',$type='xxyw')
  {
    $this->time_about(0);
    $data = $this->data;
    $data['all_type']= ['xxyw'=>'小学语文','xxsx'=>'小学数学','xxyy'=>'小学英语','czyw'=>'初中语文','czsx'=>'初中数学','czyy'=>'初中英语','czwl'=>'初中物理','czhx'=>'初中化学','czsw'=>'初中生物','czdl'=>'初中地理','czls'=>'初中历史','czzz'=>'初中政治','gzyw'=>'高中语文','gzsx'=>'高中数学','gzyy'=>'高中英语','gzwl'=>'高中物理','gzhx'=>'高中化学','gzsw'=>'高中生物','gzdl'=>'高中地理','gzls'=>'高中历史','gzzz'=>'高中政治'];

    if($type!==0 && in_array($type, array_keys($data['all_type']))){
      $data['now_type'] = $type;
      $data['type_name'] = $data['all_type'][$type];
    }else{
      $data['now_type'] = 'xxyw';
      $data['type_name'] = '小学语文';
    }

	$data['now_status'] = $status;
    $now_table = 'mo_'.$data['now_type'];
    $baidu_table = 'baidu_new_'.$type;

	$where[] = ['no_answer','=',$status];
    $data['total'] = DB::connection('mysql_local')->table($baidu_table)->where($where)->select('shiti_id','shiti_type', DB::raw('sum(visit_count) as all_pv'), DB::raw('sum(visitor_count) as all_uv'), DB::raw('sum(new_visitor_count) as new_uv'), DB::raw('avg(new_visitor_ratio) as new_visitor_ratio'), DB::raw('sum(ip_count) as ip_count'), DB::raw('sum(out_pv_count) as out_pv_count'), DB::raw('avg(bounce_ratio) as bounce_ratio'), DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(avg_visit_pages) as avg_visit_pages'),DB::raw('min(url) as url'))->groupBy('shiti_type','shiti_id')->orderBy('all_pv', 'desc')->paginate(20);
    $all_md5id = collect($data['total']->items())->pluck('shiti_id');
	
    if($status==1){
      $data['all_answer'] = DB::connection('mysql_main_jiajiao')->table($now_table)->whereIn('md5id',$all_md5id)->select('md5id','answer','question')->get();
    }else{
      $data['all_answer'] = DB::connection('mysql_main_jiajiao')->table('mo_shiti_jiexi')->where('shiti_type',$type)->whereIn('shiti_md5id',$all_md5id)->select('shiti_md5id as md5id','answer','question','analysis')->get();
    }
	
    if(\Auth::id()==2){
		//dd( $data['all_answer'] = DB::connection('mysql_main_jiajiao')->table($now_table)->whereIn('md5id',$all_md5id)->select('md5id','answer','question')->toSql());
      //dd($data['all_answer']);
    }

    return view('baidu.question_no_answer', compact('data'));
  }

  public function add_answer_for_shiti(Request $request)
  {
    $md5id = $request->shiti_id;
    $now_type = $request->shiti_type;
    $data['question'] = $request->question;
    $data['answer'] = $request->answer;
    $related_uid = \Auth::user()->related_uid;
    $table = 'mo_'.$now_type;
	$baidu_table = 'baidu_new_'.$now_type;
	$update = DB::connection('mysql_main_jiajiao')->table($table)->where('md5id',$md5id)->update($data);
    if($update){
	  DB::connection('mysql_main_jiajiao')->table('mo_no_answer')->where(['type'=>$now_type,'md5id'=>$md5id])->update(['status'=>1,'uid'=>\Auth::id()]);
	  DB::connection('mysql_local')->table($baidu_table)->where(['shiti_id'=>$md5id,'shiti_type'=>$now_type])->update(['no_answer'=>2]);
      MoShitiJiexi::updateOrCreate(
        ['shiti_md5id' => $md5id,'shiti_type'=>$now_type],
        ['answer'=>$data['answer'],'question'=>$data['question'],'analysis'=>$request->analysis,'uid'=>$related_uid]
      );
      return response()->json(['status'=>1,'msg'=>'更新成功']);
    }
    return response()->json(['status'=>0,'msg'=>'更新失败']);
  }

  //习题列表
  public function xiti($time=0,$type='timu')
  {
    $this->time_about($time);
    $data = $this->data;
    $where = $this->where;
    $where[] = ['book_id','0'];
    $where[] = ['chapter_id','0'];
    $table_now = 'baidu_new_'.$type;
    $data['now_type'] = $type;
    $data['all_type'] = ['timu','timu3','xiti'];
    $data['total'] = DB::connection('mysql_local')->table($table_now)->where(function ($query) use($where){
     $query->where($where)
       ->where(function ($query){
         $query->orWhere('timu_id','>',0)->orWhere('timu3_id','>',0)->orWhere('xiti_id','>',0);
       });
    })->select('timu_id','timu3_id','xiti_id', DB::raw('sum(visit_count) as all_pv'), DB::raw('sum(visitor_count) as all_uv'), DB::raw('sum(new_visitor_count) as new_uv'), DB::raw('avg(new_visitor_ratio) as new_visitor_ratio'), DB::raw('sum(ip_count) as ip_count'), DB::raw('sum(out_pv_count) as out_pv_count'), DB::raw('avg(bounce_ratio) as bounce_ratio'), DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(avg_visit_pages) as avg_visit_pages'),DB::raw('min(url) as url'))->groupBy('timu_id','timu3_id','xiti_id')->orderBy('all_pv', 'desc')->paginate(10);

    $data_timu = $data['total']->where('timu_id','>',0)->pluck('timu_id');
    $data_timu_now_1 = $data_timu->filter(function ($value, $key) {
      return $value <= 1223274;
    });
    $data_timu_now_2 = $data_timu->filter(function ($value, $key) {
      return $value > 1223274;
    });

    $data_timu_now_3 = $data['total']->where('timu3_id','>',0)->pluck('timu3_id');
    $data_xiti_now = $data['total']->where('xiti_id','>',0)->pluck('xiti_id');

    if(count($data_timu_now_1)>0){
      $data['now_timu_1'] = DB::connection('mysql_main_jiajiao')->table('mo_cotimu')->whereIn('id',$data_timu_now_1)->select('id','question','answer','subject')->get();
      $data['now_timu_1_jiexi'] = DB::connection('mysql_main_jiajiao')->table('mo_xiti_jiexi')->where('table','mo_cotimu')->whereIn('cid',$data_timu_now_1)->select('table','cid','question','answer','analysis')->get();
    }
    if(count($data_timu_now_2)>0){
      $data['now_timu_2'] = DB::connection('mysql_main_jiajiao')->table('mo_cotimu2')->whereIn('id',$data_timu_now_2)->select('id','question','answer','subject')->get();
      $data['now_timu_2_jiexi'] = DB::connection('mysql_main_jiajiao')->table('mo_xiti_jiexi')->where('table','mo_cotimu2')->whereIn('cid',$data_timu_now_2)->select('table','cid','question','answer','analysis')->get();
    }
    if(count($data_timu_now_3)>0){
      $data['now_timu_3'] = DB::connection('mysql_main_jiajiao')->table('mo_cotimu3')->whereIn('id',$data_timu_now_3)->select('id','question','answer','subject')->get();
      $data['now_timu_3_jiexi'] = DB::connection('mysql_main_jiajiao')->table('mo_xiti_jiexi')->where('table','mo_cotimu3')->whereIn('cid',$data_timu_now_3)->select('table','cid','question','answer','analysis')->get();
    }
    if(count($data_xiti_now)>0){
      $data['now_xiti'] = DB::connection('mysql_main_jiajiao')->table('testpaper')->whereIn('id',$data_xiti_now)->select('id','question','answer','subject')->get();
      $data['now_xiti_jiexi'] = DB::connection('mysql_main_jiajiao')->table('mo_xiti_jiexi')->where('table','testpaper')->whereIn('cid',$data_xiti_now)->select('table','cid','question','answer','analysis')->get();
    }


    return view('baidu.xiti', compact('data'));
  }

  //编辑试题解析
  public function add_jiexi(Request $request)
  {
    $data['shiti_md5id'] = $request->shiti_id;
    $data['shiti_type'] = $request->shiti_type;
    $data['uid'] = \Auth::user()->related_uid;
    $data['analysis'] = $request->analysis;
    $data['question'] = $request->question;
    $data['answer'] = $request->answer;
    if(MoShitiJiexi::updateOrCreate(
      ['shiti_md5id' => $data['shiti_md5id'],'shiti_type'=>$data['shiti_type']],
      ['answer'=>$data['answer'],'question'=>$data['question'],'analysis' => $data['analysis'],'uid'=>$data['uid']]
    )){
      return response()->json(['status'=>1,'msg'=>'更新成功']);
    }
    return response()->json(['status'=>0,'msg'=>'更新失败']);
  }

  //编辑习题解析
  public function add_xiti(Request $request)
  {
    $data['cid'] = $request->xiti_id;
    $data['table'] = $request->xiti_type;
    $data['uid'] = \Auth::user()->related_uid;
    $data['analysis'] = $request->analysis;
    $data['question'] = $request->question;
    $data['answer'] = $request->answer;
    if(MoXitiJiexi::updateOrCreate(
      ['cid' => $data['cid'],'table'=>$data['table']],
      ['answer'=>$data['answer'],'question'=>$data['question'],'analysis' => $data['analysis'],'uid'=>$data['uid']]
    )){
      return response()->json(['status'=>1,'msg'=>'更新成功']);
    }
    return response()->json(['status'=>0,'msg'=>'更新失败']);
  }

  //新文章
  public function new_portal($time=0)
  {
    $this->time_about($time);
    $data = $this->data;
    $other = $this->other;

    $where = [['uptime','>=',$other['end']],['uptime','<=',$other['end']+86400]];

    $data['all_des'] = DB::connection('mysql_main')->table('seo_portal_doc_1')->where('cid','30872')->where($where)->select('id','title','des','uptime')->orderBy('uptime','desc')->paginate(10);
    $ids = collect($data['all_des']->items())->pluck('id');
    $data['all_content'] = DB::connection('mysql_main')->table('seo_portal_doc_content_1')->whereIn('id',$ids)->select('id','content')->get();

    return view('baidu.new_portal',compact('data'));
  }

  //增加文章
  public function add_portal(Request $request)
  {
    $portal_id = intval($request->now_id);
    $portal_title = $request->title;
    $portal_des = $request->des;
    $portal_content = $request->contents;
    if($portal_id>0){
      DB::connection('mysql_main')->table('seo_portal_doc_1')->where('id',$portal_id)->update(['uid'=>\Auth::user()->related_uid,'title'=>$portal_title,'des'=>$portal_des]);
      DB::connection('mysql_main')->table('seo_portal_doc_content_1')->where('id',$portal_id)->update(['content'=>$portal_content]);
      return response()->json(['status'=>1,'msg'=>'更新成功']);
    }else{
      $related_id = DB::connection('mysql_main')->table('seo_portal_doc_1')->where('cid',30872)->select('id')->take(5)->inRandomOrder()->get();
      if(count($related_id)>0){
        $portal_relatedid = collect($related_id)->pluck('id');
      }else{
        $portal_relatedid = '';
      }
      if($new_id = DB::connection('mysql_main')->table('seo_portal_doc_1')->insertGetId(['cid'=>30872,'uid'=>\Auth::user()->related_uid,'title'=>$portal_title,'des'=>$portal_des,'uptime'=>time(),'relatedid'=>$portal_relatedid])){
        if(DB::connection('mysql_main')->table('seo_portal_doc_content_1')->insert(['id'=>$new_id,'content'=>$portal_content])){
          DB::connection('mysql_main')->table('seo_portal_cat')->where('cid',30872)->increment('total');
          return response()->json(['status'=>1,'msg'=>'更新成功']);
        }
      }
    }
    return response()->json(['status'=>0,'msg'=>'更新失败']);


  }

  //练习册详情统计
  public function book_detail($id,$time=0)
  {
    $this->time_about($time);
    $data = $this->data;
    $where_now = $this->where;
//    $data['chapter_info'] = WorkbookAnswer::where('bookid',$id)->select('id','textname')->with(['has_record'=>function($query) use ($where_now){
//      $query->where($where_now);
//    }])->orderBy('id','asc')->get();
//
//    foreach ($data['chapter_info'] as $value){
//      $data['chapter_num'][] = collect($value->has_record)->sum(function($query){
//        return $query->visit_count;
//      });
//    }

    $data['chapter_info'] = WorkbookAnswer::where('bookid',$id)->select('id','textname')->orderBy('id','asc')->get();
    $all_chapter = BaiduNewDaan::whereIn('chapter_id',$data['chapter_info']->pluck('id'))->where($where_now)->select('chapter_id',DB::raw('sum(visit_count) as visit_count'))->groupBy('chapter_id')->get();
    foreach ($data['chapter_info'] as $key=> $value){
      $data['chapter_num'][$key] = $all_chapter->where('chapter_id',$value->id)->first()?$all_chapter->where('chapter_id',$value->id)->first()->visit_count:0;
    }

    $data['book_id'] = $id;

    $data['book_info'] = BaiduNewDaan::where('book_id',$id)->where($where_now)->select(DB::raw("DATE_FORMAT(FROM_UNIXTIME(date_now),'%Y-%m-%d') as date_now"),DB::raw('sum(visit_count) as visit_count'),DB::raw('sum(visitor_count) as visitor_count'),DB::raw('sum(new_visitor_count) as new_visitor_count'),DB::raw('sum(ip_count) as ip_count'),DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(bounce_ratio) as bounce_ratio'))->orderBy('date_now','asc')->groupBy('date_now')->get()->toArray();
    //访问次数,访客数,新访客数,ip数,跳出率,平均访问时长
    //填充0???????
    if((($this->other['end']-$this->other['start'])/86400+1)>count($data['book_info'])){
      $days = ($this->other['end']-$this->other['start'])/86400+1 ;
      for($i=0;$i<$days;$i++){
        $now_date = date('Y-m-d',$this->other['start']+$i*86400);
        $data['all_date'][] = $now_date;
        if(collect($data['book_info'])->where('date_now',$now_date)->count()==0){
          $data['need_fill']['date_now'] = $now_date;
          $data['need_fill']['visit_count'] = 0;
          $data['need_fill']['visitor_count'] = 0;
          $data['need_fill']['new_visitor_count'] = 0;
          $data['need_fill']['ip_count'] = 0;
          $data['need_fill']['avg_visit_time'] = 0;
          $data['need_fill']['bounce_ratio'] = 0;
          $data['book_info'] = collect($data['book_info'])->push($data['need_fill']);
        }
      }
      $data['book_info'] = collect($data['book_info'])->sortBy('date_now');
    }else{
      $data['book_info'] = collect($data['book_info'])->sortBy('date_now');
    }
    return view('baidu.book_detail',compact('data'));
  }

  //试题详情
  public function shiti_detail($type,$md5id,$time=0)
  {
    $this->time_about($time);
    $data = $this->data;
    $where_now = $this->where;

    $data['shiti_md5id'] = $md5id;
    $data['shiti_type'] = $type;
    $now_table = 'baidu_new_'.$type;
    $data['shiti_info'] = DB::connection('mysql_local')->table($now_table)->where(['shiti_id'=>$md5id,'shiti_type'=>$type])->where($where_now)->select(DB::raw("DATE_FORMAT(FROM_UNIXTIME(date_now),'%Y-%m-%d') as date_now"),DB::raw('sum(visit_count) as visit_count'),DB::raw('sum(visitor_count) as visitor_count'),DB::raw('sum(new_visitor_count) as new_visitor_count'),DB::raw('sum(ip_count) as ip_count'),DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(bounce_ratio) as bounce_ratio'))->orderBy('date_now','asc')->groupBy('date_now')->get();
    return view('baidu.shiti_detail',compact('data'));
  }

  //习题详情
  public function xiti_detail($table,$id,$time=0)
  {
    $this->time_about($time);
    $data = $this->data;
    $where_now = $this->where;

    if($table==='mo_cotimu' || $table==='mo_cotimu2'){
      $where['timu_id'] = $id;
      $now_table = 'baidu_new_timu';
    }elseif($table==='mo_cotimu3'){
      $where['timu3_id'] = $id;
      $now_table = 'baidu_new_timu3';
    }else if($table==='testpaper'){
      $where['xiti_id'] = $id;
      $now_table = 'baidu_new_xiti';
    }else{
      die('错误类型');
    }
    $data['xiti_type'] = $table;
    $data['xiti_id'] = $id;

    $data['xiti_info'] = DB::connection('mysql_local')->table($now_table)->where($where)->where($where_now)->select(DB::raw("DATE_FORMAT(FROM_UNIXTIME(date_now),'%Y-%m-%d') as date_now"),DB::raw('sum(visit_count) as visit_count'),DB::raw('sum(visitor_count) as visitor_count'),DB::raw('sum(new_visitor_count) as new_visitor_count'),DB::raw('sum(ip_count) as ip_count'),DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(bounce_ratio) as bounce_ratio'))->orderBy('date_now','asc')->groupBy('date_now')->get();
    return view('baidu.xiti_detail',compact('data'));
  }

  //文章列表
  public function portal($time=0)
  {
    $this->time_about($time);
    $data = $this->data;
    $where = $this->where;
    $where[] = ['portal_id','>',0];

    $data['total'] = DB::connection('mysql_local')->table('baidu_new_qx_portal')->where($where)->select('portal_id', DB::raw('sum(visit_count) as all_pv'), DB::raw('sum(visitor_count) as all_uv'), DB::raw('sum(new_visitor_count) as new_uv'), DB::raw('avg(new_visitor_ratio) as new_visitor_ratio'), DB::raw('sum(ip_count) as ip_count'), DB::raw('sum(out_pv_count) as out_pv_count'), DB::raw('avg(bounce_ratio) as bounce_ratio'), DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(avg_visit_pages) as avg_visit_pages'),DB::raw('min(url) as url'))->groupBy('portal_id')->orderBy('all_pv', 'desc')->paginate(10);
    $all_id= $data['total']->pluck('portal_id')->toArray();
    $data['all_descript'] = DB::connection('mysql_main')->table('seo_portal_doc_1')->whereIn('id', $all_id)->select('id','title','des')->get();
    $data['all_content'] = DB::connection('mysql_main')->table('seo_portal_doc_content_1')->whereIn('id', $all_id)->select('id','content')->get();
    return view('baidu.portal', compact('data'));
  }

  //文章详情
  public function portal_detail($id,$time=0)
  {
    $this->time_about($time);
    $data = $this->data;
    $where_now = $this->where;
    $data['portal_id'] = $id;
    $data['portal_info'] = DB::connection('mysql_local')->table('baidu_new_qx_portal')->where('portal_id',$id)->where($where_now)->select(DB::raw("DATE_FORMAT(FROM_UNIXTIME(date_now),'%Y-%m-%d') as date_now"),DB::raw('sum(visit_count) as visit_count'),DB::raw('sum(visitor_count) as visitor_count'),DB::raw('sum(new_visitor_count) as new_visitor_count'),DB::raw('sum(ip_count) as ip_count'),DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(bounce_ratio) as bounce_ratio'))->orderBy('date_now','asc')->groupBy('date_now')->get();
    return view('baidu.portal_detail',compact('data'));
  }

  //通用时间设置
  protected function time_about($time){
    $old_day = date('Y_m_d',time()-86400);
    $now_day = date('Y_m_d',time());

    if($time===0){
      $time = $old_day.'__'.$now_day;
    }
    $now = explode('__', $time);
    if (!is_array($now) || count($now) != 2) {
      die('错误日期');
    }

    $start = strtotime(str_replace('_', '/', $now[0]));
    $end = strtotime(str_replace('_', '/', $now[1]));
    $this->data['start'] = str_replace('_', '/', $now[0]);
    $this->data['end'] = str_replace('_', '/', $now[1]);
    $this->data['time_range'] = str_replace('/','_', $this->data['start']).'__'.str_replace('/','_', $this->data['end']);
    $this->data['min'] = date('Y-m-d', BaiduNewDaan::min('date_now'));
    $this->data['max'] = date('Y-m-d', BaiduNewDaan::max('date_now'));
    $this->where[] = ['date_now', '>=', $start];
    $this->where[] = ['date_now', '<=', $end];
    $this->other['start'] = $start;
    $this->other['end'] = $end;
  }
}
