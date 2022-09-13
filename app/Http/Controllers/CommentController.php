<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Enums\NotificationType;
use App\Http\Requests\CommentRequest;
use App\Jobs\CreateNotificationJob;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(CommentService $service)
    {
        parent::__construct($service);
    }
    public function blogComments(Request $request)
    {
        return $this->formatJson($this->service->execute('getBlogComments', $request->blog));
    }

    public function postComment(CommentRequest $request)
    {
        $validated = $request->validated();
        $createdComment = $this->service->execute('store', $validated);
        CreateNotificationJob::dispatch(['account_id' => auth()->user()->id, 'notification_type' => NotificationType::COMMENT, 'comment_id' => $createdComment->id])->delay(now()->addMinute(1));
        return $this->formatJson(__('response-message.do-success', ['action' => "Add"]), HttpStatusCode::CREATED);
    }
}
