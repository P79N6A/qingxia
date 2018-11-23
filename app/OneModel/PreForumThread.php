<?php

namespace App\OneModel;

use Illuminate\Database\Eloquent\Model;

class PreForumThread extends Model
{
    public $connection = 'mysql_zjb_lww';
    public $table = 'pre_forum_thread';
    public $guarded = array();
    public $timestamps = false;
}
