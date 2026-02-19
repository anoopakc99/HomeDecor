@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-slate-500 mt-1">Here's what's happening with your store today.</p>
        </div>
        <div class="hidden md:block">
            <span class="text-sm text-slate-500 bg-white px-3 py-1 rounded shadow-sm border border-slate-200">
                {{ now()->format('l, F j, Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Categories Card -->
        <div
            class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center transition-all hover:shadow-md hover:-translate-y-1">
            <div class="p-4 rounded-full bg-blue-50 text-blue-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Categories</p>
                <p class="text-3xl font-bold text-slate-900">{{ $categoriesCount }}</p>
            </div>
        </div>

        <!-- Products Card -->
        <div
            class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center transition-all hover:shadow-md hover:-translate-y-1">
            <div class="p-4 rounded-full bg-emerald-50 text-emerald-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Products</p>
                <p class="text-3xl font-bold text-slate-900">{{ $productsCount }}</p>
            </div>
        </div>

        <!-- Orders Card -->
        <div
            class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center transition-all hover:shadow-md hover:-translate-y-1">
            <div class="p-4 rounded-full bg-purple-50 text-purple-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Orders</p>
                <p class="text-3xl font-bold text-slate-900">{{ $ordersCount }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div
            class="border-b border-slate-100 p-6 flex flex-col md:flex-row justify-between items-center bg-slate-50/50 gap-4">
            <h3 class="text-lg font-bold text-slate-800">Recent Orders</h3>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <!-- Search Input -->
                <div class="relative w-full md:w-64">
                    <input type="text" id="dashboard-order-search"
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                        placeholder="Search order, name, phone...">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline whitespace-nowrap">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div id="dashboard-orders-container">
            @include('admin.dashboard.recent_orders')
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

            // Search Input Handling
            $('#dashboard-order-search').on('keyup', function () {
                clearTimeout(searchTimer);
                let query = $(this).val();

                searchTimer = setTimeout(function () {
                    fetchOrders(query, 1);
                }, 300); // Debounce 300ms
            });

            // Pagination Handling
            $(document).on('click', '#dashboard-orders-container .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let query = $('#dashboard-order-search').val();
                fetchOrders(query, page);
            });

            function fetchOrders(search, page) {
                $.ajax({
                    url: "{{ route('admin.dashboard.orders') }}",
                    type: "GET",
                    data: {
                        search: search,
                        page: page
                    },
                    success: function (response) {
                        $('#dashboard-orders-container').html(response);
                    },
                    error: function (xhr) {
                        console.error('Error fetching orders:', xhr);
                    }
                });
            }

            // Image Preview Logic
            $(document).on('mouseenter', '.order-product-thumbnail', function (e) {
                const fullImageSrc = $(this).data('full-image');
                if (fullImageSrc) {
                    const $popover = $('#image-preview-popover');
                    $popover.find('img').attr('src', fullImageSrc);
                    $popover.removeClass('hidden');
                }
            });

            $(document).on('mousemove', '.order-product-thumbnail', function (e) {
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

            $(document).on('mouseleave', '.order-product-thumbnail', function () {
                $('#image-preview-popover').addClass('hidden');
            });
        });
    </script>
@endpush