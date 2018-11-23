<?php

namespace App\MyModel;

use Illuminate\Database\Eloquent\Model;

class ATongjiCorrect extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_correct';
    public $timestamps = false;
    protected $guarded = array();
}
