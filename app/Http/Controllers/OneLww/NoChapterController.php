<?php

namespace App\Http\Controllers\OneLww;

use App\LwwModel\AThreadChapter;
use App\OneModel\PreForumPost;
use App\OneModel\PreForumThread;
use App\OnlineModel\AOnlyBook;
use App\OnlineModel\AWorkbook1010;
use App\OnlineModel\AWorkbookAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoChapterController extends Controller
{
    public function index($onlyid,$year,$volume,$bookid)
    {
        if($bookid<=0){
            die('请先选择关联练习册');
        }
        $data['onlyid'] = $onlyid;
        $data['year'] = $year;
        $data['volume_id'] = $volume;
        $data['bookid'] = $bookid;
        $data['only_detail'] = AOnlyBook::where(['onlyid'=>$onlyid])->select(['onlyid','thread_id','bookname','grade_id','subject_id','version_id'])->get();
        $data['all_single_answers'] = AWorkbookAnswer::where(['bookid'=>$bookid,'status'=>1])->orderBy('text','asc')->select('bookid','id','text','textname','answer')->get();

        //todo
        $data['book_detail'] = AWorkbook1010::where(['id'=>$bookid])->first(['id','bookname','cover','isbn']);

        //如果有旧版章节 取章节下的解析  此时暂无bookid的处理需要
        $all_chapter = AThreadChapter::where(['onlyid'=>$data['onlyid'],'year'=>$data['year'],'volume_id'=>$data['volume_id'],'status'=>0])->select('id','name');

        $all_tid = $all_chapter->pluck('id')->toArray();

        $all_old_post_html = PreForumPost::where('bookid','>',0)->whereIn('tid',$all_tid)->first(['bookid']);

        if($all_old_post_html && $data['bookid']!=$all_old_post_html->bookid){
            return redirect()->route('no_chapter_analysis_index',[$data['onlyid'],$data['year'],$data['volume_id'],$all_old_post_html->bookid]);
        }
        $data['all_analysis_answers'] = PreForumPost::where([['bookid',$bookid],['position',1],['invisible','>',-1]])->select(['pid','tid','bookid','page','message_html'])->orderBy('page','asc')->get();

        if(count($data['all_analysis_answers'])==0){
            $max_pid=PreForumPost::max('pid');
            foreach ($data['all_single_answers'] as $key => $answer){
                $new['pid']= $max_pid+$key+1;
                $new['message'] = '';
                $new['message_html'] = '<p><img src="'.config('workbook.thumb_image_url').$answer->answer.'" /></p>';
                $new['subject'] = $answer->textname;
                $new['tid'] = 0;
                $new['position']=1;
                $new['bookid'] = $answer->bookid;
                $new['page'] = $answer->text;
                PreForumPost::create($new);
            }
            $data['all_analysis_answers'] = PreForumPost::where([['bookid',$bookid],['position',1],['invisible','>',-1]])->select(['pid','tid','bookid','page','message_html'])->orderBy('page','asc')->get();
        }

        return view('one_lww.no_chapter',compact('data'));
    }


    public function ajax(Request $request,$type){
        switch ($type){
            case 'get_message':
                $pid=intval($request->now_pid);
                if($pid>0){
                    $message=PreForumPost::where(['pid'=>$pid])->first(['message_html']);
                    $last_message = $message?$message->message_html:'';
                }else{
                    $last_message = '';
                }
                $end_message = preg_replace_callback ('/src="(.*?)"/i',function($matches){
                    if(starts_with($matches[1], 'http://')){
                        return 'src="'.$matches[1].'"';
                    }else{
                        return 'src="http://www.05wang.com/'.$matches[1].'"';
                    }
                },$last_message);
                return return_json(['message_html'=>$end_message]);
                break;

            case 'save_message':
                $pid = $request->now_pid;
                $message_html = $request->message;
                if(PreForumPost::where(['pid'=>$pid])->update(['authorid'=>\Auth::id(),'message_html'=>$message_html])){
                    return return_json();
                }else{
                    return return_json_err(0,'请先添加页码');
                }
                break;

            case 'add_page':
                $bookid = $request->bookid;
                $max_pid = PreForumPost::max('pid');
                $max_page = PreForumPost::where(['bookid'=>$bookid])->orderBy('page','desc')->first(['pid','tid','position','bookid','page']);
                if($max_page){
                    $new['subject'] = '第'.($max_page->page+1).'页';
                    $new['tid'] = $max_page->tid;
                    $new['bookid'] = $max_page->bookid;
                    $new['page'] = $max_page->page+1;
                }else{
                    $new['subject'] = '第1页';
                    $new['tid'] = 0;
                    $new['bookid'] = intval($bookid);
                    $new['page'] = 1;
                }
                $new['pid']= $max_pid+1;
                $new['message'] = '';
                $new['message_html'] = '';

                $new['position']=1;
                $new['authorid'] = \Auth::id();


                if(PreForumPost::create($new)){
                    $return['pid'] = $new['pid'];
                    $return['page'] = $new['page'];
                    return return_json($return);
                }else{
                    return return_json_err();
                }
                break;

            case 'del_page':
                $data['bookid'] = intval($request->bookid);
                $data['pid'] = intval($request->now_pid);
                if(PreForumPost::where($data)->update(['authorid'=>\Auth::id(),'invisible'=>-1])){
                    return return_json();
                }else{
                    return return_json_err();
                }
                break;

            case 'update_page_index':
                $now_page_index = $request->get('page_index_box');
                try{
                    $all_pids = collect($now_page_index)->pluck(1)->toArray();
                    PreForumPost::whereIn('pid',$all_pids)->increment('page',1000);
                    foreach ($now_page_index as $key=>$value){
                        $data['page'] = $value[0];
                        $where['pid'] = $value[1];
                        PreForumPost::where($where)->update($data);
                    }
                }catch(\Exception $e){
                    var_dump($e);
                    return return_json_err();
                }
                return return_json();
        }
    }
}
