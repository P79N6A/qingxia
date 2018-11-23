<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class TaskUid extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'task_uid';
    protected $guarded = array();
    public $timestamps = false;
}
