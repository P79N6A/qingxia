<?php

namespace App\Http\Controllers\Manage;

use App\BookVersionType;
use App\Chapter;
use App\StandardAnswer;
use App\Volume;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Book;
use App\BookVersion;
use App\Sort;
use Illuminate\Support\Facades\DB;
use App\User;


class LxcController extends Controller
{
    protected $user,$book;

    public function __construct()
    {
        $this->book = new Book();
    }

    public function index(Request $request,$subject=1){
        $subject = intval($subject)>0?intval($subject):1;
        //$user = $request->user();
        $books = $this->book->get_onlyname_book($subject);
        return view('manage.lxc_arrange',compact('books','subject'));
    }

    public function index_v2(Request $request,$subject=1){
        $subject = intval($subject)>0?intval($subject):1;
        //$user = $request->user();
        $books = $this->book->get_onlyname_book_v2($subject);
        return view('manage.lxc_arrange_v2',compact('books','subject'));
    }

    public function edit(Request $request,$onlyname,$status=0){

        //$user = $request->user();
        $edits = $this->book->get_edit_book($onlyname,$status);
        $answers = [];
        $chapter_name = [];
        $no_answer_num = 0;

        foreach ($edits as $key => $value){
            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
            if(!empty($chapters[0])){
                foreach ($chapters as $key1=>$chapter){
                    $chapter_name[] = $chapter->name;
                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
                    if(!empty($answers_now[0])){
                        $answers[] = explode('|',$answers_now[0]->answers);
                    }
                }
                $edits[$key]->answers = $answers;
                $edits[$key]->chapter_name = $chapter_name;
                if(empty($answers)){
                    $no_answer_num ++ ;
                    unset($edits[$key]);
                }
                $answers = [];
                $chapter_name = [];
            }else{
                $no_answer_num ++ ;
                unset($edits[$key]);
            }

        }

        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');
        $volumes = Volume::all('id','volumes');

        return view('manage.lxc_edit',compact('edits','sorts_note','version','press','onlyname','status','no_answer_num','volumes'));
    }

    public function edit_v2(Request $request,$onlyname,$status=0){

        //$user = $request->user();
        $edits = $this->book->get_edit_book_v2($onlyname,$status);
        $answers = [];
        $chapter_name = [];
        $no_answer_num = 0;

        foreach ($edits as $key => $value){
            $chapters = Chapter::where('book_id',$value->id)->select('id','name')->orderBy('id','asc')->get();
            if(!empty($chapters[0])){
                foreach ($chapters as $key1=>$chapter){
                    $chapter_name[] = $chapter->name;
                    $answers_now = StandardAnswer::where('chapter_id',$chapter->id)->select('answers')->get('answers');
                    if(!empty($answers_now[0])){
                        $answers[] = explode('|',$answers_now[0]->answers);
                    }
                }
                $edits[$key]->answers = $answers;
                $edits[$key]->chapter_name = $chapter_name;
                if(empty($answers)){
                    $no_answer_num ++ ;
                    //unset($edits[$key]);
                }
                $answers = [];
                $chapter_name = [];
            }else{
                $no_answer_num ++ ;
                //unset($edits[$key]);
            }

        }

        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');
        $volumes = Volume::all('id','volumes');

        return view('manage.lxc_edit_v2',compact('edits','sorts_note','version','press','onlyname','status','no_answer_num','volumes'));
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

    public function sort(Request $request,$sort,$subject=1,$grade=1){
        //$user = $request->user();
        $data['subject'] = $subject;
        $data['grade'] = $grade;
        $data['distinct_sort'] = Book::where('sort',$sort)
            ->where('main_status','-1')
            ->select('grade_id','subject_id')->distinct()->get();
        foreach ($data['distinct_sort'] as $value){
            $data['sort_grade'][$value->grade_id][$value->subject_id] = Book::Where('sort',$sort)
                ->where('main_status','-1')
                ->where('subject_id',$value->subject_id)
                ->where('grade_id',$value->grade_id)
                ->orderBy('grade_id','ASC')
                ->orderBy('subject_id','ASC')
                ->groupBy('grade_id','subject_id')
                ->count();
        }
        $data['all_sort_book'] = Book::where('sort',$sort)->where('subject_id',$subject)
            ->where('grade_id',$grade)
            ->where('main_status','-1')
            ->select('*')->paginate(20);
        $data['sort_info'] = Sort::find($sort);

        $data['sort_extra'] = $this->book->get_sort_info($sort);

        return view('manage.lxc_sort_about',compact('data'));
    }

}
