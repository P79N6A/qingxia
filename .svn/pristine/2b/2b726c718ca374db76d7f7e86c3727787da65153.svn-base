<?php

namespace App\Http\Controllers\Manage\Api;

use App\ABook1010;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiABook1010Controller extends Controller
{
    public function book_done(Request $request){
        $this->validate($request, [
            'bookname' => 'required',
        ]);
        $book_name = $request->get('bookname');
        $id = $request->get('id');
        $book_now = ABook1010::find($id);
        $book_now->bookname = $book_name;
        if($book_now->book_confirm==0){
            $book_now->book_confirm = 1;
        }else{
            $book_now->book_confirm = 0;
        }
        if($book_now->save()){
            exit(json_encode(array('status'=>1,'msg'=>'设置成功')));
        }else{
            exit(json_encode(array('status'=>0,'msg'=>'设置失败')));
        }

    }

    public function book_update(Request $request){
        $this->validate($request, [
            'id' => 'required|integer',
            'version_id'=>'min:2|max:2',
            'grade_id'=>'min:2|max:2',
            'subject_id'=>'min:2|max:2',
            'volumes_id'=>'min:2|max:2',
            'isbn'=>'integer',
        ]);
        $p = $request->except(['_token']);

        if($request->get('version_id')){
            if(strlen($p['version_id'])==1){
                $p['version_id'] = '0'.$p['version_id'];
            }
        }
        if($request->get('grade_id')){
            if(strlen($p['grade_id'])==1){
                $p['grade_id'] = '0'.$p['grade_id'];
            }
        }
        if($request->get('subject_id')){
            if(strlen($p['subject_id'])==1){
                $p['subject_id'] = '0'.$p['subject_id'];
            }
        }
//        if($request->get('volumes_id')){
//            if(strlen($p['volumes_id'])==1){
//                $p['volumes_id'] = '0'.$p['volumes_id'];
//            }
//        }
        $s = ABook1010::where('id', $p['id'])
            ->update($p);
        if($s){
            $now_book = ABook1010::where('id',$p['id'])->first();
            $now_book->booksort = $now_book->version_id.$now_book->subject_id.$now_book->grade_id.$now_book->volumes_id;
            $now_book->save();
            exit(json_encode(array('status'=>1,'msg'=>'更新成功')));
        }else{
            exit(json_encode(array('status'=>1,'msg'=>'更新失败')));
        }

    }
}
