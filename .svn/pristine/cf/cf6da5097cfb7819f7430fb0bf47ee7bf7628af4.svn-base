<?php

namespace App\Http\Controllers\Manage\Api;

use App\BookToBuy;
use App\BookVersion;
use App\Sort;
use App\Workbook;
use App\WorkbookDel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiWorkbookController extends Controller{
    public function index(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
            'sort' => 'integer',
            'version_year'=>'integer',
        ]);
        $p = $request->except(['_token']);
        if(isset($p['grade_id'])){
            $p['grade_id'] = strlen($p['grade_id'])>1?$p['grade_id']:'0'.$p['grade_id'];
        }
        if(isset($p['subject_id'])){
            $p['subject_id'] = strlen($p['subject_id'])>1?$p['subject_id']:'0'.$p['subject_id'];
        }
        if(isset($p['volumes_id'])){
            $p['volumes_id'] = strlen($p['volumes_id'])>1?$p['volumes_id']:'0'.$p['volumes_id'];
        }
        if(isset($p['version_id'])){
            $p['version_id'] = strlen($p['version_id'])>1?$p['version_id']:'0'.$p['version_id'];
        }
        //$p['name_confirm'] = 1;
        //$p['onlyname'] = $p['sort'].'|'.$p['grade_id'].'|'.$p['subject_id'].'|'.$p['volumes'].'|'.$p['book_version_id'];
//        dd($p);
        //$p['onlyname'] =

        $s = Workbook::where('id', $p['id'])
            ->update($p);
        if($s){
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'更新失败')));
        }
    }

    public function all_done(Request $request){

        $this->validate($request, [
            'original_name' => 'required',
        ]);

        $id = $request->get('id');
        // $s = Book::findorFail($id);
        // $book_info['name'] = $s->get_book_connect_name($id);
        $book_info['bookname'] = $request->get('original_name');
        $book_info['sort_name'] = $request->get('sort_name');
        //$book_info['onlyname'] = $s->sort.'|'.$s->grade_id.'|'.$s->subject_id.'|'.$s->volumes.'|'.$s->book_version_id;
        if($request->get('special_info')){
            $book_info['special_info'] = $request->get('special_info');
        }
        if($request->get('special_info_2')){
            $book_info['special_info_2'] = $request->get('special_info_2');
        }
        if($request->get('district')){
            $book_info['district'] = $request->get('district');
        }
        $book_info['o_uid'] = $request->get('o_uid');
        $book_info['name_confirm'] = 1;
        $now_book = Workbook::where('id',$id);

        $s = $now_book->select('sort','grade_id','subject_id','volumes_id','version_id')->get();
        $book_info['onlyname'] = intval($s[0]['sort']).'|'.intval($s[0]['grade_id']).'|'.intval($s[0]['subject_id']).'|'.intval($s[0]['volumes_id']).'|'.intval($s[0]['version_id']);
//        if(!empty($book_info['special_info'])){
//            $book_info['onlyname'] = $book_info['onlyname'].'|'. $book_info['special_info'];
//        }

        if($now_book->update($book_info)){
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'更新失败')));
        }
        //$p['onlyname'] = $p['sort'].'|'.$p['grade_id'].'|'.$p['subject_id'].'|'.$p['volumes'].'|'.$p['book_version_id'];
    }

    public function all_done_only(Request $request){

        $this->validate($request, [
            'original_name' => 'required',
        ]);

        $id = $request->get('id');
        // $s = Book::findorFail($id);
        // $book_info['name'] = $s->get_book_connect_name($id);
        $book_info['bookname'] = $request->get('original_name');
        $book_info['sort_name'] = $request->get('sort_name');
        //$book_info['onlyname'] = $s->sort.'|'.$s->grade_id.'|'.$s->subject_id.'|'.$s->volumes.'|'.$s->book_version_id;
        if($request->get('special_info')){
            $book_info['special_info'] = $request->get('special_info');
        }
        if($request->get('special_info_2')){
            $book_info['special_info_2'] = $request->get('special_info_2');
        }
        if($request->get('district')){
            $book_info['district'] = $request->get('district');
        }
        if($request->get('isbn')){
            $book_info['isbn'] = $request->get('isbn');
        }
        $book_info['o_uid'] = $request->get('o_uid');
        $book_info['name_confirm'] = 1;
        $now_book = Workbook::where('id',$id);

//        $book_info['onlyname'] = intval($s[0]['sort']).'|'.intval($s[0]['grade_id']).'|'.intval($s[0]['subject_id']).'|'.intval($s[0]['volumes_id']).'|'.intval($s[0]['version_id']);
        $s = $now_book->select('version_year','sort','sort_name','grade_id','subject_id','volumes_id','version_id','district')->get();
//
        //version_year|sort|sort_name|grade|subject|volumes|version_id|district
        $book_info['onlycode'] = intval($s[0]['version_year']).'|'.intval($s[0]['sort']).'|'.$book_info['sort_name'].'|'.$s[0]['grade_id'].'|'.$s[0]['subject_id'].'|'.$s[0]['volumes_id'].'|'.$s[0]['version_id'].'|'.$s[0]['district'];
//        if(!empty($book_info['special_info'])){
//            $book_info['onlyname'] = $book_info['onlyname'].'|'. $book_info['special_info'];
//        }

        if($now_book->update($book_info)){
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'更新失败')));
        }
        //$p['onlyname'] = $p['sort'].'|'.$p['grade_id'].'|'.$p['subject_id'].'|'.$p['volumes'].'|'.$p['book_version_id'];
    }


    public function workbook_sort(Request $request){
        $word = $request->get('word');
        if($word==''){
            exit(json_encode(array('status'=>0,'msg'=>'获取失败')));
        }else{
            if(is_numeric($word)){
                $sorts = Sort::where('version','like','%'.$word.'%')->select('id','name')->get();
            }else{
                $sorts = Sort::where('name','like','%'.$word.'%')->select('id','name')->get();
            }

            exit(json_encode(array('status'=>1,'msg'=>'获取成功','items'=>$sorts)));
        }
    }

    public function workbook_press(Request $request){
        $word = $request->get('word');
        if($word==''){
            exit(json_encode(array('status'=>0,'msg'=>'获取失败')));
        }else{
            if(is_numeric($word)){
                $sorts = BookVersion::where('id','like','%'.$word.'%')->select('id','name')->get();
            }else{
                $sorts = BookVersion::where('name','like','%'.$word.'%')->select('id','name')->get();
            }

            exit(json_encode(array('status'=>1,'msg'=>'获取成功','items'=>$sorts)));
        }


    }

    public function get_version_num(Request $request){
        $version_id = $request->get('version_id');
        $version_id = strlen($version_id)>1?$version_id:'0'.$version_id;
        $a = Workbook::where('version_id',$version_id)->select(['grade_id',DB::raw('count(*) as num')])->groupBy(['grade_id'])->orderbY('grade_id','asc')->get();

        if(count($a)>0){
            foreach ($a as $key=>$value){

                if($value->grade_id[0]=='0'){
                    $grade_now = $value->grade_id[1];
                }else{
                    $grade_now = $value->grade_id;
                };
                $a[$key]->grade_id = $grade_now;
                $a[$key]->grade_name = config('workbook.grade')[$grade_now];
            }
            return response()->json(array('status'=>1,'msg'=>'获取成功','data'=>$a));
        }else{
            return response()->json(array('status'=>0,'msg'=>'暂无数据'));
        }

    }

    public function get_workbook_cover(Request $request){
        $book_id = intval($request->get('book_id'));
        $data = Workbook::find($book_id,['isbn']);
        if($data->isbn!=''){
            $isbns = explode('|',$data->isbn);
            if(is_array($isbns)){
                $search_isbn = $isbns[0];
            }else{
                $search_isbn = $data->isbn;
            }

            $imgs_now = BookToBuy::where('bar_code',$search_isbn)->select('img')->take(50)->get();
            if(count($imgs_now)>0){
                return response()->json(array('status'=>1,'msg'=>'获取成功','data'=>$imgs_now));
            }
        }
        return response()->json(array('status'=>0,'msg'=>'暂无数据'));
    }

    public function delete_this_book(Request $request){
        $book_id = intval($request->get('book_id'));
        $book_now = Workbook::find($book_id)->toArray();
        $book_now['del_time'] = date('Y-m-d H:i:s',time());

        if(WorkbookDel::insert($book_now)){
            Workbook::find($book_id)->delete();
            return response()->json(array('status'=>1,'msg'=>'删除成功'));
        }
        return response()->json(array('status'=>0,'msg'=>'删除失败'));
    }

    public function recovery_this_book(Request $request){
        $book_id = intval($request->get('book_id'));
        $book_now = WorkbookDel::find($book_id)->toArray();
        unset($book_now['del_time']);
        if(Workbook::insert($book_now)){
            WorkbookDel::find($book_id)->delete();
            return response()->json(array('status'=>1,'msg'=>'恢复成功'));
        }
        return response()->json(array('status'=>0,'msg'=>'恢复失败'));
    }

//    public function book_done(Request $request){
//        $this->validate($request, [
//            'bookname' => 'required',
//        ]);
//        $book_name = $request->get('bookname');
//        $id = $request->get('id');
//        $book_now = Workbook::find($id);
//        $book_now->bookname = $book_name;
//        if($book_now->book_confirm==0){
//            $book_now->book_confirm = 1;
//        }else{
//            $book_now->book_confirm = 0;
//        }
//        if($book_now->save()){
//            exit(json_encode(array('status'=>1,'msg'=>'设置成功')));
//        }else{
//            exit(json_encode(array('status'=>0,'msg'=>'设置失败')));
//        }
//
//    }
//
//    public function book_update(Request $request){
//        $this->validate($request, [
//            'id' => 'required|integer',
//            'version_id'=>'integer',
//            'grade_id'=>'integer',
//            'subject_id'=>'integer',
//            'volumes_id'=>'integer',
//        ]);
//        $p = $request->except(['_token']);
//        $s = Workbook::where('id', $p['id'])
//            ->update($p);
//        if($s){
//            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
//        }else{
//            exit(json_encode(array('status'=>1,'msg'=>'更新失败')));
//        }
//    }
}