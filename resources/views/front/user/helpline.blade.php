@extends('layouts.app')

@section('content')
    <div class="container py-5 my-5">
        <div class="row">
            <!-- Sidebar (Should be component) -->
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
                <h4 class="fw-bold mb-4">Helpline & Support</h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center p-5">
                                <div class="mb-3 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                        class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-3.114 3.114a2.201 2.201 0 0 1-3.076-.84 17.076 17.076 0 0 1-.956-1.55c-.382-.76-.84-1.63-1.396-2.612a32.536 32.536 0 0 1-5.748-6.154c-.982-.556-1.852-1.014-2.612-1.396A16.035 16.035 0 0 1 1.576 1.885 2.201 2.201 0 0 1 .511-1.076L3.625.674c.74-.741 1.966-.666 2.611.163z" />
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Call Us</h5>
                                <p class="text-muted">Available 9:00 AM - 6:00 PM</p>
                                <a href="tel:+919876543210" class="btn btn-outline-primary">+91 98765 43210</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center p-5">
                                <div class="mb-3 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                        class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Email Support</h5>
                                <p class="text-muted">We reply within 24 hours</p>
                                <a href="mailto:support@woodenoak.com"
                                    class="btn btn-outline-primary">support@woodenoak.com</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3">Send us a message</h5>
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <form action="{{ route('user.helpline.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" name="subject" class="form-control" placeholder="Subject"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Message</label>
                                        <textarea class="form-control" name="message" rows="4"
                                            placeholder="Describe your issue..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Ticket</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection