<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZydsBook extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'zyds_book';
    public $timestamps = false;

    public function has_answer(){
        return $this->hasMany('App\ZydsAnswer','homeworkId','newid');
    }

}
