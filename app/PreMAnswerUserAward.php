<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMAnswerUserAward extends Model
{
    protected $table = 'pre_m_workbook_answer_user_award';
    protected $guarded = array();
    protected $connection = 'mysql_local';
    public $timestamps = false;
}
