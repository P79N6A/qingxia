<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ASortUid extends Model
{
    protected $table = 'a_sort_uid';
    protected $connection = 'mysql_local';
    public $timestamps = false;
}
