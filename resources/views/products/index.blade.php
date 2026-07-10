@extends('layouts.frontend')

@section('content')
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item {{ empty(request()->query('category')) ? 'active' : '' }}">
                            <a href="{{ route('products.index') }}" class="text-decoration-none {{ empty(request()->query('category')) ? 'text-white' : '' }}">All Featured</a>
                        </li>
                        @foreach($categories as $cat)
                            <li class="list-group-item {{ (isset($selectedCategory) && $selectedCategory->id === $cat->id) ? 'active' : '' }}">
                                <a href="{{ route('products.index', ['category' => $cat->url]) }}" class="text-decoration-none {{ (isset($selectedCategory) && $selectedCategory->id === $cat->id) ? 'text-white' : '' }}">{{ $cat->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">@if(isset($selectedCategory)) {{ $selectedCategory->name }} @else Featured Products @endif</h1>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <a href="{{ route('products.show', $product->url) }}" class="text-decoration-none text-reset">
                            <div class="card product-card h-100">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted mb-2">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="mt-auto">
                                        <span class="fw-bold">@if($product->price) ₹ {{ number_format($product->price,2) }}@endif</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">No products found.</div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
