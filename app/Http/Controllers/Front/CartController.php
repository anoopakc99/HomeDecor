<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your cart.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }

        // Transform for view compatibility if needed, or update view to use object
        return view('front.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['redirect' => route('login'), 'message' => 'Please login to add to cart.']);
            }
            return redirect()->route('login')->with('error', 'Please login to add items to cart.');
        }

        $product = Product::find($request->id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found!');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        $count = Cart::where('user_id', Auth::id())->count();

        if ($request->ajax()) {
            return response()->json(['message' => 'Product added to cart!', 'count' => $count]);
        }
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        if (Auth::check() && $request->id && $request->quantity) {
            // Note: $request->id here might be product_id coming from view, need to be careful.
            // But cart index view likely uses product_id as key in session implementation.
            // Let's assume view sends product_id for now, or update view logic.
            // Better to find by product_id and user_id.

            Cart::where('user_id', Auth::id())
                ->where('product_id', $request->id)
                ->update(['quantity' => $request->quantity]);

            session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if (Auth::check() && $request->id) {
            Cart::where('user_id', Auth::id())
                ->where('product_id', $request->id)
                ->delete();

            session()->flash('success', 'Product removed successfully');
        }
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }

        return view('front.cart.checkout', compact('cartItems', 'total'));
    }
}
