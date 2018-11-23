<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMHomework extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'pre_m_homework';
    public $timestamps = false;
    public function has_comments()
    {
      return $this->hasMany('App\PreMHomeworkComment','hid','id');
    }

    public function has_user()
    {
      return $this->hasOne('App\PreCommonMember','uid','uid');
    }
}
