<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ABookKnow extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_book_know';

    public function bookinfo(){
        return $this->belongsTo('App\ABook1010','booksort','booksort');
    }
}
