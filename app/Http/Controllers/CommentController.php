<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Enums\NotificationType;
use App\Jobs\CreateNotificationJob;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function blogComments(Request $request)
    {
        return Comment::where("blog_id", $request->blog_id)->where("reply_to", null)->with(['owner', 'reply'])->get();
    }

    public function postComment(Request $request)
    {
        try {
            $blog = Blog::findOrFail($request->blog_id);
        } catch (\Exception $exception) {
            return $this->formatJson(['message' => "Blog isn't exist"]);
        }
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|numeric',
            'content' => 'required',
            'reply_to' => ['numeric', 'nullable', Rule::exists('comment', 'id')->where("id", $request->post('reply_to'))->whereNull('reply_to')],
        ]);
        if ($validator->fails()) {
            return $this->formatJson($validator->getMessageBag(), HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
        $comment = array_merge(['create_at' => now(), 'account_id' => auth()->user()->id], $validator->safe()->all());

        CreateNotificationJob::dispatch(['account_id' => auth()->user()->id, 'notification_type' => NotificationType::COMMENT, 'comment_id' => $comment['blog_id']])->delay(now()->addMinute(1));

        Comment::create($comment);
        return $this->formatJson(['message' => "Success"]);
    }
}
