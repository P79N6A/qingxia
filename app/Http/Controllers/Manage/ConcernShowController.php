<?php

namespace App\Http\Controllers\Manage;

use App\ConcernCity;
use App\ConcernProvince;
use App\ConcernSchool;
use App\ConcernUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Concern;
use Illuminate\Support\Facades\DB;

class ConcernShowController extends Controller
{
    public function index(Request $request){
        $data['province'] = ConcernProvince::all(['id','name'])->toJson();
        //$user = $request->user();
        //dd(Concern::where('province_id',1)->count(DB::raw('distinct book_id')));


       //return view('manage.concern_show',compact(['user','data']));
        //$all_province = ConcernProvince::all(['id','name']);
        //$a_city = ConcernCity::where('parent_id',1)->select(['id','name'])->get();
        $a_school = ConcernSchool::where('parent_id',33)->select(['id','name'])->get();
        foreach ($a_school as $value){
            $data['user_count'][] = ConcernUser::where('school_id',$value->id)->count();
        }
        dd($data['user_count']);
        dd($a_school);
        //dd($a_city);
        foreach (Concern::where('city_id', 33)->cursor() as $concern) {
            var_dump('q');
        }
        dd('qwe');
    }
}
