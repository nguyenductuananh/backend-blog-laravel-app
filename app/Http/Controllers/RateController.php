<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Models\Blog;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RateController extends Controller
{
    public function makeRate(Request $request, Blog $blog){
        $validated = $request->validate([
            'rate' => "required|numeric|max:5|min:0",
            'blog_id' => ['required', 'numeric', Rule::in([$blog->id])],
        ]);
        $rated = Rate::where('account_id', auth()->user()->id)->where('blog_id', $request->post('blog_id'))->get();
        if(!$rated->count()) {
            return $this->formatJson(['message' => "Can't create rate for current blog"], HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
        $rate = new Rate();
        $rate->rate = $request->post('rate');
        $rate->account_id = auth()->user()->id;
        $rate->blog_id = $request->post("blog_id");
        $rate->create_at = now();
        $rate->save();
        return $this->formatJson($rate);
    }

    public function removeRate(Request $request, Blog $blog) {
        $validate = $request->validate([
            'blog_id' => ['required', 'numeric', Rule::in([$blog->id])]
        ]);

        $rate = Rate::where('blog_id', $blog->id)->where("account_id", auth()->user()->id)->get()->first();
        if(empty($rate)) {
            return $this->formatJson(['message' => "Rate isn't exist!"], HttpStatusCode::UNPROCESSABLE_ENTITY);
        } else {
            return $this->formatJson(['message' => 'Remove done']);
        }
    }
}
