<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMWorkbookUserGroup extends Model
{
    protected $table = 'pre_m_workbook_user_group';
    protected $connection = 'mysql_local';
    protected $guarded = array();
    public $timestamps = false;

    public function hasWorkbookUser()
    {
        return $this->hasMany('App\PreMWorkbookUser','isbn','isbn');
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

    public function hasOfficalBook()
    {
        return $this->hasMany('App\Aworkbook1010','isbn','isbn');
    }
}
