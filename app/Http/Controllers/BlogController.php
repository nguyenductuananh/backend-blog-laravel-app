<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public $PER_PAGE = 10;
    public  function  index(Request $request) {
        return Blog::search($request->query())->orderBy("create_at")->with('author')->paginate($this->PER_PAGE)->items();
    }

    public function show(Request $request) {

        return Blog::where('id', $request->blog)->with('author')->get()->first();
    }
}
