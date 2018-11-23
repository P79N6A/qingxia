<?php

namespace App\LocalModel\NewBuy;

use Illuminate\Database\Eloquent\Model;

class NewOnlyDelete extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_workbook_only_delete';
    public $timestamps = false;
    public $guarded = array();
}
