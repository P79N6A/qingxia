<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHdBook extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'hd_book';


    public function has_answers()
    {
        return $this->hasMany('App\TaskHdBookAnswer','objectId','objectId');
    }
}
