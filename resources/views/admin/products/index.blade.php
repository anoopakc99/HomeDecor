@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    <div class="card">
        <div class="card-header flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4">
            <h3 class="text-lg font-bold text-slate-800">All Products</h3>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <input type="text" id="product-search"
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        placeholder="Search products...">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <a href="{{ route('admin.products.create') }}"
                    class="btn btn-primary bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded whitespace-nowrap">
                    +New Product
                </a>
            </div>
        </div>

        <div id="products-table-container">
            @include('admin.products.table')
        </div>
    </div>

    <!-- Image Preview Popover -->
    <div id="image-preview-popover"
        class="fixed z-50 hidden bg-white p-2 rounded-lg shadow-xl border border-slate-200 pointer-events-none"
        style="max-width: 300px;">
        <img src="" alt="Preview" class="w-full h-auto rounded">
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let searchTimer;

            // Image Preview Logic
            $(document).on('mouseenter', '.product-thumbnail', function (e) {
                const fullImageSrc = $(this).data('full-image');
                if (fullImageSrc) {
                    const $popover = $('#image-preview-popover');
                    $popover.find('img').attr('src', fullImageSrc);
                    $popover.removeClass('hidden');
                }
            });

            $(document).on('mousemove', '.product-thumbnail', function (e) {
                const $popover = $('#image-preview-popover');
                // Position to the right of the cursor
                let top = e.clientY + 10;
                let left = e.clientX + 20;

                // Check if it goes off screen (bottom)
                if (top + $popover.outerHeight() > $(window).height()) {
                    top = e.clientY - $popover.outerHeight() - 10;
                }

                // Check if it goes off screen (right)
                if (left + $popover.outerWidth() > $(window).width()) {
                    left = e.clientX - $popover.outerWidth() - 20;
                }

                $popover.css({
                    top: top + 'px',
                    left: left + 'px'
                });
            });

            $(document).on('mouseleave', '.product-thumbnail', function () {
                $('#image-preview-popover').addClass('hidden');
            });

            // Search Input Handling
            $('#product-search').on('keyup', function () {
                clearTimeout(searchTimer);
                let query = $(this).val();

                searchTimer = setTimeout(function () {
                    fetchProducts(query, 1);
                }, 300);
            });

            // Pagination Handling
            $(document).on('click', '#products-table-container .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let query = $('#product-search').val();
                fetchProducts(query, page);
            });

            function fetchProducts(search, page) {
                $.ajax({
                    url: "{{ route('admin.products.index') }}",
                    type: "GET",
                    data: {
                        search: search,
                        page: page
                    },
                    success: function (response) {
                        $('#products-table-container').html(response);
                    },
                    error: function (xhr) {
                        console.error('Error fetching products:', xhr);
                    }
                });
            }
        });
    </script>
@endpush