@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="card">
        <div class="card-header flex justify-between items-center p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">All Orders</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Order ID</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Products</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $order->customer_name }}</div>
                                <div class="text-xs text-slate-500">{{ $order->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="flex items-center gap-2">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="h-8 w-8 rounded object-cover border border-slate-200 order-product-thumbnail cursor-pointer"
                                                     data-full-image="{{ asset('storage/' . $item->product->image) }}">
                                            @else
                                                <div class="h-8 w-8 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                                    N/A
                                                </div>
                                            @endif
                                            <span class="text-sm text-slate-600 truncate max-w-[150px]" title="{{ $item->product ? $item->product->name : 'Product Deleted' }}">
                                                {{ $item->product ? $item->product->name : 'Product Deleted' }}
                                                <span class="text-xs text-slate-400">x{{ $item->quantity }}</span>
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <span class="text-xs text-slate-500 pl-10">+{{ $order->items->count() - 3 }} more items</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900">₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if($order->status == 'completed' || $order->status == 'delivered') bg-green-100 text-green-800 
                                                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800 
                                                            @elseif($order->status == 'shipped') bg-blue-100 text-blue-800 
                                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800 
                                                            @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="text-blue-600 hover:text-blue-800 transition-colors" title="View Details">
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
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- Image Preview Popover -->
    <div id="image-preview-popover" class="fixed z-50 hidden bg-white p-2 rounded-lg shadow-xl border border-slate-200 pointer-events-none" style="max-width: 300px;">
        <img src="" alt="Preview" class="w-full h-auto rounded">
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Image Preview Logic
        $(document).on('mouseenter', '.order-product-thumbnail', function(e) {
            const fullImageSrc = $(this).data('full-image');
            if (fullImageSrc) {
                const $popover = $('#image-preview-popover');
                $popover.find('img').attr('src', fullImageSrc);
                $popover.removeClass('hidden');
            }
        });

        $(document).on('mousemove', '.order-product-thumbnail', function(e) {
            const $popover = $('#image-preview-popover');
            // Position to the right of the cursor
            let top = e.clientY + 10;
            let left = e.clientX + 20;

            // Check if it goes off screen (bottom)
            if (top + $popover.outerHeight() > $(window).height()) {
                top = e.clientY - $popover.outerHeight() - 10;
            }
            
            // Check if it goes off screen (right)
            if (left + $popover.outerWidth() > $(window).width()) {
                left = e.clientX - $popover.outerWidth() - 20;
            }

            $popover.css({
                top: top + 'px',
                left: left + 'px'
            });
        });

        $(document).on('mouseleave', '.order-product-thumbnail', function() {
            $('#image-preview-popover').addClass('hidden');
        });
    });
</script>
@endpush