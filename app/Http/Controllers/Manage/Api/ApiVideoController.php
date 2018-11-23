<?php

namespace App\Http\Controllers\Manage\Api;

use App\VideoManage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiVideoController extends Controller
{
    private $video_manage;

    public function __construct()
    {

    }

    public function save(Request $request){
        $video_manage = new VideoManage;
        $video_manage->book_id = $request->get('book_id');
        $max_sort = VideoManage::where('book_id',$request->get('book_id'))->max('sort');
        $video_manage->name = $request->get('name');
        $video_manage->description = $request->get('description');
        $video_manage->vid = $request->get('vid');
        $video_manage->sort = $max_sort+1;
        if($video_manage->save()){
            exit(json_encode(array('status'=>1,'msg'=>'新增成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'新增失败')));
        }
    }

    public function delete(Request $request){
        VideoManage::where('vid',$request->get('file_id'))->delete();
    }

    public function modify(Request $request){
        $video_manage = VideoManage::find($request->get('vid'));
        $video_manage->name = $request->get('name');
        $video_manage->description = $request->get('description');
        $video_manage->book_id = $request->get('book_id');
        $video_manage->show_status = $request->get('show_status');

        if($video_manage->save()){
            exit(json_encode(array('status'=>1,'msg'=>'新增成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'新增失败')));
        }

    }

    public function get_chapter(Request $request){
        $book_id = intval($request->get('book_id'));

        $video_chapter = VideoManage::where('book_id',$book_id)->where('show_status',1)->select('vid','name')->orderBy('sort','ASC')->get();
        if(count($video_chapter)>0){
            return response()->json(['status'=>1,'data'=>$video_chapter]);
        }else{
            return response()->json(['status'=>0,'msg'=>'暂无已上架视频']);
        }

    }

    public function set_chapter_sort(Request $request){
        $ids = $request->get('vids');
        $ids = explode('|',$ids);
        $start_sort = 1;
        foreach ($ids as $vid){
            if(!empty($vid)){
                VideoManage::where('vid',$vid)->update(['sort'=>$start_sort]);
                $start_sort+=1;
            }
        }
    }
}
