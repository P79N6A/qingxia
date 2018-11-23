<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class New1010 extends Model
{
    #protected $connection = 'mysql_local';
    protected $connection = 'mysql_main_rds';
    protected $table = 'a_workbook_1010';

    public function hasAnswers()
    {
        return $this->hasMany('App\LocalModel\NewBuy\NewAnswerServer','bookid','id')->where('status',1)->orderBy('text','asc');
    }

}
