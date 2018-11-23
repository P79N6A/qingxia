<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/15
 * Time: 下午3:50
 */

namespace App\Http\Controllers\Chart;


use App\AWorkbookNew;
use App\Http\Controllers\Controller;
use App\LModel\LBookGoodsModel;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewGoods;
use App\LocalModel\NewBuy\NewGoodsTrue;
use App\LocalModel\NewBuy\NewOnly;
use App\Sort;
use App\Utils\Http;
use App\Utils\Search;
use App\Utils\SphinxClient;
use function foo\func;
use Illuminate\Http\Request;

class TaobaoBookController extends Controller
{
    function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (floatval($s1) + floatval($s2)) * 1000;
    }
    public function index($keyword='',$contain = '',$remove =''){

        $search = new Search();
        $result = $search
            ->set_index('bookgoods')
            ->set_match_mode('extend')
            //->set_sort_mode(SPH_SORT_EXTENDED,'@weight DESC,shopTop DESC')
            ->set_ranking_mode('sum((exact_hit*1000+exact_order*1000-min_gaps*1000-min_hit_pos*10)*user_weight)')
            //->set_filter("raw_title",'高',true)
            ->go('课课练');
        //dd($result);
        //dump($result);
// 检测 PHP-Spinx 模块是否安装成功

        if (!in_array('sphinx', get_loaded_extensions())) {
           // die('模块不存在，请检查！');
        }

        $s = new SphinxClient();
        $s->setServer("192.168.0.200", 9312);

        $s->SetArrayResult (true);
        $s->setMatchMode(SPH_MATCH_EXTENDED2);

        $s->setMaxQueryTime(2000);
        $s->SetLimits(0, 500);
        //$s->SetSortMode(SPH_SORT_EXTENDED,'@weight DESC');
        $s->SetRankingMode(SPH_RANK_PROXIMITY);
        //$s->SetRankingMode(SPH_RANK_EXPR,'sum((exact_hit*1000+exact_order*1000-min_gaps*1000-min_hit_pos*10)*user_weight)');
        //$result = $s->Query("@raw_title(课课练) & @raw_title(语文) & (@raw_title(3)|@raw_title(三)) & @raw_title(-钟书)");
        //$result = $s->Query("@raw_title(课课练) & @raw_title(数学) & (@raw_title(3)|@raw_title(三)) & @raw_title(-(钟书)) &@raw_title(-(字帖)) & @raw_title(-(黄冈))");
        //$result = $s->Query("@raw_title(课课练语文) & (@raw_title(3)|@raw_title(三)) & @raw_title(-(钟书))");
        $result = $s->Query("@raw_title(课课练课课练语文)&@raw_title((3|三)年级) & @raw_title (-(字帖))&@raw_title (-(提优拔尖))");

       // ( @raw_title (-(字帖)) | @raw_title (-(提优拔尖)))
        print_r($result);
        exit;
        //$result = $result['matches'];
       /// $result = array_column($result,'id');
       // dd($result);
        //exit;*/

       if($keyword){
           //(new LBookGoodsModel())->getList2($keyword,$contain,$remove);
            if($contain == "-"){
                $contain = "";
            }
            if($remove == "-"){
                $remove = "";
            }
           $datas['list'] = (new LBookGoodsModel())->getList($keyword,$contain,$remove);
           return view("chart.taobao_book.index",['datas'=>$datas,'key'=>$keyword,'contain'=>$contain,'remove'=>$remove]);

       }else{
           $datas["list"] = [];
           return view("chart.taobao_book.index",['datas'=>$datas,'key'=>'','contain'=>$contain,'remove'=>$remove]);
       }
    }

    public function index2($keyword='',$contain = '',$remove =''){
        return view("chart.taobao_book.index2",['datas'=>[]]);
        if($keyword){
            $datas = (new LBookGoodsModel())->getList2($keyword,$contain,$remove);
            $trueDatas = [];
            foreach ($datas as &$data){
                //echo $data->subject."--".$data->grade."--".$data->raw_title."<br>";
                foreach ($this->getAllSubject($data->subject) as $item){
                    $trueDatas[$item][$data->grade] = [
                        'raw_title'=>$data->raw_title,
                        'pic_url' => $data->pic_url,
                        'view_price' => $data->view_price,
                        'nick' => $data->nick,
                        'shopLink' => $data->shopLink,
                        'view_fee' => $data->view_fee,
                        'detail_url' => $data->detail_url];
                }
            }
            foreach ($trueDatas as &$data){
                $this->checkGrade($data);
                ksort($data);
            }
            $this->checkSubject($trueDatas);
            ksort($trueDatas);
        }
    }

    public function simpleindex($sortname='',$contain = '',$remove =''){
        //getNewRecord("课本八年级物理上册苏科版");
        $sort_id = cache('all_sort_now')->where('name',$sortname)->first()->id;

        $all_need_bought = NewBoughtRecord::where([['sort',$sort_id],['status','=',0],['version_year',cache('now_bought_params')->where('uid',auth()->id())->first()->version_year]])->where(function ($query){
            $query->where(['volumes_id'=>cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id])->orWhere(['volumes_id'=>3]);
        })->select('only_id','status')->with('hasOnlyDetail:id,grade_id,subject_id,newname')->withCount('hasFound')->get();
        if(isset($_REQUEST['test']) && $_REQUEST['test']==='test'){
            dd($all_need_bought);
        }

        $data['grade_subject_info'] =  $all_need_bought->groupBy(function ($item,$key){
           return $item->hasOnlyDetail->grade_id;
        })->transform(function($item1,$key1){
            return $item1->groupBy(function ($item2,$key2){
                return $item2->hasOnlyDetail->subject_id;
            })->sortBy(function ($s_value,$s_key){
                return $s_key;
            });
        })->sortBy(function ($s_value1,$s_key1){
            return $s_key1;
        });

//
//        foreach ($data['grade_subject_info'] as $grade=>$grade_info){
//            foreach ($grade_info as $subject=>$subject_info){
//                var_dump($grade.'_'.$subject);
//            }
//        }
//dd('qeqwe');



        $data['all_need_buy_info'] = $all_need_bought->groupBy(function ($item,$key){
            return $item->hasOnlyDetail->grade_id;
        })->transform(function($item1,$key1){
            return $item1->groupBy(function ($item2,$key2){
                return $item2->hasOnlyDetail->subject_id;
            })->sortBy(function ($s_value,$s_key){
                return $s_key;
            });
        })->sortBy(function ($s_value1,$s_key1){
            return $s_key1;
        })->transform(function ($item,$key){
            return $item->collapse();
        })->collapse();

//        dd($data['grade_subject_info']);
//                foreach ($data['grade_subject_info'] as $grade=>$grade_info){
//            foreach ($grade_info as $subject=>$subject_info){
//                var_dump($grade.'_'.$subject);
//            }
//        }
//dd('qeqwe');


        return view("chart.taobao_book.simpleindex",['data'=>$data,'datas'=>[],'sortname' => $sortname,'contain' => $contain,'remove' => $remove]);
    }

    public function getBookInfo(Request $request){
        $keyword = $request["key"];
        $sort_id = cache('all_sort_now')->where('name',$keyword)->first()->id;
        $subject = $request["subject"];
        $grade  = $request["grade"];
        $contain = $request["contain"]?$request["contain"]:$keyword;
        $remove = $request["remove"];
        $data = (new LBookGoodsModel())->getList3($keyword,$subject,$grade,$contain,$remove);
        $assignData =  ['sort'=>$sort_id,'item'=>$data,'keyword'=>$keyword,'subject'=>$subject,'grade'=>$grade,'contain'=>$contain,'remove'=>$remove];

        if($data){

            $recordData = getRecord($data->detail_url);
           // dd($recordData);
            $assignData["record"] = $recordData;
        }

        return view('chart.taobao_book.bookinfo', $assignData);

    }

    public function getBookInfoTrue(Request $request)
    {
        $keyword = $request["key"];
        $subject = $request["subject"];
        $grade  = $request["grade"];
        $contain = $request["contain"]?:$keyword;
        $remove = $request["remove"];
        $data = (new LBookGoodsModel())->getListTrue($keyword,$subject,$grade,$contain,$remove);
        $assignData =  ['item'=>$data,'keyword'=>$keyword,'subject'=>$subject,'grade'=>$grade,'contain'=>$contain,'remove'=>$remove];

        if($data){

            $recordData = getRecord($data->detail_url);
            // dd($recordData);
            $assignData["record"] = $recordData;
        }

        return view('chart.taobao_book.bookinfo', $assignData);
    }


    public function saveRemove(Request $request){
        $sortname = $request["sortname"];
        $remove = $request["remove"];
        $result = \DB::connection('mysql_local')
            ->table("a_book_goods_remove")
            ->updateOrInsert(['sortname'=>$sortname],['sortname'=>$sortname,'remove'=>$remove]);
        //exit(\GuzzleHttp\json_decode(['status'=>$result]));
    }

    public function getRemove(Request $request){
        $sortname = $request["sortname"];
        $remove = \DB::connection("mysql_local")->table('a_book_goods_remove')->where('sortname',$sortname)->value('remove');
        exit($remove);
    }


/*
    private function wordSplit($keywords) {
        $fpath = ini_get('scws.default.fpath');
        $so = scws_new();
        $so->set_charset('utf-8');
        $so->add_dict($fpath . '/dict.utf8.xdb');
        //$so->add_dict($fpath .'/custom_dict.txt', SCWS_XDICT_TXT);
        $so->set_rule($fpath . '/rules.utf8.ini');
        $so->set_ignore(true);
        $so->set_multi(false);
        $so->set_duality(false);
        $so->send_text($keywords);
        $words = [];
        $results =  $so->get_result();
        foreach ($results as $res) {
            $words[] = '(' . $res['word'] . ')';
        }
        $words[] = '(' . $keywords . ')';
        return join('|', $words);
    }
    */
    public function getSortByKey(){
        $key = isset($_REQUEST["key"])?$_REQUEST["key"]:false;
        $count = isset($_REQUEST["count"])?$_REQUEST["count"]:10;
        $list = (new Sort())->getSortByKey($key,$count);
        $datas = [];
        foreach ($list as $item){
            $datas[] = ['label'=>$item,'value'=>$item];
        }
        exit(json_encode($datas));

    }

    public function hideItem($id){
        //LBookGoodsModel::where("detail_url",$id)->update(['hide'=>1]);
        //echo LBookGoodsModel::where("detail_url",$id)->toSql();
        $result = \DB::connection("mysql_local")->table("a_book_goods")->where("detail_url","=",$id)->update(['hide'=>1]);

        echo \GuzzleHttp\json_encode(['status'=>$result]);
        exit;
    }

    public function shopTop(Request $request){
        $shopid = $request["shopid"];
        $top = $request["top"];
        $val = $request['val'];
        if($top == 'shopTop'){
            $where = ['shopLink' ,'=',$shopid];
        }else{
            $where = ['id','=',$shopid];
        }
        $result = \DB::connection("mysql_local")->table("a_book_goods")->where([$where])->update([$top=>$val]);
        exit(\GuzzleHttp\json_encode(['status'=>$result]));
    }
    public function getBookList($keyword,$subject,$grade,$contain='',$remove=''){
        $sort_id = cache('all_sort_now')->where('name',$keyword)->first()->id;
        if(isset($_GET['contain'])){
            $contain = $_GET['contain'];
        }
        if(isset($_GET["remove"])){
            $remove = $_GET["remove"];
        }
        $data = (new LBookGoodsModel())->getList3($keyword,$subject,$grade,$contain,$remove,true);
        if(isset($contain)){
            $contain = $keyword;
        }
        return view('chart.taobao_book.booklist',
            [
                'sort'=>$sort_id,
                'subject'=>$subject,
                'grade'=>$grade,
                'data'=>$data,
                'title'=>$grade.'年级',
                'contain'=>$contain,
                'remove' => $remove]);
    }

    public function shopList($shopId){
        $data = LBookGoodsModel::where('shopLink',$shopId)->select("raw_title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url','shopTop')->get();
        return view('chart.taobao_book.shoplist',
            ['data'=>$data]);
    }

    public function addChart($goodsId,$jId){
        //新增购买
        if(NewBoughtRecord::where([['only_id',$jId],['status',0]])->count() != 1){
            exit(json_encode(['status'=>0,'msg'=>'非待购买练习册']));
        }
        $goodsInfo = NewGoods::where([["detail_url",$goodsId]])->first();
        if($goodsInfo){
            $updateData = [
                'shop_id'          => $goodsInfo->shopLink,
                'goods_id'         => $goodsId,
                'goods_price'      => $goodsInfo->view_price,
                'goods_fee'         => $goodsInfo->view_fee,
                'uid'               => auth()->id(),
                'bought_at'       => date("Y-m-d H:i:s"),
                'status'         =>1
                ];

            $result = NewBoughtRecord::where([['only_id',$jId],['status',0]])->update($updateData);
            if($result){
                $only_id = $jId;
//                $book_name = $request->book_name;
//                $version_id = $request->now_version_id;
//                $sort_id = $request->sort_id;
//                $data_id = 0;


                $now = NewOnly::find($only_id);
                $data['sort'] = $now->sort;
                $data['newname'] = $now->newname;
                $data['bookname'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year.'年'.$data['newname'];
                $data['status'] = 1;
                $data['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                $data['grade_id'] = $now->grade_id;
                $data['subject_id'] = $now->subject_id;
                $data['volumes_id'] = $now->volumes_id;
                $data['version_id'] = $now->version_id;
                $data['from_only_id'] = $only_id;
                $data['now_status'] = 1;

                if(AWorkbookNew::where($data)->count()>0){
                    //ATongjiBuy::where($data)->delete();
                    return response()->json(['status'=>0,'type'=>'cancel']);
                }
                $data['grade_name'] = '';
                $data['subject_name'] = '';
                $data['volume_name'] = '';
                $data['version_name'] = '';
                $data['sort_name'] = '';
                $data['ssort_id'] = 0;
                $data['update_uid'] = \Auth::id();
                $data['updated_at'] = date('Y-m-d H:i:s',time());
                if(AWorkbookNew::max('id')>1000000){
                    $data['id'] = AWorkbookNew::max('id')+1;
                }else{
                    $data['id'] = 1000000+AWorkbookNew::max('id');
                }
                if($a = AWorkbookNew::create($data)){
                    //优化设计/2018年_六年级_英语_下册_译林版_97下册87549939121/
                    make_answer_dir($data['id']);
                    //取消待买状态
                    NewOnly::where('id',$only_id)->update(['need_buy'=>0]);
                    NewBoughtRecord::where(['only_id'=>$only_id,'status'=>0])->update(['status'=>1]);
                    //更新以前练习册状态
//                        AWorkbookNew::where([['id','<',1000000],['grade_id',$data['grade_id']],['subject_id',$data['subject_id']],['volumes_id',$data['volumes_id']],['version_id',$data['version_id']],['sort',$data['sort']]])->update(['has_update'=>1]);
                    return response()->json(['status'=>1,'type'=>'new','only_id'=>$data['from_only_id'],'new_id'=>$data['id'],'new_name'=>$data['bookname'],'only_name'=>$data['newname']]);
                };


                //1.改变状态   2.新增记录  3.生成目录


                exit(json_encode(['status' => '1' ,'msg' => '操作成功']));
            }else{
                exit(json_encode(['status' => '1' ,'msg' => '数据插入失败']));
            }
        }else{
            exit(json_encode(['status' => '0','msg' => '商品已下架']));
        }
        $dbObj = \DB::connection("mysql_local")->table("a_book_goods_cart");
        if($dbObj->where([['uid','=',auth()->id()],['goodsId','=',$goodsId]])->count() > 0){
            exit(json_encode(['status'=>0,'msg'=>'已加入过']));
        }
        $price = LBookGoodsModel::where('detail_url',$goodsId)->value(\DB::raw('view_price+view_fee') );

        $result = $dbObj->insert(['uid'=>auth()->id(),'goodsId'=>$goodsId,'price'=>$price,'addtime'=>time()]);
        exit(json_encode(['status'=>$result,'msg'=>'操作成功']));
    }

    public function cartList($uid=null){
        if($uid){
            $lists = \DB::connection("mysql_local")->table("a_book_goods_cart")
                ->select('id',\DB::raw("(select raw_title from a_book_goods where a_book_goods.detail_url = a_book_goods_cart.goodsId limit 1) as bookname"),'goodsId','price','addtime','uid')
                ->where("uid",$uid)->paginate();
            $data = ['datas'=>$lists];
            if(count($lists) > 0){
                $uid = $lists[0]->uid;
                $username = \DB::connection("mysql_local")->table("users")->where('id',$uid)->value("name");
                $data['username'] = $username;
            }else{
                $data["username"] = "";
            }
            $total = \DB::connection("mysql_local")->table("a_book_goods_cart")->where('uid',$uid)->value(\DB::raw("sum(price) as total"));
            $data['total'] = $total;
            return view("chart.taobao_book.cartlist",$data);
        }else{
            $lists = \DB::connection("mysql_local")
                ->table("a_book_goods_cart")
                ->select(\DB::raw("count(uid) as num"),\DB::raw("(select name from users where users.id=a_book_goods_cart.uid) as username"),'uid')
                ->groupBy("uid")->paginate(30);

            return view('chart.taobao_book.cartindex',['lists'=>$lists]);
        }
    }
    private function getAllSubject($subject){
        switch ($subject){
            case '100':
                return ["1","2"];
            case "101":
                return ["1","2","3"];
            case "102":
                return ["1","2","3","4"];
            case "103":
                return ["1","2","3","4","5"];
            case "104":
                return ["1","2","3","4","5","6"];
            case "105":
                return ["1","2","3","4","5","6","7"];
            case "106":
                return ["1","2","3","4","5","6","7","8"];
            case "107":
                return ["1","2","3","4","5","6","7","8","9"];
            case "108":
                return ["1","2","3","4","5","6","7","8","9","10"];
            default:
                return [$subject];
        }
    }

    private function checkGrade(&$data){
        $as = ["1","2","3","4","5","6","7","8","9"];
        foreach ($as as $a){
            if(!isset($data[$a])){
                $data[$a] = [];
            }
        }
    }

    private function checkSubject(&$data){
        $ss = ["1","2","3","4","5","6","7","8","9","10"];
        foreach ($ss as $s){
            if(!isset($data[$s])){
                $data[$s] = [];
            }
        }
    }


    function curl_post_https($url,$data,$setheader){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER,$setheader);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }

    function curl_get_https($url,$setheader){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_HTTPHEADER,$setheader);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 0); // 发送一个常规的Post请求
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }




}