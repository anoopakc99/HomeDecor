@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <style>
        /* Dark Theme Setup */
        body {
            background-color: #fafafa;
        }

        .hero-dark {
            background-color: #000;
            color: #fff;
            position: relative;
            overflow: hidden;
            height: 80vh;
            min-height: 600px;
        }

        .hero-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: none;
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
            padding: 0 20px;
        }

        /* Gold accents for decorative elements only */
        .text-gold {
            color: #111111 !important;
        }

        /* Prices are always black on dark sections */
        .arrival-card-price,
        .price-text {
            color: #fff !important;
            /* On dark bg: white; on light bg see next rule */
        }

        .philosophy-text {
            text-align: center;
            max-width: 800px;
            margin: 60px auto;
            font-size: 1.15rem;
            color: #555;
            line-height: 1.8;
        }

        .bg-black-section {
            background-color: #111;
            color: #fff;
            padding: 40px 0;
        }

        .section-title-dark {
            text-align: center;
            font-weight: 300;
            margin-bottom: 3rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #fff;
            font-size: 1.8rem;
        }

        .section-title-light {
            text-align: center;
            font-weight: 300;
            margin-bottom: 3rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #111;
            font-size: 1.8rem;
        }

        .btn-outline-light-custom {
            border: 1px solid #fff;
            color: #fff;
            padding: 12px 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: transparent;
            transition: 0.4s;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }

        .btn-outline-light-custom:hover {
            background: #fff;
            color: #000;
        }

        .btn-outline-dark-custom {
            border: 1px solid #111;
            color: #111;
            padding: 12px 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: transparent;
            transition: 0.4s;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
        }

        .btn-outline-dark-custom:hover {
            background: #111;
            color: #fff;
        }

        .product-grid-item {
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .product-grid-item img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-grid-item:hover img {
            transform: scale(1.05);
        }

        .product-info-dark {
            padding-top: 15px;
        }

        .product-info-dark h6 {
            color: #fff;
            font-weight: 400;
            letter-spacing: 1px;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .product-info-light {
            padding-top: 15px;
        }

        .product-info-light h6 {
            color: #111;
            font-weight: 400;
            letter-spacing: 1px;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .price-text {
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .overlay-banner {
            position: relative;
            height: 500px;
            margin: 80px 0;
        }

        .overlay-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay-box {
            background: #111;
            color: #fff;
            padding: 50px 40px;
            position: absolute;
            top: 50%;
            right: 15%;
            transform: translateY(-50%);
            max-width: 450px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .overlay-box {
                right: 5%;
                left: 5%;
                width: auto;
            }
        }

        .badge-icon {
            width: 60px;
            height: 60px;
            border: 1px solid #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: #ffffff;
            font-size: 1.5rem;
        }

        .vocal-local-banner {
            background-color: #f4f4f4;
            padding: 40px 0;
            text-align: center;
            margin: 60px 0;
        }

        .story-grid img {
            width: 100%;
            object-fit: cover;
            transition: 0.4s ease;
        }

        .story-grid img:hover {
            opacity: 0.9;
        }

        .large-card-img-wrap {
            position: relative;
            overflow: hidden;
            background: #1a1a1a;
            /* Creates a taller vertical card like 3:4 aspect ratio */
            aspect-ratio: 3/4;
        }

        .large-card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover !important;
            /* Ensure image covers the whole box */
            transition: transform 0.6s ease;
        }

        .product-grid-item:hover .large-card-img-wrap img {
            transform: scale(1.05);
        }

        .arrival-quick-view {
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .arrival-card-body {
            padding: 14px 4px 10px;
            background: #111;
        }

        .arrival-card-title {
            font-size: 0.88rem;
            font-weight: 400;
            color: #fff;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .arrival-card-link:hover .arrival-card-title {
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-color: #d4af37;
        }

        .arrival-card-price {
            font-size: 0.82rem;
            color: #d4af37;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }

        /* Section title override for New Arrivals light tone */
        .new-arrivals-section {
            background-color: #111;
            padding: 80px 0;
        }

        .new-arrivals-section h2 {
            text-align: center;
            font-weight: 300;
            margin-bottom: 3rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #fff;
            font-size: 1.8rem;
        }
    </style>

    <!-- Hero Section -->
    <div class="hero-dark">
        @if($sliders->count() > 0)
            <img src="{{ asset('storage/' . $sliders[0]->image) }}" class="hero-bg-img" alt="Hero"
                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80';">
        @else
            <!-- Fallback image mimicking the dark chair in the reference -->
            <img src="https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                class="hero-bg-img" style="object-position: center;" alt="Hero">
        @endif


    </div>

    <!-- Philosophy Section -->
    <div class="container philosophy-text px-4">
        <h4 class="fw-bold mb-4" style="color: #111; letter-spacing: 2px; text-transform: uppercase;">Our Philosophy</h4>
        <p>We believe that furniture should be more than just functional - it should be a reflection of your unique style
            and personality. That's why we meticulously craft each piece using only the finest materials and time-honored
            techniques.</p>
    </div>

    <!-- New Arrivals (Dark) -->
    <div class="bg-black-section">
        <div class="container-fluid px-3">
            <h2 class="section-title-dark">New Arrivals</h2>
            <div class="row g-2">
                @forelse($bestsellers->take(8) as $index => $product)
                    @php
                        $dummies = [
                            'https://images.unsplash.com/photo-1549187774-b4e9b0445b41?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1540932239986-30128078f3c5?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1592078615290-033ee584e267?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1506439773649-6e0eb8cfb237?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1579503841516-e0bd7fca5faa?auto=format&fit=crop&w=600&q=80'
                        ];
                        $dummy = $dummies[$index % count($dummies)];
                        $imgSrc = $product->image ? asset('storage/' . $product->image) : $dummy;
                    @endphp
                    <div class="col-6 col-md-3">
                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}"
                            class="text-decoration-none d-block">
                            <div class="large-card-img-wrap">
                                <img src="{{ $imgSrc }}" alt="{{ $product->name }}"
                                    onerror="this.onerror=null;this.src='{{ $dummy }}';">
                            </div>
                            <div class="product-info-dark mt-2 text-start">
                                <h6 class="text-white mb-1" style="font-size: 0.88rem; font-weight: 400;">
                                    {{ $product->name }}
                                </h6>
                                <p class="text-white mb-0" style="font-size: 0.95rem; font-weight: 700;">
                                    ₹{{ number_format($product->price, 2) }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-white">
                        <p>Loading new collections...</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Overlay Banner -->
    <div class="overlay-banner">
        @php
            $bannerImg = !empty($settings['banner_image'])
                ? asset('storage/' . $settings['banner_image'])
                : 'https://images.unsplash.com/photo-1604578762246-41134e37f9cc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&h=600&q=80';
            $bannerLink = $settings['banner_button_link'] ?? route('products.index');
        @endphp
        <img src="{{ $bannerImg }}" alt="Collection Banner">
        <div class="overlay-box">
            <h3 class="fw-light mb-4 text-white" style="letter-spacing: 1px; line-height: 1.4;">
                {{ $settings['banner_title'] ?? 'Shop our new collection of wooden tables' }}
            </h3>
            <p class="text-white mb-5" style="font-size: 0.95rem; opacity: 0.9;">
                {{ $settings['banner_description'] ?? 'Designed for modern living spaces to gather around.' }}
            </p>
            <a href="{{ $bannerLink }}" class="btn-outline-light-custom"
                style="background: #fff; color: #111; border-color: #fff;">Shop Now</a>
        </div>
    </div>



    <!-- Premium Handcrafted Collection (Dark) -->
    <div class="bg-black-section pt-5 pb-5">
        <div class="container-fluid px-3">
            <h2 class="section-title-dark" style="font-size: 1.5rem;">Premium Handcrafted Collection</h2>
            <div class="row g-2">
                @foreach($bestsellers->reverse()->take(4) as $product)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}"
                            class="text-decoration-none d-block">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1540932239986-30128078f3c5?auto=format&fit=crop&w=400&q=80' }}"
                                style="height: 380px; width: 100%; object-fit: cover;" alt="{{ $product->name }}"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1540932239986-30128078f3c5?auto=format&fit=crop&w=400&q=80';">
                            <div class="mt-2 text-start">
                                <h6 class="text-white mb-1" style="font-size: 0.88rem; font-weight: 400;">{{ $product->name }}
                                </h6>
                                <p class="text-white mb-0" style="font-size: 0.95rem; font-weight: 700;">
                                    ₹{{ number_format($product->price, 2) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Our Collection (Light) -->
    <div class="container-fluid px-3 py-5 my-5">
        <h2 class="section-title-light">Our Collection</h2>
        <div class="row g-2">
            @foreach($categories->take(8) as $category)
                <div class="col-6 col-md-3">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                        class="text-decoration-none d-block">
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=400&q=80' }}"
                            style="height: 380px; width: 100%; object-fit: cover;" alt="{{ $category->name }}"
                            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=400&q=80';">
                        <div class="mt-2 text-start">
                            <h6 class="mb-1" style="font-size: 0.88rem; font-weight: 400; color: #111;">{{ $category->name }}
                            </h6>
                            <p class="text-muted small mb-0">Explore ></p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>




    <!-- Story Grid -->
    <div class="container-fluid px-3 py-3 my-5">
        <h2 class="section-title-light">Our Story</h2>
        @php
            $story1 = !empty($settings['story_image_1']) ? asset('storage/' . $settings['story_image_1']) : 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=800&q=80';
            $story2 = !empty($settings['story_image_2']) ? asset('storage/' . $settings['story_image_2']) : 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80';
            $story3 = !empty($settings['story_image_3']) ? asset('storage/' . $settings['story_image_3']) : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=400&q=80';
            $story4 = !empty($settings['story_image_4']) ? asset('storage/' . $settings['story_image_4']) : 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=400&q=80';
        @endphp
        <div class="row g-3 story-grid">
            <div class="col-md-6">
                <img src="{{ $story1 }}" style="height: 620px;" alt="Story Image 1">
            </div>
            <div class="col-md-6">
                <div class="row g-3">
                    <div class="col-12">
                        <img src="{{ $story2 }}" style="height: 300px;" alt="Story Image 2">
                    </div>
                    <div class="col-6">
                        <img src="{{ $story3 }}" style="height: 300px;" alt="Story Image 3">
                    </div>
                    <div class="col-6">
                        <img src="{{ $story4 }}" style="height: 300px;" alt="Story Image 4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vocal For Local -->
    <div style="background: #f0efed; padding: 50px 0;">
        <div class="container-fluid px-5">
            <div class="row align-items-center">
                <!-- Left Content -->
                <div class="col-md-7 text-center text-md-start">
                    <p
                        style="font-size: 0.9rem; color: #666; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px; font-weight: 500;">
                        100% Swadesi Products
                    </p>
                    <h2
                        style="font-size: 2.8rem; font-weight: 800; color: #2196F3; letter-spacing: 1px; margin-bottom: 10px;">
                        #VocalForLocal
                    </h2>
                    <p style="font-size: 1.05rem; color: #555; margin-bottom: 15px; font-weight: 400;">
                        {{ $settings['vocal_tagline'] ?? 'Working for a self-reliant India since 2017' }}
                    </p>
                    <div
                        style="display: inline-flex; align-items: center; gap: 8px; background: #fff; padding: 8px 20px; border-radius: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#FF9933" viewBox="0 0 16 16">
                            <path
                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                        </svg>
                        <span style="font-size: 0.82rem; font-weight: 600; color: #333; letter-spacing: 1px;">MADE IN INDIA
                            WITH ❤️</span>
                    </div>
                </div>
                <!-- Right: Megaphone + Tricolor Text -->
                <div class="col-md-5 text-center mt-4 mt-md-0">
                    <div style="position: relative; display: inline-block;">
                        <!-- Megaphone Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="#444" viewBox="0 0 16 16"
                            style="margin-right: 15px;">
                            <path
                                d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1H4.488a1 1 0 0 1-.99-.89L3.094 11.5H2a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1.092l.403-2.71a1 1 0 0 1 .99-.89H5.51a1 1 0 0 1 .99 1.11L6.088 4.71C8.51 4.467 10.838 3.855 13 2.714zM14 3v10a.5.5 0 0 0 1 0V3a.5.5 0 0 0-1 0M2 5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1V5zm2-.09v6.18l-.398 2.67h1.106l.398-2.67V4.24l-.398-2.67H3.602zM6 10.5c2.4.239 4.69.822 6.855 2V3.5c-2.165 1.178-4.455 1.761-6.855 2z" />
                        </svg>
                        <!-- Tricolor VOCAL FOR LOCAL text -->
                        <div style="display: inline-block; vertical-align: middle;">
                            <div style="font-size: 2.5rem; font-weight: 900; line-height: 1.1; font-style: italic;">
                                <span style="color: #FF9933;">VOCAL</span>
                            </div>
                            <div style="font-size: 2.5rem; font-weight: 900; line-height: 1.1; font-style: italic;">
                                <span style="color: #333;">FOR</span>
                            </div>
                            <div style="font-size: 2.5rem; font-weight: 900; line-height: 1.1; font-style: italic;">
                                <span style="color: #138808;">LOCAL</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trust Badges (Dark) -->
    <div class="bg-black-section py-5 mb-0" style="margin-bottom: 0;">
        <div class="container px-4">
            <div class="row text-center g-4">
                <div class="col-6 col-md-3">
                    <div class="badge-icon"><i class="bi bi-tree"></i></div>
                    <h6 class="text-white text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Solid Wood</h6>
                    <p class="text-white small mb-0" style="opacity: 0.9;">Ethically Sourced Teak & Sheesham</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="badge-icon"><i class="bi bi-hand-index-thumb"></i></div>
                    <h6 class="text-white text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Handcrafted</h6>
                    <p class="text-white small mb-0" style="opacity: 0.9;">By Skilled Indian Artisans</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="badge-icon"><i class="bi bi-patch-check"></i></div>
                    <h6 class="text-white text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Quality Checked
                    </h6>
                    <p class="text-white small mb-0" style="opacity: 0.9;">50+ Quality Checks</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="badge-icon"><i class="bi bi-truck"></i></div>
                    <h6 class="text-white text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Secure Shipping
                    </h6>
                    <p class="text-white small mb-0" style="opacity: 0.9;">Damage Protection Guarantee</p>
                </div>
            </div>
        </div>
    </div>

@endsection