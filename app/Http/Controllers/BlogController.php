<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Jobs\LogQueryTime;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public $PER_PAGE = 10;

    public function index(Request $request)
    {
        $data = Blog::search($request->query())->orderBy("create_at")->with('author')->paginate($this->PER_PAGE)->items();
        return $this->formatJson($data);
    }

    public function show(Request $request)
    {
        $blog = Blog::where('id', $request->blog)->with(['author', 'categories'])->selectRaw("blog.*, avg(rate) as avgRate")->leftJoin('rate', 'rate.blog_id', '=', 'blog.id')->groupBy("id")->get()->first();
        for ($idx = 0; $idx < count($blog->categories); $idx++) {
            $blog->categories[$idx] = $blog->categories[$idx]->title;
        }
        dispatch(new LogQueryTime($request->path(), auth()->user()->id));

        return $this->formatJson($blog);
    }

    public function store(Request $request): array
    {
        $validated = $request->validate([
            'content' => 'required',
            'title' => 'required',
            'categories' => 'required|array'
        ]);
        $blog = new Blog();
        $blog->content = $validated['content'];
        $blog->title = $validated['title'];
        $blog->create_at = now();
        $blog->account_id = auth()->user()->id;
        $blog->save();
        $blog->categories()->attach($request->post("categories"));
        return $this->formatJson(['message' => 'Add Successfully!']);

    }

    public function update(Request $request, Blog $blog)
    {
        if ($blog->account_id !== auth()->user()->id) {
            return $this->formatJson(['message' => "Forbidden"], HttpStatusCode::FORBIDDEN);
        }
        $filteredDuplicateCategories = array_unique($request->post('categories'));
        $validated = $request->validate([
            'content' => 'required',
            'title' => 'required',
            'categories' => ['required', 'array', Rule::exists('category', 'id',)->where("account_id", auth()->user()->id)->whereIn('id', $filteredDuplicateCategories)],
            'blog_id' => ['required', 'numeric', Rule::exists('blog', 'id')->where("id", $blog->id)->where('account_id', auth()->user()->id)]
        ]);

        $blog->content = $validated['content'];
        $blog->title = $validated['title'];
        $blog->save();
        $blog->categories()->sync($filteredDuplicateCategories);
        return $this->formatJson(['message' => 'Update successfully!']);

    }
}
