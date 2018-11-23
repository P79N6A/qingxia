<?php

namespace App\OneModel;

use Illuminate\Database\Eloquent\Model;

class AThreadBook extends Model
{
    public $connection = 'mysql_zjb_lww';
    public $table = 'a_thread_book';
    public $guarded = array();
    public $timestamps = false;
}
