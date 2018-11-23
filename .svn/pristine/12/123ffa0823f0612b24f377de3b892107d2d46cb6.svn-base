<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWorkbookNew extends Model
{
  protected $connection = 'mysql_local';
  public $timestamps = false;
  protected $table = 'a_workbook_new';
    protected $guarded = array();

    public function has_main_book()
    {
       return $this->hasOne('App\AWorkbook1010','id','id');
    }

    public function has_version()
    {
      return $this->hasOne('App\BookversionType','id','version_id');
    }

    public function has_collect()
    {
        return $this->hasOne('App\AWorkbook1010','id','id');
    }

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }

    public function has_sub_sort()
    {
      return $this->hasOne('App\Subsort','id','ssort_id');
    }

    public function has_user()
    {
        return $this->hasOne('App\User','id','update_uid');
    }


}
