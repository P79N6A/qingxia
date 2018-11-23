<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMQuestionWorkplace extends Model
{
    //protected $connection = 'mysql_main';
  protected $connection = 'mysql_local';
    protected $table = 'pre_m_question_workplace';
    protected $fillable = ['qid','uid','status'];

    public function teahcer()
    {
      return $this->hasOne('App\User','id','uid');
    }
}
