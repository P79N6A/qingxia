<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HdBook extends Model
{
    protected $connection = 'mysql_concern';
    protected $table = 'book';
}
