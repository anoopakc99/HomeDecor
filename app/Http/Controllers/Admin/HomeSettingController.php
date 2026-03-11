<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class HomeSettingController extends Controller
{
    public function index()
    {
        $settings = HomeSetting::allAsArray();
        return view('admin.home-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_heading' => 'nullable|string|max:100',
            'hero_subheading' => 'nullable|string|max:150',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'banner_title' => 'nullable|string|max:255',
            'banner_description' => 'nullable|string|max:500',
            'banner_button_link' => 'nullable|string|max:255',
            'story_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'story_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'story_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'story_image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:51200',
            'vocal_tagline' => 'nullable|string|max:255',
        ]);

        // Text fields
        $textFields = ['hero_heading', 'hero_subheading', 'banner_title', 'banner_description', 'banner_button_link', 'vocal_tagline'];
        foreach ($textFields as $field) {
            HomeSetting::set($field, $request->input($field));
        }

        // Image fields
        $imageFields = ['banner_image', 'story_image_1', 'story_image_2', 'story_image_3', 'story_image_4'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image
                $old = HomeSetting::getValue($field);
                if ($old && file_exists(public_path('storage/' . $old))) {
                    @unlink(public_path('storage/' . $old));
                }
                HomeSetting::set($field, $this->uploadImage($request->file($field), $field));
            }
        }

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('home_settings');

        return redirect()->route('admin.home-settings.index')->with('success', 'Home settings updated successfully.');
    }

    private function uploadImage($file, $field): string
    {
        $folder = 'home_settings';
        $path = public_path('storage/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (extension_loaded('gd')) {
            $filename = time() . '_' . $field . '.webp';
            $fullPath = $path . '/' . $filename;

            $originalMemory = ini_get('memory_limit');
            ini_set('memory_limit', '512M');

            try {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($file->getRealPath());
                $img->scale(width: 1920);

                $img->toWebp(quality: 95)->save($fullPath);
            } catch (\Exception $e) {
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move($path, $filename);
            } finally {
                ini_set('memory_limit', $originalMemory);
            }

            return $folder . '/' . $filename;
        } else {
            $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);
            return $folder . '/' . $filename;
        }
    }
}
