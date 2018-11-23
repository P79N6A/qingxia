<?php

namespace App\Http\Controllers\Manage;

use App\ABook1010;
use App\ABookKnow;
use App\BookToBuy;
use App\BookVersion;
use App\BookVersionType;
use App\Chapter;
use App\StandardAnswer;
use App\Volume;
use App\Workbook;
use App\WorkbookAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class WorkbookController extends Controller
{
    protected $user,$book;

    public function __construct()
    {
        $this->book = new Workbook();
    }

    public function index(Request $request,$subject=1){
        $subject = intval($subject)>0?intval($subject):1;
        //$user = $request->user();
        $books = $this->book->get_onlyname_book($subject);
        return view('manage.workbook_arrange',compact('books','subject'));
    }

    public function index_only(Request $request,$subject=1){
        $subject = intval($subject)>0?intval($subject):1;
        //$user = $request->user();
        $books = $this->book->get_onlycode_book($subject);
        return view('manage.workbook_arrange_only',compact('books','subject'));
    }

    public function edit(Request $request,$onlyname,$status=0){
        $booksort = Workbook::where('onlyname',$onlyname)->select('booksort')->first()->booksort;
        $for_book_id = ABook1010::where('booksort',$booksort)->select('id')->first();
        if($for_book_id){
            $book_id_now = $for_book_id->id;
        }else{
            $book_id_now = 0;
        }
        //$user = $request->user();
        $edits = $this->book->get_edit_book($onlyname,$status);
        $answers = [];
        $textname = [];
        $no_answer_num = 0;

        foreach ($edits as $key => $value){
            if(!empty($value->bookcode)){
                $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
                if(!empty($answers_now)){
                    foreach ($answers_now as $answer_row){
                        $textname[] = $answer_row['textname'];
                        $answers[] = explode('|',$answer_row['answer']);
                    }
                    if(empty($answers)){
                        $no_answer_num ++ ;
                        unset($edits[$key]);
                    }else{
                        $edits[$key]->answers = $answers;
                        $edits[$key]->textname = $textname;
                        $answers = [];
                        $textname = [];
                    }
                }else{
                    $no_answer_num ++ ;
                    unset($edits[$key]);
                }
            }else{
                $no_answer_num ++ ;
                unset($edits[$key]);
            }


//            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
//            if(!empty($chapters[0])){
//                foreach ($chapters as $key1=>$chapter){
//                    $chapter_name[] = $chapter->name;
//                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
//                    if(!empty($answers_now)){
//                        $answers[] = explode('|',$answers_now[0]->answers);
//                    }
//                }
//                $edits[$key]->answers = $answers;
//                $edits[$key]->chapter_name = $chapter_name;
//                $answers = [];
//                $chapter_name = [];
//            }else{
//                $no_answer_num ++ ;
//                unset($edits[$key]);
//            }

        }


        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');


        return view('manage.workbook_edit',compact('edits','sorts_note','version','press','onlyname','status','no_answer_num','book_id_now'));
    }

    public function edit_only(Request $request,$onlycode,$status=0){
        $booksort = Workbook::where('onlycode',$onlycode)->select('booksort')->first()->booksort;
        $for_book_id = ABook1010::where('booksort',$booksort)->select('id')->first();
        if($for_book_id){
            $book_id_now = $for_book_id->id;
        }else{
            $book_id_now = 0;
        }
        //$user = $request->user();
        $edits = $this->book->get_edit_book_only($onlycode,$status);
        $answers = [];
        $textname = [];
        $no_answer_num = 0;

        foreach ($edits as $key => $value){
            if(!empty($value->bookcode)){
                $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
                if(!empty($answers_now)){
                    foreach ($answers_now as $answer_row){
                        $textname[] = $answer_row['textname'];
                        $answers[] = explode('|',$answer_row['answer']);
                    }
                    if(empty($answers)){
                        $no_answer_num ++ ;
                        unset($edits[$key]);
                    }else{
                        $edits[$key]->answers = $answers;
                        $edits[$key]->textname = $textname;
                        $answers = [];
                        $textname = [];
                    }
                }else{
                    $no_answer_num ++ ;
                    unset($edits[$key]);
                }
            }else{
                $no_answer_num ++ ;
                unset($edits[$key]);
            }


//            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
//            if(!empty($chapters[0])){
//                foreach ($chapters as $key1=>$chapter){
//                    $chapter_name[] = $chapter->name;
//                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
//                    if(!empty($answers_now)){
//                        $answers[] = explode('|',$answers_now[0]->answers);
//                    }
//                }
//                $edits[$key]->answers = $answers;
//                $edits[$key]->chapter_name = $chapter_name;
//                $answers = [];
//                $chapter_name = [];
//            }else{
//                $no_answer_num ++ ;
//                unset($edits[$key]);
//            }

        }
        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');
        return view('manage.workbook_edit_only',compact('edits','sorts_note','version','press','onlycode','status','no_answer_num','book_id_now'));
    }

    public function edit_onlyname(Request $request,$subject,$status=0){
        $onlyname = '';
        $book_id_now = '';
        //$user = $request->user();
        $edits = $this->book->get_edit_book($onlyname,$status,$subject);
        $answers = [];
        $textname = [];
        $no_answer_num = 0;


//        foreach ($edits as $key => $value){
//            if(!empty($value->bookcode)){
//                $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
//
//                if(!empty($answers_now)){
//                    foreach ($answers_now as $answer_row){
//                        $textname[] = $answer_row['textname'];
//                        $answers[] = explode('|',$answer_row['answer']);
//                    }
//                    if(empty($answers)){
//                        $no_answer_num ++ ;
//                        unset($edits[$key]);
//                    }else{
//                        $edits[$key]->answers = $answers;
//                        $edits[$key]->textname = $textname;
//                        $answers = [];
//                        $textname = [];
//                    }
//                }else{
//                    $no_answer_num ++ ;
//                   // unset($edits[$key]);
//                }
//            }else{
//                $no_answer_num ++ ;
//                //unset($edits[$key]);
//            }
//
//
//
////            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
////            if(!empty($chapters[0])){
////                foreach ($chapters as $key1=>$chapter){
////                    $chapter_name[] = $chapter->name;
////                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
////                    if(!empty($answers_now)){
////                        $answers[] = explode('|',$answers_now[0]->answers);
////                    }
////                }
////                $edits[$key]->answers = $answers;
////                $edits[$key]->chapter_name = $chapter_name;
////                $answers = [];
////                $chapter_name = [];
////            }else{
////                $no_answer_num ++ ;
////                unset($edits[$key]);
////            }
//
//        }

        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');

        $no_onlyname = 1;
        return view('manage.workbook_edit',compact('edits','sorts_note','version','press','onlyname','status','no_answer_num','no_onlyname','subject','book_id_now'));
    }

    public function edit_onlycode(Request $request,$subject,$status=0){
        $onlycode = '';
        $book_id_now = '';
        //$user = $request->user();
        $edits = $this->book->get_edit_book_only($onlycode,$status,$subject);
        $answers = [];
        $textname = [];
        $no_answer_num = 0;


        foreach ($edits as $key => $value) {
            if (!empty($value->bookcode)) {
                $answers_now = WorkbookAnswer::where('book', $value->bookcode)->orderBy('text', 'asc')->select('answer', 'textname')->get();
                if (!empty($answers_now)) {
                    foreach ($answers_now as $answer_row) {
                        $textname[] = $answer_row['textname'];
                        $answers[] = explode('|', $answer_row['answer']);
                    }
                    if (empty($answers)) {
                        $no_answer_num++;
                        unset($edits[$key]);
                    } else {
                        $edits[$key]->answers = $answers;
                        $edits[$key]->textname = $textname;
                        $answers = [];
                        $textname = [];
                    }
                } else {
                    $no_answer_num++;
                    unset($edits[$key]);
                }
            } else {
                $no_answer_num++;
                //unset($edits[$key]);
            }
        }
//
//
//
////            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
////            if(!empty($chapters[0])){
////                foreach ($chapters as $key1=>$chapter){
////                    $chapter_name[] = $chapter->name;
////                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
////                    if(!empty($answers_now)){
////                        $answers[] = explode('|',$answers_now[0]->answers);
////                    }
////                }
////                $edits[$key]->answers = $answers;
////                $edits[$key]->chapter_name = $chapter_name;
////                $answers = [];
////                $chapter_name = [];
////            }else{
////                $no_answer_num ++ ;
////                unset($edits[$key]);
////            }
//
//        }

        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');

        $no_onlyname = 1;
        return view('manage.workbook_edit_only',compact('edits','sorts_note','version','press','onlycode','status','no_answer_num','no_onlyname','subject','book_id_now'));
    }


    public function answer(Request $request,$book_id){

        //$user = $request->user();
        $chapters = Chapter::where('book_id',$book_id)->select('id','name')->get();
        foreach ($chapters as $key=>$chapter){

            $answers[$key]['chapter_name'] = $chapter->name;

            $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->orderBy('chapter_id','asc')->get('answers');
            if(!empty($answers_now)){
                $answers[$key]['answers'] = explode('|',$answers_now[0]->answers);
            }
        }


        return view('manage.lxc_answer',compact('answers'));
    }


    /***workbook_arrange****/
    public function workbook_arrange(Request $request,$subject=1){
        $subject = intval($subject)>0?intval($subject):1;
        //$user = $request->user();
        $books = $this->book->get_onlyname_book($subject);
        return view('manage.workbook_arrange_v2',compact('books','subject'));
    }

    public function edit_v2(Request $request,$onlyname,$status=0){
        $booksort = Workbook::where('onlyname',$onlyname)->select('booksort')->first()->booksort;
        $book_chapter = ABookKnow::where('booksort',$booksort)->select(['chapter','chaptername'])->orderBy('chapter','ASC')->get();
        //$user = $request->user();
        $edits = $this->book->get_edit_book($onlyname,$status);
        $answers = [];
        $textname = [];
        $no_answer_num = 0;

        foreach ($edits as $key => $value){
            if(!empty($value->bookcode)){
                $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
                if(!empty($answers_now)){
                    foreach ($answers_now as $answer_row){
                        $textname[] = $answer_row['textname'];
                        $answers[] = explode('|',$answer_row['answer']);
                    }
                    if(empty($answers)){
                        $no_answer_num ++ ;
                        unset($edits[$key]);
                    }else{
                        $edits[$key]->answers = $answers;
                        $edits[$key]->textname = $textname;
                        $answers = [];
                        $textname = [];
                    }
                }else{
                    $no_answer_num ++ ;
                    unset($edits[$key]);
                }
            }else{
                $no_answer_num ++ ;
                unset($edits[$key]);
            }


//            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
//            if(!empty($chapters[0])){
//                foreach ($chapters as $key1=>$chapter){
//                    $chapter_name[] = $chapter->name;
//                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
//                    if(!empty($answers_now)){
//                        $answers[] = explode('|',$answers_now[0]->answers);
//                    }
//                }
//                $edits[$key]->answers = $answers;
//                $edits[$key]->chapter_name = $chapter_name;
//                $answers = [];
//                $chapter_name = [];
//            }else{
//                $no_answer_num ++ ;
//                unset($edits[$key]);
//            }

        }


        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');

        //dd($edits);
        return view('manage.workbook_edit_v2',compact('edits','sorts_note','version','press','onlyname','status','no_answer_num'));
    }


    public function edit_onlyname_v2(Request $request,$subject,$status=0){
        $onlyname = '';
        //$user = $request->user();
        $edits = $this->book->get_edit_book($onlyname,$status,$subject);
        $answers = [];
        $textname = [];
        $no_answer_num = 0;



        foreach ($edits as $key => $value){
            if(!empty($value->bookcode)){
                $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get('answer','textname');

                if(!empty($answers_now)){
                    foreach ($answers_now as $answer_row){
                        $textname[] = $answer_row['textname'];
                        $answers[] = explode('|',$answer_row['answer']);
                    }
                    if(empty($answers)){
                        $no_answer_num ++ ;
                        //unset($edits[$key]);
                    }else{
                        $edits[$key]->answers = $answers;
                        $edits[$key]->textname = $textname;
                        $answers = [];
                        $textname = [];
                    }
                }else{
                    $no_answer_num ++ ;
                    //unset($edits[$key]);
                }
            }else{
                $no_answer_num ++ ;
                //unset($edits[$key]);
            }



//            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
//            if(!empty($chapters[0])){
//                foreach ($chapters as $key1=>$chapter){
//                    $chapter_name[] = $chapter->name;
//                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
//                    if(!empty($answers_now)){
//                        $answers[] = explode('|',$answers_now[0]->answers);
//                    }
//                }
//                $edits[$key]->answers = $answers;
//                $edits[$key]->chapter_name = $chapter_name;
//                $answers = [];
//                $chapter_name = [];
//            }else{
//                $no_answer_num ++ ;
//                unset($edits[$key]);
//            }

        }

        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');

        $no_onlyname = 1;
        return view('manage.workbook_edit_v2',compact('edits','sorts_note','version','press','onlyname','status','no_answer_num','no_onlyname','subject'));
    }

    public function workbook_cover(Request $request, $version = '0', $grade = '1',$subject = '1'){
        $sql_version = strlen($version)>1?$version:'0'.$version;
        $index_version = intval($version);
        $index_grade = intval($grade);
        $index_subject = intval($subject);
        $sql_subject = strlen($subject)>1?$subject:'0'.$subject;
        $sql_grade = strlen($grade)>1?$grade:'0'.$grade;
        //$user = $request->user();
        $data['subject'] = $index_subject;
        $data['grade'] = $index_grade;
        $data['version'] = $index_version;
        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['code', 'volumes']);

//        dd($data['all_version']);die;
//        $all_book = Workbook::where('has_answer',1)->where('book_confirm',0)->orderBy('booksort','ASC')->take(100)->get(['id', 'grade_id', 'subject_id', 'version_id']);

//        dd($all_book);
//        foreach ($all_book as $value) {
//            $version_now = intval($value->version_id);
//            $grade_now = intval($value->grade_id);
//            $subject_now = intval($value->subject_id);
//            $data['sort_version'][$version_now][$grade_now][] = 1;
//        }
        $answers = [];
        $textname = [];



        $data['all_book_now'][$index_grade] = Workbook::where('grade_id', $index_grade)
            ->where('version_id', $sql_version)
            ->select('id', 'bookname','bookcode','isbn', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'cover_photo', 'cover_photo_thumbnail', 'book_confirm')
            ->orderBy('booksort', 'ASC')
            ->paginate(30);


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
//                $data['has_answer'][$grade_key][$key] = WorkbookAnswer::join('a_book_1010', 'a_workbook_answer_1010.book', 'a_book_1010.bookcode')
//                    ->where('a_book_1010.id', $value->id)
//                    ->count();
//                if(!empty($value->bookcode)){
//                    $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
//                    if(!empty($answers_now)){
//                        foreach ($answers_now as $answer_row){
//                            $textname[] = $answer_row['textname'];
//                            $answers[] = explode('|',$answer_row['answer']);
//                        }
//                        if(!empty($answers)){
//                            $data['all_answer'][$grade_key][$key]['answers'] = $answers;
//                            $data['all_answer'][$grade_key][$key]['answers_num'] = count($answers);
//                            $data['all_answer'][$grade_key][$key]['textname'] = $textname;
//                            $answers = [];
//                            $textname = [];
//                        }
//                    }
//                }

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
        return view('manage.workbook_cover', compact( 'data'));
    }

}
