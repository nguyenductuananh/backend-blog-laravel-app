<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Enums\NotificationType;
use App\Http\Requests\RatePostRequest;
use App\Http\Requests\RateRemoveRequest;
use App\Jobs\CreateNotificationJob;
use App\Models\Blog;
use App\Services\RateService;

class RateController extends Controller

{
    public function __construct(RateService $service)
    {
        parent::__construct($service);
    }

    public function makeRate(RatePostRequest $request,)
    {
        $validated =  $request->validated();
        $isSave = $this->service->storeRate($validated);
        if (!$isSave) {
            return $this->formatJson("Rated, can't add more", HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
        CreateNotificationJob::dispatch(['account_id' => auth()->user()->id, 'notification_type' => NotificationType::RATE, 'blog_id' => $validated['blog_id'],])->delay(now()->addMinute(1));
        return $this->formatJson(__('response-message.do-success', ['action' => "Create"]), HttpStatusCode::CREATED);
    }

    public function removeRate(RateRemoveRequest $request)
    {
        $validate = $request->validated();
        if ($this->service->deleteRate($validate['blog_id'])) {
            return $this->formatJson(__("response-message.do-success", ['action' => "Delete Rate"]), HttpStatusCode::SUCCESS);
        }
    }
}
