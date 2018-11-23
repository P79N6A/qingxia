<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATongjiSearchIsbnNew extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_tongji_search_isbn_hot_copy';
    protected $guarded = array();
    public $timestamps  =false;


    public function has_isbn_detail()
    {
        return $this->hasOne('App\LocalModel\IsbnAll','isbn','isbn')->where('isbn','>','978');
    }

    public function has_need_book()
    {
        return $this->hasMany('App\PreMWorkbookUser','isbn','isbn')->orderBy('addtime','desc');
    }
}
