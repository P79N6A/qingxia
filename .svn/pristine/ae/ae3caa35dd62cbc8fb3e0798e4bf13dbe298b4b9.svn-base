<?php

namespace App\OneModel;

use Illuminate\Database\Eloquent\Model;

class AOnlyBook extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_onlybook';
    public $guarded = array();
    public $timestamps = false;

    public function hasWorkbooks()
    {
        return $this->hasMany('App\OneModel\AWorkbook','onlyid','onlyid');
    }
}
