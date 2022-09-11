<?php

namespace App\Services;

use App\Models\Comment;

class CommentService extends BaseService
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }
    public function getBlogComments($blog_id)
    {

        return $this->model::where("blog_id", $blog_id)->where("reply_to", null)->with(['owner', 'reply'])->get();
    }
}
