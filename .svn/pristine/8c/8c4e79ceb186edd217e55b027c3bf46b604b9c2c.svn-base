<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSortCollect extends Model
{
    protected $connection = 'mysql_local';
    protected $guarded = array();

    public function has_workbook_1010()
    {
        return $this->hasMany('App\Aworkbook1010','sort','sort');
    }

    public function has_workbook_new()
    {
        return $this->hasMany('App\AworkbookNew','sort','sort');
    }

    public function has_hd_book()
    {

    }

}
