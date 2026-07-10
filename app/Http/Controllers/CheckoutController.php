<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $pid => $item) {
            if (!isset($products[$pid])) {
                return redirect()->route('cart.show')->with('error', 'Some products are no longer available.');
            }

            $product = $products[$pid];
            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart.show')->with('error', 'Insufficient stock for ' . $product->name);
            }

            $lineTotal = $product->price * $item['quantity'];
            $items[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'line_total' => $lineTotal,
            ];
            $total += $lineTotal;
        }

        return view('checkout', compact('items', 'total'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Cart is empty.');
        }

        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string'],
            'customer_city' => ['nullable', 'string', 'max:100'],
            'customer_state' => ['nullable', 'string', 'max:100'],
            'customer_postcode' => ['nullable', 'string', 'max:20'],
            'customer_country' => ['nullable', 'string', 'max:100'],
        ]);

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        foreach ($cart as $pid => $item) {
            if (!isset($products[$pid])) {
                return redirect()->route('cart.show')->with('error', 'Some products are no longer available.');
            }
            if ($products[$pid]->stock < $item['quantity']) {
                return redirect()->route('cart.show')->with('error', 'Insufficient stock for ' . $products[$pid]->name);
            }
            $total += $products[$pid]->price * $item['quantity'];
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'customer_city' => $request->customer_city,
            'customer_state' => $request->customer_state,
            'customer_postcode' => $request->customer_postcode,
            'customer_country' => $request->customer_country,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        $key = env('RAZORPAY_KEY');
        $secret = env('RAZORPAY_SECRET');

        $api = new Api($key, $secret);
        $razorpayOrder = $api->order->create([
            'amount' => (int) round($total * 100),
            'currency' => 'INR',
            'receipt' => 'order_' . $order->id,
            'payment_capture' => 1,
        ]);

        $razorpayOrderId = $razorpayOrder['id'] ?? null;
        $order->update(['razorpay_order_id' => $razorpayOrderId]);

        return view('checkout_payment', [
            'order' => $order,
            'razorpayOrderId' => $razorpayOrderId,
            'razorpayKey' => $key,
            'total' => $total,
        ]);
    }
}
