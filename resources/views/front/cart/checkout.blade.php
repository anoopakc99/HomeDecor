@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Checkout</h1>

        <div class="row">
            <div class="col-md-8">
                <div class="card p-4 shadow-sm">
                    <h4 class="mb-3">Shipping Details</h4>
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-lg"
                                value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="customer_email" class="form-control form-control-lg"
                                    value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="customer_phone" class="form-control form-control-lg"
                                    value="{{ Auth::user()->mobile }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delivery Address</label>
                            <textarea name="customer_address" rows="3" class="form-control form-control-lg"
                                required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">Place Order</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-4 bg-light">
                    <h4 class="mb-3">Order Summary</h4>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cartItems as $item)
                            <li class="list-group-item d-flex justify-content-between lh-sm bg-transparent">
                                <div>
                                    <h6 class="my-0">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                @php
                                    $image = $item->product->image;
                                    if ($image && !Str::startsWith($image, 'http')) {
                                        $image = asset('storage/' . $image);
                                    } elseif (!$image) {
                                        $image = 'https://placehold.co/50x50?text=' . urlencode($item->product->name);
                                    }
                                @endphp
                                <img src="{{ $image }}" class="rounded me-2"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                <span class="text-muted">₹{{ number_format($item->product->price * $item->quantity) }}</span>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between bg-transparent fw-bold fs-5">
                            <span>Total (INR)</span>
                            <span>₹{{ number_format($total) }}</span>
                        </li>
                    </ul>
                    <div class="alert alert-warning text-center">
                        <small>Cash on Delivery Available</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection