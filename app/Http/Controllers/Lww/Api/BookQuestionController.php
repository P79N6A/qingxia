<?php

namespace App\Http\Controllers\Lww\Api;

use App\LwwBookQuestion;
use App\LwwPreAExercise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookQuestionController extends Controller
{

  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $this->now_uid = Auth::id();
      return $next($request);
    });
  }

  //获取对应题目及答案
  public function page_question(Request $request)
  {
    $this->validate($request, [
      'bookid' => 'required|integer',
      'chapterid' => 'required|integer',
      'pageid' => 'required|integer',
    ]);
    $book_id = intval($request->get('bookid'));
    $chapter_id = intval($request->get('chapterid'));
    $page_id = intval($request->get('pageid'));
    $question = LwwBookQuestion::where(['bookid' => $book_id, 'chapterid' => $chapter_id, 'pageid' => $page_id])->select(['uid', 'timuid', 'question_type', 'question', 'answer','answer_normal','analysis','remark'])->get();
    if ($question->count() > 0) {
      $resp = ['status' => 1, 'msg' => '获取成功', 'questions' => $question];
    } else {
      $resp = ['status' => 0, 'msg' => '暂无对应答案'];
    }
    return response()->json($resp);
  }


  protected function is_repeat_workbook_question($chapterid, $timuid, $uid)
  {
    $re = LwwBookQuestion::where(['chapterid' => $chapterid, 'timuid' => $timuid, 'uid' => $uid])->select('id')->count();
    return $re;
  }


  //保存题目及答案
  public function question_about(Request $request)
  {

    $resp = ['status' => 0, 'msg' => '操作失败'];
    $type = $request->get('type');
    $book_id = $request->get('bookid');
    $chapter_id = $request->get('chapterid');
    $timu_id = $request->get('timuid');
    $pageid = $request->get('pageid');
    switch ($type) {
      case 't_save':
        $question = $request->get('question');
//        $question = preg_replace('/\<span class=\"answer_now\">(.*?)\<\/span\>/', '<span class="answer_now">&nbsp;</span>', $question);
        $question_type = intval($request->get('question_type'));
        if($question_type===1 || $question_type===2 || $question_type===3){
          $answer_new = $request->get('answer_new');
        }else{
          $answer_preg = preg_match_all('/<span class=\"answer_now\">(.*?)\<\/span\>/', $question,$answer_now);
          if($question_type===4){
            $answer_new = collect($answer_now[1])->toJson();
          }else{
            $answer_new = $request->get('answer');
          }
        }

        $answer = $request->get('answer');
        $analysis = $request->get('analysis');
        $remark = $request->get('remark');
        $now_timu = LwwBookQuestion::where(['bookid' => $book_id, 'chapterid' => $chapter_id, 'timuid' => $timu_id]);

        if($now_timu->count()>0){
          $updates = $now_timu->update(['question' => $question, 'question_type'=>$question_type, 'answer' => $answer,'answer_new'=>$answer_new,'analysis'=>$analysis,'remark'=>$remark]);
        }else{
          $updates = LwwBookQuestion::insert(['bookid'=>$book_id,'chapterid'=>$chapter_id,'timuid'=>$timu_id,
            'uid'=>$this->now_uid,'question_type'=>$question_type,'question'=>$question,
            'answer'=>$answer,
              'answer_new'=>$answer_new,
            'analysis'=>$analysis,
            'remark'=>$remark,
            'pageid'=>$pageid,
          ]);
        }
        $resp = ['status' => 1, 'msg' => '操作成功'];
        break;

      case 'addtoWorkbook':

        $ret['bookid'] = intval($request->get('bookid'));
        $ret['chapterid'] = intval($request->get('chapterid'));
        $ret['timuid'] = $request->get('timuid');
        $ret['uid'] = $this->now_uid;
        $ret['question'] = $request->get('question');
        $ret['answer'] = $request->get('answer');
        $ret['qtype'] = $request->get('qtype');
        $ret['pageid'] = intval($request->get('pageid'));

        if (!in_array($ret['qtype'], range(1, 5))) return 0;
        if ($this->is_repeat_workbook_question($ret['chapterid'], $ret['timuid'], $ret['uid'])) echo 8;
        else {

          $save = LwwBookQuestion::insert([
            'bookid' => $ret['bookid'],
            'chapterid' => $ret['chapterid'],
            'timuid' => $ret['timuid'],
            'uid' => $this->now_uid,
            'question_type' => $ret['qtype'],
            'question' => $ret['question'],
            'answer' => $ret['answer'],
            'pageid'=>$ret['pageid']
          ]);

          if ($save) {
//            $t_count=intval($_GET['t_count']);
//            db_query("update pre_plugin_workbook_know set t_count=$t_count where id=".$ret['chapterid']);
            $resp = ['status'=>1,'msg'=>'操作成功'];
          }
        }
        break;
      case 'changeQuestionType':
        $timuid = $request->get('timuid');
        $questionType = $request->get('question_type');
        if(in_array($questionType,range(1,5))) {
          $updated = LwwBookQuestion::where('timuid',$timuid)->update(['question_type'=>$questionType]);
          if($updated){
            $resp = ['status'=>1,'msg'=>'操作成功'];
          }
        }
        break;

      case 'add_ans':
        $timuid=$request->get('timuid');
        $chapterid=intval($request->get('chapterid'));
        //$question=mysql_escape_string(myutf2unicode($_POST['question']));
        $data['answer']=$request->get('answer');
        if($request->get('uni_answer')) {
          $data['answer_normal'] = $request->get('uni_answer');
        }
        if($request->get('analysis')) {
          $data['analysis'] = $request->get('analysis');
        }
        if($request->get('remark')){
          $data['remark'] = $request->get('remark');
        }

        $updated = LwwBookQuestion::where(['chapterid'=>$chapter_id,'timuid'=>$timuid,'uid'=>$this->now_uid])->update($data);

        if($updated){
          $resp = ['status'=>1,'msg'=>'操作成功'];
        }
        break;

      case 't_del':
        $chapter_id=intval($request->get('chapterid'));
        $timuid = $request->get('timuid');
        $now_timu = LwwBookQuestion::where(['chapterid'=>$chapter_id,'timuid'=>$timuid,'uid'=>$this->now_uid]);
        $re = $now_timu->count();

        if($re==1)
        {
          $qid=$now_timu->first()->id;
          if(LwwBookQuestion::where(['id'=>$qid])->delete())
          {
            $resp = ['status'=>1,'msg'=>'操作成功'];
          }
        }
      break;

      case 'baidu_search':
        $word = $request->get('word');
        $s = file_get_contents('http://baidu.1010jiajiao.com/cse/search?q='.$word.'&click=1&s=128433640961804148&nsid=');
        return $s;
        break;

      case 'lww_search':
        $word = $request->get('word');
        $about = LwwPreAExercise::where('title','like','%'.$word.'%')
          ->orWhere('question','like','%'.$word.'%')
          ->orWhere('answer','like','%'.$word.'%')
          ->select('id','question','answer')
          ->take(20)
          ->get();
        if(count($about)>0){
          $resp = ['status'=>1,'msg'=>'操作成功','data'=>$about];
        }
        break;
      default:
        break;
    }
    return response()->json($resp);

  }
}
