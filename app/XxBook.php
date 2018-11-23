<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XxBook extends Model
{
    protected $connection = 'mysql_local';
    public $guarded = array();
}
