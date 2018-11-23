<?php

namespace App\OnlineModel;

use Illuminate\Database\Eloquent\Model;

class ASubSort extends Model
{
    public $connection = 'mysql_main_rds';
    protected $table = 'a_subsort';
    public $timestamps = false;
    protected $guarded = array();
}
