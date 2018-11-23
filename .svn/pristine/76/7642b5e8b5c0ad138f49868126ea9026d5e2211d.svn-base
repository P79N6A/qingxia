<?php

namespace App\Http\Controllers\NewBuy;

use App\AWorkbook1010;
use App\LocalModel\NewBuy\New1010;
use App\LocalModel\NewBuy\NewOnly;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RepeatAnswerController extends Controller
{
    public function repeat_list()
    {

        //所有买的书的newname
//        \Cache::remember('all_newname', 30, function (){
//            return NewOnly::where([['id','>',1000000],['cover','like','%/pic19/%'],['cover','not like','%/pic19/new/%']])->select(DB::raw('distinct newname'))->with()->get();
//        });

        //select newname,count(newname) as num from a_workbook_1010 where version_year = 2018 and newname!=''  group by newname having num >1 order by sort asc
//        $all_repeat = NewOnly::where([['version_year','=',2018],['newname','!=','']])->select('newname',DB::raw('count(newname) as num'))->groupBy('newname')->having('num','>',1)->orderBy('sort','asc')->get();
        //,['version_year','=',2018],['newname','!=',''],['status',1],['redirect_id','=',0]
        //['version_year','=',2018],['newname','!=',''],['status',1],['redirect_id','=',0]   ->havingRaw('count(newname)>1')

        //repeat_books
//        $data['all_repeat'] = New1010::where([['is_wrong',1]])->select('newname',DB::raw('count(newname) as repeat_num'),DB::raw('any_value(sort) as sort_now'))->groupBy('newname','sort')->orderBy('newname','asc')->paginate(10);
//
//        if(isset($_REQUEST['test']) and $_REQUEST['test']==1){
//
//            dd(New1010::where([['is_wrong',1]])->select('newname',DB::raw('count(newname) as repeat_num'),DB::raw('any_value(sort) as sort_now'))->groupBy('newname','sort')->havingRaw('count(newname)>1')->orderBy('newname','asc')->toSql());
//        }
//
//        foreach ($data['all_repeat'] as $key=>$book){
//            //,['redirect_id','=',0]
//            $data['all_repeat_books'][$key] = New1010::where([['newname',$book->newname],['version_year',2018]])->select('id','bookname','cover','collect_count')->withCount('hasAnswers')->orderBy('id','asc')->get();
//        }

        //repeat_confirm_books
        //'bookname','like','%课本%']
        $data['all_repeat'] = New1010::where([['sort',0]])->select('grade_id','subject_id','volumes_id','version_id','sort',DB::raw('count(*) as num'))->groupBy('grade_id','subject_id','volumes_id','version_id','sort')->havingRaw('count(*)>1 and sum(book_confirm)>0 and sum(has_change)=0')->orderBy('grade_id','asc')->orderBy('subject_id','asc')->orderBy('volumes_id','asc')->orderBy('version_id','asc')->paginate(100);

        foreach ($data['all_repeat'] as $key=>$book){
            //,['redirect_id','=',0]
            $data['all_repeat_books'][$key] = New1010::where(['grade_id'=>$book->grade_id,'subject_id'=>$book->subject_id,'version_id'=>$book->version_id,'sort'=>$book->sort,'volumes_id'=>$book->volumes_id])->select('id','bookname','cover','collect_count','book_confirm','isbn')->withCount('hasAnswers')->orderBy('id','asc')->get();
        }

        return view('new_buy.repeat_books',compact('data'));
    }

    public function repeat_detail($newname)
    {
        $data['all_repeat_books'] = New1010::where(['newname'=>$newname,'version_year'=>2018])->select('id','bookname','cover','cip_photo','collect_count','version_year','isbn','redirect_id')->withCount('hasAnswers')->get();

        return view('new_buy.repeat_detail',compact('data'));
    }

    public function repeat_detail_books($grade_id,$subject_id,$volumes_id,$version_id)
    {

        $data['grade_id'] = $grade_id;
        $data['subject_id'] = $subject_id;
        $data['volumes_id'] = $volumes_id;
        $data['version_id'] = $version_id;
        $data['all_repeat_books'] = New1010::where(['grade_id'=>$grade_id,'subject_id'=>$subject_id,'version_id'=>$version_id,'sort'=>0,'volumes_id'=>$volumes_id])->select('id','bookname','cover','cip_photo','collect_count','version_year','isbn','redirect_id','book_confirm')->withCount('hasAnswers')->get();

        return view('new_buy.repeat_detail',compact('data'));
    }
}
