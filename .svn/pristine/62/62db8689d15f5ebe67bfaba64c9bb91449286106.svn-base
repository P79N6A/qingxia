<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ATongjiHotbook29 extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_tongji_hotbook29';
    public $timestamps = false;

    public function user_book(){
        return $this->hasMany('App\PreMWorkbookUser','isbn','isbn')->where(['status'=>0]);
    }

    public function has_ssort(){
        return $this->hasMany('App\ASubsort','sort_id','sort');
    }


}
