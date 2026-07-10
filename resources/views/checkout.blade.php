@extends('layouts.frontend')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="h4 mb-3">Checkout</h1>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Order summary</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $item['product']->name }}</strong><br>
                                    Qty: {{ $item['quantity'] }}
                                </div>
                                <span>₹ {{ number_format($item['line_total'], 2) }}</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Total</strong>
                            <strong>₹ {{ number_format($total, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Shipping & contact details</h5>

                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="customer_name">Name</label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" class="form-control @error('customer_name') is-invalid @enderror" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="customer_email">Email</label>
                            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" class="form-control @error('customer_email') is-invalid @enderror" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="customer_phone">Phone</label>
                            <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone') }}" class="form-control @error('customer_phone') is-invalid @enderror" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="customer_address">Address</label>
                            <textarea id="customer_address" name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" rows="3" required>{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="customer_city">City</label>
                                <input id="customer_city" name="customer_city" type="text" value="{{ old('customer_city') }}" class="form-control @error('customer_city') is-invalid @enderror">
                                @error('customer_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="customer_state">State</label>
                                <input id="customer_state" name="customer_state" type="text" value="{{ old('customer_state') }}" class="form-control @error('customer_state') is-invalid @enderror">
                                @error('customer_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label" for="customer_postcode">Postcode</label>
                                <input id="customer_postcode" name="customer_postcode" type="text" value="{{ old('customer_postcode') }}" class="form-control @error('customer_postcode') is-invalid @enderror">
                                @error('customer_postcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="customer_country">Country</label>
                                <input id="customer_country" name="customer_country" type="text" value="{{ old('customer_country') }}" class="form-control @error('customer_country') is-invalid @enderror">
                                @error('customer_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Continue to payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
