<div class="overflow-x-auto">
    <table class="w-full whitespace-nowrap">
        <thead class="bg-slate-50 text-left">
            <tr>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Products</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 text-sm font-medium text-slate-900">
                        <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-blue-600">
                            #{{ $order->order_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div
                                class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 mr-3">
                                {{ substr($order->customer_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-xs text-slate-500">{{ $order->customer_email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-2">
                            @foreach($order->items->take(2) as $item)
                                <div class="flex items-center gap-2">
                                    @if($item->product && $item->product->image)
                                        @php
                                            $image = $item->product->image;
                                            if ($image && !Str::startsWith($image, 'http')) {
                                                $image = asset('storage/' . $image);
                                            }
                                        @endphp
                                        <img src="{{ $image }}" 
                                             alt="{{ $item->product->name }}"
                                             class="h-8 w-8 rounded object-cover border border-slate-200 order-product-thumbnail cursor-pointer"
                                             data-full-image="{{ $image }}">
                                    @else
                                        <div class="h-8 w-8 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                            N/A
                                        </div>
                                    @endif
                                    <span class="text-sm text-slate-600 truncate max-w-[120px]" title="{{ $item->product ? $item->product->name : 'Product Deleted' }}">
                                        {{ $item->product ? $item->product->name : 'Product Deleted' }}
                                        <span class="text-xs text-slate-400">x{{ $item->quantity }}</span>
                                    </span>
                                </div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <span class="text-xs text-slate-500 pl-10">+{{ $order->items->count() - 2 }} more</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700">
                        ₹{{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded-full 
                                                    @if($order->status == 'completed' || $order->status == 'delivered') bg-green-100 text-green-800 
                                                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800 
                                                    @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 
                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="text-slate-600 hover:text-slate-800 transition-colors" title="View Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mb-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="font-medium text-slate-600">No matching records found</p>
                            <p class="text-sm text-slate-400 mt-1">This user details not found in this list related.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($recentOrders->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $recentOrders->links() }}
    </div>
@endif