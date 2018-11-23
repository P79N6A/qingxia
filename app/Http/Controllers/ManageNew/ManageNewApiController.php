<?php

namespace App\Http\Controllers\ManageNew;

use App\AWorkbookMain;
use App\AWorkbookNew;
use App\Sort;
use App\Subsort;
use App\Workbook;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ManageNewApiController extends Controller
{
    public $request;
    public $now_uid;
    public function __construct(Request $request)
    {
      $this->request = $request;
      $this->middleware(function ($request, $next) {
        $this->now_uid = Auth::id();
        return $next($request);
      });
    }

  public function index($type)
    {
      $R = [];
      switch ($type){
        case 'get_latest_data':
          $latest_data = AWorkbookNew::select()->take(20)->orderBy('id','desc')->get();
          $R = ['status'=>1,'msg'=>'请求成功','data'=>$latest_data];
          break;
        case 'get_search_bar':
          $type = $this->request->get('search_type');
          $where = [];
          if($this->request->grade_now){
            $where['grade_id'] = intval($this->request->grade_now);
          }
          if($this->request->subject_now){
            $where['subject_id'] = intval($this->request->subject_now);
          }
          if($this->request->volume_now){
            $where['volumes_id'] = intval($this->request->volume_now);
          }
          if($this->request->version_now){
            $where['version_id'] = intval($this->request->version_now);
          }

          if($type==='grade'){
            $all_data = AWorkbookNew::select('grade_id',DB::raw('count(grade_id) as num'))->where($where)->groupBy('grade_id')->get();
          }else if($type==='subject'){
            $all_data = AWorkbookNew::select('subject_id',DB::raw('count(subject_id) as num'))->where($where)->groupBy('subject_id')->get();
          }else if($type==='volume'){
            $all_data = AWorkbookNew::select('volumes_id',DB::raw('count(volumes_id) as num'))->where($where)->groupBy('volumes_id')->get();
          }else if($type==='version'){
            $all_data = AWorkbookNew::select('version_id',DB::raw('count(version_id) as num'))->where($where)->groupBy('version_id')->get();
          }else{
            $all_data = AWorkbookNew::select('sort',DB::raw('count(sort) as num'))->where($where)->groupBy('sort')->take(80)->orderBy('num','desc')->get();
          }

          $R = ['status'=>1,'msg'=>'请求成功','data'=>$all_data];

          break;

        case 'get_book_info':
          $where = [];
          if(isset($this->request->grade_now)){
            $where['grade_id'] = intval($this->request->grade_now);
          }
          if(isset($this->request->subject_now)){
            $where['subject_id'] = intval($this->request->subject_now);
          }
          if(isset($this->request->volume_now)){
            $where['volumes_id'] = intval($this->request->volume_now);
          }
          if(isset($this->request->version_now)){
            $where['version_id'] = intval($this->request->version_now);
          }
          $all_data = AWorkbookNew::where($where)->select()->take(100)->orderBy('id','desc')->get();
          $R = ['status'=>1,'msg'=>'请求成功','data'=>$all_data];

      }
      return response()->json($R);
    }

  public function workbook_new($type)
    {
      $R = ['status'=>0,'msg'=>'操作失败'];
      switch ($type){
        case 'sort':
          $word = $this->request->get('word');
          if($word==''){
            $R = ['status'=>0,'msg'=>'获取失败'];
          }else{
            $sorts = Sort::where('name','like','%'.$word.'%')->select('id','name')->get();
            $R = ['status'=>1,'msg'=>'获取成功','items'=>$sorts];
          }
          break;
        case 'add_name':
          $data_type = $this->request->data_type;
          if($data_type==='sub_sort'){
            $data['sort'] = intval($this->request->sort);
            $data['sub_sort_name'] = $this->request->sub_sort_name;
            $sort_info = cache('all_sort_now')->find($data['sort']);
            $has_sub_sort_name = Subsort::where('pid',$data['sort'])->where('name',trim($data['sub_sort_name']))->count();
            if(count($sort_info)>0 && $has_sub_sort_name==0){
              $new['name'] = $data['sub_sort_name'];
              $new['mainword'] = $sort_info->mainword;
              $new['pid'] = $sort_info->id;
              $new['pname'] = $sort_info->name;
              $new_sub = Subsort::create($new);
              if($new_sub){
                $R = ['status'=>1,'msg'=>'新增成功','data'=>['new_id'=>$new_sub->id]];
              }
            }elseif(count($sort_info)==0 && $has_sub_sort_name==0){
              if($data['sort']>0){
                $new['id'] = $data['sort'];
              }
              $new['name'] = $data['sub_sort_name'];
              $new_sort = Sort::create($new);
              $sub['pid'] = $new_sort->id;
              $sub['name'] = $data['sub_sort_name'];
              $sub['pname'] = $data['sub_sort_name'];
              $new_sub = Subsort::create($sub);
              $R = ['status'=>1,'msg'=>'新增成功','data'=>['new_sort'=>$new_sort->id,'new_id'=>$new_sub->id]];
            }
          }else{
            $data['id'] = $this->request->book_id;
            if($data_type==='volumes_name'){
              $data_type = 'volume_name';
            }
            $new[$data_type] = $this->request->add_name;
            $has_update = AWorkbookMain::where('id',$data['id'])->update($new);
            if($has_update){
              Cache::forget('all_'.$data_type);

              $R = ['status'=>1,'msg'=>'新增成功','data'=>['new_id'=>7777777]];
            }
          }

          break;

        case 'update_sub_sort':
          //查询a_workbook_new有无记录  无则新增
          $info_arr = [
            'subject_id'=>$this->request->subject_id,
            'grade_id'=>$this->request->grade_id,
            'volumes_id'=>$this->request->volumes_id,
            'version_id'=>$this->request->version_id,
            'sort'=>$this->request->sort_id,
            'ssort_id' => $this->request->subsort_id
          ];
          if(AWorkbookMain::where($info_arr)->where(['redirect_id'=>0,'version_year'=>$this->request->version_year,'subject_name'=>$this->request->subject_name,'volume_name'=>$this->request->volumes_name])->where('hdid','>','0')->where('status','<>',3)->count()>1){
            if(AWorkbookMain::where('redirect_id',$this->request->book_id)->count()==0){
              return response()->json(['status'=>0,'msg'=>'有重复数据,请重新检查并保存']);
            }
          }


          $new_count = AWorkbookNew::where($info_arr)->count();
          if($new_count==0){
            $now_book = AWorkbookMain::find($this->request->book_id);

            $new['bookname'] = $this->request->book_name;
            $new['newname'] = $now_book->bookname;
            $new['version_year'] = $this->request->version_year;
            $new['isbn'] = $now_book->isbn;
            $new['grade_id'] = $this->request->grade_id;
            $new['subject_id'] = $this->request->subject_id;
            $new['volumes_id'] = $this->request->volumes_id;
            $new['version_id'] = $this->request->version_id;
            $new['version_year'] = $this->request->version_year;;
            $new['sort'] = $this->request->sort_id;
            $new['book_confirm'] = $now_book->book_confirm;
            $new['grade_name'] = $this->request->grade_name;
            $new['subject_name'] = $this->request->subject_name;
            $new['volume_name'] = $this->request->volumes_name;
            $new['version_name'] = $this->request->version_name;
            $new['sort_name'] = '';
            $new['ssort_id'] = $this->request->subsort_id;
            AWorkbookNew::create($new);

            //bookname newname isbn grade_id subject_id volumes_id version_id version_year status sort bookconfirm
            //grade_name subject_name volumes_name version_name sort_name ssort_id
          }
          //更新a_workbook_1010  中 ssort_id
          $info_arr['bookname'] = $this->request->book_name;
          $info_arr['grade_name'] = $this->request->grade_name;
          $info_arr['subject_name'] = $this->request->subject_name;
          $info_arr['volume_name'] = $this->request->volumes_name;
          $info_arr['version_name'] = $this->request->version_name;
          $info_arr['ssort_id'] = $this->request->subsort_id;
          $info_arr['version_year'] = $this->request->version_year;
          $info_arr['update_uid'] = $this->now_uid;
          $info_arr['updated_at'] = date('Y-m-d H:i:s',time());

          if(AWorkbookMain::where('id',$this->request->book_id)->update($info_arr)){
            $R = ['status'=>1,'msg'=>'操作成功','data'=>[]];
          }
          break;

        case 'refresh_sub_sort':
          $sort = intval($this->request->sort);
          $sub_sort = Subsort::where('pid',$sort)->select('id','name as text')->get();
          if(count($sub_sort)>0){
            $R = ['status'=>1,'msg'=>'获取成功','data'=>$sub_sort];
          }else{
            $R = ['status'=>0,'msg'=>'获取失败'];
          }
          break;

        case 'get_uid_status':
          //获取uid对应处理情况
          $now_uid = intval($this->request->now_uid);
          $data = AWorkbookMain::where('update_uid',$now_uid)->select()->orderBy('updated_at','desc')->paginate(20);
          if(count($data)>0){
            $R = ['status'=>1,'msg'=>'获取成功','data'=>$data];
          }else{
            $R = ['status'=>0,'msg'=>'获取失败'];
          }
          break;

        case 'subsort_operate':
          $type = $this->request->data_type;
          $subsort_id = $this->request->subsort_id;
          if($type==='rename'){
            $data['name'] = $this->request->new_name;
            $up = Subsort::where('id',$subsort_id)->update($data);
            if($up){
              $R = ['status'=>1,'msg'=>'修改成功','data'=>$data];
            }else{
              $R = ['status'=>0,'msg'=>'修改失败','data'=>$data];
            }
          }elseif($type==='delete'){
            if(Subsort::where(['id'=>$subsort_id,'pid'=>$subsort_id])->count()>0){
              $R = ['status'=>0,'msg'=>'该系列为主系列不能删除'];
            }else{
              //取出主系列sort
              $sort = Subsort::where('id',$subsort_id)->first(['pid']);
              $new['ssort_id'] = $sort->pid;
              Subsort::where('id',$subsort_id)->delete();
              AWorkbookMain::where('ssort_id',$subsort_id)->update($new);
              AWorkbookNew::where('ssort_id',$subsort_id)->update($new);
              $R = ['status'=>1,'msg'=>'删除成功'];
            }
          }elseif ($type==='move'){
            if(Subsort::where(['id'=>$subsort_id,'pid'=>$subsort_id])->count()>0){
              $R = ['status'=>0,'msg'=>'该系列为主系列不能移动'];
            }else {
              $new_ssort_id = intval($this->request->new_subsort_id);
              $new['ssort_id'] = $new_ssort_id;
              Subsort::where('id', $subsort_id)->delete();
              AWorkbookMain::where('ssort_id', $subsort_id)->update($new);
              AWorkbookNew::where('ssort_id', $subsort_id)->update($new);
              $R = ['status' => 1, 'msg' => '删除成功'];
            }
          }else{
            $R = ['status'=>0,'msg'=>'操作失败'];
          }
          break;
          //删除
        case 'del_this':
          $book_id = intval($this->request->book_id);
          $now_uid = $this->now_uid;
          $hide = AWorkbookMain::where('id',$book_id)->update(['status'=>3,'update_uid'=>$now_uid]);
          if($hide){
            $R = ['status'=>1,'msg'=>'操作成功'];
          }else{
            $R = ['status'=>0,'msg'=>'操作失败'];
          }
          break;
          //跳转
        case 'bind_redirect':
          $book_id = intval($this->request->book_id);
          $bind_id = intval($this->request->bind_id);
          $now_uid = $this->now_uid;
          $redirect = AWorkbookMain::where('id',$book_id)->update(['redirect_id'=>$bind_id,'update_uid'=>$now_uid]);
          if($redirect){
            $R = ['status'=>1,'msg'=>'操作成功'];
          }else{
            $R = ['status'=>0,'msg'=>'操作失败'];
          }
          break;

        case 'change_cover':
          $book_id = intval($this->request->book_id);
          $cover_photo = $this->request->cover_photo;
          $change = AWorkbookMain::where('id',$book_id)->update(['cover'=>$cover_photo]);
          if($change){
            $R = ['status'=>1,'msg'=>'操作成功'];
          }else{
            $R = ['status'=>0,'msg'=>'操作失败'];
          }
          break;

        case 'confirm_chapter':
          $book_id = intval($this->request->book_id);
          $now_uid = $this->now_uid;
          $chapter_confirm = AWorkbookMain::where('id',$book_id)->update(['chapter_status'=>1,'update_uid'=>$now_uid]);
          if($chapter_confirm){
            $R = ['status'=>1,'msg'=>'操作成功'];
          }else{
            $R = ['status'=>0,'msg'=>'操作失败'];
          }
          break;
          //新增系列名
        case 'add_sort_name':
          $sort['id'] = $this->request->sort_id;
          $sort['name'] = $this->request->sort_name;
          if(Sort::create($sort)){
            $R = ['status'=>1,'msg'=>'操作成功'];
          }else{
            $R = ['status'=>0,'msg'=>'操作失败'];
          }
      }
      return response()->json($R);
    }

}
