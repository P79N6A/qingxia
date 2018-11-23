<?php

namespace App\Http\Controllers\Manage;

use App\BookVersion;
use App\BookVersionType;
use App\WorkbookRecycle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookRecycleController extends Controller
{
    public function index(Request $request){
        //$user = $request->user();

        $edits = WorkbookRecycle::leftJoin('sort', 'a_workbook_1010_delete.sort', '=', 'sort.id')
            ->leftJoin('book_version', 'a_workbook_1010_delete.press_id', '=', 'book_version.id')
            ->leftJoin('book_version_type', 'a_workbook_1010_delete.version_id', '=', 'book_version_type.id')
            ->select(['a_workbook_1010_delete.*', 'sort.note as sort_note', 'book_version.name as press_name', 'book_version_type.name as version_name'])
            ->orderBy('a_workbook_1010_delete.del_time','desc')
            ->orderBy('a_workbook_1010_delete.version_year','desc')
            ->paginate(30);

        $press = BookVersion::all('id','name');
        $version = BookVersionType::all('id','name');
        return view('manage.book_recycle',compact(['edits','press','version']));
    }
}
