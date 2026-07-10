<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function generateSlug(string $name, ?int $exceptId = null): string
    {
        $slug = Str::slug($name) ?: 'product';
        $candidate = $slug;
        $counter = 1;

        while (Product::where('url', $candidate)
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->exists()) {
            $candidate = $slug . '-' . $counter++;
        }

        return $candidate;
    }

    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return view('admin.products.index', compact('products'));
    }

    public function data(Request $request)
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $products->map(function (Product $product) {
                return [
                    'name' => $product->name,
                    'url' => $product->url,
                    'price' => '₹ ' . number_format($product->price, 2),
                    'stock' => $product->stock,
                    'category' => $product->category?->name ?? '—',
                    'featured' => $product->featured ? 'Yes' : 'No',
                    'actions' => '<a href="' . route('admin.products.edit', $product) . '" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil"></i></a>' .
                        '<form action="' . route('admin.products.destroy', $product) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">' .
                        csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>' .
                        '</form>',
                ];
            }),
        ]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'ingredients' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'usage_instructions' => ['nullable', 'string'],
            'safety_notes' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'featured' => ['sometimes', 'boolean'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            File::ensureDirectoryExists(public_path('images'));
            $uploadedImage = $request->file('image');
            $filename = uniqid('product_') . '.' . $uploadedImage->extension();
            $uploadedImage->move(public_path('images'), $filename);
            $imagePath = 'images/' . $filename;
        }

        Product::create([
            'name' => $request->name,
            'url' => $this->generateSlug($request->name),
            'category_id' => $request->category_id,
            'stock' => $request->input('stock', 0),
            'description' => $request->description,
            'image' => $imagePath,
            'ingredients' => $request->ingredients,
            'benefits' => $request->benefits,
            'usage_instructions' => $request->usage_instructions,
            'safety_notes' => $request->safety_notes,
            'price' => $request->price,
            'featured' => $request->boolean('featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'ingredients' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'usage_instructions' => ['nullable', 'string'],
            'safety_notes' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'featured' => ['sometimes', 'boolean'],
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            File::ensureDirectoryExists(public_path('images'));
            $uploadedImage = $request->file('image');
            $filename = uniqid('product_') . '.' . $uploadedImage->extension();
            $uploadedImage->move(public_path('images'), $filename);
            $imagePath = 'images/' . $filename;
        }

        $product->update([
            'name' => $request->name,
            'url' => $this->generateSlug($request->name, $product->id),
            'category_id' => $request->category_id,
            'stock' => $request->input('stock', 0),
            'description' => $request->description,
            'image' => $imagePath,
            'ingredients' => $request->ingredients,
            'benefits' => $request->benefits,
            'usage_instructions' => $request->usage_instructions,
            'safety_notes' => $request->safety_notes,
            'price' => $request->price,
            'featured' => $request->boolean('featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
