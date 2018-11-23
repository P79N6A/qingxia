<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewSort extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'new_sort_order';
    public $primaryKey = 'sort_id';
    public $timestamps = false;
    public $guarded = array();

    public function hasOnlyBooks()
    {
        return $this->hasMany('App\LocalModel\NewBuy\NewOnly','sort','sort_id');
    }

    public function hasFindBook()
    {
        return $this->hasOne('App\localModel\NewBuy\NewGoodsFind','sort_id','sort_id');
    }

    public function hasFindBook_new(){
        return $this->hasMany('App\localModel\NewBuy\NewGoodsFindBook','sort_id','sort_id');
    }

}
