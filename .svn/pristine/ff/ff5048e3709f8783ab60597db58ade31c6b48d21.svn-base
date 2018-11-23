<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaiduNewDaan extends Model
{
  protected $connection = 'mysql_local';
  protected $table = 'baidu_new_daan';
  public $timestamps = false;

  public function has_sort(){
    return $this->hasOne('App\Sort','id','sort_id');
  }

    public function has_main_book(){
        return $this->hasOne('App\AWorkbook1010','id','book_id');
    }

    public function has_book(){
        return $this->hasOne('App\AWorkbook1010','id','book_id');
    }
    public function has_user_book(){
        return $this->hasOne('App\PreMWorkbookUser','id','book_id');
    }
}
