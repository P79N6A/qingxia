<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/10
 * Time: 上午10:55
 */

namespace App\LModel;


use Illuminate\Database\Eloquent\Model;

class LSearchTongjiModel extends Model
{
    protected $table        = "a_tongji_true_search_isbn";
    protected $connection   = "mysql_local";

    public function getList($start,$end){
        return LSearchTongjiModel::where([["resultcount","=","0"],["addtime",">=",$start],["addtime","<=",$end+86400]])
            ->select(
                \DB::raw('sum(isbnSearchCount) as isbnSearchCount') ,
                \DB::raw(' max(addtime) as addtime') ,
                'isbn')
            ->groupBy('isbn')
            ->orderBy('isbnSearchCount','desc')
            ->paginate(20);
    }
}