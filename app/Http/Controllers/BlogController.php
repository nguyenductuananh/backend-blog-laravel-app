<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Jobs\LogQueryTime;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public $PER_PAGE = 10;

    public function index(Request $request)
    {
        $data = Blog::search($request->query())
            ->orderBy("create_at")
            ->with("author")
            ->paginate($this->PER_PAGE)
            ->items();
        return $this->formatJson($data);
    }

    public function show(Request $request)
    {
        $blog = Blog::where("id", $request->blog)
            ->with(["author", "categories"])
            ->selectRaw("blog.*, avg(rate) as avgRate")
            ->leftJoin("rate", "rate.blog_id", "=", "blog.id")
            ->groupBy("id")
            ->get()
            ->first();
        for ($idx = 0; $idx < count($blog->categories); $idx++) {
            $blog->categories[$idx] = $blog->categories[$idx]->title;
        }
        dispatch(new LogQueryTime($request->path(), auth()->user()->id));

        return $this->formatJson($blog);
    }

    public function store(BlogRequest $request): array
    {
        $validated = $request->validated();
        $blog = new Blog(
            array_merge($validated, [
                "create_at" => now(),
                "account_id" => auth()->user()->id,
            ])
        );
        $blog->save();
        $blog->categories()->attach($request->post("categories"));
        return $this->formatJson(["message" => "Add Successfully!"]);
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();
        
        $blog->content = $validated["content"];
        $blog->title = $validated["title"];
        $blog->save();
        $blog->categories()->sync(array_unique($request->post('categories')));
        return $this->formatJson(["message" => "Update successfully!"]);
    }
}
