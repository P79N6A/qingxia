<?php

namespace App\OneModel;

use Illuminate\Database\Eloquent\Model;

class AWorkbookAnswer extends Model
{
    protected $connection = 'mysql_main_rds';
    protected $table = 'a_workbook_answer_1010';
    public $guarded = array();
    public $timestamps = false;
}
