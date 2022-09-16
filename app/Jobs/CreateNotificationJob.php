<?php

namespace App\Jobs;

use App\Enums\NotificationType;
use App\Mail\NotificationMail;
use App\Models\Account;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CreateNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data['account'])->send(new NotificationMail());
        unset($this->data['account']);
        $notification = new Notification(array_merge($this->data, ['create_at' => now()]));
        $notification->save();
    }


}
