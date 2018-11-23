<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Baidu extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'baidu';
    protected $guarded = array();
    public $timestamps = false;

    public function has_book()
    {
      return $this->hasOne('App\AWorkbookMain','id','book_id');
    }

  public function has_sort(){
    return $this->hasOne('App\Sort','id','sort_id');
  }
}
