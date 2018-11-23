<?php

namespace App\OnlineModel;

use Illuminate\Database\Eloquent\Model;

class ATongjiHotBook extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_hotbook';
    public $timestamps = false;
    protected $guarded = array();
}
