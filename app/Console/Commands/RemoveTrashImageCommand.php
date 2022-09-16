<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;

class RemoveTrashImageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:scan-trash-image';

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
     * @return int
     */
    public function handle()
    {
        $savedFileList = Blog::all()->pluck('filePath')->filter(
            fn ($filePath) =>
            $filePath && file_exists(public_path() . $filePath)
        )->map(fn ($fileName) => str_replace(UPLOAD_PUBLIC_FOLDER, '', $fileName))->toArray();
        $fileList = array_diff(scandir(public_path() . UPLOAD_PUBLIC_FOLDER), ['.', '..']);
        foreach (array_diff($fileList, $savedFileList) as $file) {
            unlink(public_path() . UPLOAD_PUBLIC_FOLDER . $file);
        }

        return 0;
    }
}
