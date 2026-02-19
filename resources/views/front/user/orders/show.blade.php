@extends('layouts.app')

@section('content')
    <div class="container py-5 my-5">
        <div class="row">
            <div class="col-md-12 mb-4">
                <a href="{{ route('user.orders') }}" class="text-decoration-none text-muted">&larr; Back to Orders</a>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Order #{{ $order->order_number }}</h5>
                        <span class="badge bg-{{ 
                                            $order->status == 'delivered' ? 'success' :
        ($order->status == 'cancelled' ? 'danger' :
            ($order->status == 'shipped' ? 'info' :
                ($order->status == 'processing' ? 'primary' : 'warning'))) 
                                        }} fs-6">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <small class="text-muted d-block">Order Date</small>
                                <strong>{{ $order->created_at->format('d M Y, h:i A') }}</strong>
                            </div>
                            <div>
                                <small class="text-muted d-block">Payment Method</small>
                                <strong class="text-uppercase">{{ $order->payment_method }}</strong>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Amount</small>
                                <strong>₹{{ number_format($order->total_amount) }}</strong>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">Items</h6>
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center mb-3">
                                @php
                                    $image = $item->product && $item->product->image ? $item->product->image : null;
                                    if ($image && !Str::startsWith($image, 'http')) {
                                        $image = asset('storage/' . $image);
                                    } elseif (!$image) {
                                        $image = 'https://placehold.co/80';
                                    }
                                @endphp
                                <img src="{{ $image }}" class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $item->product ? $item->product->name : 'Product Deleted' }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }} x
                                        ₹{{ number_format($item->price) }}</small>
                                </div>
                                <div class="text-end">
                                    <strong>₹{{ number_format($item->quantity * $item->price) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Tracking (Static Visual) -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Status</h5>
                        <div class="position-relative m-4">
                            @php
                                $width = '0%';
                                $step2Class = 'bg-white border'; // Default pending
                                $step2Icon = '';
                                $step3Class = 'bg-white border'; // Default pending
                                $step3Icon = '';
                                $progressBarClass = 'bg-success'; // Green progress bar

                                if ($order->status == 'processing') {
                                    $width = '25%';
                                    $progressBarClass = 'bg-primary progress-bar-striped progress-bar-animated';
                                } elseif ($order->status == 'shipped') {
                                    $width = '50%';
                                    $step2Class = 'btn-success';
                                    $step2Icon = '<i class="bi bi-check-lg"></i>';
                                } elseif ($order->status == 'delivered') {
                                    $width = '100%';
                                    $step2Class = 'btn-success';
                                    $step2Icon = '<i class="bi bi-check-lg"></i>';
                                    $step3Class = 'btn-success';
                                    $step3Icon = '<i class="bi bi-check-lg"></i>';
                                } elseif ($order->status == 'cancelled') {
                                    $width = '100%'; // Full width for cancelled
                                    $progressBarClass = 'bg-danger';
                                    $step2Class = 'btn-danger';
                                    $step2Icon = '<i class="bi bi-x-lg"></i>';
                                    $step3Class = 'btn-danger';
                                    $step3Icon = '<i class="bi bi-x-lg"></i>';
                                }
                            @endphp

                            @if($order->status == 'cancelled')
                                <div class="alert alert-danger text-center">This order has been cancelled.</div>
                            @else
                                <div class="progress" style="height: 3px;">
                                    <div class="progress-bar {{ $progressBarClass }}" role="progressbar"
                                        style="width: {{ $width }};" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <!-- Step 1: Ordered (Always Completed) -->
                                <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-success rounded-pill d-flex align-items-center justify-content-center"
                                    style="width: 2rem; height:2rem;">
                                    <i class="bi bi-check-lg"></i>
                                </div>

                                <!-- Step 2: Shipped -->
                                <div class="position-absolute top-0 start-50 translate-middle btn btn-sm {{ $step2Class }} rounded-pill d-flex align-items-center justify-content-center"
                                    style="width: 2rem; height:2rem;">
                                    {!! $step2Icon !!}
                                </div>

                                <!-- Step 3: Delivered -->
                                <div class="position-absolute top-0 start-100 translate-middle btn btn-sm {{ $step3Class }} rounded-pill d-flex align-items-center justify-content-center"
                                    style="width: 2rem; height:2rem;">
                                    {!! $step3Icon !!}
                                </div>

                                <div class="position-absolute top-100 start-0 translate-middle-x mt-2 text-center"
                                    style="width: 100px;">
                                    <small class="fw-bold">Ordered</small>
                                </div>
                                <div class="position-absolute top-100 start-50 translate-middle-x mt-2 text-center"
                                    style="width: 100px;">
                                    <small class="fw-bold">Shipped</small>
                                </div>
                                <div class="position-absolute top-100 start-100 translate-middle-x mt-2 text-center"
                                    style="width: 100px;">
                                    <small class="fw-bold">Delivered</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                        <p class="mb-1">{{ $order->customer_address }}</p>
                        <p class="mb-1">Phone: {{ $order->customer_phone }}</p>
                        <p class="mb-0">Email: {{ $order->customer_email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection