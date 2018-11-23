<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoneSelfAnswer extends Model
{
    protected $connection = 'mysql_concern';
    protected $table = 'zone_self_answer';
    protected $fillable = ['has_check','o_uid'];
    public $timestamps = false;

    public function answers()
    {
        return $this->hasMany('App\ZoneAnswerPath','answer_id','id')->select('answer_img');
    }
}
