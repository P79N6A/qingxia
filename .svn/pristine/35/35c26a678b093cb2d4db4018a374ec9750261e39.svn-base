<?php

namespace App\Http\Controllers\Manage\Api;

use App\Book;
use App\Sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class ApiLxcController extends Controller
{
    public function index(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
            'sort' => 'integer',
            'grade_id'=>'integer|between:1,14',
            'subject_id'=>'integer|between:1,10',
            'sub_volumes'=>'integer',
            'sub_version'=>'integer',
            'press'=>'integer',
            'version'=>'integer',
            'o_uid'=>'required|integer'
        ]);

        $p = $request->except(['_token']);

        //$p['name_confirm'] = 1;
        //$p['onlyname'] = $p['sort'].'|'.$p['grade_id'].'|'.$p['subject_id'].'|'.$p['volumes'].'|'.$p['book_version_id'];
//        dd($p);
        //$p['onlyname'] =

        $s = Book::where('id', $p['id'])
            ->update($p);
        if($s){
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>1,'msg'=>'更新失败')));
        }
    }

    public function all_done(Request $request){

        $this->validate($request, [
            'original_name' => 'required',
        ]);

        $id = $request->get('id');
        // $s = Book::findorFail($id);
        // $book_info['name'] = $s->get_book_connect_name($id);
        $book_info['name'] = $request->get('original_name');

        //$book_info['onlyname'] = $s->sort.'|'.$s->grade_id.'|'.$s->subject_id.'|'.$s->volumes.'|'.$s->book_version_id;
        $book_info['special_info'] = $request->get('special_info');
		$book_info['special_info_2'] = $request->get('special_info_2');
        $book_info['sub_sort'] = $request->get('sub_sort');
        $book_info['main_word'] = $request->get('main_word');
        $book_info['o_uid'] = $request->get('o_uid');
        $book_info['name_confirm'] = 1;
        $now_book = Book::where('id',$id);
        $s = $now_book->select('sort','grade_id','subject_id','volumes','book_version_id')->get();
        $book_info['onlyname'] = $s[0]['sort'].'|'.$s[0]['grade_id'].'|'.$s[0]['subject_id'].'|'.$s[0]['volumes'].'|'.$s[0]['book_version_id'];
//        if(!empty($book_info['special_info'])){
//            $book_info['onlyname'] = $book_info['onlyname'].'|'. $book_info['special_info'];
//        }
        if($now_book->update($book_info)){
            $this->update_book_word($s[0]['sort'],$book_info['main_word']);
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'更新失败')));
        }
        //$p['onlyname'] = $p['sort'].'|'.$p['grade_id'].'|'.$p['subject_id'].'|'.$p['volumes'].'|'.$p['book_version_id'];
    }

    public function sort_all_done(Request $request){
        $id = $request->get('id');
        $book_info['sub_sort'] = $request->get('sub_sort');
        $book_info['main_word'] = $request->get('main_word');
        $book_info['o_uid'] = $request->get('o_uid');
        $now_book = Book::where('id',$id);
        if($now_book->update($book_info)){
            $a = $now_book->select('sort')->get();
            $sort_now = $a[0]['sort'];
            $sort = new Sort();
            $sort->update_sort_info($sort_now,$book_info['sub_sort']);
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'更新失败')));
        }
    }

    public function sort_update(Request $request){
        $type = $request->get('type');
        $sort = $request->get('sort');
        $main_word = $request->get('update_word');
        $sort_update = new Sort();
        $result = $sort_update->update_sort_info($type,$sort,$main_word);
        exit(json_encode(array('status'=>$result)));
    }

    public function sort_del(Request $request){
        $type = $request->get('type');
        $sort = $request->get('sort');
        $main_word = $request->get('update_word');
        $sort_update = new Sort();
        $result = $sort_update->del_sort_info($type,$sort,$main_word);
        exit(json_encode(array('status'=>$result)));
    }
}
