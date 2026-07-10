<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = session('cart', []);
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            if (!isset($products[$productId])) continue;
            $product = $products[$productId];
            $quantity = $item['quantity'];
            $unit = $product->price;
            $line = $unit * $quantity;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unit,
                'line_total' => $line,
            ];
            $total += $line;
        }

        return view('cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        $cart = session('cart', []);

        $current = $cart[$product->id]['quantity'] ?? 0;
        $new = $current + $quantity;

        if ($new > $product->stock) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cart[$product->id] = [
            'quantity' => $new,
            'price' => $product->price,
        ];

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Added to cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        $cart = session('cart', []);

        if ($quantity === 0) {
            unset($cart[$product->id]);
        } else {
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
            }
            $cart[$product->id] = ['quantity' => $quantity, 'price' => $product->price];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.show')->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => ['required', 'exists:products,id']]);

        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.show')->with('success', 'Item removed.');
    }
}
