<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OssController;
use App\Http\Controllers\WxController;
use App\PreMQuestion;
use App\PreMQuestionTeacherAnswer;
use App\PreMQuestionVoicePos;
use App\PreMQuestionWxVoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WxApiController extends Controller
{
    protected $wx_js;
    protected $access_token;
    protected $now_uid;
    public function __construct(){
      $wx_control = new WxController(config('weixin.APP_ID'),config('weixin.APP_SECRET'));
      $this->access_token = $wx_control->getAccessToken();
      $this->middleware(function ($request, $next) {
        $this->now_uid = Auth::id();
        return $next($request);
      });
    }

    public function download_img(Request $request)
    {
      $oss = new OssController();
      $media_id = $request->get('serverId');
      file_put_contents(public_path('images/teacher/test.txt'),'https://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id);
      $file_url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
      $content = file_get_contents($file_url);
      if(!is_dir(public_path('images/teacher/'.date('Ymd',time())))){
        mkdir(public_path('images/teacher/'.date('Ymd',time())));
      }
      $now_img = 'images/teacher/'.date('Ymd',time()).'/'.md5($content).'.jpg';
      if(file_put_contents(public_path($now_img), $content)){
        $oss->save($now_img,$content);
        exit(json_encode(array('status'=>1,'msg'=>'下载成功','img'=>$now_img)));
      }
      exit(json_encode(array('status'=>1,'msg'=>'下载成功','img'=>$now_img)));
    }

    public function update_img(Request $request)
    {
      $qid = intval($request->get('qid'));
      $img = implode('|',$request->get('img'));
      $re = PreMQuestionTeacherAnswer::where('qid',$qid)->update(['img'=>$img]);
      if($re){
        exit(json_encode(array('status'=>1,'msg'=>'上传成功')));
      }else{
        exit(json_encode(array('status'=>0,'msg'=>'上传失败')));
      }
    }

    public function upload_voice(Request $request)
    {
      ignore_user_abort(true);
      $oss = new OssController();
      foreach ($request->get('all_pos') as $value){
        $data_dir = date('Ymd', time());
        //left_pos,top_pos,voice_id
        $qid = intval($value['qid']);
        $voice_id = $value['voice_id'];
        //1 记录至pre_m_wx_voice
        $voice['voice_id'] = $voice_id;

        $voice['created_at'] = date('Y-m-d H:i:s', time());
        $new_voice = PreMQuestionWxVoice::create($voice);

        //2下载音频至服务器  更新pre_m_wx_voice
        if (!is_dir(public_path('voice/' . $this->now_uid . '/' . $data_dir))) {
          mkdir(public_path('voice/' . $this->now_uid . '/' . $data_dir), 0777, true);
        }
        $s = file_put_contents(public_path('voice/' . $this->now_uid . '/' . $data_dir . '/' . $new_voice->id . '.amr'), file_get_contents("http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$this->access_token}&media_id={$voice_id}"));
        if ($s) {
          $old_name = public_path('voice/' . $this->now_uid . '/' . $data_dir . '/' . $new_voice->id);
          if (is_file("{$old_name}.wav")) {
            unlink("{$old_name}.wav");
          }
          $str = "ffmpeg -i {$old_name}.amr {$old_name}.wav";
          PreMQuestionTeacherAnswer::where('qid', $qid)->update(['has_audio' => 1]);
          //3 记录坐标至 pre_m_question_wx_voice,更新pre_m_question_teacher_answer has_audio 为1
          if (system($str) == '') {
            $oss->save('voice/' . $this->now_uid . '/' . $data_dir . '/' . $new_voice->id.'.wav',file_get_contents($old_name.'.wav'));
            PreMQuestionWxVoice::where('voice_id',$voice_id)->update(['has_download'=>1]);
            $pos['qid'] = $qid;
            $pos['voice_id'] = $new_voice->id;
            $pos['voice_location'] = 'voice/' . $this->now_uid . '/' . $data_dir . '/' . $new_voice->id.'.wav';
            $pos['p_left'] = $value['p_left'];
            $pos['p_top'] = $value['p_top'];
            $pos['img'] = $value['now_img'];
            $pos['uid'] = $this->now_uid;
            PreMQuestionVoicePos::create($pos);
            PreMQuestionTeacherAnswer::where('qid', $pos['qid'])->update(['has_audio' => 1]);
          }
        }
      }
      $R = array('status'=>1,'msg'=>'发布成功');
      exit(json_encode($R));
    }
}
