<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class HdbookTemp extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'hd_book_temp';
    
    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sortId');
    }
}
