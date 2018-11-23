<?php

namespace App\Http\Controllers\NewAnswerAudit;

use App\AWorkbook1010;
use App\AWorkbookFeedback;
use App\AWorkbookRds;
use App\BookVersionType;
use App\Http\Controllers\OssController;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\TaskUid;
use App\PreHomeMyfavorite;
use App\PreMHomeworkMessage;
use App\PreMWorkbookAnswerUser;
use App\PreMAnswerUserBlacklist;

use App\PreMWorkbookUser;
use App\PreMWorkbookUserGroup;
use App\Volume;
use App\isbnAll;
use App\WorkbookAnswer;
use App\PreMAnswerUserAward;

use Auth;
use App\WorkbookAnswerRds;
use App\LocalModel\isbnTemp;
use App\LocalModel\IsbnTempEveryday;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use OSS\OssClient;
use ZipArchive;


class BooklistController extends Controller
{

    //练习册操作
    public function booklist(Request $request,$type=0)
    {
        if($type!=1){
            /*$data['list']=PreMWorkbookAnswerUser::from('pre_m_workbook_answer_user as u')
                ->leftJoin('a_workbook_1010 as b','u.book_id','b.id')
                ->where(['u.status'=>0,'b.status'=>14])
                ->select('u.book_id',DB::raw('any_value(b.bookname) as bookname'),DB::raw('any_value(u.addtime) as addtime'),DB::raw('any_value(b.isbn) as isbn'))
                ->groupBy('u.book_id')
                ->paginate(20);*/
                $where=[];
               if(auth::id()!=2){
                   $where['t.uid']=auth::id();
               }
               if($type==0){
                   $where['b.status']=14;
               }elseif($type==2){
                   $where['b.status']=15;
               }
               $data['list']=AWorkbook1010::from('a_workbook_1010 as b')
                   ->leftJoin('a_tongji_search_isbn_temp2 as t','b.isbn','t.isbn')
                   ->where($where)
                   ->where('b.volumes_id','!=',2)
                   ->select('b.id as book_id','b.bookname','b.isbn','b.addtime','b.status')
                   ->orderBy('addtime','desc')
                   ->paginate(20);
               foreach($data['list'] as $k=>$v){
                   $data['list'][$k]['has_answer']=PreMWorkbookAnswerUser::where(['book_id'=>$v['book_id'],'status'=>0])->count();
                   $data['list'][$k]['has_imperfect']=PreMWorkbookAnswerUser::where(['book_id'=>$v['book_id'],'status'=>2])->count();
               }
            //dd($data['list']);
        }else{
            $where=['u.book_id','>',0];
            if(auth::id()!=2){
                $where=['u.op_uid',auth::id()];
            }
            $data['list']=PreMWorkbookAnswerUser::from('pre_m_workbook_answer_user as u')
                ->leftJoin('a_workbook_1010 as b','u.book_id','b.id')
                ->where([['b.status',1],['u.op_uid','!=',0],$where])
                ->select('u.book_id',DB::raw('any_value(b.bookname) as bookname'),DB::raw('any_value(u.addtime) as addtime'),DB::raw('any_value(b.isbn) as isbn'),DB::raw('any_value(u.op_uid) as op_uid'),DB::raw('any_value(u.uptime) as uptime'))
                ->with('has_user:id,name')
                ->groupBy('u.book_id')
                ->orderBy('uptime','desc')
                ->paginate(20);
            //dd($data['list'])
        }
        $data['type']=$type;
        $data['page']= $request->page==null?1:$request->page;
        //dd($data);
        return view('new_answer_audit.booklist',compact('data'));
    }

    public function auditing($bookid,$type=0,$page=1){
        $data['bookinfo']=AWorkbook1010::where(['id'=>$bookid])->select('bookname','isbn','cover')->first();
        if($type!=1){
            $data['useranswers']=PreMWorkbookAnswerUser::where(['book_id'=>$bookid,'status'=>0])
                ->orwhere(['book_id'=>$bookid,'status'=>2])
                ->select('id','answer_img','up_uid','addtime','book_id','status')
                ->with('hasUserInfo:uid,nickname,qq')
                ->orderBy('addtime','desc')
                ->paginate(20);
        }else{
            $data['useranswers']=PreMWorkbookAnswerUser::where(['book_id'=>$bookid,'status'=>1])
                ->select('id','up_uid','addtime','book_id')
                ->with('hasUserInfo:uid,nickname,qq')
                ->get();
            $data['answers']=WorkbookAnswerRds::where(['bookid'=>$bookid,'status'=>1])->select('id','answer')->orderBy('text','asc')->get();
        }
       //dd($data);
        $data['type']=$type;
        $data['page']=$page;
        $data['bookid']=$bookid;
        return view('new_answer_audit.auditing',compact('data'));
    }

    public function updateStatus(Request $request){ //更改状态（不通过，不完整）
        $id=intval($request->id);
        $status=intval($request->status);
        $re=PreMWorkbookAnswerUser::where(['id'=>$id])->update(['status'=>$status,'op_uid'=>auth::id()]);
        exit(\GuzzleHttp\json_encode(['status'=>$re]));
    }

    public function answerpass(Request $request){ //答案通过
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $all_img=$request->all_img;
        $id=intval($request->id);
        $bookid=intval($request->bookid);
        $uid=intval($request->uid);
        $bookcode=AWorkbook1010::where(['id'=>$bookid])->select('bookcode')->first()->bookcode;

        if($uid==9999){
            $oss = new OssController(2);
            $now_dir = public_path('cache/'.$bookid.'/');
            foreach ($all_img as $k=>$v){
                if(!is_dir($now_dir)){
                    mkdir($now_dir);
                }
                $now_file = file_get_contents($v);
                $now_file_name = md5($now_file).'.jpg';
                file_put_contents($now_dir.$now_file_name, $now_file);
                $final_path = 'pic19/'.$bookid.'/sh/'.$now_file_name;
                $oss->uploadfile($final_path, $now_dir.$now_file_name);
                WorkbookAnswerRds::create([
                    'bookid'=>$bookid,
                    'book'=>$bookcode,
                    'text'=>$k+1,
                    'textname'=>'第'.($k+1).'页',
                    'answer'=>$final_path,
                    "md5answer"=>md5($now_file),
                    "addtime"=>date('Y-m-d H:i:s',time())
                ]);
            }

        }else{
            $oss = new OssController();
            foreach($all_img as  $k=>$v){
                $v = str_replace(config('workbook.user_image_url'), '', $v);
                if(!$oss->fileExist($v)) $oss->getOssClient()->copyObject('zyjl',$v,'daanpic',$v);
                if(!$oss->fileExist($v)) die('copy failed');
                WorkbookAnswerRds::create([
                    'bookid'=>$bookid,
                    'book'=>$bookcode,
                    'text'=>$k+1,
                    'textname'=>'第'.($k+1).'页',
                    'answer'=>$v,
                    "md5answer"=>md5($v),
                    "addtime"=>date('Y-m-d H:i:s',time())
                ]);
            }
        }
        
        AWorkbook1010::where(['id'=>$bookid])->update(['status'=>1,'uid'=>$uid]);
        PreMAnswerUserAward::create(['bookid'=>$bookid,'uid'=>$uid,'award'=>5]);
        PreMWorkbookAnswerUser::where(['id'=>$id])->update(['status'=>1,'op_uid'=>auth::id()]);
        PreMWorkbookAnswerUser::where(['book_id'=>$bookid])->where('id','!=',$id)
            ->update(['status'=>9,'op_uid'=>auth::id()]);
        exit(\GuzzleHttp\json_encode(['status'=>1]));
    }


    public function rotate_img(Request $request){//旋转图片
        $degree=$request->degree;
        $url=$request->url;

        if($degree<=0 || $degree>=360) return return_json_err(0,'旋转角度范围必须是0~360之间');
        $url=preg_replace('#\?.*#','',$url);
        if(strstr($url,M_PIC)){
            $ossnum=1;
            $ossfilename=str_replace(M_PIC,'',$url);
        }
        elseif(strstr($url,M_PIC_USER)){
            $ossnum=2;
            $ossfilename=str_replace(M_PIC_USER,'',$url);
        }
        else return return_json_err(0,'网址不支持');
        $oss = new OssController($ossnum);
        $newurl=$url.'?x-oss-process=image/rotate,'.$degree;
        $s=file_get_contents($newurl);
        if(strlen($s)<100) return return_json_err(0,'获取原始内容失败');
        $oss->save($ossfilename,$s);
        #\lib\cdn::instance()->refresh($url);
        exit(json_encode(['status'=>1]));
    }

    public function update_answer(Request $request){//修改并保存答案
        $all_img=$request->all_img;
        $all_answer_id=$request->all_answer_id;
        foreach($all_img as  $k=>$v){
            WorkbookAnswerRds::where(['id'=>$all_answer_id[$k]])
            ->update([
                'text'=>$k+1,
                'textname'=>'第'.($k+1).'页',
                'answer'=>$v,
                "md5answer"=>md5($v),
                "addtime"=>date('Y-m-d H:i:s',time())
            ]);
        }
        exit(\GuzzleHttp\json_encode(['status'=>1]));
    }

    public function update_bookstatus(Request $request){ //标记为无法处理
        $bookid=$request->bookid;
        $re=AWorkbook1010::where(['id'=>$bookid])->update(['status'=>15]);
        exit(\GuzzleHttp\json_encode(['status'=>$re]));
    }

    public function cancel_pass(Request $request){
        $id=$request->id;
        $bookid=$request->bookid;
        $up_uid=$request->up_uid;
        $type=$request->type;
        AWorkbook1010::where(['id'=>$bookid])->update(['status'=>14]);
        PreMWorkbookAnswerUser::where(['id'=>$id])->update(['status'=>3]);
        PreMAnswerUserAward::where(['bookid'=>$bookid,'uid'=>$up_uid])->update(['status'=>3]);
        if($type=='addblack') PreMAnswerUserBlacklist::create(['uid'=>$up_uid]);
        exit(\GuzzleHttp\json_encode(['status'=>1]));
    }

}
