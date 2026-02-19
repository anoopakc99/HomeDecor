@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="card max-w-4xl mx-auto">
        <div class="card-header border-b border-slate-100 p-6 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Order #{{ $order->order_number }}</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Back to List</a>
        </div>

        <div class="p-6">
            <!-- Status Update -->
            <!-- Status Update -->
            <div class="mb-8 p-4 bg-slate-50 rounded-lg border border-slate-200">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-md font-semibold text-slate-700">Update Order Status</h4>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status == 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status == 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                            {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="flex gap-4 items-center">
                    @csrf
                    @method('PUT')
                    <select name="status"
                        class="rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm p-2 border">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit"
                        class="bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition-colors text-sm font-medium">Update
                        Status</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Customer Details -->
                <div>
                    <h4 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Customer Information</h4>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium text-slate-600">Name:</span> {{ $order->customer_name }}</p>
                        <p><span class="font-medium text-slate-600">Email:</span> {{ $order->customer_email }}</p>
                        <p><span class="font-medium text-slate-600">Phone:</span> {{ $order->customer_phone }}</p>
                        <p><span class="font-medium text-slate-600">Address:</span> {{ $order->customer_address }}</p>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <h4 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Order Summary</h4>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium text-slate-600">Order Date:</span>
                            {{ $order->created_at->format('M d, Y H:i A') }}</p>
                        <p><span class="font-medium text-slate-600">Payment Method:</span> COD (Cash on Delivery)</p>
                        <p><span class="font-medium text-slate-600">Total Amount:</span> <span
                                class="text-lg font-bold text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div>
                <h4 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Order Items</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-4 py-3">Product</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Quantity</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium text-slate-900">
                                        <div class="flex items-center gap-3">
                                            @if($item->product && $item->product->image)
                                                @php
                                                    $image = $item->product->image;
                                                    if ($image && !Str::startsWith($image, 'http')) {
                                                        $image = asset('storage/' . $image);
                                                    }
                                                @endphp
                                                <img src="{{ $image }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="h-10 w-10 rounded object-cover border border-slate-200 order-item-thumbnail cursor-pointer"
                                                     data-full-image="{{ $image }}">
                                            @else
                                                <div class="h-10 w-10 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                                    N/A
                                                </div>
                                            @endif
                                            <span>{{ $item->product ? $item->product->name : 'Product Deleted' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">₹{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right font-semibold">
                                        ₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-slate-50 font-bold">
                                <td colspan="3" class="px-4 py-3 text-right">Grand Total</td>
                                <td class="px-4 py-3 text-right text-slate-900">
                                    ₹{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Popover -->
    <div id="order-item-popover" class="fixed z-50 hidden bg-white p-2 rounded-lg shadow-xl border border-slate-200 pointer-events-none" style="max-width: 300px;">
        <img src="" alt="Preview" class="w-full h-auto rounded">
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Image Preview Logic
        $(document).on('mouseenter', '.order-item-thumbnail', function(e) {
            const fullImageSrc = $(this).data('full-image');
            if (fullImageSrc) {
                const $popover = $('#order-item-popover');
                $popover.find('img').attr('src', fullImageSrc);
                $popover.removeClass('hidden');
            }
        });

        $(document).on('mousemove', '.order-item-thumbnail', function(e) {
            const $popover = $('#order-item-popover');
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

        $(document).on('mouseleave', '.order-item-thumbnail', function() {
            $('#order-item-popover').addClass('hidden');
        });
    });
</script>
@endpush