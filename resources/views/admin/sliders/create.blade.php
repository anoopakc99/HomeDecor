@extends('admin.layouts.app')

@section('title', 'Add Slider')

@section('content')
    <div class="card max-w-2xl mx-auto">
        <div class="card-header border-b border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800">Add New Slider</h3>
            <a href="{{ route('admin.sliders.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Back to
                List</a>
        </div>

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <div>
                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Slider Image <span
                        class="text-red-500">*</span></label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"
                            aria-hidden="true">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600">
                            <label for="image"
                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload a file</span>
                                <input id="image" name="image" type="file" class="sr-only" required>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-slate-500">
                            PNG, JPG, GIF up to 5MB. Recommended size: 1920x800px.
                        </p>
                    </div>
                </div>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title (Optional)</label>
                <input type="text" name="title" id="title"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    value="{{ old('title') }}" placeholder="e.g. Summer Sale">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-medium text-slate-700 mb-1">Subtitle (Optional)</label>
                <input type="text" name="subtitle" id="subtitle"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    value="{{ old('subtitle') }}" placeholder="e.g. Up to 50% Off">
            </div>

            <div>
                <label for="link" class="block text-sm font-medium text-slate-700 mb-1">Button Link (Optional)</label>
                <input type="url" name="link" id="link"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    value="{{ old('link') }}" placeholder="https://...">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="order" id="order"
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                        value="{{ old('order', 0) }}">
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="status" id="status"
                        class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-slate-300 rounded" value="1" checked>
                    <label for="status" class="ml-2 block text-sm text-slate-900">Active</label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition-colors font-medium">Create
                    Slider</button>
            </div>
        </form>
    </div>
@endsection