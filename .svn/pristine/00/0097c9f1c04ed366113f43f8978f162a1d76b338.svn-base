<?php

namespace App\AnswerModel;

use Illuminate\Database\Eloquent\Model;

class AWorkbookAnswerCip extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql_local';
    protected $table = 'a_workbook_answer_cip';
    protected $guarded = array();

    public function get_like_answer($hid)
    {
        if($hid>0){
            $perfect = $this->where('tid',$hid)->select()->orderBy('addtime','asc')->get();
            $likes = $this->where('tid','like','%|'.$hid.'%')->select()->orderBy('addtime','asc')->get();
        }else{
            $perfect = [];
            $likes = [];
        }

        return collect($perfect)->merge($likes);
    }

    public function get_normal_answer($hid)
    {
        return $this->where('tid',$hid)->select()->orderBy('text','asc')->get();
    }
}
