@extends('layouts.app')

@section('title', 'Banner Images')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Homepage Banners</h1>
                <p class="text-muted mb-0">Upload banner images for the homepage.</p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="images" class="form-label">Banner images</label>
                        <input id="images" name="images[]" type="file" accept="image/*" multiple required class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror">
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">You can select multiple images at once.</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Banners</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Uploaded banners</h2>

                @if($banners->isEmpty())
                    <div class="text-center text-muted py-4">No banner images uploaded yet.</div>
                @else
                    <div class="row g-3">
                        @foreach($banners as $banner)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <img src="{{ asset($banner->image) }}" class="card-img-top" alt="Banner image">
                                    <div class="card-body">
                                        <p class="mb-2 text-muted">Order #{{ $banner->sort_order }}</p>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Delete this banner?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
