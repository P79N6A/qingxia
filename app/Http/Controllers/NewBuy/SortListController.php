<?php

namespace App\Http\Controllers\NewBuy;

use App\AWorkbookNew;
use App\LocalModel\NewBuy\New1010;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewGoodsTrue;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\NewBuy\NewSort;
use App\LocalModel\NewBuy\NewSortSearchName;
use App\localModel\NewBuy\NewGoodsFind;
use DB;
use App\Utils\Search;
use App\Utils\SphinxClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SortListController extends Controller
{

    public function _getStatus($book_id,$book_name)
    {
        //0无 1已匹配 2.已有 3.退货 4.已录入 5.已上线  6已买
        #a_workbook_1010_0505 判断无或已有
        #a_workbook_new 判断已购买，退货，已录入，已上线
        $now_status = 0;
        #$book_info = NewOnly::find($book_id,['newname']);
//        $count = New1010::where(['newname'=>$book_name,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->select('id','cover')->orderBy('id','asc')->get();
//        if(count($count)>0){
//            foreach ($count as $book){
//                if($book->id>1000000 && strpos($book->cover, '/pic19/') && !strpos($book->cover, '/new/')){
//                    $now_status = 5;
//                }else{
//                    $now_status = 2;
//                }
//            }
//        }
        if($now_status==5){
            return $now_status;
        }

        $buy_info = AWorkbookNew::where('from_only_id','>',0)->where(['from_only_id'=>$book_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->first(['now_status']);
        if($buy_info){
            $now_status = $buy_info->now_status;
        }
        return $now_status;
    }

    public function index($order='find')
    {
        if($order=='find'){
            $data['all_sort'] = NewGoodsFind::select(['sort_id','sort_name','find_num','find_num_new','find_pinduoduo','search_word'])
                ->where('find_num_new','<>',0)
                ->with('hasFindBooks:sort_id,find_num')
                ->with(['hasOnlyBooks'=>function($query){
                    return $query->from('a_workbook_only')->leftJoin('a_book_bought_record as r','a_workbook_only.id','r.only_id')->where('r.status',0)->where('a_workbook_only.need_buy',1)->where(function ($query){
                        $query->where('a_workbook_only.volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('a_workbook_only.volumes_id',3);
                    })->select('a_workbook_only.id','a_workbook_only.sort');
                }])->orderBy('id','asc')->paginate(20);
            foreach($data['all_sort'] as $k=>$item){
                foreach($item->hasFindBooks as $k2=>$v2){
                    $data['all_sort'][$k]['find_book']+=$v2->find_num;
                }
            }
            //dd($data['all_sort']);
        }elseif($order=='sort'){
            $data['all_sort'] = NewSort::select(['id','sort_id','sort_name','has_hd','has_kd'])
                ->with('hasFindBook_new:sort_id,find_num')
                ->with(['hasOnlyBooks'=>function($query){
                    return $query->from('a_workbook_only')->leftJoin('a_book_bought_record as r','a_workbook_only.id','r.only_id')->where('r.status',0)->where('a_workbook_only.need_buy',1)->where(function ($query){
                        $query->where('a_workbook_only.volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('a_workbook_only.volumes_id',3);
                    })->select('a_workbook_only.id','a_workbook_only.sort');
                }])
                ->with('hasFindBook:sort_id,find_num,find_num_new,find_pinduoduo,search_word')
                ->orderBy('id','asc')->paginate(20);

            foreach($data['all_sort'] as $k=>$item){
                foreach($item->hasFindBook_new as $k2=>$v2){
                    $data['all_sort'][$k]['find_book']+=$v2->find_num;
                }
            }
        }



        //    $onlyIds = $sort->hasOnlyBooks->pluck('id');
//            if(count($onlyIds)>0){
//                $data['hasGoods'][$key] = NewGoodsTrue::whereIn('jiajiao_id',$onlyIds)->count();
//            }else{
//                $data['hasGoods'][$key] = 0;
//            }

        foreach ($data['all_sort'] as $key=>$sort){
            $data['has_shang'][$key] = NewOnly::where(['sort'=>$sort->sort_id,"volumes_id"=>1])->count();
            $data['has_xia'][$key] = NewOnly::where(['sort'=>$sort->sort_id,"volumes_id"=>2])->count();
            $data['has_quan'][$key] = NewOnly::where(['sort'=>$sort->sort_id,"volumes_id"=>3])->count();
            //$data['hasGoods'][$key] = NewGoodsTrue::from('a_book_goods_true as t')->leftJoin('a_workbook_only as o','t.jiajiao_id','o.id')->where([['o.need_buy',1],['o.volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id],['t.sort',$sort->sort_id],['t.status','>=',0]])->select(DB::raw('distinct t.jiajiao_id'))->get()->count();
            //$isbn_arr[$key]=NewBoughtRecord::from('a_book_bought_record as r')->leftJoin('a_workbook_only as o','r.only_id','o.id')->leftJoin('a_workbook_1010 as b','o.book2018','b.id')->where(['r.sort'=>$sort->sort_id,"r.status"=>0])->select('b.isbn')->get();
            //$data['hasPreg'][$key] = NewGoodsTrue::where(['sort'=>$sort->sort_id,'status'=>1])->count();
            $data['hasBought'][$key] = NewBoughtRecord::where([['sort',$sort->sort_id],['version_year',cache('now_bought_params')->where('uid',auth()->id())->first()->version_year],['status','>',3]])->where(function ($query){
                return $query->where('volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('volumes_id',3);
            })->count();
        }

        //dd($data['hasBought']);
        $search=new Search;
        $search->set_hostip('192.168.0.130')->set_index('a_book_goods')->set_filter('is_read',[0]);
       /* foreach($isbn_arr as $k=>$v){
            $isbn_str='';
            foreach($v as $k2=>$isbn){
                $isbn_str.=$isbn->isbn.'|';
            }
            $isbn_str=rtrim($isbn_str,'|');
            $isbn_arr=array_unique(array_filter(explode('|',$isbn_str)));
            $isbn_str_new='';
            foreach($isbn_arr as $isbn_new){
                $isbn_str_new.=$isbn_new.'|';
            }
            $data['isbnArr'][$k]=rtrim($isbn_str_new,'|');
            if($data['isbnArr'][$k]==''){
                $data['hasGoods'][$k]=0;
            }else{
                $list[$k]=$search->get($data['isbnArr'][$k]);
                $data['hasGoods'][$k]=$search->total();
            }
        }*/
        //dd($data);
         $data['order']=$order;
        return view('new_buy.sort_list',compact('data'));
    }

    public function sort_list($sort_id=0,$version_id=-1)
    {
        $data['buy_status_id'] = [0=>['text'=>'暂无','color'=>'bg-red disabled'],1=>['text'=>'已匹配','color'=>'bg-yellow disabled'],2=>['text'=>'已有','color'=>'bg-teal disabled'],3=>['text'=>'退货','color'=>'bg-yellow-active'],4=>['text'=>'已录','color'=>'bg-purple disabled'],5=>['text'=>'已上','color'=>'bg-green-active'],6=>['text'=>'已买','color'=>'bg-blue-active']];
        $data['now_sort'] = NewSort::find($sort_id,['sort_id','sort_name']);

        if(!$data['now_sort']){
            die('请先添加系列');
        }
        $data['now_sort_id'] = $data['now_sort']->sort_id;
        $data['now_sort_name'] = $data['now_sort']->sort_name;
        $data['now_all_version'] = NewOnly::where(['sort'=>$data['now_sort_id'],'is_abolished'=>0])->where(function ($query){
            $query->where('volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('volumes_id',3);
        })->where([['grade_id','>=',3]])->select('version_id',DB::raw('count(version_id) as num'))->with('hasVersion:id,name')->groupBy('version_id')->orderBy('num','desc')->get();
        if($version_id!=-1){
            $data['now_version_id'] = $version_id;
        }else{
            if(count($data['now_all_version'])>0){
                $data['now_version_id'] = $data['now_all_version'][0]->version_id;
            }else{
                $data['now_version_id'] = 0;
            }
        }


        $sort_search_names = NewSortSearchName::where('sort',$data['now_sort_id'])->select('search_type','search_name')->get();
        $data['sort_search_names'] = $sort_search_names->groupBy('search_type');

        $data['now_version_name'] = cache('all_version_now')->where('id',$data['now_version_id'])->first()->name;
        $now_only_books = NewOnly::where(['sort'=>$data['now_sort_id'],'version_id'=>$data['now_version_id'],'is_abolished'=>0])->where(function ($query){
            return $query->where('volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('volumes_id',3);
        })->select()->withCount(['hasFound'=>function($query){
            $query->where('status','>=',0);
        }])->get();


        $data['now_only_books'] = collect($now_only_books)->groupBy('grade_id')->transform(function ($item,$key){
                return $item->groupBy('subject_id');
        });


        return view('new_buy.sort_detail',compact('data'));
    }



    public function only($sort_id,$grade_id,$subject_id,$volumes_id,$version_id)
    {

        $condition['sort'] = $sort_id;
        $condition['grade_id'] = $grade_id;
        $condition['subject_id'] = $subject_id;
        $condition['version_id'] = $version_id;
        $condition['is_abolished'] = 0;
        //INSERT INTO `workbook`.`a_workbook_1010_0505` (`id`, `bookname`, `newname`, `grade_id`, `subject_id`, `volumes_id`, `version_id`, `sort`, `isbn`, `version_year`, `cover`, `grade_name`, `subject_name`, `volume_name`, `version_name`, `sort_name`, `addtime`, `hdid`, `redirect_id`, `bookcode`, `bookcode_1010`, `cover_photo`, `cover_photo_thumbnail`, `addtype`, `relatedid`, `clicks`, `fid`, `status`, `done`, `collect_count`, `hdcount`, `uids`, `press`, `banci`, `yinci`, `des`, `reward_credit`, `need_count`, `oldclicks`, `editable`, `t_status`, `zhuanti`, `oss`, `is_buy`, `pingbi`, `index_status`, `onlyname`, `onlyid`, `province`, `stay`, `away`, `rating`, `rating_time`, `book_confirm`, `ssort_id`, `jiexi`, `diandu`, `gendu`, `tingxie`, `cip_photo`) VALUES ();

        $data['all_only'] = NewOnly::where($condition)->where(function ($query){
            return $query->where('volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('volumes_id',3);
        })->select('id','newname')->with(['hasBooks'=>function($query){
            return $query->select('id','bookname','newname','isbn','cover','version_year','collect_count')->orderBy('version_year','desc');
        }])->get();

        return view('new_buy.only_detail',compact('data'));
    }


    public function only_name($newname)
    {


        $data['all_repeat_books'] = New1010::where(['newname'=>$newname])->select('id','bookname','cover','cip_photo','collect_count','version_year','isbn','redirect_id','book_confirm')->orderBy('version_year','desc')->withCount('hasAnswers')->get();
//        dd($data['all_repeat_books']);
//        $data['grade_id'] = $grade_id;
//        $data['subject_id'] = $subject_id;
//        $data['volumes_id'] = $volumes_id;
//        $data['version_id'] = $version_id;

        return view('new_buy.newname_detail',compact('data'));
    }
}
