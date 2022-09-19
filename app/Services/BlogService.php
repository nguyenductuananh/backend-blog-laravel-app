<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogService extends BaseService
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function index(array $queryParam)
    {
        $data = $this->model->search($queryParam)
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
            return false;
        }
        $blog->categories = $blog->categories->plunk("title");
        return $blog;
    }

    public function storeBlog($data)
    {
        $savedModel = $this->store($data);
        $savedModel->categories()->attach($data['categories']);
    }

    public function updateBlog($data = [])
    {
        $categories = array_column_default($data, 'categories', []);
        $id = array_column_default($data, 'id', null);
        $passingData = array_except($data, ['blog_id', 'categories']);
        $updatedModel = $this->update($id, $passingData);
        if (count($categories) && is_array($categories)) {
            $updatedModel->categories()->sync(array_unique($categories));
        }
        return $updatedModel;
    }

    public function uploadImageBlog($file)
    {
        $publicUploadFolder =  '/upload/images/';
        $fileName = 'Yukine_' . date_format(now(), 'Ymdhis') . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $publicStorage = Storage::build([
            'driver' => 'local',
            'root' => public_path(),
        ]);
        if ($publicStorage->put($publicUploadFolder . $fileName, file_get_contents($file))) {
            return  $publicUploadFolder . $fileName;
        }
    }
}
