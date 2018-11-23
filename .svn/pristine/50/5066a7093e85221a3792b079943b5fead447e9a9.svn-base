<?php

namespace App\Http\Controllers\BookBuy;

use App\ATongjiBuy;
use App\AWorkbook1010;
use App\AWorkbook1010Zjb;
use App\AWorkbookNew;
use App\AWorkbookOnly;
use App\BaiduNewDaan;
use App\Book;
use App\BookVersion;
use App\BookVersionType;
use App\DataSortCollect;
use App\Sort;
use App\User;
use DB;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class NewController extends Controller
{
    public function index($type = 'jiajiao')
    {
        $uid = \Auth::id();

        $whereRaw = '1=1';
        if ($uid != 2) {
            $whereRaw = "update_uid=$uid";
        }

        $data['type'] = $type;


        $data['all_sort'] = ATongjiBuy::where([['jj', 1], ['book_id', 0], ['sort', '>', 0]])->whereRaw("$whereRaw")->select('id', 'sort', 'sort_name', 'collect_count', 'update_uid')->with('has_self_new_book:id,sort,newname,version_year,has_update,grade_id,subject_id,volumes_id,version_id,arrived_at,done')->with('has_self_new_book.has_main_book:id,collect_count,concern_num')->orderBy('collect_count', 'desc')->paginate(5);
        foreach ($data['all_sort'] as $key=>$value)
        {
            $data['all_sort'][$key]['wrong_num'] = AWorkbookNew::WHERE(['sort'=>$value->id,'done'=>2])->count();
        }

        return view('book_buy.new_index', compact('data'));
    }

    public function detail($sort='',$version='')
    {
        if($sort==''){
            die('未选择系列');
        }
        $data['sort_num'] = AWorkbookNew::where(['sort'=>$sort])->count();
        $data['jj_sort'] = ATongjiBuy::where(['jj' => 1, 'book_id' => 0, 'sort' => $sort])->first(['id', 'sort', 'sort_name', 'collect_count', 'book_id', 'update_uid']);


        $all_version_now = AWorkbookNew::where([['sort', $sort],['volumes_id', '=', 2], ['grade_id', '<', 10], ['grade_id', '>', 2], ['subject_id', '>=', 1], ['subject_id', '<=', 10]])->select(DB::raw('distinct version_id'))->orderBy('version_id','asc')->get();
        if(!cache('all_sort_now')->where('id',$sort)->first()){
            die('暂无该系列');
        }
        $buy['sort_name'] = cache('all_sort_now')->where('id',$sort)->first()->name;
        if(!$data['jj_sort'] || count($all_version_now)==0){
            if(!$data['jj_sort']){
                $buy['sort'] = $sort;
                $buy['update_uid'] = \Auth::id();
                ATongjiBuy::create($buy);
                $data['jj_sort'] = ATongjiBuy::where(['jj' => 1, 'book_id' => 0, 'sort' => $sort])->first(['id', 'sort', 'sort_name', 'collect_count', 'book_id', 'update_uid']);
            }
            if(count($all_version_now)==0){
                $new['bookname'] = $buy['sort_name'];
                $new['newname'] = $buy['sort_name'];
                $new['grade_id'] = 3;
                $new['subject_id'] = 3;
                $new['volumes_id'] = 2;
                $new['version_year'] = 2018;
                $new['sort'] = $sort;
                $new['grade_name'] = '';
                $new['subject_name'] = '';
                $new['volume_name'] = '';
                $new['version_name'] = '';
                $new['sort_name'] = '';
                $new['ssort_id'] = 0;
                AWorkbookNew::create($new);
            }
            redirect(route('new_book_buy_detail',[$sort]));
        }
        if(count($all_version_now)>0 && $version==''){
            $version = $all_version_now[0]->version_id;
        }else{
            $version = intval($version);
        }
        $data['nav_version_now'] = $all_version_now;
        $data['now_version_select'] = $version;

        $data['all_book_bought'] = AWorkbookNew::where([['id','>',1000000],['sort',$sort]])->count();
        $data['all_book_answers'] = AWorkbookNew::where([['id','>',1000000],['sort',$sort],['done',1]])->count();
        $data['all_book_uploaded'] = AWorkbook1010Zjb::where([['id','>',1000000],['sort',$sort]])->count();


        foreach ($all_version_now as $version_now){
            $data['version_book_bought'][$sort][$version_now->version_id] = AWorkbookNew::where([['id','>',1000000],['version_id',$version_now->version_id],['sort',$sort]])->count();
            $data['version_book_answers'][$sort][$version_now->version_id] = AWorkbookNew::where([['id','>',1000000],['version_id',$version_now->version_id],['sort',$sort],['done',1]])->count();
            $data['version_book_uploaded'][$sort][$version_now->version_id] = AWorkbook1010Zjb::where([['id','>',1000000],['version_id',$version_now->version_id],['sort',$sort]])->count();
        }
        $jj_sort_detail = AWorkbookNew::where([['sort', $sort],['volumes_id', '=', 2],['version_id',$version], ['grade_id', '<', 10], ['grade_id', '>', 2], ['subject_id', '>=', 1], ['subject_id', '<=', 10]])->select('id', 'subject_id', 'grade_id', 'version_id', 'volumes_id as volume_id', 'version_year', 'sort', 'bookname as sort_name', 'has_update', 'isbn', 'arrived_at','done')->with('has_main_book:id,collect_count,concern_num')->get();
        $data['all_version'] = cache('all_version_now');

        if(count($jj_sort_detail)==0){
            $jj_sort_detail = AWorkbookNew::where([['sort', $sort],['volumes_id', '=', 1],['version_id',$version], ['grade_id', '<', 10], ['grade_id', '>', 2], ['subject_id', '>=', 1], ['subject_id', '<=', 10]])->select('id', 'subject_id', 'grade_id', 'version_id', 'volumes_id as volume_id', 'version_year', 'sort', 'bookname as sort_name', 'has_update', 'isbn', 'arrived_at','done')->with('has_main_book:id,collect_count,concern_num')->get();
        }
        $data['jj_sort_detail'] = collect($jj_sort_detail)->groupBy('version_id')->transform(function ($item, $k) {
            return $item->groupBy('subject_id')->transform(function ($item1, $k1) {
                return $item1->groupBy('grade_id')->transform(function ($itme2, $k2) {
                    return $itme2->groupBy('version_year')->transform(function ($itme3, $k2) {
                        return $itme3->sortByDesc(function ($value, $key) {
                            return $key;
                        });
                    })->sortByDesc(function ($value, $key) {
                        return $key;
                    });
                })->sortBy(function ($value, $key) {
                    return $key;
                });
            })->sortBy(function ($value, $key) {
                return $key;
            });
        })->sortBy(function ($value, $key) {
            return $key;
        });

        $data['jj_sort_detail_grade'] = collect($jj_sort_detail)->groupBy('grade_id')->sortBy(function ($item, $key) {
            return $key;
        })->keys();
        $data['jj_sort_detail_subject'] = collect($jj_sort_detail)->groupBy('subject_id')->sortBy(function ($item, $key) {
            return $key;
        })->keys();
        $data['sort_now'] = $sort;

        foreach (cache('all_version_now') as $key => $value) {
            $version_array[$key]['id'] = $value->id;
            $version_array[$key]['text'] = $value->name;
        }
        $data['version_select'] = json_encode($version_array);

        return view('book_buy.new_detail', compact('data'));
    }

    public function show_answer($book_id)
    {
        $now_book = AWorkbookNew::find($book_id);
        if (!$now_book) {
            die('暂无对应练习册答案');
        }
        if ($now_book->sort === 0) {
            die('系列未选择');
        }
        $book_dir = '//QINGXIA23/book/' . User::find($now_book->update_uid)->name . '/' . $now_book->sort . '_' . cache('all_sort_now')->find($now_book->sort)->name . '/' . $now_book->bookname;

//        $sort_name = cache('all_sort_now')->find($now_book->sort)->name;
//        $book_detail_name = '2018年_'.config('workbook.grade')[$now_book->grade_id].'_'.config('workbook.subject_1010')[$now_book->subject_id].'_'.config('workbook.volumes')[$now_book->volumes_id].'_'.cache('all_version_now')->find($now_book->version_id)->name.'_'.$now_book->isbn;
        $data['book_id'] = $book_id;
        $files = \File::allFiles($book_dir);
        //$data['all_pages'] = LwwBookPage::where('bookid',$book_id)->select()->orderBy('page','asc')->paginate(30);
        if (!$files) {
            die($book_dir . '目录下暂无上传文件');
        }
        $f = new Filesystem();
        $book_dir = str_replace('//QINGXIA23/book/', 'file://QINGXIA23/book/', $book_dir);
        foreach ($files as $key => $file) {
            //if($f->extension($file)=='jpg'){
            $now_file = substr(basename($file), 0, -(strlen($f->extension($file)) + 1));

            $file_arr[intval($now_file)] = $book_dir . '/' . basename($file);
            //}
        }
        ksort($file_arr);
        $data['all_pages'] = $file_arr;
        return view('book_buy.show_answer', compact('data'));
    }

    public function status($start='',$end='')
    {
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
        $all_books = AWorkbookNew::where([['update_uid','>',2],['updated_at','>=',$query_start],['updated_at','<=',$query_end]])->select('id','bookname','update_uid','updated_at','sort')->with('has_user:id,name')->with('has_sort:id,name')->get();

        $data['all_books'] = $all_books->groupBy('update_uid')->transform(function ($item,$key){
            return $item->groupBy('sort')->transform(function ($item1,$key1){
                return $item1;
            })->sortBy(function ($value,$key){
                return $key;
            });
        })->sortBy(function ($value,$key){
            return $key;
        });
        return view('book_buy.new_status',['data'=>$data]);


//        $all = AWorkbookNew::where([['id', '>', 1000000],['update_uid',$uid]])->select('id', 'bookname', 'sort', 'updated_at', 'update_uid')->get();
//        $all_person = AWorkbookNew::where([['id','>',1000000],['update_uid','>',2]])->select(DB::raw('distinct(update_uid) as update_uid'))->with('has_user:id,name')->get();
//
//
//        foreach ($all as $book) {
//            $book->updated_at = substr($book->updated_at, 0, 10);
//        }
//        //dd($all[0]);
//        $data = $all->groupBy('update_uid')->transform(function ($item, $key) {
//            return $item->groupBy('updated_at')->transform(function ($item1, $key1) {
//                return $item1->groupBy('sort')->sortBy(function ($value, $key) {
//                    return $key;
//                });
//            })->sortByDesc(function ($value, $key) {
//                return $key;
//            });
//        })->sortBy(function ($value, $key) {
//            return $key;
//        });

//        return view('book_buy.new_status', ['data' => $data,'all_person'=>$all_person,'now_uid'=>$uid]);
    }

    public function history($book_id = 0, $grade_id = -1, $subject_id = -1, $volumes_id = -1, $version_id = -1, $sort = -1)
    {
        $data = [];

        $data['grade_id'] = $grade_id;

        if ($subject_id != -1) {
            $data['subject_id'] = $subject_id;
        }
        if ($volumes_id != -1) {
            $data['volumes_id'] = $volumes_id;
        }
        if ($version_id != -1) {
            $data['version_id'] = $version_id;
        }
        $data['sort'] = $sort;

        $sort_name = cache('all_sort_now')->where('id', $data['sort'])->first() ? cache('all_sort_now')->where('id', $data['sort'])->first()->name : '未选择';

        $grade_name = config('workbook.grade')[$data['grade_id']];
        $subject_name = config('workbook.subject_1010')[$data['subject_id']];
        $version_name = cache('all_version_now')->where('id', $data['version_id'])->first() ? cache('all_version_now')->where('id', $data['version_id'])->first()->name : '未选择';
        $volumes_name = '下册';

        $now_select_name = $sort_name . $grade_name . $subject_name  . $volumes_name. $version_name;
        $search['all'] = $sort_name . '|' . $subject_name . '|' . $grade_name . '|' . $volumes_name;
        $search['sort'] = $sort_name;
        $all_book = AWorkbook1010::where($data)->where(['status'=>1])->select('id', 'bookname', 'collect_count', 'concern_num', 'cover', 'version_year')->with(['has_answer' => function ($query) {
            return $query->where('status', 1)->select('id', 'bookid', 'text', 'textname', 'answer')->orderBy('text', 'asc');
        }])->orderBy('version_year', 'desc')->orderBy('id', 'desc')->get();
        $version_year = [];
        $version_all_years = [];
        if ($all_book) {
            $all_book->groupBy('version_year')->sortByDesc(function ($value, $key) {
                return $key;
            });
            $version_year[] = $all_book->pluck('version_year');
        }

        $buy_book = AWorkbookNew::where($data)->where('version_year', '>=', '2018')->select('id', 'bookname', 'version_year')->get();
        if ($buy_book) {
            $buy_book->groupBy('version_year')->sortByDesc(function ($value, $key) {
                return $key;
            });
            $version_year[] = $buy_book->pluck('version_year');
        }
        if (count($version_year) > 0) {
            $version_all_years = collect($version_year)->collapse()->unique()->sortByDesc(function ($value, $key) {
                return $value;
            });
        }
        return view('book_buy.history', ['now_select_name' => $now_select_name, 'all_book' => $all_book, 'buy_book' => $buy_book, 'version_all_years' => $version_all_years, 'search' => $search,'data'=>$data]);
    }

    public function new_index(Request $request, $sort = 'jj', $volumes_id = 2, $need_buy = 0)
    {

//        dd(AWorkbook1010::all());
//

        $page = intval($request->page);

        if ($sort === 'jj' or $sort === 'other') {
            $data['all_book'] = Cache::rememberForever('all_book_' . $sort . '_' . $volumes_id . '_' . $page, function () use ($volumes_id, $sort, $need_buy) {
                if ($volumes_id == 0) {
                    $volumes_arr = ['volumes_id', '<=', 2];
                } else {
                    $volumes_arr = ['volumes_id', $volumes_id];
                }
                if ($need_buy == 1) {
                    $need_buy_arr = ['need_buy', 1];
                } else {
                    $need_buy_arr = ['sort', '>', 0];
                }
                if ($sort === 'jj') {
                    $order = 'collect_count';
                } else {
                    $order = 'concern_num';
                }
                return AWorkbook1010::where([$volumes_arr, ['sort', '>', 0], $need_buy_arr])->orderBy($order, 'desc')->select('id', 'bookname', 'sort', 'grade_id', 'subject_id', 'volumes_id', 'version_id', 'collect_count', 'hdid', 'concern_num')->orderBy('collect_count', 'desc')->paginate(10);
            });
        } else {
            $data['all_book'] = Cache::rememberForever('all_book_baidu_' . $volumes_id . '_' . $page, function () use ($volumes_id, $need_buy) {
                if ($volumes_id == 0) {
                    $volumes_arr = ['volume_id', '<=', 2];
                } else {
                    $volumes_arr = ['volume_id', $volumes_id];
                }
                if ($need_buy == 1) {
                    $need_buy_arr = ['need_buy', 1];
                } else {
                    $need_buy_arr = ['sort_id', '>', 0];
                }
                return BaiduNewDaan::where([$volumes_arr, $need_buy_arr, ['sort_id', '>', 0], ['book_id', '>', 0], ['book_id', '<', 10000000]])->orderBy('num', 'desc')->select('book_id', DB::raw('sum(visit_count) as num'))->groupBy('book_id')->with('has_main_book:id,hdid,bookname,sort,grade_id,subject_id,volumes_id,version_id,collect_count,concern_num')->paginate(10);
            });
        }
        foreach ($data['all_book'] as $key => $book) {
            if ($sort === 'jj' or $sort === 'other') {
                $condition['grade_id'] = $book->grade_id;
                $condition['subject_id'] = $book->subject_id;
                $condition['volumes_id'] = 2;
                $condition['version_id'] = $book->version_id;
                $condition['sort'] = $book->sort;
            } else {
                $condition['grade_id'] = $book->has_main_book->grade_id;
                $condition['subject_id'] = $book->has_main_book->subject_id;
                $condition['volumes_id'] = 2;
                $condition['version_id'] = $book->has_main_book->version_id;
                $condition['sort'] = $book->has_main_book->sort;
            }
            $data['all_book'][$key]['related_book'] = AWorkbook1010::where($condition)->select('id', 'bookname', 'collect_count', 'hdid', 'concern_num', 'version_year')->orderBy('version_year', 'desc')->take(5)->get();
            $data['all_book'][$key]['buy_status'] = AWorkbookNew::where('id', '>', 1000000)->where($condition)->select('id', 'bookname')->where('version_year', '>', '2017')->get();
        }


        //dd($data['all_book']);

        $data['sort'] = $sort;
        $data['volumes_id'] = $volumes_id;
        $data['need_buy'] = $need_buy;

//        if($data['sort']==='other'){
//
//            dd(AWorkbook1010::orderBy('collect_count','desc')->select('sort','grade_id','subject_id','volumes_id','version_id',DB::raw('sum(collect_count) as num'))->groupBy('sort','grade_id','subject_id','volumes_id','version_id')->orderBy('num','desc')->paginate(10));
//
//
//            $data['all_sort'] = DataSortCollect::where('sort','>',0)->select('sort','sort_name','hd_collect','jj_collect')->with(['has_workbook_new'=>function($query){
//                $query->with(['has_main_book'=>function($query1){
//                    $query1->with('has_hd_book:id,concern_num')->select('id','hdid','collect_count');
//                }])->select('sort','id','bookname','version_year')->orderBy('version_year','desc')->limit(500);
//            }])->orderBy('hd_collect','desc')->paginate(20);
//        }else{
//            $data['all_sort'] = DataSortCollect::where('sort','>',0)->select('sort','sort_name','hd_collect','jj_collect')->with(['has_workbook_new'=>function($query){
//                $query->where('version_year','<','2018')->with(['has_main_book'=>function($query1){
//                   $query1->with('has_hd_book:id,concern_num')->select('id','hdid','collect_count');
//                }])->select('sort','id','bookname','version_year')->orderBy('version_year','desc')->limit(500);
//            }])
//            ->orderBy('jj_collect','desc')->paginate(20);
//        }

        return view('book_buy.all_new_index', ['data' => $data]);
    }

    public function upgrade_book(Request $request)
    {
        $id = intval($request->book_id);
        $now_book = AWorkbook1010::find($id);
        if ($now_book->version_year >= 2018) {
            return response()->json(['status' => 0, 'msg' => '已有2018版,无需升级']);
        }
        $data['id'] = 5000000 + $now_book->id;
        $data['bookname'] = str_replace(['2014', '2015', '2016', '2017'], '2018', $now_book->bookname);
        $data['redirect_id'] = $now_book->id;
        $data['isbn'] = $now_book->isbn;
        $data['cover'] = $now_book->cover;
        $data['grade_id'] = $now_book->grade_id;
        $data['subject_id'] = $now_book->subject_id;
        $data['volumes_id'] = $now_book->volumes_id;
        $data['version_id'] = $now_book->version_id;
        $data['version_year'] = 2018;
        $data['addtime'] = date('Y-m-d H:i:s', time());
        $data['sort'] = $now_book->sort;
        $data['bookcode'] = md5($data['bookname'] . $data['isbn'] . $data['grade_id'] . $data['subject_id'] . $data['volumes_id'] . $data['version_id'] . $data['version_year'] . $data['sort']);
        if (AWorkbook1010::where(['bookcode' => $data['bookcode']])->count() > 0) {
            return response()->json(['status' => 0, 'msg' => '已升级，不能重复升级']);
        }
        $data['grade_name'] = '';
        $data['subject_name'] = '';
        $data['volume_name'] = '';
        $data['version_name'] = '';
        $data['sort_name'] = '';
        $data['ssort_id'] = 0;
        if ($new_book = AWorkbook1010::create($data)) {
            return response()->json(['status' => 1]);
        }
        return response()->json(['status' => 0, 'msg' => '升级失败']);
    }
}
