<?php

namespace App\Console\Commands;

use App\Models\Rate;
use Illuminate\Console\Command;

class DeleteRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is command to delete recent rate';

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
     * @return int
     */
    public function handle()
    {
        $today = now();
        $today->setHour(0);
        $today->setSecond(0);
        $today->setMillisecond(0);
        Rate::where('create_at', ">", $today)->delete();
    }
}
