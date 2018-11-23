<?php

namespace App\Console\Commands;

use App\Http\Controllers\Baidu\GetDataWangController;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:qwe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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









        $start = Carbon::create(2017,2,1)->startOfMonth();

        $end   = Carbon::today()->startOfMonth();
        do
        {
            $now_start = $start;
            $months[$start->format('Y-m')] = [$start->timestamp,$now_start->addMonth()->timestamp];
        } while ($start->addMonth() <= $end);

        dd($months);
        $dataController = new GetDataWangController();

        foreach ($months as $month){
            var_dump($month);

            #$dataController->auto_update($month[0],$month[1]);
        }

//        do
//        {
//            $months[$start->format('m-Y')] = $start->format('F Y');
//        } while ($start->addMonth() <= $end);
//
//        return $months;
//
//
//
//        $dataController = new GetDataWangController();
//        $dataController->auto_update();
    }
}
