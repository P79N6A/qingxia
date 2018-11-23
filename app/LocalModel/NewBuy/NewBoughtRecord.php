<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewBoughtRecord extends Model
{
    public $connection = 'mysql_local';
    public $table = 'a_book_bought_record';
    public $guarded = array();

    public function hasOnlyDetail()
    {
        return $this->hasOne('App\LocalModel\NewBuy\NewOnly','id','only_id');
    }

    public function hasGoodsDetail()
    {
        return $this->hasOne('App\LocalModel\NewBuy\NewGoods','detail_url','goods_id')->where('detail_url','>',0);
    }

    public function hasFound()
    {
        return $this->hasMany('App\LocalModel\NewBuy\NewGoodsTrue','jiajiao_id','only_id');
    }
    
    public function hasSort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }

    public function hasNewBook()
    {
        return $this->hasOne('App\AWorkbookNew','from_only_id','only_id');
    }

    public function hasReturn()
    {
        return $this->hasMany('App\LocalModel\NewBuy\NewBoughtReturn','only_id','only_id');
    }
}
