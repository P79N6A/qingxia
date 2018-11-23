<?php

namespace App\LocalModel;

use DB;
use Illuminate\Database\Eloquent\Model;

class IsbnTemp extends Model
{
    public $connection = 'mysql_local';
    #public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_search_isbn_temp2';
    protected $guarded = array();
    public $timestamps = false;

    public function has_offical_book()
    {
        return $this->hasMany('App\LocalModel\WorkbookTemp','isbn','isbn');
    }

    public function has_hd_book()
    {
        return $this->hasMany('App\LocalModel\HdbookTemp','isbn','isbn');
        //return $this->hasMany($related);
    }

    public function has_user_book()
    {
        return $this->hasMany('App\LocalModel\PreUserTemp','isbn','isbn');
    }

    public function has_taobao_book()
    {
        return $this->hasMany('App\LocalModel\TaobaoTemp','isbn','isbn');
    }

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }

    public function has_sort_detail()
    {
        return $this->hasMany('App\LocalModel\IsbnTemp','sort','sort');
    }

    public function has_user()
    {
        return $this->hasOne('App\User','id','uid');
    }

    //获取sort聚合排序数量
    public function scopeSorted()
    {
        //return $this;
    }
    public function hasOfficalBook()
    {
        return $this->hasMany('App\Aworkbook1010','isbn','isbn');
    }

    public function hasWorkBookUserFirst()
    {
        return $this->hasOne('App\PreMWorkbookUser','isbn','isbn');
    }
    public function hasSearchTemp()
    {
        return $this->hasOne('App\LocalModel\IsbnTemp','isbn','isbn');
    }
    public function hasIsbnDetail()
    {
        return $this->hasOne('App\LocalModel\IsbnAll','isbn','isbn');
    }

    public function hasSearchEveryday()
    {
        return $this->hasMany('App\LocalModel\IsbnTempEveryday','isbn','isbn')
            ->selectRaw('SUM(searchnum) as searchnum')
            ->groupBy('isbn');
    }
    public function hasWorkBookUser()
    {
        return $this->hasMany('App\PreMWorkbookUser','isbn','isbn');
    }
}
