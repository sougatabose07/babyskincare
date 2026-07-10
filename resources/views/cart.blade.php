@extends('layouts.frontend')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="h4 mb-3">Your Cart</h1>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(empty($items))
                <p>Your cart is empty.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item['product']->name }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.update') }}" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <input name="quantity" type="number" min="0" value="{{ $item['quantity'] }}" class="form-control" style="width:100px;">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                                    </form>
                                </td>
                                <td>₹ {{ number_format($item['unit_price'],2) }}</td>
                                <td>₹ {{ number_format($item['line_total'],2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <button class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center">
                    <h4>Total: ₹ {{ number_format($total,2) }}</h4>
                    <div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Pay</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
