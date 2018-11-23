<?php

namespace App\OneModel;

use Illuminate\Database\Eloquent\Model;

class ASort extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_sort';
    public $guarded = array();
    public $timestamps = false;
}
