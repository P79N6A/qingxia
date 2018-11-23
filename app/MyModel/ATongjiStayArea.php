<?php

namespace App\MyModel;

use Illuminate\Database\Eloquent\Model;

class ATongjiStayArea extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_stay_area';
    public $timestamps = false;
    protected $guarded = array();
}
