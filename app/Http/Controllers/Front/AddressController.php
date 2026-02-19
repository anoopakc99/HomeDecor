<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::id())->get();
        return view('front.user.addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_line' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        if ($request->is_default) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        UserAddress::create([
            'user_id' => Auth::id(),
            'address_line' => $request->address_line,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'is_default' => $request->has('is_default'),
        ]);

        return back()->with('success', 'Address added successfully.');
    }

    public function update(Request $request, $id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'address_line' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update([
            'address_line' => $request->address_line,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'is_default' => $request->has('is_default'),
        ]);

        return back()->with('success', 'Address updated successfully.');
    }

    public function destroy($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return back()->with('success', 'Address deleted successfully.');
    }
}
