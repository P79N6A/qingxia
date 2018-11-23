<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LwwBook extends Model
{
    protected $table = 'a_book';
    public $timestamps = false;

    public function chapters()
    {
        return $this->hasMany('App\LwwBookChapter','bookid','id')->orderBy('id');
    }

}
