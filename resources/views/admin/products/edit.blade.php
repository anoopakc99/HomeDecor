@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="card max-w-4xl mx-auto">
        <div class="card-header border-b border-slate-100 p-6 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Edit Product: {{ $product->name }}</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Back to
                List</a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required value="{{ old('name', $product->name) }}">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Price (₹)</label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required value="{{ old('price', $product->price) }}">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="dimensions" class="block text-sm font-medium text-slate-700 mb-1">Dimensions
                            (Optional)</label>
                        <input type="text" name="dimensions" id="dimensions"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            value="{{ old('dimensions', $product->dimensions) }}" placeholder="e.g. 10x10x10 cm">
                    </div>

                    <div>
                        <label for="material" class="block text-sm font-medium text-slate-700 mb-1">Material
                            (Optional)</label>
                        <input type="text" name="material" id="material"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            value="{{ old('material', $product->material) }}" placeholder="e.g. Wood, Metal">
                    </div>

                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="is_bestseller" id="is_bestseller"
                            class="rounded border-slate-300 text-slate-600 shadow-sm focus:border-slate-500 focus:ring-slate-500 h-4 w-4"
                            value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
                        <label for="is_bestseller" class="ml-2 block text-sm text-slate-700">Mark as Bestseller</label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border"
                            required>{{ old('description', $product->description) }}</textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Main Image</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Current Main Image"
                                    class="h-24 w-24 object-cover rounded border border-slate-200">
                            </div>
                        @endif
                        <input type="file" name="image" id="image"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                        <p class="mt-1 text-xs text-slate-500">Upload new image to replace current.</p>
                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gallery Images</label>

                        <!-- Existing Images -->
                        @if($product->images->count() > 0)
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 mb-2">Current Gallery Images:</p>
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach($product->images as $img)
                                        <div class="relative group cursor-pointer"
                                            onclick="document.getElementById('replace-{{ $img->id }}').click()">
                                            <img id="preview-{{ $img->id }}" src="{{ asset('storage/' . $img->image) }}"
                                                alt="Gallery Image"
                                                class="h-24 w-full object-cover rounded-lg border border-slate-200 transition-opacity group-hover:opacity-75">

                                            <!-- Overlay -->
                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                                <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">Click to
                                                    Replace</span>
                                            </div>

                                            <!-- Hidden Input -->
                                            <input type="file" id="replace-{{ $img->id }}" name="gallery_replace[{{ $img->id }}]"
                                                class="hidden" onchange="previewReplacement(event, 'preview-{{ $img->id }}')"
                                                onclick="event.stopPropagation()">

                                            <button type="button"
                                                onclick="event.stopPropagation(); deleteImage('{{ route('admin.products.image.destroy', $img->id) }}')"
                                                class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1 rounded-full hover:bg-red-600 shadow-sm transition-colors"
                                                title="Delete Image">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Upload -->
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
                                    <p class="text-xs text-slate-500">Add new images to gallery</p>
                                </div>
                                <input id="gallery" name="gallery[]" type="file" class="hidden" multiple
                                    onchange="previewImages(event)" />
                            </label>
                        </div>

                        <!-- Preview Container -->
                        <div id="image-preview-container" class="mt-4 grid grid-cols-3 gap-4"></div>
                        @error('gallery.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-4 border-t border-slate-100">
                <button type="submit"
                    class="bg-slate-800 text-white px-6 py-2 rounded-md hover:bg-slate-700 transition-colors font-medium">Update
                    Product</button>
            </div>
        </form>
    </div>

    <form id="delete-image-form" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deleteImage(url) {
            if (confirm('Delete this image?')) {
                var form = document.getElementById('delete-image-form');
                form.action = url;
                form.submit();
            }
        }

        function previewReplacement(event, previewId) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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