<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/4/8
 * Time: 下午2:02
 */

namespace App\Http\Controllers\Chart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class SearchApiController extends Controller
{
    public function index(){
       /* $lists = \DB::connection("mysql_local")
            ->table("a_workbook_1010_cip")
            ->where(\DB::raw("DATE_FORMAT(addtime,'%Y-%m-%d')"),'2018-04-04')
            ->whereNotNull('isbn')
            ->limit(2)
            ->get();*/
       /*
       $lists = \DB::connection("mysql_local")

           ->table("a_tongji_search_isbn_temp1")
           ->select("isbn")
           ->where([['sort','=','-1'],['resultcount','=','0'],['searchnum','>=','100']])
       ->orderBy('searchnum','desc')->get();*/
        set_time_limit(0);
        ini_set("memory_limit",-1);
       /*$lists = \DB::connection("mysql_local")
           ->table("a_workbook_1010_cip")
            ->select(\DB::raw("(select sort.name from sort where a_workbook_1010_cip.sort = sort.id) as sortname"),'sort','addtime')
            ->whereRaw("TO_DAYS( NOW( ) ) - TO_DAYS(a_workbook_1010_cip.addtime) <= 1 and a_workbook_1010_cip.sort >-1")
            ->orderBy("a_workbook_1010_cip.addtime",'desc')
            ->groupBy('sort')
       ->get();*/


      /*  $lists = \DB::connection("mysql_local")->select("select  sort,
  (select sort.name from sort where a_workbook_1010_cip.sort = sort.id) as sortname,addtime from `a_workbook_1010_cip`
where TO_DAYS( NOW( ) ) - TO_DAYS( addtime) <= 1 and sort >-1 group by sort order by `addtime` desc ");*/
        $lists = \hdAddDB::connection("mysql_local")
            ->select("select `name` from sort 
                    where id in (select  sort from `a_workbook_1010_cip where TO_DAYS( NOW( ) ) - TO_DAYS( addtime) <= 1 and sort >-1 group by sort)");

        echo \GuzzleHttp\json_encode($lists);

        exit;
    }

    /*
    public function hdAdd(Request $request){
        $isbn = $request["isbn"];
        $data = $request["content"];
        $result = \DB::connection("mysql_local")->table('a_hd_by_isbn')->insert(['isbn'=>$isbn,'data'=>$data]);
        exit(json_encode(['status'=>$result]));
    }*/

    public function hdAdd(Request $request){
        $data["owenrId"] = $request["owenrId"];
        $data["userId"] = $request["userId"];
        $data["answerType"] = $request["answerType"];
        $data["bookName"] = $request["bookName"];
        $data["sortId"] = $request["sortId"];
        $data["volumes"] = $request["volumes"];
        $data["createTime"] = $request["createTime"];

        $data["subjectId"] = $request["subjectId"];
        $data["objectId"] = $request["objectId"];
        $data["bookVersionId"] = $request["bookVersionId"];
       // dd($data);
        $result = \DB::connection("mysql_local")->table("a_workbook_1010_cip_hd_list")
            ->insert($data);
        exit(json_encode(['status'=>$result]));
    }

}