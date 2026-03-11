@extends('admin.layouts.app')

@section('title', 'Home Page Settings')

@section('content')
    <div class="card max-w-5xl mx-auto mt-6">
        <div class="card-header border-b border-slate-100 p-6 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">🏠 Home Page Settings</h3>
            <p class="text-sm text-slate-500">Manage what appears on the frontend home page</p>
        </div>

        <form action="{{ route('admin.home-settings.update') }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-10">
            @csrf
            @method('PUT')

            {{-- Hero Section --}}
            <div>
                <h4
                    class="text-sm font-semibold text-slate-700 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">
                    Hero Section</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hero Heading</label>
                        <input type="text" name="hero_heading" value="{{ $settings['hero_heading'] ?? 'CHAIR' }}"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            placeholder="e.g. CHAIR">
                        <p class="text-xs text-slate-400 mt-1">Large bold text shown in the hero banner.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hero Sub-heading</label>
                        <input type="text" name="hero_subheading"
                            value="{{ $settings['hero_subheading'] ?? 'LIMITED EDITION' }}"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            placeholder="e.g. LIMITED EDITION">
                        <p class="text-xs text-slate-400 mt-1">Smaller text above the heading.</p>
                    </div>
                </div>
            </div>

            {{-- Overlay Banner --}}
            <div>
                <h4
                    class="text-sm font-semibold text-slate-700 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">
                    Overlay Banner Section</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Banner Image</label>
                        @if(!empty($settings['banner_image']))
                            <img src="{{ asset('storage/' . $settings['banner_image']) }}"
                                class="h-28 w-full object-cover rounded-md mb-2 border border-slate-200" alt="Banner">
                        @endif
                        <input type="file" name="banner_image"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        <p class="text-xs text-slate-400 mt-1">Full-width background image for banner. Recommended:
                            1920×600px.</p>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Banner Title</label>
                            <input type="text" name="banner_title" value="{{ $settings['banner_title'] ?? '' }}"
                                class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                                placeholder="e.g. Shop our new collection of wooden tables">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Banner Description</label>
                            <textarea name="banner_description" rows="2"
                                class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                                placeholder="Short description text">{{ $settings['banner_description'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Banner Button Link</label>
                            <input type="text" name="banner_button_link"
                                value="{{ $settings['banner_button_link'] ?? '/products' }}"
                                class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                                placeholder="/products or full URL">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Our Story Images --}}
            <div>
                <h4
                    class="text-sm font-semibold text-slate-700 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">
                    Our Story Images (4 Photos)</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach([1, 2, 3, 4] as $i)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Story Image {{ $i }}</label>
                            @if(!empty($settings['story_image_' . $i]))
                                <img src="{{ asset('storage/' . $settings['story_image_' . $i]) }}"
                                    class="h-20 w-full object-cover rounded-md mb-2 border border-slate-200" alt="Story {{ $i }}">
                            @else
                                <div
                                    class="h-20 w-full bg-slate-100 rounded-md mb-2 flex items-center justify-center text-slate-400 text-xs">
                                    No image</div>
                            @endif
                            <input type="file" name="story_image_{{ $i }}"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-400 mt-2">Upload images for the "Our Story" grid section. Recommended: min
                    800px wide.</p>
            </div>

            {{-- Vocal For Local --}}
            <div>
                <h4
                    class="text-sm font-semibold text-slate-700 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">
                    Vocal For Local Banner</h4>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tagline Text</label>
                    <input type="text" name="vocal_tagline"
                        value="{{ $settings['vocal_tagline'] ?? 'Supporting Indian Craftsmanship & Artisans' }}"
                        class="w-full md:w-1/2 rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                        placeholder="e.g. Supporting Indian Craftsmanship & Artisans">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit"
                    class="bg-slate-800 text-white px-8 py-2.5 rounded-md hover:bg-slate-700 transition-colors font-medium">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection