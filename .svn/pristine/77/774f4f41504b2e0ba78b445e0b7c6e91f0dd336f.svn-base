<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/9
 * Time: 下午12:39
 */

namespace App\LModel;


use Illuminate\Database\Eloquent\Model;

class LIsbnTongji extends Model
{
    protected $table = "tongji_isbn";
    protected $connection = "mysql_local";

    public function getList($sortF = "collect_num",$sort="desc"){
        return LIsbnTongji::where($sortF,'>',0)->orderBy($sortF,$sort)->paginate(20);
    }
}