<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewOnly extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_workbook_only';
    public $guarded = array();

    public function hasVersion()
    {
        return $this->hasOne('App\BookVersionType','id','version_id');
    }

    public function hasBooks()
    {
        return $this->hasMany('App\LocalModel\NewBuy\New1010','newname','newname');
    }
    
    public function hasFound()
    {
        return $this->hasMany('App\LocalModel\NewBuy\NewGoodsTrue','jiajiao_id','id');
    }
}
