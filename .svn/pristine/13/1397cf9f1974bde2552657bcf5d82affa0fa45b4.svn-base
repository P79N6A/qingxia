<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewGoodsTrue extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_book_goods_true';
    public $timestamps = false;
    public $guarded  =array();


    public function hasOnly()
    {
        return $this->hasOne('App\LocalModel\NewBuy\NewOnly','id','jiajiao_id');
    }



}
