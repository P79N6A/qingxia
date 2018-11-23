<?php

namespace App\Http\Controllers\Mytest\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ChangeController extends Controller
{
    public function change(Request $request)
    {
        $data=request()->all();
        $id=$data['id'];
        unset($data['id']);
        $where=$data;
        //更新数据
        $result=DB::connection('mysql_local')->table('local_img_upload_logs')->where(['id'=>$id])->update($where);
        //查询新的from_id
//        $res=DB::connection('mysql_local')->table('local_img_upload_logs')
//            ->join('a_workbook_1010_new',[
//                'local_img_upload_logs.preg_sort'=>'a_workbook_1010_new.sort',
//                'local_img_upload_logs.preg_grade'=>'a_workbook_1010_new.grade_id',
//                'local_img_upload_logs.preg_subject'=>'a_workbook_1010_new.subject_id',
//                'local_img_upload_logs.preg_version'=>'a_workbook_1010_new.version_id',
//                'local_img_upload_logs.preg_volume'=>'a_workbook_1010_new.volumes_id',
//            ])
//            ->where($where)
//            ->where(['local_img_upload_logs.done'=>0])
//            ->get();
//        dd($where);

                $infos=DB::connection('mysql_local')->table('a_workbook_new')->where(['sort'=>$where['preg_sort'],'grade_id'=>$where['preg_grade'],'subject_id'=>$where['preg_subject'],'version_id'=>$where['preg_version'],'volumes_id'=>$where['preg_volume']])->get();
//                dd($infos);


        if($infos->isEmpty()){
            $code=json_encode(['status'=>'failed','data'=>'']);
        }else{
            foreach($infos as $a=>$b){
                $collect[]=['bookname'=>$b->bookname,'from_id'=>$b->id,'path'=>$b->cover];
            }
            $code=json_encode(['status'=>'success','data'=>$collect]);
        }
        return $code;
    }


}
