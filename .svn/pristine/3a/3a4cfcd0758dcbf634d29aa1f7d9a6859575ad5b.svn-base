<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsort extends Model
{
  protected $connection = 'mysql_local';
    //protected $connection = 'mysql_main';
    protected $table = 'subsort1';
    protected $guarded = array();
    public $timestamps = false;

//    public function sort()
//    {
//      return $this->belongsTo('App\Sort','pid','id');
//    }

    public function has_books()
    {
      return $this->hasMany('App\AWorkbookMain','ssort_id','id');
    }
}
