<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMQuestionFeedback extends Model
{
  protected $connection = 'mysql_local';
  //protected $connection = 'mysql_main';
    protected $table = 'pre_m_question_feedback';
}
