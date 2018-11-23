<?php

namespace App\Http\Controllers\QuestionManage;

use App\PreMQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
      return view('question_manage.index');
    }

    public function detail($id)
    {
      return view('question_manage.detail',compact('id'));
    }

    public function status()
    {
      $data = '123123';
      return view('question_manage.status',compact('data'));
    }
}
