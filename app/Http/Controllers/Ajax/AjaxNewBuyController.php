<?php

namespace App\Http\Controllers\Ajax;

use App\AWorkbook1010;
use App\AWorkbookNew;
use App\Http\Controllers\Chart\TaobaoBookController;
use App\LocalModel\AWorkbook1010Test;
use App\LocalModel\NewBuy\New1010;
use App\LocalModel\NewBuy\NewBoughtParams;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewBoughtReturn;
use App\LocalModel\NewBuy\NewGoods;
use App\LocalModel\NewBuy\NewGoodsTrue;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\NewBuy\NewOnlyDelete;
use App\LocalModel\NewBuy\NewSort;
use App\LocalModel\NewBuy\NewSortSearchName;
use App\Sort;
use Auth;
use Cache;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AjaxNewBuyController extends Controller
{
    public function ajax(Request $request,$type='')
    {
        switch ($type){
            //发现新书
            case 'find_new':
                $data_type = $request->data_type;
                $data_id = $request->data_id;
                if(!in_array($data_type, ['hd','kd'],true)){
                    return false;
                }

                $now_status_hd = NewSort::where(['id'=>$data_id])->select('has_'.$data_type)->first();
                $now_status = $now_status_hd->toArray()['has_'.$data_type];

                if($now_status==1){
                    if(NewSort::where(['id'=>$data_id])->update(['has_'.$data_type=>0])){
                        return return_json();
                    }else{
                        return return_json_err();
                    }
                }else{
                    if(NewSort::where(['id'=>$data_id])->update(['has_'.$data_type=>1])){
                        return return_json();
                    }else{
                        return return_json_err();
                    }
                }


                break;


            //更换卷册
            case 'change_volume':
                $uid = Auth::id();
                $volumes_id = $request->volumes_id;
                if(NewBoughtParams::where(['type'=>'volumes_year','uid'=>$uid])->count()==0){
                    $new['volumes_id'] = $volumes_id;
                    $new['version_year'] = cache('now_bought_params')->where('uid',0)->first()->version_year;
                    $new['uid'] = $uid;
                    $new['type'] = 'volumes_year';
                    NewBoughtParams::create($new);
                }else{
                    NewBoughtParams::where(['type'=>'volumes_year','uid'=>$uid])->update(['volumes_id'=>$volumes_id]);
                }
                Cache::forget('now_bought_params');

//                    Cache::rememberForever('now_bought_params_'.auth()->id(), function (){
//                        if(NewBoughtParams::where(['type'=>'volumes_year','uid'=>auth()->id()])->count()==0){
//                            $now_uid = 0;
//                        }else{
//                            $now_uid = auth()->id();
//                        }
//                        return NewBoughtParams::where(['type'=>'volumes_year','uid'=>$now_uid])->select()->first();
//                    });
                    #dd($now_bought_params);



                return return_json();
                break;

            //新增系列
            case 'add_new_sort':
                $sort_id = $request->sort_id;
                if(NewSort::find($sort_id)){
                    return return_json([],0,'已有该系列');
                }
                $sort_name = cache('all_sort_now')->where('id',$sort_id)->first()->name;
                if(NewSort::create(['sort_id'=>$sort_id,'sort_name'=>$sort_name])){
                    return return_json();
                }
                break;


            case 'create_new_sort':
                $sort_name = $request->sort_name;

                if(strlen(trim($sort_name))<1 || Sort::where(['name'=>$sort_name])->count()>0){
                    return return_json_err();
                }else{
                    $new_sort = Sort::create(['name'=>$sort_name]);
                    if($new_sort){
                        NewSort::create(['sort_id'=>$new_sort->id,'sort_name'=>$sort_name]);
                        Cache::forget('all_sort_now');
                        $all_sort = Cache::rememberForever('all_sort_now',function (){
                            return Sort::all(['id','name']);
                        });
                        return return_json(['new_id'=>$new_sort->id]);
                    }

                }
                break;

            //新增分类
            case 'add_new_only':
                $id = $request->now_id;
                $version_id = $request->now_version;
                $sort_id = $request->now_sort;
                $now_info = explode('_', str_replace('99999_','', $id));
                $data_grade = $now_info[0];
                $data_subject = $now_info[1];
                $only['newname'] = $request->now_name;
                $only['grade_id'] = $data_grade;
                $only['subject_id'] = $data_subject;
                if(strpos($only['newname'], '全一册')){
                    $only['volumes_id'] = 3;
                }else{
                    $only['volumes_id'] = cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id;
                }

                $only['version_id'] = $version_id;
                $only['sort'] = $sort_id;
                $only['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;

                //$only['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                $only['need_buy'] = 1;
                if($new_only = NewOnly::create($only)){
                    return return_json(['new_only_id'=>$new_only->id,'new_only_newname'=>$only['newname']]);
                }else{
                    return return_json([],0,'新增失败');
                }
                break;

            //abolished 新增购买
            case 'confirm_buy':
                #data_id,book_name,version_id
                $id = $request->data_id;
                $book_name = $request->book_name;
                $version_id = $request->now_version_id;
                $sort_id = $request->sort_id;
                $data_id = 0;
                if(starts_with($id, '99999')){
                    $now_info = explode('_', str_replace('99999_','', $id));
                    if(count($now_info)===2){
                        $data_version = $version_id;
                        $data_grade = $now_info[0];
                        $data_subject = $now_info[1];
                        //新增only表记录
                        #INSERT INTO `workbook`.`a_workbook_only` (`id`, `bookname`, `newname`, `grade_id`, `subject_id`, `volumes_id`, `version_id`, `sort`, `collect2018`, `collect2017`, `collect2016`, `collect2015`, `collect2014`, `hd2014`, `hd2015`, `hd2016`, `cover`, `isbn`, `version_year`) VALUES ('1223020', '', '基本功训练四年级英语下册冀教版', '4', '3', '2', '5', '731', '8', '0', '0', '0', '0', '0', '0', '17', 'http://thumb.1010pic.com/pic19/1223020/cover/fe02e214d28385ac70ff9d5446b7f8cc.jpg', '9787554542064', '0');
                        $only['newname'] = str_replace(cache('now_bought_params')->where('uid',auth()->id())->first()->version_year.'年', '', $book_name);
                        $only['grade_id'] = $data_grade;
                        $only['subject_id'] = $data_subject;
                        if(strpos($only['newname'], '全一册')){
                            $only['volumes_id'] = 3;
                        }else{
                            $only['volumes_id'] = cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id;
                        }
                        $only['version_id'] = $version_id;
                        $only['sort'] = $sort_id;
                        $only['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                        $new_only = NewOnly::create($only);


                        $data['sort'] = $sort_id;
                        $book_name = str_replace(config('workbook.grade'), config('workbook.grade')[$data_grade], $book_name);
                        $book_name = str_replace(config('workbook.subject_1010'), config('workbook.subject_1010')[$data_subject], $book_name);
//                        $book_name = str_replace(['2014年','2015年','2016年','2017年'], '2018年', $book_name);
                        //$sort_name = ATongjiBuy::where([['jj',$request->jj],['book_id','0'],['sort',$request->sort]])->first();
                        //$data['newname'] = str_replace(['2014年','2015年','2016年','2017年','2018年'], '', $book_name);
                        $data['newname'] = str_replace(cache('now_bought_params')->where('uid',auth()->id())->first()->version_year.'年', '', $book_name);
                        $data['bookname'] = $book_name;
                        $data['status'] = 1;
                        $data['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;

                        $data['grade_id'] = $data_grade;
                        $data['subject_id'] = $data_subject;
                        $data['volumes_id'] = $only['volumes_id'];
                        $data['version_id'] = $data_version;
                        $data['isbn'] = $request->isbn;
                        $data['from_only_id'] = $new_only->id;
                        $data['now_status'] = 1;
                    }
                    else{
                        //替换年级科目上下册
                        $data_id = $now_info[0];
                        $now = NewOnly::find($data_id);
                        $data['sort'] = $now->sort;
                        $book_name = str_replace(config('workbook.grade'), config('workbook.grade')[$now->grade_id], $book_name);
                        $book_name = str_replace(config('workbook.subject_1010'), config('workbook.subject_1010')[$now->subject_id], $book_name);
                        //$book_name = str_replace(['2014年','2015年','2016年','2017年'], '2018年', $book_name);
                        $data['newname'] = str_replace(cache('now_bought_params')->where('uid',auth()->id())->first()->version_year.'年', '', $book_name);
                        $data['bookname'] = $book_name;
                        $data['status'] = 1;
                        $data['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                        $data['grade_id'] = $now->grade_id;
                        $data['subject_id'] = $now->subject_id;
                        $data['volumes_id'] = $now->volumes_id;
                        $data['version_id'] = $version_id;
                        $data['from_only_id'] = $data_id;
                        $data['now_status'] = 1;
                    }
                    if(AWorkbookNew::where($data)->count()>0){
                        //ATongjiBuy::where($data)->delete();
                        return response()->json(['status'=>1,'type'=>'cancel']);
                    }
                    $data['grade_name'] = '';
                    $data['subject_name'] = '';
                    $data['volume_name'] = '';
                    $data['version_name'] = '';
                    $data['sort_name'] = '';
                    $data['ssort_id'] = 0;
                    $data['update_uid'] = Auth::id();
                    $data['updated_at'] = date('Y-m-d H:i:s',time());
                    if(AWorkbookNew::max('id')>1000000){
                        $data['id'] = AWorkbookNew::max('id')+1;
                    }else{
                        $data['id'] = 1000000+AWorkbookNew::max('id');
                    }
                    if($a = AWorkbookNew::create($data)){
                        //优化设计/2018年_六年级_英语_下册_译林版_9787549939121/
                        make_answer_dir($data['id']);
                        //取消待买状态
                        NewOnly::where('id',$data_id)->update(['need_buy'=>0]);
                        //更新以前练习册状态
//                        AWorkbookNew::where([['id','<',1000000],['grade_id',$data['grade_id']],['subject_id',$data['subject_id']],['volumes_id',$data['volumes_id']],['version_id',$data['version_id']],['sort',$data['sort']]])->update(['has_update'=>1]);
                        return response()->json(['status'=>1,'type'=>'new','only_id'=>$data['from_only_id'],'new_id'=>$data['id'],'new_name'=>$data['bookname'],'only_name'=>$data['newname']]);
                    };
                }
                break;

            //更改状态
            case 'change_status':
                $from_only_id = $request->now_only_id;
                $now_status = $request->now_status;
                $now_type = $request->now_type;
                if($now_type==='preg'){
                    $old_status = 1;
                }elseif($now_type==='bought'){
                    $old_status = 6;
                }else{
                    return return_json([],0,'请求失败');
                }
                if(intval($now_status)===3){
                    $now_new_book = AWorkbookNew::where(['from_only_id'=>$from_only_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year,'now_status'=>$old_status])->first(['id','bookname','version_id','sort']);

                    if($now_new_book){
                        $version_name = cache('all_version_now')->find($now_new_book->version_id)->name;
                        $book_dir = '//QINGXIA23/book4_new/'.$now_new_book->sort . '_' . cache('all_sort_now')->find($now_new_book->sort)->name . '/' . $now_new_book->bookname . '_' . $now_new_book->id;
                        $cover_dir = $book_dir.'/cover';
                        if(is_dir($cover_dir)){
                            try{
                                \File::deleteDirectory($cover_dir);
                            }catch(\Exception  $e){
                                \Log::info($book_dir.'not empty');
                            }
                        }
                        if(is_dir($book_dir)){
                            try{
                                \File::deleteDirectory($book_dir);
                            }catch(\Exception  $e){
                                \Log::info($book_dir.'not empty');
                            }
                        }
                        AWorkbookNew::where(['from_only_id'=>$from_only_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year,'now_status'=>$old_status])->delete();
                    }
                    if($old_status===6){
                        $now_book = NewBoughtRecord::where(['only_id'=>$from_only_id,'status'=>$old_status])->first();
                        $return['only_id'] = $now_book->only_id;
                        $return['subject_id'] = $now_book->subject_id;
                        $return['grade_id'] = $now_book->grade_id;
                        $return['volumes_id'] = $now_book->volumes_id;
                        $return['version_year'] = $now_book->version_year;
                        $return['sort'] = $now_book->sort;
                        $return['shop_id'] = $now_book->shop_id;
                        $return['goods_id'] = $now_book->goods_id;
                        $return['goods_price'] = $now_book->goods_price;
                        $return['goods_fee'] = $now_book->goods_fee;
                        $return['goods_according_to'] = $now_book->goods_according_to;
                        $return['uid'] = $now_book->uid;
                        $return['bought_at'] = $now_book->bought_at;
                        $return['returned_at'] = date('Y-m-d H:i:s',time());
                        NewBoughtReturn::create($return);
                    }
                    NewBoughtRecord::where(['only_id'=>$from_only_id,'status'=>$old_status])->update(['status'=>3,'shop_id'=>0,'goods_id'=>0,'goods_price'=>0,'goods_fee'=>0,'uid'=>Auth::id()]);
                }else{
                    AWorkbookNew::where(['from_only_id'=>$from_only_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year,'now_status'=>$old_status])->update(['now_status'=>$now_status]);
                    $updata['status'] = $now_status;
                    if(intval($now_status)===6){
                        $updata['bought_at'] = date('Y-m-d H:i:s',time());
                    }
                    NewBoughtRecord::where(['only_id'=>$from_only_id,'status'=>$old_status])->update($updata);
                }
                return return_json();
                break;

            //更改newname
            case 'change_newname':
                $now_id = $request->now_id;
                $now_name = $request->now_name;
                $now_only_info = NewOnly::find($now_id);
                $old_name = $now_only_info->newname;
                if($now_name==$old_name){
                    return return_json([],0,'保存名称与原名一致');
                }
                //1.更改0505所有newname  bookname
                //2.如果only表无newname记录直接更新 有记录则移动原有记录至delete表
                $all_books = AWorkbook1010::where(['newname'=>$old_name])->select('bookname','id')->get();
                foreach ($all_books as $book){
                    $new_bookname = str_replace($old_name, $now_name, $book->bookname);
                    AWorkbook1010::where('id',$book->id)->update(['bookname'=>$new_bookname]);
                }
                AWorkbook1010::where(['newname'=>$old_name])->update(['newname'=>$now_name]);
                if(NewOnly::where(['newname'=>$now_name])->count()==0){
                    NewOnly::where(['id'=>$now_id])->update(['newname'=>$now_name]);
                }else{
                    $all = NewOnly::find($now_id)->toArray();
                    if(NewOnlyDelete::where('id',$now_id)->count()>0 || NewOnlyDelete::create($all)){
                        NewOnly::where(['id'=>$now_id])->delete();
                    }
                }
                return return_json();

            //替换所有newname
            case 'change_all_name':
                $now_sort_id = $request->sort_id;
                //$now_version_id = $request->version_id;
                $grade_select = intval($request->grade_select);
                $subject_select = intval($request->subject_select);
                $version_select = intval($request->version_select);

                $old_name = $request->old_name;  //要替换的字
                $new_name = $request->new_name;  //替换后的字
                if($new_name==$old_name){
                    return return_json([],0,'保存名称与原名一致');
                }


                $all_books = AWorkbook1010::where([['sort',$now_sort_id],['bookname','like','%'.$old_name.'%']])->where(function ($query) use($grade_select,$subject_select,$version_select){
                    if($grade_select>=1 && $grade_select<=3){
                        if($grade_select==1){
                            $query->whereIn('grade_id',[1,2,3,4,5,6]);
                        }else if($grade_select==2){
                            $query->whereIn('grade_id',[7,8,9]);
                        }else{
                            $query->where('grade_id','>',9);
                        }
                    }
                    if($subject_select>=1 && $subject_select<=9){
                        $query->where('subject_id',$subject_select);
                    }
                    if(intval($version_select)!=999){
                        $query->where('version_id',$version_select);
                    }
                    $query->where('id','>',0);
                })->where(function ($query){
                    return $query->where('volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('volumes_id',3);
                })->select('id','bookname','newname')->get();


                if(count($all_books)>0){
                    foreach ($all_books as $book){
                        $new_bookname = str_replace($old_name, $new_name, $book->bookname);
                        $new_newname = str_replace($old_name, $new_name, $book->newname);
                        AWorkbook1010::where(['id'=>$book->id])->update(['newname'=>$new_newname,'bookname'=>$new_bookname]);
                    }
                    $all_only = $all_books->pluck('newname')->unique();
                    foreach ($all_only as $only_name){
                        if($only_name){
                            $new_onlyname = str_replace($old_name, $new_name, $only_name);
                            if(NewOnly::where(['newname'=>$new_onlyname])->count()==0){
                                NewOnly::where(['newname'=>$only_name])->update(['newname'=>$new_onlyname]);
                            }else{
                                $all = NewOnly::where(['newname'=>$only_name])->select()->get()->toArray();
                                foreach ($all as $single){
                                    if(NewOnlyDelete::where('id',$single['id'])->count()>0 || NewOnlyDelete::create($single)){
                                        NewOnly::where(['id'=>$single['id']])->delete();
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $all_new_name = NewOnly::where([['sort',$now_sort_id],['newname','like','%'.$old_name.'%']])->where(function ($query) use($grade_select,$subject_select,$version_select){
                        if($grade_select>=1 && $grade_select<=3){
                            if($grade_select==1){
                                $query->whereIn('grade_id',[1,2,3,4,5,6]);
                            }else if($grade_select==2){
                                $query->whereIn('grade_id',[7,8,9]);
                            }else{
                                $query->where('grade_id','>',9);
                            }
                        }
                        if($subject_select>=1 && $subject_select<=9){
                            $query->where('subject_id',$subject_select);
                        }
                        if(intval($version_select)!=999){
                            $query->where('version_id',$version_select);
                        }
                        $query->where('id','>',0);
                    })->where(function ($query){
                        return $query->where('volumes_id',cache('now_bought_params')->where('uid',auth()->id())->first()->volumes_id)->orWhere('volumes_id',3);
                    })->select('id','newname')->get();

                    $all_only = $all_new_name->pluck('newname')->unique();
                    foreach ($all_only as $only_name){
                        if($only_name){
                            $new_onlyname = str_replace($old_name, $new_name, $only_name);
                            if(NewOnly::where(['newname'=>$new_onlyname])->count()==0){
                                NewOnly::where(['newname'=>$only_name])->update(['newname'=>$new_onlyname]);
                            }else{
                                $all = NewOnly::where(['newname'=>$only_name])->select()->get()->toArray();
                                foreach ($all as $single){
                                    if(NewOnlyDelete::where('id',$single['id'])->count()>0 || NewOnlyDelete::create($single)){
                                        NewOnly::where(['id'=>$single['id']])->delete();
                                    }
                                }
                            }
                        }
                    }
                }
                return return_json();
                break;

            //获取状态链接
            case 'get_status_link':
                //买返回a_workbook_new id
                //有返回a_workbook_1010_new id 或 a_workbook_1010_0505 id
                //录返回a_workbook_new id
                //上返回a_workbook_1010_new id 或 a_workbook_1010_0505 id

                //1,2,4,5 买,有,录,上
                $now_status = intval($request->now_status);
                $now_only_id = $request->only_id;
                $new_now = AWorkbookNew::where(['from_only_id' => $now_only_id, 'version_year' => cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->first();
                $new_id = 0;
                if($now_status==1 || $now_status==4) {
                    $new_id = $new_now->id;
                }else{
                    //有则取新  无则取0505

                    if($new_now){
                        $new_buy = AWorkbook1010Test::where('from_id',$new_now->id)->first();
                        if($new_buy){
                            $new_id = $new_buy->id;
                        }else{
                            $new_name = NewOnly::find($now_only_id)->newname;
                            $new_id = AWorkbook1010::where(['newname'=>$new_name,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->first()->id;
                        }
                    }else{
                        $new_name = NewOnly::find($now_only_id)->newname;

                        $new_id = AWorkbook1010::where(['newname'=>$new_name,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->first()->id;
                    }
                }
                return return_json(['now_id'=>$new_id]);

            //标记待购买
            case 'mark_to_buy':
                $only_id = $request->only_id;
                $now_buy_status = NewOnly::find($only_id);
                $has_record = NewBoughtRecord::where([['only_id',$only_id],['status','!=',3]])->count();

                if($now_buy_status && $now_buy_status->need_buy)
                {
                    $now_buy_status->need_buy = 0;
                    NewBoughtRecord::where([['only_id',$only_id],['status',0]])->delete();
                    $now_buy_status->save();
                }else{
                    if($has_record>0){
                        return return_json_err(0,'待购买列表已有记录');
                    }else{
                        $now_buy_status->need_buy = 1;
                        $record['only_id'] = $only_id;
                        $record['uid'] = Auth::id();
                        $record['sort'] = $now_buy_status->sort;
                        $record['grade_id'] = $now_buy_status->grade_id;
                        $record['subject_id'] = $now_buy_status->subject_id;
                        $record['volumes_id'] = $now_buy_status->volumes_id;
                        $record['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                        NewBoughtRecord::create($record);
                        $now_buy_status->save();
                    }

                }
                return return_json();
                break;

            //abolished
            case 'get_found_books':
                $only_id = $request->only_id;
                $all_found_books = NewGoodsTrue::where([['jiajiao_id',$only_id],['status','!=',-1]])->select('id','view_price','view_fee','nick','shopLink','title','detail_url','pic_url','status')->orderBy('status','desc')->orderBy('addtime','desc')->get();
                return return_json($all_found_books);
                break;

            case 'save_search_name':
                $sort = $request->sort_id;
                $search_name = $request->search_name;
                $search_type = $request->search_type;
                if(NewSortSearchName::where([['sort',$sort],['search_name',$search_name],['search_type',$search_type]])->count()>0){
                    return return_json([],0,'已有此记录');
                }
                if(NewSortSearchName::create(['sort'=>$sort,'search_name'=>$search_name,'search_type'=>$search_type])){
                    return return_json();
                }
                break;

            case 'remove_search_name':
                $sort = $request->sort_id;
                $search_name = $request->search_name;
                $search_type = $request->search_type;
                if(NewSortSearchName::where(['sort'=>$sort,'search_name'=>$search_name,'search_type'=>$search_type])->delete()){
                    return return_json();
                }
                break;

            //abolished
            case 'not_found_all':
                $only_id = $request->only_id;
                if(NewGoodsTrue::where('jiajiao_id',$only_id)->update(['status'=>-1])){
                    return return_json();
                }
                break;

            case 'mark_to_found':
                $now_id = $request->now_id;
                if(NewGoodsTrue::where('id',$now_id)->update(['status'=>1])){
                    return return_json();
                }
                break;

            //保存记录
            case 'save_record':
                $now_isbn = $request->now_isbn;
                $now_id = $request->now_id;
                $now_type= $request->now_type;
                $now_val = $request->now_val;
                if($now_type==='goods_according_to'){
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query1){
                        $query1->where('status',1)->orWhere('status',6);
                    })->update(['goods_according_to'=>$now_val]);
                }else if($now_type==='goods_price'){
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query1){
                        $query1->where('status',1)->orWhere('status',6);
                    })->update(['goods_price'=>$now_val]);
                }else if($now_type === 'goods_fee'){
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query1){
                        $query1->where('status',1)->orWhere('status',6);
                    })->update(['goods_fee'=>$now_val]);
                }else if($now_type === 'goods_id') {
                    ignore_user_abort();
                    set_time_limit(0);
                    ini_set('memory_limit', -1);

                    if (NewBoughtRecord::where([['only_id', $now_id], ['status', 0],['version_year',cache('now_bought_params')->where('uid',auth()->id())->first()->version_year]])->count() != 1) {
                        exit(json_encode(['status' => 0, 'msg' => '非待购买练习册']));
                    }
                    $only_id = $now_id;
                    $now = NewOnly::find($only_id);
                    if ($now) {
                        $data['sort'] = $now->sort;
                        $data['newname'] = $now->newname;
                        $data['bookname'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year . '年' . $data['newname'];
                        $data['status'] = 1;
                        $data['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                        $data['grade_id'] = $now->grade_id;
                        $data['subject_id'] = $now->subject_id;
                        $data['volumes_id'] = $now->volumes_id;
                        $data['version_id'] = $now->version_id;
                        $data['from_only_id'] = $only_id;
                        $now_isbn = NewBoughtRecord::where([['only_id', $now_id], ['status', 0],['version_year',cache('now_bought_params')->where('uid',auth()->id())->first()->version_year]])->first(['isbn']);
                        $data['isbn'] = $now_isbn?$now_isbn->isbn:'';
                        $data['now_status'] = 1;

                        if (AWorkbookNew::where($data)->count() > 0) {
                            AWorkbookNew::where($data)->delete();
                            //ATongjiBuy::where($data)->delete();
                            return response()->json(['status' => 0, 'type' => 'cancel']);
                        }
                        $data['grade_name'] = '';
                        $data['subject_name'] = '';
                        $data['volume_name'] = '';
                        $data['version_name'] = '';
                        $data['sort_name'] = '';
                        $data['ssort_id'] = 0;
                        $data['update_uid'] = \Auth::id();
                        $data['updated_at'] = date('Y-m-d H:i:s', time());
//                        if (AWorkbookNew::max('id') > 1000000) {
//                            $data['id'] = AWorkbookNew::max('id') + 1;
//                        } else {
//                            $data['id'] = 1000000 + AWorkbookNew::max('id');
//                        }
                        $a = AWorkbookNew::insertGetId($data);
                        if ($a) {
                            //优化设计/2018年_六年级_英语_下册_译林版_9787549939121/
                            $data['id'] = $a;
                            make_answer_dir($data['id']);
                            //取消待买状态
                            NewOnly::where('id', $only_id)->update(['need_buy' => 0]);
                            $goodsInfo = NewGoods::where([["detail_url", $now_val]])->first();

                            if ($goodsInfo) {
                                $updateData = [
                                    'shop_id' => $goodsInfo->shopLink,
                                    'goods_id' => $now_val,
                                    'goods_price' => $goodsInfo->view_price,
                                    'goods_fee' => $goodsInfo->view_fee,
                                    'bought_uid' => auth()->id(),
                                    'status' => 1
                                ];
                            }else{
                                $updateData = [
                                    'goods_id' => $now_val,
                                    'bought_uid' => auth()->id(),
                                    'status' => 1
                                ];
                            }
                            NewBoughtRecord::where([['only_id', $now_id], ['status', 0],['version_year',cache('now_bought_params')->where('uid',auth()->id())->first()->version_year]])->update($updateData);
                            return return_json(['goods_price' => $goodsInfo?$goodsInfo->view_price:0, 'goods_fee' => $goodsInfo?$goodsInfo->view_fee:0, 'shopLink' => $goodsInfo?$goodsInfo->shopLink:0, 'nick' => $goodsInfo?$goodsInfo->nick:'','new_id'=>$a->id]);


//                            return response()->json(['status'=>1,'type'=>'new','only_id'=>$data['from_only_id'],'new_id'=>$data['id'],'new_name'=>$data['bookname'],'only_name'=>$data['newname']]);
                        }else{
                            return return_json_err();
                        }


                    }
//                    $header = [
//                        'Accept' => 'text/plain, */*;q=0.01',
//                        'X-Requested-With' => 'XMLHttpRequest',
//                        'User-Agent' => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36 OPR/42.0.2393.85',
//                        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
//                        'DNT' => '1',
//                        'Accept-Encoding' => 'gzip, deflate, br',
//                        'Accept-Language' => 'zh-CN,zh',
//                    ];
//                    $http = new \GuzzleHttp\Client($header);
//                    $http->get('https://item.taobao.com/item.htm?id='.$now_id);
                }elseif($now_type === 'book_page') {
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query){
                        $query->where('status',1)->orWhere('status',6);
                    })->update(['book_page'=>$now_val]);
                }elseif($now_type === 'answer_page') {
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query){
                        $query->where('status',1)->orWhere('status',6);
                    })->update(['answer_page'=>$now_val]);
                }elseif($now_type === 'answer_status') {
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query){
                        $query->where('status',1)->orWhere('status',6);
                    })->update(['answer_status'=>$now_val]);
                }elseif($now_type === 'arrived'){
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->where(function ($query){
                        $query->where('status',6);
                    })->update(['arrived_at'=>date('Y-m-d H:i:s',time())]);
                }
                elseif($now_type=='now_isbn'){
                    NewBoughtRecord::where(['only_id'=>$now_id,'version_year'=>cache('now_bought_params')->where('uid',auth()->id())->first()->version_year])->update(['isbn'=>str_replace('-', '', $now_val)]);
                }
                break;

            case 'get_related_books':
                $cond['sort'] = $request->sort;
                $cond['grade_id'] = $request->grade;
                $cond['subject_id'] = $request->subject;
                $cond['status'] = 0;
                $result = NewBoughtRecord::where($cond)->select('only_id')->with('hasOnlyDetail:id,newname')->get();
                return return_json($result);
                break;

            //废除分类
            case 'abolish_only':
                //废除only_name
                $only_id = $request->only_id;
                if(NewOnly::where(['id'=>$only_id])->update(['is_abolished'=>1])){
                    return return_json();
                }
                break;

            //更改解析状态
            case 'analyze_status':
                $only_id = $request->only_id;
                $analyze_type = $request->analyze_type;
                if($analyze_type==='start'){
                    $analyze_status = 1;
                    $analyze['analyze_start_at'] = date('Y-m-d H:i:s',time());
                }else if($analyze_type==='end'){
                    $analyze_status = 2;
                    $analyze['analyze_end_at'] =date('Y-m-d H:i:s',time());
                }else{
                    return return_json_err();
                }
                $analyze['analyze_uid'] = Auth::id();
                $analyze['analyze_status'] = $analyze_status;
                NewBoughtRecord::where([['only_id',$only_id],['answer_status','>',1]])->update($analyze);
                return return_json();
        }
        return return_json();
    }
}
