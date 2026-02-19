@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')
    <div class="card max-w-2xl mx-auto">
        <div class="card-header border-b border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800">Add New Category</h3>
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Back to
                List</a>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Category Name</label>
                <input type="text" name="name" id="name"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                    required value="{{ old('name') }}">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-slate-700 mb-1">Parent Category</label>
                <select name="parent_id" id="parent_id"
                    class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border">
                    <option value="">None (Top Level)</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Category Image</label>
                <input type="file" name="image" id="image" class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-slate-100 file:text-slate-700
                    hover:file:bg-slate-200">
                <p class="mt-1 text-xs text-slate-500">JPG, PNG, GIF up to 5MB.</p>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition-colors">Create
                    Category</button>
            </div>
        </form>
    </div>
@endsection