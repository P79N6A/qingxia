<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewGoodsFindBook extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_book_goods_findbybook';
    public $timestamps = false;
    public $guarded  =array();



}
