<?php

namespace App\MyModel;

use Illuminate\Database\Eloquent\Model;

class ATongjiOneday extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_oneday';
    public $timestamps = false;
    protected $guarded = array();

}
