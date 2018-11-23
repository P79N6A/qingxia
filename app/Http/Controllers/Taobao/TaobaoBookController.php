<?php
/**
 * Created by PhpStorm.
 * User: libing
 * Date: 2018/3/15
 * Time: 下午3:50
 */

namespace App\Http\Controllers\Taobao;

use DB;
use App\AWorkbookNew;
use App\Http\Controllers\Controller;
use App\LModel\LBookGoodsModel;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewGoods;
use App\LocalModel\NewBuy\NewGoodsTrue;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\IsbnAll;
use App\AWorkbook1010;
use App\AWorkbook1010NewZjb;
use App\Sort;
use App\Utils\Http;
use App\Utils\Search;
use App\Utils\SphinxClient;
use function foo\func;
use Illuminate\Http\Request;
use App\LocalModel\ShopBySort;
use App\LocalModel\RemoveWord;
use App\localModel\NewBuy\NewGoodsFind;
use App\localModel\NewBuy\NewGoodsFindBook;

class TaobaoBookController extends Controller
{


    //买书-购买
    public function buybook($sortname='',$contain ='',$remove =''){
        if($sortname!=''){
            $where[] = ['sortname',$sortname];
        }
        if($contain!=''){
            $where[] = ["title","like","%".$contain."%"];
        }
        if($remove!=''){
            $where[] = ["title","not like","%".$remove."%"];
        }

        $data['sortname'] = $sortname;
        $data['contain'] = $contain;
        $data['remove'] = $remove;
        $data['list']=NewGoodsTrue::from("a_book_goods_true as t")->leftJoin('a_workbook_only as o','t.jiajiao_id','o.id')
            ->where($where)->where('t.status',0)
            ->orderBy('t.shopTop', 'desc')
            ->select('t.shopTop','t.id','t.jiajiao_id','t.view_price','t.view_fee','t.nick','t.shopLink','t.title','t.sort','t.sortname','t.detail_url','t.pic_url','t.status','o.grade_id','o.subject_id','o.volumes_id')->get();

        $data['grade_subject_info'] =  $data['list']->groupBy(function ($item,$key){
            return $item->grade_id;
        })->transform(function($item1,$key1){
            return $item1->groupBy(function ($item2,$key2){
                return $item2->subject_id;
            })->sortBy(function ($s_value,$s_key){
                return $s_key;
            });
        })->sortBy(function ($s_value1,$s_key1){
            return $s_key1;
        });

        $data['all_need_buy_info'] = $data['list']->groupBy(function ($item,$key){
            return $item->grade_id;
        })->transform(function($item1,$key1){
            return $item1->groupBy(function ($item2,$key2){
                return $item2->subject_id;
            })->sortBy(function ($s_value,$s_key){
                return $s_key;
            });
        })->sortBy(function ($s_value1,$s_key1){
            return $s_key1;
        });

        //dd($data['list'][0]);
        return view('taobao.buybook',compact('data'));
    }

    public function new_bookList($shopId){
        $data['list'] = NewGoodsTrue::where('shopLink',$shopId)->select("title","detail_url","shopLink",'id','view_price',"view_fee","nick",'pic_url','shopTop','bookTop')->get();
        $data['type']='book';
        //dd($data);
        return view('taobao.shoplist', ['data'=>$data]);
    }

    public function new_shopList($keyword,$subject,$grade,$contain='',$remove=''){
        $where[]=["sortname",$keyword];
        if($contain!=''){
            $where[] = ["title","like","%".$contain."%"];
        }
        if($remove!=''){
            $where[] = ["title","not like","%".$remove."%"];
        }
        $re = NewGoodsTrue::select(DB::raw('max(shopLink) as shopLink'))->where($where)->groupBy('shopLink')->get();
        //$data = NewGoodsTrue::select()->where($re)->get();
        $data['type']='shop';
        foreach($re as $k=>$v){
            $data['list'][$k]=NewGoodsTrue::select()->where("shopLink",$v->shopLink)->first();
        }
        //dd($data);
        return view('taobao.shoplist', ['data'=>$data]);
    }


    public function hideItem($id){
        $result = NewGoodsTrue::where("detail_url",$id)->update(['status'=>3]);
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
        $result = NewGoodsTrue::where([$where])->update([$top=>$val]);
        exit(\GuzzleHttp\json_encode(['status'=>$result]));
    }

    public function findClear(Request $request){ //清除发现,status改为7
        foreach($request->vals as $k=>$v){
            $arr=explode('-',$v);
            $sortname=$arr[0];
            $subject_id=$arr[1];
            $grade_id=$arr[2];
            $result=NewGoodsTrue::from("a_book_goods_true as t")
                ->leftJoin('a_workbook_only as o','t.jiajiao_id','o.id')
                ->where(["t.sortname"=>$sortname,"o.grade_id"=>$grade_id,"o.subject_id"=>$subject_id,"t.status"=>0])
                ->update(["t.status"=>7]);
            exit(\GuzzleHttp\json_encode(['status'=>$result]));
        }
    }

    public function search(Request $request,$word='优化设计',$type=0,$sort_id=-1,$is_read=0,$v_status=0,$remove_isbn=0,$has_year=0,$start='',$end=''){  //搜索goods大表
        $search=new Search;
        $search2=new Search;
        /*$a=$search->parse_bookname($word);
        $word=$a['sort_name'];
        $grade_id=I('grade_id');
        if($grade_id===null && isset($a['grade_id'])) $grade_id=$a['grade_id'];
        if($grade_id!==null) $search->set_filter('grade_id',(int)$grade_id);
        $subject_id=I('subject_id');
        if($subject_id===null && isset($a['subject_id'])) $subject_id=$a['subject_id'];
        if($subject_id!==null) $search->set_filter('subject_id',(int)$subject_id);*/
        /*$volumes_id=I('volumes_id');
        if($volumes_id===null && isset($a['volumes_id'])) $volumes_id=$a['volumes_id'];
        if($volumes_id!==null) $search->set_filter('volumes_id',(int)$volumes_id);
        $version_id=I('version_id');
        if($version_id===null && isset($a['version_id'])) $version_id=$a['version_id'];
        if($version_id!==null) $search->set_filter('version_id',(int)$version_id);*/
        //$page=1;
        if($start=='') $start='2018-01-01';
        if($end=='') $end=date("Y-m-d");
        if($sort_id!=-1) $search->set_filter('sort_id',[0,$sort_id]);

        $page=$request->input('page');
        $re['sort_name']= Sort::where(["id"=>$sort_id])->select('name','version')->first();
        if( $sort_id>-1){
            $min_time=NewGoodsFind::where(['sort_id'=>$sort_id])->select('update_time')->first()->update_time;
            if($is_read!=0){
                $search->set_filter_range('updatetime',$min_time,time());
                $search2->set_filter_range('updatetime',$min_time,time());
            }
            $shops=ShopBySort::where(['sort_id'=>$sort_id])->select('shopLink')->get();
            $shopLink_str=[];
            foreach($shops as $k=>$item){
                $shopLink_str[$k]=$item->shopLink;
            }
            //$shopLink_str=rtrim($shopLink_str,',');
            //dd($shopLink_str);
            if($type==1){
                $search->set_filter('shoplink',$shopLink_str);
            }elseif($type==0){
                $re['shopLink_arr']=$shopLink_str==''?[]:$shopLink_str;
            }
        }
        if(!empty($re['sort_name']->version)){
            $version_arr=explode('|',$re['sort_name']->version);
            //print_r($version_arr);
            if($v_status==1 ){
                $search->set_filter('vid',$version_arr);
            }

        }

        if($word=='')$word=$re['sort_name']->name;
        $isbn_arr=explode('|',$word);
        if(check_isbn($isbn_arr[0])){
            $word_new=$word;
        }else{
            $re['remove_words']=RemoveWord::where(["sortname"=>$re['sort_name']->name,"status"=>0])->select('remove')->get();
            //dd($re['remove_words']);
            if(count($re['remove_words'])>0){
                $str='';
                foreach($re['remove_words'] as $k=>$item){
                    $str.=" !(".$item->remove.")";
                }
                $word_new=$word.$str;
            }else{
                $word_new=$word;
            }
        }
        $word_new2=$word_new;

        if($remove_isbn==1){
           /* $isbn_str='';
            $isbn_1010=AWorkbook1010::where(['sort'=>$sort_id])->whereIn('status',[1,7])->select('isbn')->get();
            $isbn_new=AWorkbook1010NewZjb::where(['sort'=>$sort_id])->select('isbn')->get();
            $isbn_goods=NewBoughtRecord::from('a_book_bought_record as r')->leftJoin('a_book_goods as g','r.goods_id','g.detail_url')->where(['sort'=>$sort_id])->where('r.status','>',3)->select('g.isbn')->get();

            foreach($isbn_1010 as $isbn){
                if($isbn->isbn!=0)$isbn_str.=$isbn->isbn.'|';
            }

            foreach($isbn_new as $isbn){
                if($isbn->isbn!=0)$isbn_str.=$isbn->isbn.'|';
            }

            foreach($isbn_goods as $isbn){
                if($isbn->isbn!=0)$isbn_str.=$isbn->isbn.'|';
            }

            $isbn_str=rtrim($isbn_str,'|');
            $isbn_arr=array_unique(explode('|',$isbn_str));
            $remove_isbn_str='';
            if(count($isbn_arr)>0){
                foreach($isbn_arr as $isbn){
                    if($isbn!='') $remove_isbn_str.=" !(".$isbn.")";
                }
                $word_new=$word_new.$remove_isbn_str;
            }*/
            /*$remove_isbn_str='';
            $isbn_str=NewBoughtRecord::from('a_book_bought_record as r')
                ->leftJoin('a_workbook_only as o','r.only_id','o.id')
                ->where(['r.sort'=>$sort_id])->where('r.goods_id','>',0)
                ->where('r.updated_at','>',date("Y-m-d",strtotime("-1 day")))
                ->select('o.isbn')->first();
           if(!empty($isbn_str)){
               foreach(explode('|',$isbn_str->isbn) as $v){
                   if($v!='') $remove_isbn_str.=" !(".$v.")";
               }
               $word_new=$word_new.$remove_isbn_str;
           }*/

        }

        if($has_year==1){
            $search->set_filter('pree_time',[2018,2019]);
        }elseif($has_year==2){
            $search->set_filter('pree_time',[2018,2019,999]);
        }
        //dd($word_new);
        if($type<2){
            $re['list']=$search
                ->set_hostip('192.168.0.130')
                ->set_index('a_book_goods')
                //->set_field_weight(['main_word'=>1000,'raw_title'=>1])
                ->set_ranking_mode("sum((exact_hit*500+exact_order*100-min_gaps*100)*user_weight)")
                ->set_sort_mode(SPH_SORT_EXTENDED,"@weight DESC,pic_addtime DESC")
                //->set_filter_range('pic_addtime',strtotime($start),strtotime($end))
                ->page($word_new,$page,20);
            $total=$search->total();
            foreach($re['list'] as $k=>$v) $re['list'][$k]['raw_title']=$search->high_light($v['raw_title'],$word_new);
            $items = $re['list'];
        }else{
            $re['list2']=$search2
                ->set_hostip('192.168.0.130')
                ->set_index('a_pinduoduo_goods')
                //->set_field_weight(['main_word'=>1000,'raw_title'=>1])
                ->set_ranking_mode("sum((exact_hit*500+exact_order*100-min_gaps*100)*user_weight)")
                ->set_sort_mode(SPH_SORT_EXTENDED,"@weight DESC,pic_addtime DESC")
                //->set_filter_range('pic_addtime',strtotime($start),strtotime($end))
                ->page($word_new2,$page,20);
            $total=$search2->total();
            foreach($re['list2'] as $k=>$v) $re['list2'][$k]['raw_title']=$search2->high_light($v['raw_title'],$word_new2);
            $items = $re['list2'];
        }
        //dd($word_new2);
       /* $re['list2']=$search2
            ->set_hostip('192.168.0.130')
            ->set_index('a_pinduoduo_goods')
            //->set_field_weight(['main_word'=>1000,'raw_title'=>1])
            ->set_ranking_mode("sum((exact_hit*500+exact_order*100-min_gaps*100)*user_weight)")
            ->set_sort_mode(SPH_SORT_EXTENDED,"@weight DESC,pic_addtime DESC")
            ->set_filter_range('pic_addtime',strtotime($start),strtotime($end))
            ->page($word_new2,$page,20);
        //dd($re['list2']);
        $total2=$search2->total();
        $total=max($total,$total2);*/
        //foreach($re['list'] as $k=>$v) $re['list'][$k]['raw_title']=$search->high_light($v['raw_title'],$word_new);
        //foreach($re['list2'] as $k=>$v) $re['list2'][$k]['raw_title']=$search->high_light($v['raw_title'],$word_new2);
        //dd($total);

        $perPage = 20;
        if(!$page){
            $page=1;
        }
        $currentPage = $page;
        $re['paginator'] = new \Illuminate\Pagination\LengthAwarePaginator($items, $total, $perPage, $currentPage,['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]);
        //dd($re);
        $re['keyword']=$word;
        $re['type']=$type;
        $re['sort_id']=$sort_id;
        $re['is_read']=$is_read;
        $re['v_status']=$v_status;
        $re['remove_isbn']=$remove_isbn;
        $re['has_year']=$has_year;
        $re['start']=$start;
        $re['end']=$end;


        return view('taobao.search',compact('re'));
    }

    public function shopLinkBySort(Request $request){ //添加店铺到系列
        $sort_id=$request->sort_id;
        $shopLink=$request->shopLink;
        ShopBySort::create(["sort_id"=>$sort_id,"shopLink"=>$shopLink]);
        exit(\GuzzleHttp\json_encode(['status'=>1]));
    }

    public function remove_word(Request $request){ //添加排除词
        $sort_name=$request->sort_name;
        $remove=$request->remove_word;
        $re=RemoveWord::where(["sortname"=>$sort_name,"remove"=>$remove])->select()->first();
        if($re){
            RemoveWord::where(["sortname"=>$sort_name,"remove"=>$remove])->update(["status"=>0]);
        }else{
            RemoveWord::create(["sortname"=>$sort_name,"remove"=>$remove]);
        }
        exit(\GuzzleHttp\json_encode(['status'=>1]));
    }

    public function del_remove(Request $request){ //移除排除词
        $sort_name=$request->sort_name;
        $remove=$request->remove_word;
        $re=RemoveWord::where(["sortname"=>$sort_name,"remove"=>$remove])->update(["status"=>1]);
        exit(\GuzzleHttp\json_encode(['status'=>$re]));
    }

    public function is_read(Request $request){ //标记已读
      /*  $search=new Search;
        $search->set_hostip('192.168.0.130')
            ->set_index('a_book_goods');
        $id_arr=$request->checkData;
        $sort_id=$request->sort_id;
        $keyword=(string)$request->keyword;
        foreach($id_arr as $id){
            $search->update_attr(['is_read'],[$id=>[1]]);
            NewGoods::where(["id"=>$id])->update(["is_read"=>1]);
        }
        $re=NewGoodsFind::where('sort_id',$sort_id)->select('find_num','search_word')->first();
        $search_word=$re->search_word;
        //var_dump($keyword);
        if(strstr($search_word,$keyword)){
            $num=count($id_arr);
            NewGoodsFind::where('sort_id',$sort_id)->update(['find_num'=>$re->find_num-$num]);
        }*/
        $sort_id=$request->sort_id;
        $re=NewGoodsFind::where(['sort_id'=>$sort_id])->update(['update_time'=>time(),'find_num'=>0,'find_num_new'=>0,'find_pinduoduo'=>0]);
        exit(\GuzzleHttp\json_encode(['status'=>$re]));
    }

    public function show_isbninfo(Request $request){
        $isbn=$request->isbn;
        $des=IsbnAll::where(['isbn'=>$isbn])->select('print_description')->first();
        if($des){
            exit(\GuzzleHttp\json_encode(['status'=>1,'des'=>$des->print_description]));
        }else{
            exit(\GuzzleHttp\json_encode(['status'=>0]));
        }

    }


    public function goods_list($sort_id=-1){ // 按年级学科展示商品
        $data['grade_arr']=[3=>'三年级',4=>'四年级',5=>'五年级',6=>'六年级',7=>'七年级',8=>'八年级',9=>'九年级'];
        $data['subject_arr']=[1=>'数学',2=>'语文',3=>'英语',4=>'物理',5=>'化学',6=>'历史',7=>'地理',8=>'生物',9=>'政治'];
        $data['list']=NewGoodsFindBook::where(['sort_id'=>$sort_id])
            ->select('sort_id','grade_id','subject_id','search_word','find_num')->get();
        $data['sort_id']=$sort_id;
        return view('taobao.goods_list',compact('data'));
    }

    public function show_bought(Request $request){
        $sort_id=intval($request->sort_id);
        $grade_id=intval($request->grade_id);
        $subject_id=intval($request->subject_id);
       /* 1=>'数学','语文','英语','物理','化学','历史','地理','生物','政治','科学','综合'
        1=>'语文','数学','英语','物理','化学','地理','历史','政治','生物','科学','综合'*/
        $subject_arr=[1=>2,1,3,4,5,7,6,9,8];
        $data=NewOnly::from('a_workbook_only as o')
            ->leftJoin('a_book_bought_record as r','o.id','r.only_id')
            ->where(['o.sort'=>$sort_id,'o.grade_id'=>$grade_id,'o.subject_id'=>$subject_arr[$subject_id]])
            ->select('r.status','o.newname')->get();
        /*$data=NewBoughtRecord::from('a_book_bought_record as r')
            ->leftJoin('a_workbook_only as o','r.only_id','o.id')
            ->where(['r.sort'=>$sort_id,'r.grade_id'=>$grade_id,'r.subject_id'=>$subject_id])
            ->select('r.id','r.status','o.newname')->get();*/
        exit(\GuzzleHttp\json_encode(['status'=>1,'data'=>$data]));
    }

}