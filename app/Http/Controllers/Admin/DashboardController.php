<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $productsCount = Product::count();
        $ordersCount = Order::count();

        // Initial load: 10 latest orders
        $recentOrders = Order::with('items.product')->latest()->paginate(10);

        return view('admin.dashboard', compact('categoriesCount', 'productsCount', 'ordersCount', 'recentOrders'));
    }

    public function getOrders(Request $request)
    {
        $query = Order::with('items.product');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $recentOrders = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return view('admin.dashboard.recent_orders', compact('recentOrders'))->render();
        }

        return view('admin.dashboard.recent_orders', compact('recentOrders'));
    }
}
