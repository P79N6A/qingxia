<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LwwBookChapter extends Model
{
    protected $table = 'a_book_chapter';
    public $timestamps = false;

    public function voice()
    {
      return $this->hasOne('App\LwwBookMp3','chapterid','id');
    }
}
