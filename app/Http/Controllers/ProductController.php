<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $selectedCategory = null;

        $query = Product::with('category')->orderBy('created_at', 'desc');

        if ($request->filled('category')) {
            $selectedCategory = Category::where('url', $request->query('category'))->first();

            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            } else {
                // fallback to featured if category not found
                $query->where('featured', true);
            }
        } else {
            $query->where('featured', true);
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products', 'categories', 'selectedCategory'));
    }
}
