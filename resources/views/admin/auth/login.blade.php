<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Home Decor</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            /* Override global admin.css flex which is for sidebar layout */
            display: block !important;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            background-color: var(--color-bg-body);
            padding: 1rem;
            /* Add padding for small screens */
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background-color: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
            }

            .login-title {
                margin-bottom: 1.5rem;
                font-size: 1.25rem;
            }
        }

        .login-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-primary);
        }

        .login-btn {
            width: 100%;
            margin-top: 1rem;
            padding: 0.75rem;
            /* Make button slightly larger for touch targets on mobile */
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--color-primary);
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="text-center mb-4">
                <img src="{{ asset('images/docor.logonew.jpeg') }}" alt="Home Decor" class="img-fluid"
                    style="max-height: 60px;">
            </div>
            <h1 class="login-title">Admin Login</h1>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required autofocus
                        placeholder="admin@example.com">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required
                        placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary login-btn">Sign In</button>
            </form>

            <a href="{{ url('/') }}" class="back-link">← Back to Website</a>
        </div>
    </div>
</body>

</html>