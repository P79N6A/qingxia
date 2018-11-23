<?php

namespace App\Temp;

use Illuminate\Database\Eloquent\Model;

class TestSinglePage extends Model
{
    public $connection = 'mysql_local';
    public $table = 'test_single_file_pages';
}
