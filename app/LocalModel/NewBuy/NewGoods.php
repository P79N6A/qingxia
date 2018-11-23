<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewGoods extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_book_goods';
    public $timestamps = false;
    public $guarded  =array();
}
