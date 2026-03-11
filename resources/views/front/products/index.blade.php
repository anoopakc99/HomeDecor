@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('content')
    <div class="container-fluid px-3 py-5">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3 mb-4">
                <h4 class="mb-3">Categories</h4>
                <div class="list-group">
                    <a href="{{ route('products.index') }}"
                        class="list-group-item list-group-item-action ajax-link {{ !request('category') ? 'active' : '' }}">All
                        Products</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                            class="list-group-item list-group-item-action ajax-link {{ request('category') == $cat->slug ? 'active' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-md-9">
                <h2 class="mb-4">
                    @if(request('search'))
                        Search results for: <em>"{{ request('search') }}"</em>
                    @elseif(request('category'))
                        {{ ucwords(str_replace('-', ' ', request('category'))) }}
                    @else
                        All Products
                    @endif
                </h2>

                @if($products->count() > 0)
                    <div class="row g-2">
                        @foreach($products as $product)
                            <div class="col-md-4">
                                @include('front.products.card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        @if(request('search'))
                            No products found for <strong>"{{ request('search') }}"</strong>. Try a different keyword.
                        @else
                            No products found in this category.
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection