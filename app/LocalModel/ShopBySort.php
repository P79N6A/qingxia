<?php

namespace App\LocalModel;

use DB;
use Illuminate\Database\Eloquent\Model;

class ShopBySort extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_book_goods_shopbysort';
    protected $guarded = array();
    public $timestamps = false;



}
