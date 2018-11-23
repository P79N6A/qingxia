<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XxHash extends Model
{
    protected $connection = 'mysql_local';
    public $timestamps = false;
    public $guarded = array();
}
