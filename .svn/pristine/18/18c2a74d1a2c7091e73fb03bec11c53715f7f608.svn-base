<?php

namespace App\Http\Controllers\Task;

use App\AnswerModel\AWorkbook1010Cip;
use App\AnswerModel\AWorkbookAnswerCip;
use App\AWorkbook1010;
use App\WorkbookAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateOssController extends Controller
{
    //处理已审核通过并且答案完整的练习册
    public function index()
    {
        dd('qwe');
        $all_books = AWorkbook1010Cip::where([['hdid','>',178964],['verified_at','>',0],['answer_not_complete',0]])->select('id','bookname','bookcode','isbn','cover_photo','grade_id','subject_id','volumes_id','version_id','version_year','addtime','sort','hdid','cip_photo')->get();
        foreach ($all_books as $book){
            $now_cip_book_id = $book->id;
            $data['bookname'] = $book->bookname;
            $data['isbn'] = $book->isbn;
            $data['hdid'] = $book->hdid;
            $data['cover_photo'] = $book->cover_photo;
            $data['grade_id'] = $book->grade_id;
            $data['subject_id'] = $book->subject_id;
            $data['volumes_id'] = $book->volumes_id;
            $data['version_id'] = $book->version_id;
            $data['version_year'] = $book->version_year;
            $data['addtime'] = $book->addtime;
            $data['sort'] = $book->sort;
            $data['bookcode'] = $book->bookcode;
            $data['grade_name'] = '';
            $data['subject_name'] = '';
            $data['volume_name'] = '';
            $data['version_name'] = '';
            $data['sort_name'] = '';
            $data['ssort_id'] = 0;
            $extension = \File::extension($book->cover_photo);
            $cover_dst = 'pic18/'.date('Ymd').md5($book->cover_photo.'1010jiajiao_123').$extension;
            download_hd_img($cover_dst, $book->cover_photo);
            $data['cover'] = config('workbook.thumb_image_url').$cover_dst;
            DB::transaction(function () use ($now_cip_book_id,$data){
                //a_workbook_1010
                $now = AWorkbook1010::create($data);
                //a_workbook_answer_1010
                $now_all_answer = AWorkbookAnswerCip::where('tid',$now_cip_book_id)->select('answer','text','textname','addtime','md5answer')->get();
                foreach ($now_all_answer as $answer){
                    $data_answer['bookid'] = $now->id;
                    $data_answer['book'] = $data['bookcode'];
                    $data_answer['text']= $answer->text;
                    $data_answer['textname']= $answer->textname;
                    $extension = \File::extension($answer->answer);
                    $answer_dst = 'pic18/'.date('Ymd').md5($answer->answer.'1010jiajiao_123').$extension;
                    download_hd_img($answer_dst, $answer->answer);
                    $data_answer['answer'] = $answer_dst;
                    $data_answer['addtime'] = $answer->addtime;
                    $data_answer['hdid'] = $data['hdid'];
                    $data_answer['md5answer'] = $answer->md5answer;
                    WorkbookAnswer::create($data_answer);
                }
                dd('qwe');

            });


            //处理封面


            download_hd_img(,$book->cover_photo);

            dd('qwe');
        }
    }
}
