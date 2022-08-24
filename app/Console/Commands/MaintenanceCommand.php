<?php

namespace App\Console\Commands;

use App\Models\MaintenanceSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto down laravel app when time at schedule time';

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
        $disk = Storage::build([
            'driver' => 'local',
            'root' => storage_path('framework'),
        ]);
        $maintenanceCount = MaintenanceSchedule::where("start", '<', now())->where("end", ">", now())->get()->count();
        if (boolval($maintenanceCount)) {
            $this->info("MAINTAIN MODE ON");
            $disk->put('down', json_encode([
                "except" => [],
                "redirect" => null,
                "retry" => null,
                "refresh" => null,
                "secret" => null,
                "status" => 503,
                "template" => null
            ]));
        } else {
            $this->info("MAINTAIN MODE OFF");
            $disk->delete('down');
        }
    }
}
