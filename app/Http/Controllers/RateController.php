<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Enums\NotificationType;
use App\Http\Requests\RatePostRequest;
use App\Jobs\CreateNotificationJob;
use App\Models\Blog;
use App\Models\Rate;
use App\Services\RateService;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RateController extends Controller

{
    public function __construct(RateService $service)
    {
        parent::__construct($service);
    }

    public function makeRate(RatePostRequest $request, Blog $blog)
    {
        $request->validated();
        $rated = Rate::where('account_id', auth()->user()->id)->where('blog_id', $request->post('blog_id'))->get();
        if ($rated->count()) {
            return $this->formatJson(['message' => "Rated, can't add more"], HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
        $rate = new Rate();
        $rate->rate = $request->post('rate');
        $rate->account_id = auth()->user()->id;
        $rate->blog_id = $request->post("blog_id");
        $rate->create_at = now();
        $rate->save();
        CreateNotificationJob::dispatch(['account_id' => auth()->user()->id, 'notification_type' => NotificationType::RATE, 'blog_id' => $rate->blog_id,])->delay(now()->addMinute(1));
        return $this->formatJson($rate);
    }

    public function removeRate(Request $request, Blog $blog)
    {
        $validate = $request->validate([
            'blog_id' => ['required', 'numeric', Rule::in([$blog->id])]
        ]);

        $rate = Rate::where('blog_id', $blog->id)->where("account_id", auth()->user()->id)->get()->first();
        if (empty($rate)) {
            return $this->formatJson(['message' => "Rate isn't exist!"], HttpStatusCode::UNPROCESSABLE_ENTITY);
        } else {
            return $this->formatJson(['message' => 'Remove done']);
        }
    }
}
