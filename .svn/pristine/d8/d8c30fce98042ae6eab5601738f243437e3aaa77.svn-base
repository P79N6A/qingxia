<?php

namespace App\Http\Controllers\AnswerAudit;

use App\AnswerModel\AWorkbook1010Cip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OssToAnswerController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $all_books = AWorkbook1010Cip::select('cover_photo','cip_photo','addtime','cip_time','hdid')->paginate(10);



        return view('answer_audit.oss_to_answer.index');
    }
}
