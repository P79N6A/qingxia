<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ABook1010 extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'a_book_1010';
    protected $fillable = ['name', 'version_id', 'grade_id',
        'subject_id', 'volumes_id','book_confirm','isbn','cover_photo_thumbnail'
    ];
    public $timestamps = false;

    public function chapters(){
        return $this->hasMany('App\ABookKnow','booksort','booksort');
    }
}
