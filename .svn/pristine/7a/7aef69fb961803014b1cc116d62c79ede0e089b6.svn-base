<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreMWorkbookContent extends Model
{
    protected $table = 'pre_m_workbook_content';
    protected $guarded = array();
    protected $connection = 'mysql_zjb';
    public $timestamps = false;

    public function hasUserInfo()
    {
        return $this->hasOne('App\PreCommonMemberProfile','uid','up_uid');
    }
    public function has_user()
    {
        return $this->hasOne('App\User','id','op_uid');
    }
}
