<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function blogComments(Request $request) {
        return Comment::where("blog_id", $request->blog_id)->where("reply_to", null)->with('owner')->with('reply')->get();
    }

    public function postComment(Request $request) {
        try{
            $blog = Blog::findOrFail($request->blog_id);
        } catch (\Exception $exception) {
            return response()->json(['message' => "Blog isn't exist"]);
        }
//        dd( Rule::exists('comment', 'reply_to')->where("id" , $request->post('reply_to'))->whereNotNull('reply_to'));
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|numeric',
            'content' => 'required',
            'reply_to'=> ['numeric', 'nullable', Rule::exists('comment', 'id')->where("id" , $request->post('reply_to'))->whereNull('reply_to')],
        ]);
        if($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }
        $comment = array_merge(['create_at' => now(), 'account_id' => auth()->user()->id], $validator->safe()->all());

        Comment::create($comment);
        return response()->json(['message' => "Success"]);
    }
}
