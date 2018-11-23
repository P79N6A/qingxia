<?php

namespace App\OnlineModel;

use Illuminate\Database\Eloquent\Model;

class ParttimeLog extends Model
{
    public $connection = 'mysql_local';
    protected $table = 'part_time_logs';
    protected $guarded = array();
}
