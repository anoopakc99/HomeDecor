<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_address' => 'required',
            'customer_phone' => 'required',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to place an order.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Cart is empty!');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total_amount' => $total,
            'payment_method' => 'cod', 
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            if ($item->product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }
        }

        // Clear Cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    }

    public function success()
    {
        return view('front.cart.success');
    }
}
