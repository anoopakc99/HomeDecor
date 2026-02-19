<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = \Illuminate\Support\Facades\Cache::remember('home_sliders', 3600, function () {
            return Slider::where('status', true)->orderBy('order')->get();
        });

        $categories = \Illuminate\Support\Facades\Cache::remember('home_categories', 3600, function () {
            return Category::whereNull('parent_id')
                ->orWhereIn('id', function ($query) {
                    $query->select('parent_id')->from('categories')->whereNotNull('parent_id');
                })->take(8)->get();
        });

        $bestsellers = \Illuminate\Support\Facades\Cache::remember('home_bestsellers', 3600, function () {
            return Product::with('category.parent')
                ->where('is_bestseller', true)
                ->take(4)
                ->get();
        });

        return view('front.home', compact('categories', 'bestsellers', 'sliders'));
    }
}
