<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/9
 * Time: 下午12:49
 */

namespace App\Http\Controllers\Chart;


use App\Http\Controllers\Controller;
use App\LModel\LIsbnTongji;

class TongJiIsbnController extends Controller
{
    public function index(){
        /*ignore_user_abort();
set_time_limit(0);
ini_set('memory_limit',-1);
$a = Book::where([['bar_code','<>',null]])->select('bar_code')->groupBy('bar_code')->orderBy('bar_code','desc')->chunk(1000,function ($books){
    foreach ($books as $book){
        if(strlen($book->bar_code)==13 && starts_with($book->bar_code, '9787')){
            $data['collect_num'] = Book::where('bar_code',$book->bar_code)->sum('collect_num');
            $data['concern_num'] = Book::where('bar_code',$book->bar_code)->sum('concern_num');
            $data['isbn'] = $book->bar_code;
            \DB::connection('mysql_local')->table('tongji_isbn')->insert($data);
        }
    }
});*/

        $sort = isset($_GET["sort"])?$_GET["sort"]:"desc";
        //exit($sort);
        $field = isset($_GET["field"])?$_GET["field"]:"1";
        if( $sort == "desc"){
            $usort = "asc";
            $class = "fa fa-sort-amount-desc";
        }else{
            $usort = "desc";
            $class = "fa fa-sort-amount-asc";
        }

        if( $field == "2"){
            $sortF = "concern_num";
            $ufield = "1";
        }else{
            $sortF = "collect_num";
            $ufield = "2";
        }

        //$href = route("isbn_tongji",["field"=>$ufield,"sort"=>$usort]);

        $model = new LIsbnTongji();
        $datas = $model->getList($sortF,$sort);
        return view('chart.tongji_isbn.index',['datas'=>$datas,'class'=>$class,'sort'=>$usort]);
    }
}