<?php

namespace App\Http\Controllers\HotTongji;

use App\BookVersionType;
use App\MyModel\ATongjiOneday;
use App\MyModel\ATongjiStayArea;
use App\MyModel\ATongjiStaySection;
use App\OnlineModel\ATongjiHotbook29;
use App\OnlineModel\AWorkbook1010;
use App\Sort;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ATongJiHotBookController extends Controller
{
    //1热度管理列表
    public function hotlist(Request $request){
        $data=[];
        $where['all']=0;
        $start=0;
        $end=0;
            if(!($request->grade_id=='-5'||$request->grade_id==null)){
                $where['grade_id']=$request->grade_id;
            }
            if(!($request->subject_id=='-5'||$request->subject_id==null)){
                $where['subject_id']=$request->subject_id;
            }
            if(!($request->volumes_id=='-5'||$request->volumes_id==null)){
                $where['volumes_id']=$request->volumes_id;
            }
            if(!($request->version_id=='-5'||$request->version_id==null)){
                $where['version_id']=$request->version_id;
            }
            if(!($request->sort_id=='-5'||$request->sort_id==null)){
                $where['sort']=$request->sort_id;
            }
            $start=str_replace('_','/',request()->start);
            $end=str_replace('_','/',request()->end);

        $data['de_grade']=($request->grade_id==null)?"-5":$request->grade_id;
        $data['de_subject']=($request->subject_id==null)?"-5":$request->subject_id;
        $data['de_volumes']=($request->volumes_id==null)?"-5":$request->volumes_id;
        $data['de_version']=($request->version_id==null)?"-5":$request->version_id;
        $data['de_the_sort']=($request->sort_id==null)?"-5":$request->sort_id;
        
        $a_workbook_1010=new AWorkbook1010();
        $info=$a_workbook_1010->getHotBook($where,$start,$end); //列表数据
//        dd($info);
        $order=request()->order==null?'sum_stay':request()->order;
        $type=request()->type==null?'desc':request()->type;
        //查询最新即时数据
        $info=$a_workbook_1010::where($where)
            ->orderBy($order,$type)
            ->select('id','bookname','onlyid','isbn','sum_collect_count','sum_searchnum','sum_sharenum','sum_bad_evaluate','sum_good_evaluate','sum_stay','sum_correct')
            ->paginate(30);
        $barcodeGenerator=new BarcodeGeneratorHTML();
//        dd($info);
        $data['data']=$info;
        $attr=$this->getAllAttr();//获取select2的全部属性
        $data['attr']=$attr;
        $data['start']=str_replace('/','_',($start?$start:date('Y-m-d',time())));
        $data['end']=str_replace('/','_',$end?$end:date('Y-m-d',time()));
        return view('hot_tongji.hot_tongji',['data'=>$data]);
    }


    //2停留
    public function stophere()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $isbn=request()->isbn;
        $data['isbn']=$isbn;//isbn赋值
        $start=str_replace('_','/',request()->start);
        $end=str_replace('_','/',request()->end);
        $data['start']=$start;//起点时间赋值
        $data['end']=$end;//终点时间赋值
        $num_date=$this->num_date($isbn,$start,$end);//查询时间人数数据
        $num_area=$this->num_area($isbn,$start,$end);//查询地区人数数据
        $num_section=$this->num_section($isbn,$start,$end);//查询章节人数数据
        $num_collect=$this->num_collect($isbn,$start,$end);//查询收藏人数
        $data['num_collect']=$num_collect;
        $data['num_date']=$num_date;
        $data['num_area']=$num_area;
        $data['num_section']=$num_section;
//        dd($data);
        return view('hot_tongji.stophere',['data'=>$data]);
    }

    //3收藏
    public function hotcollect()
    {
        $list=new AWorkbook1010();
        $data=$list::take(10)->select('id','newname','cover','onlyid')->get();
        return view('hot_tongji.hotcollect',['data'=>$data]);
    }

    //4搜索
    public function hotsearch()
    {
        $list=new AWorkbook1010();
        $data=$list::take(10)->select('id','newname','cover','onlyid')->get();
        return view('hot_tongji.hotsearch',['data'=>$data]);
    }

    //5分享
    public function hotshare()
    {
        $list=new AWorkbook1010();
        $data=$list::take(10)->select('id','newname','cover','onlyid')->get();
        return view('hot_tongji.hotshare',['data'=>$data]);
    }


    //6评价
    public function hotevaluate()
    {
        $list=new AWorkbook1010();
        $data=$list::take(10)->select('id','newname','cover','onlyid')->get();
        return view('hot_tongji.hotevaluate',['data'=>$data]);
    }


    //7纠错
    public function hotcorrect()
    {
        $list=new AWorkbook1010();
        $data=$list::take(10)->select('id','newname','cover','onlyid')->get();
        return view('hot_tongji.hotcorrect',['data'=>$data]);
    }
    
    protected function getAllAttr()
    {
        $data=[];
        $grade=[];
        foreach(config('workbook.grade') as $a=>$b){
            if(!($a=='-1'||$a==0)){
                $grade[]=['id'=>$a,'text'=>$b];
            }

        }
        $data['grade']=json_encode($grade);

        $subject=[];
        foreach(config('workbook.subject_1010') as $c=>$d){
            if($c!=0){
                $subject[]=['id'=>$c,'text'=>$d];
            }

        }
        $data['subject']=json_encode($subject);

        $volumes=[];
        foreach(config('workbook.volumes') as $e=>$f){
            if(!($e=='-1'||$e==0)){
                $volumes[]=['id'=>$e,'text'=>$f];
            }

        }
        $data['volumes']=json_encode($volumes);

        $version=[];
        $all_version = Cache::rememberForever('all_version_now',function (){
            return BookVersionType::all(['id','name']);
        });
        foreach($all_version as $g=>$h){
            if($h['name']!='未选择'){
                $version[]=['id'=>$h['id'],'text'=>$h['name']];
            }

        }
//        $version[]=['id'=>'-5','text'=>'全部版本'];
        $data['version']=json_encode($version);

        $sort=[];
        $all_sort = Cache::rememberForever('all_sort_now',function (){
            return Sort::all(['id','name']);
        });
        foreach($all_sort as $i=>$j){
            if(!($j['name']=='nosort')){
                $sort[]=['id'=>$j['id'],'text'=>$j['name']];
            }

        }
//        $sort[]=['id'=>'-5','text'=>'全部系列'];
        $data['sort']=json_encode($sort);
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $data['sort'] = str_replace($escapers, $replacements, $data['sort']);
        return $data;
    }

    //时间人数列表图数据
    protected function num_date($isbn,$start,$end){
        $a_tongji_stay_area=new ATongjiStayArea();
        $data=$a_tongji_stay_area::where('isbn',$isbn)
            ->where('date','>=',$start)
            ->where('date','<=',$end)
            ->get();
        $arr=[];
        foreach($data as $k=>$v){
            $arr[$v->date][]=$v->num;
        }
        $array=[];
        foreach($arr as $a=>$b){
            $num=0;
            foreach($b as $j=>$k){
                $num+=$k;
            }
            $array[$a]=$num;
        }
        $arrdate=[];$arrnum=[];
        foreach($array as $c=>$d){
            $arrdate[]=$c;
            $arrnum[]=$d;
        }
        return $num_date=['arrdate'=>$arrdate,'arrnum'=>$arrnum];
    }

    //地区人数列表图数据
    protected function num_area($isbn,$start,$end){
        $a_tongji_stay_area=new ATongjiStayArea();
        $data=$a_tongji_stay_area::where('isbn',$isbn)
            ->where('date','>=',$start)
            ->where('date','<=',$end)
            ->get();
        $arr=[];
        foreach($data as $k=>$v){
            $arr[config('workbook.area')[$v->area]][]=$v->num;
        }
        $array=[];
        foreach($arr as $a=>$b){
            $num=0;
            foreach($b as $j=>$k){
                $num+=$k;
            }
            $array[$a]=$num;
        }
        $arrarea=[];$arrnum=[];
        foreach($array as $c=>$d){
            $arrarea[]=$c;
            $arrnum[]=$d;
        }
        return $num_date=['arrarea'=>$arrarea,'arrnum'=>$arrnum];
    }

    //章节人数列表图数据
    protected function num_section($isbn,$start,$end){
        $a_tongji_stay_section=new ATongjiStaySection();
        $data=$a_tongji_stay_section::from('a_tongji_stay_section as t1')
            ->join('a_thread_chapter as t2','t1.section','=','t2.id')
            ->where('t1.isbn',$isbn)
            ->where('t1.date','>=',$start)
            ->where('t1.date','<=',$end)
            ->select('t1.id','t1.isbn','t1.date','t1.section','t1.num','t2.name')
            ->get();
        $arr=[];
        foreach($data as $k=>$v){
            $arr[$v->name][]=$v->num;
        }
        $array=[];
        foreach($arr as $a=>$b){
            $num=0;
            foreach($b as $j=>$k){
                $num+=$k;
            }
            $array[$a]=$num;
        }
        $arrsection=[];$arrnum=[];
        foreach($array as $c=>$d){
            $arrsection[]=$c;
            $arrnum[]=$d;
        }
        return $num_section=['arrsection'=>$arrsection,'arrnum'=>$arrnum];
    }

    //收藏人数
    public function num_collect($isbn,$start,$end)
    {
        $a_tongji_oneday=new ATongjiOneday();
        $data=$a_tongji_oneday::where('isbn',$isbn)
            ->where('date','>=',$start)
            ->where('date','<=',$end)
            ->get();
        $collect_count=[];$sharenum=[];$searchnum=[];$good_evaluate=[];$bad_evaluate=[];
        foreach($data as $k=>$v){
            $collect_count[]=$v['collect_count'];
            $sharenum[]=$v['sharenum'];
            $searchnum[]=$v['searchnum'];
            $good_evaluate[]=$v['good_evaluate'];
            $bad_evaluate[]=$v['bad_evaluate'];
        }
        $array=['collect_count'=>$collect_count,'sharenum'=>$sharenum,'searchnum'=>$searchnum,'good_evaluate'=>$good_evaluate,'bad_evaluate'=>$bad_evaluate];
        return $array;
    }
}
