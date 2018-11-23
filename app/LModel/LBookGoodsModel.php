<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/16
 * Time: 下午3:05
 */

namespace App\LModel;


use App\Utils\SphinxClient;
use Illuminate\Database\Eloquent\Model;

class LBookGoodsModel extends Model
{
    protected $table = "a_book_goods";
    protected $connection = "mysql_local";
    public $timestamps = false;

    public $guarded = array();
    /**
     * 指定主键。
     */
    public $primaryKey = 'id';

    private $nums = [];
    private $datas = [
        '1' =>['一年级','1年级','一二三四五六年级','123456年级'],
        '2' =>['二年级','2年级','二三四五六年级','23456年级'],
        '3' => ['三年级','3年级','三四五六年级','3456年级'],
        '4' => ['四年级','4年级','四五六年级','456年级'],
        '5' => ['五年级','5年级','五六年级','56年级'],
        '6' => ['六年级','6年级'],
        '7' => ['七年级','7年级','初一','初1','初一二三','初123','七八九年级','789年级'],
        '8' => ['八年级','8年级','初二','初2','初二三','初23','八九年级','89年级'],
        '9' => ['九年级','9年级','初三','初3']
    ];

    private $subject = [
        '1' => '语文',
        '2' => '数学',
        '3' => '英语|外语',
        '4' => '物理',
        '5' => '化学',
        '6' => '地理',
        '7' => '历史',
        '8' => '政治',
        '9' => '生物',
        '10'=>'科学'
    ];

    private $grade = [
        '3' => "((3[4-6]{0,3})|(三.{0,3}))",
        '4' => "((4[5-6]{0,2})|(四.{0,2}))",
        '5' => "((5[6]{0,1})|(五[六]{0,1}))",
        '6' => "(6|六)年级",
        '7' => "(((7[89]{0,2})|(七[八九]{0,2})))|(((初1[23]{0,2})|(初一[二三]{0,2})))",
        '8' => "(((8[9]{0,1})|(八[九]{0,1})))|(((初2[3]{0,1})|(初二[三]{0,1})))",
        '9' => '((9|九)年级)|(初3|初三)'
    ];

     private $sphinxGrade = [
         '1' => '(1|一)年级',
         '2' => '(2|二)年级',
         '3' => '(3|三)年级',
         '4' => '(4|四)年级',
         '5' => '(5|五)年级',
         '6' => '(6|六)年级',
         '7' => '((7|七)年级)|(初(1|一))',
         '8' => '((8|八)年级)|(初(2|二))',
         '9' => '((9|九)年级)|(初(3|三))',
         ];




    public function getList($key,$contain='',$remove=''){
        if($key){
            $where = [['hide','=',0]];
            $where[] = ['sortname' ,'=' ,$key];
            if(!empty($contain)){
                $contain = explode("|",$contain);//包含
                foreach ($contain as $item){
                    if($this->checkContain($item)){
                        $num = mb_substr($item,0,1,"UTF8");
                        $this->nums[] = $num;
                        $g = mb_substr($item,1,2,"UTF8");
                        $where[] = ["raw_title",'like',"%$num%$g%"];
                    }else{
                        $where[] = ["raw_title",'like',"%$item%"];
                    }
                }
            }
            if(!empty($remove)){
                $remove = explode("|",$remove);//排除
                foreach ($remove as $item){
                    $where[] = ['raw_title','not like',"%$item%"];
                }
            }
        }else{
            return 0;
        }
        $datas = LBookGoodsModel::where($where)
            ->orderby('view_price','asc')
            ->select("raw_title","title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url')
            ->get();
        $returnDatas = [];
        foreach ($datas as $data){
            $grade = $this->checkTitle($data["raw_title"]);
            if(empty($grade)){
                $returnDatas[999][] = $data;
            }else{
                foreach ($grade as $item){
                    $returnDatas[$item][] = $data;
                }
            }

        }
        //dd($returnDatas);
        ksort($returnDatas);
        return $returnDatas;


    }

    public function getList2($id,$key,$subject,$grade,$contain='',$remove='',$isList = false){
        /*
        if($key){
            $where = [['hide','=',0],['subject','>',0],['grade','>',0]];
            $where[] = ['sortname' ,'=' ,$key];
            if(!empty($contain)){
                $contain = explode("|",$contain);//包含
                foreach ($contain as $item){
                    $where[] = ["raw_title",'like',"%$item%"];
                }
            }
            if(!empty($remove)){
                $remove = explode("|",$remove);//排除
                foreach ($remove as $item){
                    $where[] = ['raw_title','not like',"%$item%"];
                }
            }
        }else{
            return 0;
        }

        $subjectquery = LBookGoodsModel::where($where)
            ->select(
                \DB::raw('any_value(raw_title) as raw_title'),
                'subject',
                \DB::raw('any_value(grade) as grade'),
                \DB::raw('any_value(pic_url) as pic_url'),
                \DB::raw('any_value(view_price) as view_price'),
                \DB::raw('any_value(nick) as nick'),
                \DB::raw('any_value(shopLink) as shopLink'),
                \DB::raw('any_value(view_fee) as view_fee'),
                \DB::raw('any_value(detail_url) as detail_url')
            )
            ->groupby('subject');
        $datas =  LBookGoodsModel::where($where)
            ->select(
                \DB::raw('any_value(raw_title) as raw_title'),
                \DB::raw('any_value(subject) as subject'),'grade',
                \DB::raw('any_value(pic_url) as pic_url'),
                \DB::raw('any_value(view_price) as view_price'),
                \DB::raw('any_value(nick) as nick'),
                \DB::raw('any_value(shopLink) as shopLink'),
                \DB::raw('any_value(view_fee) as view_fee'),
                \DB::raw('any_value(detail_url) as detail_url')
            )
            ->groupby('grade')
            ->union($subjectquery)->get();
        return $datas;
        */
        if($key){
            $where = [];
            //$where[] = ['id','in',$id];
            $where[] = ['raw_title' ,'like' ,"%$key%"];
            if(!empty($contain)){
                $contain = explode("|",$contain);//包含
                foreach ($contain as $item){
                    $where[] = ["raw_title",'like',"%$item%"];
                }
            }
            if(!empty($remove)){
                $remove = explode("|",$remove);//排除
                foreach ($remove as $item){
                    $where[] = ['raw_title','not like',"%$item%"];
                }
            }
            if(array_key_exists($subject,$this->subject)){
                $where[] = ['raw_title','REGEXP',$this->subject[$subject]];
            }

            if($grade == "3"){
                $where[] = ['raw_title','NOT like','%初%'];
            }
            if(array_key_exists($grade,$this->grade)){
                $where[] = ['raw_title','REGEXP',$this->grade[$grade]];
            }

        }else{
            return 0;
        }
       // dd($where);

       // \DB::connection("")->table("")->or
        $subSql = LBookGoodsModel::whereIN('id',$id)
            ->select("raw_title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url','shopTop','bookTop','has_found')
            ->where('hide','0')
            ->where('detailstatus','>=','0')
            ->orderby('bookTop','desc')
            ->orderby('shopTop','desc')
            ->orderby('has_found','desc')
            ->orderby('isbn','desc')
            ->orderby('yeaar','desc')
            ->orderby(\DB::raw('view_price+view_fee'),'asc');

//        $queryObj = \DB::connection("mysql_local")
//            ->table(\DB::raw("(({$subSql->toSql()}) as subT)"))
//            ->mergeBindings($subSql->getQuery())
//            ->where($where)
//            ->orderby('has_found','desc');

        #dd($queryObj->first());
        /*
        $queryObj =  LBookGoodsModel::whereIn('id',$id)->where($where)
            ->select("raw_title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url','shopTop','bookTop')
            ->orderby('shopTop','desc')
            ->orderby('yeaar','desc')
            ->orderby(\DB::raw('view_price+view_fee'),'asc');*/
        $data = $isList? $subSql->get(): $subSql->first();

        return $data;

    }


    public function getList3($key,$subject,$grade,$contain='',$remove='',$isList = false){
        if(!$isList){
            $contains = explode("|",$contain);
            $whereRaw = "$grade in (grade_id) and $subject in (subject_id) and bookname like '%$key%'";
            if(count($contains) > 0){
                foreach ($contains as $item){
                    $whereRaw .= " and bookname like '%$item%'  ";
                }
            }
            //不能删
            /*$isbn = \DB::connection("mysql_zjb")
                ->table("a_tongji_search_isbn_temp1")
                ->whereRaw($whereRaw)
                ->whereNotNull('isbn')
                ->orderBy('searchnum','desc')->value('isbn');*/
            $isbn = false;
            if($isbn){
                $data = LBookGoodsModel::where([['isbn',$isbn],['hide','0']])
                    ->select("raw_title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url','shopTop','bookTop')
                    ->first();
                if($data){
                    return $data;
                }
            }

        }


        $q = $key.str_replace('|',' ',$contain);

        if(array_key_exists($subject,$this->subject)){
            $q .= "(" . $this->subject[$subject] .")";
        }


        $q = "@raw_title($q)";
        if(array_key_exists($grade,$this->sphinxGrade)) {
            $q .= "&@raw_title(" . $this->sphinxGrade[$grade] . ")";
        }
        if($grade==3){
            $q .= '&@raw_title(-(初三))';
        }

        $removes = explode('|',$remove);
        $removeStr = "";
        if(count($removes) > 0){
            $q .= "&";
            foreach ($removes as $item){
                if(!empty($item)){
                    $removeStr .= "@raw_title (-(".$item."))&" ;
                }
            }
        }

        $removeStr = str_replace_last("&",'',$removeStr);
        $q .= $removeStr;
        //$q = str_replace_last("&",'',$q);//清楚最后一个& 现在只搜索 下册和全一册 所以 先注释 直接在后面把上册排除
        if(cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id==2){
            $q .= "@raw_title(-上册)";
        }else{
            $q .= "@raw_title(-下册)";
        }


        $sphinx = new SphinxClient();
        $sphinx->setServer("192.168.0.200", 9312);

        $sphinx->SetArrayResult (true);
        $sphinx->SetMatchMode(SPH_MATCH_EXTENDED2);
        $sphinx->SetRankingMode(SPH_RANK_PROXIMITY);
        $sphinx->SetSortMode(SPH_SORT_EXPR,"@weight");
        $sphinx->setMaxQueryTime(2000);

        $sphinx->SetLimits(0, 1000);



        $result = $sphinx->Query($q);
        $id = [];

        if($result){

            if($result["total"] !== "0"){
                foreach ($result['matches'] as $item){
                    $id[] = $item["id"];
                }
                return $list = $this->getList2($id,$key,$subject,$grade,$contain,$remove,$isList);

            }else{
                $totaobaokey  = $key.$grade."年级" ;
                if(array_key_exists($subject,$this->subject)){
                    $totaobaokey .= $this->subject[$subject];
                }
               $this->loadTaobao($key,$totaobaokey);
                return [];
            }
        }else{
            $totaobaokey  = $key.$grade."年级" ;
            if(array_key_exists($subject,$this->subject)){
                $totaobaokey .= $this->subject[$subject];
            }
            return $this->loadTaobao($key,$totaobaokey);

        }

    }

    public function getListTrue($key,$subject,$grade,$contain='',$remove='',$isList = false){
        if(!$isList){
            $contains = explode("|",$contain);
            $whereRaw = "$grade in (grade_id) and $subject in (subject_id) and bookname like '%$key%'";
            if(count($contains) > 0){
                foreach ($contains as $item){
                    $whereRaw .= " and bookname like '%$item%'  ";
                }
            }
            //不能删
            /*$isbn = \DB::connection("mysql_zjb")
                ->table("a_tongji_search_isbn_temp1")
                ->whereRaw($whereRaw)
                ->whereNotNull('isbn')
                ->orderBy('searchnum','desc')->value('isbn');*/
            $isbn = false;
            if($isbn){
                $data = LBookGoodsModel::where([['isbn',$isbn],['hide','0']])
                    ->select("raw_title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url','shopTop','bookTop')
                    ->first();
                if($data){
                    return $data;
                }
            }

        }

        $q = $key.str_replace('|',' ',$contain);

        if(array_key_exists($subject,$this->subject)){
            $q .= "(" . $this->subject[$subject] .")";
        }

        $q = "@raw_title($q)";
        if(array_key_exists($grade,$this->sphinxGrade)) {
            $q .= "&@raw_title(" . $this->sphinxGrade[$grade] . ")";
        }

        $removes = explode('|',$remove);
        $removeStr = "";
        if(count($removes) > 0){
            $q .= "&";
            foreach ($removes as $item){
                if(!empty($item)){
                    $removeStr .= "@raw_title (-(".$item."))&" ;
                }
            }
        }

        $removeStr = str_replace_last("&",'',$removeStr);
        $q .= $removeStr;
        //$q = str_replace_last("&",'',$q);//清楚最后一个& 现在只搜索 下册和全一册 所以 先注释 直接在后面把上册排除
        $q .= "@raw_title(-上册)";

        $sphinx = new SphinxClient();
        $sphinx->setServer("192.168.0.200", 9312);

        $sphinx->SetArrayResult (true);
        $sphinx->SetMatchMode(SPH_MATCH_EXTENDED2);
        $sphinx->SetRankingMode(SPH_RANK_PROXIMITY);
        $sphinx->SetSortMode(SPH_SORT_EXPR,"@weight");
        $sphinx->setMaxQueryTime(2000);

        $sphinx->SetLimits(0, 1000);



        $result = $sphinx->Query($q);
        $id = [];
        if($result){

            if($result["total"] !== "0"){
                foreach ($result['matches'] as $item){
                    $id[] = $item["id"];
                }
                return $list = $this->getList2($id,$key,$subject,$grade,$contain,$remove,$isList);

            }else{
                $totaobaokey  = $key.$grade."年级" ;
                if(array_key_exists($subject,$this->subject)){
                    $totaobaokey .= $this->subject[$subject];
                }
                $this->loadTaobao($key,$totaobaokey);
                return [];
            }
        }else{
            $totaobaokey  = $key.$grade."年级" ;
            if(array_key_exists($subject,$this->subject)){
                $totaobaokey .= $this->subject[$subject];
            }
            return $this->loadTaobao($key,$totaobaokey);

        }

    }


    private function checkTitle($title){
        $return = [];
        $grade = [];
        if(count($this->nums) > 0){
            foreach ($this->nums as $num){
                $grade[$this->chinese2number($num)] = $this->datas[$this->chinese2number($num)];
            }
        }

        if(count($grade) > 0){
            $this->datas = $grade;
        }

        foreach ($this->datas as $key =>$data){
            foreach ($data as $datum){
                if(strpos($title,$datum) !== false){

                    $return[] = $key;
                }
            }

        }
        return array_unique($return);
    }

    private function checkContain($contain){
        $grade = array("一年级",'二年级','三年级','四年级','五年级','六年级','七年级','八年级','九年级');
        return in_array($contain,$grade);
    }

    private function chinese2number($num){
        $chinese = [
            "一"=>'1',
            '二' => '2',
            '三' => '3',
            '四' => '4',
            '五' => '5',
            '六' => '6',
            '七' => '7',
            '八' => '8',
            '九' => '9'
        ];
        return $chinese[$num];
    }

    private function loadTaobao($sortname,$key){
        //dump($key);
        $taobao_json  = file_get_contents("https://s.taobao.com/search?ajax=true&q=".urlencode($key));
        $taobao_json = json_decode($taobao_json,true);

        if(isset($taobao_json["mainInfo"]["traceInfo"]["traceData"]["filter-tips"])){
            $filter_tips = $taobao_json["mainInfo"]["traceInfo"]["traceData"]["filter-tips"];
            if($filter_tips == "filter-tips:noresultommit"){
                echo("<h3><i class=\"fa fa-warning text-yellow\"></i> 没有!</h3>");
//                echo "淘宝上也没有搜到";
//                echo "<br/>";
//                echo "搜索地址：<strong>https://s.taobao.com/search?q=$key</strong>";
//                echo "<br>";
//                echo "淘宝说：";
//                echo "<br/>";
//                echo "&nbsp;&nbsp;&nbsp;&nbsp;别担心，我们根据部分搜索词帮您找到了一些结果：";
//                dd( $taobao_json["mainInfo"]["traceInfo"]["traceData"]["fewWords"]);
            }
        }else{
            $auctions = $taobao_json["mods"]["itemlist"]["data"]["auctions"];
            $returnData = [];
            if(isset($auctions) || $auctions == null){
                return $returnData;
            }

            foreach ($auctions as $item){
                $bookInfo = LBookGoodsModel::where('detail_url',$item['nid'])->first();
                if(!$bookInfo){
                    $insertData = ['title' =>$item["title"],
                        'raw_title' => $item["raw_title"],
                        'detail_url' => $item["nid"],
                        'view_price' => $item["view_price"],
                        'view_fee' => $item['view_fee'],
                        'item_loc' => $item['item_loc'],
                        'nick' => $item['nick'],
                        'shopLink' => $item["user_id"],
                        'sortname' => $sortname
                    ];
                    $result = LBookGoodsModel::create($insertData);
                    $returnData[] =  $result->id;
                }else{
                    $returnData[] =  $bookInfo->id;
                }

            }
          // dd($returnData);
            return $returnData;
           // $this->cre
            //foreach ()
        }

    }
}