<?php

namespace App\Http\Controllers\HomeworkManage;

use App\PreMHomework;
use App\PreMHomeworkWorkplace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeworkApiController extends Controller
{
    public function index($action='index',Request $request)
    {
      $R = ['status'=>0,'msg'=>'请求失败'];
      switch ($action){
        case 'num':
          $data['square_num'] = PreMHomework::where(['is_del'=>0,'check_status'=>0])->count();
          $data['work_num'] = PreMHomeworkWorkplace::where(['status'=>0])->count();
          $data['done_num'] = PreMHomeworkWorkplace::where(['status'=>1])->count();
          $data['recycle_num'] = PreMHomework::where(['is_del'=>1])->count();
          $R = ['status'=>1,'data'=>$data];
          break;

        case 'move':
          $hid = intval($request->hid);
          $type = $request->type;
          if($type==='workspace'){
            PreMHomework::where(['id'=>$hid])->update(['check_status'=>1]);
            $new['hid'] = $hid;
            $new['uid'] = \Auth::id();
            PreMHomeworkWorkplace::create($new);
            $R = ['status'=>1,'msg'=>'成功'];
          }else if($type==='recycle'){
            PreMHomework::where(['id'=>$hid])->update(['is_del'=>1]);
            $new['hid'] = $hid;
            $new['uid'] = \Auth::id();
            $new['status'] = 2;
            PreMHomeworkWorkplace::create($new);
            $R = ['status'=>1,'msg'=>'成功'];
          }else if($type==='square'){
            PreMHomework::where(['id'=>$hid])->update(['is_del'=>0,'check_status'=>0]);
            PreMHomeworkWorkplace::where('hid',$hid)->delete();
            $R = ['status'=>1,'msg'=>'成功'];
          }else{
            abort(404);
          }

          break;
      }
      return response()->json($R);
    }
}
