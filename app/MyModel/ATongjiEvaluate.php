<?php

namespace App\MyModel;

use Illuminate\Database\Eloquent\Model;

class ATongjiEvaluate extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_evaluate';
    public $timestamps = false;
    protected $guarded = array();
}
