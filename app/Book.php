<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{

    protected $table = 'book';
    protected $connection = 'mysql_local';
    protected $fillable = ['name', 'subject_id', 'grade_id',
        'book_version_id', 'volumes', 'press', 'version', 'sort', 'name_confirm',
        'onlyname', 'special_info','special_info_2','main_word','sub_sort','sub_version' ,'o_uid', 'updated_at'
    ];

    public function has_main_book()
    {
        return $this->hasOne('App\AWorkbook1010','hdid','id');
    }


    public function get_onlyname_book($subject)
    {
        $books = Book::where('subject_id', $subject)
            ->select(['onlyname', DB::raw('count(*) as total')])
            ->groupBy('onlyname')
            ->orderBy('total', 'desc')
            ->paginate(15);


        foreach ($books as $book){

            $book->confirm_num = Book::where('book.onlyname', '=', $book->onlyname)->where('name_confirm','=',1)->count();
        }
        return $books;
    }

    public function get_onlyname_book_v2($subject)
    {
        $books = Book::where('subject_id', $subject)
            ->where(function ($query){
                $query->where('special_info','<>','')->orWhere('special_info_2','<>','');
            })
            ->select(['onlyname', DB::raw('count(*) as total')])
            ->groupBy('onlyname')
            ->orderBy('total', 'desc')
            ->paginate(15);

        foreach ($books as $book){

            $book->confirm_num = Book::where('book.onlyname', '=', $book->onlyname)
                ->where('name_confirm','=',1)
                ->where(function ($query){
                    $query->where('special_info','<>','')->orWhere('special_info_2','<>','');
                })
                ->count();
        }
        return $books;
    }

    public function get_edit_book($onlyname,$status)
    {

//        DB::enableQueryLog();
        $books = Book::where('book.onlyname', '=', $onlyname)
            ->where('name_confirm','=',$status)
            ->join('sort', 'book.sort', '=', 'sort.id')
            ->join('book_version', 'book.press', '=', 'book_version.id')
            ->join('book_version_type', 'book.book_version_id', '=', 'book_version_type.id')
            ->select(['book.*', 'sort.name as sort_name', 'sort.note as sort_note', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('book.version','desc')
            ->paginate(30);

//        dd($books);
//        dd(DB::getQueryLog());

        return $books;
    }

    public function get_edit_book_v2($onlyname,$status)
    {

//        DB::enableQueryLog();
        $books = Book::where('book.onlyname', '=', $onlyname)
            ->where(function ($query){
                $query->where('special_info','<>','')->orWhere('special_info_2','<>','');
            })
            ->join('sort', 'book.sort', '=', 'sort.id')
            ->join('book_version', 'book.press', '=', 'book_version.id')
            ->join('book_version_type', 'book.book_version_id', '=', 'book_version_type.id')
            ->select(['book.*', 'sort.name as sort_name', 'sort.note as sort_note', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('book.version','desc')
            ->paginate(30);


//        dd(DB::getQueryLog());

        return $books;
    }

    public function get_book_connect_name($id)
    {
        $book = Book::where('book.id', intval($id))
            ->join('sort', 'book.sort', '=', 'sort.id')
            ->join('book_version_type', 'book.book_version_id', '=', 'book_version_type.id')
            ->select(['book.grade_id', 'book.subject_id', 'book.volumes', 'book.special_info', 'sort.name as sort_name', 'book_version_type.name as version_name'])
            ->get();
        if ($book) {
            $now_book = $book[0];
            $book_name = $now_book->sort_name;
            $book_grade = config('workbook.grade')[$now_book->grade_id];
            $book_subject = config('workbook.subject')[$now_book->subject_id];
            $book_volumes = config('workbook.volumes')[$now_book->volumes];
            $book_version_name = $now_book->version_name;
            $book_special_info = $now_book->special_info;
            $book_connect_name = $book_name . $book_grade . $book_subject . $book_volumes . $book_version_name . $book_special_info;

        } else {
            $book_connect_name = '';
        }
        return $book_connect_name;
    }

    public function get_sort_name(){
        return $this->hasOne('sort','id','sort');
    }

    public function get_sort_info($sort){
        $sort = Sort::find($sort,['main_word','sub_sort']);
        return $sort;
//        $distinct_main_word = Book::where('sort',$sort)->where('main_word','<>','')->distinct()->select('main_word')->get('main_word');
//        $distinct_sub_sort = Book::where('sort',$sort)->where('sub_sort','<>','')->distinct()->select('sub_sort')->get('sub_sort');
//        $data['main_word_string'] = '';
//        $data['sub_sort_string'] = '';
//        if($distinct_main_word->count()>0){
//            $main_words = array();
//            foreach ($distinct_main_word as $value){
//                $main_words[] = $value->main_word;
//            }
//            $data['main_word_string'] = implode(',',$main_words);
//        }
//        if($distinct_sub_sort->count()>0){
//            $sub_sorts = array();
//            foreach ($distinct_sub_sort as $value){
//                $sub_sorts[] = $value->sub_sort;
//            }
//            $data['sub_sort_string'] = implode(',',$sub_sorts);
//        }
//        return $data;
    }
}
