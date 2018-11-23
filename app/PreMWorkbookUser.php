<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMWorkbookUser extends Model
{
    protected $table = 'pre_m_workbook_user';
    #public $connection = 'mysql_local';
    public $connection = 'mysql_main_rds';
    public $timestamps = false;
    protected $guarded = array();
    
    public function answers()
    {
        return $this->hasOne('App\PreMWorkbookAnswerUser','book_id','id');
    }

    public function has_ssort(){
        return $this->hasMany('App\ASubsort','sort_id','sort_id');
    }
    public function has_sort(){
        return $this->hasOne('App\Sort','id','sort');
    }

    public function has_num()
    {
        return $this->hasMany('App\PreMWorkbookUser','isbn','isbn');
    }

    public function has_offical_book()
    {
        return $this->hasOne('App\AWorkbookMain','id','to_book_id');
    }

    public function hotbook29(){
        return $this->hasONe('App\ATongjiHotbook29','isbn','isbn');
    }

}
