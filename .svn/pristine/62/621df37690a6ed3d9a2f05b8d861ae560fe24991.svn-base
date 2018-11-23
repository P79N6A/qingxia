<?php

namespace App\Http\Controllers\Ajax;

use App\AWorkbookNew;
use App\Http\Controllers\Chart\TaobaoBookController;
use App\LocalModel\AWorkbook1010Test;
use App\LocalModel\NewBuy\New1010;
use App\LocalModel\NewBuy\NewBoughtParams;
use App\LocalModel\NewBuy\NewBoughtRecord;
use App\LocalModel\NewBuy\NewBoughtReturn;
use App\LocalModel\NewBuy\NewGoods;
use App\LocalModel\NewBuy\NewGoodsTrue;
use App\LocalModel\NewBuy\NewOnly;
use App\LocalModel\NewBuy\NewOnlyDelete;
use App\LocalModel\NewBuy\NewSort;
use App\LocalModel\NewBuy\NewSortSearchName;
use Auth;
use Cache;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AjaxBookListController extends Controller
{
    public function mark(Request $request)
    {
        $check=$request->checkData;
        foreach($check as $k=>$v){
            $only_id = $v;
            $now_buy_status = NewOnly::findOrFail($only_id);

            if($now_buy_status->need_buy)
            {
                $now_buy_status->need_buy = 0;
                NewBoughtRecord::where([['only_id',$only_id],['status',0]])->delete();
                $now_buy_status->save();
            }else{
                $now_buy_status->need_buy = 1;
                $record['only_id'] = $only_id;
                $record['uid'] = Auth::id();
                $record['sort'] = $now_buy_status->sort;
                $record['grade_id'] = $now_buy_status->grade_id;
                $record['subject_id'] = $now_buy_status->subject_id;
                $record['volumes_id'] = $now_buy_status->volumes_id;
                $record['version_year'] = cache('now_bought_params')->where('uid',auth()->id())->first()->version_year;
                NewBoughtRecord::create($record);
                $now_buy_status->save();
            }
        }

        return return_json();
    }
}
