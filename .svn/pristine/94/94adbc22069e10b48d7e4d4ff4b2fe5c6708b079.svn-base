<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWorkbook1010 extends Model
{
    #public $connection = 'mysql_local';
    public $connection = 'mysql_main_rds';
    protected $table = 'a_workbook_1010';
    public $timestamps = false;
    protected $guarded = array();

    public function has_hd_book()
    {
        return $this->hasOne('App\Book','id','hdid');
    }

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }
    
    public function has_answer()
    {
        return $this->hasMany('App\WorkbookAnswerRds','bookid','id');
    }


    public function hasOnly()
    {
        return $this->hasOne('App\LocalModel\NewBuy\NewOnly','newname','newname');
    }

    public function has_newonly(){
        return $this->hasOne('App\LocalModel\NewBuy\NewOnly','newname','newname');
    }

    public function has_user()
    {
        return $this->hasOne('App\User','id','uid');
    }

}


