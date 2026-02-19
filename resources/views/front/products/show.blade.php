@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 mb-4">
                <!-- Main Image -->
                <div class="mb-3 position-relative overflow-hidden">
                    @php
                        $mainImg = $product->image;
                        if ($mainImg && !Str::startsWith($mainImg, 'http')) {
                            $mainImg = asset('storage/' . $mainImg);
                        } elseif (!$mainImg) {
                            $mainImg = 'https://via.placeholder.com/500x500?text=No+Image';
                        }
                    @endphp
                    <img id="main-product-image" src="{{ $mainImg }}" class="img-fluid w-100 shadow-sm"
                        style="height: 500px; object-fit: cover;" alt="{{ $product->name }}">
                </div>

                <!-- Thumbnails -->
                @if($product->images->count() > 0)
                <div class="d-flex gap-2 overflow-auto pb-2">
                    <!-- Original Image Thumbnail -->
                    <img src="{{ $mainImg }}" class="img-thumbnail cursor-pointer active-thumb"
                        style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid var(--wood-accent);"
                        onclick="changeImage(this.src)">

                    <!-- Gallery Images -->
                    @foreach($product->images as $img)
                        @php 
                            $gallerySrc = $img->image;
                            if ($gallerySrc && !Str::startsWith($gallerySrc, 'http')) {
                                $gallerySrc = asset('storage/' . $gallerySrc);
                            }
                        @endphp
                        <img src="{{ $gallerySrc }}" class="img-thumbnail cursor-pointer"
                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; opacity: 0.7;"
                            onclick="changeImage(this.src)">
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <h1 class="display-4 fw-bold mb-2">{{ $product->name }}</h1>
                <p class="lead text-muted mb-4">Category: {{ $product->category ? $product->category->name : 'Uncategorized' }}</p>

                <h2 class="text-gold mb-4 display-5">₹{{ number_format($product->price) }}</h2>

                <div class="mb-5">
                    <h5 class="text-uppercase letter-spacing-1">Description</h5>
                    <p class="text-secondary">{{ $product->description }}</p>
                </div>

                <div class="mb-5">
                    <h5 class="text-uppercase letter-spacing-1">Specifications</h5>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-2"><strong>Dimensions:</strong> {{ $product->dimensions }}</li>
                        <li class="mb-2"><strong>Material:</strong> {{ $product->material }}</li>
                        <li class="mb-2"><strong>Warranty:</strong> 3 Years Manufacturing Warranty</li>
                    </ul>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center mb-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="me-3">
                        <label for="quantity" class="form-label visually-hidden">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="10"
                            class="form-control form-control-lg text-center border-dark rounded-0" style="width: 80px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1 rounded-0">ADD TO CART</button>
                </form>

                <div class="alert alert-success d-none rounded-0" id="add-success">Product added successfully!</div>
            </div>
        </div>

        @if($related->count() > 0)
            <div class="mt-5 pt-5 border-top">
                <h3 class="mb-5 text-center display-6">You Might Also Like</h3>
                <div class="row g-4">
                    @foreach($related as $rel)
                        <div class="col-md-3">
                            @include('front.products.card', ['product' => $rel])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection