<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'order' => 'nullable|integer',
        ]);

        $data = $request->except(['image']);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        Slider::create($data);
        \Illuminate\Support\Facades\Cache::forget('home_sliders');

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'order' => 'nullable|integer',
        ]);

        $data = $request->except(['image']);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && file_exists(public_path('storage/' . $slider->image))) {
                @unlink(public_path('storage/' . $slider->image));
            }
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $slider->update($data);
        \Illuminate\Support\Facades\Cache::forget('home_sliders');

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image && file_exists(public_path('storage/' . $slider->image))) {
            @unlink(public_path('storage/' . $slider->image));
        }
        $slider->delete();
        \Illuminate\Support\Facades\Cache::forget('home_sliders');
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }

    private function uploadImage($file)
    {
        $path = public_path('storage/sliders');

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (extension_loaded('gd')) {
            $filename = time() . '_' . uniqid() . '.webp';
            $fullPath = $path . '/' . $filename;

            // Increase memory limit for large image processing
            $originalMemory = ini_get('memory_limit');
            ini_set('memory_limit', '512M');

            try {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($file->getRealPath());

                // Auto-resize: max width 1920px, maintain aspect ratio
                $img->scale(width: 1920);

                // Quality 95% — preserves original colors, brightness & sharpness perfectly
                // WebP format itself provides excellent compression even at 95%
                $img->toWebp(quality: 95)->save($fullPath);
            } catch (\Exception $e) {
                // Fallback: save original file if processing fails
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
                return 'sliders/' . $filename;
            } finally {
                ini_set('memory_limit', $originalMemory);
            }

            return 'sliders/' . $filename;
        } else {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);

            return 'sliders/' . $filename;
        }
    }
}
