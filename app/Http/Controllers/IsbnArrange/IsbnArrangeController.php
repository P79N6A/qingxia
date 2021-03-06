<?php

namespace App\Http\Controllers\IsbnArrange;

use controller\search;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ATongjiHotbook29;
use App\PreMWorkbookUser;
use App\ASubsort;
use App\AWorkbook1010Zjb;
use App\AOnlybookZjb;
use App\AWorkbook1010;
use Auth;
use DB;
use App\ZydsBook;
use App\AWorkbookAnswer1010Zjb;

class IsbnArrangeController extends Controller
{
    public function isbn_list($sort_id=-1,$area='全部地区',$type=0,$status=0){

        $data['booklist']=ATongjiHotbook29::where(['op_uid'=>0])
            ->where(function($query)use($sort_id,$area,$type,$status){
                if($status){
                    $query->where('op_uid','!=',0);
                }else{
                    $query->where(['op_uid'=>0]);
                }
                if($sort_id>=0) $query->where(['sort'=>$sort_id]);
                if($area!='全部地区') $query->where('searcharea','like',"$area%");
                if($type==1){
                    $query->where('zyds_name','!=','0')->where('jiajiao_name','=','0');
                }elseif($type==2){
                    $query->where('jiajiao_name','!=','0')->where(DB::raw('left(zyds_name,4)'),'>',DB::raw('left(jiajiao_name,4)'));
                }
                $query ->where(['bookname'=>'']);
            })
            ->select('onlyid','onlyid_2','isbn','searchnum','sort_name','searcharea','zyds_name','jiajiao_name')
            ->with('user_book:isbn')
            ->orderBy('searchnum','desc')
            ->paginate(20);

        $data['sort_id']=$sort_id;
        $data['area']=$area;
        $data['type']=$type;
        $data['status']=$status;
        return view('IsbnArrange.isbn_list', compact('data'));
    }

    public function book_list($isbn=''){
        $data['book']=ATongjiHotbook29::where(['isbn'=>$isbn])
            ->with('has_ssort:sort_id,ssort_id,ssort_name')
            ->with('user_book:isbn,cover_img')
            ->first();
        /*$data['book']=PreMWorkbookUser::where(['isbn'=>$isbn])
            ->with('hotbook29:isbn,description,grade_id,subject_id,version_id,volume_id,sort,ssort_id,bookname')
            ->with('hotbook29.has_ssort:sort_id,ssort_id,ssort_name')
            ->first();*/
        //dd($data);
        $data['existing_book']=AWorkbook1010Zjb::where(function($query)use($isbn){
            $query->where(['isbn'=>$isbn])->whereIn('status',[1,7,14,20,21]);
        })->orderBy('id','desc')->select('id','bookname','cover','grade_id','subject_id','volumes_id','version_id','collect_count','version_year','sort','ssort_id')->get();
        $data['zyds_book']=ZydsBook::where(['isbn'=>$isbn])
            ->select('id','newid','coverImageUrl','cover_time','status')
            ->with('has_answer:homeworkId,pageImageUrl,id')
            ->get();

        if($data['existing_book']->count()>0){
            $data['book']->grade_id=$data['existing_book'][0]->grade_id;
            $data['book']->subject_id=$data['existing_book'][0]->subject_id;
            $data['book']->volumes_id=$data['existing_book'][0]->volumes_id;
            $data['book']->version_id=$data['existing_book'][0]->version_id;
            $data['book']->sort=$data['existing_book'][0]->sort;
            $data['book']->ssort_id=$data['existing_book'][0]->ssort_id;
        }
        //dd($data);
        return view('IsbnArrange.book_list',compact('data'));
    }


    public function ajax(Request $request,$type){
        switch($type){
            case 'get_ssort':
                $sort_id=intval($request->sort_id);
                $re=ASubsort::where(['sort_id'=>$sort_id])->select('ssort_id','ssort_name')->get();
                return return_json($re);
                break;

            case 'save_book':
                $isbn=$request->isbn;
                $bookinfo=$request->bookinfo;
                $zyds_answer_all=$request->zyds_answer_all;

                if(!strstr($bookinfo['cover'],'http://user.1010pic.com')){
                    $aa=parse_url($bookinfo['cover']);
                    $bookinfo['cover']=config('workbook.thumb_image_url').'d'.$aa['path'];
                }

                $re=AWorkbook1010Zjb::where([
                    'grade_id'=>$bookinfo['grade_id'],
                    'subject_id'=>$bookinfo['subject_id'],
                    'volumes_id'=>$bookinfo['volumes_id'],
                    'version_id'=>$bookinfo['version_id'],
                    'version_year'=>$bookinfo['version_year'],
                    'sort'=>$bookinfo['sort_id'],
                    'ssort_id'=>$bookinfo['ssort_id']
                ])->first(['id','isbn','cover']);

                if(empty($re)){
                    $onlyid=sprintf('%05d%02d%02d%02d%02d',$bookinfo['sort_id'],$bookinfo['grade_id'],$bookinfo['subject_id'],$bookinfo['version_id'],$bookinfo['ssort_id']);
                    $maxbookid=AWorkbook1010Zjb::where('id','<',1000000)->max('id');
                    $bookid=$maxbookid+1;
                    AWorkbook1010Zjb::create([
                        'id'=>$bookid,
                        'bookname'=>trim($bookinfo['bookname']),
                        'bookcode'=>md5($bookinfo['bookname']+1),
                        'isbn'=>$isbn,
                        'onlyid'=>$onlyid,
                        'cover'=>$bookinfo['cover'],
                        'grade_id'=>$bookinfo['grade_id'],
                        'subject_id'=>$bookinfo['subject_id'],
                        'volumes_id'=>$bookinfo['volumes_id'],
                        'version_id'=>$bookinfo['version_id'],
                        'version_year'=>$bookinfo['version_year'],
                        'sort'=>$bookinfo['sort_id'],
                        'ssort_id'=>$bookinfo['ssort_id'],
                        'addtime'=>date('Y-m-d H:i:s'),
                        'grade_name'=>'',
                        'subject_name'=>'',
                        'volume_name'=>'',
                        'version_name'=>'',
                        'sort_name'=>'',
                        'content_status'=>1,
                        'status'=>21,
                        'result_status'=>0
                    ]);
                    if(empty($bookid)) return return_json([],0,'书本插入失败');
                    if(!empty($zyds_answer_all)){  //作业大师更新到answer表
                        $zyds_id=intval($request->zyds_id);
                        foreach($zyds_answer_all as $k=>$answer){
                            $aa=parse_url($answer);
                            $new_answer='d'.$aa['path'];
                            AWorkbookAnswer1010Zjb::create([
                                'bookid'=>$bookid,
                                'book'=>md5($bookinfo['bookname']),
                                'text'=>$k+1,
                                'textname'=>'第'.($k+1).'页',
                                'answer'=>$new_answer,
                                "md5answer"=>md5($new_answer),
                                "addtime"=>date('Y-m-d H:i:s',time()),
                                "hdid"=>-1
                            ]);
                        }

                       ZydsBook::where(['id'=>$zyds_id])->update(['status'=>1]);

                    }

                    $re2=AOnlybookZjb::where(['onlyid'=>$onlyid])->first(['cover','isbn']);
                    if(empty($re2)){
                        $bookname_only=preg_replace('#全一册上|全一册下|全一册|上册|下册|^20\d{2}年|201\d{1}#','',$bookinfo['bookname']);
                        $a=AOnlybookZjb::create([
                            'onlyid'=>$onlyid,
                            'bookname'=>trim($bookname_only),
                            'sort_id'=>$bookinfo['sort_id'],
                            'grade_id'=>$bookinfo['grade_id'],
                            'subject_id'=>$bookinfo['subject_id'],
                            'version_id'=>$bookinfo['version_id'],
                            'ssort_id'=>$bookinfo['ssort_id'],
                            'version_year'=>2018,
                            'cover'=>$bookinfo['cover'],
                            'isbn'=>$isbn,
                            'status'=>1
                        ]);
                        if(!$a) return return_json([],0,'onlybook插入失败');
                    }else{
                        $up2=[];
                        $up2['isbn']=$this->combine_isbn($isbn,$re2['isbn']);
                        AOnlybookZjb::where(['onlyid'=>$onlyid])->update($up2);
                    }
                }else{
                    $up=[];
                    $up['isbn']=$this->combine_isbn($isbn,$re['isbn']);
                    $up['status']=20;
                    AOnlybookZjb::where(['id'=>$re['id']])->update($up);
                }

                return return_json([],1,'保存成功');
                break;

            case 'end_edit':
                $isbn=$request->isbn;
                ATongjiHotbook29::where(['isbn'=>$isbn])->update([
                    'op_uid'=>auth::id(),
                    'op_time'=>date('Y-m-d H:i:s')
                ]);
                $re=PreMWorkbookUser::where(['isbn'=>$isbn])->update(['status'=>4]);//标记已处理过的isbn
                return return_json(['status'=>$re]);
                break;
        }
    }

    public function combine_isbn($isbn,$isbn2){
        $a=explode('|',$isbn);
        $a2=explode('|',$isbn2);
        return implode('|',array_filter(array_unique(array_merge($a,$a2))));
    }

}
