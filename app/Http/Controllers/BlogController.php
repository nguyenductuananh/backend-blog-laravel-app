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
        $searchResult = $this->service->execute('index', $request->query(), 'name');
        return $this->formatJson($searchResult);
    }

    public function show(Request $request)
    {
        $blog = $this->service->execute('getBlog', $request->blog);
        return $this->formatJson($blog);
    }

    public function store(BlogRequest $request)
    {
        $validated = $request->validated();
        if ($this->service->executed('storeBlog', (array) $validated)) {
            return $this->formatJson(__("response-message.do-success", ['action' => 'Add']), HttpStatusCode::CREATED);
        }
    }

    public function update(BlogRequest $request)
    {
        $validated = $request->validated();
        if ($this->service->execute('updateBlog', array_merge($validated->toArray(), ['account_id' => auth()->user()->id]))) {
            return $this->formatJson(__("response-message.do-success", ['action' => "Update"]), HttpStatusCode::CREATED);
        }
    }
}
