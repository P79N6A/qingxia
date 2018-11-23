<?php

namespace App\LocalModel;

use Illuminate\Database\Eloquent\Model;

class LocalImage extends Model
{
    protected $guarded = array();
    public $connection = 'mysql_local';
    public $table = 'local_img_upload_logs';

    public function hasChildren()
    {
        return $this->hasMany('App\LocalModel\LocalImage','parent_id','id')->orderBy('status','asc')->orderBy('preg_grade','asc');
    }

    public function hasOnlyBook()
    {
        return $this->hasMany('App\OnlineModel\AOnlyBook','onlyid','onlyid');
    }
}
