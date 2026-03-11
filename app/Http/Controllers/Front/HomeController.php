<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\HomeSetting;

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

        $bestsellers = \Illuminate\Support\Facades\Cache::remember('home_bestsellers', 300, function () {
            // Get bestsellers first; if fewer than 8, fill with latest products
            $bestsellers = Product::with('category.parent')
                ->where('is_bestseller', true)
                ->latest()
                ->take(8)
                ->get();

            if ($bestsellers->count() < 8) {
                $existingIds = $bestsellers->pluck('id')->toArray();
                $remaining = Product::with('category.parent')
                    ->whereNotIn('id', $existingIds)
                    ->latest()
                    ->take(8 - $bestsellers->count())
                    ->get();
                $bestsellers = $bestsellers->concat($remaining);
            }

            return $bestsellers;
        });

        $settings = \Illuminate\Support\Facades\Cache::remember('home_settings', 3600, function () {
            return HomeSetting::allAsArray();
        });

        return view('front.home', compact('categories', 'bestsellers', 'sliders', 'settings'));
    }
}
