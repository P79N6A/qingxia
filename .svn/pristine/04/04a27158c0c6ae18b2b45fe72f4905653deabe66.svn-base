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

class BookController extends Controller
{
    public function index(Request $request, $subject = 1, $grade = 1)
    {
		$subject = intval($subject);
		$grade = intval($grade);
		
        //$user = $request->user();
        $data['subject'] = $subject;
        $data['grade'] = $grade;
        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['id', 'volumes']);
        $all_book = Workbook::where('sort', 0)->where('bookname', 'like', '%课本%')
            ->select('id', 'grade_id', 'subject_id', 'version_id')
            ->distinct()
            ->get();
        foreach ($all_book as $value) {
			$grade_now = intval($value->grade_id);
			$subject_now = intval($value->subject_id);
            $data['sort_grade'][$grade_now][$subject_now][] = 1;
        }
        $data['all_distinct_version'] = Workbook::where('sort', 0)
            ->where('bookname', 'like', '%课本%')
            ->join('book_version_type', 'a_workbook_1010.version_id', 'book_version_type.id')
            ->where('a_workbook_1010.subject_id', $subject)
            ->where('a_workbook_1010.grade_id', $grade)
            ->select('a_workbook_1010.version_id', 'book_version_type.name')
            ->orderBy('a_workbook_1010.version_id', 'ASC')
            ->distinct()
            ->get();
        $answers = [];
        $textname = [];


        foreach ($data['all_distinct_version'] as $key => $value) {
            $data['all_book_now'][$value->version_id] = Workbook::where('sort', 0)
                ->where('bookname', 'like', '%课本%')
                ->where('subject_id', $subject)
                ->where('grade_id', $grade)
                ->where('version_id', $value->version_id)
                ->select('id', 'bookname','bookcode', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'cover_photo', 'cover_photo_thumbnail', 'book_confirm')
                ->orderBy('volumes_id', 'ASC')
                ->get();
        }
        foreach ($data['all_book_now'] as $book_key => $book) {
            foreach ($book as $key => $value) {
                $data['has_answer'][$book_key][$key] = WorkbookAnswer::join('a_workbook_1010', 'a_workbook_answer_1010.book', 'a_workbook_1010.bookcode')
                    ->where('a_workbook_1010.id', $value->id)
                    ->count();
                if(!empty($value->bookcode)){
                    $answers_now = WorkbookAnswer::where('book',$value->bookcode)->orderBy('text','asc')->select('answer','textname')->get();
                    if(!empty($answers_now)){
                        foreach ($answers_now as $answer_row){
                            $textname[] = $answer_row['textname'];
                            $answers[] = explode('|',$answer_row['answer']);
                        }
                        if(!empty($answers)){
                            $data['all_answer'][$book_key][$key]['answers'] = $answers;
                            $data['all_answer'][$book_key][$key]['answers_num'] = count($answers);
                            $data['all_answer'][$book_key][$key]['textname'] = $textname;
                            $answers = [];
                            $textname = [];
                        }
                    }
                }
            }
        }





        return view('manage.book_arrange', compact('user', 'data'));
    }
}
