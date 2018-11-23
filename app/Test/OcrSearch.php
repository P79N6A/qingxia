<?php

namespace App\Test;

use Illuminate\Database\Eloquent\Model;

class OcrSearch extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'img_ocr_search_question';
    protected $guarded = array();
    public $timestamps = false;
}
