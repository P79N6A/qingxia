<?php

namespace App\Http\Controllers\ManageNew;

use App\AWorkbook1010;
use App\BookVersionType;
use App\LocalModel\HdbookTemp;
use App\LocalModel\IsbnTemp;
use App\LocalModel\PreUserTemp;
use App\LocalModel\TaobaoTemp;
use App\LocalModel\TaskUid;
use App\LocalModel\WorkbookTemp;
use App\Sort;
use App\Volume;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ManageIsbnTempController extends Controller
{
    public function index($type='pending',$buy='arrange',$single_id=0)
    {

        //INSERT INTO `mainjiajiao`.`hd_book_temp` (`id`, `isbn`, `answerType`, `audit`, `bookName`, `bookVersionId`, `coverImage`, `coverImageThumb`, `createTime`, `gradeId`, `objectId`, `ownerId`, `ownerNickname`, `pathCount`, `scanCoverFlag`, `sex`, `sortId`, `subjectId`, `userGradeId`, `userId`, `visibleType`, `volumes`, `status`, `done`, `addtime`) VALUES ('0', '9787553906973', '2', '0', '2017年一本中考地理新课标版', '24', 'book_photo_path/2016-09-21/5E9BFBE5F3CD0FFD8FEA9E8617F07906.jpg', '2016-09-21/5E9BFBE5F3CD0FFD8FEA9E8617F07906.jpg', '2016-09-21 16:31:21', '9', '100001', '3680', '荆雁墨', '0', '1', '0', '13', '7', '0', '-1', '0', '4', '0', '1', '2018-03-16 18:28:14');

        //INSERT INTO `mainjiajiao`.`pre_m_workbook_user_temp` (`id`, `onlyname`, `sort_name`, `book_id`, `to_book_id`, `subject_id`, `grade_id`, `volumes_id`, `version_id`, `version_year`, `cover_img`, `cip_img`, `chapter_img`, `isbn`, `version`, `sort_id`, `banci`, `yinci`, `relatedid`, `credit`, `need_num`, `up_uid`, `status`, `addtime`, `source`, `hdid`, `jiexi`, `diandu`, `gendu`, `tingxie`, `update_uid`) VALUES ('10000017', '294|1|9|8|0', '智慧课堂好学案', '0', '0', '9', '1', '8', '0', '0', 'pic18/user_photo/20161226/dac118c718ac97d82df5086d4cd6ace9.jpg', 'pic18/user_photo/20161226/000206ddcdc239d494013e5fca233738.jpg', NULL, '9787535392435', '人教版/R/RJ', '294', '', '', NULL, '0', '1', '1810286', '2', '2016-08-24 18:10:11', NULL, '0', '0', '0', '0', '0', '0');

        //INSERT INTO `mainjiajiao`.`a_workbook_1010_tmp` (`id`, `newisbn`, `bookname`, `bookcode`, `bookcode_1010`, `isbn`, `cover`, `cover_photo`, `cover_photo_thumbnail`, `addtype`, `relatedid`, `clicks`, `grade_id`, `subject_id`, `volumes_id`, `version_id`, `version_year`, `fid`, `status`, `done`, `collect_count`, `uid`, `uids`, `press`, `banci`, `yinci`, `des`, `reward_credit`, `need_count`, `addtime`, `oldclicks`, `editable`, `t_status`, `zhuanti`, `oss`, `is_buy`, `sort`, `hdid`, `pingbi`, `index_status`, `onlyname`, `onlyid`, `province`, `stay`, `away`, `rating`, `rating_time`, `book_confirm`, `grade_name`, `subject_name`, `volume_name`, `version_name`, `sort_name`, `ssort_id`, `jiexi`, `diandu`, `gendu`, `tingxie`, `cip_photo`) VALUES ('3', '9787504141255', '2014年5年中考3年模拟初中语文八年级上册人教版', 'd78c971744a5fb18f552fe109b00174d', '01001c20', '9787504141255', 'http://thumb.1010pic.com/book_photo_path/2014-09-19/84dde50c-9cb9-4d09-834b-b39efea49165.jpg', '2014-09-19/84dde50c-9cb9-4d09-834b-b39efea49165.jpg', 'pic18/cover_photo/20140919/84dde50c9cb94d09834bb39efea49165.jpg', '1', '25,59,96,156,207,830,1399,2758,2678,2718,2719,2778,4616,5843,8600,2633,2636,2657,2659', '26403', '8', '1', '1', '0', '2014', '1', '1', '9', '1137', '0', NULL, NULL, NULL, NULL, NULL, '0', '0', '2015-09-06 16:54:12', '2320', '1', '0', '0', '1', '0', '127', '3376', '0', '9', '127|8|1|1|0', '1', '河南', '0', '50', '-75', '1523556002', '0', '八年级', '语文', '上册', '人教版', '5年中考3年模拟全练版', '10176', '0', '0', '0', '0', NULL);

        $data['buy'] = $buy;
        $uid = \Auth::id();
        //$data['task_uids'] = [8,11,17,19,20];
        $data['task_uids'] = [17,19,20];
//        if(!in_array($uid, [2])){
//            $whereRaw = get_task($uid, $data['task_uids']);
//        }else{
//            $whereRaw = '1=1';
//        }

        $whereRaw = '1=1';



        if($buy=='buy'){
            $where = [['resultcount',0],['bookname','!=','']];
        }else{
            $where = [['searchnum','>',100],['sort','>=',0],['grade_id','like','%,%']];
            #$where = [['isbn','=','9787555332053']];
        }

        $now_isbn = IsbnTemp::where($where)->whereRaw($whereRaw)->where('searchnum','>=',100)->select('id')->orderBy('isbn','asc')->get();


        $now_sort_split = [];
        foreach ($now_isbn->split(3) as $key=>$value){
            foreach ($value as $book){
                $now_sort_split[$key][] =$book->id;
            }
        }

        $data['task'] = $now_sort_split;
        $uid = \Auth::id();
        $in_where = [];
//        if($uid===8){
//            $in_where = $now_sort_split[0];
//            //$whereRaw = "id<=".$end;
//        }elseif($uid===11){
//            $in_where = $now_sort_split[1];
//            //$whereRaw = "id<=".$end;
//        }else
        if($uid===17) {
            $in_where = $now_sort_split[0];
            //$whereRaw = "id<=".$end;
        }elseif($uid===19){
            $in_where = $now_sort_split[1];
            //$whereRaw = "id<=".$end;
        }elseif($uid===20){
            $in_where = $now_sort_split[2];

            //$whereRaw = "id<=".$end;
        }
        else{
            $in_where = [];
        }



//        where(function ($query){
//            $query->where('sort','<',0)->orWhere('sort','');
//        })

        if($buy==='buy'){
            $page = Request::capture()->get('page');
            $data['all_isbn'] = \Cache::remember('all_isbn_buy_'.$page, 120, function () use ($whereRaw,$where){
                return IsbnTemp::where($where)->whereRaw($whereRaw)->select('id','isbn','searchnum','resultcount','version_year','grade_id','subject_id','volumes_id','version_id','sort','press','press_name')->orderBy('searchnum','desc')->paginate(5);
            });
        }else{
            if(count($in_where)>0) {
                $data['all_isbn'] = IsbnTemp::where($where)->whereRaw($whereRaw)->whereIn('id', $in_where)->where('searchnum', '>=', 100)->select('id', 'isbn', 'searchnum', 'resultcount', 'version_year', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'sort', 'press', 'press_name')->orderBy('isbn', 'asc')->paginate(5);
            }else{
                $data['all_isbn'] = IsbnTemp::where($where)->whereRaw($whereRaw)->where('searchnum', '>=', 100)->select('id', 'isbn', 'searchnum', 'resultcount', 'version_year', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'sort', 'press', 'press_name')->orderBy('isbn', 'asc')->paginate(5);
            }
        }


        foreach ($data['all_isbn'] as $key=>$book){
            $data['all_isbn'][$key]['has_user_book'] = PreUserTemp::where('isbn',$book->isbn)->select('id','isbn','sort_name','sort_id','grade_id','subject_id','volumes_id','version_id','cover_img')->take(6)->orderBy('id','desc')->get();
        }


//        ->with(['has_hd_book'=>function($query){
//        return $query->select('id','bookName','coverImage','isbn','gradeId','subjectId','volumes','bookVersionId','sortId')->get();
//    }])


//->with(['has_taobao_book'=>function($query2){
//        return $query2->select('id','isbn','title','pic_url')->get();
//    }])->with(['has_offical_book'=>function($query3){
//        return $query3->select('id','isbn','bookname','isbn','cover','grade_id','subject_id','volumes_id','version_id','sort')->get();
//    }])


        //with('has_hd_book:id,bookName,coverImage,isbn,gradeId,subjectId,volumes,bookVersionId,sortId')->with('has_user_book:id,sort_name,sort_id,grade_id,subject_id,volumes_id,version_id,cover_img')->with('has_taobao_book:id,title,pic_url')->

        $data['all_version'] = \Cache::remember('all_version_now', 3600, function (){
            return BookVersionType::all(['id','name']);
        });
        $data['all_volumes'] = \Cache::remember('all_volumes_now', 3600, function (){
            return Volume::all(['id','volumes']);
        });
        foreach ($data['all_version'] as $key=>$value){
            $version_array[$key]['id'] = $value->id;
            $version_array[$key]['text'] = $value->name;
        }
        foreach ($data['all_volumes'] as $key=>$value){
            $volume_array[$key]['id'] = $value->id;
            $volume_array[$key]['text'] = $value->volumes;
        }
        foreach (config('workbook.grade') as $key=> $value){
            if($key>0){
                $grade_array[$key-1]['id'] = $key;
                $grade_array[$key-1]['text'] = $value;
            }
        }
        foreach (config('workbook.subject_1010') as $key=> $value){
            if($key>0){
                $subject_array[$key-1]['id'] = $key;
                $subject_array[$key-1]['text'] = $value;
            }
        }
        $data['version_select'] = json_encode($version_array);
        $data['subject_select'] = json_encode($subject_array);
        $data['grade_select'] = json_encode($grade_array);
        $data['volume_select'] = json_encode($volume_array);

        return view('manage_new.temp_isbn.index',compact('data'));
    }

    public function by_sort($sort='')
    {
        $data['buy'] = '';
        $data['sort'] = $sort;
        $data['task_uids'] = [17,19,20];
        if(!in_array(Auth::id(), [2])){
            $whereRaw = get_task(Auth::id(), $data['task_uids'],'sort');
        }else{
            $whereRaw = '1=1';
        }

        if($sort){
            $whereRaw = 'sort='.$sort;
        }



       // dd(IsbnTemp::where('id',3)->with('has_sort_detail:sort,grade_id')->select('sort')->get());

        $data['all_temp_sort'] = IsbnTemp::where([['resultcount',0],['sort','>',0],['sort','not like','%,%']])->whereRaw($whereRaw)->select('sort',DB::raw('count(sort) as num'))->groupBy('sort')->with('has_sort:id,name')->with(['has_sort_detail'=>function($query){
                return $query->where('resultcount',0)->select('sort','isbn','cover_photo','grade_id','subject_id','version_id','volumes_id','searchnum')->orderBy('searchnum','desc')->get();
            }])->orderBy('num','desc')->paginate(1);

        $data['all_version'] = \Cache::remember('all_version_now', 3600, function (){
            return BookVersionType::all(['id','name']);
        });
        $data['all_volumes'] = \Cache::remember('all_volumes_now', 3600, function (){
            return Volume::all(['id','volumes']);
        });
        foreach ($data['all_version'] as $key=>$value){
            $version_array[$key]['id'] = $value->id;
            $version_array[$key]['text'] = $value->name;
        }
        foreach ($data['all_volumes'] as $key=>$value){
            $volume_array[$key]['id'] = $value->id;
            $volume_array[$key]['text'] = $value->volumes;
        }
        foreach (config('workbook.grade') as $key=> $value){
            if($key>0){
                $grade_array[$key-1]['id'] = $key;
                $grade_array[$key-1]['text'] = $value;
            }
        }
        foreach (config('workbook.subject_1010') as $key=> $value){
            if($key>0){
                $subject_array[$key-1]['id'] = $key;
                $subject_array[$key-1]['text'] = $value;
            }
        }
        $data['version_select'] = json_encode($version_array);
        $data['subject_select'] = json_encode($subject_array);
        $data['grade_select'] = json_encode($grade_array);
        $data['volume_select'] = json_encode($volume_array);
        return view('manage_new.temp_isbn.by_sort',['data'=>$data]);

    }

    public function api(Request $request,$type)
    {
        switch ($type){
            case 'get_nav':
                $isbn = $request->now_isbn;
                $type = $request->now_type;
                $buy_status = $request->buy_status;
                $now_html = '';
                if($type=='1010Temp'){
                    if($buy_status==='buy'){
                        $now_info = IsbnTemp::where('isbn',$isbn)->select('grade_id','subject_id','volumes_id','version_id','sort')->first();
                        $books = AWorkbook1010::where(['sort'=>$now_info->sort,'grade_id'=>$now_info->grade_id,'subject_id'=>$now_info->subject_id,'volumes_id'=>$now_info->volumes_id,'version_id'=>$now_info->version_id])->select('id','bookname','cover','isbn')->take(6)->get();
                        if(count($books)>0){
                            foreach($books as $book){
                                $now_html.='<div class="col-md-2"> <a>'.$book->bookname.'</a> <a class="thumbnail"> <img class="answer_pic" src="'.$book->cover.'" alt=""></a> <div class="input-group"> <input class="form-control" value="'.$book->isbn.'" /> <a data-id="'.$book->id.'" class="add_isbn input-group-addon">追加保存</a> </div> </div>';
                            }
                        }
//                    }else{
//                        //has_user_book:id,sort_name,sort_id,grade_id,subject_id,volumes_id,version_id,cover_img')
//                        $books = WorkbookTemp::where('isbn',$isbn)->select('id','bookname','cover','isbn','grade_id','subject_id','volumes_id','version_id','sort')->with('has_sort:id,name')->take(6)->get();
//                        if(count($books)>0){
//                            foreach($books as $book){
//                                $now_version = cache('all_version_now')->where('id',[$book->version_id])->first();
//                                $now_version = $now_version?$now_version->name:'未选择';
//                                $grade_now = isset(config('workbook.grade')[$book->grade_id])?config('workbook.grade')[$book->grade_id]:"未选择";
//                                $subject_now = isset(config('workbook.subject_1010')[$book->subject_id])?config('workbook.subject_1010')[$book->subject_id]:"未选择";
//                                $volumes_now = isset(config('workbook.volumes')[$book->volumes_id])?config('workbook.volumes')[$book->volumes_id]:"未选择";
//                                $sort_name_now = $book->has_sort?$book->has_sort->name:$book->sort_id;
//                                $now_html.='<div class="col-md-2">
//                                        <a>'.$book->bookname.'</a>
//                                        <a class="badge bg-red">'.$sort_name_now.'</a>
//                                        <a class="thumbnail">
//                                            <img class="answer_pic" src="'.$book->cover.'" alt=""></a><a class="btn btn-xs btn-primary book_grade">'.$grade_now.'</a><a class="btn btn-xs btn-primary book_subject">'.$subject_now.'</a><a class="btn btn-xs btn-primary book_volumes">'.$volumes_now.'</a><a class="btn btn-xs btn-primary book_version">'.$now_version.'</a></div>';
//                            }
//                        }
                    }

                }else if($type=='userTemp'){
                    $books = PreUserTemp::where('isbn',$isbn)->select('id','sort_name','cover_img','isbn','grade_id','subject_id','volumes_id','version_id','sort_id')->with('has_sort:id,name')->take(6)->get();
                    if(count($books)>0){
                        foreach($books as $book){
                            $now_version = cache('all_version_now')->where('id',[$book->version_id])->first();
                            $now_version = $now_version?$now_version->name:'未选择';
                            $grade_now = isset(config('workbook.grade')[$book->grade_id])?config('workbook.grade')[$book->grade_id]:"未选择";
                            $subject_now = isset(config('workbook.subject_1010')[$book->subject_id])?config('workbook.subject_1010')[$book->subject_id]:"未选择";
                            $volumes_now = isset(config('workbook.volumes')[$book->volumes_id])?config('workbook.volumes')[$book->volumes_id]:"未选择";
                            $sort_name_now = $book->has_sort?$book->has_sort->name:$book->sort_id;
                            $now_html.='<div class="col-md-2">
                                        <a>'.$book->sort_name.'</a>
                                        <a class="badge bg-red">'.$sort_name_now.'</a>
                                        <a class="thumbnail">
                                            <img class="answer_pic" src="'.config('workbook.user_image_url').$book->cover_img.'" alt=""></a><a class="btn btn-xs btn-primary book_grade">'.$grade_now.'</a><a class="btn btn-xs btn-primary book_subject">'.$subject_now.'</a><a class="btn btn-xs btn-primary book_volumes">'.$volumes_now.'</a><a class="btn btn-xs btn-primary book_version">'.$now_version.'</a></div>';
                        }
                    }
                }
                else if($type=='hdTemp'){
                    //has_hd_book:id,bookName,coverImage,isbn,gradeId,subjectId,volumes,bookVersionId,sortId

                    $books = HdbookTemp::where('isbn',$isbn)->select('id','bookName','coverImage','isbn','gradeId','subjectId','volumes','bookVersionId','sortId')->with('has_sort:id,name')->take(6)->get();
                    if(count($books)>0){
                        foreach($books as $book){
                            $now_version = cache('all_version_now')->where('id',[$book->bookVersionId])->first();
                            $now_version = $now_version?$now_version->name:'未选择';
                            $grade_now = isset(config('workbook.grade')[$book->gradeId])?config('workbook.grade')[$book->gradeId]:"未选择";
                            $subject_now = isset(config('workbook.subject')[$book->subjectId])?config('workbook.subject')[$book->subjectId]:"未选择";
                            $volumes_now = isset(config('workbook.volumes')[$book->volumes])?config('workbook.volumes')[$book->volumes]:"未选择";

                            $sort_name_now = $book->has_sort?$book->has_sort->name:$book->sortId;

                            $now_html.='<div class="col-md-2"><a>'.$book->bookName.'</a><a class="badge bg-red">'.$sort_name_now.'</a><a class="thumbnail"><img class="answer_pic" src="http://image.hdzuoye.com/'.$book->coverImage.'" alt=""></a><a class="btn btn-xs btn-primary book_grade">'.$grade_now.'</a><a class="btn btn-xs btn-primary book_subject">'.$subject_now.'</a><a class="btn btn-xs btn-primary book_volumes">'.$volumes_now.'</a><a class="btn btn-xs btn-primary book_version">'.$now_version.'</a></div>';
                        }

                    }
                }else if($type=='taobaoTemp'){
                    $books = TaobaoTemp::where('isbn',$isbn)->select('id','title','pic_url','detail_url')->take(6)->get();
                    //->with('has_taobao_book:id,title,pic_url')->
                    if(count($books)>0){
                        foreach($books as $book){
                            $now_html.='<div class="col-md-2">
                                        <a href="https://detail.tmall.com/item.htm?id='.$book->detail_url.'" target="_blank">'.$book->title.'</a>
                                        <a class="thumbnail">
                                            <img class="answer_pic" src="'.$book->pic_url.'" alt="">
                                        </a>
                                    </div>';
                        }
                    }
                }else{$books = [];}

                return response()->json(['status'=>1,'books_html'=>$now_html]);
                break;

            case 'search_old_sort':
                if(count($request->sort)!=1){
                    return response()->json(['status'=>0]);
                }
                $sort = $request->sort[0];
                $search_sort = cache('all_sort_now')->where('id',$sort)->first()->name;
                $search_isbn = '9787'.$request->press;
                //当前系列随机图片
                $now_sort = AWorkbook1010::where([['sort',$sort],['isbn','like','%'.$search_isbn.'%']])->select('id','bookname','cover','sort','isbn')->with('has_sort:id,name')->take(6)->get();

                $now_ids = $now_sort->pluck('id');
                //名字相似随机图片
                $now_like = AWorkbook1010::where([['isbn','like','%'.$search_isbn.'%'],['bookname','like','%'.$search_sort.'%']])->whereNotIn('id',$now_ids)->with('has_sort:id,name')->select('bookname','cover','sort','isbn')->take(6)->get();

                $now_books = $now_sort->merge($now_like);


                return response()->json(['status'=>1,'books'=>$now_books]);
                break;

            case 'save_sort':
                $id = $request->now_id;
                if(count($request->now_sort)!=1){
                    return response()->json(['status'=>0]);
                }
                $sort = $request->now_sort[0];
                if(IsbnTemp::where('id',$id)->update(['sort'=>$sort])){
                    TaskUid::create(['type'=>'sort_arrange','data'=>$id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
                    return response()->json(['status'=>1]);
                }else{
                    return response()->json(['status'=>0]);
                }
                break;

            case 'save_info':
                $now_type = $request->now_type;
                $now_value = $request->now_value;
                $id = $request->now_id;
                $update = [];
                if(count($now_value)==0){
                    return response()->json(['status'=>0]);
                }
                if($now_type=='grade'){
                    if(count($now_value)>1){
                        $update['grade_id'] = implode(',', $now_value);
                    }else{
                        $update['grade_id'] = $now_value[0];
                    }
                }elseif ($now_type=='subject'){
                    if(count($now_value)>1){
                        $update['subject_id'] = implode(',', $now_value);
                    }else{
                        $update['subject_id'] = $now_value[0];
                    }
                }elseif ($now_type=='volumes'){
                    if(count($now_value)>1){
                        $update['volumes_id'] = implode(',', $now_value);
                    }else{
                        $update['volumes_id'] = $now_value[0];
                    }
                }else if($now_type=='version'){
                    if(count($now_value)>1){
                        $update['version_id'] = implode(',', $now_value);
                    }else{
                        $update['version_id'] = $now_value[0];
                    }
                }
                if(!$update){
                    return response()->json(['status'=>0]);
                }

                if(IsbnTemp::where('id',$id)->update($update)){
                    TaskUid::create(['type'=>'sort_arrange_'.$now_type,'data'=>$id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
                    return response()->json(['status'=>1]);
                }else{
                    return response()->json(['status'=>0]);
                }

                break;
            case 'add_isbn':
                $book_id = $request->now_id;
                $isbn = $request->add_isbn;
                $now_book = AWorkbook1010::find($book_id);
                if(strpos($now_book->isbn, $isbn)===false){
                    $now_book->isbn = $now_book->isbn.'|'.$isbn;
                    if($now_book->save()){
                        TaskUid::create(['type'=>'add_isbn','uid'=>Auth::id(),'data'=>$book_id,'updated_at'=>date('Y-m-d H:i:s',time())]);
                        return response()->json(['status'=>1,'msg'=>'追加isbn成功']);
                    }
                }else{
                    return response()->json(['status'=>0,'msg'=>'追加isbn失败']);
                }
                break;

            case 'add_new_sort':
                $now_id = $request->now_id;
                $sort_name = $request->sort_name;
                if(\cache('all_sort_now')->where('name',$sort_name)->count()==0){
                    TaskUid::create(['type'=>'add_new_sort','uid'=>Auth::id(),'data'=>$now_id,'updated_at'=>date('Y-m-d H:i:s',time())]);
                    if($now = Sort::create(['name'=>$sort_name])){
                        Cache::forget('all_sort_now');
                        if(IsbnTemp::where(['id'=>$now_id])->update(['sort'=>$now->id])){
                            return response()->json(['status'=>1,'msg'=>'新增系列并保存成功']);
                        }
                    }

                }
                return response()->json(['status'=>0,'msg'=>'新增系列失败']);

            case 'combine_sort':
                $sorts = $request->sorts;
                if(count($sorts)<1){
                    return response()->json(['status'=>0,'msg'=>'合并失败']);
                }
                foreach ($sorts as $sort){
                    $old_combine_sort = Sort::find($sort)->combine_sort;
                    foreach (collect($sorts)->diff([$sort]) as $other_sort){
                        if(!in_array($other_sort, explode('|', $old_combine_sort))){
                            if($old_combine_sort){
                                $old_combine_sort .= '|'.$other_sort;
                            }else{
                                $old_combine_sort = $other_sort;
                            }
                        }
                    }
                    Sort::where('id',$sort)->update(['combine_sort'=>$old_combine_sort]);
                }
                return response()->json(['status'=>1,'msg'=>'合并成功']);
                break;
        }
    }
}
