<?php

namespace App\Http\Controllers\ManageNew;

use App\ABookKnow;
use App\AWorkbookMain;
use App\AWorkbookNew;
use App\BookVersionType;
use App\Sort;
use App\Subsort;
use App\User;
use App\Volume;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ManageNewController extends Controller
{
    public function index($type='unfinished',$sort=-999){
      $uid = \Auth::id();
      $number = -1;
//      if($uid===5){
//        $number = 0;
//      }elseif($uid===8){
//        $number = 1;
//      }elseif($uid===11){
//        $number = 2;
//      }else{
//        $number = -1;
//      }
      $whereRaw = "w.id>0";
      if($type==='unfinished'){
        if($number!=-1){
          $whereRaw = "w.id%3=$number";
        }
      }

      $data['all_grade_name'] = Cache::rememberForever('all_grade_name', function (){
          return AWorkbookMain::select(DB::raw('distinct grade_name as grade_name'))->get();
      });
      $data['all_subject_name'] = Cache::rememberForever('all_subject_name', function (){
        return AWorkbookMain::select(DB::raw('distinct subject_name as subject_name'))->get();
      });
      $data['all_volume_name'] = Cache::rememberForever('all_volume_name', function (){
        return AWorkbookMain::select(DB::raw('distinct volume_name as volume_name'))->get();
      });
      $data['all_version_name'] = Cache::rememberForever('all_version_name', function (){
        return AWorkbookMain::select(DB::raw('distinct version_name as version_name'))->get();
      });

      $data['all_version'] = Cache::remember('all_version', 120, function () {
        return BookVersionType::all(['id', 'name','press_name','press_alias','district']);
      });

      $data['all_volumes'] = Cache::remember('all_volumes', 120, function (){
        return Volume::all(['id', 'volumes']);
      });

      foreach ($data['all_version'] as $key=>$value){
          $version_array[$key]['id'] = $value->id;
          $version_array[$key]['text'] = $value->name;
      }
      foreach ($data['all_volumes'] as $key=>$value){
        $volume_array[$key]['id'] = $value->id;
        $volume_array[$key]['text'] = $value->volumes;
      }
      foreach (config('workbook.grade') as $key=> $value){
        if($key>0){
          $grade_array[$key-1]['id'] = $key;
          $grade_array[$key-1]['text'] = $value;
        }
      }
      foreach (config('workbook.subject_1010') as $key=> $value){
        if($key>0){
          $subject_array[$key-1]['id'] = $key;
          $subject_array[$key-1]['text'] = $value;
        }
      }
      foreach ($data['all_grade_name'] as $key=>$value){
        if($value){
          $grade_name_array[$key]['id'] = $key;
          $grade_name_array[$key]['text'] = $value->grade_name;
        }
      }
      foreach ($data['all_subject_name'] as $key=>$value){
        if($value){
          $subject_name_array[$key]['id'] = $key;
          $subject_name_array[$key]['text'] = $value->subject_name;
        }
      }
      foreach ($data['all_volume_name'] as $key=>$value){
        if($value){
          $volume_name_array[$key]['id'] = $key;
          $volume_name_array[$key]['text'] = $value->volume_name;
        }
      }
      foreach ($data['all_version_name'] as $key=>$value){
        if($value){
          $version_name_array[$key]['id'] = $key;
          $version_name_array[$key]['text'] = $value->version_name;
        }
      }
      $data['version_select'] = json_encode($version_array);
      $data['subject_select'] = json_encode($subject_array);
      $data['grade_select'] = json_encode($grade_array);
      $data['volume_select'] = json_encode($volume_array);
      $data['grade_name_select'] = json_encode($grade_name_array);
      $data['subject_name_select'] = json_encode($subject_name_array);
      $data['volume_name_select'] = json_encode($volume_name_array);
      $data['version_name_select'] = json_encode($version_name_array);

      $data['type'] = $type;

      if($sort!=-999){
        $where[] = ['w.sort',intval($sort)];
      }else{
        //$where[] = ['w.sort','>',0];
        $where[] = ['w.sort','>',-999];
      }
      $data['sort'] = $sort;

      if($data['type']==='unfinished'){
        $where[] = ['w.update_uid',0];
        $where[] = ['w.id','>',224452];
        $order = 'w.sort asc, w.grade_id asc, w.subject_id asc, w.volumes_id asc, w.version_id asc';
      }else{
        $where[] = ['w.update_uid','=',$uid];
        $order =  'w.updated_at desc';
      }
      if(isset($_GET['edit_id'])){
        $now_id = intval($_GET['edit_id']);
        $where[] = ['w.id','>',224452];
        $where[] = ['w.id',$now_id];
      }
      $where[] = ['w.status','<>',3];
      $where[] = ['w.hdid','>',0];
      $data['now_sort'] = AWorkbookMain::where(['redirect_id'=>0])->where('version_id',24)->where('hdid','>',0)->select('sort',DB::raw('count(*) as num'),DB::raw('sum(case when update_uid > 0 then 1 else 0 end) arranged_num'),DB::raw('sum(case when update_uid = 0 then 1 else 0 end) not_num'))->with('has_sort:id,name')->groupBy('sort')->orderBy('num', 'desc')->get();



      $data['all_book'] = AWorkbookMain::from('a_workbook_1010_main as w')->leftJoin('a_workbook_new as n',function ($join){
        $join->on('w.grade_id','=','n.grade_id');
        $join->on('w.subject_id','=','n.subject_id');
        $join->on('w.volumes_id','=','n.volumes_id');
        $join->on('w.version_id','=','n.version_id');
        $join->on('w.sort','=','n.sort');
        $join->on('w.ssort_id','=','n.ssort_id');
      })->where($where)->where(['w.redirect_id'=>0])->whereRaw("$whereRaw")->select(['w.id','w.bookname','w.version_year','w.cover','w.grade_id','w.subject_id','w.volumes_id','w.version_id','w.sort','w.ssort_id','w.grade_name','w.subject_name','w.version_name','w.volume_name','w.hdid','w.update_uid','w.updated_at'])->distinct()->with('has_hd_book:id,cover_photo')->with(['has_editor:id,name','has_sort:id,name','has_sort.sub_sorts:id,pid,name'])->orderByRaw($order)->paginate('10');

//      dd($data['all_book'] = AWorkbookMain::from('a_workbook_1010_main as w')->leftJoin('a_workbook_new as n',function ($join){
//        $join->on('w.grade_id','=','n.grade_id');
//        $join->on('w.subject_id','=','n.subject_id');
//        $join->on('w.volumes_id','=','n.volumes_id');
//        $join->on('w.version_id','=','n.version_id');
//        $join->on('w.sort','=','n.sort');
//        $join->on('w.ssort_id','=','n.ssort_id');
//      })->where($where)->where(['w.not_only'=>1])->whereRaw("$whereRaw")->select([DB::raw('distinct w.id'),'w.bookname','w.version_year','w.cover','w.grade_id','w.subject_id','w.volumes_id','w.version_id','w.sort','w.ssort_id','w.grade_name','w.subject_name','w.version_name','w.volume_name','w.hdid','w.update_uid','w.updated_at',DB::raw('concat(w.grade_id,"|",w.subject_id,"|",w.volumes_id,"|",w.version_id) as all_order')])->with('has_hd_book:id,cover_photo')->with(['has_editor:id,name','has_sort:id,name','has_sort.sub_sorts:id,pid,name'])->orderByRaw($order)->toSql());
//      dd($data['all_book']);

      return view('manage_new.new',compact('data'));
    }

    public function status($uid=0)
    {
//      $data['user'] = AWorkbookMain::from('a_workbook_1010_main as a')
//        ->join('users','a.update_uid','users.id')->where('update_uid','>',0)->select(DB::raw('distinct update_uid'),'name')->get();
      $data['user'] = AWorkbookMain::from('a_workbook_1010_main as a')
        ->join('users','a.update_uid','users.id')->where('update_uid','>',0)->where('not_only',1)->where('update_uid','>',0)->select(DB::raw('distinct update_uid'),'name')->get();
      $uid_where[] = ['not_only',1];
      if($uid===0) {
        $uid_where[] = ['update_uid','>', 0];
      }else {
        $uid_where[] = ['update_uid', $uid];
      }
      $uid_where[] = ['a.status','<>',3];
        $data['all_record'] = AWorkbookMain::from('a_workbook_1010_main as a')
          ->join('subsort1 as s',function ($join){
            $join->on('s.id','=','a.ssort_id');
            $join->on('s.pid','=','a.sort');
        })
          ->join('book_version_type as b','b.id','a.version_id')
          ->join('users as u','u.id','a.update_uid')
          ->where($uid_where)->select(['a.id','a.bookname','a.cover','a.grade_id','a.grade_name','a.subject_name','a.volume_name','a.version_name as a_version_name','a.subject_id','a.version_id','a.volumes_id','a.sort','a.ssort_id','b.name as version_name','s.name as sub_sort_name','s.pname as sort_name','a.updated_at','u.name as username','u.id as uid'])->orderBy('updated_at','desc')->paginate(20);
      return view('manage_new.status',compact('data'));
    }

    //子系列首页
    public function sort_index(Request $request)
    {

//      $data['all_sort'] = Sort::where('id','>','1331')->select(['id','name'])->with('sub_sorts:id,pid,name')->withCount(['sub_sorts','about_books'])->take(100)->orderBy('about_books_count','desc')->paginate(10);
      //dd($data_now);

//      $data = Sort::select('id','name')->with('sub_sorts:id,pid,name')->withCount('has_books')->orderBy('has_books_count','desc')->paginate(10);
////      foreach ($data as $key=>$value){
////        $data[$key]['sub_sorts'] = Subsort::select('id','name')->withCount('has_books')->orderBy('has_books_count','desc')->get();
////      }
//      dd($data);

//Cache::flush();

      $page = intval($request->page);
      if($request->flush==1){
        Cache::flush();
      }
      $data['all_sort'] = Cache::remember('now_'.$page, 30, function (){
        $now_cache_data = Sort::select(['sort.id','sort.name'])->with('sub_sorts:subsort1.id,subsort1.pid,subsort1.name')->withCount('sub_sorts')->with('sub_sorts.has_books:a_workbook_1010_main.id,a_workbook_1010_main.ssort_id')->orderBy('sort.id','asc')->paginate(10);
        foreach ($now_cache_data as $key=>$value){
          if(count($value->sub_sorts)>0){
            foreach ($value->sub_sorts as $key1 => $value1){
              $now_cache_data[$key]->sub_sorts[$key1]['num'] = $value1->has_books()->count();
            }
          }
        }
        return $now_cache_data;
      });
//      dd($data['all_sort'][1]->sub_sorts[0]->has_books()->count());
      return view('manage_new.sort_index',compact('data'));
    }
    //子系列内页
    public function subsort_arrange($sort=0,$sub_sort=0){
      $data['sort_now'] = $sort;
      $data['sub_sort_now'] = $sub_sort;
      $data['all_sort_books'] = Sort::where('id',$sort)->select(['id','name','main_word'])->get();
      if(count($data['all_sort_books'])==0){
        die('无该系列');
      }
      $data['all_grade_name'] = Cache::rememberForever('all_grade_name', function (){
        return AWorkbookMain::select(DB::raw('distinct grade_name as grade_name'))->get();
      });
      $data['all_subject_name'] = Cache::rememberForever('all_subject_name', function (){
        return AWorkbookMain::select(DB::raw('distinct subject_name as subject_name'))->get();
      });
      $data['all_volume_name'] = Cache::rememberForever('all_volume_name', function (){
        return AWorkbookMain::select(DB::raw('distinct volume_name as volume_name'))->get();
      });
      $data['all_version_name'] = Cache::rememberForever('all_version_name', function (){
        return AWorkbookMain::select(DB::raw('distinct version_name as version_name'))->get();
      });

      $data['all_version'] = Cache::remember('all_version', 120, function () {
        return BookVersionType::all(['id', 'name','press_name','press_alias','district']);
      });

      $data['all_volumes'] = Cache::remember('all_volumes', 120, function (){
        return Volume::all(['id', 'volumes']);
      });

      foreach ($data['all_version'] as $key=>$value){
        $version_array[$key]['id'] = $value->id;
        $version_array[$key]['text'] = $value->name;
      }
      foreach ($data['all_volumes'] as $key=>$value){
        $volume_array[$key]['id'] = $value->id;
        $volume_array[$key]['text'] = $value->volumes;
      }
      foreach (config('workbook.grade') as $key=> $value){
        if($key>0){
          $grade_array[$key-1]['id'] = $key;
          $grade_array[$key-1]['text'] = $value;
        }
      }
      foreach (config('workbook.subject_1010') as $key=> $value){
        if($key>0){
          $subject_array[$key-1]['id'] = $key;
          $subject_array[$key-1]['text'] = $value;
        }
      }
      foreach ($data['all_grade_name'] as $key=>$value){
        if($value){
          $grade_name_array[$key]['id'] = $key;
          $grade_name_array[$key]['text'] = $value->grade_name;
        }
      }
      foreach ($data['all_subject_name'] as $key=>$value){
        if($value){
          $subject_name_array[$key]['id'] = $key;
          $subject_name_array[$key]['text'] = $value->subject_name;
        }
      }
      foreach ($data['all_volume_name'] as $key=>$value){
        if($value){
          $volume_name_array[$key]['id'] = $key;
          $volume_name_array[$key]['text'] = $value->volume_name;
        }
      }
      foreach ($data['all_version_name'] as $key=>$value){
        if($value){
          $version_name_array[$key]['id'] = $key;
          $version_name_array[$key]['text'] = $value->version_name;
        }
      }
      $data['version_select'] = json_encode($version_array);
      $data['subject_select'] = json_encode($subject_array);
      $data['grade_select'] = json_encode($grade_array);
      $data['volume_select'] = json_encode($volume_array);
      $data['grade_name_select'] = json_encode($grade_name_array);
      $data['subject_name_select'] = json_encode($subject_name_array);
      $data['volume_name_select'] = json_encode($volume_name_array);
      $data['version_name_select'] = json_encode($version_name_array);

      $data['now_sort_books'] = $data['all_sort_books'][0]->has_books()->with('has_hd_book:id,cover_photo')->where(function ($query) use($sub_sort,$sort){
        if($sub_sort>0){
          $query->where('sort',$sort);
          $query->where('ssort_id',$sub_sort);
        }
      })->select('id','bookname','cover','grade_id','grade_name','subject_id','subject_name','volumes_id','volume_name','version_id','version_name','hdid','sort','ssort_id','updated_at','version_year')->paginate(20);
      $data['all_sub_sort'] = Subsort::from('subsort1 as s')->where('pid',$sort)->leftJoin('a_workbook_1010_main as m',function ($join){
        $join->on('m.ssort_id','=','s.id');
        $join->on('m.sort','=','s.pid');
      })->select('s.id','s.name',DB::raw('count(s.id) as num'))->groupBy('s.id','s.name')->get();
      return view('manage_new.sort_detail',compact('data'));
    }

    //唯一化首页
    public function only(Request $request,$subject=1)
    {
      $data['subject'] = intval($subject)>0?intval($subject):1;
      //$user = $request->user();
      $page = intval($request->page);
      if($request->flush===1){
        Cache::flush();
      }
      $data['books'] = Cache::remember('now_only_'.$data['subject'].'_'.$page, 30, function () use($subject){
        return AWorkbookNew::from('a_workbook_new as n')->where('n.subject_id', intval($subject))->where('n.status','<>',3)->select(['n.id', 'n.sort', 'n.ssort_id', 'n.grade_id', 'n.subject_id', 'n.volumes_id', 'n.version_id',DB::raw('count(*) as num')])->join('a_workbook_1010_main as m', function ($join) {
          $join->on('n.sort', '=', 'm.sort');
          $join->on('n.subject_id', '=', 'm.subject_id');
          $join->on('n.grade_id', '=', 'm.grade_id');
          $join->on('n.volumes_id', '=', 'm.volumes_id');
          $join->on('n.version_id', '=', 'm.version_id');
          $join->on('n.ssort_id', '=', 'm.ssort_id');
        })->where('m.status','<>',3)
          ->where('m.hdid','>',0)
          ->with('has_sub_sort:id,name,pname')
          ->with('has_version:id,name')
          ->groupBy(['n.id', 'n.sort', 'n.ssort_id', 'n.grade_id', 'n.subject_id', 'n.volumes_id', 'n.version_id'])->orderBy('num', 'desc')->paginate('10');
      });

      //return $books;

      return view('manage_new.only_index',compact('data'));

    }

    //唯一化内页
    public function only_detail($sort,$sub_sort,$grade_id,$subject_id,$volumes_id,$verison_id,$version_year=0)
    {
      $query['sort'] = intval($sort);
      $query['ssort_id'] = intval($sub_sort);
      $query['grade_id'] = intval($grade_id);
      $query['subject_id'] = intval($subject_id);
      $query['volumes_id'] = intval($volumes_id);
      $query['version_id'] = intval($verison_id);
      $data['version_year_now'] = $version_year;
      $data['version'] = BookVersionType::all(['id','name']);

      $data['all_grade_name'] = Cache::rememberForever('all_grade_name', function (){
        return AWorkbookMain::select(DB::raw('distinct grade_name as grade_name'))->get();
      });
      $data['all_subject_name'] = Cache::rememberForever('all_subject_name', function (){
        return AWorkbookMain::select(DB::raw('distinct subject_name as subject_name'))->get();
      });
      $data['all_volume_name'] = Cache::rememberForever('all_volume_name', function (){
        return AWorkbookMain::select(DB::raw('distinct volume_name as volume_name'))->get();
      });
      $data['all_version_name'] = Cache::rememberForever('all_version_name', function (){
        return AWorkbookMain::select(DB::raw('distinct version_name as version_name'))->get();
      });

      $data['all_version'] = Cache::remember('all_version', 120, function () {
        return BookVersionType::all(['id', 'name','press_name','press_alias','district']);
      });
      $data['all_volumes'] = Cache::remember('all_volumes', 120, function (){
        return Volume::all(['id', 'volumes']);
      });

      foreach ($data['all_version'] as $key=>$value){
        $version_array[$key]['id'] = $value->id;
        $version_array[$key]['text'] = $value->name;
      }
      foreach ($data['all_volumes'] as $key=>$value){
        $volume_array[$key]['id'] = $value->id;
        $volume_array[$key]['text'] = $value->volumes;
      }
      foreach (config('workbook.grade') as $key=> $value){
        if($key>0){
          $grade_array[$key-1]['id'] = $key;
          $grade_array[$key-1]['text'] = $value;
        }
      }
      foreach (config('workbook.subject_1010') as $key=> $value){
        if($key>0){
          $subject_array[$key-1]['id'] = $key;
          $subject_array[$key-1]['text'] = $value;
        }
      }
      foreach ($data['all_grade_name'] as $key=>$value){
        if($value){
          $grade_name_array[$key]['id'] = $key;
          $grade_name_array[$key]['text'] = $value->grade_name;
        }
      }
      foreach ($data['all_subject_name'] as $key=>$value){
        if($value){
          $subject_name_array[$key]['id'] = $key;
          $subject_name_array[$key]['text'] = $value->subject_name;
        }
      }
      foreach ($data['all_volume_name'] as $key=>$value){
        if($value){
          $volume_name_array[$key]['id'] = $key;
          $volume_name_array[$key]['text'] = $value->volume_name;
        }
      }
      foreach ($data['all_version_name'] as $key=>$value){
        if($value){
          $version_name_array[$key]['id'] = $key;
          $version_name_array[$key]['text'] = $value->version_name;
        }
      }
      $data['version_select'] = json_encode($version_array);
      $data['subject_select'] = json_encode($subject_array);
      $data['grade_select'] = json_encode($grade_array);
      $data['volume_select'] = json_encode($volume_array);
      $data['grade_name_select'] = json_encode($grade_name_array);
      $data['subject_name_select'] = json_encode($subject_name_array);
      $data['volume_name_select'] = json_encode($volume_name_array);
      $data['version_name_select'] = json_encode($version_name_array);

      $data['books'] = AWorkbookMain::from('a_workbook_1010_main as m')->where($query)->where('status','<>',3)->where('hdid','>',0)->Leftjoin('subsort1 as s', 's.id', 'm.ssort_id')
        ->Leftjoin('book_version_type as b', 'b.id', 'm.version_id')->select(['m.id','m.hdid','m.bookcode','m.bookname','m.cover','m.cover_photo','m.redirect_id','m.cover_photo_thumbnail','m.version_year','m.collect_count','m.grade_id','m.subject_id','m.volumes_id','m.version_id','m.grade_name','m.subject_name','m.version_name','m.volume_name','m.isbn','m.sort','m.version_year','m.ssort_id','s.name as sub_sort_name','s.pname as sort_name','b.name as version_name'])->with('has_hd_book:id,cover_photo')->with(['has_answers'=>function ($query){
          $query->select('id','book','text','textname','answer')->orderBy('text','asc');
        }])->orderBy('m.version_year','desc')->get();



      $data['group_by_year'] = collect($data['books'])->groupBy('version_year');


      
      
      $data['all_sub_sort'] = Subsort::where('pid',$query['sort'])->select('id','name')->get();

      return view('manage_new.only_detail',compact('data'));
    }

    //根据章节
    public function chapter_info($grade_search=1,$subject_search=1,$volume_search=-1,$version_search=-1,$type='unfinished')
    {
      $uid = \Auth::id();
      $number = -1;
//      if($uid===5){
//        $number = 0;
//      }elseif($uid===8){
//        $number = 1;
//      }elseif($uid===11){
//        $number = 2;
//      }else{
//        $number = -1;
//      }
      $whereRaw = "w.id>0";
      if($type==='unfinished'){
        if($number!=-1){
          $whereRaw = "w.id%3=$number";
        }
      }

      $data['all_grade_name'] = Cache::rememberForever('all_grade_name', function (){
        return AWorkbookMain::select(DB::raw('distinct grade_name as grade_name'))->get();
      });
      $data['all_subject_name'] = Cache::rememberForever('all_subject_name', function (){
        return AWorkbookMain::select(DB::raw('distinct subject_name as subject_name'))->get();
      });
      $data['all_volume_name'] = Cache::rememberForever('all_volume_name', function (){
        return AWorkbookMain::select(DB::raw('distinct volume_name as volume_name'))->get();
      });
      $data['all_version_name'] = Cache::rememberForever('all_version_name', function (){
        return AWorkbookMain::select(DB::raw('distinct version_name as version_name'))->get();
      });

      $data['all_version'] = Cache::remember('all_version', 120, function () {
        return BookVersionType::all(['id', 'name','press_name','press_alias','district']);
      });

      $data['all_volumes'] = Cache::remember('all_volumes', 120, function (){
        return Volume::all(['id', 'volumes']);
      });

      $data['grade_search'] = $grade_search;
      $data['subject_search'] = $subject_search;
      $data['volume_search'] = $volume_search;
      $data['version_search'] = $version_search;

      foreach ($data['all_version'] as $key=>$value){
        $version_array[$key]['id'] = $value->id;
        $version_array[$key]['text'] = $value->name;
      }
      foreach ($data['all_volumes'] as $key=>$value){
        $volume_array[$key]['id'] = $value->id;
        $volume_array[$key]['text'] = $value->volumes;
      }
      foreach (config('workbook.grade') as $key=> $value){
        if($key>0){
          $grade_array[$key-1]['id'] = $key;
          $grade_array[$key-1]['text'] = $value;
        }
      }
      foreach (config('workbook.subject_1010') as $key=> $value){
        if($key>0){
          $subject_array[$key-1]['id'] = $key;
          $subject_array[$key-1]['text'] = $value;
        }
      }
      foreach ($data['all_grade_name'] as $key=>$value){
        if($value){
          $grade_name_array[$key]['id'] = $key;
          $grade_name_array[$key]['text'] = $value->grade_name;
        }
      }
      foreach ($data['all_subject_name'] as $key=>$value){
        if($value){
          $subject_name_array[$key]['id'] = $key;
          $subject_name_array[$key]['text'] = $value->subject_name;
        }
      }
      foreach ($data['all_volume_name'] as $key=>$value){
        if($value){
          $volume_name_array[$key]['id'] = $key;
          $volume_name_array[$key]['text'] = $value->volume_name;
        }
      }
      foreach ($data['all_version_name'] as $key=>$value){
        if($value){
          $version_name_array[$key]['id'] = $key;
          $version_name_array[$key]['text'] = $value->version_name;
        }
      }
      $data['version_select'] = json_encode($version_array);
      $data['subject_select'] = json_encode($subject_array);
      $data['grade_select'] = json_encode($grade_array);
      $data['volume_select'] = json_encode($volume_array);
      $data['grade_name_select'] = json_encode($grade_name_array);
      $data['subject_name_select'] = json_encode($subject_name_array);
      $data['volume_name_select'] = json_encode($volume_name_array);
      $data['version_name_select'] = json_encode($version_name_array);

      $data['type'] = $type;

      $where[] = ['w.sort','>',0];

      $data['sort'] = -999;

      if($data['type']==='unfinished'){
        $where[] = ['w.chapter_status','=',0];
        $order = 'w.sort asc, w.grade_id asc, w.subject_id asc, w.volumes_id asc, w.version_id asc';
      }else{
//        $where[] = ['w.update_uid','=',$uid];
        $where[] = ['w.chapter_status','=',1];
        $order =  'w.updated_at desc';
      }
      $where[] = ['w.grade_id',$grade_search];
      $where[] = ['w.subject_id',$subject_search];





      if(isset($_GET['edit_id'])){
        $now_id = intval($_GET['edit_id']);
        $where[] = ['w.id',$now_id];
      }
      $where[] = ['w.status','<>',3];
      $where[] = ['w.hdid','>',0];



      $all_volume_version = AWorkbookMain::from('a_workbook_1010_main as w')->where(['not_only'=>3])->where($where)->whereRaw("$whereRaw")->select('volumes_id','version_id',DB::raw('count(version_id) as num'))->groupBy('volumes_id','version_id')->orderBy('volumes_id','asc')->orderBy('version_id','asc')->get();
      $data['all_volume_version'] = $all_volume_version->groupBy('volumes_id');
      if($version_search!=-1){
        $where[] = ['w.version_id',$version_search];
      }
      if($volume_search!=-1){
        $where[] = ['w.volumes_id',$volume_search];
      }
      if($version_search!=-1 && $volume_search!=-1){
        $grade_now_sort = $grade_search;
        $subject_now_sort = $subject_search;
        $volume_now_sort = $volume_search;
        $version_now_sort = $version_search;
        if(strlen($grade_search)==1){
          $grade_now_sort = '0'.$grade_search;
        }
        if(strlen($subject_search)==1){
          $subject_now_sort = '0'.$subject_search;
        }
        if(strlen($volume_search)==1){
          $volume_now_sort = '0'.$volume_search;
        }
        if(strlen($version_search)==1){
          $version_now_sort = '0'.$version_search;
        }
        $booksort = $version_now_sort.$subject_now_sort.$grade_now_sort.$volume_now_sort;

        $data['chapter_info'] = ABookKnow::where(['booksort'=>$booksort])->select('chapter','chaptername')->orderBy('chapter','asc')->get();
      }
      $data['all_book'] = AWorkbookMain::from('a_workbook_1010_main as w')->leftJoin('a_workbook_new as n',function ($join){
        $join->on('w.grade_id','=','n.grade_id');
        $join->on('w.subject_id','=','n.subject_id');
        $join->on('w.volumes_id','=','n.volumes_id');
        $join->on('w.version_id','=','n.version_id');
        $join->on('w.sort','=','n.sort');
        $join->on('w.ssort_id','=','n.ssort_id');
      })->where($where)->where(['w.not_only'=>3])->whereRaw("$whereRaw")->select(['w.id','w.bookcode','w.bookname','w.version_year','w.cover','w.grade_id','w.subject_id','w.volumes_id','w.version_id','w.sort','w.ssort_id','w.grade_name','w.subject_name','w.version_name','w.volume_name','w.hdid','w.update_uid','w.updated_at'])->distinct()->with('has_hd_book:id,cover_photo')->with(['has_editor:id,name','has_sort:id,name','has_sort.sub_sorts:id,pid,name'])->with(['has_answers'=>function ($query){ $query->select('id','book','text','textname','answer')->orderBy('text','asc'); }])->orderByRaw($order)->groupBy('w.id')->paginate('10');
      return view('manage_new.chapter',compact('data'));
    }
}
