<div class="card h-100 shadow-sm border-0">
    @php
        $imgSrc = $product->image;
        if ($imgSrc && !Str::startsWith($imgSrc, 'http')) {
            $imgSrc = asset('storage/' . $imgSrc);
        } elseif (!$imgSrc) {
            $imgSrc = 'https://via.placeholder.com/400x500?text=No+Image';
        }

        // Keep existing dynamic logic for seeded data if it looks like a seed url (optional, but good for hybrid states)
        if (Str::contains($imgSrc, 'loremflickr') || Str::contains($imgSrc, 'picsum')) {
            // ... existing logic can be kept if desired, or just rely on what's in DB. 
            // For now, let's simplify to prioritize what's in DB as the user wants accurate display.
        }
    @endphp
    <a href="{{ route('products.show', $product->slug) }}">
        <img src="{{ $imgSrc }}" class="card-img-top" alt="{{ $product->name }}"
            style="height: 350px; object-fit: cover;">
    </a>
    <div class="card-body text-center bg-white">
        <h5 class="card-title fs-5 mb-2">
            <a href="{{ route('products.show', $product->slug) }}"
                class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h5>
        <p class="card-text text-gold fw-bold mb-3 fs-5">₹{{ number_format($product->price) }}</p>
        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100 rounded-0">View Details</a>
    </div>
</div>