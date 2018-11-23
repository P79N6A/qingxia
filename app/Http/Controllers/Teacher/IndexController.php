<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\WxController;
use App\PreMQuestion;
use App\PreMQuestionTeacherAnswer;
use App\PreMQuestionVoicePos;
use App\PreMQuestionWorkplace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
  public function index()
  {
    return view('teacher.personal_center');
  }

  public function teacher_square()
  {
    $data['question'] = PreMQuestionWorkplace::from('pre_m_question_workplace as w')
      ->join('pre_m_question as q', 'q.id', 'w.qid')
      ->join('pre_m_question_teacher_answer as t', 't.qid', 'w.qid')
      ->where('w.status', 1)->where('w.uid', Auth::id())
      ->select(['q.id', 'q.content', 'q.img', 'q.created_at', 't.answer', 'w.created_at as added_at', 'w.updated_at'])
      ->orderBy('w.created_at', 'desc')
      ->paginate(20);
    return view('teacher.teacher_square', compact('data'));
  }

  public function teacher_question_detail($id)
  {
    $wx = new WxController(config('weixin.APP_ID'),config('weixin.APP_SECRET'));
    $wx->getAccessToken();
    $data['wx_js'] = $wx->getSignPackage();
    $id = intval($id);
    //取出问题 取出老师回答和追问
    $data['question'] = PreMQuestion::find($id);
    $data['answer_about'] = PreMQuestionTeacherAnswer::where(['qid'=>$id])
      ->select()->orderBy('created_at','ASC')->get();
    foreach ($data['answer_about'] as $key=>$value){
      if($value->has_audio===1){
        $data['answer_about'][$key]['voice'] = PreMQuestionVoicePos::where('qid',$value->qid)->select('voice_location')->get();
      }
    }
    return view('teacher.teacher_question_detail',compact('data'));
  }


  public function teacher_center()
  {
    return view('teacher.teacher_center');
  }

  public function teacher_pressed_about()
  {
    return view('teacher.teacher_pressed_about');
  }


  public function teacher_question_reply()
  {
    return view('teacher.teacher_question_reply');
  }

}
