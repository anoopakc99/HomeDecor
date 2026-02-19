@extends('layouts.app')

@section('content')
    <div class="container py-5 my-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2C241B&color=fff"
                                class="rounded-circle me-3" width="50" alt="Avatar">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </div>
                        <hr>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('user.dashboard') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.dashboard') ? 'active' : '' }}">Dashboard</a>
                            <a href="{{ route('user.orders') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.orders*') ? 'active' : '' }}">My
                                Orders</a>
                            <a href="{{ route('user.helpline') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.helpline') ? 'active' : '' }}">Helpline</a>
                            <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button
                                    class="list-group-item list-group-item-action text-danger border-0 bg-transparent">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-9">
                <!-- Profile Update -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">My Profile</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ Auth::user()->mobile }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Current Password (leave blank to keep)</label>
                                    <input type="password" name="current_password" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password (leave blank to keep)</label>
                                    <input type="password" name="new_password" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Recent Orders</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>₹{{ number_format($order->total_amount) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection