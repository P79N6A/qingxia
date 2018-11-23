<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class IsbnAll extends Model
{
    public $connection = 'mysql_local';
    #public $connection = 'mysql_main_rds';
    public $table = 'isbn_all';
    public $timestamps = false;

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','preg_sort_id');
    }

}
