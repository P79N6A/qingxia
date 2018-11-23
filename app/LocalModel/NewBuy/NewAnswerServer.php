<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewAnswerServer extends Model
{
    #public $connection = 'mysql_local';
    protected $connection = 'mysql_main_rds';
    public $table = 'a_workbook_answer_1010';
    public $timestamps = false;
    public $guarded = array();
}
