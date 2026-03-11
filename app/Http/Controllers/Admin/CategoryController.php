<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return view('admin.categories.table', compact('categories'))->render();
        }

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
        ]);

        $data = $request->only(['name', 'parent_id']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = public_path('storage/categories');

            // Ensure directory exists
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            if (extension_loaded('gd')) {
                $filename = time() . '.webp';
                $fullPath = $path . '/' . $filename;

                $originalMemory = ini_get('memory_limit');
                ini_set('memory_limit', '512M');

                try {
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($image->getRealPath());
                    $img->scale(width: 800);

                    $img->toWebp(quality: 95)->save($fullPath);
                } catch (\Exception $e) {
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($path, $filename);
                } finally {
                    ini_set('memory_limit', $originalMemory);
                }
            } else {
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);
            }

            $data['image'] = 'categories/' . $filename;
        }

        Category::create($data);
        \Illuminate\Support\Facades\Cache::forget('home_categories');
        \Illuminate\Support\Facades\Cache::forget('navbar_categories');

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
        ]);

        $data = $request->only(['name', 'parent_id']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && file_exists(public_path('storage/' . $category->image))) {
                unlink(public_path('storage/' . $category->image));
            }

            $image = $request->file('image');
            $path = public_path('storage/categories');

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            if (extension_loaded('gd')) {
                $filename = time() . '.webp';
                $fullPath = $path . '/' . $filename;

                $originalMemory = ini_get('memory_limit');
                ini_set('memory_limit', '512M');

                try {
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($image->getRealPath());
                    $img->scale(width: 800);

                    $fileSize = $image->getSize();
                    $quality = 82;
                    if ($fileSize > 15 * 1024 * 1024) {
                        $quality = 75;
                    } elseif ($fileSize > 8 * 1024 * 1024) {
                        $quality = 78;
                    }

                    $img->toWebp(quality: $quality)->save($fullPath);
                } catch (\Exception $e) {
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $image->move($path, $filename);
                } finally {
                    ini_set('memory_limit', $originalMemory);
                }
            } else {
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);
            }

            $data['image'] = 'categories/' . $filename;
        }

        $category->update($data);
        \Illuminate\Support\Facades\Cache::forget('home_categories');
        \Illuminate\Support\Facades\Cache::forget('navbar_categories');

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->image && file_exists(public_path('storage/' . $category->image))) {
            unlink(public_path('storage/' . $category->image));
        }
        $category->delete();
        \Illuminate\Support\Facades\Cache::forget('home_categories');
        \Illuminate\Support\Facades\Cache::forget('navbar_categories');
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
