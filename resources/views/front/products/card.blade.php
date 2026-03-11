<div class="card h-100 border-0 product-card-styled">
    @php
        $imgSrc = $product->image;
        if ($imgSrc && !Str::startsWith($imgSrc, 'http')) {
            $imgSrc = asset('storage/' . $imgSrc);
        } elseif (!$imgSrc) {
            $imgSrc = 'https://via.placeholder.com/400x500?text=No+Image';
        }

        if (Str::contains($imgSrc, 'loremflickr') || Str::contains($imgSrc, 'picsum')) {
        }
    @endphp
    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none d-block">
        <img src="{{ $imgSrc }}" class="card-img-top" alt="{{ $product->name }}"
            style="height: 320px; object-fit: cover;">
        <div class="card-body px-1 py-2 bg-white">
            <h6 class="product-card-name mb-1">{{ $product->name }}</h6>
            <p class="product-card-price mb-0">₹{{ number_format($product->price, 2) }}</p>
        </div>
    </a>
</div>