<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookVersion extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'book_version';

    public function get_all_sort(){
        return BookVersion::all();
    }
}
