<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMWorkbookAnswerUser extends Model
{
    protected $connection = 'mysql_local';
    protected $table ='pre_m_workbook_answer_user';
    public $timestamps = false;
    protected $guarded = array();

    public function hasUserInfo()
    {
        return $this->hasOne('App\PreCommonMemberProfile','uid','up_uid');
    }

    public function has_user()
    {
        return $this->hasOne('App\User','id','op_uid');
    }

    public function has_answer_pic(){
        return $this->hasMany('App\WorkbookAnswerRds','bookid','book_id');
    }
}
