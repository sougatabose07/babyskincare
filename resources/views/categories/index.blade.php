@extends('layouts.frontend')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Categories</h1>
    </div>

    <div class="row">
        @forelse($categories as $category)
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
@endsection
