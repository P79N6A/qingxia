<?php

namespace App\Console;

use App\Console\Commands\TestTranslate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Baidu\GetDataController;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\FeedbackArrange::class,
        Commands\Test::class,
        Commands\ConvertBook::class,
        Commands\TestTranslate::class,
        Commands\TestNow::class,
        Commands\OneTest::class,
        Commands\ConvertImage::class,
        Commands\PregLocalImage::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function (){
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
        })->everyTenMinutes();

        $schedule->call(function (){
            $a = new GetDataController();
            $a->auto_update();
            // $num = rand(9,13);
            // $all_info = DB::connection('mysql_main')->table('yuyue_nadiyi')->where([['push_onlyhi','=',0]])->select('id','name','mobile','gradename','subjectname')->take($num)->inRandomOrder()->get();


            // foreach ($all_info as $info){
            // sleep(rand(5, 20));
            // $data['name'] = $info->name;
            // $data['phone'] = $info->mobile;
            // $data['grade'] = $info->gradename;
            // $data['subject'] = $info->subjectname;
            // $data['adid'] = 'QYSRAKJX5W';
            // $data['jh'] = '';
            // $data['dy'] = '';
            // $data['keyNum'] = '';

            // $http = new \GuzzleHttp\Client();
            // $res = $http->get('http://cmsapi.onlyhi.cn/NewNameList/createNewNameList',['query'=>$data]);
            // $a = $res->getBody()->getContents();
            // file_put_contents(app_path('111.txt'),json_encode($a));
            // if(strpos($a, '"code":"100"')!==false){
            // $time = date('Y-m-d H:i:s',time());
            // DB::connection('mysql_main')->table('yuyue_nadiyi')->where('id',$info->id)->update(['push_onlyhi'=>1,'push_at'=>$time]);
            // }

            // }
        })->twiceDaily(8, 12);


        $schedule->call(function (){
            $a = DB::connection('mysql_main')->select('select l.id,l.uid,l.got_at,l.city,m.realname,l.status,l.pushed_at,m.mobile,e.gradeid from app_course_coupon_log l,pre_common_member_profile m,pre_plugin_eduinfo e where l.uid=m.uid and l.uid=e.uid and coupon_id = 2 and e.gradeid>=7 and e.gradeid<=9 and pushed_at is null and  city in ("北京", "上海", "广州", "天津", "深圳") order by got_at desc limit 1');

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
            foreach($a as $info){
                $info = collect($info);
                $data['i'] = 200145763;
                $data['t'] = '3nw6kjw';
                $data['s'] = 200416173;
                $data['acc'] = 'a3M8DwOKrRyO55MFiwj8mbdAPGkZjxHt';
                $data['r'] = 'http://www.1010jiajiao.com';
                //姓名
                $data['c']['cp']['201510803']['n'] = $info['realname']?$info['realname']:'未填写';
                //电话
                $data['c']['cp']['201510804'][] = $info['mobile'];
                //年级 201160917,201160918,201160919
                $now_grade = 201160917;
                if($info['gradeid']==7) $now_grade = 201160917;
                if($info['gradeid']==8) $now_grade = 201160918;
                if($info['gradeid']==9) $now_grade = 201160919;
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
                $all['cvs'] = $data;
                dd($all['cvs']);
                $resp = $http->post('http://cn.mikecrm.com/handler/web/form_runtime/handleSubmit.php', [
                    'form_params'=>['d'=>json_encode($all,JSON_UNESCAPED_UNICODE)],
                    'cookies'=>$cookie,
                ]);

                if($resp->getStatusCode()==200){
                    DB::connection('mysql_main')->table('app_course_coupon_log')->where(['id'=>$info['id']])->update(['pushed_at'=>$info['got_at']]);
                }
            }


        })->hourly();

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
