<?php

namespace App\Http\Controllers\Test;


use App\Test\OcrLogs;
use App\Test\OcrSearch;
use Cron\FieldFactory;
use function foo\func;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
require_once app_path('Http/Controllers/Libs/baiduocr/AipOcr.php');
use AipOcr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

class TestController extends Controller
{

    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    private function search_question($string)
    {
        $all_search_ids = file_get_contents('http://www.1010jiajiao.com/api/search/question?word='.base64_encode(mb_substr($string,0,30)));
        $all_search_ids = json_decode($all_search_ids);
        $res = [];
        if($all_search_ids->code==0)
        {
            $allsub=array(21=>'czdl',22=>'czhx',23=>'czls',24=>'czsw',25=>'czsx',26=>'czwl',27=>'czyw',28=>'czyy',29=>'czzz',31=>'gzdl',32=>'gzhx',33=>'gzls',34=>'gzsw',35=>'gzsx',36=>'gzwl',37=>'gzyw',38=>'gzyy',39=>'gzzz',15=>'xxsx',17=>'xxyw',18=>'xxyy');
            foreach($all_search_ids->result as $k=>$r)
            {
                $v = $r->id;
                if($v>100000000){
                    $subid = intval($v/100000000);
                    if(!isset($allsub[$subid])){
                        $res[$k] = [];
                    }else{
                        $sub = $allsub[$subid];
                        $v =$v-$subid*100000000;
                        $res[$k] = DB::connection('mysql_main_rds_jiajiao')->table('mo_'.$sub)->where('id',$v)->first(['id','question as title','answer as parse']);
                    }
                }else{
                    $res[$k] = DB::connection('mysql_main_rds_tiku')->table('questions')->where('id',$v)->first(['id','title','parse']);
                }

            }

        }
        return json_encode($res);
    }

    public function index($type='index')
    {

        ignore_user_abort(true);
        ini_set('memory_limit', -1);
        set_time_limit(-1);
        switch ($type){
            case 'index':
                break;
            case 'ocr':
                $aipOcr = new AipOcr(config('workbook.APP_ID'), config('workbook.API_KEY'), config('workbook.SECRET_KEY'));
                $all_pages = File::allFiles(public_path('all_pages'));
                $half = $this->request->half?$this->request->half:0;
                foreach($all_pages as $now_img){
                    $now_img_path = $now_img->getPathname();
                    $now_img_name = public_path('all_pages_now/'.array_last(explode('\\', $now_img->getPath())).'/'.$now_img->getFilename());
                    $now_img_info = getimagesize($now_img_path);
                    $data['md5'] = md5(file_get_contents($now_img_path));
                    $data['width'] = $now_img_info[0];
                    $data['height'] = $now_img_info[1];
                    $file_info = OcrLogs::where('md5',$data['md5'])->first();
                    if($file_info){
                        $result = json_decode($file_info->ocr_result);
                    }else{
                        $data['imgpath'] = $now_img_path;
                        $result = $aipOcr->general(file_get_contents($now_img_path));
                        $data['ocr_result'] = json_encode($result);
                        OcrLogs::create($data);
                        $result = json_decode(json_encode($result));
                    }

                    $image = new ImageManager(array('driver' => 'gd'));
                    $now_img = $image->make($now_img_path);

                    if($result->words_result_num>1){
                        $all_line = [];
                        $all_location = collect($result->words_result)->pluck('location');

                        //上下左右
                        $max_top = $all_location->min('top');
                        $max_bottom_array = $all_location->sortByDesc(function ($value, $key) {
                            return $value->top+$value->height;
                        });
                        $max_bottom = array_first($max_bottom_array)->top+array_first($max_bottom_array)->height;
                        $max_left = $all_location->min('left');
                        $max_right_array = $all_location->sortByDesc(function ($value, $key) {
                            return $value->left+$value->width;
                        });
                        $max_right = array_first($max_right_array)->left + array_first($max_right_array)->width;

                        //边界
                        $now_img->line($max_left,$max_top,$max_right,$max_top, function ($draw) {
                            $draw->color('#f00');
                        });
                        $now_img->line($max_left,$max_top,$max_left,$max_bottom, function ($draw) {
                            $draw->color('#f00');
                        });
                        $now_img->line($max_left,$max_bottom,$max_right,$max_bottom, function ($draw) {
                            $draw->color('#f00');
                        });
                        $now_img->line($max_right,$max_top,$max_right,$max_bottom, function ($draw) {
                            $draw->color('#f00');
                        });

                        if($half==0){

                            if(($max_right+$max_left)/2>($data['width']/2)-10 && ($max_right-$max_left)/2>($data['width']/2)+10 ){
                                $half_width = ($max_right+$max_left)/2;
                            }else{
                                $half_width = $data['width']/2;
                            }
                        }else{
                            $half_width = $max_right;
                        }

                        foreach ($result->words_result as $line_index => $line){
                            foreach(range(1,99) as $num){
                                if(strpos($line->words, $num.'.')!==false){
                                    if(starts_with($line->words, $num)){
                                        if($half){
                                            if($line->location->left>$half_width){
                                                $all_line[$line_index] = [
                                                    'left'=>$line->location->left,
                                                    'top'=>$line->location->top,
                                                    'move_left'=>$max_right,
                                                    'move_top'=>$line->location->top,
                                                    'line'=>$line_index,
                                                    'half'=>1
                                                ];
                                                $now_file['img_path'] =$now_img_path;
                                                $now_file['line_num'] =$line_index;
                                                $now_file['line_text'] = $line->words;
                                                $now_file['search_result'] = $this->search_question($now_file['line_text']);

                                                //OcrSearch::create($now_file);
                                            }else{

                                                $all_line[$line_index] = [
                                                    'left'=>$line->location->left,
                                                    'top'=>$line->location->top,
                                                    'move_left'=>$half_width,
                                                    'move_top'=>$line->location->top,
                                                    'line'=>$line_index,
                                                    'half'=>1
                                                ];
                                                $now_file['img_path'] =$now_img_path;
                                                $now_file['line_num'] =$line_index;
                                                $now_file['line_text'] = $line->words;
                                                $now_file['search_result'] = $this->search_question($now_file['line_text']);
                                                //OcrSearch::create($now_file);

                                            }
                                        }else{
                                            $all_line[$line_index] = [
                                                'left'=>$line->location->left,
                                                'top'=>$line->location->top,
                                                'move_left'=>$max_right,
                                                'move_top'=>$line->location->top,
                                                'line'=>$line_index,
                                                'half'=>1
                                            ];
                                            $now_file['img_path'] =$now_img_path;
                                            $now_file['line_num'] =$line_index;
                                            $now_file['line_text'] = $line->words;
                                            $now_file['search_result'] = $this->search_question($now_file['line_text']);
                                        }


                                    }else{
                                        if($half){
                                            $all_line[$line_index] = [
                                                'left'=>$half_width,
                                                'top'=>$line->location->top,
                                                'move_left'=>$max_right,
                                                'move_top'=>$line->location->top,
                                                'line'=>$line_index,
                                                'half'=>0
                                            ];
                                            $now_file['img_path'] =$now_img_path;
                                            $now_file['line_num'] =$line_index;
                                            $now_file['line_text'] = $line->words;
                                            $now_file['search_result'] = $this->search_question($now_file['line_text']);
                                            //OcrSearch::create($now_file);
                                        }else{
                                            $all_line[$line_index] = [
                                                'left'=>$half_width,
                                                'top'=>$line->location->top,
                                                'move_left'=>$max_right,
                                                'move_top'=>$line->location->top,
                                                'line'=>$line_index,
                                                'half'=>0
                                            ];
                                            $now_file['img_path'] =$now_img_path;
                                            $now_file['line_num'] =$line_index;
                                            $now_file['line_text'] = $line->words;
                                            $now_file['search_result'] = $this->search_question($now_file['line_text']);
                                            //OcrSearch::create($now_file);
                                        }
                                    }
                                }
                            }
                        }

                        $all_line_last = collect($all_line)->groupBy('half')->toArray();

                        $all_line_last_count = count($all_line_last);
                        if($all_line_last_count==2){
                            foreach ($all_line_last as $half => $line_group){
                                $line_group_num = count($line_group);
                                foreach ($line_group as $key => $line_single){
                                    if($key==$line_group_num-1){
                                        if($half==0){
                                            $now_half_width = $half_width;
                                        }else{
                                            $now_half_width = $max_right;
                                        }
                                        $now_img->line($line_single['left'],$line_single['top'],$line_single['left'],$max_bottom-10, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_single['left'],$max_bottom,$now_half_width,$max_bottom-10, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($now_half_width,$max_bottom-10,$now_half_width,$line_single['top'], function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($now_half_width,$line_single['top'],$line_single['left'],$line_single['top'], function ($draw) {
                                            $draw->color('#f00');
                                        });
                                    }else{
                                        //top left bottom right
                                        $now_img->line($line_single['left'],$line_single['top'],$line_single['move_left'],$line_single['move_top'], function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_single['left'],$line_single['top'],$line_group[$key+1]['left'],$line_group[$key+1]['top']-5, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_group[$key+1]['left'],$line_group[$key+1]['top']-5,$line_group[$key+1]['move_left'],$line_group[$key+1]['move_top']-5, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_single['move_left'],$line_single['move_top'],$line_group[$key+1]['move_left'],$line_group[$key+1]['move_top']-5, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                    }
                                }
                            }
                        }
                        else {
                            foreach ($all_line_last as $half => $line_group){
                                $line_group_num = count($line_group);
                                foreach ($line_group as $key => $line_single) {
                                    if ($key == $line_group_num - 1) {
                                        $now_img->line($line_single['left'], $line_single['top'], $line_single['left'], $max_bottom - 10, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_single['left'], $max_bottom, $max_right, $max_bottom - 10, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($max_right, $max_bottom - 10, $max_right, $line_single['top'], function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($max_right, $line_single['top'], $line_single['left'], $line_single['top'], function ($draw) {
                                            $draw->color('#f00');
                                        });
                                    } else {
                                        //top left bottom right
                                        $now_img->line($line_single['left'], $line_single['top'], $line_single['move_left'], $line_single['move_top'], function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_single['left'], $line_single['top'], $line_group[$key + 1]['left'], $line_group[$key + 1]['top'] - 5, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_group[$key + 1]['left'], $line_group[$key + 1]['top'] - 5, $line_group[$key + 1]['move_left'], $line_group[$key + 1]['move_top'] - 5, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                        $now_img->line($line_single['move_left'], $line_single['move_top'], $line_group[$key + 1]['move_left'], $line_group[$key + 1]['move_top'] - 5, function ($draw) {
                                            $draw->color('#f00');
                                        });
                                    }
                                }
                            }
                        }
                        $now_img->save($now_img_name);
                    }
                }

                break;
        }
    }


    public function draw_rect($now_img,$left_top,$right_top,$right_bottom,$left_bottom)
    {

        $now_img->line($left_top->x,$left_top->y,$right_top->x,$right_top->y,function ($draw){
            $draw->color('#f00');
        });
        $now_img->line($right_top->x,$right_top->y,$right_bottom->x,$right_bottom->y,function ($draw){
            $draw->color('#f00');
        });
        $now_img->line($right_bottom->x,$right_bottom->y,$left_bottom->x,$left_bottom->y,function ($draw){
            $draw->color('#f00');
        });
        $now_img->line($left_bottom->x,$left_bottom->y,$left_top->x,$left_top->y,function ($draw){
            $draw->color('#f00');
        });
    }


    public function google($now_num=1)
    {

//        $image = new ImageManager(array('driver' => 'gd'));
//
//        $now_img1 = $image->make(public_path("google_img/camera_23_1.jpg"));
//        $now_img2 = $image->make(public_path("google_img/camera_23_2.jpg"));
//        $now_img3 = $image->make(public_path("google_img/camera_23_3.jpg"));
//        $now_img1->rotate(-90);
//        $now_img2->rotate(-90);
//        $now_img3->rotate(-90);
//        $now_img1->save();
//        $now_img2->save();
//        $now_img3->save();
//        dd('qwe');




        $default1_1 = range(1, 99);
        $default1_2 = array('XII','XI','IX','X','VIII','VII','VI','IV','V','III','II','I',);

        $default2_1 = '(';
        $default2_2 = range(1,99);

        if($now_num<11 || $now_num>20){
            $default = $default1_1;
        }else{
            $default = $default1_2;
        }

        $result = json_decode(file_get_contents(public_path('google/'.$now_num.'.json')));

        $now_all_text = $result->textAnnotations;
        $now_all_select = [];


        $all_len = count($now_all_text);
        foreach ($now_all_text as $key => $line) {
            foreach ($default as $num) {
                if ($line->description == $num && $key+1<$all_len && $now_all_text[$key + 1]->description == '.') {
                    $now_paragraph[] = ['number'=>$key,'line'=>$line];
                    break;
                }
            }

            if($now_num<11){
                if ($line->description == '(' && $key+1<$all_len  && in_array($now_all_text[$key + 1]->description, range(1,999))) {
                    $now_paragraph_two[] = ['number'=>$key,'line'=>$line];
                }
            }else{
                if (in_array($line->description, range(1,999)) && $key+1<$all_len && $now_all_text[$key + 1]->description == '.' ) {
                    $now_paragraph_two[] = ['number'=>$key,'line'=>$line];
                }
            }


            //选择题
            if ($line->description == '(' && $key+1<$all_len && $now_all_text[$key + 1]->description == ')') {
                $now_all_select[] = ['number'=>$key,'line'=>$line];

            }


        }



        list($max_left_top,$max_right_top,$max_right_bottom,$max_left_bottom) = $now_all_text[0]->boundingPoly->vertices;

        $max_left_top->x -= 50;
        $max_left_top->y -= 50;
        $max_right_top->x += 50;
        $max_right_top->y -= 50;
        $max_right_bottom->x += 50;
        $max_right_bottom->y += 50;
        $max_left_bottom->x -= 50;
        $max_left_bottom->y += 50;


        $image = new ImageManager(array('driver' => 'gd'));

        $now_img = $image->make(public_path("google_img/{$now_num}.jpg"));


//        $now_img->rotate(-90);
//        $now_img->save();
//        dd('qqq');

        //$now_img->fit(100, 100)->encode('png', 50)->trim();
//        $now_img->resize(1000,1000,function ($constraint) {
//            $constraint->aspectRatio(); //to preserve the aspect ratio
//            $constraint->upsize();
//
//        });
        $now_img_name = public_path("google_img_test/{$now_num}.jpg");

        $this->draw_rect($now_img, $max_left_top, $max_right_top, $max_right_bottom, $max_left_bottom);

        $now_paragraph_len = count($now_paragraph);
        $now_paragraph_two_len = count($now_paragraph_two);

        //大题框
        foreach ($now_paragraph as $key=>$paragraph){
            $now_small_rect = $paragraph['line']->boundingPoly->vertices;
           if($key+1<$now_paragraph_len){
               $now_small_rect_next = $now_paragraph[$key+1]['line']->boundingPoly->vertices;
               $this->draw_rect($now_img, json_decode(json_encode(['x'=>$max_left_top->x,'y'=>$now_small_rect[0]->y])), json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$now_small_rect[0]->y])), json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$now_small_rect_next[0]->y])), json_decode(json_encode(['x'=>$max_left_top->x,'y'=>$now_small_rect_next[0]->y])));
           }else{
               $this->draw_rect($now_img, json_decode(json_encode(['x'=>$max_left_top->x,'y'=>$now_small_rect[0]->y])), json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$now_small_rect[0]->y])), $max_right_bottom, json_decode(json_encode(['x'=>$max_left_top->x,'y'=>$max_left_bottom->y])));
           }
        }


        //小题框
        foreach ($now_paragraph_two as $key=>$paragraph){
            $now_small_rect = $paragraph['line']->boundingPoly->vertices;
            if($key+1<$now_paragraph_two_len){
                $now_small_rect_next = $now_paragraph_two[$key+1]['line']->boundingPoly->vertices;

                $next_y = $now_small_rect_next[0]->y;

                foreach ($now_paragraph as $big_key=>$big_paragraphy){
                    $now_big_rect = $big_paragraphy['line']->boundingPoly->vertices;

                    if($big_key+1<$now_paragraph_len){
                        $now_big_rect_next = $now_paragraph[$big_key+1]['line']->boundingPoly->vertices;
                        //上Y大于1大框小于2大框 下Y大于2大框
                        if($now_small_rect[0]->y>$now_big_rect[0]->y && $now_small_rect[0]->y<$now_big_rect_next[0]->y && $now_small_rect_next[0]->y>$now_big_rect_next[0]->y){
                            $next_y = $now_big_rect_next[0]->y;
                        }
                        //上Y小于大框  下Y大于大框
//                        if($now_small_rect[0]->y<$now_big_rect[0]->y && $now_small_rect[0]->y<$now_big_rect_next[0]->y && $now_small_rect_next[0]->y>$now_big_rect_next[0]->y){
//                            $next_y = $now_big_rect_next[0]->y;
//                        }
                    }

                    //
                    if($big_key==0 && $now_small_rect[0]->y<$now_big_rect[0]->y && $now_small_rect_next[0]->y>$now_big_rect[0]->y){
                        $next_y = $now_big_rect[0]->y;
                    }
                }

                $this->draw_rect($now_img,
                    json_decode(json_encode(['x'=>$max_left_top->x+30,'y'=>$now_small_rect[0]->y])),
                    json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$now_small_rect[0]->y])),
                    json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$next_y])),
                    json_decode(json_encode(['x'=>$max_left_top->x+30,'y'=>$next_y])));
            }else{
                $last_y = $max_right_bottom->y;
                foreach ($now_paragraph as $big_key=>$big_paragraphy){
                    $now_big_rect = $big_paragraphy['line']->boundingPoly->vertices;
                    if($big_key+1<$now_paragraph_len){
                        $now_big_rect_next = $now_paragraph[$big_key+1]['line']->boundingPoly->vertices;
                        if($now_big_rect_next[0]->y<=$max_right_bottom->y && $now_big_rect_next[0]->y>=$now_small_rect[0]->y){
                            $last_y = $now_big_rect_next[0]->y;
                            break;
                        }

                    }else{
                        if($now_big_rect[0]->y<=$max_right_bottom->y && $now_big_rect[0]->y>=$now_small_rect[0]->y){
                            $last_y = $now_big_rect[0]->y;
                            break;
                        }
                    }
                }

                $this->draw_rect($now_img,
                    json_decode(json_encode(['x'=>$max_left_top->x+30,'y'=>$now_small_rect[0]->y])),
                    json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$now_small_rect[0]->y])),
                    json_decode(json_encode(['x'=>$max_right_top->x,'y'=>$last_y])),
                    json_decode(json_encode(['x'=>$max_left_top->x+30,'y'=>$last_y])));
            }
        }


        //括号类选择题框
        if(count($now_all_select)>0){
            foreach ($now_all_select as $key=>$select){

                $now_left_brackets = $select['line']->boundingPoly->vertices;
                $now_right_brackets = $now_all_text[$select['number']+1]->boundingPoly->vertices;




                $this->draw_rect($now_img,
                    $now_left_brackets[1],
                    $now_right_brackets[0],
                    $now_right_brackets[3],
                    $now_left_brackets[2]
                );
            }
        }

        $now_img->save($now_img_name);
        echo "<img src='".asset('google_img_test/'.$now_num.'.jpg')."'>";
    }

}