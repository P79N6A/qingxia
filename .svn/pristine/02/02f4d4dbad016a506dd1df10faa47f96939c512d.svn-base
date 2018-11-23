<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class AWorkbook1010Test extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_workbook_1010_new';
    protected $guarded = array();
    public $timestamps = false;
    
    public function has_answers()
    {
        return $this->hasMany('App\LocalModel\AWorkbookAnswerTest','bookid','id')->orderBy('text','asc');
    }

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }
    
    public function has_user()
    {
        return $this->hasOne('App\User','id','update_uid');
    }
    
}
