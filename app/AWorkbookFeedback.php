<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWorkbookFeedback extends Model
{
    protected $table = 'a_workbook_feedback';
    protected $connection = 'mysql_local';
    protected $guarded = array();
    public $timestamps = false;


    public function has_book()
    {
        return $this->hasOne('App\AWorkbook1010','id','bookid');
    }

    public function has_user_book(){
        return $this->hasOne('App\PreMWorkbookUser','id','bookid');
    }

    public function has_user()
    {
        return $this->hasOne('App\User','id','update_uid');
    }

    public function hasAnswer()
    {
        return $this->hasMany('App\WorkbookAnswer','bookid','bookid');
    }


}
