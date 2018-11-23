<?php

namespace App\Http\Controllers\Manage;

use App\BookVersionType;
use App\Chapter;
use App\StandardAnswer;
use App\Volume;
use App\Workbook;
use App\WorkbookAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\ABook1010;
use App\BookToBuy;

class BookController extends Controller
{
    public function index(Request $request, $version = '0', $grade = '1',$subject = '1')
    {

        $sql_version = strlen($version)>1?$version:'0'.$version;
        $index_version = intval($version);
        $index_grade = intval($grade);
        $index_subject = intval($subject);
        $sql_subject = strlen($subject)>1?$subject:'0'.$subject;
        $sql_grade = strlen($grade)>1?$grade:'0'.$grade;
        //$user = $request->user();
        $data['subject'] = $sql_subject;
        $data['grade'] = $sql_grade;
        $data['version'] = $sql_version;
        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['code', 'volumes']);

        $all_book = ABook1010::orderBy('booksort','ASC')->get(['id', 'grade_id', 'subject_id', 'version_id']);

        foreach ($all_book as $value) {
            $version_now = intval($value->version_id);
            $grade_now = intval($value->grade_id);
            $subject_now = intval($value->subject_id);
            $data['sort_version'][$version_now][$grade_now][] = 1;
        }
//        $data['all_distinct_grade'] = ABook1010::where('a_book_1010.subject_id', $subject)
//            ->where('a_book_1010.version_id', $version)
////            ->join('book_version_type', 'a_book_1010.version_id', 'book_version_type.id')
////            ->select('a_book_1010.version_id', 'book_version_type.name')
////            ->orderBy('a_book_1010.version_id', 'ASC')
//            ->distinct()
//            ->get();

        $answers = [];
        $textname = [];

        if($index_grade==0){
            //get_all
            foreach (config('workbook.grade') as $key => $value) {
                if(isset($data['sort_version'][$index_version][$key])){
                    $grade_key = strlen($key)>1?$key:'0'.$key;
                    $data['all_book_now'][$key] = ABook1010::where('grade_id', $grade_key)
                        ->where('version_id', $sql_version)
                        ->select('id', 'bookname','bookcode','isbn', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'cover_photo', 'cover_photo_thumbnail', 'book_confirm')
                        ->orderBy('booksort', 'ASC')
                        ->get();
                }
            }
        }else{
            //get_single_grade
            $data['all_book_now'][$index_grade] = ABook1010::where('grade_id', $index_grade)
                ->where('version_id', $sql_version)
                ->select('id', 'bookname','bookcode','isbn', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'cover_photo', 'cover_photo_thumbnail', 'book_confirm')
                ->orderBy('booksort', 'ASC')
                ->get();
        }

        foreach ($data['all_book_now'] as $grade_key => $book) {
            foreach ($book as $key => $value) {
                //barcode相关
                if($value->isbn!=''){
                    $isbns = explode('|',$value->isbn);
                    if(is_array($isbns)){
                        $search_isbn = $isbns[0];
                    }else{
                        $search_isbn = $value->isbn;
                    }

                    $data['isbn'][$grade_key][$key] = BookToBuy::where('bar_code',$search_isbn)->select('img')->take(50)->get();
                }

                //答案相关
                $data['has_answer'][$grade_key][$key] = WorkbookAnswer::join('a_book_1010', 'a_workbook_answer_1010.book', 'a_book_1010.bookcode')
                    ->where('a_book_1010.id', $value->id)
                    ->count();
                 if(!empty($value->bookcode)){
                    $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
                     if(!empty($answers_now)){
                         foreach ($answers_now as $answer_row){
                         $textname[] = $answer_row['textname'];
                         $answers[] = explode('|',$answer_row['answer']);
                         }
                         if(!empty($answers)){
                         $data['all_answer'][$grade_key][$key]['answers'] = $answers;
                         $data['all_answer'][$grade_key][$key]['answers_num'] = count($answers);
                         $data['all_answer'][$grade_key][$key]['textname'] = $textname;
                         $answers = [];
                         $textname = [];
                         }
                     }
                 }

            }

        }

        foreach ($data['all_version'] as $key=>$value){
            $version_array[$key]['id'] = strlen($value->id)>1?$value->id:'0'.$value->id;
            $version_array[$key]['text'] = $value->name;
        }
        foreach (config('workbook.grade') as $key=> $value){
            $grade_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $grade_array[$key-1]['text'] = $value;
        }
        foreach (config('workbook.subject_1010') as $key=> $value){
            $subject_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $subject_array[$key-1]['text'] = $value;
        }
        foreach ($data['all_volumes'] as $key=>$value){
            $volume_array[$key]['id'] = $value->code;
            $volume_array[$key]['text'] = $value->volumes;
        }
        $data['version_select'] = json_encode($version_array);
        $data['subject_select'] = json_encode($subject_array);
        $data['grade_select'] = json_encode($grade_array);
        $data['volume_select'] = json_encode($volume_array);
        return view('manage.book_arrange', compact('user', 'data'));
    }

    public function chapter(Request $request, $version = '0', $grade = '1',$subject = '1'){
        $sql_version = strlen($version)>1?$version:'0'.$version;
        $index_version = intval($version);
        $index_grade = intval($grade);
        $index_subject = intval($subject);
        $sql_subject = strlen($subject)>1?$subject:'0'.$subject;
        $sql_grade = strlen($grade)>1?$grade:'0'.$grade;
        //$user = $request->user();
        $data['subject'] = $sql_subject;
        $data['grade'] = $sql_grade;
        $data['version'] = $sql_version;
        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['code', 'volumes']);


        $all_book = ABook1010::orderBy('booksort','ASC')->get(['id', 'grade_id', 'subject_id', 'version_id']);

        foreach ($all_book as $value) {
            $version_now = intval($value->version_id);
            $grade_now = intval($value->grade_id);
            $subject_now = intval($value->subject_id);
            $data['sort_version'][$version_now][$grade_now][] = 1;
        }


//        $data['all_distinct_grade'] = ABook1010::where('a_book_1010.subject_id', $subject)
//            ->where('a_book_1010.version_id', $version)
////            ->join('book_version_type', 'a_book_1010.version_id', 'book_version_type.id')
////            ->select('a_book_1010.version_id', 'book_version_type.name')
////            ->orderBy('a_book_1010.version_id', 'ASC')
//            ->distinct()
//            ->get();

        $answers = [];
        $textname = [];

        if($index_grade==0){
            //get_all
            foreach (config('workbook.grade') as $key => $value) {
                if(isset($data['sort_version'][$index_version][$key])){
                    $grade_key = strlen($key)>1?$key:'0'.$key;
                    $data['all_book_now'][$key] = ABook1010::where('grade_id', $grade_key)
                        ->where('version_id', $sql_version)
                        ->select('id', 'bookname','bookcode','isbn', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'cover_photo', 'cover_photo_thumbnail', 'book_confirm')
                        ->orderBy('booksort', 'ASC')
                        ->get();
                }
            }
        }else{
            //get_single_grade
            $data['all_book_now'][$index_grade] = ABook1010::where('grade_id', $index_grade)
                ->where('version_id', $sql_version)
                ->select('id', 'bookname','bookcode','wrong_chapter','booksort','isbn', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'cover_photo', 'cover_photo_thumbnail', 'book_confirm')
                ->orderBy('booksort', 'ASC')
                ->paginate(10);
        }




        foreach ($data['all_book_now'] as $grade_key => $book) {
            foreach ($book as $key => $value) {

                $all_book = Workbook::where('booksort',$value->booksort)->select(['id','bookcode','bookname','chapter_confirm'])->orderBy('version_year','DESC')->take(10)->get();
                //dd($all_book);

                foreach ($all_book as $key1=>$value1){
                    $data['all_books_info'][$value->id][$value1->id] = array('bookname'=>$value1->bookname,'chapter_confirm'=>$value1->chapter_confirm);

                    $data['all_books_answers'][$value->id][$value1->id] = $value1->answers()->orderBy('text','asc')->select(['text','textname','answer'])->get();
                }

                //barcode相关
//                if($value->isbn!=''){
//                    $isbns = explode('|',$value->isbn);
//                    if(is_array($isbns)){
//                        $search_isbn = $isbns[0];
//                    }else{
//                        $search_isbn = $value->isbn;
//                    }
//
//                    $data['isbn'][$grade_key][$key] = BookToBuy::where('bar_code',$search_isbn)->select('img')->take(50)->get();
//                }

                //答案相关
//                $data['has_answer'][$grade_key][$key] = WorkbookAnswer::
//('a_book_1010', 'a_workbook_answer_1010.book', 'a_book_1010.bookcode')
//                    ->where('a_book_1010.id', $value->id)
//                    ->count();
//                 if(!empty($value->bookcode)){
//                    $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
//                     if(!empty($answers_now)){
//                         foreach ($answers_now as $answer_row){
//                         $textname[] = $answer_row['textname'];
//                         $answers[] = explode('|',$answer_row['answer']);
//                         }
//                         if(!empty($answers)){
//                         $data['all_answer'][$grade_key][$key]['answers'] = $answers;
//                         $data['all_answer'][$grade_key][$key]['answers_num'] = count($answers);
//                         $data['all_answer'][$grade_key][$key]['textname'] = $textname;
//                         $answers = [];
//                         $textname = [];
//                         }
//                     }
//                 }
            }

        }

       // dd($data['all_books_answers']);

        foreach ($data['all_books_answers'] as $key=>$all_book){

            if(count($all_book)>0) {
                foreach ($all_book as $workbook_key=>$answer_about){
                   if(count($answer_about)>0){
                       foreach ($answer_about as $answer_row){
                           $textname[] = $answer_row->textname;
                           $answers[] = explode('|',$answer_row->answer);
                       }
                       if(!empty($answers)){
                           $data['all_answer'][$key][$workbook_key]['answers'] = $answers;
                           $data['all_answer'][$key][$workbook_key]['answers_num'] = count($answers);
                           $data['all_answer'][$key][$workbook_key]['textname'] = $textname;
                           $answers = [];
                           $textname = [];
                       }
                   }
                }

            }
        }


//        foreach ($data['all_version'] as $key=>$value){
//            $version_array[$key]['id'] = strlen($value->id)>1?$value->id:'0'.$value->id;
//            $version_array[$key]['text'] = $value->name;
//        }
//        foreach (config('workbook.grade') as $key=> $value){
//            $grade_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
//            $grade_array[$key-1]['text'] = $value;
//        }
//        foreach (config('workbook.subject_1010') as $key=> $value){
//            $subject_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
//            $subject_array[$key-1]['text'] = $value;
//        }
//        foreach ($data['all_volumes'] as $key=>$value){
//            $volume_array[$key]['id'] = $value->code;
//            $volume_array[$key]['text'] = $value->volumes;
//        }
//        $data['version_select'] = json_encode($version_array);
//        $data['subject_select'] = json_encode($subject_array);
//        $data['grade_select'] = json_encode($grade_array);
//        $data['volume_select'] = json_encode($volume_array);

        return view('manage.book_chapter', compact('user', 'data'));
    }


}
