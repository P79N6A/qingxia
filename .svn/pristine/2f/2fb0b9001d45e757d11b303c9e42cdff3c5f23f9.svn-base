<?php

namespace App\Http\Controllers\HomeworkManage;

use App\PreMHomework;
use App\PreMHomeworkWorkplace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeworkController extends Controller
{
    public function index($type='index')
    {
      $now_uid = \Auth::id();
      if ($type === 'index'){
        $data['zyq'] = PreMHomework::where(['is_del'=>0, 'check_status'=>0])->with(['has_user:uid,username', 'has_comments:id,hid,comment'])->paginate(20);
      }elseif ($type==='feedback'){

      }elseif($type==='workspace'){
        $data['zyq'] = PreMHomework::where(['is_del'=>0,'check_status'=>1])->with(['has_user:uid,username', 'has_comments:id,hid,comment'])->paginate(20);
      }elseif($type==='done'){
        $data['zyq'] = PreMHomeworkWorkplace::from('pre_m_homework_workplace as w')
          ->join('pre_m_homework as h', 'h.id', 'w.hid')
          ->join('users as u', 'u.id', 'w.uid')
          ->where('w.status', 1)
          ->where(function ($query) use($now_uid){
            if($now_uid!=2){
              $query->where('w.uid', $now_uid);
            }
          })
          ->select(['h.id as hid', 'h.descript', 'h.pic', 'h.add_time', 'w.uid as teacher_uid','u.name as teacher_name','w.created_at as added_at', 'w.updated_at'])
          ->orderBy('w.created_at', 'desc')
          ->paginate(10);
      }elseif($type==='recycle'){
        $data['zyq'] = PreMHomework::where('is_del', 1)->with(['has_user:uid,username', 'has_comments:id,hid,comment'])->paginate(20);
      }else{
        abort(404);
      }
      $data['type'] = $type;

      return view('homework_manage.index',compact('data'));
    }
}
