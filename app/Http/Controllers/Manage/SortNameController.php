<?php

namespace App\Http\Controllers\Manage;

use App\AWorkbookOnly;
use App\BookVersion;
use App\BookVersionType;
use App\Sort;
use App\Volume;
use App\Workbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SortNameController extends Controller
{
    public function index(Request $request){
        //$user = $request->user();
//        $data['all_sort'] = Sort::select(['sort.name',
//            DB::raw('group_concat(distinct sort.id) as id_now'),DB::raw('group_concat(distinct sort_name separator "|") as sort_name'),DB::raw('count(*) as total')])
//            ->join('a_workbook_1010_only',function($join){
//                $join->on('a_workbook_1010_only.sort','=','sort.id');
//                //$join->on('a_workbook_1010.sort_name','<>','sort.name');
//            })
//            ->groupBy(['sort.name','sort.order_now'])
//            ->orderBy('sort.order_now', 'desc')
//            ->paginate('15');
        //SELECT sort,count(*) FROM `a_workbook_1010_only` group by sort order by count(*) desc
        $data['all_sort'] = AWorkbookOnly::select(['sort.name','a_workbook_1010_only.sort as id_now',DB::raw('group_concat(distinct a_workbook_1010_only.sort_name separator "|") as sort_name'),DB::raw('count(*) as total')])
            ->join('sort',function($join) {
                $join->on('sort.id','=','a_workbook_1010_only.sort');
            })
            ->groupBy(['sort.name','a_workbook_1010_only.sort'])->orderBy('total','DESC')->paginate('15');
        foreach ($data['all_sort'] as $key=>$value){
            $all_sort_name = explode('|',$value->sort_name);
            $a = Workbook::select(['sort_name',DB::raw('count(*) as total')])
                ->where('sort',$value->id_now)
                //->where('sort_name','!=','')
                ->orderBy('total','desc')
                ->groupBy('sort_name')->get();


            foreach ($all_sort_name as $sort_now){
                foreach ($a as $sort_a){
                    if($sort_a->sort_name==$sort_now){
                        $data['final_sort_array'][$value->id_now][] = array('sort_id'=>$value->id_now,'count'=>$sort_a->total,'name'=>$sort_now);
                    }
                }
            }
        }

        if(isset($data['final_sort_array'])){
            foreach ($data['final_sort_array'] as $key=>$sort_array){
                $data['final_sort_array'][$key] = collect($sort_array)->sortByDesc('count');
            }
        }

        return view('manage.sort_name',compact('data'));
    }

    public function detail(Request $request,$id,$name,$press_id='',$all=0){
        //$user = $request->user();
		$data['press'] = BookVersion::all('id','name');
		$data['id_now'] = $name;
		$data['id'] = $id;
		$data['press_now'] = Workbook::where('sort',$id)->where('sort_name',$name)
            ->join('sort', 'a_workbook_1010.sort', '=', 'sort.id')
            ->join('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->join('book_version_type', DB::raw('CAST(a_workbook_1010.version_id as UNSIGNED)'), '=', 'book_version_type.id')
            ->select(['a_workbook_1010.press_id','a_workbook_1010.sort_name', 'book_version.name as press_name'])
            ->orderBy('a_workbook_1010.booksort','asc')
            ->orderBy('a_workbook_1010.version_year','desc')
            ->get();
        if(count($data['press_now'])>0){
            //$data['all_press_now'] = collect($data['press_now'])->groupBy('press_id')->sort()->reverse();
            $all_press_now = collect($data['press_now'])->groupBy('press_id')->sort();
            if(count($all_press_now)>0){
                foreach ($all_press_now as $key=>$all_press){
                    $data['all_data'][$key]['data'] = $all_press;
                    $data['all_data'][$key]['sort_name'] = $all_press->groupBy('sort_name')->sort()->reverse()->toArray();
                    $data['all_data'][$key]['all_sort_name'] = $all_press->groupBy('sort_name')->keys();
                    $data['all_data'][$key]['press_name'] =$all_press[0]->press_name;
                    $data['all_data'][$key]['press_id'] =$all_press[0]->press_id;
                }
                if($press_id===''){$press_id = $all_press_now->keys()[0];}

            }
            $data['all_sort_name'] = collect();
            ksort($data['all_data']);
            foreach ($data['all_data'] as $value){
                $data['all_sort_name']->push($value['sort_name']);

            }
            $data['all_sort_name'] = $data['all_sort_name']->collapse()->keys();
        }

        if($all==1){
            $press_id = '';
        }


        $data['press_now'] = $press_id;
        $data['sort_name_now'] = $name;
		
		
        $data['book_now'] = Workbook::where('sort',$id)
            ->where('sort_name',$name)
			->where(function($query) use($press_id){
					if($press_id!==''){
						$query->where('press_id',$press_id);
					}
				})
            ->join('sort',function($join){
                $join->on('a_workbook_1010.sort','=','sort.id');
//                $join->on('a_workbook_1010.sort_name','<>','sort.name');
            })
            ->join('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->join('book_version_type', DB::raw('CAST(a_workbook_1010.version_id as UNSIGNED)'), '=', 'book_version_type.id')
            ->select(['a_workbook_1010.*', 'sort.note as sort_note','sort.id as sort_id','sort.name as sort_name_real', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('a_workbook_1010.press_id','asc')
            ->orderBy('a_workbook_1010.booksort','asc')
            ->orderBy('a_workbook_1010.version_year','desc')
            ->get();


//        if($all==1){
//            $data['book_now'] = collect($data['book_now']->groupBy('press_id')->sort()->sortBy('booksort')
//                ->sortByDesc('version_year'))->collapse();
//        }

        //dd(collect($data['book_now'])->groupBy('press_id')->sort()->sortBy('booksort')->sortByDesc('version_year'));
            //->paginate(30);

     
        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['code', 'volumes']);
        $data['name'] = $name;

        foreach ($data['all_version'] as $key=>$value){
            $version_array[$key]['id'] = strlen($value->id)>1?$value->id:'0'.$value->id;
            $version_array[$key]['text'] = $value->name;
        }
        foreach (config('workbook.grade') as $key=> $value){
            $grade_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $grade_array[$key-1]['text'] = $value;
        }
        foreach (config('workbook.subject_1010') as $key=> $value){
            $subject_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $subject_array[$key-1]['text'] = $value;
        }
        foreach ($data['all_volumes'] as $key=>$value){
            $volume_array[$key]['id'] = $value->code;
            $volume_array[$key]['text'] = $value->volumes;
        }
        $data['version_select'] = json_encode($version_array);
        $data['subject_select'] = json_encode($subject_array);
        $data['grade_select'] = json_encode($grade_array);
        $data['volume_select'] = json_encode($volume_array);
        $data['current_route'] = 'sort_name_detail';
        return view('manage.sort_name_detail',compact('data'));
    }

    public function all(Request $request,$id,$press_id='',$get_sort_name='',$all=0){
        //$user = $request->user();
		$data['press'] = BookVersion::all('id','name');
		$data['id_now'] = $id;
        $data['id'] = $id;
		$data['press_now'] = Workbook::where('sort',$id)
            ->join('sort',function($join){
                $join->on('a_workbook_1010.sort','=','sort.id');
//                $join->on('a_workbook_1010.sort_name','<>','sort.name');
            })
            ->join('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->join('book_version_type', DB::raw('CAST(a_workbook_1010.version_id as UNSIGNED)'), '=', 'book_version_type.id')
            ->select(['a_workbook_1010.press_id','a_workbook_1010.sort_name', 'book_version.name as press_name'])
            ->orderBy('a_workbook_1010.booksort','asc')
            ->orderBy('a_workbook_1010.version_year','desc')
            ->get();

		if(count($data['press_now'])>0){
			//$data['all_press_now'] = collect($data['press_now'])->groupBy('press_id')->sort()->reverse();
			$all_press_now = collect($data['press_now'])->groupBy('press_id')->sort();
			if(count($all_press_now)>0){
                foreach ($all_press_now as $key=>$all_press){
                    $data['all_data'][$key]['data'] = $all_press;
                    $data['all_data'][$key]['sort_name'] = $all_press->groupBy('sort_name')->sort()->reverse()->toArray();
                    $data['all_data'][$key]['all_sort_name'] = $all_press->groupBy('sort_name')->keys();
                    $data['all_data'][$key]['press_name'] =$all_press[0]->press_name;
                    $data['all_data'][$key]['press_id'] =$all_press[0]->press_id;
                }
                if($press_id===''){$press_id = $all_press_now->keys()[0];}
                if($get_sort_name===''){$get_sort_name = $all_press_now->first()[0]->sort_name;}
			}
			$data['all_sort_name'] = collect();
			ksort($data['all_data']);

            foreach ($data['all_data'] as $value){
                $data['all_sort_name']->push($value['sort_name']);

            }
            $data['all_sort_name'] = $data['all_sort_name']->collapse()->keys();
		}

        if($all==1){
            $press_id = '';
            $get_sort_name = '';
        }

		$data['press_now'] = $press_id;
		$data['sort_name_now'] = $get_sort_name;
        $data['book_now'] = Workbook::where('sort',$id)
			->where(function($query) use($press_id,$get_sort_name){
				if($press_id!==''){
					$query->where('press_id',$press_id);
				}
                if($get_sort_name!==''){
                    $query->where('sort_name',$get_sort_name);
                }
			})
            ->join('sort',function($join){
                $join->on('a_workbook_1010.sort','=','sort.id');
                $join->on('a_workbook_1010.sort_name','<>','sort.name');
            })
            ->join('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->join('book_version_type', DB::raw('CAST(a_workbook_1010.version_id as UNSIGNED)'), '=', 'book_version_type.id')
            ->select(['a_workbook_1010.*', 'sort.note as sort_note', 'sort.id as sort_id','sort.name as sort_name_real','book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('a_workbook_1010.press_id','asc')
            ->orderBy('a_workbook_1010.booksort','asc')
            ->orderBy('a_workbook_1010.version_year','desc')
            ->paginate(200);


        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['code', 'volumes']);

        foreach ($data['all_version'] as $key=>$value){
            $version_array[$key]['id'] = strlen($value->id)>1?$value->id:'0'.$value->id;
            $version_array[$key]['text'] = $value->name;
        }
        foreach (config('workbook.grade') as $key=> $value){
            $grade_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $grade_array[$key-1]['text'] = $value;
        }
        foreach (config('workbook.subject_1010') as $key=> $value){
            $subject_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $subject_array[$key-1]['text'] = $value;
        }
        foreach ($data['all_volumes'] as $key=>$value){
            $volume_array[$key]['id'] = $value->code;
            $volume_array[$key]['text'] = $value->volumes;
        }
        $data['version_select'] = json_encode($version_array);
        $data['subject_select'] = json_encode($subject_array);
        $data['grade_select'] = json_encode($grade_array);
        $data['volume_select'] = json_encode($volume_array);
        $data['has_paginate'] = 1;
        $data['current_route'] = 'sort_name_all';
        return view('manage.sort_name_detail',compact('data'));
    }

    public function index_v2(Request $request){
        //$user = $request->user();
        $data['book_now'] = Workbook::where('status',1)
            ->where(function ($query){
                $query->where('sort','<',0);
                $query->orwhere('sort_name','like','%版%');
            })
            ->join('sort',function($join){
                $join->on(DB::raw('CAST(a_workbook_1010.sort as UNSIGNED)'),'=','sort.id');
                $join->on('a_workbook_1010.sort_name','<>','sort.name');
            })
//            ->where('sort','<',0)->orwhere('sort_name','like','%版%')
            ->join('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->join('book_version_type', DB::raw('CAST(a_workbook_1010.version_id as UNSIGNED)'), '=', 'book_version_type.id')
            ->select(['a_workbook_1010.*','sort.note as sort_note', 'sort.id as sort_id','sort.name as sort_name_real', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('a_workbook_1010.sort_name','asc')
            ->orderBy('a_workbook_1010.press_id','asc')
            ->orderBy('a_workbook_1010.booksort','asc')
            ->orderBy('a_workbook_1010.version_year','desc')
            ->paginate(20);
        $data['all_version'] = BookVersionType::all(['id', 'name']);
        $data['all_volumes'] = Volume::all(['code', 'volumes']);

        foreach ($data['all_version'] as $key=>$value){
            $version_array[$key]['id'] = strlen($value->id)>1?$value->id:'0'.$value->id;
            $version_array[$key]['text'] = $value->name;
        }
        foreach (config('workbook.grade') as $key=> $value){
            $grade_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $grade_array[$key-1]['text'] = $value;
        }
        foreach (config('workbook.subject_1010') as $key=> $value){
            $subject_array[$key-1]['id'] = strlen($key)>1?$key:'0'.$key;
            $subject_array[$key-1]['text'] = $value;
        }
        foreach ($data['all_volumes'] as $key=>$value){
            $volume_array[$key]['id'] = $value->code;
            $volume_array[$key]['text'] = $value->volumes;
        }
        $data['press'] = BookVersion::all('id','name');
        $data['version_select'] = json_encode($version_array);
        $data['subject_select'] = json_encode($subject_array);
        $data['grade_select'] = json_encode($grade_array);
        $data['volume_select'] = json_encode($volume_array);
        $data['has_paginate'] = 1;
        return view('manage.sort_name_detail_v2',compact('data'));
    }
}
