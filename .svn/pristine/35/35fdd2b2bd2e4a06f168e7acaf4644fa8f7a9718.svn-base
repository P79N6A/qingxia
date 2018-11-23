<?php

namespace App\Http\Controllers\OneLww;

use App\LocalModel\TaskUid;
use App\OneModel\AThreadChapter;
use App\OneModel\PreForumPost;
use App\OnlineModel\AOnlyBook;
use App\OnlineModel\AWorkbook1010;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreviewController extends Controller
{
    public function index($onlyid,$year,$volume,$bookid)
    {
        $data['bookid'] = $bookid;
        $data['year'] = $year;
        $data['onlyid'] = $onlyid;
        $data['volume_id'] = $volume;
        $data['only_detail'] = AOnlyBook::where(['onlyid'=>$onlyid])->first(['onlyid','bookname','grade_id','subject_id','version_id']);
        $data['book_detail'] = AWorkbook1010::where(['id'=>$bookid])->first(['id','bookname','cover','isbn']);
        $data['all_page'] = PreForumPost::where([['bookid',$bookid],['position',1],['invisible','>',-1]])->select('pid','page')->orderBy('page','asc')->get();
        $data['has_chapter_num'] = AThreadChapter::where(['status'=>0,'onlyid'=>$onlyid,'year'=>$year,'volume_id'=>$volume])->count();
        return view('one_lww.preview.index',compact('data'));
    }

    public function ajax(Request $request,$type)
    {
        switch ($type){
            case 'get_pid':
                $now_chapterid = $request->chapterid;
                $now_pid_pages = AThreadChapter::where(['id'=>$now_chapterid])->first(['pid_pages']);
                if($now_pid_pages){
                    return return_json(['now_pid'=>$now_pid_pages->pid_pages]);
                }
                break;

            case 'confirm_done':
                $now_book_id = $request->now_book_id;
                if(AWorkbook1010::where(['id'=>$now_book_id])->update(['jiexi2'=>1]))
                {
                    TaskUid::create(['type'=>'confirm_analysis_done','data'=>$now_book_id,'uid'=>\Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
                    return return_json();
                }
                break;
        }
        return return_json_err();
    }
}
