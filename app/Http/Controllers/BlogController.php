<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(BlogService $service)
    {
        $this->service = $service;
    }
    public function index(Request $request)
    {
        $searchResult = $this->service->index($request->query());
        return $this->formatJson($searchResult);
    }

    public function show(Request $request)
    {
        $blog = $this->service->getBlog($request->blog);
        return $this->formatJson($blog);
    }

    public function store(BlogRequest $request): array
    {
        $validated = $request->validated();
        $this->service->storeBlog($validated);
        return $this->formatJson(response_format_data(__("response-message.do-success", ['action' => 'Add']), HttpStatusCode::CREATED));
    }

    public function update(BlogRequest $request)
    {
        $validated = $request->validated();
        $this->service->updateBlog($validated);
        return $this->formatJson(["message" => __("response-message.do-success", ['action' => "Update"]), HttpStatusCode::CREATED]);
    }
}
