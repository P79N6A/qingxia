<?php

namespace App\Jobs;

use App\Console\Commands\SendEmails;
use App\LwwBook;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    public $tries = 5;

  /**
   * Create a new job instance.
   *
   * @param User $podcast
   */
    public function __construct(User $user)
    {
      $this->user = $user;
    }

  /**
   * Execute the job.
   *
   * @param LwwBook $book
   * @return void
   */
    public function handle(LwwBook $book)
    {
      Log::info("Request Cycle with Queues Begins");
      //$this->user->update(['related_uid'=>2]);
      Log::info("Request Cycle with Queues Ends");
    }

//    public function retryUntil()
//    {
//      return now()->addSeconds(5);
//    }
}
