<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\UserAddress;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        return view('front.user.dashboard', compact('user', 'recentOrders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        return back()->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->paginate(10);
        return view('front.user.orders.index', compact('orders'));
    }

    public function viewOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $id)->with('items.product')->firstOrFail();
        return view('front.user.orders.show', compact('order'));
    }

    public function helpline()
    {
        $tickets = \App\Models\SupportTicket::where('user_id', Auth::id())->latest()->get();
        return view('front.user.helpline', compact('tickets'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\SupportTicket::create([
            'user_id' => Auth::id(),
            'ticket_id' => 'TKT-' . strtoupper(Str::random(10)),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return back()->with('success', 'Your support ticket has been submitted successfully. We will contact you soon.');
    }
}
