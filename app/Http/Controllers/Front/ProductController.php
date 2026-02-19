<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category.parent');

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products = $query->paginate(9);
        $categories = \Illuminate\Support\Facades\Cache::remember('navbar_categories', 3600, function () {
            return Category::with('children')->whereNull('parent_id')->get();
        });

        return view('front.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category.parent', 'images'])->where('slug', $slug)->firstOrFail();

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('front.products.show', compact('product', 'related'));
    }
}
