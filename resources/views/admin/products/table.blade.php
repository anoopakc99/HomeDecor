<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left">Image</th>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Category</th>
                <th class="px-6 py-3 text-left">Price</th>
                <th class="px-6 py-3 text-left">Bestseller</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($products as $product)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        @if($product->image)
                            @php
                                $image = $product->image;
                                if ($image && !Str::startsWith($image, 'http')) {
                                    $image = asset('storage/' . $image);
                                }
                            @endphp
                            <img src="{{ $image }}" alt="{{ $product->name }}"
                                class="h-12 w-12 rounded object-cover border border-slate-200 product-thumbnail cursor-pointer"
                                data-full-image="{{ $image }}">
                        @else
                            <div
                                class="h-12 w-12 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                No Img</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $product->name }}</div>
                        <div class="text-xs text-slate-500">{{ Str::limit($product->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $product->category ? $product->category->name : 'Uncategorized' }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-900">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($product->is_bestseller)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Yes</span>
                        @else
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded-full text-xs">No</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-red-800 text-sm font-medium bg-transparent border-0 cursor-pointer">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mb-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="font-medium text-slate-600">No matching records found</p>
                            <p class="text-sm text-slate-400 mt-1">This product details not found in this list related.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $products->links() }}
    </div>
@endif