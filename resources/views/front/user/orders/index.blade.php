@extends('layouts.app')

@section('content')
    <div class="container py-5 my-5">
        <div class="row">
            <!-- Sidebar Integration (simplified copy of dashboard sidebar for now, ideally component) -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2C241B&color=fff"
                                class="rounded-circle me-3" width="50" alt="Avatar">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('user.dashboard') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.dashboard') ? 'active' : '' }}">Dashboard</a>
                            <a href="{{ route('user.orders') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.orders*') ? 'active' : '' }}">My
                                Orders</a>
                            <a href="{{ route('user.helpline') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.helpline') ? 'active' : '' }}">Helpline</a>
                            <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button
                                    class="list-group-item list-group-item-action text-danger border-0 bg-transparent">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">My Orders</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($order->items->take(2) as $item)
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($item->product && $item->product->image)
                                                            @php
                                                                $image = $item->product->image;
                                                                if ($image && !Str::startsWith($image, 'http')) {
                                                                    $image = asset('storage/' . $image);
                                                                }
                                                            @endphp
                                                            <img src="{{ $image }}" alt="{{ $item->product->name }}"
                                                                class="rounded border user-product-thumbnail"
                                                                style="width: 32px; height: 32px; object-fit: cover; cursor: pointer;"
                                                                data-full-image="{{ $image }}">
                                                        @else
                                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center"
                                                                style="width: 32px; height: 32px;">
                                                                <small class="text-muted">N/A</small>
                                                            </div>
                                                        @endif
                                                        <small class="text-muted text-truncate" style="max-width: 120px;"
                                                            title="{{ $item->product ? $item->product->name : 'Product Deleted' }}">
                                                            {{ $item->product ? $item->product->name : 'Product Deleted' }}
                                                            <span class="text-xs">x{{ $item->quantity }}</span>
                                                        </small>
                                                    </div>
                                                @endforeach
                                                @if($order->items->count() > 2)
                                                    <small class="text-muted ps-4">+{{ $order->items->count() - 2 }} more</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>₹{{ number_format($order->total_amount) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                        <td>
                                            <a href="{{ route('user.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary">View</a>
                                            @if($order->status == 'delivered')
                                                <a href="{{ route('user.orders.invoice', $order->id) }}"
                                                    class="btn btn-sm btn-outline-dark ms-1"><i class="bi bi-download"></i>
                                                    Invoice</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Popover -->
    <div id="user-image-popover" class="position-fixed bg-white p-1 rounded shadow-lg border"
        style="display: none; z-index: 1050; max-width: 300px; pointer-events: none;">
        <img src="" class="img-fluid rounded w-100">
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Image Preview Logic
                $(document).on('mouseenter', '.user-product-thumbnail', function (e) {
                    const fullImageSrc = $(this).data('full-image');
                    if (fullImageSrc) {
                        const $popover = $('#user-image-popover');
                        $popover.find('img').attr('src', fullImageSrc);
                        $popover.show();
                    }
                });

                $(document).on('mousemove', '.user-product-thumbnail', function (e) {
                    const $popover = $('#user-image-popover');
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

                $(document).on('mouseleave', '.user-product-thumbnail', function () {
                    $('#user-image-popover').hide();
                });
            });
        </script>
    @endpush
@endsection