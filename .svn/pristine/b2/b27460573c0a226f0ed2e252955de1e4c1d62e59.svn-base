<?php

namespace App\Http\Controllers\BookBuy\Api;

use App\ASortUid;
use App\ATongjiBuy;
use App\AWorkbook1010;
use App\AWorkbookNew;
use App\BookNeedBuy;
use App\BookNewAdd;
use App\HdBook;
use App\HdUserBook;
use App\Sort;
use App\Workbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->now_uid = Auth::id();
            return $next($request);
        });
    }

    public function sort_about($sort)
    {
        Workbook::where('sort', $sort)->select();
    }

    //新增至待购买
    public function add_all(Request $request)
    {
        $ids = explode(',', $request->get('ids'));
        foreach ($ids as $key => $id) {
            $id = intval($id);
            if ($id > 0) {
                $count_now = BookNeedBuy::where('book_id', $id)->count();
                if ($count_now > 0) {
                    return response()->json(['status' => 0, 'msg' => '已存在记录']);
                }
            }

        }
        foreach ($ids as $id) {
            $id = intval($id);

            if ($id > 1000000) {
                $data['book_id'] = intval($id)-1000000;
                $data_now = HdUserBook::where('id',$data['book_id'])->first();
                $data['uid'] = intval($this->now_uid);
                $data['book_name'] = $data_now['book_name'];
                $data['grade_id'] = $data_now['grade_id'];
                $data['subject_id'] = $data_now['subject_id'];
                $data['volume_id'] = $data_now['volumes'];
                $data['version_id'] = $data_now['book_version_id'];
                $data['isbn'] = $data_now['bar_code'];
                $data['cover_photo_thumbnail'] = $data_now['cover_photo_thumb'];
                BookNeedBuy::create($data);
            }else{
                if(intval($id)>0){
                    $data['book_id'] = intval($id);
                    $data_now = HdBook::where('id',$data['book_id'])->first();
                    $data['uid'] = intval($this->now_uid);
                    $data['book_name'] = $data_now['name'];
                    $data['grade_id'] = $data_now['grade_id'];
                    $data['subject_id'] = $data_now['subject_id'];
                    $data['volume_id'] = $data_now['volumes'];
                    $data['version_id'] = $data_now['book_version_id'];
                    $data['isbn'] = $data_now['bar_code'];
                    $data['cover_photo_thumbnail'] = $data_now['cover_photo_thumbnail'];
                    BookNeedBuy::create($data);
                }
            }
        }
        return response()->json(['status' => 1, 'msg' => '加入成功']);
    }

    //已购买
    public function add_done(Request $request)
    {
        $ids = explode(',', $request->get('ids'));
        foreach ($ids as $key => $id) {
            $id = intval($id);
            if ($id > 0) {
                BookNeedBuy::where('book_id', $id)->update(['status' => 1]);
            }
        }
        return response()->json(['status' => 1, 'msg' => '操作成功']);
    }

    public function done_status(Request $request, $type)
    {
        if ($type == 'done') {
            $data = ['status' => 2];
        } else {
            $data = ['status' => 3];
        }
        BookNeedBuy::where('book_id', intval($request->get('book_id')))->update($data);
        return response()->json(['status' => 1, 'msg' => '操作成功']);
    }

    //删除待购买书本
    public function delete_book(Request $request)
    {
        $book_id = intval($request->get('book_id'));
        if (BookNeedBuy::where('book_id', $book_id)->delete()) {
            return response()->json(['status' => 1, 'msg' => '操作成功']);
        }
    }

    //新增shu
    public function add_new_book(Request $request)
    {
        $data['name'] = $request->get('bookname');
        $data['grade_id'] = $request->get('grade_id');
        $data['subject_id'] = $request->get('subject_id');
        $data['volume_id'] = $request->get('volume_id');
        $data['press_id'] = $request->get('press_id');
        $data['version_id'] = $request->get('version_id');
        $data['sort_id'] = $request->get('sort_id');
        $data['isbn'] = $request->get('isbn');
        $data['uid'] = $this->now_uid;
        $data['version_year'] = $request->get('version_year');

        if (BookNewAdd::create($data)) {
            return response()->json(['status' => 1, 'msg' => '操作成功']);
        } else {
            return response()->json(['status' => 0, 'msg' => '新增失败']);
        }
    }

    public function check_it(Request $request)
    {
        $book_id = intval($request->get('book_id'));
        $has_it = BookNeedBuy::where('book_id',$book_id)->count();
        if($has_it==0){
            $R = ['status' => 1, 'msg' => '可以入库'];
        }else{
            $R = ['status' => 0, 'msg' => '已添加购买,不能重复添加'];
        }
        return response()->json($R);
    }

    //标记状态
    public function mark_buy_status(Request $request)
    {
        $id = $request->id;
        $type = $request->data_type;
        if(starts_with($id, '999999999') && strpos($id, '|')){
            if(strpos($id, '_')){
                $data_info = explode('_', explode('|', $id)[1]);
                $data_version = $data_info[0];
                $data_grade = $data_info[1];
                $data_subject = $data_info[2];
                $data['sort'] = $request->sort;
                $sort_name = ATongjiBuy::where([['jj',$request->jj],['book_id','0'],['sort',$request->sort]])->first();
                $data['sort_name'] = '2018年'.$sort_name->sort_name.config('workbook.grade')[$data_grade].config('workbook.subject_1010')[$data_subject].cache('all_version_now')->where('id',$data_version)->first()->name;
                $data['jj'] = $request->jj;
                $data['book_id'] = 999999;
                $data['status'] = 1;
                $data['update_uid'] = Auth::id();
                $data['version_year'] = 2018;
                $data['grade_id'] = $data_grade;
                $data['subject_id'] = $data_subject;
                $data['volume_id'] = 2;
                $data['version_id'] = $data_version;
            }else{
                $data_id = explode('|', $id)[1];
                $now = ATongjiBuy::find($data_id);
                $data['sort'] = $now->sort;
                $data['sort_name'] = str_replace(['2014','2015','2016','2017'], '2018', $now->sort_name);
                $data['jj'] = $now->jj;
                $data['book_id'] = 999999;
                $data['status'] = 1;
                $data['update_uid'] = Auth::id();
                $data['version_year'] = 2018;
                $data['grade_id'] = $now->grade_id;
                $data['subject_id'] = $now->subject_id;
                $data['volume_id'] = 2;
                $data['version_id'] = $now->version_id;
                $data['isbn'] = $now->isbn;
                if(ATongjiBuy::where($data)->count()>0){
                    $now_status = ATongjiBuy::where($data)->first()->status;
                    if($now_status==0){
                        $make_status = 1;
                    }else{
                        $make_status = 0;
                    }
                    if(ATongjiBuy::where($data)->update(['status'=>$make_status])){
                        return response()->json(['status'=>1]);
                    }
                }
            }

            if(ATongjiBuy::create($data)){
                return response()->json(['status'=>1]);
            };
        }else{
            $now = ATongjiBuy::find($id);
            if($now->status==0){
                $now->status = 1;
            }else{
                $now->status = 0;
            }
            $now->update_uid = Auth::id();
            if($now->save()){
                return response()->json(['status'=>1]);
            }
        }
        return response()->json(['status'=>0]);
    }

    //新api
    public function new_book_buy_api(Request $request,$type)
    {
        //更换负责人
        if($type==='change_owner'){
            $data['update_uid'] = $request->user_id;
            $sort = $request->sort;
            if(ATongjiBuy::where(['sort'=>$sort,'book_id'=>0,'jj'=>1])->update($data)){
                return response()->json(['status'=>1]);
            }
        }
        //新增练习册
        else if($type==='mark_status'){
            $id = $request->id;
            $isbn = str_replace('-', '', $request->isbn);
            $book_name = $request->bookname;
            $version_id = $request->version_id;
            $data_id = 0;
            if(starts_with($id, '999999999') && strpos($id, '|')){
                if(strpos($id, '_')){
                    $data_info = explode('_', explode('|', $id)[1]);
                    $data_version = $version_id;
                    $data_grade = $data_info[1];
                    $data_subject = $data_info[2];
                    $data['sort'] = $request->sort;
                    $book_name = str_replace(config('workbook.grade'), config('workbook.grade')[$data_grade], $book_name);
                    $book_name = str_replace(config('workbook.subject_1010'), config('workbook.subject_1010')[$data_subject], $book_name);
                    $book_name = str_replace(['2014年','2015年','2016年','2017年'], '2018年', $book_name);
                    //$sort_name = ATongjiBuy::where([['jj',$request->jj],['book_id','0'],['sort',$request->sort]])->first();
                    $data['newname'] = str_replace(['2014年','2015年','2016年','2017年','2018年'], '', $book_name);
                    $data['bookname'] = $book_name;
                    $data['status'] = 1;
                    $data['version_year'] = 2018;

                    $data['grade_id'] = $data_grade;
                    $data['subject_id'] = $data_subject;
                    $data['volumes_id'] = 2;
                    $data['version_id'] = $data_version;
                    $data['isbn'] = $request->isbn;
                }
                else{
                    //替换年级科目上下册
                    $data_id = explode('|', $id)[1];
                    $now = AWorkbookNew::find($data_id);
                    $data['sort'] = $now->sort;
                    $book_name = str_replace(config('workbook.grade'), config('workbook.grade')[$now->grade_id], $book_name);
                    $book_name = str_replace(config('workbook.subject_1010'), config('workbook.subject_1010')[$now->subject_id], $book_name);
                    $book_name = str_replace(['2014年','2015年','2016年','2017年'], '2018年', $book_name);
                    $data['newname'] = str_replace(['2014年','2015年','2016年','2017年','2018年'], '', $book_name);
                    $data['bookname'] = $book_name;
                    $data['status'] = 1;
                    $data['version_year'] = 2018;
                    $data['grade_id'] = $now->grade_id;
                    $data['subject_id'] = $now->subject_id;
                    $data['volumes_id'] = 2;
                    $data['version_id'] = $version_id;
                    $data['isbn'] = $isbn;
                }
                if(AWorkbookNew::where($data)->count()>0){
                    //ATongjiBuy::where($data)->delete();
                    return response()->json(['status'=>1,'type'=>'cancel']);
                }
                $data['grade_name'] = '';
                $data['subject_name'] = '';
                $data['volume_name'] = '';
                $data['version_name'] = '';
                $data['sort_name'] = '';
                $data['ssort_id'] = 0;
                $data['update_uid'] = Auth::id();
                $data['updated_at'] = date('Y-m-d H:i:s',time());
                if(AWorkbookNew::max('id')>1000000){
                    $data['id'] = AWorkbookNew::max('id')+1;
                }else{
                    $data['id'] = 1000000+AWorkbookNew::max('id');
                }
                if($a = AWorkbookNew::create($data)){
                    //优化设计/2018年_六年级_英语_下册_译林版_9787549939121/
                    make_answer_dir($data['id']);
                    //更新以前练习册状态
                    AWorkbookNew::where([['id','<',1000000],['grade_id',$data['grade_id']],['subject_id',$data['subject_id']],['volumes_id',$data['volumes_id']],['version_id',$data['version_id']],['sort',$data['sort']]])->update(['has_update'=>1]);
                    AWorkbook1010::where([['grade_id',$data['grade_id']],['subject_id',$data['subject_id']],['volumes_id',$data['volumes_id']],['version_id',$data['version_id']],['sort',$data['sort']]])->update(['need_buy'=>0]);
                    return response()->json(['status'=>1,'type'=>'new','new_id'=>$data['id'],'new_name'=>$data['bookname']]);
                };
            }
//            else{
//                $now = AWorkbookNew::find($id);
//                $data['grade_id'] = $now->grade_id;
//                $data['subject_id'] = $now->subject_id;
//                $data['volumes_id'] = $now->volumes_id;
//                $data['version_id'] = $now->version_id;
//                $data['sort'] = $now->sort;
//
//                if(AWorkbookNew::where($data)->update(['has_update'=>0])){
//                    if(AWorkbookNew::where(['id'=>$id])->delete()){
//                        return response()->json(['status'=>1,'type'=>'cancel']);
//                    }
//                }
//            }
        }
        //更新旧版练习册
        else if($type==='update_this_book'){
            $book_id = $request->book_id;
            $now = AWorkbookNew::find($book_id);
            if($now->has_update==1){
                if(AWorkbookNew::where(['update_from'=>$now->id])->delete()){
                    if(AWorkbookNew::where(['id'=>$now->id])->update(['has_update'=>0])){
                        return response()->json(['status'=>1]);
                    }
                }
                return response()->json(['status'=>0]);
            }
            if($now->version_year<2018){
                $data['sort'] = $now->sort;
                $data['newname'] = $now->newname;
                $data['bookname'] = '2018年'.$now->newname;
                $data['status'] = 1;
                $data['has_update'] = 0;
                $data['update_from'] = $book_id;
                $data['version_year'] = 2018;
                $data['grade_id'] = $now->grade_id;
                $data['subject_id'] = $now->subject_id;
                $data['volumes_id'] = $now->volumes_id;
                $data['version_id'] = $now->version_id;
                if(AWorkbookNew::where($data)->count()>0){
                    return response()->json(['status'=>0]);
                }
                $data['grade_name'] = '';
                $data['subject_name'] = '';
                $data['volume_name'] = '';
                $data['version_name'] = '';
                $data['sort_name'] = '';
                $data['ssort_id'] = 0;
                $data['update_uid'] = Auth::id();
                $data['updated_at'] = date('Y-m-d H:i:s',time());
                if(AWorkbookNew::max('id')>1000000){
                    $data['id'] = AWorkbookNew::max('id')+1;
                }else{
                    $data['id'] = 1000000+AWorkbookNew::max('id');
                }
                if($a = AWorkbookNew::create($data)){
                    AWorkbookNew::where(['id'=>$book_id])->update(['has_update'=>1]);
                    AWorkbook1010::where([['grade_id',$data['grade_id']],['subject_id',$data['subject_id']],['volumes_id',$data['volumes_id']],['version_id',$data['version_id']],['sort',$data['sort']]])->update(['need_buy'=>0]);
                    make_answer_dir($data['id']);

                    return response()->json(['status'=>1,'type'=>'new','new_id'=>$data['id'],'new_name'=>$data['bookname']]);
                };
            }
        }
        //删除新练习册
        else if($type==='delete_book'){
            $book_id = $request->book_id;
            $now = AWorkbookNew::find($book_id);
            if($now->version_year==2018){
                if($now->update_from>0){
                    if(AWorkbookNew::where(['id'=>$now->update_from])->update(['has_update'=>0])){
                        if(AWorkbookNew::where(['id'=>$now->id])->delete()){
                            return response()->json(['status'=>1]);
                        }
                    }
                }else{
                    if(AWorkbookNew::where(['id'=>$now->id])->delete()){
                        return response()->json(['status'=>1]);
                    }
                }
            }

        }
        //确认到货
        else if($type==='confirm_receive'){
            $book_id = $request->book_id;
            if(AWorkbookNew::where(['id'=>$book_id])->update(['arrived_at'=>date('Y-m-d H:i:s',time())])){
                response()->json(['status'=>1]);
            }

        }

        //更换版本
        elseif($type==='change_version'){
            $book_id = $request->book_id;
            $version_id = $request->version_id;
            AWorkbookNew::where('id',$book_id)->update(['version_id'=>$version_id]);
            if($book_id>1000000){
                make_answer_dir($book_id);
            }
            return response()->json(['status'=>1]);
        }

        //更换名称
        else if($type==='change_new_name'){
            $book_id = $request->book_id;
            $book_name = $request->book_name_now;
            if($book_id>1000000){
                $new_name = str_replace('2018年', '', $book_name);
                if(AWorkbookNew::where(['id',$book_id])->update(['bookname'=>$book_name,'newname'=>$new_name])){
                    make_answer_dir($book_id);
                }
            }
            return response()->json(['status'=>1]);
        }

        return response()->json(['status'=>0]);
    }

    public function history_page_api()
    {

    }

}
