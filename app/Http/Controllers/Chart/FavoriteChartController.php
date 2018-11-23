<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/8
 * Time: 下午3:26
 */

namespace App\Http\Controllers\Chart;


use App\Http\Controllers\Controller;
use App\PreHomeMyfavorite;

/**
 * 收藏统计
 * Class FavoriteChartController
 * @package App\Http\Controllers\Chart
 */
class FavoriteChartController extends Controller
{
    public function index(){
        $firstday = strtotime("-30 day");
        $lastday = strtotime('-1 day');
        //$data["list"] = \DB::connection("mysql_local")->select("SELECT * from(SELECT count(bookid) as scount,(SELECT bookname FROM a_workbook_1010_main WHERE pre_home_myfavorite.bookid=a_workbook_1010_main.id) as bookName FROM pre_home_myfavorite WHERE addtime >=$firstday and addtime<=$lastday GROUP BY bookid ORDER BY scount DESC ) as nt WHERE scount >= 20;");
        $data["list"] = \DB::connection("mysql_local")->select("SELECT * from(SELECT count(bookid) as scount,(SELECT bookname FROM a_workbook_1010_main WHERE pre_home_myfavorite.bookid=a_workbook_1010_main.id) as bookName FROM pre_home_myfavorite GROUP BY bookid ORDER BY scount DESC ) as nt  WHERE scount >= 20 ;");
        return view("chart.favorite_chart.index",compact("data"));
    }

    public function indexAjax(){
        $start = $_POST["start"];
        $end = $_POST["end"];
        if($start){
            $start = strtotime($start);
        }else{
            exit("<h3><i class=\"fa fa-warning text-yellow\"></i> 参数错误!</h3>");
        }
        if($end){
            $end = strtotime($end);
        }else{
            exit("<h3><i class=\"fa fa-warning text-yellow\"></i> 参数错误!</h3>");
        }
        $data["list"] = \DB::connection("mysql_local")->select("SELECT * from(SELECT count(bookid) as scount,(SELECT bookname FROM a_workbook_1010_main WHERE pre_home_myfavorite.bookid=a_workbook_1010_main.id) as bookName FROM pre_home_myfavorite WHERE addtime >=$start and addtime<=$end GROUP BY bookid ORDER BY scount DESC ) as nt WHERE scount >= 20;");
        if(count($data['list'])>0){
            return view("chart.favorite_chart.ajax_index",compact("data"));
        }else{

            exit("<h3><i class=\"fa fa-warning text-yellow\"></i> 没有数据</h3>");
            //return view("chart.favorite_chart.error",compact('msg'));
        }

    }


}