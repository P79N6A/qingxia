<?php

namespace App\Http\Controllers\Cover;
require_once app_path('Http/Controllers/Libs/baiduocr/AipOcr.php');
use AipOcr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LocalModel\CoverIsbn;
use App\AWorkbookNew;
use App\localModel\NewBuy\NewBoughtRecord;
use Intervention\Image\ImageManagerStatic as Image;

class CoverController extends Controller
{
    public function CheckCover(){
        $data=CoverIsbn::where('status','=',1)->orwhere('status','=',2)->orderBy('status','desc')->paginate(20);
        foreach($data as $k=>$v){
            $bookid_arr=explode(",",$v->bookid);
            $re=AWorkbookNew::whereIn('id',$bookid_arr)->select('id','bookname','sort')->with('has_sort:id,name')->get();;
            if($v->status==1){
                $data[$k]['cover']='//192.168.0.117/book4_new/'.$re[0]->has_sort->id.'_'.$re[0]->has_sort->name.'/'.$re[0]->bookname.'_'.$re[0]->id.'/cover/'.basename($v->cover);
                $data[$k]['cip']='//192.168.0.117/book4_new/'.$re[0]->has_sort->id.'_'.$re[0]->has_sort->name.'/'.$re[0]->bookname.'_'.$re[0]->id.'/cover/'.basename($v->cip);
            }elseif($v->status==2){
                $data[$k]['cover']=str_replace('//QINGXIA23/','//192.168.0.117',$v->cover);
                $data[$k]['cip']=str_replace('//QINGXIA23/','//192.168.0.117',$v->cip);
            }
            $data[$k]['books']=$re;
        }
        //dd($data);
        return view("cover.check_cover",compact('data'));
    }

    public function CopyCover(Request $request){
        $coverid=intval($request->coverid);
        $bookid=intval($request->bookid);
        $re=CoverIsbn::where(['id'=>$coverid])->first();
        $re2=AWorkbookNew::where(['id'=>$bookid])->select('id','bookname','sort')->with('has_sort:id,name')->first();
        $dir_path=iconv('utf-8','gbk','//QINGXIA23/book4_new/'.$re2->has_sort->id.'_'.$re2->has_sort->name.'/'.$re2->bookname.'_'.$re2->id.'/cover');
        $status=0;
        if(is_dir($dir_path)){
            if(copy($re->cover, $dir_path.'/'.basename($re->cover)) && copy($re->cip, $dir_path.'/'.basename($re->cip))){
                unlink($re->cover);
                unlink($re->cip);
                $status=CoverIsbn::where(['id'=>$coverid])->update(['status'=>1,'bookid'=>$bookid]);
            }
        }
        return response()->json(['status'=>$status]);
    }

    public function is_check(Request $request){
        $id_arr=$request->id_arr;
        CoverIsbn::whereIn('id',$id_arr)->update(['status'=>4]);
    }


    public function save_pic(){ //文件夹的图片入库
        set_time_limit(0);
        $arr = \File::files('//QINGXIA23/WWW/bookcover2');
        $covers=[];
        foreach($arr as $k=>$pic){
            if(strstr(basename($pic),'.db')) continue;
            $filename=str_replace('.jpg','',basename($pic));
            $covers[$filename]=$pic;
        }
        ksort($covers);
        array_values($covers);

        foreach($covers as $k=>$pic){
            if(strstr('.db',basename($pic))) continue;
            if(intval($k)%2!=0){
                $re['cover']=$pic;
            }else {
                $re['cip'] = $pic;
                $re['md5cover'] = md5_file($re['cover']);
                if(CoverIsbn::where(['md5cover'=>$re['md5cover']])->count()){
                    continue;
                }
                CoverIsbn::create($re);
            }
        }
    }

    public function recognition(){ //图片识别
        $aipOcr = new AipOcr(config('workbook.APP_ID'), config('workbook.API_KEY'), config('workbook.SECRET_KEY'));
        $data=CoverIsbn::where(['isbn'=>0,'status'=>0])->whereNull('words')->first();
        if(!$data) exit(\GuzzleHttp\json_encode(['status'=>0]));
        $words_str='';
        $re=[];
        $re['isbn']=0;
        Image::make($data->cip)->resize(1200, null,function ($constraint) {
            $constraint->aspectRatio();
        })->save($data->cip);
        $result = $aipOcr->webImage(file_get_contents($data->cip));
        foreach($result['words_result'] as $v){
            if(strlen($v['words'])>=13){
                $v['words']=str_replace('—','-',$v['words']);
                if(preg_match('#978[-\d\s]{10,}#',$v['words'],$arr)){
                    $re['isbn']=str_replace(' ','',str_replace('-','',$arr[0]));
                    if(strlen($re['isbn'])==13) break;
                }
            }
            $words_str.= $v['words'];
        }
        if(!$re['isbn'] || strlen($re['isbn'])!=13){
            $re['isbn']=0;
            $re['words']=$words_str;
        }
        CoverIsbn::where(['id'=>$data->id])->update($re);
        exit(\GuzzleHttp\json_encode(['cip'=>$data->cip,'isbn'=>$re['isbn'],'status'=>1]));
    }

    public function copy_cover(){ //匹配并复制图片
        set_time_limit(0);
        $data=CoverIsbn::where(['status'=>0])->where('isbn','!=',0)->first();
        if(!$data) exit(\GuzzleHttp\json_encode(['status'=>0]));

        $re=NewBoughtRecord::from('a_book_bought_record as r')
            ->leftJoin('a_workbook_new as n','r.only_id','n.from_only_id')
            ->where(['r.isbn'=>$data->isbn])
            ->where(function ($query) {
            $query->where('r.status', '=', 1)
                ->orWhere('r.status', '=', 6);
        })->select('n.id','n.bookname','n.sort')->with('hasSort:id,name')->get();
        if(!$re->count()){
            CoverIsbn::where(['id'=>$data->id])->update(['status'=>3]);
            exit(\GuzzleHttp\json_encode(['cover'=>$data->cover,'bookname'=>'未找到对应书本','status'=>1]));
        }elseif(count($re)==1){
            $dir_path=iconv('utf-8','gbk','//QINGXIA23/book4_new/'.$re[0]->hasSort->id.'_'.$re[0]->hasSort->name.'/'.$re[0]->bookname.'_'.$re[0]->id.'/cover');
            if(is_dir($dir_path)){
                if(copy($data->cover, $dir_path.'/'.basename($data->cover)) && copy($data->cip, $dir_path.'/'.basename($data->cip))){
                    unlink($data->cover);
                    unlink($data->cip);
                    CoverIsbn::where(['id'=>$data->id])->update(['status'=>1,'bookid'=>$re[0]->id]);
                }else{
                    exit(\GuzzleHttp\json_encode(['msg'=>'复制失败','status'=>2]));
                }
            }else{
                exit(\GuzzleHttp\json_encode(['msg'=>'没有对应的文件夹','status'=>2]));
            }
            exit(\GuzzleHttp\json_encode(['cover'=>$data->cover,'bookname'=>$re[0]->bookname,'status'=>1]));
        }elseif(count($re)>1){
            $bookid_str='';
            foreach($re as $v){
                $bookid_str.=$v->id.',';
            }
            $bookid_str=rtrim($bookid_str,',');
            CoverIsbn::where(['id'=>$data->id])->update(['status'=>2,'bookid'=>$bookid_str]);
            exit(\GuzzleHttp\json_encode(['cover'=>$data->cover,'bookname'=>'有多本','status'=>1]));
        }

    }



}
