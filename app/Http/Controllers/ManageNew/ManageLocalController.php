<?php

namespace App\Http\Controllers\ManageNew;

use App\LocalModel\AWorkbook1010Bd;
use App\LocalModel\AWorkbookAnswerBd;
use App\LocalModel\AWorkbookAnswerNew;
use App\LocalModel\LocalCip;
use App\Sort;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageLocalController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

        $all_dictories = \File::directories('//Qingxia23/www/bookcover/');
        foreach ($all_dictories as $key=>$dic){
            $data['all_dict'][$key] = substr($dic, 27);
        }



        //$data['all_sort'] = AWorkbook1010Bd::select('sort_name',DB::raw('count(sort_name) as num'))->groupBy('sort_name')->orderBy('num','desc')->get();

        return view('manage_new.local.index',['data'=>$data]);

    }

    public function sort_list($dir_name='',$type='pending')
    {

        //http://192.168.0.117/book\53天天练_155\人教版_0\img0053.jpg

        if($dir_name==''){
            abort(404);
        }
        $data['type_now'] = $type;
        $data['now_dir'] = $dir_name;

        $all_files = \File::allFiles('//Qingxia23/www/bookcover/'.$dir_name);
        foreach ($all_files as $key=>$value){
            if(!in_array(\File::extension($value),['jpg','png','gif'])){
                unset($all_files[$key]);
            }
            $now_img = str_replace('\\','/', substr($value->getPathName(), 26));
            if($type=='pending'){
                if(AWorkbook1010Bd::where('cover_photo',$now_img)->orWhere('cip_photo',$now_img)->count()>0){
                    unset($all_files[$key]);
                }
                //
            }else{
                if(AWorkbook1010Bd::where('cover_photo',$now_img)->orWhere('cip_photo',$now_img)->count()==0){
                    unset($all_files[$key]);
                }
            }

        }
        $data['now_books'] = collect($all_files)->split(intval(count($all_files)/2));

        $data['all_isbn'] = [];
        foreach ($data['now_books'] as $key=>$value){
            $data['all_isbn'][$key] = '';
            foreach ($value as $cover){

                $now_path = str_replace('\\','/', substr($cover->getPathName(), 26));

                $isbn_now = LocalCip::where('cip_photo',$now_path)->select('isbn')->first();
                if(count($isbn_now)===1 && $isbn_now->isbn!=''){
                    $data['all_isbn'][$key] = $isbn_now->isbn;
                }
                //如果有匹配记录
                //查出数据并显示
                $now_connect = AWorkbook1010Bd::where(['cover_photo'=>$now_path])->orWhere(['cip_photo'=>$now_path])->select()->first();
                if(count($now_connect)===1){
                    $data['all_book_info'][$key] = $now_connect;
                    $data['all_book_answer'][$key] = $now_connect->has_answers;
                }else{
                    $data['all_book_info'][$key] = '';
                    $data['all_book_answer'][$key] = '';
                }
            }
        }


//        $a = substr($data['now_books'][0][0], 26);
//        dd('http://192.168.0.117/bookcover/'.substr($data['now_books'][0][0]->getpathname(),26));
//        dd($data['now_books'][0][0]);


        $data['all_sorts'] = AWorkbook1010Bd::select('sort_name')->groupBy('sort_name')->get();

        $data['related_sorts'] = Sort::where('name','like','%'.$dir_name.'%')->select('id','name')->get();



//        $all_sort_now = AWorkbook1010Bd::where('sort_name','53天天练')->select()->get();
//
//        $data['all_sort'] = $all_sort_now;
//        $data['all_sort_group'] = $all_sort_now->groupBy('grade_id')->transform(function ($item,$key){
//            return $item->groupBy('subject_id')->transform(function ($item1,$key1){
//                return $item1->groupBy('version_name');
//            })->sortBy(function ($value2,$key2){
//                return $key2;
//            });
//        })->sortBy(function ($value3,$key3){
//            return $key3;
//        });
//
//        dd($data['all_sort_group']);





        //dd(collect($all_files)->split(intval(count($all_files)/2)));
//        foreach ($all_dictories as $key=>$dic){
//            $data['all_dict'][$key] = substr($dic, 27);
//        }


        
//        $all_sort_now = AWorkbook1010Bd::where('sort_name',$sort_name)->select()->get();
//
//        $data['all_sort'] = $all_sort_now;
//        $data['all_sort_group'] = $all_sort_now->groupBy('grade_id')->transform(function ($item,$key){
//           return $item->groupBy('subject_id')->sortBy(function ($value1,$key1){
//               return $key1;
//           });
//        })->sortBy(function ($value2,$key2){
//            return $key2;
//        });
        return view('manage_new.local.sort_list',['data'=>$data]);

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
                $all_answer = AWorkbookAnswerNew::where(['sort_name'=>$now_sort_name,'version_name'=>$now_version_name,'grade_name'=>$now_grade_name,'subject_name'=>$now_subject_name,])->select()->orderBy('answer','asc')->get();
                $all_string = '';
                foreach ($all_answer as $answer){
                    $all_string .= '<a class="thumbnail"><img data-id="'.$answer->id.'" class="answer_pic real_pic" src="'.'http://192.168.0.117/book/'.substr($answer->answer,21).'" alt=""><i class="badge bg-blue delete_this">移除</i><i class="badge bg-red exchange" data-type="left">与左图交换</i><i class="badge bg-red exchange" data-type="right">与右图交换</i></a>';
                }
                return response()->json(['status'=>1,'answer_info'=>$all_string]);
                break;

            case 'confirm_done':
//now_id,bookname,version_year,sort_id,grade_id,subject_id,volume_id,version_id,isbn,answer_all
                //answer_grade_name,answer_subject_name,answer_sort,answer_version,cover_photo,cip_photo
                //$now_id = $this->request->now_id;
                $book['bookname'] = $this->request->bookname;
                $book['version_year'] = $this->request->version_year;
                $book['sort'] = $this->request->sort_id;
                $book['grade_id'] = $this->request->grade_id;
                $book['subject_id'] = $this->request->subject_id;
                $book['volumes_id'] = $this->request->volume_id;
                $book['version_id'] = $this->request->version_id;
                $book['cover_photo'] = str_replace('\\', '/', $this->request->cover_photo);
                $book['cip_photo'] = str_replace('\\', '/', $this->request->cip_photo);
                $old_info['grade_name'] = $this->request->answer_grade_name;
                $old_info['subject_name'] = $this->request->answer_subject_name;
                $old_info['sort_name'] = $this->request->answer_sort;
                $old_info['version_name'] = $this->request->answer_version;
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
                if($book['isbn']){
                    if(!check_isbn($book['isbn'])){
                        return response()->json(['status'=>0,'msg'=>'isbn验证不通过']);
                    }
                }
                if($book['sort']<=0){
                    return response()->json(['status'=>0,'msg'=>'请填写系列']);
                }
                if(empty($answer['answer_all'])){
                    return response()->json(['status'=>0,'msg'=>'答案为空']);
                }

                $book['bookcode'] = md5($book['bookname'].$book['version_year'].$book['sort'].$book['grade_id'].$book['subject_id'].$book['volumes_id'].$book['version_id'].$book['isbn'].'from_local');

                DB::transaction(function() use ($old_info,$book,$answer){
                    if($book['isbn']){
                        $now_press = get_press($book['isbn']);
                    }else{
                        $now_press = 0;
                    }
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
                    $book_now = AWorkbook1010Bd::where($old_info)->first();
                    AWorkbook1010Bd::where($old_info)->update($book);

                    $hdid = 0;
                    foreach ($answer['answer_all'] as $key=>$value){
                        $now['text'] = $key+1;
                        $now['textname'] = '第'.$now['text'].'页';
                        $now['bookid'] = $book_now->id;
                        $now['book'] = $book['bookcode'];
                        AWorkbookAnswerBd::where(['id'=>$value])->update($now);
                    }



                });
                break;
        }
        return response()->json(['status'=>1]);
    }
}
