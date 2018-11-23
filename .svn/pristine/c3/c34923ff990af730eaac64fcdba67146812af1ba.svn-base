<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Workbook extends Model
{
    protected $table = 'a_workbook_1010';
    public $timestamps = false;
    //protected $fillable = ['grade_id','isbn','cover_photo_thumbnail','subject_id','volumes_id','bookname', 'book_confirm','name_confirm','version_id','sort_name','special_info','special_info_2','version_year','press_id','district'];

    public function get_onlyname_book($subject)
    {
        $subject = strlen($subject)>1?$subject:'0'.$subject;
        $books = Workbook::where('subject_id', $subject)
            ->where('has_answer',1)
            ->select(['onlyname',DB::raw('count(*) as total'),DB::raw('sum(stay) as stays')])
            ->groupBy('onlyname')
            ->orderBy('stays', 'desc')
            ->paginate(15);
        foreach ($books as $key => $book){
            $book->confirm_num = Workbook::where('onlyname', '=', $book->onlyname)->where('has_answer','=',1)->where('name_confirm','=',1)->count();
        }

        return $books;
    }

    public function get_onlycode_book($subject){
        $books = Workbook::where('subject_id', $subject)
            ->where('has_answer',1)
            ->select(['onlycode',DB::raw('count(*) as total'),DB::raw('sum(stay) as stays')])
            ->groupBy('onlycode')
            ->having(DB::raw('count(*)'),'>',1)
            ->orderBy('total', 'desc')
            ->paginate(15);
        return $books;
    }

    public function get_edit_book($onlyname,$status=0,$subject='')
    {

//      DB::enableQueryLog();

        $subject = strlen($subject)>1?$subject:'0'.$subject;
        $books = Workbook::where('a_workbook_1010.onlyname', '=', $onlyname)
            ->where('name_confirm','=',$status)
            ->where(function($query) use($subject) {
                if (!empty($subject)) {
                    $query->where('subject_id','=',$subject);
                }
            })
            ->leftJoin('sort', 'a_workbook_1010.sort', '=', 'sort.id')
            ->leftJoin('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->leftJoin('book_version_type', 'a_workbook_1010.version_id', '=', 'book_version_type.id')
            ->select(['a_workbook_1010.*', 'sort.note as sort_note', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('a_workbook_1010.version_year','desc')
            ->paginate(30);


//        dd(DB::getQueryLog());

        return $books;
    }

    public function get_edit_book_only($onlycode,$status=0,$subject='')
    {

//      DB::enableQueryLog();

        $subject = strlen($subject)>1?$subject:'0'.$subject;
        $books = Workbook::where('a_workbook_1010.onlycode', '=', $onlycode)
            ->where(function($query) use($subject) {
                if (!empty($subject)) {
                    $query->where('subject_id','=',$subject);
                }
            })
            ->leftJoin('sort', 'a_workbook_1010.sort', '=', 'sort.id')
            ->leftJoin('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->leftJoin('book_version_type', 'a_workbook_1010.version_id', '=', 'book_version_type.id')
            ->select(['a_workbook_1010.*', 'sort.note as sort_note', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('a_workbook_1010.version_year','desc')
            ->paginate(30);


//        dd(DB::getQueryLog());

        return $books;
    }

    public function answers(){
        return $this->hasMany('App\WorkbookAnswer','book','bookcode');
    }

    public function chapters(){
        return $this->hasMany('App\AWorkbookKnow','booksort','booksort');
    }

    public function has_answer($bookcode){
        WorkbookAnswer::where('book',$bookcode)->count();
    }

    public function getListByIsbn($isbn){
        return Workbook::where('isbn',$isbn)
            ->select("id","bookname","isbn","grade_name","volume_name","subject_name","version_name","sort_name","cover")
            ->paginate(20);
    }

}
