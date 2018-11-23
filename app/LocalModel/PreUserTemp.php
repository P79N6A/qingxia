<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class PreUserTemp extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'pre_m_workbook_user_temp';

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort_id');
    }
}
