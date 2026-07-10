<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $products = $category->products()->with('category')->orderBy('created_at', 'desc')->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }

    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }
}
