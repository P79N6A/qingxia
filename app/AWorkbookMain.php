<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AWorkbookMain extends Model
{
  protected $connection = 'mysql_local';
  #protected $connection = 'mysql_main_rds';
  protected $table = 'a_workbook_1010';
  public $timestamps = false;
  protected $guarded = array();

  public function has_sort(){
    return $this->hasOne('App\Sort','id','sort');
  }

  public function has_editor()
  {
    return $this->hasOne('App\User','id','update_uid');
  }
  
  public function has_answers()
  {
    return $this->hasMany('App\WorkbookAnswer','book','bookcode');
  }
  
  public function has_hd_book()
  {
    return $this->hasOne('App\HdBook','id','hdid');
  }

  public function has_redirects()
  {
    return $this->hasMany('App\AWorkbookMain','redirect_id','id');
  }

}
