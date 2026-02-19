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

        // Category Filter (by slug)
        if ($request->has('category') && $request->category != '') {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                    ->orWhereHas('parent', function ($pq) use ($categorySlug) {
                        $pq->where('slug', $categorySlug);
                    });
            });
        }

        // Search Logic (Voice/Text)
        if ($request->has('search') && trim($request->search) != '') {
            $search = trim($request->search);
            $searchLower = strtolower($search);

            // Log for debugging (in storage/logs/laravel.log)
            \Illuminate\Support\Facades\Log::info('Search Query: ' . $searchLower);

            // 1. Text Parsing for Price (e.g., "under 5000", "below 2000")
            if (preg_match('/(under|below|less than)\s+(\d+)/i', $searchLower, $matches)) {
                $price = $matches[2];
                $query->where('price', '<=', $price);
                $term = trim(str_replace($matches[0], '', $searchLower));
            }
            // Regex to capture "above 5000" or "more than 5000"
            elseif (preg_match('/(above|more than|over)\s+(\d+)/i', $searchLower, $matches)) {
                $price = $matches[2];
                $query->where('price', '>=', $price);
                $term = trim(str_replace($matches[0], '', $searchLower));
            } else {
                $term = $searchLower;
            }

            // If there is a product name term left after price extraction
            if (!empty($term)) {
                // Split into individual words for broader matching
                $words = array_filter(explode(' ', $term));
                $query->where(function ($q) use ($term, $words) {
                    // Full phrase match first
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhereHas('category', function ($catQ) use ($term) {
                            $catQ->where('name', 'like', "%{$term}%");
                        });
                    // Individual word matches
                    foreach ($words as $word) {
                        if (strlen($word) > 2) { // skip very short words
                            $q->orWhere('name', 'like', "%{$word}%")
                                ->orWhere('description', 'like', "%{$word}%")
                                ->orWhereHas('category', function ($catQ) use ($word) {
                                    $catQ->where('name', 'like', "%{$word}%");
                                });
                        }
                    }
                });
            }
        }

        $products = $query->paginate(9);
        $categories = \Illuminate\Support\Facades\Cache::remember('navbar_categories', 3600, function () {
            return Category::with('children')->whereNull('parent_id')->get();
        });

        return view('front.products.index', compact('products', 'categories'));
    }

    public function show($slug, \App\Services\RecommendationService $recommendationService)
    {
        $product = Product::with(['category.parent', 'images'])->where('slug', $slug)->firstOrFail();

        // Use the AI Service for Recommendations
        $related = $recommendationService->getRelatedProducts($product);

        return view('front.products.show', compact('product', 'related'));
    }
}
