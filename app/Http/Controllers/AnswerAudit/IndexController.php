<?php

namespace App\Http\Controllers\AnswerAudit;

use App\AWorkbook1010;
use App\AWorkbookFeedback;
use App\AWorkbookRds;
use App\BookVersionType;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\TaskUid;
use App\OnlineModel\AWorkbookAnswer;
use App\PreHomeMyfavorite;
use App\PreMHomeworkMessage;
use App\PreMWorkbookAnswerUser;
use App\PreMWorkbookUser;
use App\PreMWorkbookUserGroup;
use App\Volume;
use App\WorkbookAnswer;
use App\WorkbookAnswerRds;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use ZipArchive;

class IndexController extends Controller
{

    //练习册操作
    protected function to_book_operate($data,$old_ids)
    {
        DB::beginTransaction(); try {
        //1.更新to_book_id 和 status
        PreMWorkbookUser::where([['isbn', $data['isbn']]])->whereIn('id', $old_ids)->update(['to_book_id' => $data['to_book_id'], 'status' => 7, 'update_uid' => \Auth::user()->related_uid]);
        //2.答案to_book_id变更
        $all_old_ids_sql = PreMWorkbookUser::where(['to_book_id' => $data['to_book_id']])->select('id')->get();
        //      $all_old_ids = collect($all_old_ids_sql)->pluck('id');
        PreMWorkbookAnswerUser::whereIn('id', $old_ids)->update(['to_book_id' => $data['to_book_id']]);
        //3.收藏变更
        $bookcode = AWorkbookRds::where(['id' => $data['to_book_id']])->first()->bookcode;
        PreHomeMyfavorite::whereIn('bookid', $old_ids)->update(['id' => $bookcode, 'bookid' => $data['to_book_id']]);
    }catch (Exception $e) {
        DB::rollBack();
    }
    }

    //isbn列表
    public function by_isbn(Request $request)
    {
//        $data_0 = DB::connection('mysql_local')->select('select isbn,count(isbn) as num from pre_m_workbook_user where `status` = 0 and to_book_id=0 AND LENGTH(isbn)=13 group by isbn');
//        $data_1 = DB::connection('mysql_local')->select('select isbn,count(isbn) as num from pre_m_workbook_user where `status` = 1 and to_book_id=0 AND LENGTH(isbn)=13 group by isbn');
//        $data_9 = DB::connection('mysql_local')->select('select isbn,count(isbn) as num from pre_m_workbook_user where `status` = 9 and to_book_id=0 AND LENGTH(isbn)=13 group by isbn');
//
//        PreMWorkbookUserGroup::where([['id','>',0]])->delete();
//        foreach ($data_0 as $value){
//            if(PreMWorkbookUserGroup::where(['isbn'=>$value->isbn])->count()>0){
//                PreMWorkbookUserGroup::where(['isbn'=>$value->isbn])->increment('num',$value->num);
//            }else{
//                PreMWorkbookUserGroup::create(['isbn'=>$value->isbn,'num'=>$value->num]);
//            }
//        }
//        foreach ($data_1 as $value){
//            if(PreMWorkbookUserGroup::where(['isbn'=>$value->isbn])->count()>0){
//                PreMWorkbookUserGroup::where(['isbn'=>$value->isbn])->increment('num',$value->num);
//            }else{
//                PreMWorkbookUserGroup::create(['isbn'=>$value->isbn,'num'=>$value->num]);
//            }
//        }
//        foreach ($data_9 as $value){
//            if(PreMWorkbookUserGroup::where(['isbn'=>$value->isbn])->count()>0){
//                PreMWorkbookUserGroup::where(['isbn'=>$value->isbn])->increment('num',$value->num);
//            }else{
//                PreMWorkbookUserGroup::create(['isbn'=>$value->isbn,'num'=>$value->num]);
//            }
//        }
//        dd(';qwe');




        //,DB::raw('any_value(sort_name)'),DB::raw('any_value(subject_id)'),DB::raw('any_value(grade_id)'),DB::raw('any_value(volumes_id)'),
//        DB::raw('any_value(version_id)'),DB::raw('any_value(cover_img)'),DB::raw('any_value(cip_img)'),DB::raw('any_value(isbn)'),DB::raw('any_value(version)'),DB::raw('any_value(banci)'),DB::raw('any_value(yinci)')
//        $page = intval($request->page);
//        $now_max_time = PreMWorkbookUser::whereIn('status',[0,1,9])->max('addtime');
//        $time = date('Y-m-d',strtotime($now_max_time)-60*60*24);
//        $now_isbn = PreMWorkbookUser::where([['addtime','>',$time]])->whereIn('status',[0,1,9])->select(DB::raw('distinct(isbn)'))->get();
//        $data['all_isbn'] = Cache::remember('all_isbn_'.$page, 30, function () use ($now_isbn){
//            return PreMWorkbookUser::whereIn('status',[0,1,9])->whereIn('isbn',$now_isbn)->select('isbn',DB::raw('count(isbn) as num'))->groupBy('isbn')->orderBy('num','desc')->paginate(30);
//        });


        $data['all_isbn'] = PreMWorkbookUserGroup::where('status',0)->select('isbn','num')->with('hasWorkBookUserFirst:isbn,cover_img')->with('hasSearchTemp:isbn,searchnum')->with('hasIsbnDetail:isbn,print_description,preg_sort_id,preg_grade_id,preg_subject_id,preg_volumes_id,preg_version_id,real_bookname')->withCount('hasOfficalBook')->orderBy('num','desc')->paginate(10);

        return view('answer_audit.index',compact('data'));
    }

    //isbn详情
    public function isbn_detail($isbn)
    {
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

        $data['isbn'] = $isbn;
        $data['offical_book'] = AWorkbook1010::where(['isbn'=>$isbn])->select('id','bookname','cover_photo_thumbnail','isbn','grade_id','subject_id',
            'volumes_id','version_id','version_year','sort')->with('has_sort:id,name')->get();
        $data['isbn_detail'] = PreMWorkbookUser::where([['isbn',$isbn],['status','in','0,1,9'],['addtime','>=','2018-01-01']])->select('addtime','id','sort_name','grade_id','subject_id','grade_id','volumes_id','version_id','version_year','sort_id as sort','cover_img','cip_img','hdid')->orderBy('addtime','desc')->get();
        //dd($data);
        return view('answer_audit.isbn_detail',compact('data'));
    }

    //isbn所有封面
    public function isbn_cover($isbn='')
    {

        if($isbn===''){
            return return_json_err();
        }
        $all_cover = PreMWorkbookUser::where('isbn',$isbn)->select(DB::Raw('distinct cover_img as cover_img'))->orderBy('cover_img','desc')->take(50)->get();
        foreach ($all_cover as $cover){
            if(!starts_with($cover->cover_img, 'http://') && !starts_with($cover->cover_img, 'http://user.1010pic.com') ){
                $cover->cover_img = config('workbook.user_image_url').$cover->cover_img;
            }
        }
        return return_json($all_cover);
    }

    //答案列表
    public function by_answer()
    {
        //status为7暂定为已通过求助练习册
        $data['status'] = 7;
        $now_max_time = PreMWorkbookUser::where($data)->max('addtime');
        $time = date('Y-m-d',strtotime($now_max_time)-60*60*24*30);//['addtime','>',$time],
        $data['now_book'] = PreMWorkbookUser::where([['status',7],['to_book_id','>',0]])->select(DB::raw('distinct to_book_id'))->with('has_offical_book:id,bookname,cover')->get();
        return view('answer_audit.by_answer',compact('data'));
    }

    //答案详情
    public function answer_detail($to_book_id,Request $request)
    {
        $data['type']=$request->from?$request->from:'';
        $data['book_id'] = $to_book_id;
        if($to_book_id>10000000){
            $data['book_info'] = PreMWorkbookUser::where(['id'=>$to_book_id])->first();
            $data['book_info']->sort = $data['book_info']->sort_id;
            $data['book_info']->bookname = $data['book_info']->sort_name;
            $data['book_info']->collect_count = 0;
            $data['book_info']->concern_num = 0;
            $data['book_info']->redirect_id = 0;
            if($data['book_info']->status===9){
                if(strpos($data['book_info']->cover_img, 'pic')!==false){
                    $data['book_info']->cover = config('workbook.thumb_image_url').$data['book_info']->cover_img;
                }else{
                    $data['book_info']->cover = 'http://image.hdzuoye.com/'.$data['book_info']->cover_img;
                }
                $data['book_info']->cip_photo = 'http://image.hdzuoye.com/'.$data['book_info']->cip_img;
            }else{
                $data['book_info']->cover = config('workbook.thumb_image_url').$data['book_info']->cover_img;
                $data['book_info']->cip_photo = config('workbook.thumb_image_url').$data['book_info']->cip_img;
            }

        }else{
            $data['book_info'] = AWorkbook1010::where(['id'=>$to_book_id])->first();
        }

        if(isset($data['book_info']->isbn)){
            $data['book_info']->isbn = convert_isbn($data['book_info']->isbn);
        }else{
            if(!isset($data['book_info'])){
                die('本地暂无该练习册');
            }else{
                $data['book_info']->isbn = '';
            }

        }


        $data['offical_answer'] = WorkbookAnswerRds::where(['bookid'=>$to_book_id,'status'=>1])->select(['id','text','textname','answer'])->orderBy('text','asc')->get();
        $data['offical_answer_recycle'] = WorkbookAnswerRds::where(['bookid'=>$to_book_id,'status'=>3])->select(['id','text','textname','answer'])->orderBy('id','asc')->get();

        $data['user_answer'] = PreMWorkbookAnswerUser::where(['to_book_id'=>$to_book_id])->select(['answer_img','up_uid','addtime'])->get();

        foreach ($data['user_answer'] as $key=>$value){
            $data['user_message'][$key] = PreMHomeworkMessage::where(['type'=>'answer_audit','hid'=>$data['book_id'],'huid'=>$value->up_uid])->select(['uid','huid','add_time','msg'])->get();
        }
        $related = [
            'grade_id'=>$data['book_info']->grade_id,
            'subject_id'=>$data['book_info']->subject_id,
            'volumes_id'=>$data['book_info']->volumes_id,
            'version_id'=>$data['book_info']->version_id,
            'sort'=>$data['book_info']->sort,
        ];
        $data['related_books'] = AWorkbook1010::where($related)->select('id','bookname','version_year','grade_id','subject_id','volumes_id','version_id','sort','collect_count','collect_count as concern_num','cover','cip_photo','isbn')->with(['has_answer'=>function($query){
            return $query->select('id','bookid','text','textname','answer','status')->orderBy('text','asc');
        }
        ])->orderBy('version_year','desc')->get();
        foreach ($data['related_books'] as $related_book){
            $related_book->isbn = convert_isbn($related_book->isbn);
        }

        //dd($data);
        return view('answer_audit.answer_detail',compact('data'));
    }

    //api接口
    public function api(Request $request,$type)
    {
        if(in_array($type, ['to_offical_book','save_offical_book']) && count($request->now_book_ids)==0){
            return response()->json(['status'=>0,'msg'=>'请选择求助']);
        }
        if($type==='to_offical_book'){
            $data['to_book_id'] = intval($request->book_id);
            $data['isbn'] = $request->isbn;
            return $this->to_book_operate($data,$request->now_book_ids);
        }
        else if($type==='save_offical_book'){
            //save
            if($request->book_id<10000000){
                $a_workbook = AWorkbook1010::find($request->book_id);
            }else{
                $a_workbook = new AWorkbook1010();
            }

            //bookname  bookcode     isbn   cover  grade_id  subject_id  volumes_id version_id version_year
            $a_workbook->bookname = $request->bookname;
            $a_workbook->isbn = $request->isbn;
            $a_workbook->cover = $request->cover;
            $a_workbook->grade_id = $request->grade_id;
            $a_workbook->subject_id = $request->subject_id;
            $a_workbook->volumes_id = $request->volumes_id;
            $a_workbook->version_id = $request->version_id;
            $a_workbook->version_year = $request->version_year;
            $a_workbook->sort = $request->sort;
            $a_workbook->ssort_id = $request->ssort_id;
            $a_workbook->hdid = $request->hdid;
            $a_workbook->bookcode = md5($request->version_year.$request->bookname.$request->grade_id.$request->subject_id.$request->volumes_id.$request->version_id);
            if($request->book_id>10000000){
                $a_workbook->status = 4;
                $a_workbook->rating = 0;
                $a_workbook->grade_name = '';
                $a_workbook->subject_name = '';
                $a_workbook->volume_name = '';
                $a_workbook->version_name = '';
                $a_workbook->sort_name = '';
            }
            $a_workbook->save();
//            if(AWorkbookRds::create($add)){
//
//            }
            $data['isbn'] = $request->isbn;
            $data['to_book_id'] = $a_workbook->id;
            $this->to_book_operate($data,$request->now_book_ids);
        }
        else if($type === 'get_book_info'){
            $id = $request->now_book_id;
            $type = $request->now_book_type;
            if($type==='old_book'){
                $book_detail = AWorkbook1010::where(['id'=>$id])->first(['id','sort_name','grade_id','subject_id','grade_id','volumes_id','version_id','version_year','sort','cover','cover as cip_img','hdid']);
            }else{
                $book_detail = PreMWorkbookUser::where(['id'=>$id])->first(['id','sort_name','grade_id','subject_id','grade_id','volumes_id','version_id','version_year','sort_id as sort','cover_img','cip_img','hdid']);
            }

            return response()->json(['status'=>1,'msg'=>'操作成功','book_info'=>$book_detail]);
        }
        else if($type === 'download_img') {
            $all_img = $request->get('all_img');
            $book_id = $request->get('book_id');
            if(count($all_img)<1 || $book_id<=0){
                return response()->json(['status'=>0,'msg'=>'请选择下载图片']);
            }
            $public_dir = public_path() . '/uploads/';
            $des_dir = $public_dir.\Auth::id().'/'.$book_id.'/';
            if(!is_dir($des_dir)){
                mkdir($des_dir,777,true);
            }
//            foreach ($all_img as $file) {
//                $file_name = \File::name($file).'.'.\File::extension($file);
//                file_put_contents($des_dir.'/'.$file_name, file_get_contents($file));
//            }
            $zipFileName = date('Y-m-d-H-i-s', time()) . '.zip';
            $zip = new ZipArchive;
            if ($zip->open($des_dir . $zipFileName, ZipArchive::CREATE) === TRUE){
                foreach ($all_img as $file) {
                    $now_index =  strpos($file, '?auth_key=');
                    if($now_index!=0){
                        $file = substr($file, 0,$now_index);
                    }
                    $file_name = \File::name($file).'.'.\File::extension($file);
                    $zip->addFromString($file_name, file_get_contents($file));
                }
                $zip->close();

            }
            return response()->json(['status'=>1,'msg'=>'操作成功','zip'=>'/uploads/'.\Auth::id().'/'.$book_id.'/'.$zipFileName]);
            #return response()->download($des_dir.$zipFileName,$zipFileName);
        }
        else if($type==='move_to_trash'){
            $book_id = intval($request->book_id);
            WorkbookAnswerRds::where('bookid',$book_id)->update(['status'=>3]);
        }
        else if($type==='update_answer'){
            $book_id = $request->book_id;
            $imgs = $request->all_img;
            $update_type = $request->update_type;
            $append_id = $request->append_id;
            if(count($imgs)<1){
                return response()->json(['status'=>0,'msg'=>'操作失败,请先上传图片']);
            }
            if($book_id>10000000){
                $now_book = PreMWorkbookUser::find($book_id);
                $data['book'] = $book_id;
            }else{
                $now_book = AWorkbook1010::find($book_id);
                $data['book'] = $now_book->bookcode;
            }
            $max_id = WorkbookAnswerRds::max('id');
//            if($max_id<=20000000){
//                $max_id = 20000000;
//            }
            if($now_book){
                if($update_type==='update'){
                    WorkbookAnswerRds::where(['bookid'=>$book_id])->update(['status'=>3]);
                    $data['bookid'] = $book_id;
                    $data['hdid'] = $now_book->hdid;
                    foreach ($imgs as $key=>$img){
                        $data['id'] = WorkbookAnswerRds::max('id')+1;
                        $data['text'] = $key+1;
                        $data['textname'] = "参考答案第{$data['text']}页";
                        $data['answer'] = str_replace(config('workbook.thumb_image_url'), '', $img);
                        $data['status'] = 1;
                        $data['addtime'] = date('Y-m-d H:i:s',time());
                        $data['md5answer'] = md5($data['answer']);
                        WorkbookAnswerRds::create($data);
                    }
                }else{
                    $data['bookid'] = $book_id;
                    $data['hdid'] = $now_book->hdid;
                    if($append_id>0){
                        $now_text = WorkbookAnswerRds::where(['id'=>$append_id,'bookid'=>$book_id,'status'=>1])->first();
                        $now_other_answer = WorkbookAnswerRds::where([['bookid',$book_id],['status',1],['text','>',$now_text->text]])->select('id')->orderBy('text')->get();
                        foreach ($imgs as $key=>$img){
                            $data['id'] = WorkbookAnswerRds::max('id')+1;
                            $data['text'] = intval($key)+intval($now_text->text)+1;
                            $data['textname'] = "参考答案第{$data['text']}页";
                            $data['answer'] = str_replace(config('workbook.thumb_image_url'), '', $img);
                            $data['status'] = 1;
                            $data['addtime'] = date('Y-m-d H:i:s',time());
                            $data['md5answer'] = md5($data['answer']);
                            WorkbookAnswerRds::create($data);
                        }
                        foreach ($now_other_answer as $key => $answer){
                            $text = intval($now_text->text)+count($imgs)+intval($key)+1;
                            $text_name = "参考答案第{$text}页";
                            WorkbookAnswerRds::where(['id'=>$answer->id,'bookid'=>$book_id,'status'=>1])->update(['text'=>$text,'textname'=>$text_name]);
                        }
                    }else{
                        $max_text = WorkbookAnswerRds::where(['bookid'=>$book_id,'status'=>1])->count();
                        foreach ($imgs as $key=>$img){
                            $data['text'] = intval($key)+intval($max_text)+1;
                            $data['textname'] = "参考答案第{$data['text']}页";
                            $data['answer'] = str_replace(config('workbook.thumb_image_url'), '', $img);
                            $data['status'] = 1;
                            $data['addtime'] = date('Y-m-d H:i:s',time());
                            $data['md5answer'] = md5($data['answer']);
                            WorkbookAnswerRds::create($data);
                        }
                    }
                }
                AWorkbook1010::where('id',$book_id)->update(['status'=>1]);
            }else{
                return response()->json(['status'=>0,'msg'=>'操作失败']);
            }
        }
        else if($type==='delete_answer'){
            $book_id = $request->book_id;
            $answer_id = $request->answer_id;
            if(!WorkbookAnswerRds::where(['id'=>$answer_id,'bookid'=>$book_id])->update(['status'=>3])){
                return response()->json(['status'=>0,'msg'=>'操作失败']);
            }
            $now_all_answer = WorkbookAnswerRds::where(['bookid'=>$book_id,'status'=>1])->select('id')->orderBy('text')->get();
            foreach ($now_all_answer as $key => $answer){
                $text = intval($key)+1;
                $text_name = "参考答案第{$text}页";
                WorkbookAnswerRds::where(['id'=>$answer->id,'bookid'=>$book_id,'status'=>1])->update(['text'=>$text,'textname'=>$text_name]);
            }
        }
        else if($type==='replace_img'){
            $book_id = $request->book_id;
            $answer_id = $request->answer_id;
            if($answer_id==='cover'){
                if($book_id>10000000){
                    PreMWorkbookUser::where(['id'=>$book_id])->update(['cover_img'=>str_replace(config('workbook.thumb_image_url'), '', $request->now_img)]);
                }else{
                    if($book_id<10000000){
                        AWorkbook1010::where(['id'=>$book_id])->update(['cover'=>$request->now_img]);
                    }
                }
            }elseif($answer_id==='cip'){
                AWorkbook1010::where(['id'=>$book_id])->update(['cip_photo'=>$request->cip_photo]);
            }else{
                $now_img = str_replace(config('workbook.thumb_image_url'), '', $request->now_img);
                WorkbookAnswerRds::where(['id'=>$answer_id,'bookid'=>$book_id])->update(['answer'=>$now_img]);
            }

        }
        else if($type==='send_msg'){
            //book_id,up_uid,msg
            $data['hid'] = $request->book_id;
            $data['huid'] = $request->up_uid;
            $data['uid'] = \Auth::user()->related_uid;
            $data['type'] = 'answer_audit';
            $data['msg'] = $request->msg;
            $data['add_time'] = date('Y-m-d H:i:s');
            if(!PreMHomeworkMessage::create($data)) return response()->json(['status'=>0,'msg'=>'操作失败']);
        }
        else if($type==='update_isbn'){
            $data_0 = DB::connection('mysql_main')->select('select isbn,count(isbn) as num from pre_m_workbook_user where `status` = 0 and LENGTH(isbn)=13 group by isbn');
            $data_1 = DB::connection('mysql_main')->select('select isbn,count(isbn) as num from pre_m_workbook_user where `status` = 1 and LENGTH(isbn)=13 group by isbn');
            $data_9 = DB::connection('mysql_main')->select('select isbn,count(isbn) as num from pre_m_workbook_user where `status` = 9 and LENGTH(isbn)=13 group by isbn');

        }else if($type==='save_book'){
            $book_id = $request->now_book_id;
            //id,redirect_id,book_name,isbn,version_year,grade_id,subject_id,volumes_id,version_id,sort
            if($book_id<10000000){
                $book_now = AWorkbook1010::find($book_id);
                $book_only = NewOnly::find($book_id);
                if(!$book_now) return response()->json(['status'=>0]);
                $book_now->bookname = $request->book_name;
                $book_now->sort = intval($request->sort);
                $book_now->redirect_id = $request->redirect_id;
            }else{
                $book_now = PreMWorkbookUser::find($book_id);
                if(!$book_now) return response()->json(['status'=>0]);
                $book_now->sort_name = $request->book_name;
                $book_now->sort_id = intval($request->sort);
            }
            $isbn = str_replace('-', '', $request->isbn);
            if(strpos($isbn,'|')<=0 && !check_isbn($isbn)){
                return response()->json(['status'=>0]);
            }
            $book_now->isbn = $isbn;
            $book_now->version_year = $request->version_year;
            $book_now->grade_id = $request->grade_id;
            $book_now->subject_id = $request->subject_id;
            $book_now->volumes_id = $request->volumes_id;
            $book_now->version_id = $request->version_id;
            $book_now->newname = str_replace($book_now->version_year.'年','',$book_now->bookname);



            if(!$book_now->save()){
                return response()->json(['status'=>0]);
            }else{
                if($book_id<10000000) {
                    $book_only = NewOnly::where('newname',$book_now->newname)->first();
                    $book_only->sort = intval($request->sort);
                    $book_only->isbn = $isbn;
                    $book_only->grade_id = $request->grade_id;
                    $book_only->subject_id = $request->subject_id;
                    $book_only->volumes_id = $request->volumes_id;
                    $book_only->version_id = $request->version_id;
                    $book_only->newname = str_replace($book_now->version_year . '年', '', $book_now->bookname);
                    $book_only->save();
                }
            }
            TaskUid::create(['type'=>'feedback_info','data'=>$book_id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
        }else if($type==='confirm_done'){
            $book_id = $request->book_id;
            AWorkbook1010::where(['id'=>$book_id])->update(['status'=>1]);
            TaskUid::create(['type'=>'newbook','data'=>$book_id,'uid'=>\Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
            if(!AWorkbookFeedback::where(['bookid'=>$book_id])->update(['update_uid'=>\Auth::id(),'status'=>1,'updated_at'=>date('Y-m-d H:i:s',time())])){
                return response()->json(['status'=>0,'msg'=>'操作失败']);
            }else{
                TaskUid::create(['type'=>'feedback','data'=>$book_id,'uid'=>\Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
            }
        }else if($type==='not_need_deal'){
            $book_id = $request->book_id;
            if(!AWorkbookFeedback::where(['bookid'=>$book_id])->update(['update_uid'=>\Auth::id(),'not_need_deal'=>1,'updated_at'=>date('Y-m-d H:i:s',time())])){
                return response()->json(['status'=>0,'msg'=>'操作失败']);
            }
        }else if($type==='save_order'){
            $from=$request->from;
            $now_book_id = $request->book_id;
            $now_all_answer = $request->img_ids;

            foreach ($now_all_answer as $key=>$value){
                $now['text'] = $key+1;
                $now['textname'] = '第'.$now['text'].'页';
                //$now['tid'] = $tid;
                WorkbookAnswerRds::where([['bookid',$now_book_id],['id',$value]])->update($now);
            }
            if($from=='userAnswer'){
                $id=PreMWorkbookAnswerUser::insertGetId([
                    'book_id'=>$now_book_id,
                    'answer_img'=>'',
                    'up_uid'=>Auth::id(),
                    'op_uid'=>Auth::id(),
                    'status'=>1,
                    'hdid'=>0
                ]);
                PreMWorkbookAnswerUser::where(['book_id'=>$now_book_id])->where('id','!=',$id)
                    ->update(['status'=>9,'op_uid'=>auth::id()]);
            }
        }
        else if($type==='update_redirect'){
            $book_id = $request->book_id;
            $redirect_id = $request->redirect_id;
            if($book_id<10000000 and AWorkbook1010::where('id',$book_id)->update(['redirect_id'=>$redirect_id])){
                TaskUid::create(['type'=>'redirect_id','data'=>$book_id.'_'.$redirect_id,'uid'=>Auth::id(),'updated_at'=>date('Y-m-d H:i:s',time())]);
                return return_json();
            }
        }else if($type =='recovery_answer'){
            $answer_id = $request->answer_id;
            AWorkbookAnswer::where(['id'=>$answer_id])->update(['status'=>1]);
        }

        return response()->json(['status'=>1,'msg'=>'操作成功']);
    }

    protected function update_text($book_id)
    {

    }

}
