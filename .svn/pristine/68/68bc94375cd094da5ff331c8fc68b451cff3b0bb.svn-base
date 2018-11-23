<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LwwBookQuestion extends Model
{
    public $timestamps = false;
    protected $table = 'a_book_question';
    protected $guarded = array();
    
    public function timu_pics()
    {
      return $this->hasMany('App\LwwBookPageTimupos','timuid','timuid');
    }
}
