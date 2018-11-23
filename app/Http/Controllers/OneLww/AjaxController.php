<?php

namespace App\Http\Controllers\OneLww;

use App\AWorkbook1010Zjb;
use App\Http\Controllers\OssController;
use App\OnlineModel\AOnlyBook;
use App\OneModel\AThreadChapter;
use App\OneModel\AWorkbook;
use App\OneModel\PreForumPost;
use App\OneModel\PreForumThread;
use App\OnlineModel\ATongjiHotBook;
use App\OnlineModel\AWorkbook1010;
use App\OnlineModel\ParttimeLog;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function update_onlybook_isbn($onlyid,$isbn)
    {
        $isbn = str_replace('-', '', $isbn);
        $isbn_arr = explode('|', $isbn);
        if(is_array($isbn_arr) && count($isbn_arr)>1){
            foreach ($isbn_arr as $isbn_single){
                $this->update_onlybook_isbn($onlyid,$isbn_single);
            }
        }else{
            $now_isbn = AOnlyBook::where(['onlyid'=>$onlyid])->first(['isbn']);
            if($now_isbn && strpos($now_isbn->isbn, $isbn)===false){
                AOnlyBook::where(['onlyid'=>$onlyid])->update(['isbn'=>$now_isbn->isbn.'|'.$isbn]);
            }
        }
    }


    protected function push_chapter($onlyid,$k,$v,$volumes_id,$pid,$level,$year)
    {
         //章节入库
        //$thread_id=AOnlyBook::where(['onlyid'=>$onlyid])->first(['a_thread_book_id']);
        $condition=[];
            //onlyid,name.num,parent_uid,lev,volume_id
        $condition['onlyid']=$onlyid;
        $condition['name']=$v['text'];
        $condition['num']=$k;
        $condition['uid']=\Auth::id();
        $condition['parent_id']=$pid;
        $condition['lev']=$level;
        $condition['year']=$year;
        $condition['volume_id']=$volumes_id;
        if(is_numeric($v['id'])){
            AThreadChapter::where(['id'=>$v['id']])
                ->update($condition);
            if($pid!=0){
                AThreadChapter::where(['id'=>$pid])->update(['ispost'=>0]);
            }
            $pid=$v['id'];
        }else{
            if(AThreadChapter::where(['name'=>$condition['name'],'parent_id'=>$condition['parent_id'],'year'=>$year,'volume_id'=>$condition['volume_id'],'onlyid'=>$condition['onlyid'],'status'=>0])->count()==0){
                $create=AThreadChapter::create($condition);
                if($pid!=0){
                    AThreadChapter::where(['id'=>$pid])->update(['ispost'=>0]);
                }
                $pid = $create->id;
            }
//            if($thread_id && $thread_id->thread_id>0){
//                $tid=PreForumThread::create([
//                    'subject'=>$v['text'],
//                    'authorid'=>\Auth::id(),
//                    'author'=>User::find(\Auth::id())->name,
//                    'lastpost'=>time()
//                ]);
                //$condition['thread_id']=$tid->tid;
               // $pid=AThreadChapter::create($condition);
//            }else{
//            $create=AThreadChapter::create($condition);
//            $pid = $create->id;
            //}
        }

        return $pid;
    }

    public function switch_action($type)
    {
        switch ($type){
            case 'onlyid_get':
                $word = $this->request->get('word');
                if($word==''){
                    exit(json_encode(array('status'=>0,'msg'=>'获取失败')));
                }else{
                    $onlyids = AOnlyBook::where('onlyid','like','%'.$word.'%')->select('id','onlyid as name')->get();
                    exit(json_encode(array('status'=>1,'msg'=>'获取成功','items'=>$onlyids)));
                }
                break;

            case 'add_sort':
//                $sort_name=$this->request->sort_name;
//                if($sort_name=='') return return_json_err(0,'系列名不能为空');
//                $tid=M('pre_forum_thread')->insert(['subject'=>$sort_name,'fromtable'=>'a_book'],1);
//                $re=M('a_xilie')->where(['name'=>$sort_name])->select();
//                if($re){
//                    M('a_xilie')->where(['name'=>$sort_name])->update(['status'=>0]);
//                }else{
//                    M('a_xilie')->insert(['id'=>$tid,'name'=>$sort_name]);
//                }
                break;
            case 'del_sort':
//                $id=II('id');
//                M('a_xilie')->where(['id'=>$id])->update(['status'=>3]);
                break;
            case 'update_sort':
//                $id=II('id');
//                $sort_name=trim(I('sort_name'));
//                if($sort_name=='') return msg::text('系列名不能为空');
//                M('a_xilie')->where(['id'=>$id])->update(['name'=>$sort_name]);
                break;

            case 'update_ssort':
                $only_id = $this->request->only_id;
                $ssort_id = $this->request->ssort_id;
                $ssort_name = $this->request->ssort_name;
                if(AOnlyBook::where(['onlyid'=>$only_id])->update(['ssort_id'=>$ssort_id,'ssort_name'=>$ssort_name])){
                    return return_json();
                }
                break;
            case 'search_bookname':
                $bookname=$this->request->word;
                $re=AOnlyBook::where([['bookname','like', '%".$bookname."%'],['status',0]])->select('id')->get();

                if(count($re)>0){
                    $str='';
                    foreach($re as $v){
                        $str.=$v->id.',';
                    }
                    $str=rtrim($str,',');
                    return return_json(['str'=>$str]);
                }else{
                    return return_json_err(0,'未找到');
                }
                break;

            case 'add_book':
//                $book=I('book');
//                if(intval($book['xilie_id'])==0) return msg::text('请选择正确的系列');
//                $fid=M('a_xilie')->where(['id'=>$book['xilie_id']])->selectVal('fid');
//                if(intval($book['id'])==0){
//                    $id=M('pre_forum_thread')->insert([
//                        'fid'=>$fid,
//                        'subject'=>$book['bookname'],
//                        'fromtable'=>'a_chapter'
//                    ],1);
//                    M('a_thread_book')->insert([
//                        'id'=>$id,
//                        'pid'=>$book['xilie_id'],
//                        'name'=>$book['bookname'],
//                        'subject_id'=>$book['subject_id'],
//                        'grade_id'=>$book['grade_id'],
//                        'book_version_id'=>$book['version_id'],
//                        'type'=>$book['type_id']
//                    ],1);
//                }else{
//                    M('a_thread_book')
//                        ->where(['id'=>$book['id']])
//                        ->update([
//                            'pid'=>$book['xilie_id'],
//                            'name'=>$book['bookname'],
//                            'subject_id'=>$book['subject_id'],
//                            'grade_id'=>$book['grade_id'],
//                            'book_version_id'=>$book['version_id'],
//                            'type'=>$book['type_id']
//                        ]);
//                }
                break;

            case 'del_book':
//                $id=II('id');
//                M('a_thread_book')->where(['id'=>$id])->update(['status'=>3]);
//                break;

            case 'getchapter':
                $book=AOnlyBook::where(['onlyid'=>$this->request->onlyid])->first(['onlyid','bookname']);
                $arr=[0=>['id'=>$book->onlyid,'parent'=>'#','text'=>$book->bookname]];
                $chapter=AThreadChapter::where([
                    'onlyid'=>$this->request->onlyid,
                    'volume_id'=>$this->request->volumes,
                    'year'=>$this->request->year,
                    'status'=>0])
                    ->select('id','name','parent_id')->orderBy('num','asc')->get();
                foreach($chapter as $k=>$v){
                    $arr[$k+1]['id']=$v->id;
                    if($v->parent_id==0){
                        $arr[$k+1]['parent']=$this->request->onlyid;
                    }else{
                        $arr[$k+1]['parent']=$v->parent_id;
                    }
                    $arr[$k+1]['text']=$v->name;
                }
                //print_r($arr);
                return response()->json($arr);
                break;

            case 'save_chapter':
                $onlyid=$this->request->onlyid;
                $volumes_id=intval($this->request->volumes_id);
                $year=intval($this->request->year);
                $chapter_data=$this->request->chapter_data;
                //print_r($chapter_data);die;
                /* $now_key_1 = 0;
                 $now_key_2 = 0;
                 $now_key_3 = 0;
                 $now_key_4 = 0;*/
                $pid = $pid2 = $pid3 = 0;
                foreach($chapter_data as $k=>$v){
                    if($k>0){
                        if($v['level']==2){
                            $pid=$this->push_chapter($onlyid,$k,$v,$volumes_id,0,$v['level']-2,$year);
                        }elseif($v['level']==3){
                            $pid2=$this->push_chapter($onlyid,$k,$v,$volumes_id,$pid,$v['level']-2,$year);
                        }elseif($v['level']==4){

                            $pid3=$this->push_chapter($onlyid,$k,$v,$volumes_id,$pid2,$v['level']-2,$year);
                        }elseif($v['level']==5){
                            $this->push_chapter($onlyid,$k,$v,$volumes_id,$pid3,$v['level']-2,$year);
                        }
                    }
                }
                break;

            case 'del_chapter':
                $chapter_arr=$this->request->chapter_arr;

                foreach($chapter_arr as $k=>$v){
                    AThreadChapter::where([['parent_id',$v],['parent_id','!=',0]])->update(['status'=>3]);
                    AThreadChapter::where(['id'=>$v])->update(['status'=>3]);
                }
                return return_json();
                break;

            case 'get_message':
                $id=intval($this->request->id);

                if($id>0){
                    $message=PreForumPost::where(['tid'=>$id,'position'=>1])->first(['message_html']);
                    $last_message = $message?$message->message_html:'';
                }else{
                    $last_message = '';
                }
                //$end_message = str_replace('src="data/', 'src="http://www.05wang.com/data/', $last_message);
                //$end_message = str_replace('src="/static/book/answer/', 'src="http://www.05wang.com/static/book/answer/', $last_message);

                $end_message = preg_replace_callback ('/src="(.*?)"/i',function($matches){
                    if(starts_with($matches[1], 'http://')){
                        return 'src="'.$matches[1].'"';
                    }else{
                        return 'src="http://www.05wang.com/'.$matches[1].'"';
                    }
                },$last_message);

//                $end_message = preg_replace('/src="([^http:\/\/].*?)"/i','src="http://www.05wang.com/$1"',$last_message);
                return return_json(['message_html'=>$end_message]);
                break;

            case 'save_message':
                $id=intval($this->request->id);
                //$volume_id=intval($this->request->volume_id);
                $message_html=str_replace('&amp;','&',$this->request->message);
                if($message='') return return_json_err(0,'内容为空');
                $max_pid=PreForumPost::max('pid');
                $re=PreForumPost::where(['tid'=>$id,'position'=>1])->select()->get();

                if($re->count()>0){
                    PreForumPost::where(['tid'=>$id,'position'=>1])->update(['message_html'=>$message_html]);
                }else{
                    $chaptername=AThreadChapter::where(['id'=>$id])->first(['name']);

                    PreForumPost::create([
                            'pid'=>$max_pid+1,
                            'message'=>'',
                            'message_html'=>$message_html,
                            'subject'=>$chaptername->name,
                            'tid'=>$id,
                            'position'=>1
                        ]);
                }
                AThreadChapter::where(['id'=>$id])->update(['ispost'=>1]);
                return return_json();
                break;

            case 'change_volumes':
                $id_arr=$this->request->id_arr;
                $type=$this->request->type;
                $old_volumes=intval($this->request->old_volumes);
                $onlyid=$this->request->onlyid;
                $change_volumes=intval($this->request->change_volumes);
                $year=intval($this->request->year);
                if(!$id_arr) return return_json_err(0,'请选择要修改的章节');
                if($type=='pid'){
                    AThreadChapter::where(['onlyid'=>$onlyid,'volume_id'=>$old_volumes,'year'=>$year])->update(['volume_id'=>$change_volumes,'uid'=>\Auth::id()]);
                }else{
                    foreach($id_arr as $k=>$v){
                        AThreadChapter::where(['id'=>$v,'volume_id'=>$old_volumes,'year'=>$year])->update(['volume_id'=>$change_volumes,'uid'=>\Auth::id()]);
                    }
                }
                return return_json();
                break;

            case 'start_edit':
//                $id=II('id');
//                M('a_thread_book')->where(['id'=>$id])->update(['uid'=>UID,'start_time'=>date('Y-m-d H:i:s')]);
                break;

            case 'end_edit':
//                $id=II('id');
//                M('a_thread_book')->where(['id'=>$id])->update(['end_time'=>date('Y-m-d H:i:s')]);
                break;

            case 'get_cover':
                $onlyid=$this->request->onlyid;
                $volume_id=intval($this->request->volume_id);
                $year=intval($this->request->year);
                $re=AWorkbook1010Zjb::where(['onlyid'=>$onlyid,'version_year'=>$year,'volumes_id'=>$volume_id])->first(['id','cover']);
                return return_json($re);
                break;

            case 'save_cover':
                //$formData=I();
//                $bookid=$this->request->bookid;
//                $fp=new \lib\upload();
//                if($fp->setAllowExt(['jpg','png','gif'])->isOK('myfile')){
//                    $ext=$fp->getFileExt();
//                    $cover_name=$fp->getMd5Name();
//                    $ossfile='pic19/'.$bookid.'/cover/'.$cover_name.".".$ext;
//                    $file=M_ROOT.'cache/'.$ossfile;
//                    $fp->saveToLocal($file);
//                    \lib\img::img2thumb($file,$file,1000);
//                    if(!defined("LOCAL")) \lib\oss::instance()->uploadfile($ossfile,$file,1);
//                    M("a_workbook_1010")
//                        ->where(["id"=>$bookid])
//                        ->update([
//                            "cover"=>M_PIC.$ossfile
//                        ]);
//                }
                break;

            case 'html_to_pic':
                $html_now = $this->request->html;
                $chapter_id = $this->request->chapter_id;
                $book_id = $this->request->book_id;
                $css_now = asset('css/style.css');
                $css_ueditor = asset('ueditor/themes/default/css/ueditor.css');
                $css_ueditor_iframe = asset('ueditor/themes/iframe.css');
                $now_html_dir = public_path("onelww/html/$book_id/");
                $now_pic_dir = public_path("onelww/pic/$book_id/");
                if(!is_dir($now_html_dir)){
                    mkdir($now_html_dir,0777,$recursive=true);
                    chmod($now_html_dir, 0777);
                }
                if(!is_dir($now_pic_dir)){
                    mkdir($now_pic_dir,0777,$recursive=true);
                    chmod($now_pic_dir, 0777);
                }
                $html = "<html><meta charset='utf-8'><head><link type='text/css' rel='stylesheet' href=\"$css_now\"><link type='text/css' rel='stylesheet' href=\"$css_ueditor\"><link type='text/css' rel='stylesheet' href=\"$css_ueditor_iframe\"></head><body><div>$html_now</div></body></html>";
                file_put_contents(public_path('onelww/html/').$book_id.'/'.$chapter_id.'.html',$html);
                if(PHP_OS=='WINNT'){
                    $str = "cd C:\Users\qwerty\Desktop\CutyCapt-Win32-2010-04-26 && CutyCapt.exe --url=http://www.test2.com/onelww/html/$book_id/$chapter_id.html --out=$now_pic_dir$chapter_id.png  --min-height=50 --min-width=1000";
                }else{
                    $str = 'sudo /usr/bin/xvfb-run --server-args="-screen 0, 1027x768x24" /usr/bin/CutyCapt --url=http://ck3.1010jiajiao.com/teacher_answer_html/'.$chapter_id.'.html --out='.$now_pic_dir.'/'.$chapter_id.'.png --min-height=50 --min-width=400';
                }
                $s = exec($str,$info,$return_val);
                if($return_val == 0){
                    return return_json(['now_img'=>"/onelww/pic/$book_id/$chapter_id.png"]);
//                    $oss = new OssController();
//                    $oss->save("teacher_answer_pic/$this->now_uid/$date_dir/$qid.png",file_get_contents(public_path("teacher_answer_pic/$this->now_uid/$date_dir/$qid.png")));
//                    $answer_pic = "teacher_answer_pic/$this->now_uid/$date_dir/$qid.png";
//                    PreMQuestionTeacherAnswer::where('qid',$qid)->update(['answer_pic'=>$answer_pic]);
                }
                break;


            //章节升级
            case 'upgrade_chapter':
                $onlyid = $this->request->onlyid;
                $version_year = $this->request->version_year;
                $volume = $this->request->volume;
                $to_year = $this->request->to_year;
                AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$to_year,'volume_id'=>$volume])->update(['status'=>3]);
                $old_chapter = AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$version_year,'volume_id'=>$volume,'status'=>0])->select('onlyid','name','num','parent_id','lev','volume_id')->orderBy('parent_id','asc')->get();
                foreach ($old_chapter as $chapter){

                    $data['onlyid'] = $chapter->onlyid;
                    $data['name'] = $chapter->name;
                    $data['num'] = $chapter->num;
                    if($chapter->parent_id==0){
                        $data['parent_id'] = $chapter->parent_id;
                    }else{
                        $old_parent_name = $old_chapter->where('id',$chapter->parent_id)->first()->name;
                        $data['parent_id'] = AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$to_year,'volume_id'=>$volume,'status'=>0,'name'=>$old_parent_name])->first(['id'])->id;
                    }
                    $data['lev'] = $chapter->lev;
                    $data['volume_id'] = $chapter->volume_id;
                    $data['uid'] = \Auth::id();
                    $data['year'] = $to_year;
                    AThreadChapter::create($data);
                }
                return return_json();
                break;

            //升级为课本章节
            case 'copy_book_chapter':
                $onlyid = $this->request->onlyid;
                $version_year = $this->request->version_year;
                $volume = $this->request->volume;
                $book_only_id = substr_replace(substr_replace($onlyid,'00000',0,5), '00', -2);

                $max_chapter_year = AThreadChapter::where(['onlyid'=>$book_only_id,'volume_id'=>$volume])->max('year');
                if($max_chapter_year){
                    AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$version_year,'volume_id'=>$volume])->update(['status'=>3]);
                    $book_chapter = AThreadChapter::where(['onlyid'=>$book_only_id,'year'=>$max_chapter_year,'volume_id'=>$volume,'status'=>0])->select('onlyid','name','num','parent_id','lev','volume_id')->orderBy('parent_id','asc')->get();
                    foreach ($book_chapter as $chapter){
                        $data['onlyid'] = $onlyid;
                        $data['name'] = $chapter->name;
                        $data['num'] = $chapter->num;
                        if($chapter->parent_id==0){
                            $data['parent_id'] = $chapter->parent_id;
                        }else{
                            $old_parent_name = $book_chapter->where('id',$chapter->parent_id)->first()->name;
                            $data['parent_id'] = AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$version_year,'volume_id'=>$volume,'status'=>0,'name'=>$old_parent_name])->first(['id'])->id;
                        }
                        $data['lev'] = $chapter->lev;
                        $data['volume_id'] = $volume;
                        $data['uid'] = \Auth::id();
                        $data['year'] = $version_year;
                        AThreadChapter::create($data);
                    }
                    return return_json();
                }
                break;

            case 'change_onlyid':
                $now_id = $this->request->now_id;
                $now_onlyid = $this->request->now_onlyid;
                if(AWorkbook::where(['id'=>$now_id])->update(['onlyid'=>$now_onlyid])){
                    return return_json();
                }
                break;
            //新增至零五网
            case 'add_to_lww':
                $only_id = $this->request->only_id;
                $book_info = AOnlyBook::where('onlyid',$only_id)->first(['bookname']);
                $data['tid'] = PreForumThread::where('tid','<',1000000)->max('tid')+1;
                $data['author']=Auth::user()->name;
                $data['authorid']=Auth::user()->id;
                $data['subject']=$book_info->bookname;
                $data['fromtable'] = 'a_chapter';
                if($s = PreForumThread::create($data)){
                    AOnlyBook::where('onlyid',$only_id)->update(['thread_id'=>$s->tid]);
                    return return_json(['new_id'=>$s->tid]);
                }
                break;
            //更新答案状态
            case 'update_answer_status':
                $only_id = $this->request->only_id;
                $answer_status = $this->request->answer_status;
                if($s = AOnlyBook::where(['onlyid'=>$only_id])->update(['answer_status'=>$answer_status])){
                    return return_json();
                }
                break;

            case 'hotbook_update_answer_status':
                $id = $this->request->id;
                $answer_type = $this->request->answer_type;
                if(!in_array($answer_type, ['hd','kd','xzz','answer'],true)){
                    return return_json_err();
                }
                $answer_status = $this->request->answer_status;
                if($s = \App\OnlineModel\ATongjiHotBook::where(['id'=>$id])->update([$answer_type.'_status'=>$answer_status])){
                    return return_json();
                }
                break;

            case 'update_own_uid':
                $only_id = $this->request->only_id;
                $own_uid = $this->request->own_uid;
                if($s = AOnlyBook::where(['onlyid'=>$only_id])->update(['own_uid'=>$own_uid])){
                    $data['teacher_uid'] = Auth::id();
                    $data['part_time_uid'] = $own_uid;
                    $data['onlyid'] = $only_id;
                    ParttimeLog::create($data);
                    return return_json();
                }
                break;

            //保存练习册信息
            case 'save_bookinfo':
                $version_year = intval($this->request->version_year);
                $book_name =  $version_year.'年'.str_replace([$version_year.'年'], '', $this->request->book_name);
                $only_name = str_replace(['上册','下册','全一册',$version_year.'年'], '', $book_name);
                if($book_name==''){
                    return return_json_err(0,'书名错误');
                }
                $grade_id = intval($this->request->grade_id);
                $subject_id = intval($this->request->subject_id);
                $volumes_id = intval($this->request->volumes_id);
                $version_id = intval($this->request->version_id);
                $sort_id = intval($this->request->sort_id);
                $new_ssort_id = is_numeric($this->request->ssort_id);

                $where['volumes_id'] = $volumes_id;
                $where['grade_id'] = $only['grade_id']= $grade_id;
                $where['subject_id'] = $only['subject_id'] = $subject_id;
                $where['version_id'] = $only['version_id'] = $version_id;
                $where['sort'] = $only['sort_id'] = $sort_id;
                $where['isbn'] = str_replace('-', '', $this->request->isbn);
                $where['version_year'] = $only['version_year'] = $version_year;
                $only['bookname'] = $only_name;
                $only['ssort_name'] = $this->request->ssort_name?$this->request->ssort_name:'';
                $where['cover'] = $this->request->cover;

                if(AOnlyBook::where(['bookname'=>$only_name])->count()>0){
                    if(AOnlyBook::where(['bookname'=>$only_name])->update($only)){
                        $where['bookname'] = $book_name;
                        if($new_ssort_id) {
                            $where['ssort_id'] = intval($this->request->ssort_id);
                        }

                        $now_id =intval($this->request->book_id);
                        AWorkbook1010::where(['id'=>$now_id])->update($where);
                        $onlyid = AOnlyBook::where(['bookname'=>$only_name])->first(['onlyid']);
                        $this->update_onlybook_isbn($onlyid->onlyid,$where['isbn']);
                        return return_json();
                    }else{
                        $where['bookname'] = $book_name;
                        $now_id =intval($this->request->book_id);
                        if($new_ssort_id) {
                            $where['ssort_id'] = intval($this->request->ssort_id);
                        }
                        AWorkbook1010::where(['id'=>$now_id])->update($where);
                        $onlyid = AOnlyBook::where(['bookname'=>$only_name])->first(['onlyid']);
                        $this->update_onlybook_isbn($onlyid->onlyid,$where['isbn']);
                    }
                }else{
                    $where['bookname'] = $book_name;
                    $where['cover'] = $only['cover'] = $this->request->cover;
                    $where['onlyid'] = $only['onlyid'] = str_pad($sort_id,5,"0",STR_PAD_LEFT).str_pad($grade_id,2,"0",STR_PAD_LEFT).str_pad($subject_id,2,"0",STR_PAD_LEFT).str_pad($version_id,2,"0",STR_PAD_LEFT)."00";

                    if(AOnlyBook::where(['onlyid'=>$where['onlyid']])->count()>0){
                        if($new_ssort_id){
                            $where['ssort_id'] = $only['ssort_id'] = intval($this->request->ssort_id);
                        }else{
                            $max_ssort_id = AOnlyBook::where('onlyid','like',substr($where['onlyid'],0,-2).'%')->max('ssort_id')+1;
                            $where['ssort_id'] = $only['ssort_id'] = $max_ssort_id;
                        }

                        $where['onlyid'] = $only['onlyid'] = substr($where['onlyid'],0,-2).str_pad($where['ssort_id'],2,"0",STR_PAD_LEFT);
                        $now_count = AOnlyBook::where(['onlyid'=>$only['onlyid']])->count();
                        if($now_count>0 || ($where['ssort_id']>0 && AOnlyBook::create($only))){
                            $where['bookname'] = $book_name;
                            $now_id =intval($this->request->book_id);
                            AWorkbook1010::where(['id'=>$now_id])->update($where);
                            $this->update_onlybook_isbn($where['onlyid'],$where['isbn']);
                            return return_json();
                        }
                    }else{
                        $where['ssort_id'] = $only['ssort_id'] = 0;
                        $only['bookname'] = $only_name;
                        $only['cover'] = $this->request->cover;
                        if(AOnlyBook::create($only)){
                            $where['bookname'] = $this->request->book_name;
                            $now_id =intval($this->request->book_id);
                            AWorkbook1010::where(['id'=>$now_id])->update($where);
                            $this->update_onlybook_isbn($where['onlyid'],$where['isbn']);
                            return return_json();
                        }
                    }
                }

                return return_json_err();
                break;

            //新增练习册
            case 'add_new_book':
                $book_name = intval($this->request->version_year).'年'.$this->request->book_name;
                $version_year = intval($this->request->version_year);
                $only_name = str_replace(['上册','下册','全一册',$version_year.'年'], '', $book_name);

                $grade_id = intval($this->request->grade_id);
                $subject_id = intval($this->request->subject_id);
                $volumes_id = intval($this->request->volumes_id);
                $version_id = intval($this->request->version_id);
                $sort_id = intval($this->request->sort_id);
                $new_ssort_id = is_numeric($this->request->ssort_id);
                $cover = $this->request->cover;
                $isbn = str_replace('-', '', $this->request->isbn);
                $ssort_name = $this->request->ssort_name?$this->request->ssort_name:'';
                $bookcode = md5($book_name.$version_year.$sort_id.$grade_id.$subject_id.$volumes_id.$version_id.$isbn.'from_local');

                $where['bookname'] = $book_name;
                $only['bookname'] = $only_name;
                $where['cover'] = $cover;
                //$where['status'] = 24;
                $where['status'] = 14;
                $where['sort'] = $sort_id;
                $where['bookcode'] = $bookcode;
                $where['grade_id'] = $only['grade_id']= $grade_id;
                $where['subject_id'] = $only['subject_id'] = $subject_id;
                $where['version_id'] = $only['version_id'] = $version_id;
                $where['volumes_id'] = $volumes_id;
                $only['sort_id'] = $sort_id;
                $where['isbn'] = $only['isbn'] = str_replace('-', '', $this->request->isbn);
                $where['version_year'] = $only['version_year'] = $version_year;
                $only['ssort_name'] = $this->request->ssort_name?$this->request->ssort_name:'';
                $where['addtime'] = date('Y-m-d H:i:s',time());
                if(AOnlyBook::where([['bookname',$only_name],['onlyid','like','0%']])->whereRaw('LENGTH(onlyid)=13')->count()>0){
                    $onlyid = AOnlyBook::where([['bookname',$only_name],['onlyid','!=',0]])->whereRaw('LENGTH(onlyid)=13')->first(['onlyid','ssort_id']);
                    $where['onlyid'] = $onlyid->onlyid;
                    $where['ssort_id'] = $onlyid->ssort_id;
                    $only['onlyid'] = $onlyid->onlyid;
                    AOnlyBook::where(['bookname'=>$only_name])->update($only);

                    if(AWorkbook1010::where(['onlyid'=>$onlyid,'version_year'=>$where['version_year'],'volumes_id'=>$where['volumes_id']])->count()==0){
                        $where['id'] = AWorkbook1010::where('id','<',1000000)->max('id')+1;
                        if(AWorkbook1010::create($where)){
                            $this->update_onlybook_isbn($onlyid->onlyid,$where['isbn']);
                            return return_json();
                        }
                    }
                }else{
                    $where['onlyid'] = $only['onlyid'] = str_pad($sort_id,5,"0",STR_PAD_LEFT).str_pad($grade_id,2,"0",STR_PAD_LEFT).str_pad($subject_id,2,"0",STR_PAD_LEFT).str_pad($version_id,2,"0",STR_PAD_LEFT)."00";
                    if(AOnlyBook::where(['onlyid'=>$only['onlyid']])->count()>0){
                        if($new_ssort_id){
                            $where['ssort_id'] = $only['ssort_id'] = intval($this->request->ssort_id);
                        }else{
                            $max_ssort_id = AOnlyBook::where('onlyid','like',substr($where['onlyid'],0,-2).'%')->max('ssort_id')+1;
                            $where['ssort_id'] = $only['ssort_id'] = $max_ssort_id;
                        }

                        $where['onlyid'] = $only['onlyid']= substr($where['onlyid'],0,-2).str_pad($where['ssort_id'],2,"0",STR_PAD_LEFT);
                        $now_count = AOnlyBook::where(['onlyid'=>$only['onlyid']])->count();
                        if($now_count>0 || ($where['ssort_id']>0 && AOnlyBook::create($only))){
                            $where['id'] = AWorkbook1010::where('id','<',1000000)->max('id')+1;
                            if(AWorkbook1010::create($where)){
                                $this->update_onlybook_isbn($where['onlyid'],$where['isbn']);
                                return return_json();
                            }
                        }
                    }else{
                        $where['ssort_id'] = $only['ssort_id'] = 0;
                        $only['cover'] = $cover;

                        if(AOnlyBook::create($only)){
                            $where['bookname'] = $book_name;
                            $where['id'] = AWorkbook1010::where('id','<',1000000)->max('id')+1;
                            if(AWorkbook1010::create($where)){
                                $this->update_onlybook_isbn($where['onlyid'],$where['isbn']);
                                //return return_json();
                            }
                        }
                    }
                }
                break;


            //获取热门isbn练习册
            case 'get_hot_books':
                if($this->request->isbn){
                    $isbn = str_replace('-', '', $this->request->isbn);
                    $where = ['isbn'=>$isbn];
                }else{
                    $grade_id = $this->request->grade_id;
                    $subject_id = $this->request->subject_id;
                    $sort_id = $this->request->sort_id;
                    $where = ['grade_id'=>$grade_id,'subject_id'=>$subject_id,'sort'=>$sort_id];
                }

                $now_hot_books = ATongjiHotBook::where($where)->select(['sort','grade_id','subject_id','version_id','bookname','description','isbn','searchnum'])->get();
                if(count($now_hot_books)>0){
                    foreach ($now_hot_books as $book){
                        $book->isbn = convert_isbn($book->isbn);
                    }
                    return return_json($now_hot_books);
                }

                break;
            //返回最后命名
            case 'get_final_name':


                break;

            //升级练习册
            case 'upgrade_book':
                $book_id = $this->request->book_id;
                $book_info = AWorkbook1010::where(['id'=>$book_id])
                    ->select(['onlyid','bookcode','bookname','version_year','sort','grade_id','subject_id','volumes_id','version_id','cover','isbn','ssort_id'])->first();

                if($book_info){
                    if(AWorkbook1010::where(['onlyid'=>$book_info->onlyid,
                        'version_year'=>config('workbook.now_add_book')['now_year'],'volumes_id'=>config('workbook.now_add_book')['now_volumes']])->count()==0){
                        $data['onlyid'] = $book_info->onlyid;
                        $data['bookname'] = str_replace($book_info->version_year, config('workbook.now_add_book')['now_year'], $book_info->bookname);

                        $data['addtime'] = date('Y-m-d H:i:s',time());
                        $data['version_year'] = config('workbook.now_add_book')['now_year'];
                        $data['sort'] = $book_info->sort;
                        $data['grade_id'] = $book_info->grade_id;
                        $data['subject_id'] = $book_info->subject_id;
                        $data['volumes_id'] = config('workbook.now_add_book')['now_volumes'];
                        $data['version_id'] = $book_info->version_id;
                        $data['isbn'] = $book_info->isbn;
                        $data['bookcode'] = md5($data['bookname'].$data['version_year'].$data['sort'].$data['grade_id'].$data['subject_id'].$data['volumes_id'].$data['version_id'].$data['isbn'].'from_local');
                        $data['cover'] = $book_info->cover;
                        $data['ssort_id'] = $book_info->ssort_id;
                        $data['id'] = AWorkbook1010::where('id','<',1000000)->max('id')+1;
                        if(AWorkbook1010::create($data)){
                            return return_json();
                        }
                    }
                }

                break;

            case 'save_isbn':
                $book_id = $this->request->book_id;
                $isbn = str_replace('-', '', $this->request->isbn);
                $onlyid = AWorkbook1010::where(['id'=>$book_id])->first(['onlyid']);
                $this->update_onlybook_isbn($onlyid->onlyid,$isbn);
                if(AWorkbook1010::where(['id'=>$book_id])->update(['isbn'=>$isbn])){
                    return return_json();
                }
                break;

            //保存学子斋答案网址
            case 'save_answer_url':
                $id = $this->request->id;
                $answer_url = $this->request->answer_url?$this->request->answer_url:'';
                $answer_url_type = intval($this->request->answer_url_type);
                if(ATongjiHotBook::where(['id'=>$id])->update(['answer_url'=>$answer_url,'answer_url_type'=>$answer_url_type])){
                    return return_json();
                }
                break;

            case 'upload_img_to_oss':
                $pic = base64_decode($this->request->pic);
                $oss = new OssController();
                $oss->save($pic, file_get_contents(public_path($pic)));
                break;

            //确认处理完毕
            case 'confirm_done_hotbooks':
                $id = $this->request->id;
                if(ATongjiHotBook::where(['id'=>$id])->update(['has_done'=>1,'updated_uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())])){
                    return return_json(['name'=>Auth::user()->name,'time'=>date('Y-m-d H:i:s',time())]);
                }
                break;

            //更改热门练习册信息
            case 'change_hotbook_info':
                $id = $this->request->book_id;
                $type = $this->request->data_type;
                $val = $this->request->data_val;
                if(!in_array($type, ['grade_id','subject_id','version_id'],true)){
                    return return_json_err();
                }
                if(ATongjiHotBook::where(['id'=>$id])->update([$type=>$val])){
                    return return_json();
                }
                break;

            //确认解析处理完毕
            case 'confirm_analysis_done':
                ignore_user_abort();
                set_time_limit(0);
                ini_set('memory_limit',-1);
                $only_id = $this->request->onlyid;
                $version_year = $this->request->year;
                $volume_id = $this->request->volume;

                AWorkbook1010::where(['onlyid'=>$only_id,'version_year'=>$version_year,'volumes_id'=>$volume_id])->update(['jiexi2'=>1]);

                $all_chapter = AThreadChapter::where(['onlyid'=>$only_id,'year'=>$version_year,'volume_id'=>$volume_id])->select('id')->get();

                if(count($all_chapter)>1){
                    foreach ($all_chapter as $chapter){
                        file_get_contents('http://handler.05wang.com/api/htm2pic/put_thread_pic/'.$chapter->id);
                    }
                }

                break;

            case 'renew_chapter_pic':
                ignore_user_abort();
                set_time_limit(0);
                ini_set('memory_limit',-1);
                return file_get_contents('http://handler.05wang.com/api/htm2pic/put_thread_pic/'.$this->request->chapter_id);


                break;

            case 'update_onlyid_img':
                $onlyid=  $this->request->onlyid;
                $cover = $this->request->img;
                if(AOnlyBook::where(['onlyid'=>$onlyid])->update(['cover'=>$cover])){
                    return return_json();
                }
                break;

            case 'update_volume':
                $onlyid=  $this->request->onlyid;
                $year=  $this->request->year;
                $volume=  $this->request->volume;
                $to_volume_id=  $this->request->to_volume_id;

                if(AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$year,'volume_id'=>$to_volume_id,'status'=>0])->count()>0){
                    return return_json_err(0,'当前有章节存在，请先删除');
                }
                if(AThreadChapter::where(['onlyid'=>$onlyid,'year'=>$year,'volume_id'=>$volume,'status'=>0])->update(['volume_id'=>$to_volume_id])){
                    return return_json();
                }
                break;

            case 'need_upload_content':
                $only_id = $this->request->onlyid;
                $now_status = AOnlyBook::where(['onlyid'=>$only_id])->first(['need_upload']);
                if($now_status){
                    $final = $now_status->need_upload==0?1:0;
                    if(AOnlyBook::where(['onlyid'=>$only_id])->update(['need_upload'=>$final])){
                        return return_json();
                    }
                }
                break;

            //更新onlyname或者bookname05
            case 'update_onlyinfo':
                $only_id = $this->request->onlyid;
                $new_name = $this->request->now_name;
                $now_type = $this->request->update_type;
                if(!in_array($now_type, ['bookname','bookname05'],true)){
                    return return_json_err();
                }
                if(AOnlyBook::where(['onlyid'=>$only_id])->update([$now_type=>$new_name])){
                    return return_json();
                }
                break;

        }
        return return_json_err();
    }
}
