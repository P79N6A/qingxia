<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class WorkbookTemp extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_workbook_1010_temp';

    public function has_sort()
    {
        return $this->hasOne('App\Sort','id','sort');
    }
}
