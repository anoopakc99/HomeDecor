<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left">Image</th>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Slug</th>
                <th class="px-6 py-3 text-left">Parent</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($categories as $category)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="h-10 w-10 rounded object-cover border border-slate-200 category-thumbnail cursor-pointer"
                                data-full-image="{{ asset('storage/' . $category->image) }}">
                        @else
                            <div
                                class="h-10 w-10 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                No Img</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-slate-500 text-sm">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-slate-500 text-sm">
                        @if($category->parent)
                            <span class="px-2 py-1 bg-slate-100 rounded text-xs">{{ $category->parent->name }}</span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block"
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
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mb-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="font-medium text-slate-600">No matching records found</p>
                            <p class="text-sm text-slate-400 mt-1">This category details not found in this list related.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($categories->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $categories->links() }}
    </div>
@endif