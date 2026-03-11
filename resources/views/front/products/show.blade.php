@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <style>
        /* ===== PRODUCT DETAIL PAGE STYLES ===== */
        .product-page-wrap {
            background: #fff;
        }

        /* Breadcrumb */
        .product-breadcrumb {
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 1.5rem;
        }

        .product-breadcrumb a {
            color: #888;
            text-decoration: none;
        }

        .product-breadcrumb a:hover {
            color: #111;
        }

        /* Gallery layout */
        .gallery-col {
            display: flex;
            gap: 16px;
            height: 560px; /* Align heights */
        }

        .gallery-thumbs {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100px;
            flex-shrink: 0;
            height: 100%;
        }

        .gallery-thumb {
            width: 100%;
            flex: 1;
            min-height: 0;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.25s;
        }

        .gallery-thumb:hover,
        .gallery-thumb.active {
            border-color: #111;
        }

        .gallery-main {
            flex: 1;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .gallery-main:hover img {
            transform: scale(1.03);
        }

        /* Info column */
        .product-info-col {
            padding-left: 2rem;
        }

        .product-category-tag {
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 0.75rem;
        }

        .product-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #111;
            line-height: 1.2;
            margin-bottom: 1.25rem;
            letter-spacing: -0.5px;
        }

        .product-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: #111 !important;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }

        .product-divider {
            border: none;
            border-top: 1px solid #e5e5e5;
            margin: 1.5rem 0;
        }

        .product-section-label {
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .product-description {
            font-size: 0.95rem;
            color: #444;
            line-height: 1.75;
            margin-bottom: 0;
        }

        .product-spec-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .product-spec-list li {
            display: flex;
            gap: 10px;
            font-size: 0.88rem;
            color: #555;
            padding: 7px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-spec-list li:last-child {
            border-bottom: none;
        }

        .product-spec-list strong {
            color: #111;
            min-width: 90px;
        }

        /* Quantity + Cart */
        .qty-cart-wrap {
            display: flex;
            gap: 12px;
            align-items: stretch;
        }

        .qty-input {
            width: 70px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 0;
            font-size: 1rem;
            padding: 0.6rem 0;
        }

        .btn-add-cart {
            flex: 1;
            background: #111;
            color: #fff;
            border: 2px solid #111;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.85rem 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-add-cart:hover {
            background: #fff;
            color: #111;
        }

        /* Trust row */
        .trust-row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.78rem;
            color: #555;
            letter-spacing: 0.5px;
        }

        .trust-item svg {
            flex-shrink: 0;
            color: #111;
        }

        /* Similar products */
        .similar-section {
            background: #fafafa;
            padding: 70px 0;
            margin-top: 60px;
        }

        .similar-section h2 {
            text-align: center;
            font-size: 1.1rem;
            letter-spacing: 5px;
            text-transform: uppercase;
            font-weight: 400;
            color: #111;
            margin-bottom: 0.5rem;
        }

        .similar-section p {
            text-align: center;
            font-size: 0.85rem;
            color: #aaa;
            margin-bottom: 3rem;
        }

        .similar-card {
            background: #fff;
            text-align: center;
        }

        .similar-card-img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .similar-card-wrap:hover .similar-card-img {
            transform: scale(1.04);
        }

        .similar-card-img-wrap {
            overflow: hidden;
            aspect-ratio: 3/4;
            background: #f5f5f5;
        }

        .similar-card-wrap {
            display: block;
            text-decoration: none;
        }

        .similar-card-body {
            padding: 14px 4px 4px;
        }

        .similar-card-name {
            font-size: 0.88rem;
            color: #111;
            margin-bottom: 4px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .similar-card-price {
            font-size: 0.85rem;
            color: #111 !important;
            font-weight: 700;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .gallery-col {
                flex-direction: column-reverse;
                height: auto;
            }

            .gallery-thumbs {
                flex-direction: row;
                width: 100%;
                overflow-x: auto;
                height: 80px;
                gap: 10px;
            }

            .gallery-thumb {
                width: 65px;
                height: 65px;
                flex: none;
            }

            .gallery-main img {
                height: 320px;
            }

            .product-info-col {
                padding-left: 0;
                padding-top: 1.5rem;
            }

            .product-title {
                font-size: 1.6rem;
            }
        }
    </style>

    <div class="product-page-wrap">
        <div class="container py-5">
            <div class="row g-4 g-md-5">

                {{-- LEFT: Gallery --}}
                <div class="col-md-6">
                    @php
                        $mainImg = $product->image;
                        if ($mainImg && !Str::startsWith($mainImg, 'http')) {
                            $mainImg = asset('storage/' . $mainImg);
                        } elseif (!$mainImg) {
                            $mainImg = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80';
                        }
                        $hasGallery = $product->images->count() > 0;
                    @endphp
                    <div class="gallery-col">
                        {{-- Thumbnails (left strip) --}}
                        @if($hasGallery)
                            <div class="gallery-thumbs">
                                <img src="{{ $mainImg }}" class="gallery-thumb active"
                                    onclick="switchImage(this, '{{ $mainImg }}')"
                                    onerror="this.src='https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=200&q=80';">
                                @foreach($product->images as $img)
                                    @php
                                        $gSrc = $img->image;
                                        if ($gSrc && !Str::startsWith($gSrc, 'http')) {
                                            $gSrc = asset('storage/' . $gSrc);
                                        }
                                    @endphp
                                    <img src="{{ $gSrc }}" class="gallery-thumb" onclick="switchImage(this, '{{ $gSrc }}')"
                                        onerror="this.src='https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=200&q=80';">
                                @endforeach
                            </div>
                        @endif

                        {{-- Main image --}}
                        <div class="gallery-main">
                            <img id="main-product-image" src="{{ $mainImg }}" alt="{{ $product->name }}"
                                onerror="this.src='https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80';">
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Product Info --}}
                <div class="col-md-6 product-info-col">

                    {{-- Breadcrumb --}}
                    <div class="product-breadcrumb">
                        <a href="{{ route('home') }}">Home</a> &nbsp;/&nbsp;
                        @if($product->category)
                            <a href="{{ route('products.index') }}">{{ $product->category->name }}</a> &nbsp;/&nbsp;
                        @endif
                        <span style="color:#111;">{{ $product->name }}</span>
                    </div>

                    {{-- Category tag --}}
                    <div class="product-category-tag">
                        {{ $product->category ? $product->category->name : 'Furniture' }}
                    </div>

                    {{-- Title --}}
                    <h1 class="product-title">{{ $product->name }}</h1>

                    {{-- Price --}}
                    <div class="product-price">₹{{ number_format($product->price) }}</div>

                    <hr class="product-divider">

                    {{-- Description --}}
                    <div class="mb-4">
                        <div class="product-section-label">Description</div>
                        <p class="product-description">{{ $product->description }}</p>
                    </div>

                    <hr class="product-divider">

                    {{-- Specifications --}}
                    <div class="mb-4">
                        <div class="product-section-label">Specifications</div>
                        <ul class="product-spec-list">
                            @if($product->dimensions)
                                <li><strong>Dimensions</strong> {{ $product->dimensions }}</li>
                            @endif
                            @if($product->material)
                                <li><strong>Material</strong> {{ $product->material }}</li>
                            @endif
                            @if($product->warranty)
                                <li><strong>Warranty</strong> {{ $product->warranty }}</li>
                            @endif
                        </ul>
                    </div>

                    <hr class="product-divider">

                    {{-- Add to Cart --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <div class="qty-cart-wrap">
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10"
                                class="qty-input form-control">
                            <button type="submit" class="btn-add-cart">Add to Cart</button>
                        </div>
                    </form>

                    {{-- Trust icons --}}
                    <div class="trust-row">
                        <div class="trust-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524z" />
                            </svg>
                            Secure Checkout
                        </div>
                        <div class="trust-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12z" />
                            </svg>
                            Free Delivery
                        </div>
                        <div class="trust-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                <path
                                    d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0zm-5.655-.77A2 2 0 0 0 2.5 3.857l.01 1.04a.5.5 0 0 1-.145.356l-.75.73a2 2 0 0 0 0 2.854l.75.73a.5.5 0 0 1 .145.357l-.01 1.04a2 2 0 0 0 2.025 2.025l1.04-.01a.5.5 0 0 1 .356.145l.73.75a2 2 0 0 0 2.854 0l.73-.75a.5.5 0 0 1 .357-.145l1.04.01a2 2 0 0 0 2.025-2.025l-.01-1.04a.5.5 0 0 1 .145-.356l.75-.73a2 2 0 0 0 0-2.854l-.75-.73a.5.5 0 0 1-.145-.357l.01-1.04a2 2 0 0 0-2.025-2.025l-1.04.01a.5.5 0 0 1-.356-.145l-.73-.75a2 2 0 0 0-2.854 0l-.73.75a.5.5 0 0 1-.357.145z" />
                            </svg>
                            Quality Assured
                        </div>
                        <div class="trust-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10h14V4zM2 5h12v8H2z" />
                            </svg>
                            Easy Returns
                        </div>
                    </div>

                    <div class="alert alert-success d-none rounded-0 mt-3" id="add-success">Product added to cart!</div>
                </div>
            </div>
        </div>

        {{-- Similar Products --}}
        @if(isset($related) && $related->count() > 0)
            <div class="similar-section py-5">
                <div class="container-fluid px-3">
                    <h2>Similar Products</h2>
                    <p>Handpicked recommendations for your taste</p>
                    <div class="row g-2">
                        @foreach($related as $prod)
                            @php
                                $pImg = $prod->image;
                                if ($pImg && !Str::startsWith($pImg, 'http')) {
                                    $pImg = asset('storage/' . $pImg);
                                }
                                $pDummy = 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=600&q=80';
                                if (!$pImg) {
                                    $pImg = $pDummy;
                                }
                            @endphp
                            <div class="col-6 col-md-3">
                                <a href="{{ route('products.show', $prod->slug) }}" class="similar-card-wrap">
                                    <div class="similar-card-img-wrap">
                                        <img src="{{ $pImg }}" class="similar-card-img" alt="{{ $prod->name }}"
                                            onerror="this.onerror=null;this.src='{{ $pDummy }}';">
                                    </div>
                                    <div class="similar-card-body text-start">
                                        <div class="similar-card-name">{{ $prod->name }}</div>
                                        <div class="similar-card-price">₹{{ number_format($prod->price, 2) }}</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Dark Brand Story & Craftsmanship Banner --}}
    <div class="brand-story-banner">
        <div class="container text-center">
            
            <div class="row align-items-start justify-content-center">
                <div class="col-md-6 mb-5 mb-md-0 px-md-4">
                    <h4 class="brand-story-custom-title">How solid are we?</h4>
                    <p class="brand-story-custom-desc">We, at Lakkadhaara, take our work very seriously. Only the finest and most reliable go into the making of Lakkadhaara's exclusive products.</p>
                </div>
                <div class="col-md-6 px-md-4">
                    <h4 class="brand-story-custom-title">Generations Long Durability</h4>
                    <p class="brand-story-custom-desc">With its timeless appeal and robust construction, Lakkadhaara's Furniture can withstand the test of time, passing from one generation to the next.</p>
                </div>
            </div>

            <div class="brand-badges-row">
                <div class="badge-item">
                    <div class="badge-circle badge-color-1">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="badge-label">Artisan<br>Empowerment</div>
                </div>
                <div class="badge-item">
                    <div class="badge-circle badge-color-2">
                       <i class="bi bi-hammer"></i>
                    </div>
                    <div class="badge-label">Indian<br>Handcrafted</div>
                </div>
                <div class="badge-item">
                    <div class="badge-circle badge-color-3">
                        <i class="bi bi-tree"></i>
                    </div>
                    <div class="badge-label">Sustainable<br>Practices</div>
                </div>
                <div class="badge-item">
                    <div class="badge-circle badge-color-4">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div class="badge-label">Aesthetics &<br>Functionality</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Explore The Collections --}}
    <div class="container-fluid px-3 py-5">
        <h3 class="text-center mb-4" style="font-size:1.6rem; letter-spacing:1px; text-transform:uppercase;">Explore The Collections</h3>
        <div class="row g-2">
            <div class="col-6 col-md-3">
                <a href="{{ route('products.index') }}" class="similar-card-wrap text-decoration-none d-block">
                    <div class="similar-card-img-wrap">
                        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=600&q=80" alt="Dining Room" class="similar-card-img">
                    </div>
                    <div class="p-3 text-center">
                        <div class="text-dark" style="text-transform:uppercase; font-weight:600; letter-spacing:1px;">Dining Room</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('products.index') }}" class="similar-card-wrap text-decoration-none d-block">
                    <div class="similar-card-img-wrap">
                        <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=600&q=80" alt="Living Room" class="similar-card-img">
                    </div>
                    <div class="p-3 text-center">
                        <div class="text-dark" style="text-transform:uppercase; font-weight:600; letter-spacing:1px;">Living Room</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('products.index') }}" class="similar-card-wrap text-decoration-none d-block">
                    <div class="similar-card-img-wrap">
                        <img src="https://images.unsplash.com/photo-1540574163026-643ea20d25b5?auto=format&fit=crop&w=600&q=80" alt="Bedroom" class="similar-card-img">
                    </div>
                    <div class="p-3 text-center">
                        <div class="text-dark" style="text-transform:uppercase; font-weight:600; letter-spacing:1px;">Bedroom</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('products.index') }}" class="similar-card-wrap text-decoration-none d-block">
                    <div class="similar-card-img-wrap">
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=600&q=80" alt="Home Office" class="similar-card-img">
                    </div>
                    <div class="p-3 text-center">
                        <div class="text-dark" style="text-transform:uppercase; font-weight:600; letter-spacing:1px;">Home Office</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Guarantee Blocks (Light Gray Cards) --}}
    <div class="guarantee-section py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="guarantee-block">
                        <div class="guarantee-icon">
                            <i class="bi bi-patch-check" style="font-size: 3rem;"></i>
                        </div>
                        <div class="guarantee-title">Damage Covered</div>
                        <div class="guarantee-desc">Be assured. Your order is in safe hands. We provide replacement on damaged items.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="guarantee-block">
                        <div class="guarantee-icon">
                            <i class="bi bi-award" style="font-size: 3rem;"></i>
                        </div>
                        <div class="guarantee-title">100% Genuine Products</div>
                        <div class="guarantee-desc">We manufacture 100% Genuine <strong>Solid Wood</strong> Furniture because its durable and have long life.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="guarantee-block">
                        <div class="guarantee-icon">
                            <i class="bi bi-box2" style="font-size: 3rem;"></i>
                        </div>
                        <div class="guarantee-title">Free Delivery</div>
                        <div class="guarantee-desc">To provide you best shopping experience with us, We provide free delivery on every order we receive.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Reviews Minimal Section --}}
    @php
        $reviews = $product->reviews()->where('is_approved', true)->get();
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        
        $starCounts = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];
    @endphp

    <div class="reviews-section-minimal">
        <div class="container" style="max-width: 800px;">
            <div class="reviews-header-minimal d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                
                <div class="d-flex align-items-center mb-4 mb-md-0">
                    <div class="reviews-rating-number me-4">{{ number_format($averageRating, 1) }}</div>
                    
                    <div>
                        <div class="reviews-stars-group mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $averageRating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="bi bi-star-half text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="reviews-count-text">Based on {{ $totalReviews }} reviews</div>
                    </div>

                    <div class="reviews-bars-minimal ms-md-5 ms-4">
                        @foreach([5, 4, 3, 2, 1] as $star)
                        @php 
                            $count = $starCounts[$star];
                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="review-bar-row-minimal">
                            <span class="star-label">{{ $star }} Star{{ $star > 1 ? 's' : ' ' }}</span>
                            <div class="bar-container-minimal">
                                <div class="bar-fill-minimal" style="width: {{ $percentage }}%;"></div>
                            </div>
                            <span class="count-label">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-dark rounded-0 px-4 py-2 fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        WRITE A REVIEW
                    </button>
                </div>
            </div>

            {{-- Reviews List --}}
            @if($totalReviews > 0)
            <div class="reviews-list mt-5 border-top pt-4">
                @foreach($reviews as $review)
                <div class="review-card mb-4 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">{{ $review->user->name ?? 'Customer' }} <span class="text-success small ms-2"><i class="bi bi-check-circle-fill"></i> Verified</span></div>
                        <div class="text-muted small">{{ $review->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="text-warning small mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill {{ $i <= $review->rating ? '' : 'text-muted opacity-25' }}"></i>
                        @endfor
                    </div>
                    <p class="mb-0 text-muted" style="font-size: 0.95rem;">{{ $review->review }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-muted mt-5 pt-4 border-top">
                <p>No reviews yet. Be the first to share your thoughts!</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Review Modal --}}
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="reviewModalLabel">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @auth
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 0.9rem;">Rating</label>
                            <select name="rating" class="form-select rounded-0" required>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Good</option>
                                <option value="3">3 - Average</option>
                                <option value="2">2 - Poor</option>
                                <option value="1">1 - Terrible</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.9rem;">Your Review</label>
                            <textarea name="review" rows="4" class="form-control rounded-0" placeholder="Tell us what you think about this product..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-0 py-2 fw-bold">SUBMIT REVIEW</button>
                    </form>
                    @else
                    <div class="text-center py-4">
                        <p class="mb-3 text-muted">You must be logged in to leave a review.</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-0 px-4">Log In to Review</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <script>
        function switchImage(thumb, src) {
            document.getElementById('main-product-image').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }
    </script>

@endsection