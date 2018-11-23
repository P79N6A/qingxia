<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/8
 * Time: 下午3:21
 */

namespace App\Http\Controllers\Chart;


use App\Http\Controllers\Controller;
use App\LModel\LBuyBookWithIsbnModel;
use App\LModel\LSearchTongjiModel;
use App\Workbook;
use Illuminate\Support\Facades\Input;

class SearchChartController extends Controller
{
    public function index($start="",$end=''){
        $lastid = \DB::connection("mysql_local")
                        ->table("a_tongji_search_isbn_task")
                        ->orderBy("id","desc")
                        ->value("lastid");
        if($lastid){
            $id =\DB::connection("mysql_local")->table("a_tongji_search_isbn")->orderBy("id","desc")->value("id");
            if($id > $lastid){//如果有新数据执行以下操作
                //\DB::connection("mysql_local")->delete("delete from a_tongji_true_search_isbn");
                //WHERE a_tongji_search_isbn.id > $lastid  插入的时候加了条件会有重复，所有先删除，在拷贝
                \DB::connection("mysql_local")
                    ->insert("INSERT INTO a_tongji_true_search_isbn
                (SELECT any_value(id),isbn,count(isbn)AS isbnSearchCount,max(addtime),sum(resultcount) as resultcount  
                  FROM a_tongji_search_isbn 
                  WHERE a_tongji_search_isbn.id > $lastid
                  GROUP BY isbn);");
                \DB::connection("mysql_local")->table("a_tongji_search_isbn_task")->insert(['lastid'=>$id]);
            }
        }else{
            \DB::connection("mysql_local")
                ->insert("INSERT INTO a_tongji_true_search_isbn
                (SELECT any_value(id),isbn,count(isbn)AS isbnSearchCount,max(addtime),sum(resultcount) as resultcount  FROM a_tongji_search_isbn GROUP BY isbn);");
            $id =\DB::connection("mysql_local")->table("a_tongji_search_isbn")->orderBy("id","desc")->value("id");
            \DB::connection("mysql_local")->table("a_tongji_search_isbn_task")->insert(['lastid'=>$id]);
        }
        $tongjiModel = new LSearchTongjiModel();
        $start = (checkDateFormat($start))?strtotime($start):strtotime(date("Y-m-d",strtotime("-29 day")));
        $end = (checkDateFormat($end))?strtotime($end):time();
        $datas =$tongjiModel->getList($start,$end);

        return view('chart.tongji_search.index',['datas'=>$datas,'start'=>date("Y-m-d",$start),'end'=>date("Y-m-d",$end)]);
    }

    public function bookInfoByIsbn($isbn){
        $worbookObject = new Workbook();
        $datas = $worbookObject->getListByIsbn($isbn);
        return view("",["datas"=>$datas]);
    }

    public function edit($isbn){
        if(\Request::isMethod("post")){
            $datas = \Request::all();
            $status = 1;
            $msg = "";
            if(!isset($datas['isbn']) || empty($datas['isbn'])){
                $status = 0;
                $msg = "isbn不能为空\n";
            }
            if(!isset($datas["price"]) || empty($datas["price"])){
                $status = 0;
                $msg = "价格不能为空\n";
            }
            if(!isset($datas["buydate"]) || empty($datas["buydate"])){
                $status = 0;
                $msg .="购买时间不能为空\n";
            }elseif(!checkDateFormat($datas["buydate"])){
                $status == 0;
                $msg .= "购买时间格式错误";
            }
            unset($datas["_token"]);
            if(!$datas["purchaser"]){
                $datas["purchaser"] = "";
            }
            $datas["buydate"] = strtotime($datas["buydate"]);
            $insert = LBuyBookWithIsbnModel::create($datas);
            if(!$insert){
                $status = 0;
                $msg = "数据提交失败";
            }
            return \Response::json([
                'status'=>$status,
                'msg' => $msg
            ]);

        }
        return view("chart.tongji_search.edit",["isbn"=>$isbn]);
    }

}