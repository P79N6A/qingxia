<?php

namespace App\AnswerModel;

use Illuminate\Database\Eloquent\Model;

class AWorkbook1010Cip extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_local';
    protected $table = 'a_workbook_1010_cip';
    protected $guarded = array();
    //public function

    public function has_user()
    {
        return $this->hasOne('App\User','id','update_uid');
    }

    public function has_offical()
    {
        return $this->hasOne('App\AWorkbook1010','isbn','isbn');
    }

    public function has_related()
    {
        return $this->hasOne('App\LocalModel\IsbnTemp','isbn','isbn');
    }
}
