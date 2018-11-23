<?php

namespace App\OneModel;

use Illuminate\Database\Eloquent\Model;

class AWorkbook extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_workbook';
    public $guarded = array();
    public $timestamps = false;

    public function hasOnly()
    {
        return $this->hasOne('App\OneModel\AOnlyBook','onlyid','onlyid');
    }

    public function hasAnswers()
    {
        return $this->hasMany('App\OneModel\AWorkbookAnswer','bookid','id')->orderBy('text','asc');
    }

    public function hasChapters()
    {
        return $this->hasMany('App\OneModel\AThreadChapter','onlyid','onlyid')->orderBy('num','asc');
    }
}
