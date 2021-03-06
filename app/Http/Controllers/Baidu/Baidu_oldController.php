<?php

namespace App\Http\Controllers\Baidu;

use App\AWorkbookMain;
use App\Baidu;
use App\BaiduHash;
use App\BaiduNew;
use App\BookVersionType;
use App\Volume;
use App\WorkbookAnswer;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class BaiduOldController extends Controller
{
  public function index($time = 0, $grade_id = 0, $subject_id = 0, $volume_id = 0, $version_id = -1, $sort_id = -999)
  {
    $old_day = date('Y_m_d',time()-86400);
    $now_day = date('Y_m_d',time());

    if($time===0){
      $time = $old_day.'__'.$now_day;
    }
    $now = explode('__', $time);
    if (!is_array($now) || count($now) != 2) {
      die('错误日期');
    }

    $start = strtotime(str_replace('_', '/', $now[0]));
    $end = strtotime(str_replace('_', '/', $now[1]));

    $info = array();
    if ($grade_id > 0) {
      $info[] = ['grade_id', '=', $grade_id];
    }
    if ($subject_id > 0) {
      $info[] = ['subject_id', '=', $subject_id];
    }
    if ($volume_id > 0) {
      $info[] = ['volume_id', '=', $volume_id];
    }
    if ($version_id >= 0) {
      $info[] = ['version_id', '=', $version_id];
    }
    if ($sort_id > -1) {
      $info[] = ['sort_id', '=', $sort_id];
    }
    $data['grade_id'] = $grade_id;
    $data['subject_id'] = $subject_id;
    $data['volume_id'] = $volume_id;
    $data['version_id'] = $version_id;
    $data['sort_id'] = $sort_id;

    $where[] = ['book_id', '>', 0];
    $where[] = ['date_now', '>=', $start];
    $where[] = ['date_now', '<=', $end];

    $data['all_version'] = Cache::remember('all_version', 120, function () {
      return BookVersionType::all(['id', 'name', 'press_name', 'press_alias', 'district']);
    });

    $data['all_volumes'] = Cache::remember('all_volumes', 120, function () {
      return Volume::all(['id', 'volumes']);
    });

    $data['all_version'] = collect($data['all_version']);
    $data['all_volumes'] = collect($data['all_volumes']);


    foreach ($data['all_version'] as $key => $value) {
      $version_array[$key]['id'] = $value->id;
      $version_array[$key]['text'] = $value->name;
    }
    foreach ($data['all_volumes'] as $key => $value) {
      $volume_array[$key]['id'] = $value->id;
      $volume_array[$key]['text'] = $value->volumes;
    }
    foreach (config('workbook.grade') as $key => $value) {
      if ($key > 0) {
        $grade_array[$key - 1]['id'] = $key;
        $grade_array[$key - 1]['text'] = $value;
      }
    }
    foreach (config('workbook.subject_1010') as $key => $value) {
      if ($key > 0) {
        $subject_array[$key - 1]['id'] = $key;
        $subject_array[$key - 1]['text'] = $value;
      }
    }

    $data['version_select'] = json_encode($version_array);
    $data['subject_select'] = json_encode($subject_array);
    $data['grade_select'] = json_encode($grade_array);
    $data['volume_select'] = json_encode($volume_array);

    $data['start'] = str_replace('_', '/', $now[0]);
    $data['end'] = str_replace('_', '/', $now[1]);
    $data['total'] = BaiduNew::where($where)->select('book_id', DB::raw('sum(visit_count) as all_pv'), DB::raw('sum(visitor_count) as all_uv'), DB::raw('sum(new_visitor_count) as new_uv'), DB::raw('avg(new_visitor_ratio) as new_visitor_ratio'), DB::raw('sum(ip_count) as ip_count'), DB::raw('sum(out_pv_count) as out_pv_count'), DB::raw('avg(bounce_ratio) as bounce_ratio'), DB::raw('avg(avg_visit_time) as avg_visit_time'),DB::raw('avg(avg_visit_pages) as avg_visit_pages'))->whereHas('has_book',function ($query) use ($info){
      $query->where($info);
    })->with(array('has_book'=>function($query) use ($info){
      if(!empty($info)){
        $query->where($info);
      }
      $query->select('id','bookname','grade_id','subject_id','version_id','volumes_id','sort');
    }))->with('has_book.has_sort:id,name')->groupBy('book_id')->orderBy('all_pv', 'desc')->paginate(20);
    $data['min'] = date('Y-m-d', BaiduNew::min('date_now'));
    $data['max'] = date('Y-m-d', BaiduNew::max('date_now'));



    return view('baidu.index', compact('data'));
  }


  public function question($time=0,$type=0)
  {

    $data['all_type']= ['xxyw'=>'小学语文','xxsx'=>'小学数学','xxyy'=>'小学英语','czyw'=>'初中语文','czsx'=>'初中数学','czyy'=>'初中英语','czwl'=>'初中物理','czhx'=>'初中化学','czsw'=>'初中生物','czdl'=>'初中地理','czls'=>'初中历史','czzz'=>'初中政治','gzyw'=>'高中语文','gzsx'=>'高中数学','gzyy'=>'高中英语','gzwl'=>'高中物理','gzhx'=>'高中化学','gzsw'=>'高中生物','gzdl'=>'高中地理','gzls'=>'高中历史','gzzz'=>'高中政治'];

    if(in_array($type, array_keys($data['all_type']))){
      $data['now_type'] = $type;
      $data['type_name'] = $data['all_type'][$type];
      $where[] = ['shiti_type','=',$type];
    }else{
      $data['now_type'] = 0;
      $data['type_name'] = '全部';
    }

    $old_day = date('Y_m_d',time()-86400);
    $now_day = date('Y_m_d',time());

    if($time===0){
      $time = $old_day.'__'.$now_day;
    }
    $now = explode('__', $time);
    if (!is_array($now) || count($now) != 2) {
      die('错误日期');
    }

    $start = strtotime(str_replace('_', '/', $now[0]));
    $end = strtotime(str_replace('_', '/', $now[1]));
    $data['start'] = str_replace('_', '/', $now[0]);
    $data['end'] = str_replace('_', '/', $now[1]);
    $data['min'] = Cache::rememberForever('min_data', function (){
      return date('Y-m-d', Baidu::min('date_now'));
    });
    $data['max'] = Cache::rememberForever('max_data', function (){
      return date('Y-m-d', Baidu::max('date_now'));
    });

    $where[] = ['has_shiti','=',1];
    $where[] = ['date_now', '>=', $start];
    $where[] = ['date_now', '<=', $end];
    $where[] = ['shiti_id','<>',''];
    $data['total'] = Baidu::where($where)->select('shiti_id','shiti_type', DB::raw('any_value(url) as url'),DB::raw('sum(pv) as all_pv'), DB::raw('sum(uv) as all_uv'), DB::raw('sum(cb) as all_cb'), DB::raw('sum(spend_time) as spend_time'))->groupBy('shiti_type','shiti_id')->orderBy('all_pv', 'desc')->paginate(20);
    return view('baidu.question', compact('data'));
  }


  public function api(Request $request)
  {
    Cache::forget('max_data');
    ignore_user_abort();
    set_time_limit(0);
    $type = $request->type;
    switch ($type) {
      case 'get_information':
        $max_time = $request->max_date;
        $now_time = $request->now_date;

//        $this->parse_file(storage_path('baidu/2017-12-09.csv'),1512777600);
//        return false;


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
        $cookie = new \GuzzleHttp\Cookie\CookieJar();
        $domain = 'tongji.baidu.com';
        $values = ['BAIDUID' => 'AC7442964AD00711E0BBA267F4E5FA2E:FG=1',
          'BIDUPSID' => 'AC7442964AD00711E0BBA267F4E5FA2E',
          'PSTM' => '1512705534',
          'PRISON_COOKIE' => '5a2a2de450539a1c39717f8a4e0',
          'uc_login_unique' => '386b0bc7a89ee0335c85e068305a9e6a',
          'uc_recom_mark' => 'cmVjb21tYXJrXzI0ODM0OTk2', 'Hm_ct_41fc030db57d5570dd22f78997dc4a7e' => '306*1*24834996', 'H_PS_PSSID' => '1456_21101_18559_17001_25178_20929', 'PSINO' => 2, 'SFSSID' => 'uadf6mnj9o8ah04lc156vhg1b4', 'Hm_lvt_41fc030db57d5570dd22f78997dc4a7e' => '1512717423,1512722142,1512782164,1512958028', 'hm_usertype' => 0, 'hm_username' => 'qweqweasd', 'SIGNIN_UC' => '70a2711cf1d3d9b1a82d2f87d633bd8a02629704811', '__cas__st__' => '559caf25b771981e70c333df3f54e8e8ff956f5f5a387b63679aa6d09d88d79d9f09ce90102609c364fd5b1d', '__cas__id__' => '24834996', 'Hm_lpvt_41fc030db57d5570dd22f78997dc4a7e' => '1512958143'];


        $a = json_decode(file_get_contents(base_path('baidu.cookie')), JSON_UNESCAPED_SLASHES);
        foreach ($a as $v) {
          $cookie->setCookie(new SetCookie([
            'Domain' => $v['domain'],
            'Name' => $v['name'],
            'Value' => $v['value'],
          ]));
        }
        //CookieJar::fromArray($cookies, $domain)
        //$cookie->setCookie($cookie)
        $http = new \GuzzleHttp\Client($header);

        $data['st'] = strtotime($max_time) . '000';
        $data['et'] = strtotime($now_time) . '000' + 86400000;
        $data['flag'] = 'overview';
        $data['format'] = 'csv';
        $data['indicators'] = 'pv_count,visitor_count,outward_count,exit_count,average_stay_time';
        $data['method'] = 'download/generate';
        $data['offset'] = '0';
        $data['order'] = 'pv_count,desc';
        $data['reportId'] = '14';
        $data['siteId'] = '4875889';
        $data['target'] = '-1';
        $real_start = $data['st'];
        if (($data['et'] - $data['st']) / 86400000 >= 1) {
          $days = ($data['et'] - $data['st']) / 86400000;
          for ($i = 1; $i < $days + 1; $i++) {
            $data['st'] = $real_start + 86400000 * ($i - 1);
            $data['et'] = $data['st'] + 86400000;
            $get_file = $this->get_data_hash($data, $http, $cookie);
            if ($get_file['status'] == 0) {
              return response()->json($get_file);
            }
          }
        }


//          $response = $http->request('get','https://tongji.baidu.com/web/24834996/download/fetch?siteId=4875889&queryId=614002d275b69c379eb25a26686c97f1',[
//            'cookies'=>$cookie,
//          ]);
    }
  }

  protected function get_data_hash($data, $http, $cookie)
  {

    $response = $http->request('post', 'https://tongji.baidu.com/web/24834996/ajax/post', [
      'form_params' => $data,
      'cookies' => $cookie,
    ]);
    //$re = json_encode();
    $re = json_decode($response->getBody());

    if ($re && $re->status == 0) {
      $hash['time'] = $data['et'] - 86400000;
      $hash['hash'] = $re->data;
      if(BaiduHash::where(['hash'=>$hash['hash'],'status'=>0])->count()>1){
        return ['status' => 0, 'msg' => '正在更新中'];
      }
      if(date('Y-m-d',substr($hash['time'], 0,10))==date('Y-m-d',time())){
        if(BaiduHash::where(['time'=>$hash['time'],'status'=>0])->count()>=1){
          return ['status' => 0, 'msg' => '正在更新中'];
        }
      }
      BaiduHash::create($hash);
      $hash_all[] = $re->data;
    } else {
      return ['status' => 0, 'msg' => '百度cookie已过期'];
    }
    foreach ($hash_all as $get) {
      $this->get_download($http, $cookie, $data['st'], $get);
    }
    return ['status' => 1, 'msg' => '成功'];
  }

  protected function get_download($http, $cookie, $time, $hash)
  {
    sleep(10);
    $time_now = date('Y-m-d', substr($time, 0, 10));
    try{
      $response = $http->request('get', 'https://tongji.baidu.com/web/24834996/download/fetch?siteId=4875889&queryId=' . $hash, [
        'cookies' => $cookie,
        'sink' => storage_path('baidu/' . $time_now . '.csv')
      ]);
      if (strlen($response->getBody()->getContents()) > 500) {
        return $this->parse_file(storage_path('baidu/' . $time_now . '.csv'), substr($time, 0, 10));
      }else{
        $this->get_download($http, $cookie, $time, $hash);
      }
    }catch (Exception $e){
      var_dump($e);
    }

  }

  protected function parse_file($file, $time)
  {
    Baidu::where('date_now', $time)->delete();
    $file = fopen($file, "r");
    while (!feof($file)) {
      $data = fgetcsv($file);
      if (intval($data[0]) > 0 && strstr($data[1], 'http')!=NULL) {
        $book_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/daan\/bookid\_(\d+)\.html(.*?)/', $data[1], $match_books);
        $chapter_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/daan\/chapter\_(\d+)\.html(.*?)/', $data[1], $match_chapters);
        //http://www.1010jiajiao.com/gzyy/shiti_id_f2f95639822b6362098c07eea7a95d91
        $shiti_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/(.*?)\/shiti_id_([a-z0-9]{32})/', $data[1],$match_shiti);
        if ($book_url) {
          $baidu['book_id'] = $match_books[1];
          $this->insert_data($data, $baidu['book_id'], $time);
        } elseif ($chapter_url) {
          $chapter_id = $match_chapters[1];
          $book_id = WorkbookAnswer::where('id', $chapter_id)->select('bookid')->first();
          if (count($book_id) > 0) {
            $this->insert_data($data, $book_id->bookid, $time);
          }
        }elseif($shiti_url) {
          $shiti['type'] = $match_shiti[1];
          $shiti['id'] = $match_shiti[2];
          $this->insert_shiti($data,$shiti,$time);
        }else{
          $this->insert_data($data, 0, $time);
        }
      }
    }
    fclose($file);
    BaiduHash::where('time',$time.'000')->update(['status'=>1]);
  }

  protected function insert_data($data, $book_id, $time)
  {
    if($book_id==0){
      $bookinfo = [];
    }else{
      $bookinfo = AWorkBookMain::find($book_id, ['bookname', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'sort']);
    }

    if (count($bookinfo) > 0) {
      $baidu['book_id'] = $book_id;
      $baidu['book_name'] = $bookinfo->bookname;
      $baidu['grade_id'] = $bookinfo->grade_id;
      $baidu['subject_id'] = $bookinfo->subject_id;
      $baidu['volume_id'] = $bookinfo->volumes_id;
      $baidu['version_id'] = $bookinfo->version_id;
      $baidu['sort_id'] = $bookinfo->sort;
    }
    try {

      $baidu['url'] = $data[1];
      $baidu['pv'] = str_replace(',', '', $data[2]);
      $baidu['uv'] = str_replace(',', '', $data[3]);
      $baidu['cb'] = str_replace(',', '', $data[4]);
      $baidu['exit'] = str_replace(',', '', $data[5]);
      $t1 = strtotime("2011-01-01 " . $data[6]);
      $t2 = strtotime("2011-01-01 00:00:00");
      $baidu['spend_time'] = $t1 - $t2;
      $baidu['date_now'] = $time;

      Baidu::create($baidu);
    } catch (Exception $e) {
      var_dump($e);
    }
    return ['status'=>1,'msg'=>'正在更新'];
  }

  protected function insert_shiti($data,$shiti,$time)
  {
    try {
      $baidu['url'] = $data[1];
      $baidu['pv'] = str_replace(',', '', $data[2]);
      $baidu['uv'] = str_replace(',', '', $data[3]);
      $baidu['cb'] = str_replace(',', '', $data[4]);
      $baidu['exit'] = str_replace(',', '', $data[5]);
      $t1 = strtotime("2011-01-01 " . $data[6]);
      $t2 = strtotime("2011-01-01 00:00:00");
      $baidu['spend_time'] = $t1 - $t2;
      $baidu['date_now'] = $time;
      $baidu['shiti_id'] = $shiti['id'];
      $baidu['shiti_type'] = $shiti['type'];
      $baidu['has_shiti'] = 1;
      Baidu::create($baidu);
    }catch (Exception $e) {
      var_dump($e);
    }
    return ['status'=>1,'msg'=>'正在更新'];
  }
}
