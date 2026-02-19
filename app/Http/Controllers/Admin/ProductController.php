<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return view('admin.products.table', compact('products'))->render();
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $request->except(['image', 'gallery']);
        $data['slug'] = Str::slug($request->name);
        $data['is_bestseller'] = $request->has('is_bestseller');

        // Main Image
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        $product = Product::create($data);

        // Gallery Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $this->uploadImage($file, 'products/gallery');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path
                ]);
            }
        }

        \Illuminate\Support\Facades\Cache::forget('home_bestsellers');

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_replace.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $request->except(['image', 'gallery', 'gallery_replace']);
        $data['slug'] = Str::slug($request->name);
        $data['is_bestseller'] = $request->has('is_bestseller');

        if ($request->hasFile('image')) {
            // Delete old main image
            if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                @unlink(public_path('storage/' . $product->image));
            }
            $data['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        $product->update($data);

        // Handle new gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $this->uploadImage($file, 'products/gallery');
                $product->images()->create(['image' => $path]);
            }
        }

        // Handle existing gallery image replacements
        if ($request->hasFile('gallery_replace')) {
            foreach ($request->file('gallery_replace') as $id => $file) {
                $productImage = \App\Models\ProductImage::find($id);
                if ($productImage && $productImage->product_id == $product->id) {
                    // Delete old image file
                    if (file_exists(public_path('storage/' . $productImage->image))) {
                        @unlink(public_path('storage/' . $productImage->image));
                    }
                    // Upload new and update record
                    $path = $this->uploadImage($file, 'products/gallery');
                    $productImage->update(['image' => $path]);
                }
            }
        }

        \Illuminate\Support\Facades\Cache::forget('home_bestsellers');

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete main image
        if ($product->image && file_exists(public_path('storage/' . $product->image))) {
            @unlink(public_path('storage/' . $product->image));
        }

        // Delete gallery images
        foreach ($product->images as $img) {
            if ($img->image && file_exists(public_path('storage/' . $img->image))) {
                @unlink(public_path('storage/' . $img->image));
            }
            $img->delete();
        }

        $product->delete();
        \Illuminate\Support\Facades\Cache::forget('home_bestsellers');

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function uploadImage($file, $directory)
    {
        $path = public_path('storage/' . $directory);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (extension_loaded('gd')) {
            // Generate a unique filename with .webp extension
            $filename = time() . '_' . uniqid() . '.webp';
            $fullPath = $path . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $img = $manager->read($file->getRealPath());

            // High Quality Resize logic tailored for e-commerce
            // 1200px is good for zoom capabilities while keeping file size low
            $img->scaleDown(width: 1200);

            // Encode to WebP with 80% quality (best balance)
            $img->toWebp(quality: 80)->save($fullPath);
        } else {
            // Fallback: Use original extension and just move the file
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);
        }

        return $directory . '/' . $filename;
    }

    public function destroyImage(ProductImage $productImage)
    {
        if (file_exists(public_path('storage/' . $productImage->image))) {
            @unlink(public_path('storage/' . $productImage->image));
        }
        $productImage->delete();
        return back()->with('success', 'Image deleted successfully.');
    }
}
