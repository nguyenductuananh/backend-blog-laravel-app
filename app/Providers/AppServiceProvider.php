<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Helper init
        require_once base_path() . '/app/Utils/arrayHelpers.php';
        require_once base_path() . '/app/Utils/responseHelpers.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Log query when a sql execute
        DB::listen(function ($query) {
            $line = '[' . now() . ']' . PHP_EOL;
            $line .= $query->sql . PHP_EOL . PHP_EOL;
            $line .= '________________________________________________' . PHP_EOL;
            file_put_contents(storage_path('logs/query.log'), $line, FILE_APPEND);
        });
    }
}
