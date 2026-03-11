<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Home Decor</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- Tailwind via CDN for quick utilities if needed, but primary is custom CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Override Tailwind reset/base where necessary or use my custom css primarily */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop"
        class="fixed inset-0 bg-slate-900 bg-opacity-50 z-40 hidden transition-opacity duration-300 md:hidden"
        onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white text-slate-800 transition-transform duration-300 transform -translate-x-full md:translate-x-0 shadow-xl border-r border-slate-200">
        <div class="h-16 flex items-center justify-center border-b border-slate-100 bg-white px-4">
            <img src="{{ asset('images/docor.logonew.jpeg') }}" alt="Admin Panel" class="h-10 w-auto object-contain">
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Categories
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Products
            </a>

            <a href="{{ route('admin.sliders.index') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.sliders.*') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Sliders
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Orders
            </a>

            <a href="{{ route('admin.support.index') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.support.*') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414" />
                </svg>
                Support Tickets
            </a>

            <a href="{{ route('admin.home-settings.index') }}"
                class="flex items-center px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg transition-colors {{ request()->routeIs('admin.home-settings.*') ? 'bg-slate-100 text-slate-900 font-semibold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Home Settings
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100 bg-white">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-4 py-2 text-slate-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-3 group-hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div id="main-content-wrapper" class="flex flex-col min-h-screen transition-all duration-300 md:ml-64">
        <!-- Top Header -->
        <header
            class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sticky top-0 z-50 border-b border-slate-100 flex-shrink-0">
            <div class="flex items-center space-x-4">
                <!-- Mobile & Desktop Sidebar Toggle -->
                <button onclick="toggleSidebar()"
                    class="text-slate-500 hover:text-slate-800 focus:outline-none p-2 rounded-md hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold text-slate-800 hidden sm:block">@yield('title', 'Admin Dashboard')</h2>
            </div>

            <h2 class="text-lg font-semibold text-slate-800 sm:hidden">@yield('title', 'Admin')</h2>

            <div class="flex items-center space-x-2 sm:space-x-4">
                <a href="{{ url('/') }}" target="_blank" title="Go to Website"
                    class="flex items-center space-x-2 px-3 py-2 text-sm text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-md transition-colors">
                    <span class="hidden sm:inline">Site Home</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>

                <!-- User Dropdown -->
                <div class="relative group">
                    <button onclick="toggleDropdown()"
                        class="flex items-center space-x-2 cursor-pointer p-1 rounded-full hover:bg-slate-50 focus:outline-none">
                        <div
                            class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-slate-700 hidden md:block">{{ Auth::user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-slate-100 hidden transform origin-top-right transition-all z-50">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-sm text-slate-500">Signed in as</p>
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 bg-slate-50 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-sm relative animate-fade-in-down"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-sm relative animate-fade-in-down"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const mainContent = document.getElementById('main-content-wrapper');
            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                // Mobile behavior: Toggle translate and backdrop
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Open
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    // Close
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            } else {
                // Desktop behavior: Toggle width and margin
                if (sidebar.style.width === '5rem') {
                    // Expand
                    sidebar.style.width = '16rem'; // w-64

                    // Show text spans
                    document.querySelectorAll('#sidebar nav a span, #sidebar .uppercase, #sidebar button span').forEach(el => el.style.display = '');

                    // Adjust main content
                    mainContent.style.marginLeft = '16rem'; // md:ml-64
                } else {
                    // Collapse
                    sidebar.style.width = '5rem'; // w-20

                    // Hide text spans
                    document.querySelectorAll('#sidebar nav a span, #sidebar .uppercase, #sidebar button span').forEach(el => el.style.display = 'none');

                    // Adjust main content
                    mainContent.style.marginLeft = '5rem'; // md:ml-20
                }
            }
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('user-dropdown');
            const button = event.target.closest('button[onclick="toggleDropdown()"]');

            if (!button && !dropdown.contains(event.target) && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });

        // Simple script to confirm deletes
        function confirmDelete(event) {
            if (!confirm('Are you sure you want to delete this item?')) {
                event.preventDefault();
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .animate-fade-in-down {
                animation: fadeInDown 0.3s ease-out;
            }
            @keyframes fadeInDown {
                0% {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>

</html>