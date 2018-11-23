<?php

namespace App\OnlineModel;

use Illuminate\Database\Eloquent\Model;

class AOnlyBook extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_onlybook';
    public $timestamps = false;
    protected $guarded = array();

    public function hasWorkbooks()
    {
        return $this->hasMany('App\OnLineModel\AWorkbook1010','onlyid','onlyid');
    }
}
