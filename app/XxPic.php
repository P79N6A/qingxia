<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XxPic extends Model
{
    protected $connection = 'mysql_local';
    public $timestamps = false;
    public $guarded = array();
}
