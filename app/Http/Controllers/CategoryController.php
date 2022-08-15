<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index() : object {
        return Category::where('account_id', auth()->user()->id)->orWhere('account_id', null)->get();
    }
    public function store(Request $request) : Category {
        $validated = $request->validate([
            'title' => 'required'
        ]);

        $category = new Category();
        $category->title = $validated['title'];
        $category->account_id = auth()->user()->id;
        $category->save();

        return $category;
    }
}
