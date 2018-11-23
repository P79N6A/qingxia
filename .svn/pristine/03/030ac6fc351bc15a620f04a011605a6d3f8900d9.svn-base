<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewGoodsFind extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_book_goods_findbysort';
    public $timestamps = false;
    public $guarded  =array();


    public function hasOnlyBooks()
    {
        return $this->hasMany('App\LocalModel\NewBuy\NewOnly','sort','sort_id');
    }

    public function hasFindBooks(){
        return $this->hasMany('App\localModel\NewBuy\NewGoodsFindBook','sort_id','sort_id');
    }
}
