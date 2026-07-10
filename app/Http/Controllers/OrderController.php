<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $paymentId = $request->input('razorpay_payment_id');
        $rpOrderId = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        if ($order->razorpay_order_id !== $rpOrderId) {
            return redirect()->route('cart.show')->with('error', 'Order mismatch.');
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $rpOrderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);
        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            return redirect()->route('cart.show')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Cart is empty.');
        }

        DB::beginTransaction();

        try {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $total = 0;
            foreach ($cart as $pid => $item) {
                if (!isset($products[$pid])) {
                    throw new \Exception('Product not available');
                }
                $product = $products[$pid];
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Insufficient stock for ' . $product->name);
                }
                $unit = $product->price;
                $line = $unit * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unit,
                    'total_price' => $line,
                ]);

                $product->decrement('stock', $item['quantity']);

                $total += $line;
            }

            $order->update(['status' => 'paid', 'razorpay_payment_id' => $paymentId, 'total_amount' => $total]);

            DB::commit();

            session()->forget('cart');

            return redirect()->route('products.index')->with('success', 'Payment successful, order placed.');
        } catch (\Exception $e) {
            DB::rollBack();
            $order->update(['status' => 'cancelled']);
            return redirect()->route('cart.show')->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
}
