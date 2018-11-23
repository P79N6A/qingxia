<?php

namespace App\Http\Controllers\UserAbout;

use App\AWorkbookFeedback;
use App\HdBook;
use App\TaskHdBook;
use App\WorkbookAnswer;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    public function index($sortBy='num',$is_book=0,$status=0,$has_new_book=0)
    {
        $data['sortBy'] = $sortBy;
        $data['is_book'] = intval($is_book)!==0?1:0;
        $data['status'] = intval($status)!==0?1:0;
        $data['has_new_book'] = intval($has_new_book)!==0?1:0;


        //黄少敏5，苏蕾8，张连荣11，肖高萍17，宋晗18，印娜19，张玲莉20
        $uid = \Auth::id();
        $number = -1;
        if($uid===8){
            $whereRaw = "bookid%6=0";
        }elseif($uid===11){
            $whereRaw = "bookid%6=1";
        }elseif($uid===17){
            $whereRaw = "bookid%6=2";
        }elseif($uid===18){
            $whereRaw = "bookid%6=3";
        }elseif($uid===19){
            $whereRaw = "bookid%6=4";
        }elseif($uid===20){
            $whereRaw = "bookid%6=5";
        }else{
            $whereRaw = "1=1";
        }
        if($is_book==1){
            $whereRaw .= ' and is_book = 1 ';
        }
        if($has_new_book==1){
            //$whereRaw .= ' and newid>0 ';
        }

        if($sortBy==='collect'){
            $data['feedback'] = AWorkbookFeedback::From('a_workbook_feedback as f')->leftJoin('a_workbook_1010 as a','f.bookid','=','a.id')->whereRaw($whereRaw)->where([['bookid','>',0],['uuid','>',0],['a.volumes_id',2],['f.status','=',$data['status']],['f.not_need_deal',0]])->select('bookid',DB::raw('count(bookid) as num'),DB::raw('any_value(f.collect_count) as collect_count'),DB::raw('any_value(f.update_uid) as update_uid'),DB::raw('any_value(f.updated_at) as updated_at'),DB::raw('any_value(f.verified_at) as verified_at'),DB::raw('group_concat(text) as text'))->with('has_user_book:id,sort_name,isbn')->with('has_user:id,name')->with('has_book:id,bookname,newid,collect_count,isbn')->orderBy('collect_count','desc')->groupBy('bookid')->paginate(20);
        }else{
            $data['feedback'] = AWorkbookFeedback::From('a_workbook_feedback as f')->join('a_workbook_1010 as a','f.bookid','=','a.id')->whereRaw($whereRaw)->where([['bookid','>',0],['uuid','>',0],['f.status','=',$data['status']],['f.not_need_deal',0]])->select('bookid',DB::raw('count(bookid) as num'),DB::raw('group_concat(text) as text'),DB::raw('any_value(update_uid) as update_uid'),DB::raw('any_value(f.updated_at) as updated_at'),DB::raw('any_value(verified_at) as verified_at'))->with('has_user_book:id,sort_name,isbn')->with('has_user:id,name')->with('has_book:id,bookname,newid,collect_count,isbn')->with('has_book.hasOnly:newname,book2018,book2017,book2016,book2015,book2014')->orderBy('num','desc')->groupBy('bookid')->paginate(20);
        }

        //select distinct bookid,updated_at,update_uid from a_workbook_feedback where status = 1
//        $data['situation'] = AWorkbookFeedback::where([['status',1],['updated_at','!=','']])->select(DB::raw('distinct bookid,update_uid,DATE_FORMAT(updated_at,"%Y-%m-%d") as updated_at'))->get();
        //dd($data['situation']->groupBy('updated_at')->);
        foreach ($data['feedback'] as $key=>$feedback){
            $data['feedback'][$key]['answer_num'] = WorkbookAnswer::where(['bookid'=>$feedback->has_book->id,'status'=>1])->count();
            $data['feedback'][$key]['new_answer_num'] = WorkbookAnswer::where(['bookid'=>$feedback->has_book->newid,'status'=>1])->count();
        }

        return view('user_about.feedback',compact('data'));
    }

    public function status($start='',$end=''){
        if($start==''){
            $data['start'] = date('Y-m-d',time()).' 00:00:00';
        }else{
            $data['start'] = $start;
        }
        if($end==''){
            $data['end'] = date('Y-m-d',time()+86400).' 00:00:00';
        }else{
            $data['end'] = $end;
        }

        $query_start = $data['start'].' 00:00:00';
        $query_end = $data['end'].' 23:59:59';
        $all_books = AWorkbookFeedback::where([['updated_at','>=',$query_start],['updated_at','<=',$query_end]])->select('bookid','update_uid','updated_at',DB::raw('any_value(verified_at) as verified_at'))->groupBy('bookid','update_uid','updated_at')->with('has_book:id,bookname')->with('has_user:id,name')->get();

        $data['all_books'] = $all_books->groupBy('update_uid')->transform(function ($key,$item){
            return $key->sortByDesc('updated_at');
        });
        return view('user_about.feedback_status',['data'=>$data]);
    }

    public function isbn_search($isbn){
        if(strpos($isbn, '|')){
            $isbn = explode('|', $isbn)[0];
        }
//        $header = ['headers' => [
//            'Client-Ip'=>'1.1.1.1',
//            'Real-ip'=>'1.1.1.1',
//            'X-forwarded-for'=>'1.1.1.1',
//            'PROXY_USER'=>'1.1.1.1',
//            'Charset'=>'UTF-8',
//            'appVersion'=>129,
//            'appType'=>2,
//            'timeStamp'=>'1519954423678',
//            'userId'=>-1,
//            'mac'=>'00:00:00:00:00:00',
//            'androidId'=>'YV7Y8bcKUa0QsbNs',
//            'channel'=>'juniorchannal',
//            'imei'=>'000534489750862',
//            'token'=>'2d3dc1133edb75ce09c48d808db8d6a4',
//            'deviceType'=>'Huawei CAM-L21',
//            'windowsType'=>1,
//            'User-Agent'=>'okhttp/3.3.0',
//            'Content-Type'=>'application/x-www-form-urlencoded',
//            'uuid'=>'e935cfb8d44a2670c4c896fcf5efa424',
//        ]];
//        $http = new \GuzzleHttp\Client($header);
//
//        $res = $http->post('https://handler.hdzuoye.com/hd-server/recommend/getRecommendAnswerByBarcode.do',[
//            'body'=>'data={"gradeId":-1,"subjectId":-1,"userGradeId":-1,"sortId":-1,"version":"","bookVersionId":-1,"barcode":"'.$isbn.'","answerType":1,"volumes":-1}&pageIndex=1&pageSize=20',
//            // 'cert' => ['E:\wamp64\bin\php\cacert.pem']
//        ]);
//        $all_books = \GuzzleHttp\json_decode($res->getBody()->getContents());
//        $all_lxc = [];
//
//        if($all_books->data && $all_books->data->totalCount>1){
//            $all_lxc = $all_books->data->selfAnswerVoList;
//            foreach ($all_lxc as $key=>$lxc){
//                $res_now = $http->post('https://handler.hdzuoye.com/hd-server/selfAnswerPath/getSelfAnswerPathByObjectId.do',[
//                    'body'=>'objectId='.$lxc->objectId.'&answerType='.$lxc->answerType.'&userId=-1'
//                ]);
//                $all_lxc[$key]->has_answers = \GuzzleHttp\json_decode($res_now->getBody()->getContents())->data->answerPathVoList;
//            }
//        }
        $data['book_id'] = $isbn;
        $data['all_books'] = TaskHdBook::where('isbn',$isbn)->select('id','bookName','objectId','coverImage')->with('has_answers:id,objectId,answerPathImage')->get();
        //$data['all_books'] = $all_lxc;

        return view('user_about.search_isbn',['data'=>$data]);
    }

    public function api(Request $request,$type){
        switch ($type){
            case 'verify_confirm':
                $book_id = $request->book_id;
                AWorkbookFeedback::where('bookid',$book_id)->update(['verified_at'=>date('Y-m-d H:i:s',time())]);
            break;

        }
        return response()->json(['status'=>1]);
    }
}
