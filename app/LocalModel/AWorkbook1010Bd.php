<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class AWorkbook1010Bd extends Model
{
    protected $connection = 'mysql_zjb';
    protected $table = 'a_workbook_1010_bd';
    public $timestamps = false;
    
    public function has_answers()
    {
        return $this->hasMany('App\LocalModel\AWorkbookAnswerBd','bookid','id')->orderBy('text','asc');
    }
    
}
