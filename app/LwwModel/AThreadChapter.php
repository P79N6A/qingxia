<?php

namespace App\LwwModel;

use Illuminate\Database\Eloquent\Model;

class AThreadChapter extends Model
{
    protected $connection = 'mysql_05wang';
    protected $table = 'a_thread_chapter';
    public $timestamps = false;

    public function hasPost()
    {
        return $this->hasMany('App\OneModel\PreForumPost','tid','id');
    }
    
}
