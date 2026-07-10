@extends('layouts.frontend')

@section('content')
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card mb-3">
        <div class="card-body">
          <h1 class="h4">{{ $category->name }}</h1>
          @if($category->description)
            <p class="mb-0">{{ $category->description }}</p>
          @endif
          <hr>

          @if(isset($products) && $products->isNotEmpty())
            <div class="row">
              @foreach($products as $product)
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
                          <span class="fw-bold">@if($product->price) €{{ number_format($product->price,2) }}@endif</span>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>

            <div class="d-flex justify-content-center">
              {{ $products->links() }}
            </div>
          @else
            <p class="small text-muted">No products in this category yet.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
