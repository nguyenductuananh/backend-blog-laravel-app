<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Blog;

class BlogService extends BaseService
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function index(array $queryParam)
    {
        $data = $this->model::search($queryParam)
            ->orderBy("create_at")
            ->with("author")
            ->paginate(PAGINATION_PER_PAGE)
            ->items();
        return $data;
    }

    public function getBlog(int $id)
    {
        $blog = $this->model::where("id", $id)
            ->with(["author", "categories"])
            ->avgRate()
            ->get()
            ->first();
        if (empty($blog)) {
            throw (new NotFoundException());
        }
        $blog->categories = empty($blog->categories) ? [] : $blog->categories;
        for ($idx = 0; $idx < count($blog->categories); $idx++) {
            $blog->categories[$idx] = $blog->categories[$idx]->title;
        }
        return $blog;
    }

    public function storeBlog($data)
    {
        $savedModel = $this->store($data);
        $savedModel->categories()->attach($data['categories']);
        return $savedModel;
    }

    public function updateBlog($data)
    {
        $categories = [...$data['categories']];
        unset($data['categories']);
        $updatedModel = $this->update($data['id'], $data);
        if (count($categories) && is_array($categories)) {
            $updatedModel->categories()->sync(array_unique($categories));
        }
        return true;
    }
}
