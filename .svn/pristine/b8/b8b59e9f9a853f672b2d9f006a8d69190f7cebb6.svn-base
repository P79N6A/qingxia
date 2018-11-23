<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATongjiBuy extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_tongji_buy';
    public $timestamps = false;
    public $guarded = array();
    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }


    public function has_operate_user()
    {
        return $this->hasOne('App\ASortUid','sort','sort');
    }

    public function has_self_jj_book()
    {
        return $this->hasMany('App\ATongjiBuy','sort','sort')->where([['book_id','>',0],['jj',1]])->orderBy('status','desc')->orderBy('collect_count','desc');
    }

    //取a_workbook_new
    public function has_self_new_book()
    {
        return $this->hasMany('App\AWorkbookNew','sort','sort')->where([['grade_id','<',10],['grade_id','>',2],['subject_id','>=',1],['subject_id','<=',5],['volumes_id',2]]);
    }

    public function has_self_hd_book()
    {
        return $this->hasMany('App\ATongjiBuy','sort','sort')->where([['book_id','>',0],['jj',0]])->orderBy('status','desc')->orderBy('collect_count','desc');
    }

    public function has_jj_book()
    {
        return $this->hasOne('App\AWorkbook1010','id','book_id');
    }

    public function has_hd_book()
    {
        return $this->hasOne('App\Book','id','book_id');
    }





    public function has_hd_books()
    {
        return $this->hasMany('App\Book','sort','sort');
    }

    public function has_jj_books()
    {
        return $this->hasMany('App\AWorkbook1010','sort','sort');
    }
}
