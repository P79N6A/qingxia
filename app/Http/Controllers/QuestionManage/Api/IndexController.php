<?php

namespace App\Http\Controllers\QuestionManage\Api;

use App\Http\Controllers\OssController;
use App\PreMQuestion;
use App\PreMQuestionFeedback;
use App\PreMQuestionOcr;
use App\PreMQuestionTeacherAnswer;
use App\PreMQuestionVoicePos;
use App\PreMQuestionWorkplace;
use App\PreMQuestionWxVoice;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

require_once app_path('Http/Controllers/Libs/baiduocr/AipOcr.php');
use AipOcr;

class IndexController extends Controller
{

    protected $now_uid;
    public function __construct()
    {
      $this->middleware(function($request,$next){
        $this->now_uid  = Auth::id();
        return $next($request);
      });
    }

  public function index(Request $request,$action)
  {
    $data = [];
    $now_uid = $this->now_uid;
    switch ($action) {
      case 'get_square':
        $data['question'] = PreMQuestion::from('pre_m_question as q')
          ->join('a_workbook_1010_main as a', 'q.book_id', 'a.id')
          ->where('book_id', '>', 0)
          ->where('check_status', 0)
          ->where('is_del', 0)
          ->select(['q.*', 'a.bookname'])
          ->orderBy('q.id', 'desc')
          ->paginate(20);
        break;
      case 'get_all_num':
        //反馈站数量
        $data['feedback_count'] = PreMQuestionFeedback::where(function ($query) use($now_uid){
          if($now_uid!=2){
            $query->where('uid',$now_uid);
          }
        })->count();
        $data['workspace_count'] = PreMQuestionWorkplace::where(function ($query) use($now_uid){
          if($now_uid!=2){
            $query->where('uid',$now_uid);
          }
          $query->where('status',0);
        })->count();
        $data['has_done_count'] = PreMQuestionWorkplace::where(function ($query) use($now_uid){
          if($now_uid!=2){
            $query->where('uid',$now_uid);
          }
          $query->where('status',1);
        })->count();
//        $data['recycle_count'] = PreMQuestionWorkplace::where(['uid' => $this->now_uid, 'status' => 2])->count();
        $data['recycle_count'] = PreMQuestion::where(['is_del' => 1])->count();
        break;
      case 'add_to_arrange':
        $ids = $request->get('check');
        $can_check = [];
        foreach ($ids as $id) {
          $id = intval($id);
          if (PreMQuestionWorkplace::where('qid', $id)->count()) {
            $data['has_checked'][] = $id;
          } else {
            $can_check['qid'][] = $id;
          }
        }
        $new['uid'] = $this->now_uid;
        if (empty($data['has_checked'])) {
          foreach ($can_check['qid'] as $qid) {
            $new['qid'] = $qid;
            PreMQuestion::where('id', $qid)->update(['check_status' => 1]);
            PreMQuestionWorkplace::create($new);
          }
          $data = ['status' => 1, 'msg' => '添加成功'];
        } else {
          if (!empty($can_check)) {
            foreach ($can_check['qid'] as $qid) {
              $new['qid'] = $qid;
              PreMQuestion::where('id', $qid)->update(['check_status' => 1]);
              PreMQuestionWorkplace::create($new);
            }
          }
          $data['msg'] = '部分题目添加失败,请刷新后重试';
          $data['status'] = 0;
          $data['checked'] = $can_check;
        }
        break;
      case 'add_to_workplace':
        $ids = $request->get('check');
        $can_check = [];
        foreach ($ids as $id) {
          $id = intval($id);
          if (PreMQuestionWorkplace::where('qid', $id)->count()) {
            $data['has_checked'][] = $id;
          } else {
            $can_check['qid'][] = $id;
          }
        }
        $new['uid'] = $this->now_uid;
        if (empty($data['has_checked'])) {
          foreach ($can_check['qid'] as $qid) {
            $new['qid'] = $qid;
            PreMQuestion::where('id', $qid)->update(['check_status' => 1]);
            PreMQuestionWorkplace::create($new);
          }
          $data = ['status' => 1, 'msg' => '添加成功'];
        } else {
          if (!empty($can_check)) {
            foreach ($can_check['qid'] as $qid) {
              $new['qid'] = $qid;
              PreMQuestion::where('id', $qid)->update(['check_status' => 1]);
              PreMQuestionWorkplace::create($new);
            }
          }
          $data['msg'] = '部分题目添加失败,请刷新后重试';
          $data['status'] = 0;
          $data['checked'] = $can_check;
        }
        break;
      case 'make_answer':
        $new['qid'] = intval($request->get('id'));
        $now['id'] = intval($request->get('edit_id'));
        $to_uid = PreMQuestion::find($new['qid'])->uid;
        $new['to_uid'] = $to_uid;
        $new['uid'] = $this->now_uid;
        $new['answer'] = $request->get('answer');
        $data = ['status' => 0, 'msg' => '回答失败'];
        if($now['id']>0){
          if (PreMQuestionTeacherAnswer::where($now)->update($new)) {
            $data = ['status' => 1, 'msg' => '回答成功'];
          }
        }else{
          if (PreMQuestionTeacherAnswer::create($new)) {
            PreMQuestionWorkplace::where('qid', $new['qid'])->update(['status' => 1]);
            //取html内容生成图片
            $data = ['status' => 1, 'msg' => '回答成功'];
          }
        }
        break;
      case 'html_to_pic':
        set_time_limit(0);
        ignore_user_abort();
        $qid = intval($request->get('qid'));
        $this->html_to_pic($qid);
        break;
      case 'del_to_recycle':
        $ids = $request->get('check');
        $new['uid'] = $this->now_uid;
        $new['status'] = 2;
        foreach ($ids as $qid) {
          $new['qid'] = intval($qid);
          if (PreMQuestion::where('id', $qid)->update(['is_del' => 1])) {
            PreMQuestionWorkplace::create($new);
          }
        }
        $data = ['status' => 1, 'msg' => '删除成功'];
        break;
      case 'show_workplace':
        $data['work_space'] = PreMQuestionWorkplace::from('pre_m_question_workplace as w')
          ->join('pre_m_question as q', 'q.id', 'w.qid')
          ->join('users as u', 'u.id', 'w.uid')
          ->where('w.status', 0)->where(function ($query) use($now_uid){
             if($now_uid!=2){
               $query->where('w.uid',$now_uid);
             }
            }
          )->select(['q.id', 'q.content', 'q.img','q.grade_id', 'q.created_at', 'w.uid as teacher_uid','u.name as teacher_name','w.created_at as added_at'])
          ->orderBy('w.created_at', 'desc')
          ->paginate(20);
        foreach ($data['work_space'] as $key=>$value){
          $data['work_space'][$key]['grade_name'] = config('workbook.grade')[$value->grade_id];
        }
        break;
      case 'show_feedback':
        $data['feedback'] = PreMQuestionFeedback::from('pre_m_question_feedback as f')
          ->join('pre_m_question as q', 'q.id', 'f.qid')
          ->join('pre_m_question_teacher_answer as a', 'f.qid', 'a.qid')
          ->join('users as u', 'u.id', 'f.uid')
          ->where(function ($query) use($now_uid){
            if($now_uid!=2){
              $query->where('f.uid',$now_uid);
            }
          })
          ->select(['f.id', 'q.id as qid', 'q.content', 'q.img', 'q.created_at', 'a.answer', 'f.feedback', 'f.solution', 'f.status', 'f.created_at as added_at'])
          ->orderBy('f.created_at', 'desc')
          ->paginate(20);
        break;
      case 'feedback_reply':
        $id = intval($request->get('id'));
        $solution = $request->get('solution');
        if (PreMQuestionFeedback::where('id', $id)->update(['solution' => $solution, 'status' => 1])) {
          $data = ['status' => 1, 'msg' => '回复成功'];
        } else {
          $data = ['status' => 0, 'msg' => '回复失败'];
        }
        break;
      case 'has_done':
        $data['has_done'] = PreMQuestionWorkplace::from('pre_m_question_workplace as w')
          ->join('pre_m_question as q', 'q.id', 'w.qid')
          ->join('pre_m_question_teacher_answer as t', 't.qid', 'w.qid')
          ->join('users as u', 'u.id', 'w.uid')
          ->where('w.status', 1)
          ->where(function ($query) use($now_uid){
            if($now_uid!=2){
              $query->where('w.uid', $this->now_uid);
            }
          })
          ->select(['q.id as qid', 'q.content', 'q.img', 'q.created_at', 't.answer','t.answer_pic','t.img as t_img','t.has_audio', 't.id','w.uid as teacher_uid','u.name as teacher_name','w.created_at as added_at', 'w.updated_at'])
          ->orderBy('w.created_at', 'desc')
          ->paginate(10);
        foreach ($data['has_done'] as $key=>$value){
          if($value->has_audio===1){
            $data['has_done'][$key]['audio_about'] = PreMQuestionVoicePos::where('qid',$value->id)->select('img','voice_location','p_left','p_top')->get();
          }
        }
        break;
      case 'show_recycle':
        $data['show_recycle'] = PreMQuestionWorkplace::from('pre_m_question_workplace as w')
          ->join('pre_m_question as q', 'q.id', 'w.qid')
          ->join('users as u', 'u.id', 'w.uid')
          ->where('w.status', 2)
          ->where('q.is_del', 1)
          ->select(['q.id', 'q.content', 'q.img', 'q.created_at', 'w.uid as teacher_uid','u.name as teacher_name','w.created_at as added_at'])
          ->orderBy('w.created_at', 'desc')
          ->paginate(20);
        break;
    }
    return response()->json($data);
  }

  protected function html_to_pic($qid)
  {
    $answer_now = PreMQuestionTeacherAnswer::where('qid',$qid)->first(['answer']);
    $css_now = asset('css/style.css');
    $html = "<html><meta charset='utf-8'><head><link type='text/css' rel='stylesheet' href=\"$css_now\"></head><body><div>$answer_now->answer</div></body></html>";
    file_put_contents(public_path('teacher_answer_html/').$qid.'.html',$html);
    $date_dir = date('Ymd',time());
    $now_dir = public_path("teacher_answer_pic/$this->now_uid/$date_dir/");
    if(!is_dir($now_dir)){
      mkdir($now_dir,0777,$recursive=true);
      chmod($now_dir, 0777);
    }
    if(PHP_OS=='WINNT'){
      $str = "cd C:\Users\qwerty\Desktop\CutyCapt-Win32-2010-04-26 && CutyCapt.exe --url=http://www.test2.com/teacher_answer_html/$qid.html --out=$now_dir$qid.png --zoom-factor=2.0 --min-height=50 --min-width=400";
    }else{
      $str = 'sudo /usr/bin/xvfb-run --server-args="-screen 0, 1027x768x24" /usr/bin/CutyCapt --url=http://ck3.1010jiajiao.com/teacher_answer_html/'.$qid.'.html --out='.$now_dir.'/'.$qid.'.png --min-height=50 --min-width=400';
    }
    $s = exec($str,$info,$return_val);
    if($return_val == 0){
      $oss = new OssController();
      $oss->save("teacher_answer_pic/$this->now_uid/$date_dir/$qid.png",file_get_contents(public_path("teacher_answer_pic/$this->now_uid/$date_dir/$qid.png")));
      $answer_pic = "teacher_answer_pic/$this->now_uid/$date_dir/$qid.png";
      PreMQuestionTeacherAnswer::where('qid',$qid)->update(['answer_pic'=>$answer_pic]);
    }
  }

  public function ocr_it(Request $request){

      $ret = $data = array();
      $data['status'] = 0;
      $ret['qid'] = $qid = intval($request->get('qid'));
      $ret['sort_id'] = $sort_id = intval($request->get('sort_id'));
      $now_width = intval($request->get('now_img_width'));
      $now_height = intval($request->get('now_img_height'));
      $ret['img'] = $now_pic = $request->get('now_src');
      $ret['uid'] = $this->now_uid;
      $size_info = getimagesize($now_pic);
      $im = imagecreatefromjpeg($now_pic);
      $real_width = $size_info[0];
      $real_height = $size_info[1];
      PreMQuestionOcr::where(['qid'=>$qid,'sort_id'=>$sort_id])->delete();
      $b = explode(',', $request->get('cuts'));
      $ocr_words = '';
      if (count($b) == 4) {
        $ret['pleft'] = round($b[0] / $now_width, 5);
        $ret['ptop'] = round($b[1] / $now_height, 5);
        $ret['pwidth'] = round($b[2] / $now_width, 5);
        $ret['pheight'] = round($b[3] / $now_height, 5);
        $new_img_width = $ret['pwidth'] * $real_width;
        $new_img_height = $ret['pheight'] * $real_height;
        $newim = imagecreatetruecolor($new_img_width, $new_img_height);
        $now = imagecopyresampled($newim, $im, 0, 0, $ret['pleft'] * $real_width, $ret['ptop'] * $real_height, $ret['pwidth'] * $real_width, $ret['pheight'] * $real_height, $new_img_width, $new_img_height);
        if (!$now) {
          $R['status'] = 0;
          return response()->json($R);
        }
        if (!is_dir(storage_path('app/public/all_ocr_pages/' . $qid . '/cut_pages/'))) {
          mkdir(storage_path('app/public/all_ocr_pages/' . $qid . '/cut_pages/'),0777,true);
        }
        $s = PreMQuestionOcr::create($ret);


        $new_pic = imagejpeg($newim, storage_path("app/public/all_ocr_pages/{$qid}/cut_pages/{$sort_id}.jpg"));
        if($new_pic){
          ignore_user_abort(true);
          $aipOcr = new AipOcr(config('workbook.APP_ID'), config('workbook.API_KEY'), config('workbook.SECRET_KEY'));
          $result = $aipOcr->webImage(file_get_contents(storage_path("app/public/all_ocr_pages/{$qid}/cut_pages/{$sort_id}.jpg")));

          if ($result['words_result_num'] > 0) {
            $ocr_words = collect($result['words_result'])->implode('words', ' ');
            $now_timu = file_get_contents('http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word=' . urlencode(mb_substr($ocr_words,0,12)));
            $data['ocr_word'] = $ocr_words;
            $data['now_timu'] = $now_timu;
          }else{
            $R['status'] = 0;
            return response()->json($R);
          }
//          $s->ocr_result = json_encode($result,JSON_UNESCAPED_UNICODE);
//          $s->search_result = $ret['search_result'];
          $s->save();
        }
      }
      $data['status'] = 1;
      return response()->json($data);
  }

  public function search_it(Request $request){
    $search_word = $request->get('word');
    $data['now_timu'] = file_get_contents("http://www.1010jiajiao.com/html5app/ajax/jsonp?a=search_timu&word=".ltrim($search_word));
    if($data['now_timu']!='[]'){
      $data['status'] = 1;
    }else{
      $data['status'] = 0;
    }
    return response()->json($data);
  }

  public function detail(Request $request,$id,$action){
    $id = intval($id);
    $data = [];
    switch ($action){
      case 'get_detail':
        $data['detail'] = PreMQuestion::from('pre_m_question as q')
          ->join('pre_m_question_workplace as w','q.id','w.qid')
          ->where('q.id',$id)->where('w.uid',$this->now_uid)
          ->where('w.status',0)
          ->first();
        break;
      case 'answer':
        $new['qid'] = $id;
        $new['uid'] = $this->now_uid;
        $new['answer'] = $request->get('answer');
        if(PreMQuestionTeacherAnswer::create($new)){
          PreMQuestionWorkplace::where('qid',$id)->update(['status'=>1]);
          $data = ['status'=>1,'msg'=>'回答成功'];
        }else{
          $data = ['status'=>0,'msg'=>'回答失败'];
        }
    }
    return response()->json($data);
  }

  public function save_pic_to_oss(Request $request)
  {
    $old_img = $request->get('old_img');
    $now_img = $request->get('now_img');
    $oss = new OssController();
    $oss->save($old_img,file_get_contents($now_img));
    exit(json_encode(array('status'=>1,'msg'=>'保存成功')));
  }

  public function move_to($type, Request $request)
  {
    $id = intval($request->id);
    if($type==='recycle'){
      PreMQuestion::where(['id'=>$id])->update(['is_del'=>1]);
      if(PreMQuestionWorkplace::where(['qid'=>$id])->update(['status'=>2])){
        exit(json_encode(array('status'=>1,'msg'=>'操作成功')));
      }
    }else if($type==='workspace'){
      PreMQuestionWorkplace::where(['qid'=>$id])->delete();
      if(PreMQuestion::where(['id'=>$id])->update(['check_status'=>0,'is_del'=>0])){
        exit(json_encode(array('status'=>1,'msg'=>'操作成功')));
      }
    }else{
      exit(json_encode(array('status'=>0,'msg'=>'操作错误')));
    }
  }
}
