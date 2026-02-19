@extends('admin.layouts.app')

@section('title', 'Edit Slider')

@section('content')
    <div class="card max-w-2xl mx-auto">
        <div class="card-header border-b border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800">Edit Slider</h3>
            <a href="{{ route('admin.sliders.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Back to
                List</a>
        </div>

        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Slider Image</label>
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $slider->image) }}" alt="Current Slider"
                        class="h-32 w-full object-cover rounded-md border border-slate-200">
                </div>
                <input type="file" name="image" id="image"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                <p class="mt-1 text-xs text-slate-500">Upload to replace. Recommended: 1920x800px.</p>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Title (Optional)</label>
                <input type="text" name="title" id="title"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    value="{{ old('title', $slider->title) }}" placeholder="e.g. Summer Sale">
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-medium text-slate-700 mb-1">Subtitle (Optional)</label>
                <input type="text" name="subtitle" id="subtitle"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    value="{{ old('subtitle', $slider->subtitle) }}" placeholder="e.g. Up to 50% Off">
            </div>

            <div>
                <label for="link" class="block text-sm font-medium text-slate-700 mb-1">Button Link (Optional)</label>
                <input type="url" name="link" id="link"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    value="{{ old('link', $slider->link) }}" placeholder="https://...">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                    <input type="number" name="order" id="order"
                        class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                        value="{{ old('order', $slider->order) }}">
                </div>
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="status" id="status"
                        class="h-4 w-4 text-slate-600 focus:ring-slate-500 border-slate-300 rounded" value="1" {{ $slider->status ? 'checked' : '' }}>
                    <label for="status" class="ml-2 block text-sm text-slate-900">Active</label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition-colors font-medium">Update
                    Slider</button>
            </div>
        </form>
    </div>
@endsection