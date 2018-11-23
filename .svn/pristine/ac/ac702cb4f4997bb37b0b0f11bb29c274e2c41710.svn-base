<?php

namespace App\Http\Controllers\Baidu;

use App\AWorkbookMain;
use App\BaiduHash;
use App\BaiduNewDaan;
use App\WorkbookAnswer;
use Cache;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use Psy\Exception\Exception;

class GetDataController extends Controller
{
  public function api(Request $request)
  {
    Cache::forget('max_data');
    ignore_user_abort();
    set_time_limit(0);
    ini_set('memory_limit', -1);
    $type = $request->type;
    switch ($type) {
      case 'get_information':
        $max_time = $request->max_date;
        $now_time = $request->now_date;
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

        $data['st'] = strtotime($max_time) . '000';
        $data['et'] = strtotime($now_time) . '000' + 86400000;
        $data['et2'] = '';
        $data['st2'] = '';
        $data['flag'] = 'overview';
        $data['flashIndicators'] = 'out_pv_count';
        $data['format'] = 'csv';
        $data['indicators'] = 'visitor_count,out_pv_count,visit_count,avg_visit_pages,avg_visit_time,bounce_ratio,ip_count,new_visitor_count,new_visitor_ratio';
        $data['method'] = 'download/generate';
        $data['offset'] = '0';
        $data['order'] = 'visit_count,desc';
        $data['pageSize'] = 20;
        $data['reportId'] = '15';
        $data['searchWord'] = '';

        $all_dir = ['8229526' => 'daan', '4967357' => 'xxyw', '4967358' => 'xxsx', '4967359' => 'xxyy', '4967326' => 'czdl', '4967328' => 'czhx', '4967330' => 'czls', '4967332' => 'czsw', '4967335' => 'czsx', '4967336' => 'czwl', '4967337' => 'czyw', '4967338' => 'czyy', '4967339' => 'czzz', '4967340' => 'gzdl', '4967344' => 'gzhx', '4967345' => 'gzls', '4967349' => 'gzsw', '4967350' => 'gzsx', '4967351' => 'gzwl', '4967353' => 'gzyw', '4967354' => 'gzyy', '4967356' => 'gzzz', '8229519' => 'timu', '8229523' => 'timu3', '8229528' => 'xiti', '8230626' => 'qx_portal', '8229530' => 'yuedu'];
        $data['target'] = '-1';
        $real_start = $data['st'];
        $real_end = $data['et'];
        if (($real_end - $real_start) / 86400000 >= 1) {
          $days = ($real_end - $real_start) / 86400000;
          for ($i = 1; $i < $days + 1; $i++) {
            foreach ($all_dir as $key => $dir) {
              //$data['siteId'] = '4875889';
              $data['siteId'] = $key;
              for ($j = 1; $j < 4; $j++) {
                $data['st'] = $real_start + 86400000 * ($i - 1);
                $data['et'] = $data['st'] + 86400000;
                $data['sri'] = 1 + 10000 * ($j - 1);
                $data['eri'] = 10000 + 10000 * ($j - 1);
                if ($j > 1) {
                  $time_now = date('Y-m-d', substr($data['st'], 0, 10));
                  if (!is_file(storage_path('baidu/' . $time_now . '/' . $dir . '_' . intval($j - 1) . '.csv'))) {
                    continue;
                  }
                  $file = file(storage_path('baidu/' . $time_now . '/' . $dir . '_' . intval($j - 1) . '.csv'));
                  if (count($file) < 10000) {
                    continue;
                  }
                }
                $get_file = $this->get_data_hash($key, $dir, $data, $http);
                //storage_path('baidu/' . $time_now .'/'.$dir.'_'.$start.'.csv')
                if ($get_file['status'] == 0) {
                  return response()->json($get_file);
                }
              }
            }
          }
        }
//          $response = $http->request('get','https://tongji.baidu.com/web/24834996/download/fetch?siteId=4875889&queryId=614002d275b69c379eb25a26686c97f1',[
//            'cookies'=>$cookie,
//          ]);
    }
  }

  public function auto_update()
  {
    ignore_user_abort();
    set_time_limit(0);
    ini_set('memory_limit', -1);

    $max_time = BaiduNewDaan::max('date_now');
    $now_time = date('Y-m-d', time());
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

    $data['st'] = $max_time . '000';
    $data['et'] = strtotime($now_time) . '000' + 86400000;
    $data['et2'] = '';
    $data['st2'] = '';
    $data['flag'] = 'overview';
    $data['flashIndicators'] = 'out_pv_count';
    $data['format'] = 'csv';
    $data['indicators'] = 'visitor_count,out_pv_count,visit_count,avg_visit_pages,avg_visit_time,bounce_ratio,ip_count,new_visitor_count,new_visitor_ratio';
    $data['method'] = 'download/generate';
    $data['offset'] = '0';
    $data['order'] = 'visit_count,desc';
    $data['pageSize'] = 20;
    $data['reportId'] = '15';
    $data['searchWord'] = '';
    $all_dir = ['8229526' => 'daan', '4967357' => 'xxyw', '4967358' => 'xxsx', '4967359' => 'xxyy', '4967326' => 'czdl', '4967328' => 'czhx', '4967330' => 'czls', '4967332' => 'czsw', '4967335' => 'czsx', '4967336' => 'czwl', '4967337' => 'czyw', '4967338' => 'czyy', '4967339' => 'czzz', '4967340' => 'gzdl', '4967344' => 'gzhx', '4967345' => 'gzls', '4967349' => 'gzsw', '4967350' => 'gzsx', '4967351' => 'gzwl', '4967353' => 'gzyw', '4967354' => 'gzyy', '4967356' => 'gzzz', '8229519' => 'timu', '8229523' => 'timu3', '8229528' => 'xiti', '8230626' => 'qx_portal', '8229530' => 'yuedu'];


    $data['target'] = '-1';
    $real_start = $data['st'];
    $real_end = $data['et'];

    if (($real_end - $real_start) / 86400000 >= 1) {
      $days = ($real_end - $real_start) / 86400000;
      for ($i = 1; $i < $days + 1; $i++) {
        foreach ($all_dir as $key => $dir) {
          $data['siteId'] = $key;
          for ($j = 1; $j < 4; $j++) {
            $data['st'] = $real_start + 86400000 * ($i - 1);
            $data['et'] = $data['st'] + 86400000;
            $data['sri'] = 1 + 10000 * ($j - 1);
            $data['eri'] = 10000 + 10000 * ($j - 1);
            if ($j > 1) {
              $time_now = date('Y-m-d', substr($data['st'], 0, 10));
              if (!is_file(storage_path('baidu/' . $time_now . '/' . $dir . '_' . intval($j - 1) . '.csv'))) {
                continue;
              }
              $file = file(storage_path('baidu/' . $time_now . '/' . $dir . '_' . intval($j - 1) . '.csv'));
              if (count($file) < 10000) {
                continue;
              }
            }
            $get_file = $this->get_data_hash($key, $dir, $data, $http);
            if ($get_file['status'] == 0) {
              return response()->json($get_file);
            }
          }
        }
      }
    }
  }

  protected function get_data_hash($siteId, $dir, $data, $http)
  {
    $cookieFile = base_path('baidu.cookie');
    $cookie = new \GuzzleHttp\Cookie\FileCookieJar($cookieFile, true);
    $response = $http->request('post', 'https://tongji.baidu.com/web/24834996/ajax/post', [
      'form_params' => $data,
      'cookies' => $cookie,
    ]);
    //$re = json_encode();
    $re = json_decode($response->getBody());

    if ($re && $re->status == 0) {
      $hash['time'] = $data['et'] - 86400000;
      $hash['hash'] = $re->data;
      $hash['dir'] = $dir;
      if ($data['sri'] == 1) {
        $hash['start'] = 1;
      } else {
        $hash['start'] = 2;
      }
      if (BaiduHash::where(['hash' => $hash['hash'], 'status' => 0])->count() > 0) {
        return ['status' => 0, 'msg' => '正在更新中'];
      }
      if (date('Y-m-d', substr($hash['time'], 0, 10)) == date('Y-m-d', time())) {
        if (BaiduHash::where(['time' => $hash['time'], 'status' => 0, 'start' => $hash['start']])->count() >= 1) {
          return ['status' => 0, 'msg' => '正在更新中'];
        }
      }
      BaiduHash::create($hash);
      $hash_all[] = $re->data;
    } else {
      return ['status' => 0, 'msg' => '百度cookie已过期'];
    }
    foreach ($hash_all as $get) {
      $this->get_download($siteId, $dir, $http, $cookie, $data['st'], $hash['start'], $get);
    }
    return ['status' => 1, 'msg' => '成功'];
  }

  protected function get_download($siteId, $dir, $http, $cookie, $time, $start, $hash)
  {

    sleep(random_int(1, 10));
    $time_now = date('Y-m-d', substr($time, 0, 10));
    if (!is_dir(storage_path('baidu/' . $time_now))) {
      mkdir(storage_path('baidu/' . $time_now));
      chmod(storage_path('baidu/' . $time_now), 0777);
    }

    try {
      if (is_file(storage_path('baidu/' . $time_now . '/' . $dir . '_' . $start . '.csv'))) {
        unlink(storage_path('baidu/' . $time_now . '/' . $dir . '_' . $start . '.csv'));
      }
      $response = $http->request('get', 'https://tongji.baidu.com/web/24834996/download/fetch?siteId=' . $siteId . '&queryId=' . $hash, [
        'cookies' => $cookie,
        'sink' => storage_path('baidu/' . $time_now . '/' . $dir . '_' . $start . '.csv')
      ]);
      if (strlen($response->getBody()->getContents()) > 120) {
        return $this->parse_file($dir, $time_now, $start, substr($time, 0, 10));
      } else {
        $this->get_download($siteId, $dir, $http, $cookie, $time, $start, $hash);
      }
    } catch (Exception $e) {
      var_dump($e);
    }

  }

  protected function parse_file($dir, $time_now, $start, $time)
  {
    $file = storage_path('baidu/' . $time_now . '/' . $dir . '_' . $start . '.csv');
    try {
      chmod($file, 0666);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    $now_table = 'baidu_new_' . $dir;
    DB::connection('mysql_local')->table($now_table)->where(['date_now' => $time, 'dir' => $dir, 'page_now' => $start])->delete();
    $file = fopen($file, "r");
    while (!feof($file)) {
      $data = fgetcsv($file);
      if (intval($data[0]) > 0 && isset($data[2]) && intval($data[2]) > 0 && isset($data[1]) && strstr($data[1], 'http') != NULL) {
        $book_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/daan\/bookid\_(\d+)\.html(.*?)/', $data[1], $match_books);
        $chapter_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/daan\/chapter\_(\d+)\.html(.*?)/', $data[1], $match_chapters);
        //http://www.1010jiajiao.com/gzyy/shiti_id_f2f95639822b6362098c07eea7a95d91
        $shiti_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/(.*?)\/shiti_id_([a-z0-9]{31,33})(.*?)/', $data[1], $match_shiti);

        $timu_id_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/timu_id\_(\d+)(.*?)/', $data[1], $match_timu);
        $timu3_id_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/timu3_id\_(\d+)(.*?)/', $data[1], $match_timu3);
        $xiti_id_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/xiti_id\_(\d+)(.*?)/', $data[1], $match_xiti);

        $portal_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/qx_portal\/doc\/(\d+)(.*?)/', $data[1], $match_portal);
        $yuedu_url = preg_match('/http:\/\/www\.1010jiajiao\.com\/yuedu_id_(\d+)(.*?)/', $data[1], $match_yuedu);

        if ($book_url) {
          $baidu['book_id'] = $match_books[1];
          $this->insert_data($dir, $data, $baidu['book_id'], 0, $time, $start);
        } elseif ($chapter_url) {
          $chapter_id = $match_chapters[1];
          $book_id = WorkbookAnswer::where('id', $chapter_id)->select('bookid')->first();
          if (count($book_id) > 0) {
            $this->insert_data($dir, $data, $book_id->bookid, $chapter_id, $time, $start);
          }
        } elseif ($shiti_url) {
          $shiti['type'] = $match_shiti[1];
          $shiti['id'] = $match_shiti[2];
          $this->insert_shiti($dir, $data, $shiti, $time, $start);
        } elseif ($timu_id_url) {
          $timu_id = $match_timu[1];
          $this->insert_data($dir, $data, 0, 0, $time, $start, $timu_id, 0, 0);
        } elseif ($timu3_id_url) {
          $timu3_id = $match_timu3[1];
          $data['timu3_id'] = $timu3_id;
          $this->insert_data($dir, $data, 0, 0, $time, $start, 0, $timu3_id, 0);
        } elseif ($xiti_id_url) {
          $xiti_id = $match_xiti[1];
          $data['xiti_id'] = $xiti_id;
          $this->insert_data($dir, $data, 0, 0, $time, $start, 0, 0, $xiti_id);
        } elseif ($portal_url) {
          $portal_id = $match_portal[1];
          $this->insert_data($dir, $data, 0, 0, $time, $start, 0, 0, 0, $portal_id);
        } elseif ($yuedu_url) {
          $yuedu_id = $match_yuedu[1];
          $this->insert_data($dir, $data, 0, 0, $time, $start, 0, 0, 0, 0, $yuedu_id);
        } else {
          $this->insert_data($dir, $data, 0, 0, $time, $start);
        }
      }
    }
    fclose($file);
    BaiduHash::where(['time' => $time . '000', 'dir' => $dir, 'start' => $start])->update(['status' => 1]);
  }

  protected function insert_data($dir, $data, $book_id, $chapter_id, $time, $page, $timu_id = 0, $timu3_id = 0, $xiti_id = 0, $portal_id = 0, $yuedu_id = 0)
  {
    $baidu = [];
    $bookinfo = [];
    if ($data[2] > 0) {
      $baidu['book_id'] = $book_id;
      $baidu['chapter_id'] = $chapter_id;
      if ($book_id == 0) {
        $bookinfo = [];
      } else {
//        if(BaiduNew::where('book_id',$book_id)->count()>0){
//          $bookinfo = '';
//        }else{
        $bookinfo = AWorkBookMain::find($book_id, ['bookname', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'sort']);
      }
    }

    if ($bookinfo) {
      $baidu['book_id'] = $book_id;
      $baidu['book_name'] = $bookinfo->bookname;
      $baidu['grade_id'] = $bookinfo->grade_id;
      $baidu['subject_id'] = $bookinfo->subject_id;
      $baidu['volume_id'] = $bookinfo->volumes_id;
      $baidu['version_id'] = $bookinfo->version_id;
      $baidu['sort_id'] = $bookinfo->sort;
    }

    $baidu['dir'] = $dir;
    $baidu['timu_id'] = $timu_id;
    $baidu['timu3_id'] = $timu3_id;
    $baidu['xiti_id'] = $xiti_id;
    $baidu['portal_id'] = $portal_id;
    $baidu['yuedu_id'] = $yuedu_id;
    $baidu['url'] = str_limit(iconv('gbk', 'utf-8', $data[1]), 255);
    $baidu['visit_count'] = str_replace(',', '', $data[2]);
    $baidu['visitor_count'] = str_replace(',', '', $data[3]);
    $baidu['new_visitor_count'] = str_replace(',', '', $data[4]);
    $new_visitor_ratio = str_replace(['%', '-'], '', $data[5]);
    $baidu['new_visitor_ratio'] = is_float($new_visitor_ratio) ? $new_visitor_ratio : sprintf("%.2f", $new_visitor_ratio);
    $baidu['ip_count'] = str_replace([',', '-'], '', $data[6]);
    $baidu['out_pv_count'] = str_replace([',', '-'], '', $data[7]);
    $bounce_ratio = str_replace(['%', '-'], '', $data[8]);
    $baidu['bounce_ratio'] = is_float($bounce_ratio) ? $bounce_ratio : sprintf("%.2f", $bounce_ratio);
    $data[9] = str_replace('-', '', $data[9]);
    if (empty($data[9])) {
      $data[9] = '00:00:01';
    }
    $t1 = strtotime("2011-01-01 " . $data[9]);
    $t2 = strtotime("2011-01-01 00:00:00");
    $baidu['avg_visit_time'] = $t1 - $t2;
    $baidu['avg_visit_pages'] = $data[10];
    $baidu['date_now'] = $time;
    $baidu['page_now'] = $page;
    try {
      $table_now = 'baidu_new_' . $dir;
      DB::connection('mysql_local')->table($table_now)->insert($baidu);
    } catch (Exception $e) {
      var_dump($e);
    }
    return ['status' => 1, 'msg' => '正在更新'];
  }

  protected function insert_shiti($dir, $data, $shiti, $time, $page)
  {
    $baidu = [];
    if ($data[2] > 0) {
      $baidu['dir'] = $dir;
      $baidu['url'] = str_limit(iconv('gbk', 'utf-8', $data[1]), 255);
      $baidu['visit_count'] = str_replace(',', '', $data[2]);
      $baidu['visitor_count'] = str_replace(',', '', $data[3]);
      $baidu['new_visitor_count'] = str_replace(',', '', $data[4]);
      $new_visitor_ratio = str_replace(['%', '-'], '', $data[5]);
      $baidu['new_visitor_ratio'] = is_float($new_visitor_ratio) ? $new_visitor_ratio : sprintf("%.2f", $new_visitor_ratio);
      $baidu['ip_count'] = str_replace(',', '', $data[6]);
      $baidu['out_pv_count'] = str_replace([',', '-'], '', $data[7]);
      $bounce_ratio = str_replace(['%', '-'], '', $data[8]);
      $baidu['bounce_ratio'] = is_float($bounce_ratio) ? $bounce_ratio : sprintf("%.2f", $bounce_ratio);
      $data[9] = str_replace('-', '', $data[9]);
      if (empty($data[9])) {
        $data[9] = '00:00:01';
      }
      $t1 = strtotime("2011-01-01 " . $data[9]);
      $t2 = strtotime("2011-01-01 00:00:00");
      $baidu['avg_visit_time'] = $t1 - $t2;
      $baidu['avg_visit_pages'] = $data[10];
      $baidu['date_now'] = $time;
      $baidu['shiti_id'] = $shiti['id'];
      $baidu['shiti_type'] = $shiti['type'];
      $baidu['has_shiti'] = 1;
      $baidu['page_now'] = $page;
      try {
        $table_now = 'baidu_new_' . $dir;
		if(DB::connection('mysql_main_jiajiao')->table('mo_no_answer')->where(['type'=>$baidu['shiti_type'],'md5id'=>$baidu['shiti_id']])->count()>0){
			$now_table = 'mo_'.$dir;
			if(DB::connection('mysql_local')->table($table_now)->where(['shiti_type'=>$baidu['shiti_type'],'shiti_id'=>$baidu['shiti_id'],'no_answer'=>2])->count()==0){
				$baidu['no_answer'] = 1;
			}else{
				$baidu['no_answer'] = 2;
			}
          
        }
        DB::connection('mysql_local')->table($table_now)->insert($baidu);
      } catch (Exception $e) {
        var_dump($e);
      }
    }
    return ['status' => 1, 'msg' => '正在更新'];
  }
}
