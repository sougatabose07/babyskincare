@extends('layouts.frontend')

@section('content')
    {{-- Banners carousel --}}
    @if(isset($banners) && $banners->isNotEmpty())
        <div id="homeCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($banners as $i => $banner)
                    <div class="carousel-item {{ $i==0 ? 'active' : '' }}">
                        <img src="{{ asset($banner->image) }}" class="d-block w-100" alt="banner" style="max-height: 300px; object-fit: cover;">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @endif

    {{-- Featured products --}}
    <section id="featured" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="small">View all</a>
        </div>
        <div class="row">
            @forelse($featuredProducts ?? [] as $product)
                <div class="col-6 col-md-3 mb-4">
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
                <div class="col-12">No featured products yet.</div>
            @endforelse
        </div>
    </section>

    {{-- Categories --}}
    <section id="categories" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Categories</h2>
        </div>
        <div class="row">
            @forelse($categories ?? [] as $category)
                <div class="col-6 col-md-3 mb-4">
                    <a href="{{ route('categories.show', $category->url) }}" class="text-decoration-none text-reset">
                        <div class="card category-card h-100 text-center p-2">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <h5 class="card-title mb-0">{{ $category->name }}</h5>
                                @if($category->description)
                                    <p class="small text-muted mt-2">{{ Str::limit($category->description, 60) }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">No categories found.</div>
            @endforelse
        </div>
    </section>

@endsection
