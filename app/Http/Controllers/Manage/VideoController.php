<?php

namespace App\Http\Controllers\Manage;

use App\VideoManage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Book05;
use Illuminate\Support\Facades\DB;


class VideoController extends Controller
{

    public function index(Request $request,$book_id=0){
        //$user = $request->user();
        $data['all_video_info'] = VideoManage::select(['book_id',DB::raw('count(book_id) as num')])->groupBy(['book_id'])->orderBY('num','DESC')->paginate(10);



        $get_access_token = json_decode(file_get_contents('https://openapi.iqiyi.com/api/iqiyi/authorize?client_id=45dad714d8ab40c0a7ee2c6b2d5a7c49&client_secret=5d31c3797b779530288f9459bef315ef'), true);
        $data['access_token'] = $get_access_token['data']['access_token'];
        $data['video_list_local'] = VideoManage::select(['name','description','vid','book_id','show_status'])->where(function ($query) use($book_id){
            if(intval($book_id)>0){
                $query->where('book_id',$book_id);
            }
        })->orderBy('updated_at','desc')->paginate(15);
        $ids_collect = collect($data['video_list_local']->items());
        if($ids_collect->isNotEmpty()){

            $ids_array = collect($data['video_list_local']->items())->pluck('name','vid');
            $data['ids_status'] = $ids_collect->pluck('show_status','vid');
            $data['ids_books'] = $ids_collect->pluck('book_id','vid');
            $ids_string = $ids_collect->implode('vid',',');
//        $file_ids = $file_ids.',51eb232cf75d4234a032ea03bf33b29b';

            //$ids_string = '';
            $data['video_list_online'] = json_decode(file_get_contents('http://openapi.iqiyi.com/api/file/videoListForExternal?access_token='.$data['access_token'].'&file_ids='.$ids_string.'&page_size=15&page=1'));

            //dd($data['video_list_online']);
            //        foreach ($data['video_list_online']->data as $value){
//            $v = new VideoManage;
//            $v->book_id = 23;
//            $v->name = $value->fileName;
//            $v->description = $value->description;
//            $v->vid = $value->fileId;
//            $v->save();
//        }
            //dd($data['video_list_online']);

            $online_collect = collect($data['video_list_online']->data);
            $online_string = $online_collect->implode('fileId',',');
            if($ids_collect->count()!=$online_collect->count()){
                $need_req = collect(explode(',',$ids_string))->diff(collect(explode(',',$online_string)));
                if(!empty($need_req)){
                    foreach ($need_req as $key => $value){
                        var_dump($value);
                        if($value!=''){
                            $data['video_list_other_fid'][$key]['key'] = $value;
                            $data['video_list_other_fid'][$key]['name'] = $ids_array[$value];
                            $data['video_list_other'][$key] = json_decode(file_get_contents('http://openapi.iqiyi.com/api/file/fullStatus?access_token='.$data['access_token'].'&file_id='.$value));
                        }
                    }
                }
            }
        }

        //dd($data['video_list_other_fid']);
        //dd($data['video_list_other']);
        $data['book_list'] = Book05::all(['id','bookName','picture']);


        return view('manage.video',compact('data'));
    }

    public function show(Request $request,$book_id){
        //$user = $request->user();
        $data['all_video_info'] = VideoManage::select(['book_id',DB::raw('count(book_id) as num')])->groupBy(['book_id'])->orderBY('num','DESC')->get();
        $get_access_token = json_decode(file_get_contents('https://openapi.iqiyi.com/api/iqiyi/authorize?client_id=45dad714d8ab40c0a7ee2c6b2d5a7c49&client_secret=5d31c3797b779530288f9459bef315ef'), true);
        $data['access_token'] = $get_access_token['data']['access_token'];
        $data['video_list_local'] = VideoManage::select(['name','description','vid','book_id','show_status'])->where(function ($query) use($book_id){
            if($book_id!=0){
                $query->where('book_id',$book_id);
            }
        })->orderBy('updated_at','desc')->paginate(15);
        $ids_collect = collect($data['video_list_local']->items());
        if($ids_collect->isNotEmpty()){
            $ids_array = collect($data['video_list_local']->items())->pluck('name','vid');
            $data['ids_status'] = $ids_collect->pluck('show_status','vid');
            $data['ids_books'] = $ids_collect->pluck('book_id','vid');
            $ids_string = $ids_collect->implode('vid',',');
//        $file_ids = $file_ids.',51eb232cf75d4234a032ea03bf33b29b';

            //$ids_string = '';
            $data['video_list_online'] = json_decode(file_get_contents('http://openapi.iqiyi.com/api/file/videoListForExternal?access_token='.$data['access_token'].'&file_ids='.$ids_string.'&page_size=15&page=1'));

            //dd($data['video_list_online']);
            //        foreach ($data['video_list_online']->data as $value){
//            $v = new VideoManage;
//            $v->book_id = 23;
//            $v->name = $value->fileName;
//            $v->description = $value->description;
//            $v->vid = $value->fileId;
//            $v->save();
//        }
            //dd($data['video_list_online']);

            $online_collect = collect($data['video_list_online']->data);
            $online_string = $online_collect->implode('fileId',',');
            if($ids_collect->count()!=$online_collect->count()){
                $need_req = collect(explode(',',$ids_string))->diff(collect(explode(',',$online_string)));
                if(!empty($need_req)){
                    foreach ($need_req as $key => $value){
                        var_dump($value);
                        if($value!=''){
                            $data['video_list_other_fid'][$key]['key'] = $value;
                            $data['video_list_other_fid'][$key]['name'] = $ids_array[$value];
                            $data['video_list_other'][$key] = json_decode(file_get_contents('http://openapi.iqiyi.com/api/file/fullStatus?access_token='.$data['access_token'].'&file_id='.$value));
                        }
                    }
                }
            }
        }

        //dd($data['video_list_other_fid']);
        //dd($data['video_list_other']);
        $data['book_list'] = Book05::all(['id','bookName','picture']);

        return view('manage.video_show',compact('data'));
    }

}
