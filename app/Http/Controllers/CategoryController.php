<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    public function __construct(CategoryService $service)
    {
        parent::__construct($service);
    }
    public function index(): Collection
    {
        return $this->service->getOwnCategories();
    }
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $this->service->store($validated);
        return $this->formatJson(__('response-message.do-success', ['action' => 'Add']), HttpStatusCode::CREATED);
    }
}
