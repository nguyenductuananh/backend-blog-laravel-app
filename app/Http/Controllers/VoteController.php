<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\VoteStoreRequest;
use App\Services\VoteService;
use Illuminate\Http\JsonResponse;

class VoteController extends Controller
{
    public function __construct(VoteService $service)
    {
        parent::__construct($service);
    }

    public function makeVote(VoteStoreRequest $request)
    {
        $validated = $request->validated();
        $isSave = $this->service->executed("storeVote", $validated['comment_id']);
        if ($isSave) {
            return $this->formatJson(__("response-message.do-success", ['action' => 'Create Vote']), HttpStatusCode::CREATED);
        } else {
            return $this->formatJson(__('response-message.cant-create-vote'), HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
    }

    public function removeVote(VoteStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $isSuccess = $this->service->execute('removeVote', $validated['comment_id']);
        if ($isSuccess) {
            return $this->formatJson(__('response-message.do-success', ['action' => "Remove Vote"]));
        } else {
            return $this->formatJson(__("response-message.fail"), HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
    }
}
