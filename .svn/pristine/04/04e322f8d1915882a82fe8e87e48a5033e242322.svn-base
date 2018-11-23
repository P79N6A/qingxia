<?php

namespace App\LocalModel;

use DB;
use Illuminate\Database\Eloquent\Model;

class IsbnTempEveryday extends Model
{
    public $connection = 'mysql_local';
    #public $connection = 'mysql_main_rds';
    protected $table = 'a_tongji_search_isbn_temp2_everyday';
    protected $guarded = array();
    public $timestamps = false;

    public function hasWorkBookUserFirst()
    {
        return $this->hasOne('App\PreMWorkbookUser','isbn','isbn');
    }

    public function hasIsbnDetail()
    {
        return $this->hasOne('App\LocalModel\IsbnAll','isbn','isbn');
    }

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }

    public function hasOfficalBook()
    {
        return $this->hasMany('App\Aworkbook1010','isbn','isbn');
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

    public function hasIsbnTemp()
    {
        return $this->hasOne('App\LocalModel\IsbnTemp','isbn','isbn');
    }

    public function hasCover1010()
    {
        return $this->hasMany('App\Aworkbook1010','isbn','isbn')->orderBy('id','desc');

    }

}
