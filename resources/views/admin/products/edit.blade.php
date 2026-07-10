@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Edit Product</h1>
                <p class="text-muted mb-0">Update the product details.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Back to list</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.update', $product) }}" class="js-validate" novalidate enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required maxlength="255" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($product->image)
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="{{ asset($product->image) }}" alt="Product image" class="img-fluid rounded" style="max-height: 180px;">
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input id="image" name="image" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients</label>
                        <textarea id="ingredients" name="ingredients" class="form-control @error('ingredients') is-invalid @enderror" rows="3">{{ old('ingredients', $product->ingredients) }}</textarea>
                        @error('ingredients')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="benefits" class="form-label">Benefits</label>
                        <textarea id="benefits" name="benefits" class="form-control @error('benefits') is-invalid @enderror" rows="3">{{ old('benefits', $product->benefits) }}</textarea>
                        @error('benefits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="usage_instructions" class="form-label">Usage Instructions</label>
                        <textarea id="usage_instructions" name="usage_instructions" class="form-control @error('usage_instructions') is-invalid @enderror" rows="3">{{ old('usage_instructions', $product->usage_instructions) }}</textarea>
                        @error('usage_instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="safety_notes" class="form-label">Safety Notes</label>
                        <textarea id="safety_notes" name="safety_notes" class="form-control @error('safety_notes') is-invalid @enderror" rows="3">{{ old('safety_notes', $product->safety_notes) }}</textarea>
                        @error('safety_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input id="stock" name="stock" type="number" step="1" min="0" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control @error('stock') is-invalid @enderror">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input id="featured" name="featured" type="checkbox" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="form-check-input @error('featured') is-invalid @enderror">
                        <label class="form-check-label" for="featured">Featured product</label>
                        @error('featured')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
    </div>
@endsection
