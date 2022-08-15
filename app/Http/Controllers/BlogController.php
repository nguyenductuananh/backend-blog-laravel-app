<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public $PER_PAGE = 10;

    public function index(Request $request)
    {
        return Blog::search($request->query())->orderBy("create_at")->with('author')->paginate($this->PER_PAGE)->items();
    }

    public function show(Request $request)
    {
        $blog = Blog::where('id', $request->blog)->with(['author', 'categories'])->get()->first();
        for ($idx = 0; $idx < count($blog->categories); $idx++) {
            $blog->categories[$idx] = $blog->categories[$idx]->title;
        }
        return $blog;
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
        return ['message' => 'Add Successfully!'];
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'content' => 'required',
            'title' => 'required',
            'categories' => 'required|array',
            'blog_id' => ['required', 'numeric', Rule::exists('blog', 'id')->where("id", $blog->id)]
        ]);

        $blog->content = $validated['content'];
        $blog->title=$validated['title'];
        $blog->save();
        $blog->categories()->detach();
        $blog->categories()->attach($validated['categories']);
        return ['message' => 'Update successfully!'];
    }
}
