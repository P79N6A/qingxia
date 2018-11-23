<?php

namespace App\Http\Controllers\AuditContent;


use App\PreMWorkbookContent;
use App\AWorkbook1010;
use App\Http\Controllers\OssController;
use App\PreMWorkbookAnswerUser;
use App\PreMAnswerUserBlacklist;

use App\isbnAll;
use App\PreMAnswerUserAward;
use Auth;
use App\WorkbookAnswerRds;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use OSS\OssClient;



class UserContentController extends Controller
{


    public function booklist(Request $request,$type=0)
    {
        $where=[];
        if($type==0){
            $where['b.content_status']=1;
        }elseif($type==1){
            $where['b.content_status']=2;
            $where['c.status']=1;
        }
        $data['list']=PreMWorkbookContent::from('pre_m_workbook_content as c')
            ->leftJoin('a_workbook_1010 as b','c.book_id','b.id')
            ->where($where)
            ->select('c.book_id','b.bookname','c.addtime','b.isbn','c.op_uid')
            ->with('has_user:id,name')
            ->groupBy('c.book_id')
            ->paginate(20);
        //dd($data['list']);
        $data['type']=$type;
        $data['page']= $request->page==null?1:$request->page;
        return view('user_content.booklist',compact('data'));
    }



    public function auditing($bookid,$type=0,$page=1){
        $data['bookinfo']=AWorkbook1010::where(['id'=>$bookid])->select('bookname','isbn','cover')->first();

        $data['usercontent']=PreMWorkbookContent::where(['book_id'=>$bookid,'status'=>$type])
            ->orwhere(['book_id'=>$bookid,'status'=>2])
            ->select('id','content_img','up_uid','addtime','book_id','status')
            ->with('hasUserInfo:uid,nickname,qq')
            ->orderBy('addtime','desc')
            ->paginate(20);
        //dd($data);
        $data['type']=$type;
        $data['page']=$page;
        $data['bookid']=$bookid;
        return view('user_content.auditing',compact('data'));
    }

    public function updateStatus(Request $request){ //更改状态（不通过，不完整）
        $id=intval($request->id);
        $status=intval($request->status);
        $re=PreMWorkbookContent::where(['id'=>$id])->update(['status'=>$status,'op_uid'=>auth::id()]);
        exit(\GuzzleHttp\json_encode(['status'=>$re]));
    }

    public function contentpass(Request $request){ //内容通过
        $id=intval($request->id);
        $bookid=intval($request->bookid);
        $uid=intval($request->uid);
        $all_img=$request->all_img;
        $img_str='';
        foreach ($all_img as $k=>$v) {
            $img_str.=$v.'|';
        }
        AWorkbook1010::where(['id'=>$bookid])->update(['content_status'=>2]);
        PreMAnswerUserAward::create(['bookid'=>$bookid,'uid'=>$uid,'award'=>5]);
        PreMWorkbookContent::where(['id'=>$id])->update(['content_img'=>$img_str,'status'=>1,'op_uid'=>auth::id()]);
        PreMWorkbookAnswerUser::where(['book_id'=>$bookid])->where('id','!=',$id)
            ->update(['status'=>4,'op_uid'=>auth::id()]);
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

    public function content_cancel(Request $request){ //撤销通过
        $id=$request->id;
        $bookid=$request->bookid;
        $up_uid=$request->up_uid;
        AWorkbook1010::where(['id'=>$bookid])->update(['content_status'=>1]);
        PreMWorkbookContent::where(['id'=>$id])->update(['status'=>3]);
        PreMAnswerUserAward::where(['bookid'=>$bookid,'uid'=>$up_uid])->update(['status'=>3]);
        exit(\GuzzleHttp\json_encode(['status'=>1]));
    }

}
