<?php

namespace App\Http\Controllers\Manage;

use App\Sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index(Request $request){
        //$user = $request->user();

        return view('manage.index');
    }
}
