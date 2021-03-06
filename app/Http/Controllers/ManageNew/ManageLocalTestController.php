<?php

namespace App\Http\Controllers\ManageNew;

use App\AWorkbookNew;
use App\LocalModel\AWorkbook1010Bd;
use App\LocalModel\AWorkbook1010Test;
use App\LocalModel\AWorkbookAnswerBd;
use App\LocalModel\AWorkbookAnswerNew;
use App\LocalModel\AWorkbookAnswerTest;
use App\LocalModel\LocalCip;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\TaskUid;
use App\OneModel\AOnlyBook;
use App\OnlineModel\ASubSort;
use App\OnlineModel\AWorkbook1010;
use App\Sort;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageLocalTestController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }



    protected function update_onlybook_isbn($onlyid,$isbn)
    {
        $isbn = str_replace('-', '', $isbn);
        $isbn_arr = explode('|', $isbn);
        if(is_array($isbn_arr) && count($isbn_arr)>1){
            foreach ($isbn_arr as $isbn_single){
                $this->update_onlybook_isbn($onlyid,$isbn_single);
            }
        }else{
            $now_isbn = \App\OnlineModel\AOnlyBook::where(['onlyid'=>$onlyid])->first(['isbn']);
            if($now_isbn && strpos($now_isbn->isbn, $isbn)===false){
                \App\OnlineModel\AOnlyBook::where(['onlyid'=>$onlyid])->update(['isbn'=>$now_isbn->isbn.'|'.$isbn]);
            }
        }
    }

    public function index()
    {

        $data['all_sort'] = AWorkbook1010Test::whereNull('confirmed_at')->select('sort',DB::raw('sum(case when confirmed_at is null then 1 else 0 end) not_confirm_num'),DB::raw('count(sort) as all_num'))->with('has_sort:id,name')->groupBy('sort')->orderBy('not_confirm_num','desc')->paginate(10);

        return view('manage_new.local.index_test',['data'=>$data]);

    }

    public function sort_list($sort='',$type='pending',$now_id=0)
    {
        if($sort==''){
            abort(404);
        }

        $data['type'] = $type;
        $data['sort'] = $sort;
        if($now_sort_name = cache('all_sort_now')->where('id',$sort)->first()){
            $data['sort_name'] = $now_sort_name->name;
        }else{
            $data['sort_name'] = '待定';
        }

        if($type==='local_dir'){
            if($now_id===0){
                $all_dir = \File::directories('//QINGXIA23/book4_new/'.$sort.'_'.$data['sort_name']);
                $book_ids = [];
                $answers = [];
                $covers = [];
                foreach ($all_dir as $dir){
//                $answers[] = \File::files($dir);
//                $covers[] = \File::allFiles($dir.'/cover');
                    $book_ids[] = last(explode('_',\File::basename($dir)));
                }
            }else{
                $book_ids = [$now_id];
            }

            $book_ids_string = implode(',', $book_ids);
            $data['now_books'] = AWorkbookNew::whereIn('id',$book_ids)->where('from_only_id','>',0)->select('id','bookname','isbn','grade_id','subject_id','volumes_id','version_id','version_year','sort')->orderBy('grade_id','asc')->orderBy('subject_id','asc')->get();
//            foreach ($answers as $key1=> $item){
//                foreach ($item as $key2 => $value){
//                    if(!in_array(\File::extension($value),['jpg','png','gif'])){
//                        unset($answers[$key1][$key2]);
//                    }
//                }
//            }


            foreach ($data['now_books'] as $key => $book){

//                if($covers[$key]){
//                    foreach ($covers[$key] as $key1=> $item){
//                        if(!in_array(\File::extension($item),['jpg','png','gif'])){
//                            unset($covers[$key][$key1]);
//                        }
//                    }
//                    if(isset($covers[$key][0])){
//                        $data['now_books'][$key]['cover_photo'] = 'http://192.168.0.117/book4/'.substr($covers[$key][0]->getPathName(),22);
//                        $data['now_books'][$key]['cover'] = 'http://192.168.0.117/book4/'.substr($covers[$key][0]->getPathName(),22);
//                    }else{
//                        $data['now_books'][$key]['cover_photo'] = 'not_upload';
//                    }
//                    if(isset($covers[$key][1])){
//                        $data['now_books'][$key]['cip_photo'] = 'http://192.168.0.117/book4/'.substr($covers[$key][1]->getPathName(),22);
//                    }else{
//                        $data['now_books'][$key]['cip_photo'] = 'not_upload';
//                    }
//                }else{
//                    $data['now_books'][$key]['cover_photo'] = 'not_upload';
//                    $data['now_books'][$key]['cip_photo'] = 'not_upload';
//                }

                $data['now_books'][$key]['answer_dir'] = '\\\\QINGXIA23\\book4_new\\'.$sort.'_'.$data['sort_name'].'/'.$book->bookname.'_'.$book->id;
                $data['now_books'][$key]['cip_dir'] = $data['now_books'][$key]['answer_dir'].'/cover';





                $answers = \File::files($data['now_books'][$key]['answer_dir']);

                usort($answers, function($a,$b){
                    if(\File::name($a)==\File::name($b)){
                        return 0;
                    }
                    return (\File::name($a) < \File::name($b))?-1:1;
                });

                $covers =\File::files($data['now_books'][$key]['cip_dir']);

                usort($covers, function($a,$b){
                    if(\File::name($a)==\File::name($b)){
                        return 0;
                    }
                    return (\File::name($a) < \File::name($b))?-1:1;
                });


//                $answers = array_sort(\File::files($data['now_books'][$key]['answer_dir']),function ($file){
//                    return intval(\File::basename($file));
//                });

//                $covers = array_sort(\File::files($data['now_books'][$key]['cip_dir']),function ($file){
//                    return intval(\File::basename($file));
//                });
                foreach ($answers as $a_key => $item){
                    if(!in_array(\File::extension($item),['jpg','png','gif'])){
                            unset($answers[$a_key]);
                    }
                }
                foreach ($covers as $a_key => $item){
                    if(!in_array(\File::extension($item),['jpg','png','gif'])){
                        unset($covers[$a_key]);
                    }
                }

                $data['now_books'][$key]['has_answers'] = $answers;

                $data['now_books'][$key]['cover_photo'] = isset($covers[0])?'http://192.168.0.117/book4_new/'.substr($covers[0],22):'';
                $data['now_books'][$key]['cover'] = isset($covers[0])?'http://192.168.0.117/book4_new/'.substr($covers[0],22):'';
                $data['now_books'][$key]['cip_photo'] = isset($covers[1])?'http://192.168.0.117/book4_new/'.substr($covers[1],22):'';

            }
            //移除已整理  不移除无答案
            foreach ($data['now_books'] as $key=>$book){
                if(AWorkbook1010Test::where('from_id',$book->id)->count()>0){
                    unset($data['now_books'][$key]);
                }
            }
        }
        elseif($type==='pending'){

            if($now_id>0){
                $whereRaw = 'from_id='.$now_id;
            }else{
                $whereRaw = '1=1';
            }
            $data['now_books'] = AWorkbook1010Test::where(['sort'=>$sort])->whereRaw($whereRaw)->where(function ($query) use ($type){
                if($type==='pending'){
                    return $query->whereNull('confirmed_at');
                }else{
                    return $query->whereNotNull('confirmed_at');
                }
            })->select('id','bookname','isbn','cover','grade_id','subject_id','volumes_id','version_id','version_year','sort','cip_photo')->with('has_answers')->orderBy('grade_id','asc')->orderBy('subject_id','asc')->get();

            foreach ($data['now_books'] as $key=>$book){
                $data['now_books'][$key]['cover'] = str_replace(config('workbook.thumb_image_url'), 'http://192.168.0.117/', $book->cover);
                $data['now_books'][$key]['cip_photo'] = str_replace(config('workbook.thumb_image_url'), 'http://192.168.0.117/', $book->cip_photo);
            }
        }elseif($type==='done'){
            $data['now_books'] = AWorkbook1010Test::where(['sort'=>$sort])->where(function ($query) use ($type){
                if($type==='pending'){
                    return $query->whereNull('confirmed_at');
                }else{
                    return $query->whereNotNull('confirmed_at');
                }
            })->select('id','bookname','isbn','cover','grade_id','subject_id','volumes_id','version_id','version_year','sort','cip_photo')->with('has_answers')->orderBy('grade_id','asc')->orderBy('subject_id','asc')->get();
            foreach ($data['now_books'] as $key=>$book){
                $data['now_books'][$key]['cover'] = str_replace(config('workbook.thumb_image_url'), 'http://192.168.0.117/', $book->cover);
                $data['now_books'][$key]['cip_photo'] = str_replace(config('workbook.thumb_image_url'), 'http://192.168.0.117/', $book->cip_photo);
            }
        }

        $data['all_books_info'] = $data['now_books']->groupBy('grade_id')->transform(function ($item,$key){
            return $item->groupBy('subject_id');
        });
        return view('manage_new.local.sort_list_test',['data'=>$data]);
    }

    public function api($type)
    {
        switch ($type){
            case 'get_sort':
                $sort_name = $this->request->sort_name;
                $all_sort_now = AWorkbook1010Bd::where('sort_name',$sort_name)->select()->get();

                $data['all_sort'] = $all_sort_now;
                $data['all_sort_group'] = $all_sort_now->groupBy('grade_id')->transform(function ($item,$key){
                   return $item->groupBy('subject_id')->transform(function ($item1,$key1){
                       return $item1->groupBy('version_name');
                   })->sortBy(function ($value2,$key2){
                       return $key2;
                   });
                })->sortBy(function ($value3,$key3){
                    return $key3;
                });

                $all_string = '<div class="choose_box">';
                foreach ($data['all_sort_group'] as $grade => $grade_value){
                    foreach ($grade_value as $subject=>$subject_value){
                        foreach ($subject_value as $version=>$version_value){
                            $all_string .= '<a class="btn btn-default choose_book" data-grade="'.$grade.'" data-subject="'.$subject.'" data-grade_name="'.$version_value[0]->grade_name.'" data-subject_name="'.$version_value[0]->subject_name.'" data-version="'.$version.'" data-version_id="'.$version_value[0]->version_id.'">'.config('workbook.grade')[$grade].config('workbook.subject_1010')[$subject].$version.'</a>';
                        }
                    }
                }
                $all_string .='</div>';


                return response()->json(['status'=>1,'sort_info'=>$all_string]);
                break;

            case 'get_answer':
                $now_sort_name = $this->request->answer_sort;
                $now_version_name = $this->request->answer_version;
                $now_grade_name = $this->request->answer_grade_name;
                $now_subject_name = $this->request->answer_subject_name;
                $all_answer = AWorkbook1010Test::where(['sort_name'=>$now_sort_name,'version_name'=>$now_version_name,'grade_name'=>$now_grade_name,'subject_name'=>$now_subject_name,])->select()->first();
                $all_string = '';
                foreach ($all_answer->has_answers as $answer){
                    $all_string .= '<a class="thumbnail"><img data-id="'.$answer->id.'" class="answer_pic real_pic" src="http://192.168.0.117/'.$answer->answer.'" alt=""><i class="badge bg-blue delete_this">移除</i><i class="badge bg-red exchange" data-type="left">与左图交换</i><i class="badge bg-red exchange" data-type="right">与右图交换</i></a>';
                }
                return response()->json(['status'=>1,'answer_info'=>$all_string]);
                break;

            case 'confirm_done':
//now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all
                //answer_grade_name,answer_subject_name,answer_sort,answer_version,cover_photo,cip_photo
                //$now_id = $this->request->now_id;
                $book_id = $this->request->now_id;
                $book['bookname'] = $this->request->bookname;
                $book['version_year'] = $this->request->version_year;
                $book['sort'] = $this->request->sort_id;
                $book['grade_id'] = $this->request->grade_id;
                $book['subject_id'] = $this->request->subject_id;
                $book['volumes_id'] = $this->request->volume_id;
                $book['version_id'] = $this->request->version_id;
                $book['cover_photo'] = str_replace('\\', '/', $this->request->cover_photo);
                $book['cip_photo'] = str_replace('\\', '/', $this->request->cip_photo);
                $book['isbn'] = str_replace(['-','|'], '', $this->request->isbn);
//                if(\Auth::id()<=5){
//                    if($now_id%6===0){
//                        $book['update_uid'] = 8;
//                    }elseif($now_id%6===1){
//                        $book['update_uid'] = 11;
//                    }elseif($now_id%6===2){
//                        $book['update_uid'] = 17;
//                    }elseif($now_id%6===3){
//                        $book['update_uid'] = 18;
//                    }elseif($now_id%6===4){
//                        $book['update_uid'] = 19;
//                    }elseif($now_id%6===5){
//                        $book['update_uid'] = 20;
//                    }
//                }else{
                    $book['update_uid'] = \Auth::id();
                //}
                $book['updated_at'] = date('Y-m-d H:i:s',time());
                $answer['answer_all'] = $this->request->answer_all;
//                if(!check_isbn($book['isbn'])){
//                    return response()->json(['status'=>0,'msg'=>'isbn验证不通过']);
//                }
                if($book['sort']<0){
                    return response()->json(['status'=>0,'msg'=>'请填写系列']);
                }
                if(empty($answer['answer_all'])){
                    return response()->json(['status'=>0,'msg'=>'答案为空']);
                }
                foreach ($answer['answer_all'] as $value){
                    if(!$value){
                        return response()->json(['status'=>0,'msg'=>'答案有空缺']);
                    }
                }


                $book['bookcode'] = md5($book['bookname'].$book['version_year'].$book['sort'].$book['grade_id'].$book['subject_id'].$book['volumes_id'].$book['version_id'].$book['isbn'].'from_local');

                $book['onlyid'] = 0;

                $now_new_name = str_replace($book['version_year'].'年', '', $book['bookname']);
                $online_onlyid = AWorkbook1010::where([['newname',$now_new_name],['onlyid','!=',0]])->whereRaw('LENGTH(onlyid)=13')->first(['onlyid']);
                if($online_onlyid){
                    $book['onlyid'] = $online_onlyid->onlyid;
                }else{
                    $now_ssort_id = 0;
                    $now_ssort = ASubSort::where([['sort_id',$book['sort']],['ssort_name','!=','']])->select('ssort_id','ssort_name')->get();
                    foreach ($now_ssort as $ssort){
                        if(strpos($now_new_name, $ssort->ssort_name)!==false){
                            $now_ssort_id = $ssort->ssort_id;
                        }
                    }
                    $book['onlyid'] = str_pad($book['sort'],5,"0",STR_PAD_LEFT).str_pad($book['grade_id'],2,"0",STR_PAD_LEFT).str_pad($book['subject_id'],2,"0",STR_PAD_LEFT).str_pad($book['version_id'],2,"0",STR_PAD_LEFT).str_pad($now_ssort_id,2,"0",STR_PAD_LEFT);

                }

            //

            //$book['onlyid'] = str_pad($book['sort'],5,"0",STR_PAD_LEFT).str_pad($book['grade_id'],2,"0",STR_PAD_LEFT).str_pad($book['subject_id'],2,"0",STR_PAD_LEFT).str_pad($book['version_id'],2,"0",STR_PAD_LEFT)."00";



                if($this->request->now_type==='local_dir'){
                    ignore_user_abort();
                    set_time_limit(0);
                    ini_set('memory_limit', -1);
                    //本地记录转表拿到新增id
                    //cover,cip,answers移动至pic19
                    //\File::copy($path, $target);
                    //更新封面cip
                    //插入答案
                    DB::transaction(function () use($book_id,$book,$answer) {
                        $now_cover = '//QINGXIA23/'.substr($book['cover_photo'], 21);
                        $now_cip = '//QINGXIA23/'.substr($book['cip_photo'],21);

                        $book['from_id'] = $book_id;
                        unset($book['cover_photo']);
                        unset($book['cip_photo']);
                        #$this->update_onlybook_isbn($book['onlyid'],)
                        $now = AWorkbook1010Test::create($book);
                        if(!is_dir(dirname('//QINGXIA23/www/pic19/'.$now->id.'/cover/'.md5_file($now_cip).'.'.\File::extension($now_cip)))){
                            mkdir(dirname('//QINGXIA23/www/pic19/'.$now->id.'/cover/'.md5_file($now_cip).'.'.\File::extension($now_cip)), 0777, true);
                        }
                        \File::copy($now_cover, '//QINGXIA23/www/pic19/'.$now->id.'/cover/'.md5_file($now_cover).'.'.\File::extension($now_cover));
                        \File::copy($now_cip, '//QINGXIA23/www/pic19/'.$now->id.'/cover/'.md5_file($now_cip).'.'.\File::extension($now_cip));
                        foreach($answer['answer_all'] as $key => $answer){
                            $now_answer = '//QINGXIA23/'.substr($answer, 21);
                            \File::copy($now_answer, '//QINGXIA23/www/pic19/'.$now->id.'/'.md5_file($now_answer).'.'.\File::extension($now_answer));
                            $new_answer['bookid'] = $now->id;
                            $new_answer['book'] = $book['bookcode'];
                            $new_answer['text'] = intval($key+1);
                            $new_answer['textname'] = '第'.intval($key+1).'页';
                            $new_answer['answer'] = 'pic19/'.$now->id.'/'.md5_file($now_answer).'.'.\File::extension($now_answer);
                            $new_answer['md5answer'] = md5_file($now_answer);
                            AWorkbookAnswerTest::create($new_answer);
                        }
                        $new['cover'] = config('workbook.thumb_image_url').'pic19/'.$now->id.'/cover/'.md5_file($now_cover).'.'.\File::extension($now_cover);
                        $new['cip_photo'] = config('workbook.thumb_image_url').'pic19/'.$now->id.'/cover/'.md5_file($now_cip).'.'.\File::extension($now_cip);
                        AWorkbook1010Test::where(['id'=>$now->id])->update($new);
                        TaskUid::create(['type'=>'local_answer','data'=>$now->id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
                        //更改workbook_new 状态   a_book_bought  为4
                        $New_book = AWorkbookNew::find($book_id,['from_only_id']);
                        AWorkbookNew::where('id',$book_id)->update(['now_status'=>4]);
                        NewBoughtRecord::where(['only_id'=>$New_book->from_only_id,'status'=>6])->update(['status'=>4]);
                    });

                }else{
                    DB::transaction(function() use ($book_id,$book,$answer){
                        $now_press = get_press($book['isbn']);
                        $sort_now = Sort::find($book['sort']);
                        if($sort_now){
                            if(strlen($sort_now->version)==0){
                                $sort_now->version = $now_press;
                            }else{
                                if(strpos($sort_now->version, '|'.$now_press)<=0){
                                    $sort_now->version = $sort_now->version.'|'.$now_press;
                                }
                            }
                            $sort_now->save();
                        }
                        AWorkbook1010Test::where(['id'=>$book_id])->update($book);
                        foreach ($answer['answer_all'] as $key=>$value){
                            $now['text'] = $key+1;
                            $now['textname'] = '第'.$now['text'].'页';
                            $now['bookid'] = $book_id;
                            $now['book'] = $book['bookcode'];
                            AWorkbookAnswerTest::where(['id'=>$value])->update($now);
                        }
                        TaskUid::create(['type'=>'local_answer_again','data'=>$book_id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
                    });
                }


                break;

            case 'move_to_local':
                $book_id = $this->request->book_id;
                if(AWorkbook1010Test::where(['id'=>$book_id])->delete()){
                    if(AWorkbookAnswerTest::where('bookid',$book_id)->delete()){
                        return response()->json(['status'=>1]);
                    }
                }else{
                    return response()->json(['status'=>0]);
                }

        }
        return response()->json(['status'=>1]);
    }
}
