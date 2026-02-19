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
                            <a href="{{ route('user.addresses.index') }}"
                                class="list-group-item list-group-item-action {{ Route::is('user.addresses*') ? 'active' : '' }}">Saved
                                Addresses</a>
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

            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold">Saved Addresses</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        + Add New Address
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row g-4">
                    @forelse($addresses as $address)
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm {{ $address->is_default ? 'border-primary' : 'border-0' }}">
                                <div class="card-body position-relative">
                                    @if($address->is_default)
                                        <span class="badge bg-primary mb-2">Default</span>
                                    @endif
                                    <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
                                    <p class="mb-1">{{ $address->address_line }}</p>
                                    <p class="mb-1">{{ $address->city }}, {{ $address->state }} - {{ $address->zip }}</p>
                                    <p class="mb-3">Mobile: {{ Auth::user()->mobile }}</p>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#editAddressModal{{ $address->id }}">Edit</button>
                                        <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('user.addresses.update', $address->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Address</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Address Line</label>
                                                <input type="text" name="address_line" class="form-control"
                                                    value="{{ $address->address_line }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">City</label>
                                                    <input type="text" name="city" class="form-control"
                                                        value="{{ $address->city }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">State</label>
                                                    <input type="text" name="state" class="form-control"
                                                        value="{{ $address->state }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">ZIP Code</label>
                                                <input type="text" name="zip" class="form-control" value="{{ $address->zip }}"
                                                    required>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                                    id="defaultCheck{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }}>
                                                <label class="form-check-label" for="defaultCheck{{ $address->id }}">
                                                    Set as Default Address
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5 text-muted bg-light rounded">
                                <i class="bi bi-geo-alt fs-1"></i>
                                <p class="mt-2">No saved addresses found.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('user.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Address Line</label>
                            <input type="text" name="address_line" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ZIP Code</label>
                            <input type="text" name="zip" class="form-control" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                id="defaultCheckNew">
                            <label class="form-check-label" for="defaultCheckNew">
                                Set as Default Address
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection