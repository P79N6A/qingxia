<?php

namespace App\Http\Controllers\Manage;

use App\Book;
use App\Sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SubSortController extends Controller
{

    public function index(Request $request)
    {
        //$user = $request->user();
//        $data['user']=$user;
        $sort_about['sort_info'] = Sort::select(['id','name','main_word','sub_sort'])->paginate(30);
        foreach ($sort_about['sort_info'] as $key => $value){
            $sort_about['img'][$key] = Book::where('sort',$value->id)->select(['cover_photo','cover_photo_thumbnail'])->take(5)->get();
        }
        return view('manage.sub_sort_arrange')->with(['sort_about'=>$sort_about]);
    }
}
