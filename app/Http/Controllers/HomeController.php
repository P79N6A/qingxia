<?php

namespace App\Http\Controllers;



use App\LwwBookChapter;
use App\LwwBookPageTimupos;
use Illuminate\Http\Request;
require_once 'Libs/baiduocr/AipOcr.php';
use AipOcr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //13714079412@163.com:bao19911111
//      Mail::raw('这是一封测试邮件', function ($message) {
//        $to = '13714079412@163.com';
//        $message ->to($to)->subject('测试邮件');
//      });
//
//
//      $redis = app('redis.connection');
//        dd($redis->get('laravel_session'));

        return redirect(route('backend'));
    }

    public function test(Request $request)
    {

        #print_r(json_decode($request->test));





    return view('test.test');
//      $chapters = LwwBookChapter::where('bookid',26)->select('bookid','id','pages')->orderBy('id','asc')->get();
//      $timus = LwwBookPageTimupos::where('bookid',26)->select('bookid','id','chapterid','timu_page')->orderBy('id','asc')->get();
//      foreach ($chapters as $chapter){
//        foreach ($timus as $timu){
//         $page_array = explode(',',$chapter->pages);
//          if(in_array($timu->timu_page,$page_array)){
//            var_dump($timu->id);
//            var_dump($timu->timu_page);
//            var_dump($chapter->id);
//            var_dump('------------');
//            //LwwBookPageTimupos::where('id',$timu->id)->update(['chapterid'=>$chapter->id]);
//          }
//        }
//      }





// 初始化
      $aipOcr = new AipOcr(config('workbook.APP_ID'), config('workbook.API_KEY'), config('workbook.SECRET_KEY'));
// 身份证识别
// echo json_encode($aipOcr->idcard(file_get_contents('idcard.jpg'), true), JSON_PRETTY_PRINT);

// 银行卡识别
// echo json_encode($aipOcr->bankcard(file_get_contents('bankcard.jpg')));

// 通用文字识别(含文字位置信息)
// echo json_encode($aipOcr->general(file_get_contents('general.png')));


// 通用文字识别(不含文字位置信息)
// echo json_encode($aipOcr->basicGeneral(file_get_contents('general.png')), JSON_PRETTY_PRINT);

// 网图OCR识别
//      $data = json_encode($aipOcr->basicGeneral(file_get_contents(storage_path('app/public/all_book_pages/27/cut_pages/2/1_1576.jpg'))),JSON_PRETTY_PRINT);
//      dump($data);die();
      echo json_encode($aipOcr->webImage(file_get_contents(storage_path('app/public/all_book_pages/27/cut_pages/2/1_1576.jpg'))), JSON_PRETTY_PRINT);
      die;

// 生僻字OCR识别
// echo json_encode($aipOcr->enhancedGeneral(file_get_contents('general.png')), JSON_PRETTY_PRINT);

// 行驶证识别
// echo json_encode($aipOcr->vehicleLicense(file_get_contents('vehicleLicense.jpg')), JSON_PRETTY_PRINT);

// 驾驶证
// echo json_encode($aipOcr->drivingLicense(file_get_contents('drivingLicense.jpg')), JSON_PRETTY_PRINT);

// 车牌
//echo json_encode($aipOcr->licensePlate(file_get_contents('licensePlate.jpg')), JSON_PRETTY_PRINT);

      return view('test');
    }
}
