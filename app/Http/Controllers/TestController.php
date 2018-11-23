<?php

namespace App\Http\Controllers;

use App\AnswerModel\AWorkbook1010Cip;
use App\ATongjiBuy;
use App\ATongjiSearchIsbnNew;
use App\AWorkbook1010;
use App\AWorkbookFeedback;
use App\AWorkbookNew;
use App\AWorkbookRds;
use App\BaiduNewDaan;
use App\Book;
use App\DataSortCollect;
use App\LocalModel\ATongjiSearchIsbn;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LwwBookPageTimupos;
use App\LwwBookQuestion;
use App\Workbook;
use Carbon\Carbon;
use App\Http\Controllers\Baidu\GetDataWangController;
use App\Jobs\ProcessPodcast;
use App\LocalModel\AWorkbook1010Bd;
use App\LocalModel\AWorkbook1010Test;
use App\LocalModel\AWorkbookAnswerBd;
use App\LocalModel\AWorkbookAnswerNew;
use App\LocalModel\AWorkbookAnswerTest;
use App\LocalModel\IsbnAll;
use App\LocalModel\IsbnTemp;
use App\LocalModel\IsbnTempEveryday;
use App\LocalModel\NewBuy\New1010;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\NewBuy\NewOnlyDelete;
use App\LocalModel\NewBuy\NewSort;
use App\LocalModel\TaskUid;
use App\Sort;
use App\TempModel\Pinduoduo;
use App\User;
use App\WorkbookAnswer;
use App\WorkbookAnswerRds;
use App\XxBook;
use App\XxHash;
use App\XxPic;

use function foo\func;
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use App\Baidu;
use App\BaiduNew;
use App\AWorkbookMain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Monolog\Logger;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Shitoudev\Phone\PhoneLocation;
use Storage;

class TestController extends Controller
{


    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     */

    public function saveEventLog(Request $request)
    {
        file_put_contents(public_path('12323.txt'), $request->all());
    }


    //public function




    public function test_pdd(Request $request)
    {
        $now_url = $request->header('NowUrl');
        $get_list_url = str_replace('size=50', 'size=1000', $now_url);
        $items = json_decode(file_get_contents($get_list_url));
        $search_name = parse_query($get_list_url)['q'];
        foreach ($items->items as $item){
            try{
                $data['goods_name'] = $item->goods_name;
                $data['goods_id'] = $item->goods_id;
                $data['hd_thumb_url'] = $item->hd_thumb_url;
                $data['normal_price'] = $item->normal_price;
                $data['price'] = $item->price;
                $data['market_price'] = $item->market_price;
                $now_word = $this->getMainWordByTitle($data['goods_name']);
                $data['main_word']=empty($now_word)?'':implode('|',$now_word);
                $data['updatetime'] = time();
                $has_goods = Pinduoduo::where(['goods_id'=>$data['goods_id']])->first();
                if($has_goods){
                    if($has_goods->goods_name!=$data['goods_name']){
                        Pinduoduo::where(['goods_id'=>$data['goods_id']])->update($data);
                    }
                }else{
                    Pinduoduo::create($data);
                }
                #$data['main_word'] = $search_name;
            }catch (\Exception $e){
                print($e);
            }
        }
    }


    public function move_tree($old_key,$new_key,$start_path)
    {
        $oss = new OssController();

        $all_img = $oss->getOssClient()->listObjects('daanpic', ['delimiter' => '/', 'prefix' =>$start_path, 'max-keys' => 1000]);
        //var_dump($all_img);die;
        foreach ($all_img->getObjectList() as $imgs) {
            if($imgs->getKey()==$start_path) continue;
            if ($imgs->getSize() > 0) {
                $img_key = $imgs->getKey();
                $img_new_key = str_replace('/' . $old_key . '/', '/' . $new_key . '/', $img_key);
                $oss->getOssClient()->copyObject('daanpic', $imgs->getKey(), 'daanpic', $img_new_key);
            }
        }

        foreach ($all_img->getPrefixList() as $img_path){
            if($img_path->getPrefix()=='all_book_pages/'.$old_key.'/cut_pages/') continue;
            $this->move_tree($old_key, $new_key, $img_path->getPrefix());
        }
    }


    public function test_book(Request $request)
    {


        $header = [
            'Accept' => 'text/plain, */*;q=0.01',
            'Origin' => 'https://tongji.baidu.com',
            'X-Requested-With' => 'XMLHttpRequest',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36 OPR/42.0.2393.85',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            'DNT' => '1',
            'Referer' => 'https://tongji.baidu.com/web/24834996/visit/toppage?siteId=4875889',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'zh-CN,zh',
        ];
        $http = new \GuzzleHttp\Client($header);
        //https://tongji.baidu.com/web/24834996/overview/index?siteId=4875889
        $cookieFile = base_path('baidu.cookie');
        $cookie = new \GuzzleHttp\Cookie\FileCookieJar($cookieFile,true);
        $response = $http->request('get', 'https://tongji.baidu.com/web/24834996/overview/index?siteId=4875889', [
            'cookies' => $cookie,
        ]);
        file_put_contents(public_path('response.txt'),$response->getBody()->getContents());


        dd('qwe');








        dd(\File::directories('//QINGXIA23/WWW/bookcover'));


        $all = AWorkbookNew::where('id','>',1223128)->select('isbn')->get();



        dd('qwe');



        set_time_limit(0);
        //ignore_user_abort();
        ini_set('memory_limit', -1);
        $type = $_GET['type'];
        switch ($type) {
            case 'update_img':
                $url = $request->header('NowUrl');
                $isbn = DB::connection('mysql_local')->table('test_kdzy')->where('status', 0)->first(['isbn']);
                $data['isbn'] = $isbn->isbn;
                $data['imgs'] = $url;
                $image_info =  getimagesize($url);
                $data['filesize'] = $image_info[0].'_'.$image_info[1];
                DB::connection('mysql_local')->table('test_kdzy_imgs')->insert($data);
                break;
        }
        dd('qwe');
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', -1);


        $start_date = 1535731200;
        $end_date = 1536854400;
        
        foreach(range($start_date, $end_date,86400) as $now_date){
            $all_isbn = ATongjiSearchIsbn::where([['addtime','>',$now_date],['addtime','<=',$now_date+86400],['resultcount','=',0]])->select('isbn')->groupBy('isbn')->get();
            foreach ($all_isbn as $isbn){
                if(ATongjiSearchIsbn::where([['isbn',$isbn->isbn],['resultcount','>',0]])->count()==0){
                    $new['isbn'] = $isbn->isbn;
                    $new['addtime'] = $now_date;
                    $new['resultcount'] = 0;
                    ATongjiSearchIsbnNew::create($new);
                }
            }
        }

        dd('qwe');


        ATongjiSearchIsbn::where([['addtime','>',1535731200],['addtime','<=',1535817600],['resultcount','>',0]])->orderBy('addtime','desc')->chunk(1000,function($workbooks){
            foreach ($workbooks as $workbook){
                $has_now = ATongjiSearchIsbnNew::where('isbn',$workbook->isbn)->select(['resultcount'])->first();
                if($has_now){

                }
                dd($workbook);
            }


        });


        dd('qweqwe');







        //143803181=>'376',143804181=>'419',143820181=>'407',143864181=>'414',143959181=>'417',
        //144305181=>'426',144381181=>'413',
        $book_transfor = [10103171=>'27',13458171=>'31',62248171=>'240',102942181=>'425',116135181=>'377',116138181=>'424',116239181=>'418',116373181=>'420',116389181=>'374',116646181=>'375',119074181=>'428',119176181=>'410',119348181=>'416'];

        #ignore_user_abort();
        $oss = new OssController();
        foreach ($book_transfor as $key=>$value){
            $this->move_tree($value,$key,'all_book_pages/'.$value.'/');
            $all_timupos = LwwBookPageTimupos::where('bookid',$value)->select(['*'])->get();
            foreach ($all_timupos as $timu){
                $timu_arr = $timu->toArray();
                $timu_arr['bookid'] = $key;
                $timu_arr['timuid'] = $key.substr($timu->timuid, strlen($timu->bookid));
                $timu_arr['update_from_id'] = $timu->id;
                $timu_arr['chapterid'] = 0;
                unset($timu_arr['id']);
                $s = LwwBookPageTimupos::create($timu_arr);
                try{
                    $oss->getOssClient()->copyObject('daanpic', "all_book_pages/{$value}/cut_pages/{$timu->timu_page}/{$timu->sort}_{$timu->id}.jpg", 'daanpic', "all_book_pages/{$key}/cut_pages/{$timu->timu_page}/{$timu->sort}_{$s->id}.jpg");
                }catch (\Exception $e){
                    var_dump('not move');
                }

            }

            $all_question = LwwBookQuestion::where('bookid',$value)->select(['*'])->get();
            foreach ($all_question as $question){
                $question_arr = $question->toArray();
                $question_arr['bookid'] = $key;
                $question_arr['timuid'] = $key.substr($question->timuid, strlen($question->bookid));
                $question_arr['update_from_id'] = $question->id;
                $question_arr['chapterid'] = 0;
                unset($question_arr['id']);
                LwwBookQuestion::create($question_arr);

            }
        }

        dd('qqq');


        $start = Carbon::create(2017,1,1)->startOfMonth();

        $end   = Carbon::today()->startOfMonth();
        do
        {
            $now_start = $start;
            $months[$start->format('Y-m')] = [$start->timestamp,$now_start->addMonth()->timestamp];
        } while ($start->addMonth() <= $end);

        $dataController = new GetDataWangController();

        foreach ($months as $month){
            $dataController->auto_update($month[0],$month[1]);
        }





//        $dataController = new GetDataWangController();
//        $dataController->auto_update();
        dd('qwe');



        $all_sort = Sort::where([['id','<=',10021],['id','>',0]])->select('id','name')->orderBy('id','asc')->get();
        foreach ($all_sort as $sort){
            if(NewSort::find($sort->id)===null){
                $new_sort_order['sort_id'] = $sort->id;
                $new_sort_order['sort_name'] = $sort->name;
                NewSort::create($new_sort_order);
            }
        }


        dd('qweqwe');






        if (isset($_GET['f']) == false) {
            exit;
        }
        $fs = explode('-', $_GET['f']);
        var_dump($_GET['f']);die;
        foreach ($fs as $f) {
            if (strlen($f) > 6) {
                exit;
            }
        }

        $base = public_path();
        $js = $base . 'min/js/' . $_GET['f'] . '.js';

        var_dump($js);die;
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $timestamp) {
            header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
            exit;
        }
        header('Content-Type: text/javascript; charset=utf-8');


        $gzip = true;
        if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            $gzip = false;
        } else if (ini_get('zlib.output_compression') == 'On' || ini_get('zlib.output_compression_level') > 0 || ini_get('output_handler') == 'ob_gzhandler') {
            $gzip = false;
        } else if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
            $gzip = false;
        }

        if ($gzip) {
            ob_start('ob_gzhandler');
        }
        echo file_get_contents($js);
        dd('qweqe');
        if ($path['extension'] == 'php') {
            chdir($_SERVER['DOCUMENT_ROOT'] . $path['dirname']);
            $_SERVER['PHP_SELF'] = $_GET['f'];
            $_SERVER['SCRIPT_NAME'] = $_GET['f'];
            $_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . $_GET['f'];
            unset($_GET['f']);
            unset($_REQUEST['f']);
            $_SERVER['QUERY_STRING'] = http_build_query($_GET);
            dd($path);
            include $path['basename'];
        }






        return response()->download(storage_path('app/public/all_ocr_pages/0/cut_pages/1.jpg'),'test.jpg');
        #return Storage::URL('all_ocr_pages/0/cut_pages/1.jpg');
        dd('qweqe');

        $a = json_decode(file_get_contents(public_path('123231111111.txt')));
        foreach ($a->items as $item){
            $data['goods_name'] = $item->goods_name;
            $data['goods_id'] = $item->goods_id;
            $data['hd_thumb_url'] = $item->hd_thumb_url;
            $data['normal_price'] = $item->normal_price;
            $data['price'] = $item->price;
            $data['market_price'] = $item->market_price;
            Pinduoduo::create($data);
        }
        dd($a->items);



        set_time_limit(0);
        //ignore_user_abort();
        ini_set('memory_limit', -1);
        $type = $_GET['type'];
        switch ($type){
            case 'save_the_data':
                if($request->errcode==6666){
                    $self_answer = $request->data['selfAnswerVoList'];
                    if(count($self_answer)>0){
                        $hash = $request->header('NowUrl');
                        foreach ($self_answer as $answer){
                            try{
                                $answer['hash'] = $hash;
                                XxBook::create($answer);
                            }catch(\Exception $e) {
                                print('repeat');
                            }
                        }
                    }

                }
                break;
            case 'start_the_download':
                $now_data = json_decode($request->data);
                XxBook::where('real_id','>',1)->update(['down_status'=>0]);
                XxBook::where('objectId',$now_data->objectId)->update(['down_status'=>1]);
                break;
            case 'end_the_download':
                XxBook::where([['real_id','>',1],['down_status',1]])->update(['down_status'=>0]);
                break;
            case 'get_the_offical_answer':

                $now_pic = $request->header('NowUrl');
                $object_now = XxBook::where('down_status',1)->select('objectId')->first();
                if($object_now){
                    $object_id = $object_now->objectId;
                    try {
                        XxPic::create(['pic_url' => $now_pic, 'pic_type' => 'offical_answer', 'object_id' => $object_id]);
                    }catch(\Exception $e) {
                        print('repeat');
                    }
                }

                break;
            case 'addSelfAnswerReprint':
                $object_id = json_decode($request->data)->objectId;
                #return response()->json(collect(json_decode('{"errcode":6666,"data":{}'))->toArray());
                break;
            case 'get_the_list_by_name':
                $data['request_string'] = json_decode($request->data)->bookName;
                $data['hash'] = $request->header('NowUrl');
                XxHash::create($data);
                break;
            case 'get_the_list_by_isbn':
                $data['request_string'] = json_decode($request->data)->barcode;
                $data['hash'] = $request->header('NowUrl');
                XxHash::create($data);
                #return response()->json(collect(json_decode('{"errcode":6666,"data":{}'))->toArray());
                break;
            case 'get_log_config':
                #{"errcode":6666,"data":{"amountLogIntervalTime":10,"amountLogCountPerRequest":50}}
                return response()->json(['errcode'=>6666,'data'=>(object) array('amountLogIntervalTime'=>6000,'amountLogCountPerRequest'=>50),'status'=>1]);
                break;
            case 'update_book_id':
                $now_data = $request->data;
                XxBook::where('objectId',$now_data['objectId'])->update(['pathCount'=>$now_data['pathCount'],'coverImageThumb'=>$now_data['coverImageThumb']]);
                break;

            default :
                break;


        }
        #{"errcode":6666,"data":{}}
        return response()->json(['errcode'=>6666,'data'=>(object) array(),'status'=>1]);
        dd('qweqwe');

        $maxtime=IsbnTempEveryday::max("addtime");
        $time=date('Y-m-d',strtotime('+1 day',strtotime($maxtime)));
        $time2=str_replace('-','',$time);
        $url="http://www.1010jiajiao.com/api/tongji/get_no_result_isbn_byday/".$time2;
        echo $url."\n";
        $s=file_get_contents($url);
        $arr=json_decode($s,true);
        //print_r($arr);
        foreach($arr['result'] as $k=>$v){
            echo $k."  ";
            $has_isbn_num =IsbnTemp::where(["isbn"=>$v['isbn']])
                ->count();
            if($has_isbn_num==0){
                $re=IsbnAll::where(["isbn"=>$v['isbn']])->first();
                if($re){
                    IsbnTemp::create([
                            "isbn"=>$v['isbn'],
                            "searchnum"=>$v['n'],
                            "bookname"=>$re['bookname'],
                            "grade_id"=>$re['preg_grade_id'],
                            "subject_id"=>$re['preg_subject_id'],
                            "volumes_id"=>$re['preg_volumes_id'],
                            "version_id"=>$re['preg_version_id'],
                            "sort"=>$re['preg_sort_id']
                        ]);
                }else{
                    IsbnTemp::create([
                            "isbn"=>$v['isbn'],
                            "searchnum"=>$v['n']
                        ]);
                }
            }
            IsbnTempEveryday::create([
                "isbn"=>$v['isbn'],
                "searchnum"=>$v['n'],
                "addtime"=>$time
            ]);
        }


        dd('qweqwe');







        echo count(strlen('http://php.net'));
        die;

        $all_book_id = AWorkbookFeedback::whereNotNull('verified_at')->select(DB::raw('distinct bookid as bookid'))->get();
        foreach ($all_book_id as $bookid){
            AWorkbookFeedback::where('bookid',$bookid->bookid)->whereNull('verified_at')->update(['not_need_deal'=>1]);
        }
        dd('qweqwe');





        //{"file":"eNqzsS/IKFBwSCwqSqyMz00s0FCJd3cNiVZKzk1RitXRAItrJiUWp5qZxKekJuenpAJVBLkGhroGA1Xl5ufnKcVqalrb2wEAgbIXkg==","sign":"dd2a953b061f8f6b3749de1faea4fb39","path":"/testS.php"}

//
//        $all_repeat = New1010::where([['version_year','=',2018],['newname','!=',''],['status',1]])->select('newname',DB::raw('count(newname) as repeat_num'),DB::raw('any_value(sort) as sort_now'))->groupBy('newname','sort')->havingRaw('count(newname)>1')->orderBy('newname','asc')->get();
//
//
//        foreach ($all_repeat as $key=>$book){
//            $all_repeat_books[$key] = New1010::where(['newname'=>$book->newname,'version_year'=>2018])->select('id','bookname','cover','collect_count')->withCount('hasAnswers')->orderBy('id','asc')->get();
//
//        }
//
//        $all = [];
//        foreach ($all_repeat_books as $key=>$books){
//            if(count($books)>=2){
//
//            $arr = [];
//            foreach ($books as $key1=>$book){
//                if(strpos($book->cover,'/pic19/') && !strpos($book->cover,'/new/')){
//                    $arr[$key1] = 'jiajiao';
//                    $book->now_type = 'jiajiao';
//                }else{
//                    $arr[$key1] = 'hd';
//                    $book->now_type = 'hd';
//                }
//            }
//            //dd($books->max('has_answers_count'));
//            $now_min_count = $books->min('has_answers_count');
//            $now_max_count = $books->max('has_answers_count');
//            $now_min_book = $books->where('has_answers_count',$now_min_count)->first();
//            $now_max_book = $books->where('has_answers_count',$now_max_count)->first();
//            if($now_min_book->now_type==='jiajiao' && $now_min_count!=$now_max_count){
//                foreach ($books as $key1=>$book) {
//                    $all[] = $book->id;
//                    AWorkbook1010::where('id', $book->id)->update(['is_wrong' => 1]);
//                }
//            }
//            }
//        }
//
//
//
//
//
//        dd($all_repeat_books);
//
//
//
//        dd(collect($all_repeat_books)->collapse()->pluck('id'));
//
//        foreach ($all_repeat_books as $key=>$books){
//            if(count($books)==2){
//                $two_books[$key] = $books;
//                $arr = [];
//                foreach ($books as $key1=>$book){
//                    if(strpos($book->cover,'/pic19/') && !strpos($book->cover,'/new/')){
//                        $arr[$key1] = 'jiajiao';
//                        $book->now_type = 'jiajiao';
//                    }else{
//                        $arr[$key1] = 'hd';
//                        $book->now_type = 'hd';
//                    }
//                }
//                if(collect($arr)->unique()->count()===2){
//                    if($books[0]->has_answers_count!=$books[1]->has_answers_count){
//                        if($books[0]->now_type==='jiajiao'){
//                            New1010::where('id',$books[1]->id)->update(['redirect_id'=>$books[0]->id]);
//                            TaskUid::create(['type'=>'redirect_id','data'=>$books[1]->id.'_'.$books[0]->id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
//                        }else{
//                            New1010::where('id',$books[0]->id)->update(['redirect_id'=>$books[1]->id]);
//                            TaskUid::create(['type'=>'redirect_id','data'=>$books[0]->id.'_'.$books[1]->id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
//                        }
//                    }
//                }
//            }
//        }
//
//
//
//
//
//
//        dd($all_need_parse);
//
//
//
//
//
//        dd('qwe');


        //新
        $all_new_book = AWorkbook1010Test::where([['id','>',1000000],['sort',91]])->select('id','isbn','bookcode')->get();
        $ids = $all_new_book->pluck('id');
        $all_new_answers = AWorkbookAnswerTest::whereIn('bookid',$ids)->select('uid', 'bookid', 'book', 'chapter_id', 'text', 'textname', 'answer', 'status', 'md5_file', 'tid', 'done', 'addtime', 'oss', 'hdid', 'md5answer' )->get();



        $old_books = AWorkbook1010::where([['id','>',1000000],['sort',91]])->select('id','isbn','bookcode')->get();
        foreach ($old_books as $key => $book){
            foreach ($all_new_book as $new_book){
                if($book->isbn===$new_book->isbn){
                    $new_ids[$book->id] = $new_book->id;
                    $new_bookcode[$book->bookcode] = $new_book->bookcode;
                }
            }
        }


        foreach ($all_new_answers as $key=>$answer){
            foreach ($new_ids as $old_id=>$new_id){
                if($answer->bookid==$new_id){
                    $all_new_answers[$key]->bookid = $old_id;
                }
            }
            foreach ($new_bookcode as $old_code=>$new_code){
                if($answer->book==$new_code){
                    $all_new_answers[$key]->book = $old_code;
                }
            }
        }


        foreach ($all_new_answers->toArray() as $new_answer){
            DB::connection('mysql_local')->table('a_workbook_answer_1010_struct')->insert($new_answer);
        }

        dd($all_new_answers->toArray());



        $all_books = New1010::where([['sort',1],['version_id',0],['volumes_id',2],['bookname','like','%人教版%']])->select('id','bookname','newname')->get();

        $all_only = $all_books->pluck('newname')->unique();


        $k = NewOnly::where(['newname'=>'小学同步测控优化设计三年级数学下册人教版福建专版'])->select()->get()->toArray();

        foreach ($k as $kk){
            dd($kk['id']);
            NewOnlyDelete::create($kk);
        }


        dd($k);



        $all_isbn = IsbnTemp::where([['id','11285'],['subject_id','like','%,%'],['volumes_id','like','%,%']])->select('id','subject_id','volumes_id','version_id','grade_id')->get();
        dd($all_isbn->take(10));
        foreach ($all_isbn as $isbn){
            $now_arr = explode(',',$isbn->volumes_id);
            if(collect($now_arr)->count()!=collect($now_arr)->unique()->count()){
                dd('qweqweqweqe');
                IsbnTemp::where('id',$isbn->id)->update(['volumes_id'=>implode(',', collect($now_arr)->unique()->toArray())]);
            }
        }
        dd('qe');



            $a = [3,5,6,7,8,9];


        $all = AWorkbookNew::where([['updated_at','>','2018-04-23 00:00:00'],['update_uid','>',0]])->select('id')->get();
        foreach($all as $value){
            make_answer_dir($value->id);
        }

dd('qweqwe');

//        $all = AWorkbook1010Test::whereIn('id',[1222132,1222137,1222138,1222140,1222141,1222143,1222156,1222158,1222160,1222161,1222162,1222163])->select('id','cover')->get();
//        foreach ($all as $book){
//            $now_dir = '//QINGXIA23/www/pic19/'.$book->id.'/cover/';
//            $cover_cip = \File::files($now_dir);
//            foreach ($cover_cip as $pic){
//
//                if(basename($pic)==str_replace(config('workbook.thumb_image_url').'pic19/'.$book->id.'/cover/', '', $book->cover)){
//                    continue;
//                }
//                AWorkbook1010Test::where('id',$book->id)->update(['cip_photo'=>config('workbook.thumb_image_url').'pic19/'.$book->id.'/cover/'.\File::basename($pic)]);
//            }
//        }
        dd($all);







        $now = AWorkbookNew::where('id','>',1000000)->select('sort',DB::raw('count(sort) as sort_num'))->with('has_sort:id,name')->groupBy('sort')->get();

        foreach ($now as $book){
            print_r($book->sort.'_'.$book->has_sort->name.'____'.$book->sort_num);
            echo '<br />';
        }

        dd(AWorkbookNew::where([['id','>',1000000],['update_uid','>',0]])->count());





        //select * from a_tongji_search_isbn_temp1 where sort=-1 and resultcount=0 and searchnum>50 order by searchnum desc;
        #select * from a_workbook_answer_1010 where bookid = 1221334 order by text asc

//        $a = AWorkbookNew::where('id','>',1000000)->whereNotIn('sort',[1,5,6,8,11,34,44,45,46,50,70,71,73,91,111,124,127,129,140,155,162,166,174,200,212,215,232,234,238,255,269,279,282,288,300,311,360,361,394,433,470,505,514,515,560,628,637,638,651,658,681,698,700,703,728,753,765,797,798,799,867,868,874,888,889,891,909,911,1036,1046,1048,2090,2234,2284,2297,2344,2543,2605,2608,2922,4003,5034,5277,5582,5721,5775,6078,6477,7263,7388,7448,8278,8328,8625])->select()->get();
//        $all_book = [];
//        foreach ($a as $book){
//            $all_book[] = $book->bookname;
//        }
//        dd($all_book);




//        $all = AWorkbook1010Cip::where([['id','>=',247711],['sort','=',9999]])->select('id','isbn','sort')->get();
//
//        foreach ($all as $item){
//            if($item->sort){
//                $now = AWorkbook1010Test::where('isbn',$item->isbn)->select('sort')->get();
//                if(count($now)>0){
//                    if($now[0]->sort>0){
//                        AWorkbook1010Cip::where('id',$item->id)->update(['sort'=>$now[0]->sort]);
//                    }
//
//                }
//            }
//        }

        dd('qwe');




//        $a = AWorkbook1010Cip::where([['id','>',241777]])->select('id','isbn')->get();
//        foreach ($a as $book){
//            $f = AWorkbook1010::where('isbn',$book->isbn)->orderBy('id','desc')->first();
//            if($f){
//                $update['grade_id'] = $f->grade_id;
//                $update['subject_id'] = $f->subject_id;
//                $update['volumes_id'] = $f->volumes_id;
//                $update['version_id'] = $f->version_id;
//                $update['sort'] = $f->sort;
//                AWorkbook1010Cip::where('id',$book->id)->update($update);
//            }
//
//        }

        //dd($a);
        //1217111
        $a = DB::connection('mysql_local')->table('a_workbook_new')->where('id','>',1217111)->select('id')->get();
        foreach ($a as $book){
            make_answer_dir($book->id);
        }

        dd('qweqe');




        $a = DB::connection('mysql_local')->table('a_tongji_search_isbn_temp1')->where([['sort','>=',0],['resultcount',0],['searchnum','>',100]])->select('id','isbn')->orderBy('searchnum','desc')->get();
        foreach ($a as $value){
            $count = AWorkbook1010::where('isbn',$value->isbn)->count();
//            $http = new \GuzzleHttp\Client();
//            sleep(random_int(1, 5));
//            $res = $http->get('http://handler.1010pic.com//api/search/app?&word='.$value->isbn.'&index=zuoyeben2');
//            $response = \GuzzleHttp\json_decode($res->getBody()->getContents());
//            $count = count($response->result->list);
            if($count>0){
                DB::connection('mysql_local')->table('a_tongji_search_isbn_temp1')->where('id',$value->id)->update(['resultcount'=>$count]);
            }
        }
        dd($a);
        
        








        dd(Request::capture()->aaa);

        dd('qweqwe');

//        $now = AWorkbook1010Cip::where('id','>',239437)->whereIn('id',[240634,240636,240638,240639,240641,240643,240644,240645,240647,240648,240649,240650,240653,240654,240658,240659,240663,240664,240665,240668,240670,240673,240674,240679,240687,240689,240693,240696,240697,240698,240699,240700,240703,240704,240707,240708,240709,240714,240718,240719,240721,240723,240724,240728,240729,240733,240734,240737,240738,240739,240742,240743,240744,240749,240750,240754,240758,240759,240763,240764,240765,240768,240769,240774,240777,240778,240779,240784,240787,240788,240794,240798,240799,240802,240803,240804,240807,240808,240809,240814,240815,240816,240818,240819,240821,240824,240826,240829,240833,240834,240835,240838,240839,240843,240844,240847,240848,240849,240852,240854,240856,240858,240859])->select()->get();
        $arr = [];
        $brr = [];
        foreach ([240636,240723,240737,240763,240803] as $a){


            if($a%5==0){
                $arr[] = User::find(8)->name;
                print User::find(8)->name.'__'.$a.'<br/>';
                $brr[] = User::find(8)->name.'__'.$a.'<br/>';
            }elseif ($a%5==1){
                $arr[] = User::find(11)->name;
                print User::find(11)->name.'__'.$a.'<br/>';
                $brr[] = User::find(11)->name.'__'.$a.'<br/>';
            }elseif ($a%5==2){
                $arr[] = User::find(17)->name;
                print User::find(17)->name.'__'.$a.'<br/>';
                $brr[] = User::find(17)->name.'__'.$a.'<br/>';
            }elseif ($a%5==3){
                $arr[] = User::find(19)->name;
                print User::find(19)->name.'__'.$a.'<br/>';
                $brr[] = User::find(19)->name.'__'.$a.'<br/>';
            }elseif ($a%5==4){
                $arr[] = User::find(20)->name;
                print User::find(20)->name.'__'.$a.'<br/>';
                $brr[] = User::find(20)->name.'__'.$a.'<br/>';
            }



//            var_dump($a.'___'.User::find($a%6)->name);
        }

        dd(collect($brr)->sort());

        dd('qwe');
        //dd(md5_file('//QINGXIA23/WWW/book/53天天练_155/人教PEP版_0/img0103.jpg'));


        $all = AWorkbook1010Bd::from('a_workbook_1010_bd as d')->leftJoin('a_workbook_1010_test_copy as c','d.bookname_new','c.bookname_new')->where([['d.update_uid','>',0],['c.bookname','=','']])->select('c.id','d.id as bd_id','d.bookname','d.cover_photo','d.isbn','d.grade_id','d.subject_id','d.volumes_id','d.version_id','d.version_year','d.sort','d.cip_photo')->get();
        try{
            foreach ($all as $book){
                $data['bookname'] = $book->bookname;
                if($book->cover_photo){
                    $now_cover_photo = '//Qingxia23/www/bookcover/'.$book->cover_photo;
                    $dst_cover_photo = 'pic19/'.$book->id.'/'.md5($book->cover_photo).'.'.\File::extension($book->cover_photo);
                    $dst_cover_photo_full = '//Qingxia23/www/'.$dst_cover_photo;
                    \File::copy($now_cover_photo, $dst_cover_photo_full);
                    $data['cover_photo'] = $dst_cover_photo;
                }
                $data['isbn'] = $book->isbn;
                $data['grade_id'] = $book->grade_id;
                $data['subject_id'] = $book->subject_id;
                $data['volumes_id'] = $book->volumes_id;
                $data['version_id'] = $book->version_id;
                $data['version_year'] = $book->version_year;
                $data['sort'] = $book->sort;
                if($book->cip_photo){

                    $now_cip_photo = '//Qingxia23/www/bookcover/'.$book->cip_photo;
                    $dst_cip_photo = 'pic19/'.$book->id.'/'.md5($book->cip_photo).'.'.\File::extension($book->cip_photo);
                    $dst_cip_photo_full = '//Qingxia23/www/'.$dst_cip_photo;
                    \File::copy($now_cip_photo, $dst_cip_photo_full);
                    $data['cip_photo'] = $dst_cip_photo;
                }
                AWorkbook1010Test::where('id',$book->id)->update($data);
            }
        }catch(Exception $e){
            print $e;
        }



        dd($all);


        dd('qwe');





//        $all = DB::connection('mysql_zjb')->table('a_workbook_1010_test')->select(['id','bookname_new'])->get();
//        foreach ($all as $book){
//            if($book->bookname_new){
//            DB::connection('mysql_zjb')->table('a_workbook_1010_test')->where('id',$book->id)->update(['bookname_new'=>str_replace('2018', '2018年', $book->bookname_new)]);
//            }
//        }
        dd('qwe');



        $a = DB::connection('mysql_local')->table('local_cip')->where('isbn','!=','')->whereIn('isbn',['9787200127867','9787200136432','9787564130022','9787107317149','9787107321931','9787564130039','9787200127768','9787200136463','9787200136395','9787107321955','9787564130046','9787107321986','9787107321962','9787110085707','9787107321993','9787110087954','9787110087947','9787213052903','9787551578387','9787538393491','9787538391183','9787551595445','9787551595650','9787558033162','9787538391176','9787538393477','9787558033131','9787563472093','9787563472086','9787558033186','9787563472079','9787538394412','9787558033155','9787563483365','9787538394368','9787563483341','9787558033179','9787558033148','9787561396551','9787539554464','9787539558905','9787539558929','9787539558912','9787508839530','9787508814216','9787508813936','9787508806150','9787558015298','9787508806174','9787508818061','9787508800462','9787558015106','9787508839523','9787508813882','9787558034961','9787508813899','9787508814223','9787558014161','9787558028342','9787519218867','9787519218836','9787558015281','9787519218843','9787565208317','9787558014154','9787565212406','9787558015274','9787565212154','9787558015304','9787558014178','9787563483358','9787567545434','9787563483334','9787567557642','9787545046687','9787811406429','9787213052910','9787545046632','9787545047660','9787517813996','9787303246588','9787545047608','9787534173844','9787545048353','9787517813972','9787213053405',' 9787545038439','9787551407151','9787213053375','9787545038422','9787555304210','9787545038415','9787545046588','9787555304104','9787545047516','9787213053399','9787545037166','9787555304036','9787545039023','9787545039016','9787545039009','9787545038996','9787303208814','9787303208807','9787303208791','9787546212630','9787546212647','9787303136230','9787546212777','9787303136148','9787303136162','9787538514469','9787212087739','9787567556164','9787303136131','9787212087746','9787212067120','9787212087722','9787567550414','9787212067137','','9787519101060','9787554416891','9787519101053','9787554412824','9787554412817','9787214145307','9787214145321','9787214145338','9787214145345','9787214145352','9787211145604','9787214144171','9787214141309','9787214144263','9787214145758','9787567557536','9787214145796','9787214145802','9787567557291','9787567557543','9787214145918','97872145925','9787214146083','9787214146106','9787214146267','9787214146274','9787214144690','9787214145598','9787214146090','9787567541085','9787214144256','9787563483341','9787214145628','9787567558304','9787539558936','9787567558298','9787213053375','9787567555426','9787539558912','9787567556959','9787539558929','9787567557420','9787539558905','9787213053405','9787540550721','9787540550745','9787567556768','9787107313998','9787551594431','9787567558113','9787551594318','9787567556836','9787567560352','97877567549982','9787567506176','9787895091009','9787212087739','9787895091016','9787567560512','9787567560871','9787567559363','9787549941889','9787549929979','9787549930241','9787549929986','9787549930852','9787549930869','9787549930289','9787213053399','9787213053375','9787213053405'])->select()->get();
        foreach ($a as $book){
            dd($book->cip_photo);
        }








//        $now = AWorkbook1010Cip::where('id','>',238082)->whereIn('id',[239150,239188,239244,239251,239302,239304,239314,239398,239404,239426])->select()->get();
//        $arr = [];
//        foreach ([239150,239188,239244,239251,239302,239304,239314,239398,239404,239426] as $a){
//
//            if($a%5==0){
//                $arr[] = User::find(8)->name;
//                print $a.'__'.User::find(8)->name.'<br/>';
//            }elseif ($a%5==1){
//                $arr[] = User::find(11)->name;
//                print $a.'__'.User::find(11)->name.'<br/>';
//            }elseif ($a%5==2){
//                $arr[] = User::find(17)->name;
//                print $a.'__'.User::find(17)->name.'<br/>';
//            }elseif ($a%5==3){
//                $arr[] = User::find(19)->name;
//    print $a.'__'.User::find(18)->name.'<br/>';;
//            }elseif ($a%5==4){
//                $arr[] = User::find(20)->name;
//        print $a.'__'.User::find(19)->name.'<br/>';;
//            }
//
//
//
////            var_dump($a.'___'.User::find($a%6)->name);
//        }
        dd('qweqwe');

//        $all_files = \File::allFiles('D:\wamp64\www\bookcode\test');
//
//        foreach ($all_files as $file){
//            $now_id = explode('_',\File::basename($file))[0];
//            DB::connection('mysql_local')->table('local_cip')->where('id',$now_id)->update(['is_cover'=>1]);
//
//        }
        dd('111');


//        $all_files = \File::allFiles('//QINGXIA23/bookcover');
//
//        try{
//            foreach ($all_files as $file){
//                $now_name = str_replace('\\', '/', $file->getrelativePathname());
//                $now_file = DB::connection('mysql_local')->table('local_cip')->where('cip_photo',$now_name)->select('id')->get();
//                if(count($now_file)>0){
//                    \File::copy($file->getpathname(), 'D:\wamp64\www\bookcode\test/'.$now_file[0]->id.'_'.\File::basename($file));
//                }
//            }
//        }catch (Exception $e){
//            print $e;
//    }






        //dd('test_dir');
        //隐藏上册

//        $all_books = DB::connection('mysql_local')->table('a_workbook_1010_cip_0404')->select('cover_photo','update_uid','updated_at','verified_at','answer_not_complete')->get();
//        foreach ($all_books as $book){
//            $data['update_uid'] = $book->update_uid;
//            $data['updated_at'] = $book->updated_at;
//            $data['verified_at'] = $book->verified_at;
//            $data['answer_not_complete'] = $book->answer_not_complete;
//            AWorkbook1010Cip::where('cover_photo',$book->cover_photo)->update($data);
//        }
//        dd($all_books);



        dd('qweqwe');



        $all_book = AWorkbookNew::where([['id','>',1000000]])->select('id','bookname','sort','version_id')->get();
        $all_dir_name = [];
        foreach ($all_book as $book){
            foreach (['上册','下册','全一册'] as $volumes)
            {
                if($x =strpos($book->bookname, $volumes)){
                    $sort = $book->has_sort->name;
                    $version = substr(str_replace($volumes, '', $book->bookname), $x);
                    $all_dir_name[] = $sort.'_'.$book->sort.'/'.$version.'_'.$book->version_id;

                }
            }
        }
        foreach (collect($all_dir_name)->unique() as $dir){
            if(!is_dir('//QINGXIA23/www/book3/'.$dir)){
                mkdir('//QINGXIA23/www/book3/'.$dir,0777,true);
            }
        }





        dd('QWE');
        //feedback 导入课本

        $all_books = AWorkbook1010::where('book_confirm',1)->select('id','collect_count')->get();
        $new_book_ids = [];
        foreach ($all_books as $book){
            if(AWorkbookFeedback::where('bookid',$book->id)->count()==0){
                $new_book_ids[] = $book->id;
                //var_dump($book->id);
                $new['uid'] = 999999;
                $new['uuid'] = 1111111111111111;
                $new['bookid'] = $book->id;
                $new['text'] = '其它';
                $new['collect_count'] = $book->collect_count;
                $new['is_book'] = 1;
                AWorkbookFeedback::create($new);
            }else{
                AWorkbookFeedback::where('bookid',$book->id)->update(['is_book'=>1,'collect_count'=>$book->collect_count]);
            }
        }
        dd($new_book_ids);
        dd($all_books);





      set_time_limit(0);
      ignore_user_abort();
      ini_set('memory_limit', -1);

      dd('qqq');
        $all_book = AWorkbook1010Cip::where([['grade_id','>',0],['update_uid',0]])->select('id')->get();
        foreach ($all_book as $now_book){
            $now = [];
            if($now_book->id%6===0){
                $now['update_uid'] = 8;
            }elseif($now_book->id%6===1){
                $now['update_uid'] = 11;
            }elseif($now_book->id%6===2){
                $now['update_uid'] = 17;
            }elseif($now_book->id%6===3){
                $now['update_uid'] = 18;
            }elseif($now_book->id%6===4){
                $now['update_uid'] = 19;
            }elseif($now_book->id%6===5){
                $now['update_uid'] = 20;
            }
            AWorkbook1010Cip::where('id',$now_book->id)->update($now);
        }
        dd('qwe');


        $all_book = AWorkbookNew::where([['id','>',1000000]])->select('id','')->get();

        $all_dir_name = [];
        foreach ($all_book as $book){
            foreach (['上册','下册','全一册'] as $volumes)
            {
                if($x =strpos($book->bookname, $volumes)){
                    $sort = $book->has_sort->name;
                    $version = substr(str_replace($volumes, '', $book->bookname), $x);
                    $all_dir_name[] = $sort.'_'.$book->sort.'/'.$version.'_'.$book->version_id;

                }
            }
        }
        foreach (collect($all_dir_name)->unique() as $dir){
            if(!is_dir('//QINGXIA23/book/'.$dir)){
                mkdir('//QINGXIA23/book/'.$dir,0777,true);
            }
        }
        dd('qqqq');






//      dd(AWorkbookFeedback::where('id','>',25556)->select('bookid')->groupBy('bookid')->get());

        $feedbacks = AWorkbookFeedback::where('id','>',25556)->select('bookid')->groupBy('bookid')->get();

        foreach ($feedbacks as $feedback){
            if($feedback->bookid<10000000){
                $data['collect_count'] = AWorkbook1010::where('id',$feedback->bookid)->first()?AWorkbook1010::where('id',$feedback->bookid)->first()->collect_count:0;
                AWorkbookFeedback::where('bookid',$feedback->bookid)->update($data);
            }
        }
        dd('qweqwe');




        $generator = new BarcodeGeneratorPNG();
        echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('9787541966323', $generator::TYPE_CODE_128)) . '">';
        dd('qqqq');




      $str = '2018年必会必考精讲点练九年级化学上册人教版';

      $all_book = AWorkbookNew::where('id','>',1000000)->select('sort','version_id','bookname')->with('has_sort:id,name')->get();

      $all_dir_name = [];
      foreach ($all_book as $book){
          foreach (['上册','下册','全一册'] as $volumes)
          {
              if($x =strpos($book->bookname, $volumes)){
                  $sort = $book->has_sort->name;
                  $version = substr(str_replace($volumes, '', $book->bookname), $x);
                  $all_dir_name[] = $sort.'_'.$book->sort.'/'.$version.'_'.$book->version_id;

              }
          }
      }
      foreach (collect($all_dir_name)->unique() as $dir){
        if(!is_dir('//QINGXIA23/book/'.$dir)){
            mkdir('//QINGXIA23/book/'.$dir,0777,true);
        }
        }
        dd('qqqq');

        foreach (['上册','下册','全一册'] as $volumes)
        {
            if($x =strpos($str, $volumes)){
               dd(substr(str_replace($volumes, '', $str), $x));
            }
        }
      die;


        dd(str_replace(['2014','2015','2016','2017'], '2018','2016年学习之友七年级数学下册北师大版'));

        dd('q');
        //$this->pinyin
      //查找非空文件上传

      $sort = $book = [];
      //,8,11,14,17,18
      foreach([5] as $value){
          $user_name = User::find($value)->name;

          $all_sort_dirs = \File::directories('//QINGXIA23/book/'.$user_name.'/');
          foreach ($all_sort_dirs as $sort_dir){
              $all_book_dirs = \File::directories($sort_dir);
              foreach ($all_book_dirs as $book_dir){
                  $all_imgs = \File::allFiles($book_dir);
                  if(count($all_imgs)>0){
                      $book[] = $book_dir;
                  }
              }
          }
      }


      $book_dirs = collect($book)->unique();
      $this->pinyin=new \Overtrue\Pinyin\Pinyin();

        foreach ($book_dirs as $book_dir){
          $book_name = \File::name($book_dir);
          $book_info = AWorkbookNew::where([['id','>',1000000],['bookname',$book_name]])->first(['grade_id','volumes_id','version_id','subject_id','sort','bookname','version_year']);

          if($book_info){
              $data['bookname'] = $book_info->bookname;
              $data['sort'] = $book_info->sort;
              $data['version_year'] = $book_info->version_year;
              $data['grade_id'] = $book_info->grade_id;
              $data['volumes_id'] = $book_info->volumes_id;
              $data['subject_id'] = $book_info->subject_id;
              $data['version_id'] = $book_info->version_id;
              $data['hdid'] = -1;
              $data['bookcode'] = md5($data['bookname'].$data['sort'].$data['version_year'].$data['grade_id'].$data['volumes_id'].$data['subject_id'].$data['version_id']);
              $data['grade_name'] = '';
              $data['subject_name'] = '';
              $data['volume_name'] = '';
              $data['version_name'] = '';
              $data['sort_name'] = '';
              $data['ssort_id'] = 0;
              $data['addtime'] = date('Y-m-d H:i:s',time());
          if($new_book = AWorkbook1010::create($data)){
              $cover_cip = \File::files($book_dir);
              $cover_img_now = $cover_cip[0];
              $cip_img_now = $cover_cip[1];
              $answer_dictories = \File::directories($book_dir);
              foreach ($answer_dictories as $answer_dictory){
                  if($answer_dictory[-1]==='1'){

                  }else{
                      $this->insert_answer($answer_dictory, $new_book->id, $data['bookcode']);
                  }

                  //dd(app('pinyin')->abbr(\File::name($answer_dictory)));
              }

              $cover_img_path="pic19/".$new_book->id."/".md5_file($cover_img_now).'.'.\File::extension($cover_img_now);
              $cip_img_path="pic19/".$new_book->id."/".md5_file($cip_img_now).'.'.\File::extension($cip_img_now);
              AWorkbook1010::where(['id'=>$new_book->id])->update(['cover'=>config('workbook.thumb_image_url').$cover_img_path,'cip_photo'=>config('workbook.thumb_image_url').$cip_img_path]);
              \File::copy($cover_img_now, 'D:/wamp64/www/vzytest/storage/app/public/offical_answer_pic/'.$cover_img_path);
              \File::copy($cip_img_now, 'D:/wamp64/www/vzytest/storage/app/public/offical_answer_pic/'.$cip_img_path);
              //$this->insert_answer();
              dd($new_book->id);
          }

              #dd(\File::directories($book_dir));

          }

      }

      //$data['bookcode']=md5($str);





      dd();



      
      
      
      dd('111');




      //更新需要购买
//      $new = AWorkbookNew::where('version_year','2018')->SELECT('grade_id','subject_id','volumes_id','version_id','sort')->get();
//      foreach ($new as $book){
//          $condition['grade_id'] = $book->grade_id;
//          $condition['subject_id'] = $book->subject_id;
//          $condition['volumes_id'] = $book->volumes_id;
//          $condition['version_id'] = $book->version_id;
//          $condition['sort'] = $book->sort;
//          AWorkbook1010::where($condition)->update(['need_buy'=>0]);
//      }


//      $had = AWorkbook1010::where('version_year',2018)->SELECT('grade_id','subject_id','volumes_id','version_id','sort')->groupBy('grade_id','subject_id','volumes_id','version_id','sort')->get();
//      foreach ($had as $book){
//          $condition['grade_id'] = $book->grade_id;
//          $condition['subject_id'] = $book->subject_id;
//          $condition['volumes_id'] = $book->volumes_id;
//          $condition['version_id'] = $book->version_id;
//          $condition['sort'] = $book->sort;
//          AWorkbook1010::where($condition)->update(['need_buy'=>0]);
//      }

      AWorkbook1010::where('need_buy',0)->select('id')->chunk(1000,function ($books){
          foreach ($books as $book){
              BaiduNewDaan::where('book_id',$book->id)->update(['need_buy'=>0]);

          }
      });


      dd('qwe');




      $cache_all_books = \Cache::remember('all_books_now', 120, function (){
          return AWorkbook1010::select('grade_id','subject_id','volumes_id','version_id','sort')->groupBy('grade_id','subject_id','volumes_id','version_id','sort')->get();
      });

      foreach ($cache_all_books as $book){
          $condition['grade_id'] = $book->grade_id;
          $condition['subject_id'] = $book->subject_id;
          $condition['volumes_id'] = $book->volumes_id;
          $condition['version_id'] = $book->version_id;
          $condition['sort'] = $book->sort;
          if(AWorkbook1010::where('version_year',2018)->where($condition)->count()>0){
              AWorkbook1010::where($condition)->update(['need_buy'=>0]);
          }
          if(AWorkbookNew::where('version_year','2018')->where($condition)->count()>0){
              AWorkbook1010::where($condition)->update(['need_buy'=>0]);
          }
      }


      dd('qweqwe');





//      $all = AWorkbook1010::select('sort',DB::raw('sum(collect_count) as num'))->groupBy('sort')->orderBy('num','desc')->with('has_sort:id,name')->chunk(100,function ($books){
//          foreach ($books as $book){
//              if($book->sort>=0){
//                  $data['sort'] = $book->sort;
//                  $data['sort_name'] = $book->has_sort?$book->has_sort->name:'123';
//                  $data['jj_collect'] = $book->num;
//                  DataSortCollect::create($data);
//              }
//          }
//      });


      $a = Book::select('sort',DB::raw('sum(concern_num) as num'))->groupBy('sort')->orderBy('num','desc')->chunk(100,function ($books){
          foreach ($books as $book){
              DataSortCollect::where(['sort'=>$book->sort])->update(['hd_collect'=>$book->num]);
          }
      });
      dd('qwe');

//      $all_sort = DataSortCollect::select('sort','hd_collect')->orderBy('jj_collect','desc')->get();
//      foreach ($all_sort as $sort){
//          $sort_num = $sort->hd_collect;
//          $books = AWorkbook1010::where([['sort',$sort],['hdid','>',0]])->select('sort','hdid')->with('has_hd_book:id,concern_num')->get();
//              foreach ($books as $book){
//                  if($book->has_hd_book){
//                      $sort_num += $book->has_hd_book->concern_num;
//                  }
//              }
//          DataSortCollect::where(['sort'=>$sort])->update(['hd_collect'=>$sort_num]);
//      }
      dd('qwe');


//      $a = DataSortCollect::select('sort')->orderBy('jj_collect','desc')->take(1)->get();
//
//      foreach($a as $workbook_1010s){
//          $concern_num = 0;
//          foreach ($workbook_1010s->has_workbook_1010 as $workbook){
////              var_dump($workbook_1010s->sort);
////              dd($workbook->has_hd_book->concern_num);
//              if($workbook->has_hd_book){
//                  $concern_num = $concern_num +$workbook->has_hd_book->concern_num;
//              }
//          }
//          DataSortCollect::where(['sort'=>$workbook_1010s])->update(['hd_collect'=>$concern_num]);
//      }
      dd($a[0]->has_workbook_1010);


      $all_sort = DataSortCollect::where('jj_collect','>','1000')->select('sort')->with('has_workbook_1010:sort,hdid')->with('has_workbook_1010.has_hd_book:id,concern_num')->get();
      foreach ($all_sort as $sort){
          dd($sort);

      }



      dd($all);






     // system('net use \\192.168.0.117\book');

      $a = AWorkbookNew::where([['id','>',1000000]])->select(['id','bookname','sort','update_uid'])->get();
      $b = [];
      foreach ($a as $book){
          $book_dir = User::find($book->update_uid)->name.'/'.$book->sort.'_'.cache('all_sort_now')->find($book->sort)->name.'/'.$book->bookname;
          if(!is_dir('//QINGXIA23/book/'.$book_dir)){
              mkdir('//QINGXIA23/book/'.$book_dir,0777,true);
          }

          //AWorkbookNew::where([['id','<',1000000]])->where($data)->update(['has_update'=>1]);
//          if($c->count()>0){
//              $b[] = $c;
//          }
      }
        dd($b);


      dd('qweqwe');


      $a = AWorkbookNew::where([['id','>',1000000]])->select(['id','grade_id','subject_id','volumes_id','version_id','version_year','sort','isbn'])->get();
      foreach ($a as $book){
          $sort_name = Sort::find($book->sort)->name;
          AWorkbookNew::where(['id'=>$book->id])->update(['has_update'=>1]);
          $book_detail_name = '2018年_'.config('workbook.grade')[$book->grade_id].'_'.config('workbook.subject_1010')[$book->subject_id].'_'.config('workbook.volumes')[$book->volumes_id].'_'.cache('all_version_now')->find($book->version_id)->name.'_'.$book->isbn;
          if(!is_dir(storage_path('app/public/offical_answer_pic/'.$sort_name.'/'.$book_detail_name))){
              mkdir(storage_path('app/public/offical_answer_pic/'.$sort_name.'/'.$book_detail_name),0777,true);
          }
      }
        dd('qweqwe');

//      $a = AWorkbookNew::where([['has_update',1],['id','<',1000000]])->select(['id','grade_id','subject_id','volumes_id','version_id','version_year','sort'])->get();
//      $new_book = [];
//      foreach ($a as $book){
//          $data['grade_id'] = $book->grade_id;
//          $data['subject_id'] = $book->subject_id;
//          $data['volumes_id'] = $book->volumes_id;
//          $data['version_id'] = $book->version_id;
//          $data['sort'] = $book->sort;
//
//          $new_book = AWorkbookNew::where($data)->where([['id','>',1000000]])->update(['update_from'=>$book->id,'has_update'=>0]);
//
//      }





      $a = db::connection('mysql_local')->table('a_sort_uid')->select('id')->get();
      foreach ($a as $key => $value){
          if($key%4==0){
              $data['uid'] = 5;
          }else if($key%4==1){
              $data['uid'] = 8;
          }else if($key%4==2){
              $data['uid'] = 11;
          }else if($key%4==3){
              $data['uid'] = 14;
          }
          db::connection('mysql_local')->table('a_sort_uid')->where('id',$value->id)->update($data);
      }

      dd('111');


        ATongjiBuy::where([['jj',1],['book_id','>',0]])->select('id','book_id')->with('has_jj_book:id,version_id')->chunk(1000,function ($books){
          foreach ($books as $book){
              //ATongjiBuy::where(['id'=>$book->id])
              $data['version_id'] = $book->has_jj_book->version_id;
              ATongjiBuy::where(['id'=>$book->id])->update($data);
              //dd($book->has_hd_book);
          }
      });
      dd('111');

//      ATongjiBuy::where([['jj',0],['book_id','>',0],['version_year',0]])->select('id','book_id')->with('has_hd_book:id,subject_id,grade_id,volumes,version')->chunk(1000,function ($books){
//          foreach ($books as $book){
//              //ATongjiBuy::where(['id'=>$book->id])
//              $data['subject_id'] = $book->has_hd_book->subject_id;
//              $data['grade_id'] = $book->has_hd_book->grade_id;
//              $data['volume_id'] = $book->has_hd_book->volumes;
//              $data['version_year'] = intval($book->has_hd_book->version)>0?intval($book->has_hd_book->version):0;
//              ATongjiBuy::where(['id'=>$book->id])->update($data);
//            //dd($book->has_hd_book);
//          }
//      });


//      ATongjiBuy::where([['jj',1],['book_id','>',0]])->select('id','book_id')->with('has_jj_book:id,subject_id,grade_id,volumes_id,version_year')->chunk(1000,function ($books){
//          foreach ($books as $book){
//              //ATongjiBuy::where(['id'=>$book->id])
//              $data['subject_id'] = $book->has_hd_book->subject_id;
//              $data['grade_id'] = $book->has_hd_book->grade_id;
//              $data['volume_id'] = $book->has_hd_book->volumes_id;
//              $data['version_year'] = $book->has_hd_book->version_year;
//              ATongjiBuy::where(['id'=>$book->id])->update($data);
//              //dd($book->has_hd_book);
//          }
//      });



      //家教单本收藏超过100入库
//      AWorkbook1010::where('collect_count','<=',500)->select('id','sort','bookname','collect_count')->chunk(1000,function ($books){
//          foreach ($books as $book){
//              $data['jj'] = 1;
//              $data['book_id'] = $book->id;
//              $data['sort'] = $book->sort;
//              $data['sort_name'] = $book->bookname;
//              $data['collect_count'] = $book->collect_count;
//              ATongjiBuy::create($data);
//          }
//      });

      //互动作业单本收藏超过100入库
      $a = Book::where('collect_num','<=',500)->select('id','sort','name','collect_num')->chunk(1000,function ($books){
          foreach ($books as $book){
              $data['jj'] = 0;
              $data['book_id'] = $book->id;
              $data['sort'] = $book->sort;
              $data['sort_name'] = $book->name;
              $data['collect_count'] = $book->collect_num;
              ATongjiBuy::create($data);
          }
      });
      dd($a);
//      $a = ATongjiBuy::where(['jj'=>0])->select('sort')->with('has_sort:id,name')->get();
//      foreach ($a as $value){
//          if($value->has_sort){
//              ATongjiBuy::where(['jj'=>0,'sort'=>$value->sort])->update(['sort_name'=>$value->has_sort->name]);
//          }
//      }



      #ignore_user_abort();
      set_time_limit(0);
//      $a = Book::where([['bar_code','<>',null]])->select('bar_code')->groupBy('bar_code')->orderBy('bar_code','desc')->chunk(1000,function ($books){
//          foreach ($books as $book){
//              if(strlen($book->bar_code)==13 && starts_with($book->bar_code, '9787')){
//                  $data['collect_num'] = Book::where('bar_code',$book->bar_code)->sum('collect_num');
//                  $data['isbn'] = $book->bar_code;
//                  DB::connection('mysql_local')->table('tongji_isbn')->insert($data);
//              }
//          }
//      });
      dd('qweqwe');
//      Book::chunk(1000, function ($books) {
//          foreach ($books as $book) {
//              if(starts_with($book->isbn, '978'))
//          }
//      });


//      $jobs = DB::connection('mysql_main')->select('select l.uid,l.city,m.realname,l.status,l.got_at,l.pushed_at,m.mobile,e.gradeid from app_course_coupon_log l,pre_common_member_profile m,pre_plugin_eduinfo e where l.uid=m.uid and l.uid=e.uid and coupon_id = 2 and e.gradeid>=7 and e.gradeid<=9 and pushed_at is null and  city in ("北京", "上海", "广州", "天津", "深圳") order by got_at desc limit 20');
      $jobs = AWorkbookRds::select('id as header','bookname as body','cover as footer')->paginate(20);
      $data['id'] = 'new_modal';
      $data['title'] = 'qweqwe';
      $data['body'] = 'asdasd';
      $data['footer'] = 'sdfsdfsdfsdf';
      return view('test.new',compact('jobs'));
      dd($a);


      $header = [
          'User-Agent'=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0',
          'Accept'=>'application/json, text/javascript, */*; q=0.01',
          'Accept-Language'=>'zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
          'Accept-Encoding'=>'gzip, deflate',
          'Referer'=>'http://cn.mikecrm.com/3nw6kjw',
          'Content-Type'=>'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With'=>'XMLHttpRequest',
          'cookies' => true
      ];
      $cookie = new \GuzzleHttp\Cookie\CookieJar();
      $http = new \GuzzleHttp\Client($header);

      /* //乂学  http://cn.mikecrm.com/3nw6kjw
      $data['i'] = 200145763;
      $data['t'] = '3nw6kjw';
      $data['s'] = 200416173;
      $data['acc'] = 'a3M8DwOKrRyO55MFiwj8mbdAPGkZjxHt';
      $data['r'] = 'http://www.1010jiajiao.com';
      //姓名
      $data['c']['cp']['201510803']['n'] = $info['realname'];
      //电话
      $data['c']['cp']['201510804'][] = $info['mobile'];
      //年级 201160917,201160918,201160919
      $now_grade = 201160917;
      if($info['grade']=='七年级') $now_grade = 201160917;
      if($info['grade']=='八年级') $now_grade = 201160918;
      if($info['grade']=='九年级') $now_grade = 201160919;
//      if($info['grade']=='预初' || $info['grade']=='初一') $now_grade = 201160917;
//      if($info['grade']=='初二') $now_grade = 201160918;
//      if($info['grade']=='初三' || $info['grade']=='初四') $now_grade = 201160919;
      //城市 201171845,201171846,201171847,201171848,201171849
      $now_city = 201171845;
      if($info['city']=='北京') $now_city = 201171845;
      if($info['city']=='上海') $now_city = 201171846;
      if($info['city']=='广州') $now_city = 201171847;
      if($info['city']=='深圳') $now_city = 201171848;
      if($info['city']=='天津') $now_city = 201171849;
      $data['c']['cp']['201510805'] = $now_grade;
      $data['c']['cp']['201525158'] = $now_city;
      $data['c']['ext']['uvd'] = [201510803,201510804];
      $all['cvs'] = $data; */

      //  http://cn.mikecrm.com/D4mPPdO   测试用  wkqqpszh@sharklasers.com  qweqwe

      foreach(collect($a) as $info){
          $info = collect($info);

          $data['i'] = 200049157;
          $data['t'] = 'D4mPPdO';
          $data['s'] = 200176746;
          $data['acc'] = 'iaa0gL2ANWGcjXDWoxDVp0ju36VyuQKM';
          $data['r'] = 'http://www.1010jiajiao.com';
          //姓名
          $data['c']['cp']['200559420'] = $info['realname'];
          //电话
          $data['c']['cp']['200559421'] = $info['mobile'];
          //年级 201160917,201160918,201160919
          $now_grade = 200543008;
          if($info['gradeid']==7) $now_grade = 200543008;
          if($info['gradeid']==8) $now_grade = 200543009;
          if($info['gradeid']==9) $now_grade = 200543010;
          //城市 201171845,201171846,201171847,201171848,201171849
          $now_city = 200543011;
          if($info['city']=='北京') $now_city = 200543011;//13900090001
          if($info['city']=='上海') $now_city = 200543014;//13900130001
          if($info['city']=='广州') $now_city = 200543013;//18820012121
          if($info['city']=='深圳') $now_city = 200543015;//13510933355
          if($info['city']=='天津') $now_city = 200543012;//13512973260
          $data['c']['cp']['200559422'] = $now_grade;
          $data['c']['cp']['200559423'] = $now_city;
          $all['cvs'] = $data;

          $resp = $http->post('http://cn.mikecrm.com/handler/web/form_runtime/handleSubmit.php', [
              'form_params'=>['d'=>json_encode($all,JSON_UNESCAPED_UNICODE)],
              'cookies'=>$cookie,
          ]);
          dd($resp->getStatusCode());
      }






      dd(auth_url('/book_photo_path/2016-08-24/65768dc6-d3d1-4cc6-847f-a23e68f0b61d.jpg'));

      $oss = new OssController();
      $files = Storage::allFiles("all_book_pages/21/cut_pages/");
      dd($files);
      foreach ($files as $file){
          $utf8_file = iconv('gbk','utf-8',$file);
          dd($utf8_file);
          $oss->save($utf8_file,file_get_contents(storage_path("app/public/{$file}")));
      }




//    $phone_info = new PhoneLocation();
//    $arr = '15834873015,18193501303,15573990311,13101186784,13452363987,18809054833,13941126355,18248222031,15523194379,17674511533,13917959358,18169567389,15031318230,18875414207,18026228343,18193932402,15040896414,13694667495,13982986335,15207473896,15718891356,13792000629,13463561717,18963383453,13921765933,15292079979,15763353682,15992644764,15165261027,13885594022,13853746017,15295715934,18036539059,18395238211,15006202419,18253862939,13487301176,13648130057,17762074849,15879259027,13596837329,15256496353,15128808685,13544235196,13204861508,18706379469,15688235979,15256287246,15059319824,18589718714,13658837392,15068528027,15942470360,13698692403,13095499537,17705683036,15270967871,18230145245,13078531402,18523691891,13110646670,13706817061,13863358943,15193391456,18084051808,13574998472,18139511522,15265837840,18452692171,15023774949,13921755252,18954602935,18235830457,18068684761,15020606097,18323589925,15839038278,18663692706,15161114580,15961452138,15982878636,17605504385,13999667317,13649927118,15870956248,15738597985,15246186936,18203037694,18786373674,13320384296,15948942060,13982399943,15023353536,13171057833,17645490522,15736213935,18692044838,13082201260,18290650467,15568706820,13944049132,18398760556,15254957249,15209377623,15894593026,17729621285,13996124288,13993901254,13030198357,15252393656,15164755907,15612367846,13329614279,15141412155,15752287103,13992473693,13188377301,18704502856,15166763066,13553261070,14790767582,15182037052,15120382577,17674608517,18085755985,18904076727,13638929421,18719594313,17068411117,18343285066,13299573525,15193072955,15146827882,15580701757,18854791201,18138586449,17790330507,15117524676,13649336786,15241003213,13259550650,18885238907,15188130376,15911400304,13627962942,13866405926,18262363920,13704970097,15143092267,15202314928,18256185350,15155869208,15237364448,18975201920,15351660412,13814730934,13560023498,13954160610,15114220719,18855407644,13964393484,15345224753,18325662839,15174809572,13013691559,15904464690,18817148758,18534448335,18712006272,18815564980,13566770035,15096294219,13963095637,13606335471,15066402485,15754939759,13558835488,18935894250,15862194860,18223639061,13050664282,13979518827,13154520801,13019096870,18354986077,13754782239,13227189205,15655526003,18793975828,13105459977,15161769390,17095677508,15936418173,13805231483,18235499630,13852891551,13964482577,15629995969,15190323816,15304714383,13467209545,13040610393,13561259381,15105238173,18121924040,18539225867,15575735589,13625336917,15093116411,17770829872,18678996202,15504218859,13626392421,18311810783,15830686635,13320619487,15755696930,15296785522,13586209584,13601445809,15129335090,18724068932,13309036587,15101351554,18160738657,13585387831,13636039833,15369417469,15999067979,15105234408,15290572784,15126545441,15096784657,13559436968,15299701723,15135346408,15120986701,17882646727,15170005874,13206490972,15179388519,13870917024,17795594568,15811621134,15619967072,18374520708,18212368584,15648527327,15044651962,13578039307,18756597815,18291948419,15870550761,13188469232,13555799455,15201937153,15060339221,15848549661,18326398515,13114468770,15102459413,15998920581,15520137116,15974880862,18585301305,18375076738,15887501526,13570413768,13387176632,18939677365,15241561826,18944989027,18797440529,18040994017,15911875370,17336811036,13263827795,13870892364,13799054981,15940207990,15097228492,15393647527,15121942348,13305988756,13324011172,13845911334,17758580907,18785569161,18087573096,18152272395,18666253365,13679367980,15111778783,13119379291,13550963929,18503056191,18515638561,18919118195,18734068058,18387790656,17612272693,13189860619,15826041506,18325903580,18783484986,15883595003,13760054523,13846167152,15963205205,18009567731,13924671420,13726835955,17839557057,17308376718,15570528667,15065220260,15055946393,18409393162,13865266370,18395303208,15774739888,13224782080,18246572470,15099230863,15070153839,18305075050,15564355562,13425437909,18167551076,15542372838,18710778180,15863522417,18251741259,18439451819,18040468989,13849381716,18794551205,13696840743,17768273385,15707445717,18745200525,15944120542,15565220135,15052062891,13273524526,18166265833,15952800053,15844968820,15153365902,18086069019,15954941995,15020228387,13598243463,13963558901,15014597929,18464880082,18452580347,15339888816,18395827051,13687097898,13012089167,15062252128,13803879023,15088963635,13637365939,15763382851,13676359938,13558915763,17763363579,18888492367,15289475978,17743809043,15954921557,15237747178,13813498982,13352210117,13153028447,17869129163,13412772805,13314448479,18275948352,15245837371,15898951813,18622667720,13706684831,15039729054,18725431137,13655820087,18841498821,13666836380,15624081736,13514650225,15103248383,13831506486,18603730262,13512577157,15025485022,13770384915,13907075941,15146300938,15264846139,13667030853,15988104051,15564299979,15088988982,18351463058,13064851072,13615507975,15961972520,13031107084,15890412540,13376367748,18606160230,15997147118,15094256143,15659670158,15069365900,18214153150,15603671865,18715469148,18386164186,15349540239,15235395362,18315989001,15335436146,15854763700,18387954194,15556956053,13899686651,18136765609,15979389160,15706713553,13063424351,15261993671,15560782438,18211322743,13516090825,15002701513,13630322478,13704624327,15855595931,15945675521,13399191339,15007874840,18869596151,18212252474,13647150432,15117450129,13936874605,17167348836,15941535445,15814527700,18387064398,18712586694,15717015307,15604591633,15628886657,13168133178,18741940726,13190356295,15845521130,18209649588,18875747670,18384766296,13001330965,18055081790,13141716982,13945499252,18162925802,18746430135,15171942555,13064199870,18780019211,18390782292,15144903076,13624559418,18226314637,15005708601,18347342936,17674613421,18074629699,13891137412,18309770235,15971690616,15184751338,18872565312,18716134423,13676779525,18266231553,18219740133,17742785556,14784755450,15946223309,15752484158,13899772139,15827612533,17720410932,15533064689,18805222811,13436611409,13257130493,15554729723,13970255015,18707273052,13589466035,18099091393,18814708608,18865618003,15932798546,17885286893,13170152399,18947355161,18012297679,13323537973,18219376525,15997912715,13469512144,15800484535,18768326226,15772779256,13227506857,18099444574,13966713548,18375652466,15115093525,18783225441,18221206841,18673743972,15840624669,13945732891,15115893645,17796891028,15885242986,15018960324,13866235155,15294081069,15844469423,15708674858,15927886571,18692914279,15076350321,13549553980,13451331351,13098618138,13815272951,13671269963,13595926504,18179328629,13581750948,13840056502,18607582880,13050940186,15295413534,15352492324,13217143380,18216117654,18832779973,15271581966,15971961910,15065438618,13373348824,13082173992,15971066401,13882572984,18352987317,18962037718,17839004724,13865090935,13591905316,15953907864,15073703981,18673964458,15840268089,17312362990,15759207228,18766228263,17795405369,15385626273,13875596005,13505666285,13517305254,13562985065,13082065545,18212082072,13507087917,18763153198,15674669478,13111767961,13942634505,18407761146,13551407165,15058672177,18292894509,18794493834,13956609552,18292655822,13295365396,18767525502,13830751916,15145648851,13830537586,13787087793,15942409623,13643643503,15660269058,18023149926,15162835103,13619006537,18970923688,13812377939,15812290502,18754470219,13894655086,15161935105,15944801095,18099068653,15953087719,15137374408,15392595064,15833542841,13772847456,18970025162,15368517130,15163039761,15270964621,14727954956,18379325715,15053014529,13537018072,13954295051,18729584033,15015657269,18669349173,13504770534,15637132666,18392865208,15695059466,15884703296,18383404600,15169935789,15080221940,13665119859,15994199899,15066278748,18371233861,15853443420,13989655457,13793266487,13013553973,18709670751,13031780611,18959973975,13814200805,17376464581,13382432561,13897533419,15777851056,15842622043,13624486715,18486581612,13891847778,13952375038,15143460119,15191657407,18402960659,18161642921,15297169829,13569215242,18732993211,15656820398,13504932858,13773989243,15774398716,18136813251,15354779511,15025224459,17380828380,18770161670,13947024046,18649204790,13756044243,18774753330,15019038360,18753062093,13971523189,13214707727,15043864590,13697234565,18284080487,18374471408,13598266935,15766561756,17872555097,13461166480,15886173478,17745835268,15929073633,13974622344,15597459878,15198513267,18189600390,13272963370,18748973404,13042295433,15554063489,15310376924,18955842237,13931445584,18203045210,15590095366,15295106849,18294149932,17799210107,17699763606,15114425652,18456071297,13979274321,15858206328,15812246739,15924814064,13315012265,18246736357,15974693301,13320229856,15912796402,18704031669,17074076132,18707430724,18393396639,13689132628,15807924948,18223948003,18293432384,13323694366,15961963397,14717204568,13378204861,13208500137,17313583940,15505010088,15565775783,13409186976,18523225285,13667695381,13595630065,15841288702,18774403083,15274580556,17338621045,13082815300,18133253812,18171686459,15873565851,18655008185,17376395596,15868395932,13967560234,18795039431,15883960605,17307600019,15030978682,15175941969,15736691638,18894113576,15520871706,17373586218,15349589208,15774114238,17671404690,13275685991,17396199201,15375138921,13264794468,13789713920,18315964949,15614744689,15911876655,15616859726,14785754205,13972821492,18277360013,18082851862,15939267787,15556657320,18683182739,13525162550,15903362946,18994272622,13615159577,13589745548,18837077709,13551987304,15528588191,18275952984,18662988848,13487640366,13137861213,18258933423,15895154660,15091288261,15145575422,13864407563,13325353703,15364890875,14730316149,15874914458,13754704066,18951549182,18800567459,13655148223';
//    $arr_now = explode(',', $arr);
//    $num = 0;
//
//    $phone = [];
//    foreach ($arr_now as $value){
//      $info = $phone_info->find($value);
//      if(isset($info['city'])){
//        if(in_array($info['city'], ["北京", "上海", "广州", "天津", "深圳"])){
//          $num += 1;
//          $phone[] = $value;
//        }
//      }
//    }
//    var_dump(implode(',', $phone));
//    var_dump($num);die;
//
//
//
//
//
//
//
//
//    $a = '<p>we&nbsp;<span class="answer_now">qweqwe&nbsp;adq&nbsp;<span class="answer_now">wes["df}s"f</span>&nbsp;fdfgdfgdf</p><p>asdasdrgrg fsdgd&nbsp;&nbsp;weqwe&nbsp; qwe</span></p>';
//
//
////    $d = str_replace('http://thumb.1010pic.com/', '', $a);
////    dd($d);
////    $d = preg_replace('/\http:\/\/thumb\.1010pic\.com\//', '', $a);
////
////    dd($d);
//
//    $b = preg_match_all('/<span class=\"answer_now\">(.*?)\<\/span\>/', $a,$c);
//
//    dd($c);
//    dd(collect($c[1])->toJson());
//
//    dd('qweqweqwe');
//    $a = DB::connection('mysql_main_jiajiao')->table('mo_no_answer')->where('status',1)->select()->get();
//    var_dump($a);die;
//    dd($a);

    //url like '%chaoxing%' and answer='答案：<br>'
    //'czsx'=>'初中数学',
    // $all_type= ['xxsx'=>'小学数学','xxyw'=>'小学语文','xxyy'=>'小学英语','czsx'=>'初中数学','czyw'=>'初中语文'];
    // $where[] = ['url','like','%chaoxing%'];
    // $where[] = ['answer','=','答案：<br>'];



    // print '科目-无答案数-有统计信息数';
    // echo '<br/>';
    // foreach ($all_type as $key=>$value){
    // $data['type'] = $key;
    // $table = 'mo_'.$key;
    // $baidu_table = 'baidu_new_'.$key;

    // print($key.'-'.DB::connection('mysql_main_jiajiao')->table('mo_no_answer')->where('type',$key)->count().'-'.DB::connection('mysql_local')->table($baidu_table)->where('no_answer',1)->count());
    // echo '<br/>';



    // DB::connection('mysql_main_jiajiao')->table('mo_no_answer')->where(['type'=>$key])->select('md5id')->orderBy('id','asc')->chunk(1000, function ($timu) use($data,$baidu_table) {
    // $array = [];
    // foreach ($timu as $timu_detail){
    // $array[] = $timu_detail->md5id;
    // }


    // DB::connection('mysql_local')->table($baidu_table)->whereIn('shiti_id',$array)->update(['no_answer'=>1]);
    // });
    // DB::connection('mysql_main_jiajiao')->table($table)->where($where)->select('md5id')->orderBy('id','asc')->chunk(1000, function ($timu) use($data) {
    // $array = [];
    // foreach ($timu as $timu_detail) {
    // $data['md5id'] = $timu_detail->md5id;
    // $array[] = $data;
    // }
    // DB::connection('mysql_main_jiajiao')->table('mo_no_answer')->insert($array);
    // });
  //}
dd('qweqwe');





mkdir(storage_path('baidu/2018-01-11'));
chmod(storage_path('baidu/2018-01-11'),0766);
dd('qweqwe');
  // $info = BaiduNew::where('book_id','>',0)->select('id','url')->get();
  // foreach ($info as $data){
  // $chapter_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/daan\/chapter\_(\d+)\.html(.*?)/', $data->url, $match_chapters);
  // if($chapter_url){
  // $chapter_id = $match_chapters[1];
  // BaiduNew::where('id',$data->id)->update(['chapter_id'=>$chapter_id]);
  // }
  // }

$info = BaiduNew::where([['portal_id',0],['url','like','%qx_portal/doc/%']])->select('id','url')->get();
//http://www.1010jiajiao.com/qx_portal/doc/875874.html
foreach ($info as $value){
$portal_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/qx_portal\/doc\/(\d+)(.*?)/', $value->url,$match_portal);
if($portal_url){
$portal_id = $match_portal[1];
BaiduNew::where('id',$value->id)->update(['portal_id'=>$portal_id]);
}
}
dd('qweqwe');




$info = BaiduNew::where([['book_id','=',0],['shiti_id','=','']])->select('id','url')->get();
foreach ($info as $data){
  $timu_id_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/timu_id\_(\d+)(.*?)/', $data->url, $match_timu);
  $timu3_id_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/timu3_id\_(\d+)(.*?)/', $data->url, $match_timu3);
  $xiti_id_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/xiti_id\_(\d+)(.*?)/', $data->url, $match_xiti);
  if($match_timu){
    $chapter_id = $match_timu[1];
    BaiduNew::where('id',$data->id)->update(['timu_id'=>$chapter_id]);
  }
  if($match_timu3){
    $chapter_id = $match_timu3[1];
    BaiduNew::where('id',$data->id)->update(['timu3_id'=>$chapter_id]);
  }
  if($match_xiti){
    $chapter_id = $match_xiti[1];
    BaiduNew::where('id',$data->id)->update(['xiti_id'=>$chapter_id]);
  }
}

dd('qweqwe');

// foreach($all as $key=>$value){
// var_dump($key);
// Baidu::where([['shiti_id','=',$key],['shiti_type','!=',$value]])->update(['shiti_type'=>$value]);
// }
// dd('qweqwe');

// $a = Baidu::where('shiti_done',1)->select('shiti_id','shiti_type')->get();
// foreach($a as $b){
// Baidu::where(['shiti_id'=>$b->shiti_id,'shiti_type'=>$b->shiti_type])->update(['shiti_done'=>1]);
// }
// dd('qweqwe');



$data['all_type']= ['xxyw'=>'小学语文','xxsx'=>'小学数学','xxyy'=>'小学英语','czyw'=>'初中语文','czsx'=>'初中数学','czyy'=>'初中英语','czwl'=>'初中物理','czhx'=>'初中化学','czsw'=>'初中生物','czdl'=>'初中地理','czls'=>'初中历史','czzz'=>'初中政治','gzyw'=>'高中语文','gzsx'=>'高中数学','gzyy'=>'高中英语','gzwl'=>'高中物理','gzhx'=>'高中化学','gzsw'=>'高中生物','gzdl'=>'高中地理','gzls'=>'高中历史','gzzz'=>'高中政治'];
set_time_limit(0);
ini_set('memory_limit', -1);
$all_shiti = Baidu::where(['has_shiti'=>1,'shiti_done'=>0])->select('id','shiti_id','shiti_type');
foreach ($all_shiti->cursor() as $shiti){
  if(in_array($shiti->shiti_type, array_keys($data['all_type']))){

    $table  ='mo_'.$shiti->shiti_type;

    $now = DB::connection('mysql_main')->table($table)->where('md5id',$shiti->shiti_id)->first();

    $now_data = collect($now)->toArray();

    if($now_data['date_added']==='0000-00-00 00:00:00'){
      $now_data['date_added'] = date('Y-m-d H:i:s',1);
    }
    if(DB::connection('mysql_local')->table($table)->where('md5id',$now_data['md5id'])->count()==0){
      DB::connection('mysql_local')->table($table)->insert($now_data);
    }
    Baidu::where(['shiti_id'=>$shiti->shiti_id,'shiti_type'=>$shiti->shiti_type])->update(['shiti_done'=>1]);

  }


}



}

    public function getMainWordByTitle($title){
        $title=str_replace('+','加',$title);
        $title=strtr($title,['語'=>'语','會'=>'会','學'=>'学','話'=>'话','韓'=>'韩','書'=>'书']);
        $title=preg_replace('#\s+#','',$title);
        $this->main_words=[];
        $preg_exlude='医|焊|刑|饰|投|党|孕|胎|厨|烟|岗|穴|井|病|钓|忙|妆|轧|鱼|喵|爆|虫|饵|竿|眼|弗|甲|宣|棉|药|污|癌|账|粮|米|饼|泵|房|酒|犬|税|煤|韩|耦|钉|日本|生存|如何|词典|渔具|会话|邮票|心灵|餐劵|治愈|修养|美容|相册|绘画|电网|象棋|论证|艺术|名典|咖啡|水票|古代史|公路|激光|护士|水手|销售|翡翠|免罚|看图|制作|杂志|食品|香港|遗传|礼物|实录|游戏|电脑|渗透|可靠|随身|绘本|玉器|线路|输电|一本书帮你|改造|工程|养生|视频|记录|简笔画|奥赛指导|课外书|伦理学|代金券|学习机|机械系统|冗余|仿真分析|应急|地震|橡胶|配方|服务|拍卖|果树|速写|英国|营销|流行|从业|证券|高薪|运营|推广|手工|清洁|扫地|神话|空调|诉讼|水电|护理|菜|宝宝|术语|社区|花卉|素描|阴谋|流程|会计|硕士|植物|舞蹈|自考|职称|公务员|工程师|电气|音乐|人力|自学|生产|电源|建筑|地球|制造|涂色|工艺|模具|选股|产业|亲子|教子|施工|财报|财务|表扬学|机械优化|机械结构|结构优化|自动|固体|桩基|车辆|房地产|电力|风力|航天|成人|印刷|验收|法学|饲料|发电机|飞机|安装|维护|军用|改革|移动|字帖|写字课课|书写|口语|漫画|财经|旅行|美国|新加坡|第一本|计算机|等级|安全|刑法|建设|水平|日语|语法|管理|茶|维修|证书|日记|风水|幼儿|算命|考研|养殖|时尚|旅游|小说|生理|职场|礼仪|外贸|心理|健身|瑜伽|信息学|装修|养花|面试|谜语|越南|纸艺|签名|保健|学前|书法|事业|经管|单位|雅思|金融|行政|高档|影响|职业|体育|招聘|经济学|圣经|自主招生|字母书|长笛|银行|数码|办公|商业|电动|个性|母语|童书|行走|托业|摄影|工作|服装|影视|手链|野史|股权|编织|大提琴|投资|互联网|超能力|智慧背囊|产出|城市|三轴|统计学|逆变器|电路|程序|化工|通晓|军事|获奖|读者|数据|成考|检索|变压器|装置|元动力|缺陷|环境试验|特性|中华传统|齿轮|阵列|混泥土|零件|日报|公交|涡轮|信号|手绘|石油|采油|光纤|模型|钢筋|液压|神经|船舶|电流|电压|开采|绿道|托福|注塑|飞行|发动机|魔法书|结构动力|热敏|单词|学生制服|汽车|江湖|电磁笔|语言类|四大名著|分类作文|满分作文|一本好书|大一本科|带一本书|作文一本全|全国一本|魅力课堂';//排除包含本词的结果
        if(preg_match('#'.$preg_exlude.'#',$title)) return [];

        $preg0='通城学典初中英语完形填空与阅读理解140篇全国通用版|5年中考江苏13大市中考真题历年回顾精选28套卷|口算应用题分级训练,口算题卡加应用题专项|名校名师培优口算解决问题同步练加易错练|瀚海图书初中英语词汇专项训练内蒙古专用|名题文化初升高衔接教材走好高中第一步|初中文言文阅读训练及古诗词赏析训练|状元之路二轮钻石卷高考热点专题专练|同步检测优化训练单元滚动检测示范卷|春雨教育考必胜全国中考试题分类精粹|金考卷特快专递-2015高考专题冲关训练|河北省王朝霞中考零距离真题详解19套|同步辅导与能力训练,同步训练与拓展|潍坊四中校本教材高考热点专题专练|快乐假期走进名校培优训练衔接教材|毕业总复习高分策略指导与实战训练|世纪金牌口算题卡与应用题专项训练|总复习及毕业学习水平检测全真试卷|国华作业本名校学案滚动迁移学习法|津桥教育小学数学暑假巩固衔接15讲|全能卷王,小学毕业升学全程总复习|初中新课标试题研究课堂满分训练|期末满分冲刺阶段练习与单元测试|天利38套2014高考试题汇编全解真题|名师导航小升初毕业总复习测试卷|金质教辅期末冲刺优选卷冲刺100分|同行课课100分过关作业标准大考卷|中考第三轮复习冲刺专用模拟试卷|通城学典初中课外文言文阅读100篇|考什么学加练课后作业本河北专版|课堂全解字词句篇与同步作文训练|金钥匙组合训练单元期末冲刺100分|云南省标准教辅初中暑假快乐提升|初中生必做的阅读理解与完形填空|全国中考试题精选汇编与分类详解|初中学业水平统一考试标准样卷集|尚文教育河北省首席中考真题精选|小学毕业升学模拟试卷及真卷精选|创新思维全优英语课课100分作业本|好帮手单元月考期中期末全程测控|初中学业水平考试说明与复习指导|高考模拟试题精粹天利38套经济版|150加50篇英语完形填空与阅读理解|小学毕业升学复习必做的18套试卷|黄冈状元成才路状元口算天天练|走向外国语学校小升初模拟试题|小学毕业升学总复习及模拟试卷|中考必备名师解密热点试题汇编|云南师大附小一线名师核心试卷|一路夺冠HappyHoliday快乐假期暑假|名师导航新课堂练习与同步测试|英语知识要点解读及同步课课练|云南重点小学一线名师提优作业|华章教育寒假总复习学习总动员|升学复习必做的专项卷和真题卷|壹学教育小学语文升级阅读训练|智能考试期末好卷各地试卷精选|金质教辅直击中考培优夺冠金卷|天天向上素质教育读本教材新解|初中新课程重点知识总复习指导|单元目标检测云南师大附小密卷|小学毕业升学完全试卷冲刺100分|著名重点中学招生分班真卷精选|培生新课堂同步训练与单元测评|课课小考卷试题研究课堂10分钟|名校学案课课小考卷课堂10分钟|天利38套五年高考真题汇编详解|必做题1000例考前冲刺必备中考|一天一页每日6分钟数学天天练|大提速新课堂同步训练与测评|绩优课堂单元达标创新测试卷|广州市小学英语同步学习辅导|京版教材配套资源英语新视野|水浒传媒初中升高中衔接教材|新路学业快乐假期暑假总复习|初中毕业生升学考试复习用书|中考真题加名校模拟详解详析|学考英语阅读理解与完形填空|暑假作业安徽少年儿童出版社|精讲精练重点难点字词句篇章|三点一测初高中知识衔接教材|全程测评卷单元月考期中期末|黄冈小状元同步字词句学与练|年度暑假暑假总复习赢在暑假|云南省名校名卷期末冲刺100分|伴你学习新课程单元过关练习|新课程初中学习能力自测丛书|赢在课堂全程优化达标测评卷|衔接教材暑假初升高对接必备|春如金卷小学英语课时导学案|伴你成长同步辅导与能力训练|名师指路全程备考经典一卷通|高效课堂初中语文阅读周周练|三维设计高考专题辅导与测试|快乐假期每一天全优暑假作业|小升初系统总复习指导与检测|四步导学高效学练方案大试卷|活力英语阅读理解与完形填空|小升初部分市县招生试题汇编|历史与社会思想品德学习攻略|初中毕业学业考试说明检测卷|新浪书业复习总动员衔接教材|完形填空与阅读理解周秘计划|小学毕业考试标准及复习指导|金典学案同步导学与同步测试|广东中考考前押题卷模拟测试|初中全程导学微专题跟踪检测|中学英语快速阅读与完形填空|阅读理解与完形填空专项训练|直线英语阅读理解与完形填空|同步训练高中阶梯训练示范卷|系统集成新课程同步基础训练|同步测控优化设计单元检测卷|中招试题详解暨中招复习指导|亮点给力周末优化设计大试卷|见证奇迹英才学业设计与反馈|取胜通关中考模拟卷能力提升|创新成功学习快乐寒假作业本|英语阅读理解与完形填空150篇|单元双测专题期中期末大试卷|阳光英语阅读理解与完形填空|智慧课堂同步教材全练与测试|中考英语词汇记忆与检测宝典|悦然好学生必开卷吉林省专版|学海金卷初中夺冠单元检测卷|一卷通AB卷快乐学习夺冠100分|金博士1课3练单元达标测试题|课课练&单元练,LeoLiu中学英语|小学毕业升学必做的16套试卷|齐鑫传媒好成绩1加1学习导航|暑假成长乐园,寒假成长乐园|中考加速金卷仿真预测8套卷|全能练考卷,单元全能练考卷|3年招生试卷及预测试题精选|金榜1号课时作业与单元评估|3年中考真题考点分类集训卷|小学毕业总复习题优讲练测|同步练习册全优达标测试卷|高中学业水平考试全景训练|随堂练习卷期末满分冲刺卷|开心暑假作业年度复习方案|中考全程复习仿真模拟试卷|初中学业水平考查全景训练|初中毕业学业考试模拟试卷|暑假优化集训，暑假训练营|全真模拟加名师押题冲刺卷|小学奥数举一反三达标测试|复习与测评单元综合测试卷|高中新课标同步攻略与测评|新课标小学教学资源试题库|好帮手同步训练与单元测评|赢在新起点初升高衔接教材|天源图书天源假期作业暑假|中考民族团结篇复习与检测|满分试卷单元期末过关检测|新课堂单元测试卷冲刺100分|金钥匙神算手计算能力评估|小学口算竖式计算与应用题|口算题卡二合一训练天天练|配套家庭练习册及单元测试|家校导学小学课时南粤练案|金版新学案大一轮复习讲义|复习计划100分期末寒假衔接|寒假作业欢乐共享快乐假期|100分闯关考前冲刺全真模拟|单元加期末自主复习与测试|一路领先暑假语文同步阅读|小学综合能力测评同步训练|轻松暑假快乐学习培优衔接|绿色互动空间阶梯调研测试|名师指导知识梳理卷一卷通|新课标高考总复习创新方案|中考英语必考词汇通关训练|名师面对面单元培优测评卷|最新广东小升初全真考试卷|三维设计新课标高考总复习|蓝博士创新同步测试组合卷|初中毕业学业考试实战真卷|新课程学习与测评单元双测|普通高中新课程问题导学案|单元期中期末专题冲刺100分|步步高学案导学与随堂笔记|快乐起跑线单元滚动活页测|智能考核卷新思维提优训练|中考必备全国中考试题精析|单元加期末复习先锋大考卷|阅读理解与完形填空加油站|单元双测同步达标活页试卷|志鸿成功之路塞上名校金考|智慧学习初中学科单元试卷|全国小学毕业升学试题精选|北京市各区模拟及真题精选|假期好作业暨期末复习寒假|教材基础知识梳理复习方案|钟书金牌上海作业直播课堂|高效课堂小升初毕业总复习|轻松夺冠单元期末冲刺100分|自能自测课时训练与示范卷|小学毕业升学考试试卷精选|金质教辅一卷搞定冲刺100分|学业水平测试模块强化训练|培优优选卷期末复习冲刺卷|各地期末汇编期末冲刺100分|初中毕业生学业考试指导书|考入重点校小升初星级题库|新中考复习指导与自主测评|中考一二轮总复习阶梯试卷|中考命题规律与必考压轴题|小学毕业升学全程模拟试卷|毕业生升学文化课考试说明|学业考试初中总复习风向标|金牌教辅中考真题分类汇编|周考月考期中期末冲刺100分|完型填空阅读理解选词填空|区域地理学考必备配套练习|名校学案配套质量检测试卷|新目标期末与检测考点集萃|学期总复习状元成才路寒假|沈阳十年中考试题分类精选|双考倍多分教材四练四测卷|强化训练快乐假期期末复习|试题精编全国各地中考真题|高中新课程导学与能力培养|通城学典小学英语听力能手|自主学习能力测评单元测试|年度总复习精要复习总动员|直击中考初中全能优化复习|小学同步达标单元双测AB卷';
        $preg1='学考A加卷同步复习与测试|步步高-课时导练一文一练|高效A计划小学升初中衔接|考点新突破课时过关8分钟|英语阅读小学生每日5分钟|浙江省各地期末试卷精选|新课程初中物理同步训练|优等生单元期末冲刺100分|现代文阅读技能训练100篇|暑假作业广东人民出版社|初中学业水平考试总复习|高中学业水平考试指导卷|学业水平标准与考试说明|创意课堂中考总复习指导|同程期末大收官暑假作业|同步训练全优达标测试卷|夺冠百分百初中精讲精练|奋斗者中考全程备考方略|新概念小学年级衔接教材|天府名校优课练成都专版|考易通初中全程复习导航|小升初考前专项强化训练|中考先锋滚动迁移复习法|海淀黄冈全能综合大考卷|暑假作业与生活假日知新|小学达标测试与基础训练|高中新课程课时详解精练|创新学案课时作业测试卷|各地期末真题汇编精选卷|提优作业本加期末总复习|宇轩图书阳光同学作业本|高分阅读组合阅读周周练|学考优化中考总复习方案|同步创新口算题卡多式练|单元加月考开心冲刺100分|一线名师总复习暑假作业|优秀生假日时光同步练习|小学毕业升学模拟测试卷|走向名校小升初考前集训|教与学中考全程复习导练|金博士期末闯关密卷100分|初中总复习能力提升丛书|夺冠训练归类模拟总复习|暑假作业山东美术出版社|小学升学多伦夯基总复习|名师学案英语高考新题型|名校名师暑假培优作业本|试题研究全优闯关金考卷|小学综合能力测评测试卷|初中生学业评价指导用书|帮你学数学全讲归纳精练|暑假作业贵州教育出版社|中考综合学习评价与检测|新题策高考一轮复习全书|单元达标重点名校调研卷|天天向上暑假复习优计划|英语知识与能力评价手册|东和文化小学新教材全解|一卷一练单元同步测试卷|小学毕业升学考试总复习|小学英语能力测试与练习|整合集训随堂检测天天练|口算心算速算天天练习簿|口算速算解决问题天天练|新课程初中学习能力自测|名校优题课时达优练与测|快乐每一天神算手天天算|河南中考总复习专用教材|中考阶段总复习模拟试题|培优优选卷期末冲刺100分|胜券在握中考总复习指导|举一反三单元同步过关卷|小学毕业升学全程总复习|小学毕业升学系统总复习|课程达标测试卷闯关100分|课堂金考卷创优单元测评|英语中考模拟适应性测试|任务型阅读与首字母填空|暑假作业河北科技出版社|名师面对面中考满分策略|口算加应用题专项天天练|中考特训营真题分类集训|同步练习学案与过关测试|创新阅读文言文阅读训练|中考总复习夺冠训练方案|同步训练与期中期末闯关|上海市中考语文考前演练|巅峰阅读现代文拓展集训|深圳市小学英语课堂跟踪|高考英语听力模拟试题集|金考卷单元专项期中期末|阳光作业本课时同步优化|追梦之旅小学全程练习篇|阳光作业本课时优化设计|新课程学习资源学习手册|好好学习单元期末冲刺卷|初中重点知识总复习指导|实验操作与探究活动手册|宇轩图书初升高衔接教材|随堂演练及单元要点检测|快乐练习寒假衔接优计划|期中期末真题单元测试卷|举一反三期末百分冲刺卷|从课本到奥数难题大练习|巅峰对决中考仿真押题卷|津桥教育初升高课程衔接|授之以渔阅读训练与写作|新高必读初升高衔接教程|智多星试题研究课时全练|单元检测卷及系统总复习|提优检测卷初中强化拓展|创新学习同步解析与测评|非考不可年级衔接总复习|初中学业水平考试模拟卷|学业水平考试标准测评卷|暑假作业中州古籍出版社|名师课堂初升高衔接教本|同步导练新教材活学巧练|小学升学考试冲刺复习卷|高中数学知识方法和实践|亮点给力提优课时作业本|初中学业考试说明与指导|文言文课内外巩固与拓展|同步首选单元考点梳理卷|冲刺重点中学外国语学校|初中学业考试说明与解读|初中基础知识讲析与测试|中考真题及模拟试题汇编|探究学案全程导学与测评|教与学中考全程复习训练|名校学案黄冈全程特训卷|北斗星天天向上同步测试|语文阅读与写作强化训练|对口升学要点及试题精编|云南省标准教辅优佳学案|英语快速阅读与完形填空|小学升初中教材学法指导|小学升学多轮夯基总复习|中考全接触中考试题突破|语文同步拓展阅读与训练|名校联盟中考探究与测试|高中同步导学案卓越学案|同步测控学习指导与练习|中考总复习全能培优点津|中考快递中考仿真预测卷|初中毕业学业会考仿真卷|课堂讲练考中考全程突破|小升初模拟加真题考试卷|期末突破名牌中学一卷通|字词句篇与同步作文达标|课程学习与测评同步学习|英语听力与阅读能力训练|名校之路小状元冲刺100分|中考语文复习教与学指导|中考优化夺标初中总复习|名校复习期末暑假总动员|口算应用题天天练一本全|一品辅堂高中同步学练考|金指指南课课达标与自测|开心15天精彩寒假巧计划|新概念英语单元测试AB卷|单元综合练习与检测AB卷|基础知识同步训练10分钟|中考历年回顾精选28套卷|考前45分钟随堂高效检测|68所名校图书小考必做的|赢在中考3年中考2年模拟|4560课时双测同步练习册|高效A计划期末暑假衔接|轻松假期行,快乐假期行|中考专项训练与模拟E卷|新鑫文化走好高中第1步|同步课时特训,课时特训|同步阅读训练,同步阅读|培优小状元培优金卷1号|高效A计划期末寒假衔接|学期测评全A夺分冲刺卷|中考专项训练与模拟e卷|顶尖期末真题绩优8套卷|举一反三奥数1000题全解|1线课标新卷期末达标卷|第1卷单元月考期中期末|教辅1号考点梳理测评卷|中考新评价新课标二轮|假期总动员暑假必刷题|百所名校期末试卷精编|阳光同学课时优化作业|创新名卷期末冲刺100分|中考核心考点模块专训|3年中考2年模拟1年预测|新课堂同步学习与探究|普通高中学业水平考试|学业水平考试通关金卷|综合应用创新题典中点|初中文言文译注及赏析|初中文言文详解与阅读|金钥匙提优训练课课练|初中文言文全解一本通|同步训练与测评课时练|现代文综合阅读周计划|帮你学期中期末测试卷|实战课堂单元学业自测|小学毕业总复习与检测|大联考单元期末测试卷|新中考考点分类大试卷|当堂练新课时同步训练|小学生口算速算练习册|名师同行课课快乐过关|单招生——相约在高校|中考必备中考试卷精选|毕业升学冲刺必备方案|步步高自主检测与反馈|智多星创新达标测评卷|魔法教程字词句段篇章|全程测控系列课堂练习|文涛书业期末冲刺100分|过关检测同步活页试卷|课外文言文阅读与训练|明日之星课时优化作业|黑皮系列分层强化训练|初中课程学习指导手册|初中毕业升学考试指南|新动力英语优学课时练|中考专辑全真模拟试卷|各地中考模拟试卷精选|阶段性同步复习与测试|高考状元假期学习方案|全国中考试题精选精析|中考必备中考试题精选|考场速查开卷宝典速查|小学知识与能力测试卷|小学生毕业系统总复习|最高考单元阶段测控卷|初中毕业班系统总复习|中考金卷中考试题精编|名师点津课时达标检测|拉分题解题思想与方法|考前专项分类高效检测|阳光互动绿色成长空间|初中毕业中考水平测试|新课程自主学习与测评|浙江之星课时优化作业|高中英语听力满分攻略|爱尚课好学生课时检测|本土攻略精准复习方案|提炼知识点师大测评卷|假期好作业暨期末复习|高考数学轻松突破120分|英语课时单元练习组合|小学毕业升学完全试卷|新课程课堂同步练习册|黄冈教程期末冲刺100分|寒假骑兵团学期总复习|新起点单元同步测试卷|名校名卷期末冲刺100分|海淀加黄冈单元测试卷|一线调研期末真题精编|同行课课100分过关作业|全新英语同步课堂练习|一线名师夺冠王检测卷|新课程学案导学与测评|小升初名师帮你总复习|初中毕业升学考试指导|学业加油站期末满分卷|暑假作业假期学习乐园|金试卷单元同步测试卷|赢在阅读限时提优训练|暑假作业新世界出版社|二期课改配套教辅读物|浙江之星学业水平测试|初中毕业学业考试指南|学业水平考试全景训练|走进暑假自主学习指导|最高考高考全程总复习|节节高单元评价与阶段|命题规律与必考压轴题|快乐暑假假期生活指导|速算天地数学口算心算|学习乐园单元自主检测|新疆名校中考模拟试卷|教辅金牌期末冲刺100分|学业水平考试中考测评|新疆中考题典模拟试卷|小升初必备系统复习卷|学业水平考试应试指南|中考总复习名师面对面|培优作业本加核心试卷|高考总复习南方凤凰台|阶段性单元目标大试卷|学业水平考试模拟试卷|长江全能学案实验报告|实验班小学毕业总复习|小升初综合素质检测卷|快乐假期衔接优化训练|全国中考试题分类精选|古诗文阅读与拓展训练|小升初入学模拟测评卷|中考挑战满分真题汇编|南方新课堂高考总复习|小学毕业考试考试说明|培优闯关NO1期末冲刺卷|假期作业快乐接力营寒|小状元金考卷全能提优|步步高大一轮复习讲义|基础章节总复习测试卷|小学毕业考试试卷精编|口算速算应用题天天练|优加学案阅读理解训练|中考对策全程复习方案|从课本到奥数难题点拨|古诗文同步阅读与训练|走向假期期末仿真试卷|创意课堂分课活页训练|一通百通单元测试考评|单元加期末复习与测试|小学升学全真模拟试卷|新教材衔接与自主训练|名校优卷期末冲刺100分|活页英语时文阅读理解|公告目录教辅快乐暑假|课堂达优期末冲刺100分|快乐寒假寒假能力自测|暑假作业完美假期生活|教材精析精练字词句篇|德才兼备作业创新设计|学业水平模拟试卷汇编|中领航深度衔接时效卷|每月一考单元卷月考卷|考易通综合素质测评卷|小天才新题型全能测评|单元加期末100分冲刺卷|高中新课程评价与检测|中考快递英语阅读首选|高考模拟考场试题汇编|中考热点试题分类全解|智多星全优闯关金考卷|夺冠金卷单元同步测试|优化夺标期末冲刺100分|优学有道绿色互动空间|课堂作业实验提高训练|智能优选卷周周微测试|小学毕业升学模拟试卷|新编小学毕业复习宝典|精英教程字词句段篇章|一线梳理加测评大试卷|新课程实践与探究丛书|热点重点难点专题透析|初中毕业学业考试指导|邵阳标准卷中考预测卷|现代文文言文拓展阅读|启光中考全程复习方案|培优题典分类分项分级|海淀考王期末完胜100分|绿色互动空间优学优练|天天向上课时同步训练|课内外古诗文阅读特训|培优好卷单元加期末卷|小升初归类复习测评卷|八斗才金点子培优计划|天天象上初中同步学案|学习总动员期末加寒假|全国中考真题分类汇编|测试与评估模拟试题集|活力课堂新课程导学案|广东名著阅读全解全练|精英课堂字词句段篇章';
        $preg2='小学综合素质教育测评|创新测试卷期末直通车|名师金手指同步大试卷|期末冲刺100分闯关密卷|期末冲刺必备模拟试卷|冲刺重点中学必做试卷|中学生英语阅读新视野|中考快递真题分类详解|尖子生小学升学总复习|名师面对面同步作业本|期末夺冠百分百金牌卷|名校学案全能测评100分|课时练全优达标测试卷|拓展阅读寒假能力训练|毕业升学全真模拟试卷|零失误单元分层测试卷|中考总复习试题猜想卷|名校学案课堂同步导学|壹学教育拉网提优小卷|字词句段篇章语言训练|小博士单元期末一卷通|名校学案单元达标测试|全能优化大考卷金题卷|暑假作业假期读书生活|培优考王单元加期末卷|名校练考卷期末冲刺卷|小学毕业总复习导学案|口算心算速算能力训练|暑假作业期末综合复习|学练快车道寒假同步练|初中知识与能力测试卷|应用知识竞赛培训教程|语篇初中英语阅读训练|小学毕业总复习归类卷|方洲新概念名师手把手|初中升学考试复习方略|开心一卷通全优大考卷|辽宁作业分层培优学案|状元陪练课时优化设计|黄冈小状元数学基本功|减负增效拓展三阶训练|中考备战非常领跑金卷|新课程素质达标与拓展|学习总动员期末加暑假|小学互动课堂同步训练|突破中考专题分类集训|钟书金牌过关冲刺100分|中考热点作家作品阅读|随堂检测英语同步作业|口算题卡加应用题集训|天府大本营学期总复习|中考夺标最新模拟试题|期末冲刺100分满分试卷|尖子生的作业本优等生|大提速同步训练与测评|全品新中考稳拿基础分|思路教练同步课时作业|举一反三同步巧讲精练|开心试卷期末冲刺100分|期末100分闯关海淀考王|新课标课堂同步练习册|中考自主学习素质检测|考易通全程达标测试卷|三翼新学案单元测试卷|5年真题加1年模拟试题|金钥匙1加1中考总复习|星火英语Spark巅峰训练|中考数学合成演练30天|1加1轻巧夺冠完美期末|朗声初中课堂评估1加1|中考1对1全程精讲导练|亮点给力江苏中考48套|小学素质强化训练AB卷|随堂10分钟轻松小练习|一线中考试卷精编23套|千里马随堂小练10分钟|轻松课堂单元测试AB卷|单元测试超效最新AB卷|小考必备考前冲刺46天|中考快递3年真题荟萃|步步高-学案导学设计|学考新视野,中考夺分|全A计划学业水平测试|第二卷3年中考优化卷|寒假作业本,寒假园地|江苏13大市中考20套卷|暑假作业,暑假作业本|N版英语综合技能测试|尖子班课时卷,尖子班|中考总复习名师A计划|1号卷期末整理与复习|新中考全真模拟8套卷|江苏13大市中考28套卷|中考新题型模拟试卷|课时提优计划作业本|数学奥赛暑假天天练|初中总复习中考精编|中考真题常考基础题|零距离学期系统复习|小学毕业归类总复习|新活力总动员新课标|期末复习及暑假合刊|随堂练习与单元测试|学业考试真题试卷集|动力源期末暑假作业|随堂手册课时作业本|展望未来辅导与练习|创新学案课时学练测|现代文阅读新选精练|单元检测卷同步训练|阅读轻松组合周周练|同步精练与单元检测|初中复习与能力训练|状元成才路创新名卷|创新课堂创新作业本|优优好卷单元测评卷|初中课程同步学练测|探究学案专项期末卷|教与学课程同步讲练|完形填空与阅读理解|阅读训练与写作提升|同步阅读与拓展训练|步步高学案导练设计|启东中学中考总复习|课时达标与单元评估|全能金卷期末大冲刺|钟书金牌金典导学案|阳光课堂金牌练习册|中考冲刺名校模拟卷|单元过关目标检测卷|名师指导双基测试卷|小学毕业升学总复习|一卷夺高分高分密卷|学习与探究暑假学习|中考英语语法与单选|考点集训与满分备考|开心假期复习加预习|学升全程时习测试卷|阅读理解与完形填空|新起点百分百课课练|上海达标卷好题好卷|名师金手指领衔课时|海淀黄冈优佳一卷通|状元成才路创优作业|单元学习指导与评价|口算心算速算天天练|高中同步辅导与检测|初中升年级衔接教材|小学升初中衔接教材|全品小升初三级特训|初中升高中衔接教材|升学指导与强化训练|初中同步实验检测卷|小学毕业升学模拟卷|决胜新中考学霸宝典|期末复习与假期作业|奥数典型题举一反三|创新导学案高中同步|初中文言文精讲精练|完形填空和阅读理解|优化学习中考定位卷|动力源期末寒假作业|导学导练考试总复习|精编课时训练课必通|优加金卷标准大考卷|中考全真模拟测试卷|亮点给力计算天天练|上海课课通优化精练|口算笔算妙算天天练|品至教育品至好作业|挑战100分单元测评卷|拓展思维探究与应用|通城学典非常课课练|智慧课堂同步讲练测|中考解答题专项集训|冲刺100分达标测试卷|小学语文默写天天练|中考试卷汇编四色卷|三点一测学霸必刷题|天天100分优化作业本|初中总复习教学指南|中考全真模拟封闭卷|中考复习精讲与测试|课堂作业本双休练习|思维训练教程练习册|初中总复习全程导航|中考复习冲刺提分卷|毕业会考阶梯模拟卷|名题教辅名题伴你行|创佳绩课业巧点精练|单元期末满分大冲刺|课堂活动与课后评价|新目标英语阅读训练|阅读与写作双向训练|三点一测快乐周计划|高考总复习首选用卷|红对勾课课通大考卷|英才计划赢在起跑线|英语首字母综合填空|新标准英语课时作业|同步练习目标与测试|暑假作业阅读与写作|现代文阅读精讲精练|全优训练期末测试卷|中考夺分自主复习案|口算心算速算应用题|实验班全程提优训练|新目标课时同步导练|模拟试卷精选及详解|配套练习同步活页卷|ABC考王全程测评试卷|名师作业本同步课堂|100分培优智能优选卷|品至教育品至新课堂|学业考试综合练习册|本土假期总复习暑假|精彩考评单元测评卷|快乐学习课时全能练|黄冈密卷中考总复习|期中期末满分冲刺卷|新课程学习质量检测|三点一测课堂作业本|基础完全训练与提升|学业水平单元测试卷|三维设计每天半小时|蓉城学堂点击与突破|全程检测单元测试卷|学期总复习暑假生活|走进重高加练半小时|桂壮红皮书暑假天地|亮点激活青岛新中考|素质教育单元测评卷|期末夺冠满分测评卷|学效评估完全测试卷|中考考点分类新突破|四合一阅读组合训练|初中总复习夺标金卷|历史与社会思想品德|新课标教材同步导练|中考同期声核心突破|快乐练习课时全能练|新课标新疆中考导航|高分装备复习与测试|小学毕业生暑假必读|训练与检测完全试卷|黄冈小博士冲刺100分|学业总复习全程精练|全效学习同步学练测|中考模拟仿真冲刺卷|暑假生活学期总复习|阳光考场单元测试卷|课时作业与单元测试|各地期末复习特训卷|骄子之路高考总复习|同步训练优化测试卷|单元加期末冲刺100分|欢乐校园成长大本营|小博士期末闯关100分|全程助学与学习评估|启光名师单元诊断卷|新编基础毕业总复习|新动力口算估算速算|黄冈期末全程特训卷|初中学业考试导与练|新课标小学英语高手|全科王同步课时练习|高考考试大纲调研卷|毕业升学模拟测试卷|新卷王期末冲刺100分|南方新课堂暑假园地|名师新课堂集优360度|文言文古诗文一本通|小考状元必备测试卷|备考金卷智能优选卷|中考探究题精析精练|校园英语英语大课堂|全练中考中考总复习|金考卷单元考点梳理|同步练习优化测试卷|新课标英语阅读训练|全能卷王单元测试卷|小升初名校备考密卷|课外文言文拓展阅读|期末复习网全程跟踪|文言文课外阅读特训|满分夺冠期末测试卷|中学英语阅读新模式|中考必备考点分类卷|文言文经典阅读300题|英语拓展听力与阅读|名校小升初招生密卷|提优训练非常阶段123|小升初考前冲刺必备|亮点给力江苏新中考|全优课堂高考总复习|阅读与写作提升训练|金版学案高考总复习|中考标准模拟冲刺卷|毕业复习指导与训练|名校名师题优夺分卷|同步题组训练与测评|中考试题荟萃及详解|同步训练与单元测试|点睛新教材全能解读|每周6加13读3练1周1测|学海单元双测第一卷|快乐成长小考总复习|同步训练与中考闯关|创新设计高考总复习|中考总复习优化方案|花山小状元100全优卷|评价与检测暑假作业|文言文课外拓展训练|快乐的假日暑假作业|教材解读全效讲练测|同步提优阅读与训练|勤学早测试卷好好卷|全程百分百核心期末|高考命题与名校模拟|实验班变式提优训练|快乐起跑线期末冲刺|全优计划全能大考卷|一学通状元冲刺100分|多元评价与素质提升|完全大考卷冲刺名校|假期作业期末复习网|高效阅读读出好成绩|中考及会考真题汇编|状元同步阅读与写作|实验指导与实验报告|高中新课标同步作业|轻松夺冠全能掌控卷|单元优化全能练考卷|非常假期集结号寒假|名师面对面先学后练|金榜名卷复习冲刺卷|暑假活动实践与思考|单元测试新思维100分|学习指导与基础训练|同步练习加过关测试|阅读与写作双优训练|现代文经典阅读300题|成才之路同步学练测|全优方案夯实与提高|周测月考单元评价卷|精讲精测刷题一页通|黄冈小状元培优学案|小学升初中夺冠密卷|阅读与作文高效训练|快乐暑假精彩每一天|小学升初中核心试卷|总复习系统强化训练|新课标阶梯阅读训练|同步导学与优化训练|学期复习期末加寒假|新课标学案高考调研|学业水平考试总复习|英才学业设计与反馈|快乐寒假假期好时光|核心期末提优归纳卷|单元月考期末测评卷|有效课堂课时作业本|高中学业水平测试卷|小升初全真模拟试卷|中国华罗庚学校课本|高分装备期末备考卷|中考试卷汇编及详解|新动力英语复合训练|黄冈小状元语文详解|阅读拓展与作文提优|规范总复习中考突破|黄冈单元加期末金卷|优化夺标单元测试卷|全效学习中考学练测|日练周测新语思英语|名师面对面同步课堂|金牌课时单元滚动卷|新中考透析仿真样卷|小学生学习指导丛书|榜上有名中考新攻略|全优中考系统总复习|暑假生活学习与生活|初中英语听力与阅读|分层学习检测与评价|初中语文教与学阅读|期中期末联考测试卷|现代文阅读考点训练|金豆豆奔奔单元100分|抢先起跑提优大试卷|专题归类及模拟试卷|名校学案高效课时通|数法题解与达标训练|培优课堂随堂练习册|金榜秘笈名校作业本|99加1领先期末特训卷|同步作文与创新阅读|现代文课外阅读100篇|同步辅导与能力训练|毕业升学系统总复习|招生分班真题分类卷|课堂之翼分层测试卷|中考考点分类突破卷|单元综合练习与检测|笑傲中考模拟测试卷|豫欣图书名师新课堂|中考先锋中考总复习|新素质教育作业训练|实验探究报告练习册|轻松总复习假期作业|中考怎么考命题解读|单元同步训练测试题|精编提优100分大试卷|单元学习体验与评价|夺冠新课堂课时同步|课堂内外练测步步高|全程备考经典一卷通|南方新课堂金牌学案|湘教考苑中考总复习|古诗文系统化教与学|小学暑假作业与生活|教与学智能教材学案|中考模拟实战演练卷|挑战100单元检测试卷|全品高考第二轮专题|高中新课标同步导学|七彩假期期末大提升|名校课堂助教型教辅|金牌教辅学练优计划|同步指导训练与检测|假期大本营快乐寒假|开心假期寒假轻松练|分级阅读与听力训练|心算口算速算天天练|成功中考系统总复习|初中科学实验与探究|字词句篇与同步作文|优化设计单元测试卷|全真全程达标检测卷|翰东文化单元一考通|检测优化卷阶梯训练|总复习全真模拟试卷|红对勾中考试题精编|滚动迁移中考总复习|中考试题精选精析卷|中考必做真题课时练|高中新课程学习指导|字词句篇与达标训练|各地期末测试大考卷|口算计算应用一卡通|全优学练测随堂学案|学成教育必考文言文|作文素材积累与运用|各地期末卷真题汇编|学习与探究寒假学习|中考必备中考模拟卷|评价与检测寒假作业|现代文阅读全解全练|阅读理解加完形填空|必考知识点全程精练|启东中学中考模拟卷|节节高名师课时计划|领扬中考中考总复习|毕业生学业考试复习|互动中考复习大讲义|导航总复习巴蜀汇编|阳光同学课堂小检测|阅读与作文优化训练|中考必备名校中考卷|学业考试模拟与预测|名校通行证有效作业|课课通同步随堂检测|期末真题汇编精选卷|开文教育暑期好帮手|中考试卷与标准模拟|金东方文化全优训练|高中总复习优化设计|小学同步评价与测试|口算应用题整合集训|中考题型训练一本通|互联网多功能作业本|开心每一天暑假作业|六大名校中考冲刺卷|互联网高效优化测试|高分装备评优首选卷|金钥匙期末冲刺100分|中考押题最后三套卷|新课标三维同步训练|希望100全阶段测试卷|名师指导期末冲刺卷|普通高中同步练习册|单元测评卷对接中考|初中现代文阅读专刊|同步课堂随堂练习册|名师引路中考总复习|课堂精练解读与指导|智能文化单元练考王|中考冲刺仿真测试卷|新课标同步单元练习|汇考综合模拟押宝卷|系统总复习精讲精练|中考金牌中考总复习|中考总复习抢分计划|初中学业考试总复习|中考总复习特别指导|精编全程达标压轴卷|中考总复习赢在中考|高中优等生一课一练|良友文化教材同步练|活力英语全程检测卷|英语阅读理解天天练|跨越中考总复习方略|小学毕业系统总复习|小学升初中试卷精编|中考试题分类精华卷|名校学案初中生辅导|阅读与作文联通训练|初中文言文提分训练|一课一练与同步阅读|阅读与单元提高练习|考点解析与知能训练|高中同步学情跟进卷|金点考单元同步检测|中考方舟真题超详解|快乐小博士金卷100分|考点预测期末测试卷|小学毕业考试题精编|中考专题演练三合一|天天向上课堂作业本|计算小能手应用题卡|应用题巧解举一反三|状元训练法标准试卷|中考新方向发现中考|学科王同步课时练习|中考试题精选及详解|金牌作业本标准试卷|中考总复习综合练习|开心考卷单元测试卷|轻松寒假复习加预习|中考指南配套测试卷|模拟试卷及真题精选|实验班提优辅导教程|时刻准备着暑假作业|全程考评期末一卷通|课时作业与单元测评|必胜课小学同步训练|目标实施手册测试卷|英语专项突破周周练|阳光阅读海底两万里|新课程新策略复习篇|基础知识过关小练习|能力形成同步测试卷|中考十二套模拟试题|单元目标检测题AB卷|3年真题2年模拟试卷|亮点激活3加1大试卷|每日10分钟口算题卡|小升初必备冲刺48天|中考18天冲刺攻城卷|好成绩1加1优选好卷|1课3练单元达标测试|好成绩1加1学习导航|小学生阅读训练88篇|2014年高考试题汇编|寒假作业,假期作业|U计划学期系统复习|同步练习,智能训练|假期作业,暑假作业';
        $preg3='暑假乐园,假期乐园|点亮智慧A加作业本|巴蜀密卷状元1卷通|读写周计划,导学案|第1考场期末大考卷|夺A之路中考总复习|课堂制胜,有效课堂|A加直通车同步练习|中考数理化冲A特训|翰东文化期末1考通|学考A加同步课时练|金牌1号名优测试卷|初中学业水平考查|课时练优化测试卷|快乐导航点点课堂|天府领航培优高手|期末暑假提优计划|五洲导学全优学案|有效课堂精讲精练|花山小助手作业本|自主学习当堂反馈|中考题型应试训练|中考三轮复习方案|初中快乐寒假作业|中考试题专题训练|口算应用双卡训练|复习加考试标准卷|探究习案课时精练|自主学习寒假生活|各地期末试卷精选|绿色指标自我提升|精致课堂随堂反馈|蓉城学堂阅读周练|单元期末冲刺100分|100分闯关期末冲刺|新课程资源与学案|暑假作业快乐假期|怎样学好牛津英语|创新学习三级训练|黄冈中考考点突破|文言文译注及赏析|同步三练核心密卷|伴你成长暑假作业|快乐假期培优衔接|全能超越同步学案|课时作业达标训练|特优好卷全能试题|赢在暑假抢分计划|全品高考复习方案|名校联盟快乐课堂|小冠军100分作业本|初中学业水平考试|举一反三完全训练|同步练习强化拓展|快乐暑假假日时光|领航新课标练习册|模拟加真题测试卷|学年总复习给力100|课时作业加测试卷|考点归纳达标检测|单元期中期末试卷|全解全习一课一练|期末学业水平测试|小学生活创新空间|暑假培优衔接教材|新课程新教材导航|步步高高考总复习|轻松夺冠期末考卷|初中暑假快乐提升|核心课堂一通百通|考押题冲刺大狂押|基础知识加古诗文|同步首选全练全测|口算题卡加应用题|中考集训冲关试卷|名师导航好卷100分|特级教师全优试卷|假期自主学习训练|学业质量模块测评|中考文言文课课练|快乐假期暑假作业|中考冲刺课堂作业|高考试题汇编全解|语文全真模拟试卷|每时每刻快乐优加|假日乐园快乐暑假|完美假期暑假作业|天梯小状元一卷通|金钥匙默写作业本|标准期末考卷100分|同步阅读举一反三|智趣暑假温故知新|考场速查开卷宝典|原创课堂课时作业|乐享假期暑假作业|名师导航专项突破|中考全真模拟试卷|云南本土假期生活|周测月考直通高考|第三学期寒假衔接|教学练新同步练习|暑假作业哈萨克文|思维训练经典题组|河北考王赢在中考|导学与评估测评卷|中考试题分类精粹|黄金假期暑假作业|黄冈小状元作业本|实验班中考总复习|大中考学法大视野|课前课后同步练习|优加学案暑假活动|三元及第单元通关|口算题卡与应用题|初中全程复习方略|中考试卷分类汇编|名师点津随堂小测|阳光作业暑假乐园|全优学习达标训练|各地中考模拟精选|小学生智能优化卷|高考基础题天天练|小升初模拟冲刺卷|毕业升学真卷精编|全优中考复习策略|文轩小阁经典训练|专项突破模拟试卷|复习王期末总动员|单元达标名校调研|100分单元过关检测|听说读写能力培养|黑皮系列全解全练|快乐升级暑假作业|寒假培优衔接训练|中考学业水平测试|周测月考直通名校|满格电课时作业本|高效课堂宝典训练|智优教辅全能金卷|新课程学习与评价|期末考试真题汇编|伴你成长ABC向前冲|各地期末试卷汇编|互动课堂教材解读|湘岳假期暑假作业|多维互动提优课堂|毕业升学真题详解|中考真题详解汇编|课时新作业金榜卷|全效学习衔接教材|中考复习信息快递|举一反三全能训练|一课一练补充习题|一线名师提优试卷|提分百分百检测卷|生物学习能力自测|亮点给力考点激活|古诗文全解一点通|快乐益智口算题卡|小学期末冲刺100分|寒假学程每天一练|辽宁中考真题汇编|中考解读考点精练|初中培优举一反三|口算心算速算巧算|口算题卡计算能手|新课程复习与提高|学生课程精巧训练|机灵兔课堂小作业|创新考王完全试卷|单元期中期末专题|浙江期末冲刺100分|新课程学习与测评|同步解析拓展训练|新课标自主检测ABC|高中学业水平提升|黄冈360度定制密卷|一课一练补充练习|全新考卷名师导航|期末冲刺闯关100分|开心假期暑假作业|解锁中考真题档案|寒假自主学习手册|中考模拟试题汇编|中考专题分类集训|文言文全解与训练|大篷车课改导学案|假期园地复习计划|满格电课时导学练|新课程指导与练习|初中语文扩展阅读|全程金卷冲刺100分|暑假衔接状元100分|素质教育精度精测|课时优化状元练案|课程达标冲刺100分|每周过手最佳方案|新疆名师名校名卷|中考真题分类集训|中考高效复习方案|单元检测卷课时练|高中金牌单元测试|必考点灵通复习法|中考金榜专题分类|智琅图书英才学堂|初中英语分级听读|智琅图书权威考卷|特级教师优化试卷|兰州中考试题精选|程训练课时作业本|提分计划单元期末|中考文言文一本通|周报经典英语周报|小学英语进阶训练|新概念课外文言文|新标准口算练习册|状元桥双考进阶卷|精致课堂有效反馈|自主学习指导课程|新导航全程测试卷|高考模拟试题汇编|探究在线高效课堂|快乐读写练中提高|校缘题库决胜中考|世纪金榜暑假作业|名师特攻冲刺100分|单元期中期末测评|小学升学模拟试卷|清华绿卡核心密卷|精英教程100分攻略|天天向上提分金卷|假期作业自我检测|轻松夺冠全优考卷|暑假作业美妙假期|同步跟踪全程检测|新思路辅导与训练|小升初培优小帮手|各地期末名卷精选|优加学案赢在中考|文言文阅读与赏析|冲刺全真模拟试题|小学总复习冲刺卷|中考总复习一卷通|小学英语阅读高手|金牌奥数举一反三|同步导学创新学习|同步测试天天向上|荣恒教育夺冠金卷|小升初达标总复习|新考典单元测试卷|暑假天地暑假园地|宝贝计划黄冈金卷|三年中考三年模拟|名师面对面大考卷|课堂作业同步练习|同步练习创新作业|新课标同步学练测|中考准星复习指导|木头马分层课课练|高效课堂课时作业|阳光作业本金牌教|同步练习导学精练|假期伴学寒假作业|中考英语满分必备|欢乐假期寒假作业|文言文阅读及赏析|英语阅读高分突破|酷咖英语阅读理解|一路领航核心密卷|中考语文专题训练|跟课阅读同步拓展|新学案同步导与练|课程标准同步导练|初中学业考试指导|一品课堂通关测评|新动力优学课时练|351高效课堂导学案|中考总复习导与练|小考必考真题真练|优佳学案暑假活动|湘岳假期寒假作业|中考文言文一本全|小学生圆梦作业本|五维一体精讲精练|新动力课堂讲练考|课时同步配套练习|暑假衔接培优教材|小学英语阅读100篇|阅读与作文周周练|期末冲刺名校考题|高分拔尖提优教程|天舟文化精彩寒假|小学毕业升学必备|走进新课程课课练|配套练习课时作业|自主课堂全解全析|古诗古文考点阅读|总复习综合测试卷|快乐起跑线周考卷|高中英语同步阅读|假日乐园快乐寒假|字词句篇单元达标|小考状元复习100分|刷卷阶梯阅读测试|领航中考命题调研|创新设计课堂讲义|巴蜀英才同步训练|群文阅读经典读本|成功一号名卷天下|中考考点经典新题|口算应用培优题卡|名师计划口算题卡|开心假期寒假作业|西城学科专项测试|趣味数学口算题卡|一线名师云南密卷|成长资源口算应用|浙江名校名师金卷|解决问题专项训练|初中毕业学业考试|小考指导轻松夺冠|期末冲刺考易100分|金钥匙习作作业本|小升初真题分类卷|高效课堂提优训练|快乐假期寒假作业|小精豆核心期末卷|各地中考试卷汇编|快乐学习寒假作业|课后练习专题精析|期末试卷真题汇编|提优作业核心试卷|一课三练课时导学|小学期末标准试卷|新思维成才典对典|各地初中期末汇编|课内外文言文阅读|高考模拟试题精选|同步达标检测试卷|考前模拟预测试卷|初中综合寒假作业|课外阅读试题精选|中考英语听力快线|初中英语同步单词|优加学案创新金卷|中考连线课堂同步|初中英语阅读训练|假期训练黄山书社|英语听说强化训练|阅读理解完形填空|中考新突破面对面|新课堂单元测试卷|小学教材课堂全解|小考满分特训方案|初中英语专题精析|巴蜀密卷名师名卷|重点中学与你有约|乐多英语专项突破|口算应用题天天练|周测月考直通中考|乐知源现代文阅读|口算能力训练手册|冠亚中考模拟试题|复习王学期总动员|问题解决导学方案|全国中考试题精选|高分拔尖课时作业|凤凰数字化导学稿|假期课堂寒假作业|好成绩优佳必选卷|中考模拟试卷汇编|小学毕业模拟试卷|毕业总复习冲刺卷|期末冲刺营养套餐|归类复习模拟试卷|毕业总复习全真卷|考试命题指导纲要|全优点练单元计划|中考真题分类训练|拓展阅读三问训练|步步为赢赢在期末|高分计划期末突击|英语阅读理解150篇|高效课堂课时精练|永乾教育金版课堂|一课一案创新导学|名校一号梦启课堂|培优全真模拟试卷|阶段综合测试卷集|步步为赢学案导学|小学升学试题汇编|英才计划全能好卷|中考模拟试卷精编|直指名校过关评测|新课标期末考试卷|我优秀我怕谁考吧|模拟强化测试精编|单元检测创新评价|期末闯关冲刺100分|知识集锦名著导读|同步训练创新作业|东和文化同步训练|形成性练习与检测|一线课堂学业测评|阳光课堂课时作业|假期作业快乐寒假|古诗文夯基与积累|学习质量模块测评|同步训练册算到底|互动英语课文全解|高中小题限时训练|现代文赏析一本通|优加学案口算题卡|阳光课堂同步练习|湘考王名校测试卷|假期作业快乐暑假|快乐练测课时精编|单元同步核心密卷|全真模拟试卷精编|恒基学业水平测试|新编高中同步作业|随堂练习册课时练|优佳学案寒假活动|文言文积累与训练|同步测评卷期末卷|名题教辅黄冈夺冠|一线名师双优考卷|课前课后快速检测|冲刺100分必备必练|文言文全解一本通|高考模拟试卷整编|九通英语专项训练|名师点拨卷期末卷|文言文全解与赏析|中考真题专项训练|勤奋图书快乐周考|贵州英才解析测评|小学毕业生总复习|名校零距离测试卷|黑龙江中考信息卷|衔接教材学期复习|中考押题模拟试卷|自主学习能力测评|启航中考权威预测|毕业班中考总复习|智趣寒假温故知新|新课标同步练习册|中考模式试卷汇编|必考口算应用题卡|两导两练高效学案|假期生活寒假作业|适应性摸底卷精选|通城学典提优能手|口算应用题一本全|初中毕业升学指导|快乐学习暑假作业|中考解密模拟试题|主题课时强化训练|小学生应用题特训|领航中考冲刺试卷|毕业总复习导学练|中考阶段总复习ABC|名师特攻分分拆解|假期课堂暑假作业|小学拓展课堂突破|黄金假期寒假作业|语文阅读阶梯训练|百分百提优大试卷|达标加提高测试卷|课课练与单元测试|名校期末复习宝典|专项加模拟测试卷|全优学伴提优训练|中考全优复习策略|暑假自主学习手册|初中英语能力训练|名校名卷黄冈小考|思悟课堂阶梯精练|学生寒假实践手册|同步练习配套试卷|五年中考试题透视|名校名师夺冠金卷|英语经典美文阅读|高中同步单元双测|高效总复习一本通|口算速算应用题卡|衔接教材复习计划|轻负高效优质训练|中考备考训练精选|模拟试卷汇编优化|学业测评一课一测|总复习与应试训练|优秀生倍速复习法|卷行天下课时巧练|语文读写双优训练|中考备考每天一点|同步练习册课时练|中考先锋总复习卷|全国中考试题精析|三习五练课堂作业|名校名典同步助学|众行教育冲刺100分|名校作业课时精练|会考结业学习手册|全程导练提优训练|绿色假期暑假作业|中考备考好好学习|快乐衔接辅导专家|第一好卷冲刺100分|全程夺冠中考突破|中考开卷考场速查|十套密卷中考方向|假期作业提能训练|时习之期末加暑假|讲透教材数法题解|寒假作业美妙假期|黄冈小状元达标卷|智慧树同步讲练测|同步学考优化设计|一诺书业全能金卷|阳光训练课时作业|名著阅读高效训练|课时练单元达标卷|新中考全程总复习|归类加模拟考试卷|品至教育品至好卷|复习资源同步练习|五年中考一年模拟|中考复习最佳方案|初中学业考试说明|中考英语备考手册|启东专项听力训练|一课一卷随堂检测|今日课堂课时作业|寒假作业假期园地|高效导学金典课堂|高分拔尖提优训练|课本中的名人故事|非常5加2假期A计划|举一反三口算高手|高效课堂能力测评|完美假期寒假作业|初中英语听力教程|课课练与单元检测|全能测控口算题卡|中考押题最后三卷|中考英语听力冲刺|考易通课时全能练|全优课堂满分备考|走进重高培优测试|练习与测试检测卷|课时优化龙门学案|快乐驿站假期作业|中考快递同步检测|大提速中考限时练|中考英语话题复习|众行教育互动英语|中学教材知识新解|金牌高效全能英语|赢在暑假衔接教材|名师金手指大试卷|小助手课时一本通|全品中考复习方案|赢在高考假期作业|中考智胜河南中考|名师点拨课时作业|快乐假期假期生活|学生暑假实践手册|一通百通同步训练|小升初知识一本全|名著导读全析精练|远航教育全能100分|双基同步导航训练|直通中考实战试卷|双基同步导学导练|金指课堂同步检评|期末寒假提优计划|学科教学基本要求|精彩假期暑假作业|高考模拟试卷精编|点金训练精讲巧练|小学奥数举一反三|新课程单元测试卷|中考快递真题28套|小学生每日20分钟|聚焦小考冲刺48天|暑假培优衔接16讲|突破3加1精讲典练|高考语文附加40分|超效单元测试AB卷|名校招生真卷60套|高中英语阅读6加1|中考全效英语80练|新编单元测试AB卷|同步双基双测AB卷|高考语文考前50天|最佳方案冲刺AB卷|刷卷单元自测AB卷|暑假作业非常5加2|全程突破AB测试卷|中考妙策33套汇编|名校金题教材1加1|中考3加2课时优化|优化学习暑假40天|特训30天衔接教材|小学英语测试AB卷|K6金卷基础测评卷|中考试题汇编45套|寒假作业非常5加2|优化学习寒假20天|同步单元测试AB卷|刷卷专项突破AB卷|海淀单元测试AB卷|38分钟课时作业本|轻松15分达标作业|小学生每日5分钟|课后练,寒假园地|一课3练课时导练|蓉城课堂给力A加|一课3练三好练习|全优期末真题8套|中考教材梳理e卷|期末快递黄金8套|FASTUDY速士达英语|3年中考试卷汇编|1课一练课时达标|应用题,口算测试|课时训练,课堂练|语法与词汇1000题|寒假学习与应用|暑假学习与生活|悦读阅心约未来|寒假微课训练营|希望英语测试卷|实验活动练习册|初中单元测试卷|探究应用新思维|口算速算天天练|压轴题举一反三|现代文经典阅读|实验教材新学案|培优提高暑期班|自主学习与测评|导学练开心寒假|暑假作业天天练|课时分层作业本|新视野暑假作业|口算应用一卡通|实验班提优训练|浙江期末冲刺卷|创新达标考试卷|分级练习与评价|玩加学假期生活|过好寒假每一天|启东中学作业本|文言文完全解读|竖式脱式天天练|全优达标测试卷|期末暑假一本通|云南省考标准卷|特高级教师点拨|能力培养与练习|小学毕业模考卷|寒假益智训练营|暑假作业导与练|新中考应用题典|温故知新寒暑假|文言文图解注译';
        $preg4='黔西南中考导学|课时同步学练测|初中能力测试卷|同步解析与测评|优品全程特训卷|综合练习与检测|小学单元期末卷|高考总复习指导|名师指导一卷通|新课程学习指导|高效课时练加测|同步导学练习册|中考指南总复习|响叮当暑假作业|启东专项作业本|小升初实战训练|归类复习测试卷|学习目标与检测|学生实验报告册|金星教育辅导帮|小学毕业总复习|走好高中第一步|自主预习与评价|同步练习与测评|标准课堂练与考|名校课堂小练习|新课程暑假作业|优效学习练创考|配套检测与练习|单元达标测试卷|考王初中总复习|古诗文阅读精练|发散思维新课堂|单元练习与测试|新坐标同步练习|课外文言文阅读|单元期末冲刺卷|期中期末加油站|单元创新测试卷|优化设计课课练|单元达标活页卷|学效评估练习册|实验探究报告册|中考现代文阅读|暑假作业及活动|课课达标作业本|地理中考模拟通|阳光课堂作业本|先锋课堂导学案|小升初衔接教材|三维达标自测卷|畅学图书领航者|浙江期末全真卷|课课通轻松练习|中考语文全冲刺|少年智力开发报|目标复习检测卷|新坐标暑假作业|口算题卡应用题|易百分名校好卷|全频道课时作业|金种子领航考卷|初中总复习导航|夺冠同步学练考|阅读组合式训练|优质课堂导学案|科学知识一点通|中考压轴题专练|小英雄天天默写|写作指导与训练|冲刺名校大试卷|中考真题解析卷|启东黄冈大试卷|课时达标讲练测|应用题通关训练|金博士一点全通|全新暑假作业本|考点梳理全优卷|优化课堂问学案|单元同步学练测|七彩的假期生活|中考夺冠抢分练|中学生美文阅读|课堂导案天天练|自主假期作业本|轻松过关优选卷|课堂过关循环练|星级口算天天练|海东青跟踪测试|小升初真题精选|个性化能力阅读|三年真题测试卷|金钥匙读写双赢|快乐练分层作业|单元同步夺冠卷|期末冲刺大试卷|口算速算练习册|语文同步练习册|初三中考总复习|备战中考总复习|课时练提速训练|五步三查导学案|超越训练讲练测|金状元直击期末|启东小题作业本|期末突破一卷通|先锋备考期末卷|口算应用一点通|复习计划风向标|解决问题天天练|中考命题大解密|中考冲刺领航卷|教材练习与巩固|新思维培优训练|名师导航总复习|多功能闯关100分|同步训练测试卷|快乐暑假天天练|学业水平考试题|附加题黄山书社|单元期中期末卷|学情研测新标准|中考金卷预测卷|初中优选测试卷|贵州新中考导学|展望未来练习册|小学全能测试卷|状元100分作业本|夺冠单元检测卷|单元专题测试卷|同步优化练与测|初中课外现代文|质量跟踪检测卷|初中满分冲刺卷|新课堂同步训练|快乐学习检测卷|奥数趣味大闯关|新课堂易学方案|快乐学习随堂练|毕业总复习全解|考前三轮复习卷|中考复习导学案|新课程助学丛书|全频道课时精练|新课堂假期生活|易百分课时训练|一通百通考必赢|启东黄冈作业本|单元测试得满分|优百分课时互动|作业与单元评估|中考导航模拟卷|期末冲刺百分百|新课程同步练习|小升初百练百胜|单元评价测试卷|轻松课堂标准练|期末冲刺夺100分|全程畅优大考卷|暑假作业与生活|中考真题分类卷|寒假假期快乐练|过好假期每一天|培优阅读双测卷|一讲三练应用题|新课程单元测试|前沿解读新教材|新中考仿真试卷|期末100分冲刺卷|全能卷王评价卷|一课一练一本通|新题型热点题库|智慧轩冲刺100分|快乐成长导学案|模块式全能训练|期末复习冲刺卷|柒和远志直通车|课标是唯一标准|寒假专题突破练|初中实验报告册|期末考试金钥匙|新课程单元检测|一考通综合训练|学业质量测试薄|高效课堂导学案|松江区区本作业|中考开卷一本全|自我评价与提升|名校联盟冲刺卷|期末夺冠百分百|精英教程全能卷|新疆中考总复习|中考原创预测卷|打好基础练加测|中考复习三级跳|金博优暑假作业|培优全程检测卷|衔接学习一本通|最高考假期作业|名校提分一卷通|新课程实验报告|天天向上周周测|非常假期集结号|新课堂实验报告|快乐寒假天天练|学练案自助读本|期末联考测试卷|文言文高效训练|初高中衔接教材|全优考评一卷通|同步课时练测卷|畅学图书学与练|新动力高分攻略|同步单元测试卷|期末满分冲刺卷|课堂360度测试卷|同步特训小博士|综合复习与测试|新编初中总复习|新概念口算题卡|导学练寒假作业|轻松夺冠优胜卷|高分计划一卷通|寒假作业及活动|达标金卷百分百|新课堂课外阅读|精编提优大试卷|语法活页一卷通|一轮复习导学案|寒假作业与生活|新思维暑假作业|金钥匙阅读书系|新课标同步练习|中考专项大演练|单元检测评估卷|全程优选测试卷|小学总复习教程|名师课堂导学案|全程导航大提速|小学生绩优名卷|探究活动报告册|中考备考全攻略|名师伴你总复习|魔法阅读讲解练|中考分类精华集|中考试题精选集|升学考试一本通|指南针神州中考|三维阶段测评卷|寒假作业正能量|课程达标测试卷|三维同步学与练|金榜夺冠真题卷|轻松暑假总复习|优化作业加试卷|课时学案作业本|单元滚动检测卷|中考复习与指导|一线名师提分卷|同步拓展与训练|综合学习与评估|各地真题精编卷|阶段检测优化卷|百强名校统一卷|全能达标测试卷|新课程能力培养|中考最后一套卷|现代文课外阅读|口算速算智力算|百分学练测考卷|精彩课堂轻松练|完全攻略周计划|快捷英语周周练|中考模拟总复习|中考导航押题卷|专项卷和真题卷|文言文图解注释|金钥匙模拟密卷|试验探究报告册|飞越阅读周周测|英语听力模拟题|中考名师预测卷|绩优生绩优名卷|能力培养与测试|百校联盟金考卷|自能课堂作业本|实验班培优训练|优倍伴学总复习|毕业生暑期必读|高中汉语练习册|启东黄冈小状元|实验探究与指导|名师面对面中考|新课改课堂作业|期末真题优选卷|快乐学习一点通|课时变式学与教|小学英语一本通|考点知识梳理卷|自主课时天天练|新课程达标检测|认知规律训练法|单元期末综合卷|口算估算课课练|初中古诗文详解|名校年度总复习|精彩假期寒假篇|常规作业天天练|期末冲刺满分卷|状元新课堂100分|培优竞赛新方法|文言文课内阅读|中考复习总动员|刷卷期末冲刺100|教育世家状元卷|名师计划导学案|数学学习与研究|新起点同步精练|亮点给力大试卷|小超人创新课堂|天天练口算题卡|黄冈课堂作业本|课时练同步测评|课时单元金银卷|期中期末复习卷|同步检测三级跳|重难点突破训练|中考真题超详解|小学总复习指导|一线名师作业本|口算达标天天练|小学数学应用题|期终冲刺百分百|北京中考必刷题|新课标同步精练|图解巧练应用题|导与学学案导学|研究性学习指导|文言文阅读全解|名师作业导学号|单元期末测试卷|小升初冲进名校|微课堂单元计划|上海中考总动员|系统分类总复习|尖子生培优教材|中学单元测试卷|新课堂同步阅读|典型易错题训练|创新达标期末卷|小升初金卷导练|百分好题测评卷|小学生学习园地|伴你学习新课程|单元专项过关卷|新课堂冲刺100分|出彩同步大试卷|导学精析与训练|同步训练与闯关|单元练测活页卷|新练习巩固方案|新支点卓越课堂|学习指导与检测|小学培优总复习|轻松寒假总复习|全优模拟大考卷|习题化知识清单|实验班提优课堂|单元月考过关卷|寒假生活微指导|步步高同步提优|新概念作业练习|冲刺100分必选卷|名师金典测试卷|百分百全能训练|文言文比较阅读|升学全真模拟卷|夺冠课时导学案|名师优选冲刺卷|初中优化测试卷|毕业升学总动员|过好暑假每一天|暑假假期快乐练|英语听力与训练|名师课堂一练通|名校名师模拟卷|考点梳理时习卷|基础小题天天练|课堂内外金考卷|金榜名卷六合一|寒假学习与生活|江苏名师大考卷|名题同步导学练|全新寒假作业本|总复习寒假作业|单元提优测试卷|高分装备评优卷|高频考点总复习|英语阅读周计划|暑假衔接起跑线|精编全能大试卷|现代文阅读突破|欢乐春节快乐学|名校名师大联盟|考前全程总复习|小学科学实验册|题优中考总复习|快乐暑假快乐学|初中语文阅读卷|金牌学案风向标|古今文拓展阅读|全程提优大考卷|高中阶段三测卷|金状元绩优好卷|10套模拟2套真题|全程练习与评价|同步作文新讲练|学业评价测试卷|阶段综合测试卷|同步强化训练卷|小学生暑假衔接|新编单元练测卷|全程检测金考卷|古诗文高效导学|高中同步测试卷|系统归类总复习|期末智能优化卷|形成性自主评价|小升初必刷题现|课外古诗文阅读|基础强化天天练|基本功测评试卷|新课标高效阅读|优加全能大考卷|小单元复习手册|课外现代文阅读|高考密码猜题卷|全优点练课计划|名师选优冲刺卷|高效假期总复习|青海省中考密卷|微学习非常假期|八斗才英才计划|新天地阶梯阅读|数学奥赛天天练|小学期末总冲刺|育才课堂教学案|单元综合大考卷|初中优化作业本|同步作文教学练|文言文扩展阅读|小学毕业升学卷|期末冲刺智胜卷|全程培优测试卷|阳光课堂口算题|竞赛模拟训练卷|寒假培优作业本|学与练课时作业|文言文全能达标|优加学案课时通|阶梯训练示范卷|好学生本土中考|成功阶梯步步高|名校课堂优选卷|同步学习与辅导|名校秘题小学霸|中考考点集训卷|同步训练作业本|小学毕业考试卷|课堂知识梳理卷|单元优化练考卷|考前提分天天练|学业水平测试卷|毕业班综合训练|海东青中考ABC卷|微课程单元自测|专题复习教学案|新支点中考经典|培优闯关练加考|微课程学案导学|抢先起跑大试卷|中考零距离突破|重难点题库大全|期末复习百分百|实验操作练习册|中考信息猜想卷|南通小题课时练|标准单元测试卷|初中期末测试卷|随堂手册作业本|南通小题周周练|同步训练达标卷|毕业综合练习册|单元专题双测卷|新坐标寒假作业|中考复习指南针|全程考评一卷通|新教材完全解读|毕业生复习丛书|魔力导学开心练|中考模拟预测卷|金状元提优好卷|全品高分小练习|中考总复习试卷|活页单元测评卷|中考拐点讲练本|名师点拨测试卷|全程达标测试卷|配套练习与检测|同步导学与测试|小状元冲刺100分|语法与单项选择|智能训练练测考|一卷通完全试卷|探究实验报告册|文言文多维全解|新课程学习辅导|名校密卷活页卷|课时单元夺冠卷|领跑中考新方案|口算心算天天练|木头马阅读小卷|全能闯关冲刺卷|同步练测一本全|课堂能力测试卷|中考说明与训练|课时达标百分百|组合阅读周周赢|金考卷活页题选|中考精英总复习|优质课堂导学练|双基过关堂堂练|单元检测达标卷|国华学期总复习|小学优化测试卷|暑假衔接优计划|伴你成长作业本|中考本土预测卷|全程突破导练测|知识与能力训练|满分王周周检测|暑假生活微指导|小学总复习全案|暑假衔接提优卷|中教联优化指导|全品高考短平快|全程评价与自测|综合素质测评卷|同步阅读周周练|阅读理解与写作|导学练暑假作业|状元龙快乐学习|课内课外直通车|口算心算快速算|新课改课堂口算|零失误分层训练|备战中考信息卷|尖子生超级训练|金钥匙中考冲刺|创优考冲刺100分|指南针导学探究|暑假作业正能量|学案与能力培养|导学与探究丛书|单元全能练考卷|指南针高分必备|自能自测示范卷|新语文阅读训练|学练案自主读本|英语阅读早晚练|小升初模拟试卷|教材解读与拓展|阳光课堂应用题|黄冈冠军课课练|自我提升与评价|数学课堂与感悟|启东培优微专题|智能测评与辅导|练与测联动课堂|中考625仿真试卷|优干线暑假计划|小升初完全试卷|期末夺冠测试卷|三好生课时作业|导学检测跟踪卷|轻松赢考期末卷|课程探究大试卷|轻松英语听力通|全优期末大考卷|小学生词语手册|中考全程总复习|总复习中考押题|黄冈中考押题卷|中考导航总复习|互动课堂导学案|学与练全程卷王|课时精练与精测|中考英语阅读通|必胜课课课达标|口算与应用题卡|单元整合与测评|古诗文专题精练|全品小学总复习|七天学案学练考|课时提优作业本|标准课堂测试卷|新课标同步训练|小天才课时作业|期末寒假一本通|满分冲刺微测验|节节高解析测评|决胜中考模拟卷|乐学名校点金卷|中考系列红十套|100分考点大试卷|期末寒假大串联|文言文教材全解|学习与考试图册|狂做小题天天练|黄冈名师天天练|全优口算作业本|同步应用天天练|文言文导读精练|艺术生百日冲刺|中考总复习导学|2年真题3年模拟|3年中考2年模拟|5年高考3年模拟|53题霸专题集训|轻松15分导学案|8年沉淀1年突破|考前小综合60练|课堂检测10分钟|实验班培优15讲|3年中考3年模拟|小学英语AB试卷|中考5加3预测卷|中考60天冲刺卷|5年中考3年模拟|五年中考三年模拟|首席单元19套卷|1年模拟1年小考|优化高效1课3练|中考名校28金卷|黑12套模拟试题|课堂巧练10分钟|中考5加3模拟卷|1日1练口算题卡|首席期末8套卷|魔力暑假A计划|每天6分钟计算|习题e百检测卷|课堂小测6分钟|期末复习第1卷|金3练课堂学案|A加资源与评价|中考5月冲关卷|全程智能1卷通|A加优化作业本|Gogo伴你开心学|课堂检测8分钟|新学考A加方案|冲刺100分1号卷|每周1考课课训|阅读写作e路通|MOOC中考新天地|问鼎燕赵3刷卷|5年真题与模拟|魔力寒假A计划|新课程寒假BOOK|A加100智取期末|高分拔尖训练|创新课时作业|过关冲刺100分|单元质量达标|名校阅读训练|竟赢高效备考|绿色成长空间|课时达标100分|课课练检测卷|新编牛津英语|长江全能学案|暑假学习园地|期末冲刺100分|起航阅读在线|暑假假期集训|上海分层作业|升学模拟考试|暑假衔接教程|单元加期末卷|冲刺名校小考|期末试卷精选|衔接暑假作业|创新课时训练|小升初特训班|同步系列支点|九江实验中学|常考难题突破|小升初冲刺卷|名校课堂内外|快乐接力营暑|课时练测试卷|应用题天天练|寒假期好帮手|中考升学指导|全真模拟试卷|快乐寒假生活|期末100分闯关|面对面课时练|小考冲刺金卷|权威试卷汇编|阅读阶梯训练|剑桥小学英语|全程复习训练|单元练习组合|倍速课时学练|开心夺冠100分|英语听力专练|语法精讲精练|尖子生新课堂|中考复习攻略|全优课堂作业|高效培优读本|中考命题调研|同步导学练测|阅读高效训练|暑假生活指导|教材知识详解|全程闯关100分|中学英才教程|小学语文阅读|状元口算计算|名师课时计划|假期生活指导|课程基础训练|中考试题精选|培优暑假作业|教学质量检测|中考达标学案|中考模拟密卷|课堂完全解读|中考模拟演练|计算专项训练|名校指典暑假|标准课堂作业|单元目标检测|迈迈快乐教程|中考试题精编|章节复习手册|期末复习合刊|组合阅读训练|应用题训练营|中考全面出击|小学课时特训|牛津高中英语|步步高练加测|全能课时训练|阅读达标训练|科学全能检测|备战中考寒假|全优课程达标|新课标新精编|英语阅读训练|中考真题精选|全品语法快练|暑假快乐假期|七彩成长空间|新梦想导学练|考点专项突破|核心考点全解|中考高分突破|单元夺冠100分|阅读授之以渔|学霸错题笔记|学升同步练测|新课堂作业本|尖子生课课练|考易通大试卷|小学教材全测|优化同步练习|中考模拟试卷|课堂达标100分|中考模拟试题|课堂练习检测|寒假成长乐园|中考酷题酷卷|核心地图点拨|尖子班课时卷|学业水平考试|知识检测试卷|成功训练计划|毕节中考导学|中考试题解读|智趣暑假作业|高效课时学案|寒假高效作业|倍速同步作文|英语学习手册|三维数字课堂|寒假假期集训|标准期末考卷|考前突破密卷|新考题大集结|寒假快乐假期|名校单科考王|单元专题训练|期末模拟试题|火线计划暑假|英语听力训练|中考真题汇编|全能达标100分|全优课时作业|寒假培优衔接|中考备战策略|新思维夺冠卷|字词句段篇章|学业水平测试|中考试卷汇编|满分训练设计|第一线导学卷|应用题小状元|假期复习计划|单元练习测试|英语奥林匹克|名校全优考卷|教材完全解读|中考分类必备|分级阅读训练|能力培优100分|中考分类集训|阅读训练100篇|中考复习必备|复习计划100分|寒假生活指导|语文阅读训练|中考复习金典|全品中考试卷|步步高达标卷|寒假提优捷径|黔南中考导学|期末考点预测|中考复习指导|期末闯关100分|新课程练习册|毕节题库新编|中学教材全解|考点调查360度|淘金先锋课堂|寒假学习生活|基础卷限时练|寒假学习园地|配套综合练习|寒假学习乐园|中考必备6加14|中考试题汇编|期末复习检测|新课程导学案|阳光假日寒假|毕节中考全解|学习思考行动|寒假优化学习|中考科学集训|贵州中考金卷|小升初失分题|优加学习方案|暑假优化学习|课时复习讲义|寒假专题集训|中考满分教练|中考试卷精选|单元能力自测|高考真题汇编|英语活动手册|试卷分类汇编|互动同步训练|给力闯关100分|学业提优检测|归类总复习卷|学生活动手册|初中学业考试|胜券在握阅读|中考专项突破|新课程新练习|基础能力训练|中考专题讲练|优等生测评卷|新疆中考名卷|黄冈口算题卡|学科能力达标|入学考冲刺卷|小学滚动测试|综合应用题卡|暑假提优衔接|大单元测试卷|全能闯关100分|黄冈状元笔记|名校名师名作|口算题天天练|口算应用题卡|汇测期末刷题|中考复习精要|口算心算速算|中考真题精编|假期乐园寒假|优干线周周卷|期末真题精编|轻松学习100分|中考真题分类|金考卷资源包|随堂优化训练|心算口算巧算|初中同步练案|期末轻松100分|英语组合阅读|轻松阅读训练|学习指导用书|精巧暑假作业|金钥匙课课通|考点同步解读|同步奥数培优|同步课堂感悟|中考试题荟萃|快乐假期作业|小升初押题卷|名师同步导学|课时周测月考|小学目标测试|同步课时精练|阅读组合突破|课本配套练习|课时练优选卷|暑假学习乐园|中考试题研究|课时练加考评|初中英语阅读|遵义中考导学|全程探究阅读|课课练活页卷|特别培优训练|走进英语小屋|初中英语读本|琢玉计划暑假|聪明芽导练考|名校试卷精选|导学测评拓展|总复习测试卷|中考智胜考典|新经典练与测|微专题小练习|名校课堂练习|英语同步听力|综合能力训练|初中英语听力|应用题作业本|竞赢高效备考|三维随堂精练|小升初重点校|小升初总复习|大阅读周周练|多维阅读课堂|决胜期末100分|分层强化训练|中考仿真试卷|名校闯关100分|自主创新作业|培优口算题卡|同步轻松训练|同步练习指导|新教材同步练|试题优化精编|开心口算题卡|创新课时精练|从课本到奥数|教材全能学案|初中语文精练|小学升学夺冠|学习活动方案|黑马金牌阅读|小题考前100练|先锋备考密卷|素养提升讲练|721数学总复习|同步口算题卡|假期学习乐园|小考复习精要|金牌名师导航|快乐英语阅读|名校考点梳理|全优冲刺100分|高效单元双测|中学生数理化|小升初大通关|期末金卷100分|高分夺冠真卷|英才学业评价|开心闯关100分|学习实践园地|周周练月月测|新课标应用题|小学英语听读|与经典面对面|暑期升级训练';
        $preg5='中考复习方案|拓展阅读训练|练考通全优卷|英语进阶特训|新活力总动员|全程检测100分|暑假专题集训|强化模拟训练|小学期末100分|小升初全能卷|单元自测试卷|同步词汇训练|课堂同步提优|暑假学习生活|168套优化重组|全能智慧课堂|特优复习计划|优干线测试卷|小学课堂全解|小升初特训卷|优干线课课练|小学达标训练|小学毕业升学|创优作业100分|高效同步测练|阅读片段训练|走向名牌中学|课堂活动手册|毕业模拟试卷|统一标准试卷|本真全程学案|专项复习训练|学与教超链接|全优卷练考通|培优应用题卡|初中复习指导|模拟试题精选|高中同步导练|上海名校名卷|毕业复习指导|英语完形填空|口算与应用题|毕业复习资料|中考冲刺试卷|期末综合测试|同步拓展阅读|湖南中考必备|中考考点设计|新课时作业本|师教你学数学|创新优化学习|中考精确制导|小学奥数读本|中考一路领航|试题方法详解|初中模拟汇编|考纲强化阅读|夺冠王检测卷|真题专项分类|新编基础训练|高效期末复习|寒假轻松假期|课内外文言文|新课堂同步练|高效复习计划|小学自评测试|权威模拟试卷|中考提分攻略|新疆小考密卷|暑假衔接教材|随堂考一卷通|小超人作业本|轻松双休练习|七鸣巅峰对决|成功冲刺100分|启典同步指导|口算基础训练|随堂同步练习|期中期末100分|中考整合集训|口算测试100分|智趣寒假作业|倍速同步口算|小博士期末卷|全优期末测评|培优模拟试卷|中考复习指南|贵州小考导学|时政热点精析|互动作文训练|神算手天天练|小考分类必备|名著精讲细练|初中课堂笔记|易考100一考通|导学案精练集|教材全解全析|单元智取100分|长江寒假作业|初中信息技术|走向高考考场|金太阳导学案|中考专题专练|暑假高效作业|课堂达标检测|同步轻松练习|初中名校密卷|金钥匙冲刺卷|初中物理探究|长江暑假作业|中考复习总汇|暑假课程练习|全品小学阅读|期末复习指导|名校期末密卷|新世界新假期|新编综合练习|小学互动英语|小学暑假作业|课堂夺冠100分|百分百夺冠卷|初中暑假作业|暑假拔高衔接|小考满分策略|学习探究诊断|金典课堂学案|阅读强化训练|新中考新突破|开心寒假作业|学习能力自测|单元同步训练|中考分类训练|教材精析精练|英语能力训练|走向中考考场|同步检测金卷|能力拓展练习|前沿课时设计|阅读复合训练|中考备考计划|学习指导丛书|小升初夺冠卷|梦想家大试卷|学习质量监测|培优同步作文|金牌达标训练|课课练单元测|新动力课时练|上海中考真卷|世纪同步精练|中考热点解读|活力假期暑假|寒假课程练习|阳光学业评价|分课活页训练|说课教材诠解|竞赛培优教材|上海名师导学|初中基础训练|综合复习检测|化学全程复习|广东中考必备|宇轩衔接教材|金牌一课一练|年度复习精要|汇测初中英语|阅读专题训练|新教材新学案|双基优化训练|阅读理解填词|考霸中考档案|课外阅读训练|首席课时训练|初中物理实验|语法专项训练|课时同步导练|中学区域地理|我的寒假生活|新课改新学案|年度复习计划|课堂同步评价|中考精品汇编|小学课堂笔记|初升高必刷题|名校金典课堂|中考试题精粹|黄冈兵法密卷|星际培优测试|学业考试手册|七彩口算题卡|华语中考模拟|新编助学读本|英语短文填词|新课程新学习|教材完全学案|中考试题调研|教与学新思路|全优训练计划|新阅读训练营|快乐学习暑假|素质目标检测|新阅读与作文|英语听读空间|假期自主学习|教材补充练习|小升初模拟卷|本土期末寒假|创优课时训练|中考化学冲刺|初中衔接教材|中招标准样卷|提升训练暑假|暑假培训教材|冲刺名牌初中|中考全程突破|同步核心练习|暑假轻松假期|单元双测试卷|同步听读训练|初中分层作业|123中考冲刺卷|教材备考笔记|课堂活动用书|同步评价手册|心算口算速算|小学暑假衔接|小学课堂练习|高效课堂作业|大暑假小一轮|高效提升金刊|初中语文阅读|阅读训练80篇|随堂1加1导练|暑假提优40天|Sunny假期作业|寒假HAPPY假日|1加1单元夺金|寒假生活20天|快乐语文1加1|暑假Happy假日|课时特训AB卷|1加1轻巧夺冠|星火英语SPARK|智能达标AB卷|智慧金卷26套|中考冲刺60天|单元测试AB卷|全优新同步|金色阳光AB卷|考前冲刺16卷|知识集锦1加1|15天巧夺100分|小学夺冠AB卷|快乐5加2金卷|寒假拔高15天|综合测评1加1|53中考真题卷|期末模拟16套|1加1阅读好卷|轻松学习40分|备战中考8加2|双基同步AB卷|25套试卷汇编|10分钟天天练|40分钟课时练|单元评估AB卷|寒假Happy假日|阅读真题80篇|暑假生活50天|创新阅读AB卷|中考45套汇编|课堂检测AB卷|寒假提优20天|综合检测1加1|中考冲刺e百|英语mini课堂|5年中考试卷|PASS教材搭档|3年真题原卷|江苏5年经典|X新目标英语|A加闯关100分|PASS绿卡图书|金海全A突破|课程新体验|活动报告册|学年复习王|暑假接力棒|期末冲刺卷|提优作业本|暑假总动员|全品作业本|同步练习册|小学总复习|全品小复习|系统总复习|一线调研卷|题优讲练测|快乐过暑假|活动填图册|基础小练习|倍速学习法|中考总复习|中考大决战|全品学练考|优化与提高|精课大百度|360度训练法|同步测试卷|现代文阅读|试吧大考卷|名校测试卷|暑假新天地|招牌题题库|真题汇编卷|浙江新期末|复习与研究|寒假总动员|衔接训练营|随堂大考卷|常考基础题|决胜新中考|暑假作业本|培优提高班|运算升级卡|创新与探究|聚焦新中考|辅导与训练|达标测试卷|黄冈小状元|课时配套练|单元检测卷|强化拓展卷|寒假小小练|金牌作业本|备考总复习|培优总复习|中国好阅读|优加作业本|培优新课堂|考前巧复习|青苹果阅读|每日半小时|口算大通关|导学与演练|期末小状元|自主学语文|课堂作业本|计算小达人|原创与经典|字词句段篇|魔力导学案|国华作业本|培优大考卷|考出好成绩|考卷大集结|学业检测卷|标准大考卷|单元测试卷|暑假总复习|轻松作业本|考能大提升|探究与巩固|黄冈创优卷|假期总动员|中考风向标|状元大课堂|暑假训练营|跟踪测试卷|考点全易通|单元达标卷|原创新课堂|随堂小卷子|单元练测卷|冠军练加考|实验活动册|走向优等生|学习与检测|课标新检测|期末真题卷|快乐作业本|好题作业本|全品新阅读|期末大冲刺|应用题题卡|考场透视镜|配套练习册|课时练练通|暑假提高班|课时导学案|畅优新课堂|名校期末卷|随堂测试卷|毕业总复习|实战演练卷|金牌堂堂练|分层测试卡|题优练与测|寒假新天地|学习与评价|周周清检测|阅读与写作|自主学英语|学生双语报|导学新作业|双语课时练|优等生数学|导学与测评|中考零距离|中考步步高|学习与拓展|课时天天练|初中大联考|口算一本通|优品新课堂|名校练加考|口算练习册|课时训练案|仿真临考卷|课时作业本|尖子生题库|复习总动员|新同步练习|功到自然成|金牌周周练|寒假大串联|中考制高点|初中总复习|中考错题本|零负担作业|中考一本通|新口算题卡|名师解教材|天天练口算|决胜百分百|龙门点金卷|名师面对面|同步伴你学|迎战新考场|基础天天练|课堂小作业|创新课时练|导学与测试|课堂学与练|黄冈随堂练|完美大考卷|高考导学案|倍速训练法|学习与实践|新优化设计|同步天天练|考场百分百|金牌每课通|练习与测试|体验型学案|完全练考卷|新题型大典|寒假接力棒|午间天天练|期末天天练|课堂导学案|英语练习册|周周大考卷|口算天天练|加分猫汇测|名校作业本|新黑马阅读|口算作业本|新群文读本|尖子生学案|智能考核卷|期末大考卷|总复习指导|全优大考卷|畅响双优卷|单元评估卷|非常好夺冠|期末集中赢|名师大考卷|仿真模拟卷|金榜测评卷|单元与期末|名校特优卷|高效课时100|复习与预测|快速课课通|课外文言文|名师导学练|巩固与提高|金榜导学案|课堂新坐标|暑假加油站|课堂学练考|寒假新动向|中考新导引|点对点题型|名校一卷通|品学双优卷|寒假新时空|填充练习册|重难点手册|期末金牌卷|暑假小小练|中考直通车|创维新课堂|密码大考卷|快乐练练吧|新天地期末|化学实验册|新每课一练|随堂讲与练|新课程精析|导学新课堂|期末集结号|计算零失误|单元期末卷|寒假学与练|培优作业本|寒假训练营|学霸作业本|计算小状元|课堂新动态|模拟测试卷|体验与创新|期末直通车|品优练考卷|训练与检测|培优新帮手|新教材全练|综合练习册|综合测试卷|中考模拟卷|中考新动向|正宗十三县|创优考100分|黄冈测试卷|小考全卷王|期末大收官|小考总动员|寒假总复习|名校练考卷|状元成才路|寒假直通车|期末大盘点|练出好成绩|素质提优123|新中考集锦|课课大考卷|文言文阅读|期末总复习|学生成长册|新疆新中考|名校直通车|中学生世界|假期新作业|百分大闯关|创新大试卷|学习辅导报|新概念英语|名师金考卷|课时全优练|假期面对面|名师伴你行|天府教与学|全真模拟卷|挑战压轴题|励耘新中考|总复习提纲|中考必胜搏|时代新课程|课时总动员|助学案测读|优佳大考卷|期末大赢家|夺冠小状元|学期总动员|能力测评卷|单元测评卷|优品小课堂|暑假新时空|优选期末卷|专题分类卷|高中练习册|优品金题卷|课时新体验|新中考先锋|听力一本通|期末夺冠卷|轻松假期行|精英新课堂|说明与检测|听力总动员|单元直通车|期末考向标|期末考试卷|同步练与测|寒假必刷题|标准模拟卷|听力与阅读|全能测试卷|高效全能卷|期末优选卷|中考冲刺卷|学习总动员|中考一卷通|Top巅峰特训|暑假星生活|全练押题卷|假期作业本|中考训练卷|拓展与培优|单元自测题|复习与考试|金榜小状元|新暑假作业|寒假作业本|与名师对话|新补充习题|文言文精解|小考练兵场|与名师同行|创新测试卷|沙场大练兵|全方位阅读|新题型题库|新课标指导|本土地道卷|期末特优卷|文科爱好者|名师教学练|中考猜题卷|理科爱好者|同步新课堂|全能总复习|名校压轴题|名师大课堂|优等生兵法|中考夺分王|升级创优卷|金榜大讲堂|非常九年级|高中必刷题|快乐小博士|新编教与学|南方新高考|中考倒计时|口算超级本|期末金考卷|暑假必刷题|模拟题精编|红领巾乐园|中考导学案|课时讲练测|核心金考卷|状元测试卷|中考大提速|中考押题卷|针对性训练|新中考指南|中考专题通|练习自测卷|全程大考卷|升学总复习|导学与评估|知识大集结|维克多英语|魔力一卷通|金太阳考案|复习直升机|满分训练法|新天地试卷|课时学练测|数学导学案|高中得分王|新教材讲解|智能优选卷|阅读早晚练|中考单科王|单元月考卷|暑假百分百|研优大考卷|南方教与学|学习与研究|快乐过寒假|化学教与学|小学知识点|口算计算题|素质评估卷|应用题夺冠|暑假一本通|中考预测卷|导学与训练|小学生生活|阅读新概念|学业加油站|同步大试卷|BBS试卷精编|考点全演练|暑期预科班|夺冠冲刺卷|高效新学案|全程培优卷|名校百分卷|名师点拨卷|全优测试卷|中考动车组|应用题秘籍|金榜夺冠卷|特优五合卷|凤凰新学案|图解应用题|优等生题库|期末测试卷|复习大本营|中考开卷通|状元导学案|100分天天学|三级测试卷|状元大考卷|全优天天练|状元作业本|中考必刷卷|课堂练加测|浙江新中考|金牌导学案|探究与训练|新黄冈兵法|百分百训练|专项期末卷|学习方法报|初中生生活|发展性评价|总复习大全|优效作业本|非常好冲刺|精英口算卡|百题大过关|期末小综合|小题轻松练|期末考哪儿|课后精练卷|复习与指导|知识点聚焦|我的笔记本|课堂小测本|暑假接力赛|评价与测试|全优标准卷|应用题点拨|期末好成绩|中考考什么|学法大视野|精练与提高|山西新中考|毕业模拟卷|全能夺冠卷|培优新方法|导与练练案|导航总复习|夺冠百分百|自主学数学|暑假直通车|全练练测考|华夏一卷通|中考必备卷|初中生必背|中考第一卷|作文起跑线|全程测评卷|中考新视野|高考总复习|寒假零距离|师大测评卷|练习新方案|高效新导学|全程检测卷|励耘新同步|测试新方案|新目标检测|测试组合卷|高效期末卷|入学测试卷|同步学练考|高效课时通|常德标准卷|权威测试卷|上海达标卷|同步检测卷|新课程导学|考点一本通|基础与提升|100分冲刺卷|暑假大串联|轻松练测考|阅读周周练|物理导学案|权威作业本|全程优选卷|99加1活页卷|中考指导书|猫头鹰阅读|实验报告册|考易百分百|期末押题卷|零负担试卷|暑假天天乐|归纳与测评|分层周周测|导学思丛书|天天好试卷|暑假展才艺|黄冈新课堂|学习与巩固|学网期末卷|名校新中考|小学全程卷|全优冲刺卷|冲刺六套题|中考加速度|考前总复习|全优备考卷|年度总复习|新中考英语|夺冠新课堂|中考大本营|培优新航标|原创讲练测|课时讲练通|中考仿真卷|海淀中学生|培优大视野|新课时作业|状元训练法|愉快的寒假|阅读风向标|高考必刷题|学优冲刺100|分层课课练|培优夺冠王|计算天天练|专项训练卷|英语新听力|生本精练册|课课小考卷|阅读加力略|期末冲刺王|作业百分百|课堂练习册|作业与测试|儿童乐学园|创新课课练|随堂练习卷|100分夺冠卷|全能真题王|作业与测评|高效学习法|达优测试卷|寒假百分百|检测优化卷|暑假学与练|寒假新生活|阅读新视界|同步练测卷|单元智测卷|中考新概念|中考新评价|中考全接触|广东学导练|导读与检测|期末总动员|听力特训营|目标与检测|初中生世界|双休日作业|名校零距离|奥数训练营|学期复习王|优学三步曲|周周练听力|假期训练营|中考一本全|创新大课堂|假期快乐练|好课堂堂练|同步学练测|基本功训练|学期总复习|课时检测卷|南方新中考|总复习资料|名师精选卷|假期新观察|单元评价卷|名师导学案|新坐标学案|暑假新动向|假期好时光|期末预测卷|今年新试卷|英才小灵通|周自主读本|优佳百分卷|全优练考卷|导学全程练|加练半小时|愉快的暑假|原创仿真卷|英语周计划|假期百分百|中考总动员|核心测考卷|拓展读与写|生物实验册|金榜小博士|阅读急先锋|广东新考卷|中考一练通|名师手把手|名师优选卷|挑战零失误|同步大冲关|秒杀口算题|特优练考卷|青海省中考|顶尖单元练|暑假同步练|全品优等生|快乐暑假练|小考加速度|赢在新课堂|中考面对面|期末加寒假|非常课课通|顶尖课课练|指导与训练|智慧大考卷|特优期末卷|中考伴我行|寒假一本通|同行学练测';
        $preg6='中考大检阅|综合课课练|国学小状元|直通实验班|长江作业本|课时一本通|名师新学案|360全优测评|寒暑假作业|最新版数学|学考模拟卷|假期天天乐|总复习测试|中考新突破|假期伴你行|同步测评卷|全品大讲堂|暑假骑兵团|三维导学案|抢分加速度|寒假加油站|完全大考卷|培优新题库|驿站新跨越|智慧大课堂|新概念阅读|培优周课堂|作业课课清|走进文言文|能力测试卷|竖式计算卡|金钥匙试卷|新暑假生活|智慧小复习|资源与评价|知识训练营|新辅教导学|中考新方向|全程练考卷|中考新航线|阅读与完型|新寒假生活|课堂10分钟|随堂练1加2|新课堂AB卷|教材3D解读|随堂10分钟|金钥匙1加1|每日10分钟|30分钟狂练|学考A加卷|天天5分钟|领航1卷通|假期A计划|开心1卷通|B卷周计划|华夏1卷通|神舟第1卷|期末第1卷|金榜1卷通|2点备考案|本土第1卷|中考A计划|新编金3练|海淀1号卷|课时A计划|概念1地图|E通练加考|课时1卷通|中考红8套|暑假1本通|预测卷6套|全优好卷|全优课堂|课业达标|龙门活页|假期园地|本真试卷|词语手册|开心寒假|中考夺分|自我提升|优化设计|思维点拨|天天向上|火线100天|寒假作业|精英英语|寒假计划|金典课堂|课时必胜|精典练习|阅读拓展|应用题卡|高效作业|暑假作业|闯关中考|衔接教材|精讲精练|阅读成长|全能提分|创新学案|巧练提分|快乐阅读|活动手册|超越假期|寒假乐园|七彩假期|精确制导|中考题库|课标新卷|单元测评|三优夺标|全程加能|毕业升学|口算题卡|暑假计划|单元双测|周末培优|百分金卷|校本作业|创新课堂|优化课堂|暑假乐园|假期乐园|课时作业|名校考题|假期作业|快乐暑假|试题研究|优化中考|全优考卷|习题e百|闯关100分|全能100分|课堂点睛|暑假衔接|一本好卷|精华讲堂|精彩假期|计算高手|课程导报|打好基础|每课必练|冲刺100分|超越中考|状元笔记|全优训练|习题精选|达标训练|决胜中考|培优必练|同步阅读|赢在假期|期末优选|绝对名师|快乐假期|学习评价|衔接捷径|语文阅读|超越训练|浙江考题|开心夺冠|开心教程|踩点夺分|名校密参|数法题解|寒假生活|全优设计|智能训练|点击中考|探究学案|同步训练|快乐学习|小题狂练|课时先锋|巧学巧练|精典考卷|单科集训|凯翔英语|阶梯计算|三维设计|一课一练|师大名卷|预学寓练|奇速英语|绩优中考|阅读在线|抢先起跑|全通学案|课堂追踪|学海导航|假期生活|课时笔记|教学大典|活力英语|出彩阅读|中考必备|英才教程|培优辅导|导学教程|考前一搏|智慧阅读|京城名题|题库精选|四清导航|优加密卷|品优课堂|主题读写|练习精编|英语阅读|上海试卷|创优作业|基础训练|中考巨匠|课课过关|阳光课堂|暑假评测|指点中考|点拨训练|培优名卷|快乐寒假|一课二读|口算能手|北大绿卡|绩优学案|同步练习|快乐之星|动感课堂|火线计划|教材解析|期末寒假|课时特训|学业测评|专项训练|橙色寒假|超级课堂|日清周练|左讲右练|赢在课堂|课时训练|自助课堂|课课达标|激活中考|学英语报|蓉城学堂|作业辅导|假期冲浪|每课一练|导思学案|课课练习|培优一号|知识集锦|最新中考|赢在寒假|百练百胜|课堂作业|假期学苑|伴你成长|中考闯关|金榜学案|导学导练|益智课堂|课堂精炼|100分闯关|浙江期末|一本搞定|综合素质|金卷100分|导学精练|课时测控|高效课堂|课堂直播|自主课堂|中考方略|同步学典|组合训练|语文活页|课时精练|智慧测评|假期伙伴|智慧课堂|名师金典|开心暑假|课时全练|课堂全解|基础精练|活页检测|经典密卷|优学优练|课时设计|中考航标|启智课堂|名师导练|百分导学|课时学案|快乐假日|名师点拔|完美课堂|朗朗阅读|金牌解析|七彩练霸|试题探究|全优计划|名校秘题|同步练测|哈皮暑假|精彩练习|学霸训练|阅读完形|期末冲刺|完美假期|点金中考|英才园地|智慧学堂|探究导学|能力评价|学考传奇|课时掌握|同步导学|自能导学|金牌互动|核心课堂|课时测评|导学同步|真卷精编|教材新解|学海乐园|一品中考|英语指导|快乐口算|课时达标|联动课堂|学习方法|赢在中考|领先中考|龙江中考|胜券在握|课堂达优|晨读英语|状元陪练|全能集训|课堂练习|名师名卷|师说中考|名师指导|快乐夺冠|学考教程|优化指导|导学课堂|百分计划|寒假衔接|世纪金榜|名师助学|阳光夺冠|寒假突破|高分攻略|趣味课堂|名师点金|胜卷在握|天府优学|高分宝典|浙江名卷|优化学案|期末复习|励耘活页|指导用书|口算速算|高分必刷|过关精练|一卷搞定|核心期末|直击期末|同步习作|高效复习|全程测控|优加课堂|口算应用|听读导航|提分教练|第一作业|模拟试卷|聚焦课堂|快捷英语|小题快练|活力试卷|同步学案|巧思妙算|评价手册|教材全解|鼎尖训练|真题集锦|阅读训练|琢玉计划|行知天下|轻松暑假|随堂一测|成长资源|全优学习|暑假园地|主题英语|本土作业|分级夺冠|小卷狂练|每时每课|迎考特训|轻巧夺冠|单元考王|全优考典|开心作业|名题训练|一线课堂|阅读空间|百分易卷|寒假特训|中考突破|假日乐园|教材课本|提升训练|补充习题|奥数点拨|赢在微点|一卷通关|乐学课堂|中考专家|赢在期末|单元金卷|轻松小卷|快乐课堂|自选作业|春风阅读|天梯学案|中考攻略|渗透教材|暑假生活|导学测评|活力假期|期末闯关|权威考卷|高考调研|中考解读|复习计划|全优假期|初中学案|期末100分|成长阅读|阅读导航|成功阶梯|精彩课堂|主体课堂|家校导学|教学探研|名校作业|探究课堂|词汇专练|领航中考|全真模拟|考题研究|全能金卷|状元作业|本土暑假|领跑中考|口算心法|晓岚英语|阶梯作业|中考精典|试题解读|题库新编|默写能手|假日数学|随堂练习|轻负高效|一飞冲天|龙江名师|名师优卷|假日英语|中华题王|课时导航|一本到位|龙门之星|假日语文|课时点津|龙门专题|蓉城学霸|完形填空|立体设计|完全作业|寒假园地|默写高手|树人练案|浙江考卷|单元检测|高效练习|学在荆州|课时双测|夺冠金卷|青海中考|中考夺标|精点试题|随堂学案|中考内参|互动课堂|学习之友|高分中考|中考押题|暑假专刊|春如金卷|必会必考|突破课堂|互动同步|期末竞优|我为题狂|高效智能|学习检测|奥数夺冠|完美读法|中考备战|全效学习|学生周报|随堂练123|美文赏读|考前必练|快乐练习|赢战中考|假期之友|夺冠密题|随堂小测|随堂新卷|名师题库|百分阅读|课时掌控|名师一号|家庭作业|走向中考|导学练习|学力水平|考点解析|课课优优|课时方案|捷径训练|期末金卷|每周一考|名卷天下|期末红100|金榜中考|本土练霸|全能检测|课外作业|培优训练|知识清单|课堂导练|同步导练|黄金假日|终极学案|卓越英语|必备好卷|达标测试|真题集训|效率阅读|精选题库|天梯阅读|全程金卷|领航课堂|金榜一号|核心考卷|期末赢家|浙江中考|一本必胜|中考秘籍|单元突破|进阶集训|天下中考|练习册吉|优品中考|单元测试|名师金卷|轻轻松松|亮点给力|默写达人|读写有方|名师测控|培优100分|假日综合|计算能手|实验作业|无敌战卷|实验报告|沸腾英语|假日知新|优生乐园|实验手册|精彩暑假|名师学案|上海新卷|课程导学|阅读教程|全优读本|乐享课堂|步步通优|同步点拨|领军中考|阳光假期|中考冲刺|本土名卷|口算大卡|一路菁英|英才计划|同步首选|决战中考|天府数学|读写双优|天府前沿|提优密卷|卓越课堂|全能好卷|期末暑假|大语考卷|中考先锋|课时点睛|争分多榜|全能练考|全程突破|小考专家|阳光假日|听课笔记|期末宝典|激情英语|名师点拨|完全考卷|期末调研|名校学案|解题高手|状元导练|尚文阅读|阶梯训练|巅峰阅读|浙江好卷|拓展检测|激活思维|试题精编|探究乐园|创新方案|重庆中考|读写天下|期末好卷|小考神童|魔法教程|状元阅读|渡舟阅读|中考导练|课时宝典|新新学案|自主训练|旺子成龙|放心读写|快乐导航|提优名卷|优化探究|升学锦囊|中考档案|七彩课堂|非常阅读|晨读晚练|问题引领|课堂同步|课堂精练|假期导航|实验训练|先锋题典|阅读给力|专项测练|江苏正卷|学业评价|自主读本|左记右练|课堂小练|我爱阅读|给力阅读|全程导练|经典导学|解题决策|名师名题|随堂口算|追击中考|寒假在线|哈佛英语|追击小考|培优好题|随堂演练|归类集训|课堂达标|状元计划|小考必做|语法学案|典范阅读|有效课堂|五练一测|暑假集训|众享教育|数学指导|小考实战|同步三练|中考壹线|江苏密卷|火辣英语|名校密题|集优方案|乐学训练|优佳好卷|仁爱英语|听力课堂|精美课堂|高原学子|衔接课程|同步英语|巴蜀学案|金牌阅读|优化方案|简单英语|试题优化|填充图册|综合检测|地道中考|锦绣文章|夺冠100分|学习方案|龙门书局|优学训练|地理图册|语文题卡|同步作文|培优讲义|第一测评|必练密题|图解速记|学冠之星|英才点津|吉林专版|智慧讲堂|小考宝典|激活课堂|奇迹课堂|金榜课堂|随走随练|质量检测|点睛学案|寒假练练|湘教考苑|金典训练|复习指导|亮点激活|期末点津|鼎新学案|快捷语文|金质课堂|课时培优|中考指南|百科讲坛|HelloEnglish|状元考案|考场制胜|探究手册|全程设计|金版课堂|英才考评|暑假天地|奥数教程|蓝卡英语|中考导航|一卷夺冠|综合复习|随堂检测|寒假攻略|优加攻略|升学必备|听力突破|单元自测|发现会考|单元巧练|时事政治|非常听力|仿真试卷|语法专练|创新练习|榜上有名|剑桥英语|南通小题|达标金卷|拓展训练|每日精练|配套练习|学习指导|随堂小练|欢乐寒假|学海风暴|名校课堂|好题真卷|阅读快车|神机妙算|阳光计划|发现中考|本土学练|迈向牛津|作业优化|高分计划|全能课堂|寒假天地|小题狂做|聊城中考|效率署假|优佳学案|课堂之翼|高效测评|变式训练|状元及第|中考前沿|博睿英语|假期驿站|奔腾英语|致胜中考|博师在线|标准阅读|川蜀中考|名师原创|经纬考场|全程助学|同步奥数|考场英语|名师计划|考前辅导|步步为赢|全程解读|五读俱全|快乐考卷|绿色假期|高效中考|学习中考|思维体操|全程领航|课堂导学|无敌卷王|课时优化|夺冠计划|效率寒假|数学宝典|作业精编|培优竞赛|知然阅读|取胜通关|专题讲练|小考题典|练习部分|超级考卷|制胜密码|清华北大|长沙中考|聚焦中考|超级培优|湘岳中考|上海特训|中考全案|假期测评|强化训练|互动英语|中考专项|一本全练|课堂前后|轻松寒假|三维阅读|中考学案|假日氧吧|提分作业|欢乐暑假|成功宝典|备考指南|动感假期|课课精练|高效考卷|好卷100分|广东中考|导学先锋|暑假特训|成功之路|英语口语|备考策略|试题调研|名师100分|学考精练|期末密卷|数学口算|名题金卷|中考类题|全优备考|考点扫描|奇迹试卷|暑期衔接|赢在暑假|金牌训练|提优作业|培优密卷|贵州中考|主题阅读|数海探究|小题精练|过关训练|创新中考|期末必备|完美学案|创意课堂|中考专题|优化训练|随堂小考|晨诵两练|地方专版|学海高手|随堂作业|优加精卷|名校计划|层层递进|巴蜀英才|指针寒假|阅读风暴|水平测试|品学双优|蓉城中考|中考高手|仁爱地理|经典课堂|教材金练|课时检测|阅读高分|中考调研|教材全析|创优学案|课内课外|金榜夺冠|渔夫阅读|变式提优|和谐假期|牛津英语|优化全练|教材全练|拓展阅读|中考专辑|考点狂练|名师解密|中考锦囊|字词句篇|中考宝典|学习笔记|教材解读|中考状元|河南中考|创新教程|课堂手册|阅读旗舰|掌控中考|细解巧练|课时金练|金版教程|阅读授之|学生用书|高效学案|探究100分|黄冈名卷|命题研究|名师彩卷|阅读之星|金牌导学|悦读联播|思维训练|聚焦小题|绩优课堂|名师精编|寒假集训|名师导学|考点集训|金榜行动|暑假在线|立体期末|名校绿卡|江苏好卷|中考加分|点拨中考|学业观察|奥赛课本|学业参考|妙解教材|名师导航|满分阅读|剑指中考|名校夺冠|上海作业|读写突破|名校冲刺|教材详解|巅峰备考|非练不可|目标测试|语文读本|智慧中考|赢在高考|名校考卷|智解中考|专题突破|培优好卷|状元100分|专家伴读|全优考王|名师好卷|非常学案|课堂点拨|至尊听力|晨读晚记|期末在线|导学阶梯|金牌教练|同步精练|四项专练|优势阅读|测评创新|全效课堂|六月冲刺|顶尖训练|同步口算|秒杀小题|中考亮剑|全程测试|名冠金典|顶尖卷王|名师点津|全通练案|黄冈课堂|佳一数学|寒假专版|小卷实战|一天一练|走向假期|川行中考|早读手册|黄冈密卷|助学读本|效率暑假|定位中考|假期伴学|开心英语|会考指要|学习指要|寒假实训|非常考生|育才金典|课堂在线|中考导学|名师密卷|双成卷王|泛听泛读|我的笔记|暑假培优|教材快线|优质课堂|乐学阅读|分层作业|高效精练|期末精华|暑假专版|中考指导|精析巧练|阳光阅读|汉江中考|满分试卷|假期特训|轻松课堂|暑假课堂|精彩寒假|快乐成长|葵花宝典|中考实战|考前突破|妙语短篇|实战中考|一品设计|每课100分|阶梯听力|哈皮寒假|快乐衔接|金榜之路|鼎尖阅读|全效测评|内部讲义|优化作业|小题巧练|同步学习|金典育才|南昌二中|暑假习训|课时夺冠|口算训练|金点中考|阅读课堂|作文教程|微点特训|开心练习|阅读方舟|综合自测|寒假评测|专项练习|南通密卷|阅读100篇|中考对策|有效作业|非常习题|培优计划|本土寒假|绿色阅读|假期冲冠|情景导学|专项突破|长江学典|赢在燕赵|名校密卷|假期时光|黄金周卷|三点一测|活页练习|热点测试|中考指要|一课四练|高速课堂|中考金卷|短文填空|名校导练|课程全测|快乐驿站|会考通关|目标检测|海淀金卷|自主练习|本土精编|备考点拨|同步双测|知识绿卡|第一微卷|课时集训|锦囊妙解|金榜AB卷|精彩60天|学考2加1|随堂1加2|密解1对1|名题1加1|非常1加1|培优60课|53随堂测|教材1加1|双测AB卷|同步AB卷|53天天练|领先AB卷|中考3加2|随堂1加1|最新AB卷|金题1加1|快乐AB卷|中考6加1|中考1加1|B卷必刷|通城1典|全A计划|五E课堂|每课1练|1线超越|名校1号|第1课堂|A级口算|A加金题|金卷1号|暑假BOOK|B卷狂练|A加练案|夺A计划|中考2号|蓝色A典|期末1卷|练习册|课课通|中考通|课时练|冲刺王|大联考|讲与练|一遍过|标准卷|课课练|好帮手|冲刺卷|大中考|状元坊|练闯考|佳分卷|错题本|课后练|作业本|测试卷|指南针|加加练|一卷通|导学练|易百分|课堂练|新假期|小学霸|中考168|课课帮|学霸123|小秘招|导学案|导与练|神算手|新思维|帮你学|学练考|金考卷|优选卷|练测考|赢在100|优练测|点金卷|题练王|口算100|中考211|学练优|快乐园|新学练|学与练|风向标|期末卷|堂堂清|易学练|一本通|给力100|高效通|练加考|口算本|新中考|棒棒堂|新观察|新领程|新资源|全优卷|月考卷|互动园|听说练|勤学早|提高班|数理报|周周测|讲练测|随堂练|智慧鸟|课时卷|预测卷|单元练|总复习|头名卷|金试卷|特训卷|新阅读|应用题|大冲关|53English|榜中榜|优题库|伴你学|黑白题|新课堂|奥赛王|新学案|零距离|地图册|高考帮|教材帮|核按钮|微课堂|中考王|本土卷|满分王|跟我学|课堂360|新视野|夺分王|创优练|一考通|一点通|导与学|一练通|中考360|新语思|中考易|简易通|专题王|丢分题|全易通|学科王|集结号|培优卷|一卷OK|1课1练|PK中考|1课3练|全A加|课本|刷题|一本|好卷|汇练|乐思|学霸|题粹|师说|点金|听练|同行|考前|名题|学案|阅读';
        $title=preg_replace_callback('#'.$preg0.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        $title=preg_replace_callback('#'.$preg1.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        $title=preg_replace_callback('#'.$preg2.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        $title=preg_replace_callback('#'.$preg3.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        $title=preg_replace_callback('#'.$preg4.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        $title=preg_replace_callback('#'.$preg5.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        preg_replace_callback('#'.$preg6.'#i',function ($m){$this->main_words[]=$m[0];return '';},$title);
        //print_r($this->main_words);
        return array_unique($this->main_words);
    }


    public function img2thumb($src_img, $dst_img, $width = 1000, $height = 0, $cut = 0, $proportion = 0)//缩略图 最大宽度限定到1000
    {
        $srcinfo = getimagesize($src_img);
        if(!$srcinfo){@unlink($src_img);return false;}//如果不是图片则删除原始文件
        $savepath=dirname($dst_img);
        if(!is_dir($savepath)) mkdir($savepath,0777,true);
        $ot = pathinfo($dst_img, PATHINFO_EXTENSION);
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);

        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
        if($src_w<$src_h && $width>1000) $width=1000;//解决除用于周报外的图片最大宽度
        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;

        /**
         * 缩略图不超过源图尺寸（前提是宽或高只有一个）
         */
        if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
        {
            $proportion = 1;
        }
        if($width> $src_w)
        {
            $dst_w = $width = $src_w;
        }
        if($height> $src_h)
        {
            $dst_h = $height = $src_h;
        }

        if(!$width && !$height && !$proportion)
        {
            return false;
        }
        if(!$proportion)
        {
            if($cut == 0)
            {
                if($dst_w && $dst_h)
                {
                    if($dst_w/$src_w> $dst_h/$src_h)
                    {
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    }
                    else
                    {
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                }
                else if($dst_w xor $dst_h)
                {
                    if($dst_w && !$dst_h)  //有宽无高
                    {
                        $propor = $dst_w / $src_w;
                        $height = $dst_h  = $src_h * $propor;
                    }
                    else if(!$dst_w && $dst_h)  //有高无宽
                    {
                        $propor = $dst_h / $src_h;
                        $width  = $dst_w = $src_w * $propor;
                    }
                }
            }
            else
            {
                if(!$dst_h)  //裁剪时无高
                {
                    $height = $dst_h = $dst_w;
                }
                if(!$dst_w)  //裁剪时无宽
                {
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int)round($src_w * $propor);
                $dst_h = (int)round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        }
        else
        {
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width  = $dst_w = $src_w * $proportion;
        }

        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        if(function_exists('imagecopyresampled'))
        {
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        else
        {
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }

    public function insert_answer($path,$book_id,$book_code)
    {
        $bookpre="D:/wamp64/www/vzytest/storage/app/public/offical_answer_pic/";
        $bookimgdir=$bookpre.$book_id;
        $i=1;
        $all_files = \File::allFiles($path);
        foreach ($all_files as $file){
            //$picpath = \File::basename($file);
            //$picpath=$file->getRealPath();
            //echo $picpath."\n";
            $daandir=app('pinyin')->abbr(\File::name($path));//答案目录名称
            if(!is_dir($bookimgdir.'/'.$daandir)) mkdir($bookimgdir.'/'.$daandir,0777,true);
            $ext= \File::extension($file);
            $picname=md5_file($file);
            $pic_rel="pic19/".$book_id."/".$daandir."/".$picname.'.'.$ext;
            $new_picpath="{$bookimgdir}/{$daandir}/".$picname.'.'.$ext;
            //\File::copy($file, $bookimgdir.'/'.$daandir);
            //copy($picpath,$new_picpath);
            $this->img2thumb($file,$new_picpath,1000);//大图转换为最大宽度1000 需要看转换的效果
            $md5answer=md5($pic_rel);
//            if($i==0){
//                AWorkbook1010::where(['id'=>$book_id])->update(['cover'=>config('workbook.thumb_image_url').$pic_rel]);
//            }else{
            WorkbookAnswer::create([
                "bookid"=>$book_id,
                "answer"=>$pic_rel,
                "md5answer"=>$md5answer,
                "text"=>$i,
                "textname"=>"第{$i}页",
                "book"=>$book_code
            ]);
//            }
            $i++;
        }
    }
}
