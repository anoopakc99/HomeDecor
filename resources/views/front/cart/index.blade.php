@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Shopping Cart</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($cartItems->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px;">Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th style="width: 150px;">Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr data-id="{{ $item->product_id }}">
                                <td>
                                    @php
                                        $image = $item->product->image;
                                        if ($image && !Str::startsWith($image, 'http')) {
                                            $image = asset('storage/' . $image);
                                        } elseif (!$image) {
                                            $image = 'https://placehold.co/100x100?text=' . urlencode($item->product->name);
                                        }
                                    @endphp
                                    <img src="{{ $image }}" class="img-fluid rounded" style="max-height: 80px; width: auto;">
                                </td>
                                <td class="text-start fs-5">{{ $item->product->name }}</td>
                                <td>₹{{ number_format($item->product->price) }}</td>
                                <td>
                                    <input type="number" value="{{ $item->quantity }}" class="form-control text-center update-cart"
                                        min="1">
                                </td>
                                <td>₹{{ number_format($item->product->price * $item->quantity) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->product_id }}">
                                        <button class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg">Continue Shopping</a>
                </div>
                <div class="col-md-6 text-end">
                    <h3 class="mb-3">Total: ₹{{ number_format($total) }}</h3>
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg px-5">Proceed to Checkout</a>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Explore our handcrafted collection and find something potential.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Browse Products</a>
            </div>
        @endif
    </div>

    @section('scripts')
        <script>
            $(".update-cart").change(function (e) {
                e.preventDefault();
                var ele = $(this);
                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: "post",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.parents("tr").attr("data-id"),
                        quantity: ele.val()
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            });
        </script>
    @endsection

@endsection