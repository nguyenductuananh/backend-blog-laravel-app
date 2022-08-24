<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Enums\NotificationType;
use App\Jobs\CreateNotificationJob;
use App\Models\Comment;
use App\Models\Vote;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function makeVote(Comment $comment)
    {
        $vote = new Vote();
        $existVote = Vote::where("account_id", auth()->user()->id)->where("comment_id", $comment->id)->get()->count();
        if ($existVote) {
        }
        $vote->account_id = auth()->user()->id;
        $vote->comment_id = $comment->id;
        $vote->create_at = now();
        try {
            $vote->saveOrFail();
            CreateNotificationJob::dispatch(['account_id' => auth()->user()->id, 'notification_type' => NotificationType::VOTE, 'comment_id' => $vote->comment_id])->delay(now()->addMinute(1));
            return $this->formatJson($vote);
        } catch (Exception $exception) {
            return $this->formatJson(['message' => "Can't create vote for this comment"], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function removeVote(Comment $comment): JsonResponse
    {
        try {
            $vote = Vote::where("account_id", auth()->user()->id)->where("comment_id", $comment->id)->first()->get();
            if (!empty($vote)) {
                $vote->delete();
                return $this->formatJson(['message' => 'Remove vote successfully!']);
            } else {
                return $this->formatJson(['message' => "Vote isn't exist!"], HttpStatusCode::UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $exception) {
            return $this->formatJson(['message' => 'Remove vote fails', 'errors' => $exception->getMessage()], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
