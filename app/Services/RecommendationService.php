<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    /**
     * Get smart product recommendations based on the current product.
     * Uses a weighted algorithm: Category Match > Price Proximity.
     * Caches results for high performance.
     *
     * @param Product $product
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedProducts(Product $product, int $limit = 4)
    {
        // Cache Key: unique for each product to ensure speed
        $cacheKey = 'related_products_' . $product->id . '_limit_' . $limit;

        return Cache::remember($cacheKey, 60 * 60, function () use ($product, $limit) { // Cache for 1 hour

            // 1. Basic Filtering: Same Category, Not Same Product
            $query = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id);


            // 2. Smart Price Matching (AI Logic)
            // Users are likely to buy items in a similar price range.
            // We sort by how close the price is to the current product's price,
            // ensuring the most price-similar items within the same category are prioritized.
            // SQL Logic: ABS(price - current_price) ASC
            $query->orderByRaw("ABS(price - {$product->price}) ASC");

            $recommendations = $query->take($limit)->get();

            // 3. Empty Result Fallback (If category has no other items or not enough matches)
            // Show Trending/Random products instead of empty space
            if ($recommendations->isEmpty()) {
                $recommendations = Product::where('id', '!=', $product->id)
                    ->inRandomOrder()
                    ->take($limit)
                    ->get();
            }

            return $recommendations;
        });
    }
}
