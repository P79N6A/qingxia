<?php

namespace App\Http\Controllers\Task;

use App\AWorkbookFeedback;
use App\Http\Controllers\UserAbout\FeedbackController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateFeedbackController extends Controller
{

    public function __construct()
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', -1);
    }

    //1.更新feedback collect_count
    public function index()
    {
        AWorkbookFeedback::where([['uid','>',0],['bookid','>',0],['id','>',0]])->select('id','bookid')->with('has_book:id,collect_count')
            ->chunk(1000,function ($feedbacks){
               foreach ($feedbacks as $feedback){
                   AWorkbookFeedback::WHERE(['id'=>$feedback->id])->update(['collect_count'=>$feedback->has_book?$feedback->has_book->collect_count:0]);
               }
            });
    }
}
