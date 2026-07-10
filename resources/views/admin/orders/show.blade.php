@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Order #{{ $order->id }}</h1>
                <p class="text-muted mb-0">Status: {{ ucfirst($order->status) }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Back to orders</a>
        </div>

        <div class="row gy-4">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Items</h5>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Deleted product' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₹ {{ number_format($item->unit_price, 2) }}</td>
                                            <td>₹ {{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Order summary</h5>
                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                        <p><strong>Customer:</strong> {{ $order->customer_name }} ({{ $order->customer_email }})</p>
                        <p><strong>Amount:</strong> ₹ {{ number_format($order->total_amount, 2) }}</p>
                        <p><strong>Created:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>

                        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Update status</label>
                                <select name="status" class="form-select">
                                    @foreach(['pending', 'paid', 'shipped', 'delivered', 'cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
