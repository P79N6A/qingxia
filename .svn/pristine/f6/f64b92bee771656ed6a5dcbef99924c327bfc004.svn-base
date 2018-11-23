<?php

namespace App\Http\Controllers\Mytest\Api;

use App\Sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SortController extends Controller
{
    public function get_sort()
    {
        $sort=request()->word;
        //获取系列目录
        $sort=DB::connection('mysql_local')->table('sort')->where('name','like',"%".$sort."%")->select('id','name as text')->get();
        $sort=json_encode($sort);
//        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
//        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
//        $sort = str_replace($escapers, $replacements, $sort);
        return $sort;
    }
}
