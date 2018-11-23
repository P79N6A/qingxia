<?php

namespace App\Console\Commands;

use App\AWorkbook1010;
use App\AWorkbookFeedback;
use DB;
use Illuminate\Console\Command;

class FeedbackArrange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:arrange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update feedback\'s collect_count';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $feedbacks = AWorkbookFeedback::where('id','>=',39641)->select('bookid')->groupBy('bookid')->get();
        foreach ($feedbacks as $feedback){
            if($feedback->bookid<10000000){
                $data['collect_count'] = AWorkbook1010::where('id',$feedback->bookid)->first()?AWorkbook1010::where('id',$feedback->bookid)->first()->collect_count:0;
                AWorkbookFeedback::where('bookid',$feedback->bookid)->update($data);
            }
        }
    }
}
