<?php

namespace App\Jobs;

use App\Models\LogQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class LogQueryTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $requestPath = "";
    public $userId = "";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $userId)
    {
        $this->requestPath = $path;
        $this->userId = $userId;
    }

//    public function middleware() {
//        return [new WithoutOverlapping($this->userId)];
//    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logTime = new LogQuery();
        $logTime->create_at = now();
        $logTime->user_id = $this->userId;
        $logTime->path = $this->requestPath;
        $logTime->save();
    }
}
