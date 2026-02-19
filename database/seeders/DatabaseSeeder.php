<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([...]); // Not needed for now

        // Categories with Specific Images (Parents)
        $categories = [
            ['name' => 'Living Room', 'slug' => 'living-room', 'image' => 'https://loremflickr.com/800/600/living,room,furniture/all'],
            ['name' => 'Bedroom', 'slug' => 'bedroom', 'image' => 'https://loremflickr.com/800/600/bed,bedroom,furniture/all'],
            ['name' => 'Dining', 'slug' => 'dining', 'image' => 'https://loremflickr.com/800/600/dining,table,furniture/all'],
            ['name' => 'Cabinets', 'slug' => 'cabinets', 'image' => 'https://loremflickr.com/800/600/cabinet,shelf,furniture/all'],
        ];

        foreach ($categories as $category) {
            \DB::table('categories')->insert(array_merge($category, ['parent_id' => null, 'created_at' => now(), 'updated_at' => now()]));
        }

        // Subcategories
        $livingId = \DB::table('categories')->where('slug', 'living-room')->value('id');
        $bedId = \DB::table('categories')->where('slug', 'bedroom')->value('id');
        $diningId = \DB::table('categories')->where('slug', 'dining')->value('id');
        $cabinetsId = \DB::table('categories')->where('slug', 'cabinets')->value('id');

        $subcategories = [
            // Living Room
            ['name' => 'Sofas', 'slug' => 'sofas', 'parent_id' => $livingId, 'image' => null],
            ['name' => 'Coffee Tables', 'slug' => 'coffee-tables', 'parent_id' => $livingId, 'image' => null],
            ['name' => 'Chairs', 'slug' => 'chairs', 'parent_id' => $livingId, 'image' => null],
            // Dining
            ['name' => 'Dining Tables', 'slug' => 'dining-tables', 'parent_id' => $diningId, 'image' => null],
            ['name' => 'Dining Chairs', 'slug' => 'dining-chairs', 'parent_id' => $diningId, 'image' => null],
            // Bedroom
            ['name' => 'Beds', 'slug' => 'beds', 'parent_id' => $bedId, 'image' => null],
            ['name' => 'Wardrobes', 'slug' => 'wardrobes', 'parent_id' => $bedId, 'image' => null],
            // Cabinets
            ['name' => 'Bookshelves', 'slug' => 'bookshelves', 'parent_id' => $cabinetsId, 'image' => null],
        ];

        foreach ($subcategories as $sub) {
            \DB::table('categories')->insert(array_merge($sub, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Fetch Subcategory IDs for Products
        $sofaId = \DB::table('categories')->where('slug', 'sofas')->value('id');
        $coffeeTableId = \DB::table('categories')->where('slug', 'coffee-tables')->value('id');
        $chairId = \DB::table('categories')->where('slug', 'chairs')->value('id');
        $bedSubId = \DB::table('categories')->where('slug', 'beds')->value('id');
        $diningTableId = \DB::table('categories')->where('slug', 'dining-tables')->value('id');
        $bookshelfId = \DB::table('categories')->where('slug', 'bookshelves')->value('id');

        $products = [
            [
                'category_id' => $coffeeTableId,
                'name' => 'Teak Wood Coffee Table',
                'slug' => 'teak-wood-coffee-table',
                'price' => 12500.00,
                'description' => 'Handcrafted solid teak wood coffee table with natural finish.',
                'dimensions' => '120x60x45 cm',
                'material' => 'Teak Wood',
                'image' => 'https://loremflickr.com/400/500/wood,table/all',
                'is_bestseller' => true,
            ],
            [
                'category_id' => $chairId,
                'name' => 'Classic Rocking Chair',
                'slug' => 'classic-rocking-chair',
                'price' => 18000.00,
                'description' => 'Traditional rocking chair for relax and comfort.',
                'dimensions' => '60x90x110 cm',
                'material' => 'Sheesham Wood',
                'image' => 'https://loremflickr.com/400/500/chair,wood/all',
                'is_bestseller' => false,
            ],
            [
                'category_id' => $bedSubId,
                'name' => 'King Size Storage Bed',
                'slug' => 'king-size-storage-bed',
                'price' => 45000.00,
                'description' => 'Premium king size bed with hydraulic storage.',
                'dimensions' => '180x200 cm',
                'material' => 'Oak Wood',
                'image' => 'https://loremflickr.com/400/500/bed,frame,wood/all',
                'is_bestseller' => true,
            ],
            [
                'category_id' => $diningTableId,
                'name' => '6-Seater Dining Set',
                'slug' => '6-seater-dining-set',
                'price' => 55000.00,
                'description' => 'Elegant dining set with 6 cushioned chairs.',
                'dimensions' => '180x90x75 cm',
                'material' => 'Walnut Finish',
                'image' => 'https://loremflickr.com/400/500/dining,table,wood/all',
                'is_bestseller' => true,
            ],
            [
                'category_id' => $sofaId,
                'name' => 'Modern Fabric Sofa',
                'slug' => 'modern-fabric-sofa',
                'price' => 32000.00,
                'description' => 'Comfortable 3-seater fabric sofa in beige.',
                'dimensions' => '210x90x85 cm',
                'material' => 'Solid Wood & Fabric',
                'image' => 'https://loremflickr.com/400/500/sofa,interior/all',
                'is_bestseller' => true,
            ],
            [
                'category_id' => $bookshelfId,
                'name' => 'Vintage Bookshelf',
                'slug' => 'vintage-bookshelf',
                'price' => 24500.00,
                'description' => 'Open bookshelf with vintage finish and ample storage.',
                'dimensions' => '90x180x35 cm',
                'material' => 'Mango Wood',
                'image' => 'https://loremflickr.com/400/500/bookshelf,wood/all',
                'is_bestseller' => false,
            ],
        ];

        foreach ($products as $product) {
            $productId = \DB::table('products')->insertGetId(array_merge($product, ['created_at' => now(), 'updated_at' => now()]));

            // Seed multiple images for the product
            $keywords = ['furniture', 'wood', 'detail', 'interior'];
            for ($i = 0; $i < 4; $i++) {
                \DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image' => 'https://loremflickr.com/400/500/' . implode(',', $keywords) . '/all?random=' . rand(1, 1000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
