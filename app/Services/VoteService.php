<?php

namespace App\Services;

use App\Models\Vote;

class VoteService extends BaseService
{
    public function __construct(Vote $model)
    {
        parent::__construct($model);
    }

    public function storeVote($comment_id)
    {
        $existVote = $this->model::where("account_id", auth()->user()->id)->where("comment_id", $comment_id)->get()->toArray();

        if (boolval(count($existVote))) {
            return false;
        } else {
            return boolval($this->store(['comment_id' => (int)$comment_id]));
        }
    }
    public function removeVote($comment_id)
    {
        $this->model::where("account_id", auth()->user()->id)->where("comment_id", $comment_id)->delete();
        return true;
    }
}
