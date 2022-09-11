<?php

namespace App\Services;

use App\Models\Category;

class CategoryService extends BaseService
{

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getOwnCategories()
    {
        return $this->model::where('account_id', auth()->user()->id)->orWhere('account_id', null)->get();
    }
}
