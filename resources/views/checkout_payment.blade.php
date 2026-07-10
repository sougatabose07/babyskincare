@extends('layouts.frontend')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="h4 mb-3">Complete Payment</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <p>Order #: {{ $order->id }}</p>
                    <p>Amount: ₹ {{ number_format($total,2) }}</p>
                    <p>Please complete the payment using Razorpay test checkout.</p>

                    <button id="rzp-button" class="btn btn-primary">Pay with Razorpay (test)</button>
                </div>
            </div>

            <form id="payment-form" method="POST" action="{{ route('orders.verify', $order) }}" style="display:none;">
                @csrf
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            const options = {
                "key": "{{ $razorpayKey }}",
                "amount": {{ (int) round($total * 100) }},
                "currency": "INR",
                "name": "{{ config('app.name') }}",
                "order_id": "{{ $razorpayOrderId }}",
                "handler": function (response){
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('payment-form').submit();
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            const rzp = new Razorpay(options);
            document.getElementById('rzp-button').onclick = function(e){
                rzp.open();
                e.preventDefault();
            }
        </script>
    @endpush
@endsection
