<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/15
 * Time: 下午3:50
 */

namespace App\Http\Controllers\CaijiBook;
use DB;
use App\Http\Controllers\Controller;
use App\Utils\Http;
use App\Utils\Search;
use Illuminate\Http\Request;


class CaijiBookController extends Controller
{
    public function search(Request $request,$word='',$remove=''){
        $page=$request->input('page');
        $search=new Search;
        if($remove!=''){
            $str='';
            foreach(explode(',',$remove) as $k=>$v){
                $str.=" !(".$v.")";
            }
            $word_new=$word.$str;
        }else{
            $word_new=$word;
        }
        $re['list']=$search
            ->set_hostip('192.168.0.130')
            ->set_index('a_caiji_book')
            ->set_ranking_mode("sum((exact_hit*500+exact_order*100-min_gaps*100)*user_weight)")
            ->page($word_new,$page,20);
        $total=$search->total();
        foreach($re['list'] as $k=>$v) $re['list'][$k]['bookname']=$search->high_light($v['bookname'],$word);
        $items = $re['list'];
        $perPage = 20;
        if(!$page){
            $page=1;
        }
        $currentPage = $page;
        $re['paginator'] = new \Illuminate\Pagination\LengthAwarePaginator($items, $total, $perPage, $currentPage,['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]);
        $re['word']=$word;
        $re['remove']=$remove;
        return view('caiji_book.search',compact('re'));
    }

}