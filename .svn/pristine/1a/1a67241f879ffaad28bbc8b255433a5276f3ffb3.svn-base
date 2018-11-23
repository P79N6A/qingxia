<?php

namespace App\Http\Controllers\Manage;

use App\Workbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class IsbnController extends Controller
{
    public function index(Request $request){
        //$user = $request->user();
        $data['all_isbn'] = Workbook::where(DB::raw('length(isbn)'),13)
            ->where('isbn','not like',DB::raw('concat("9787",press_id,"%")'))
            ->where('isbn','like','9787%')
            ->where('status','<>',3)
            ->join('book_version', 'a_workbook_1010.press_id', '=', 'book_version.id')
            ->select(['a_workbook_1010.id','a_workbook_1010.isbn','a_workbook_1010.cover_photo_thumbnail','a_workbook_1010.bookname','a_workbook_1010.press_id','book_version.name as press_name',DB::raw('substring(isbn,5,4)')])
            ->orderBy('isbn','desc')
            ->paginate(100);

        return view('manage.isbn_manage',compact('data'));
    }
}
