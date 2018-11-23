<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMAnswerUserBlacklist extends Model
{
    protected $table = 'pre_m_workbook_answer_user_blacklist';
    protected $guarded = array();
    protected $connection = 'mysql_local';
    public $timestamps = false;
}
