<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sort extends Model
{
    public $connection = 'mysql_local';
    #public $connection = 'mysql_main_rds';
    protected $table = 'sort';
    public $timestamps = false;
    protected $guarded = array();

    public function has_books()
    {
      return $this->hasMany('App\AWorkbookMain','sort','id')->orderBy('grade_id')->orderBy('subject_id')->orderBy('volumes_id')->orderBy('version_id');
    }
    
    public function get_all_sort(){
        return Sort::all();
    }

    public function sub_sorts()
    {
      return $this->hasMany('App\Subsort','pid','id');
    }
    
    public function about_books()
    {
      //return $this->hasMany('App\Subsort','pid','id')->with('has_books');
      return $this->hasManyThrough('App\AWorkbookMain','App\Subsort','pid','ssort_id','id');
    }


    public function update_sort_info($type,$sort,$main_word){
//        $distinct_main_word = Book::where('sort',$sort)->where('main_word','<>','')->where('main_status','-1')->distinct()->select('main_word')->get('main_word');
//        $distinct_sub_sort = Book::where('sort',$sort)->where('sub_sort','<>','')->where('main_status','-1')->distinct()->select('sub_sort')->get('sub_sort');
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

        $sort_about = Sort::find($sort,['id','main_word','sub_sort']);

        if($type=='main_word'){
            $update_word = $sort_about->main_word;
        }else{
            $update_word = $sort_about->sub_sort;
        }

        if(!empty($update_word)){
            $sort_main_word = explode(',',$update_word);
            if(!is_array($sort_main_word)){
                $sort_main_word = array($sort_main_word);
            }
            if(!in_array($main_word,$sort_main_word)){
                array_push($sort_main_word,$main_word);
            }
            if($type=='main_word') {
                $sort_about->main_word = implode(',', $sort_main_word);
            }else{
                $sort_about->sub_sort = implode(',', $sort_main_word);;
            }
        }else{
            if($type=='main_word') {
                $sort_about->main_word = $main_word;
            }else{
                $sort_about->sub_sort = $main_word;
            }
        }

        if($sort_about->save()){
            return 1;
        }else{
            return 0;
        }

    }

    public function del_sort_info($type,$sort,$main_word){
        $sort_about = Sort::find($sort,['id','main_word','sub_sort']);
        if($type=='main_word'){
            $del_word = $sort_about->main_word;
        }else{
            $del_word = $sort_about->sub_sort;
        }
        if(!empty($del_word)){
            $sort_del_word = explode(',',$del_word);
            if(!is_array($sort_del_word)){
                $sort_del_word = array($sort_del_word);
            }
            foreach ($sort_del_word as $key=>$value){
                if($main_word===$value){
                    unset($sort_del_word[$key]);
                }
            }
            if($type=='main_word') {
                $sort_about->main_word = implode(',', $sort_del_word);
            }else{
                $sort_about->sub_sort = implode(',', $sort_del_word);;
            }
            if($sort_del_word!=$del_word){
                if($sort_about->save()){
                    return 1;
                }
            }
            return 0;
        }else{
            return 0;
        }
    }

    public function getSortByKey($key='',$count=10){
        if(!$key){
            return Sort::limit($count)->pluck('name');
        }
        return Sort::where("name","like","%$key%")->limit($count)->pluck('name');

    }
}
