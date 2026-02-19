@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <!-- Hero -->
    <!-- Hero Slider -->
    @if($sliders->count() > 0)
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <div class="position-relative" style="height: 600px;">
                            <img src="{{ asset('storage/' . $slider->image) }}" class="d-block w-100 h-100"
                                style="object-fit: cover;" alt="{{ $slider->title }}">
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.4);"></div>
                            <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                                @if($slider->subtitle)
                                    <h5 class="text-gold text-uppercase mb-3 animate__animated animate__fadeInDown">
                                        {{ $slider->subtitle }}</h5>
                                @endif
                                @if($slider->title)
                                    <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInUp">{{ $slider->title }}</h1>
                                @endif
                                @if($slider->link)
                                    <div>
                                        <a href="{{ $slider->link }}"
                                            class="btn btn-primary btn-lg px-5 animate__animated animate__fadeInUp"
                                            style="animation-delay: 0.3s;">Explore Collection</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @else
        <!-- Static Fallback Hero -->
        <section class="hero position-relative text-center">
            <div class="container">
                <h5 class="text-gold text-uppercase mb-3">Est. 1985</h5>
                <h1 class="display-2 fw-bold mb-4">Timeless Wooden Elegance</h1>
                <p class="lead mb-5 fs-4" style="max-width: 700px; margin: 0 auto; opacity: 0.9;">
                    Discover diverse collections of handcrafted furniture that bring warmth and character to your home.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-5">Explore Collection</a>
            </div>
        </section>
    @endif

    <!-- Vocal for Local Banner -->
    <section class="bg-dark-wood py-4 text-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8">
                    <span class="fs-5 text-gold me-3">★ VOCAL FOR LOCAL ★</span>
                    <span class="text-light">Supporting Indian Craftsmanship & Artisans.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Masonry Categories -->
    <section class="container py-5 my-5">
        <h2 class="section-title display-5 mb-5 text-center">Curated Collections</h2>

        <div class="row g-4">
            @foreach($categories as $category)
                @php
                    $catImg = $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/600x400?text=' . $category->name;
                @endphp
                <div class="col-md-6 mb-4">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                        class="text-decoration-none ajax-link">
                        <div class="position-relative overflow-hidden group rounded shadow-sm">
                            <img src="{{ $catImg }}" class="img-fluid w-100"
                                style="height: 400px; object-fit: cover; transition: transform 0.5s ease;"
                                alt="{{ $category->name }}">
                            <div class="position-absolute bottom-0 start-0 p-4 w-100"
                                style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <h3 class="text-white mb-0 fw-bold">{{ $category->name }}</h3>
                                <p class="text-gold mb-0">Explore Collection →</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- New Arrivals (Bestsellers) -->
    <section class="bg-white py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="display-5 mb-0 fw-bold">New Arrivals</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ajax-link text-uppercase"
                    style="letter-spacing: 1px;">View All</a>
            </div>

            <div class="row g-4">
                @foreach($bestsellers as $product)
                    <div class="col-md-3">
                        @include('front.products.card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="container py-5 my-4">
        <div class="row text-center g-5">
            <div class="col-md-3">
                <div class="mb-3">
                    <img src="https://placehold.co/80/2C241B/C6A87C?text=W" class="rounded-circle" alt="Icon">
                </div>
                <h5>Solid Wood</h5>
                <p class="text-muted small">Ethically Sourced Teak & Sheesham</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <img src="https://placehold.co/80/2C241B/C6A87C?text=H" class="rounded-circle" alt="Icon">
                </div>
                <h5>Handcrafted</h5>
                <p class="text-muted small">By Skilled Indian Artisans</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <img src="https://placehold.co/80/2C241B/C6A87C?text=Q" class="rounded-circle" alt="Icon">
                </div>
                <h5>Quality Checked</h5>
                <p class="text-muted small">50+ Quality Checks</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <img src="https://placehold.co/80/2C241B/C6A87C?text=S" class="rounded-circle" alt="Icon">
                </div>
                <h5>Secure Shipping</h5>
                <p class="text-muted small">Damage Protection Guarantee</p>
            </div>
        </div>
    </section>
@endsection