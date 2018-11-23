<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZydsAnswer extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'zyds_answer';
    public $timestamps = false;

}
