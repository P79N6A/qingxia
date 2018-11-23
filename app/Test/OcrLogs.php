<?php

namespace App\Test;

use Illuminate\Database\Eloquent\Model;

class OcrLogs extends Model
{
    protected $connection = 'mysql_local';
    protected $table = 'img_ocr_logs';
    protected $guarded = array();
}
