@extends('layouts.frontend')

@section('content')
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card mb-3 mt-1">
        @if($product->image)
          <div class="d-flex justify-content-center mt-3">
            <img src="{{ asset($product->image) }}" class="img-fluid" alt="{{ $product->name }}" style="max-height:200px; max-width:200px; object-fit:cover;">
          </div>
        @endif
        <div class="card-body">
          <h1 class="h4">{{ $product->name }}</h1>
          @if($product->category)
            <p class="text-muted mb-2">Category: {{ $product->category->name }}</p>
          @endif
          @if($product->price)
            <p class="lead">Price: ₹ {{ number_format($product->price,2) }}</p>
          @endif

          <form method="POST" action="{{ route('cart.add') }}" class="d-flex align-items-center gap-2 mb-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div style="width:120px;">
              <input name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" class="form-control">
            </div>
            <button class="btn btn-primary">Add to cart</button>
          </form>

          @if($product->stock <= 0)
            <div class="alert alert-warning">Out of stock</div>
          @endif
          <p>{!! nl2br(e($product->description)) !!}</p>

          @if($product->ingredients)
            <h5>Ingredients</h5>
            <p>{!! nl2br(e($product->ingredients)) !!}</p>
          @endif

          @if($product->benefits)
            <h5>Benefits</h5>
            <p>{!! nl2br(e($product->benefits)) !!}</p>
          @endif

          @if($product->usage_instructions)
            <h5>Usage Instructions</h5>
            <p>{!! nl2br(e($product->usage_instructions)) !!}</p>
          @endif

          @if($product->safety_notes)
            <h5>Safety Notes</h5>
            <p class="text-danger">{!! nl2br(e($product->safety_notes)) !!}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
