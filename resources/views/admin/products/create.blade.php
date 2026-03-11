@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
    <div class="card max-w-4xl mx-auto mt-6">
        <div class="card-header border-b border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800">Add New Product</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Back to
                List</a>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required value="{{ old('name') }}">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Price (₹)</label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required value="{{ old('price') }}">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-slate-700 mb-1">Dimensions
                            (Optional)</label>
                        <input type="text" name="dimensions" id="dimensions"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            value="{{ old('dimensions') }}" placeholder="e.g. 10x10x10 cm">
                    </div>

                    <div>
                        <label for="material" class="block text-sm font-medium text-slate-700 mb-1">Material
                            (Optional)</label>
                        <input type="text" name="material" id="material"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            value="{{ old('material') }}" placeholder="e.g. Wood, Metal">
                    </div>

                    <div>
                        <label for="warranty" class="block text-sm font-medium text-slate-700 mb-1">Warranty
                            (Optional)</label>
                        <input type="text" name="warranty" id="warranty"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            value="{{ old('warranty') }}" placeholder="e.g. 1 Year Warranty, Lifetime Warranty">
                    </div>

                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="is_bestseller" id="is_bestseller"
                            class="rounded border-slate-300 text-slate-600 shadow-sm focus:border-slate-500 focus:ring-slate-500 h-4 w-4"
                            value="1" {{ old('is_bestseller') ? 'checked' : '' }}>
                        <label for="is_bestseller" class="ml-2 block text-sm text-slate-700">Mark as Bestseller</label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required>{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Main Image</label>
                        <input type="file" name="image" id="image"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200"
                            required>
                        <p class="mt-1 text-xs text-slate-500">Main product image. Max 5MB.</p>
                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="gallery" class="block text-sm font-medium text-slate-700 mb-1">Gallery Images
                            (Optional)</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="gallery"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-slate-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm text-slate-500"><span class="font-semibold">Click to
                                            upload</span> or drag and drop</p>
                                    <p class="text-xs text-slate-500">SVG, PNG, JPG or GIF (MAX. 1200x1200px)</p>
                                </div>
                                <input id="gallery" name="gallery[]" type="file" class="hidden" multiple
                                    onchange="previewImages(event)" />
                            </label>
                        </div>
                        <div id="image-preview-container" class="mt-4 grid grid-cols-3 gap-4"></div>
                        @error('gallery.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-4 border-t border-slate-100">
                <button type="submit"
                    class="bg-slate-800 text-white px-6 py-2 rounded-md hover:bg-slate-700 transition-colors font-medium">Create
                    Product</button>
            </div>
        </form>
    </div>

    <script>
        function previewImages(event) {
            var container = document.getElementById("image-preview-container");
            container.innerHTML = "";
            var files = event.target.files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function (e) {
                    var imgContainer = document.createElement("div");
                    imgContainer.className = "relative group";

                    var img = document.createElement("img");
                    img.src = e.target.result;
                    img.className = "h-24 w-full object-cover rounded-lg border border-slate-200";

                    imgContainer.appendChild(img);
                    container.appendChild(imgContainer);
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection