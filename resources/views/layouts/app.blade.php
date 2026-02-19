<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wooden Oak Studio | Handcrafted Furniture</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm py-2">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand d-flex align-items-center me-auto" href="{{ route('home') }}">
                <img src="{{ asset('images/docor.logonew.jpeg') }}" alt="Wooden Oak Studio" class="img-fluid"
                    style="max-height: 50px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Shop All</a></li>

                    @if(isset($navbarCategories))
                        @foreach($navbarCategories as $category)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{{ $category->id }}"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $category->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $category->id }}">
                                    @foreach($category->children as $child)
                                        <li><a class="dropdown-item ajax-link"
                                                href="{{ route('products.index', ['category' => $child->slug]) }}">{{ $child->name }}</a>
                                        </li>
                                    @endforeach
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item ajax-link"
                                            href="{{ route('products.index', ['category' => $category->slug]) }}">All
                                            {{ $category->name }}</a></li>
                                </ul>
                            </li>
                        @endforeach
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Cart <span
                                class="badge bg-secondary" id="cart-count">
                                @auth
                                    {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                                @else
                                    0
                                @endauth
                            </span></a>
                    </li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @else
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link dropdown-toggle hidden-arrow" href="#" id="navbarNotificationLink"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                        class="bi bi-bell" viewBox="0 0 16 16">
                                        <path
                                            d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                                    </svg>
                                    @php
                                        // Filter: Show Unread OR Read within last 5 minutes
                                        $notifications = Auth::user()->notifications->filter(function ($n) {
                                            return is_null($n->read_at) || $n->read_at->gt(now()->subMinutes(5));
                                        });
                                        // Count logic: Use the count of displayed notifications
                                        $unreadCount = $notifications->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="badge rounded-pill bg-danger"
                                            style="position: absolute; top: 0px; right: 0px; font-size: 0.6rem;">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarNotificationLink"
                                    style="width: 320px; max-height: 400px; overflow-y: auto;">
                                    <li>
                                        <h6 class="dropdown-header fw-bold">Notifications</h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider my-0">
                                    </li>
                                    @forelse($notifications as $notification)
                                        <li>
                                            <a class="dropdown-item py-2 {{ $notification->read_at ? '' : 'bg-light' }}"
                                                href="{{ route('user.notifications.read', $notification->id) }}"
                                                style="white-space: normal;">
                                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                    <strong
                                                        class="small text-primary">{{ ucfirst(str_replace('_', ' ', $notification->data['type'] ?? 'Notification')) }}</strong>
                                                    <span class="text-muted"
                                                        style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="mb-0 small text-muted">
                                                    {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 80) }}
                                                </p>
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider my-0">
                                        </li>
                                    @empty
                                        <li class="p-4 text-center text-muted small">No recent notifications.</li>
                                    @endforelse
                                    @if($notifications->count() > 0)
                                        <li><a class="dropdown-item text-center small text-primary fw-bold py-2"
                                                href="{{ route('user.notifications.markAllRead') }}">Mark all as read</a></li>
                                    @endif
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="d-inline-block text-truncate"
                                        style="max-width: 150px;">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">My Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.orders') }}">My Orders</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.helpline') }}">Helpline</a></li>
                                    <li></li>
                            </li>
                            <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('user.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        @yield('content')
    </div>

    <!-- Floating Voice Search Setup -->
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050; margin-bottom: 80px; display: none;">
        <button id="floating-voice-btn"
            class="btn btn-warning rounded-circle shadow-lg d-flex align-items-center justify-content-center"
            style="width: 60px; height: 60px; transition: transform 0.2s;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-mic-fill text-dark" viewBox="0 0 16 16">
                <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z" />
                <path
                    d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-5 5V14h2a.5.5 0 0 1 0 1H6a.5.5 0 0 1 0-1h2v-1a5 5 0 0 1-5-5V7a.5.5 0 0 1 .5-.5z" />
            </svg>
        </button>
    </div>

    <!-- Voice Listening Modal/Overlay -->
    <div id="voice-overlay"
        class="position-fixed top-0 start-0 w-100 h-100 d-none flex-column align-items-center justify-content-center"
        style="background: rgba(0,0,0,0.85); z-index: 2000; backdrop-filter: blur(5px);">
        <div class="text-center text-white">
            <div class="spinner-grow text-warning mb-4" role="status" style="width: 4rem; height: 4rem;">
                <span class="visually-hidden">Listening...</span>
            </div>
            <h2 class="fw-bold mb-3">I'm Listening...</h2>
            <p class="lead mb-4">Try saying "Sofa under 10000" or "Wooden Table"</p>
            <button id="stop-voice-btn" class="btn btn-outline-light rounded-pill px-4">Cancel</button>
        </div>

        <!-- Hidden Search Form -->
        <form action="{{ route('products.index') }}" method="GET" id="voice-search-form">
            <input type="hidden" name="search" id="hidden-voice-input">
        </form>
    </div>

    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('images/docor.logonew.jpeg') }}" alt="Wooden Oak Studio" class="mb-3 img-fluid"
                        style="max-height: 60px; width: auto;">
                    <h5 class="visually-hidden">Wooden Oak Studio</h5>
                    <p>Premium handcrafted wooden furniture for your home.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Shop</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: support@woodenoak.com</p>
                    <p>Phone: +91 98765 43210</p>
                </div>
            </div>
            <div class="text-center mt-3">
                <p>&copy; {{ date('Y') }} Wooden Oak Studio. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple AJAX Navigation (Hijax)
        $(document).on('click', 'a.nav-link, a.ajax-link', function (e) {
            // Check if it's an external link or anchor link
            let url = $(this).attr('href');
            if (!url || url === '#' || url.startsWith('http') && !url.includes(window.location.hostname)) return;

            e.preventDefault();
            history.pushState(null, '', url);
            loadContent(url);
        });

        window.onpopstate = function () {
            loadContent(location.href);
        };

        function loadContent(url) {
            $('#main-content').css('opacity', '0.5');
            $.ajax({
                url: url,
                success: function (response) {
                    let doc = new DOMParser().parseFromString(response, 'text/html');
                    let newContent = doc.getElementById('main-content');

                    if (newContent) {
                        $('#main-content').html(newContent.innerHTML).css('opacity', '1');
                        document.title = doc.title;
                    } else {
                        // Fallback if #main-content not found (partial view?)
                        $('#main-content').html(response).css('opacity', '1');
                    }
                    window.scrollTo(0, 0);
                },
                error: function () {
                    window.location.href = url; // Fallback
                }
            });
        }

        // Product Gallery Image Switcher
        function changeImage(src) {
            document.getElementById('main-product-image').src = src;
            // Update active state visuals if needed
            document.querySelectorAll('.img-thumbnail').forEach(img => {
                img.style.opacity = '0.7';
                img.style.border = 'none';
            });
            event.target.style.opacity = '1';
            event.target.style.border = '2px solid var(--wood-accent)';
        }
    </script>
    @yield('scripts')
    @stack('scripts')
    <script>
        // Floating Voice Search Logic
        const floatingBtn = document.getElementById('floating-voice-btn');
        const overlay = document.getElementById('voice-overlay');
        const stopBtn = document.getElementById('stop-voice-btn');
        const hiddenInput = document.getElementById('hidden-voice-input');
        const voiceForm = document.getElementById('voice-search-form');

        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'en-US';

            // Start Listening
            floatingBtn.addEventListener('click', () => {
                overlay.classList.remove('d-none');
                overlay.classList.add('d-flex');
                recognition.start();
            });

            // Stop/Cancel
            stopBtn.addEventListener('click', () => {
                recognition.stop();
                overlay.classList.add('d-none');
                overlay.classList.remove('d-flex');
            });

            // On Result
            recognition.onresult = (event) => {
                const rawTranscript = event.results[0][0].transcript.trim();

                // Deduplicate repeated words (e.g. "Sofa Sofa Sofa" → "Sofa")
                const words = rawTranscript.split(/\s+/);
                const seen = new Set();
                const uniqueWords = [];
                for (const word of words) {
                    const lower = word.toLowerCase();
                    if (!seen.has(lower)) {
                        seen.add(lower);
                        uniqueWords.push(word);
                    }
                }
                const cleanedTranscript = uniqueWords.join(' ');

                // Show visual feedback
                overlay.innerHTML = `
                    <div class="text-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check-circle-fill text-success mb-3" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <h3 class="fw-bold">Searching: "${cleanedTranscript}"...</h3>
                    </div>
                `;

                // Redirect instantly to search results
                const searchUrl = `{{ route('products.index') }}?search=${encodeURIComponent(cleanedTranscript)}`;
                window.location.href = searchUrl;
            };

            // On Error or End
            recognition.onerror = (event) => {
                console.error("Voice error", event.error);
                overlay.classList.add('d-none');
                overlay.classList.remove('d-flex');
                alert("Could not hear you. Please try again.");
            };

            recognition.onend = () => {
                // Usually we keep overlay open until result or manual close, but if no result, maybe close?
                // For now, let's leave it to user to cancel or re-click if it timed out silently.
            };

        } else {
            floatingBtn.style.display = 'none'; // Hide if not supported
        }
    </script>
</body>

</html>