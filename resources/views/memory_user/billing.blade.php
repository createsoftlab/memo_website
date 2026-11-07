<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscription - Forever Missed</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Your Custom CSS (copied from create_memory.blade.php) -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/creatememory.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/pricing.css') }}">
</head>

<body class="bg-light">

    <!-- Include Header (if applicable) -->
    @include('home.header')

    <div class="container py-5 mt-4">

        <div class="row mb-5">
            <div class="col-12 text-center text-md-start">
                <h2 class="font-serif fw-bold" style="color: #3b281c;">Manage Your Subscription</h2>
                <p class="text-muted">Renew your current plan or upgrade to unlock more features.</p>
                <a href="{{ route('memoryUser.dashboard') }}" class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Current Plan Details & Renewal -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100"
                    style="border-radius: 15px; border-top: 5px solid #3b281c !important;">
                    <div class="card-body p-4">
                        <h5 class="text-muted small text-uppercase fw-bold mb-3">Current Plan</h5>
                        <h2 class="fw-bold mb-1" style="color: #d4af37;">{{ $currentPackage->name ?? 'No Active Plan' }}
                        </h2>

                        <hr class="my-4">

                        <div class="mb-4">
                            <p class="mb-2"><i class="bi bi-calendar-check text-success me-2"></i>
                                <strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                            <p class="mb-1"><i class="bi bi-clock-history text-warning me-2"></i> <strong>Expires
                                    on:</strong><br>
                                <span class="ms-4 text-muted">
                                    {{ $user->package_expires_at ? \Carbon\Carbon::parse($user->package_expires_at)->format('d M, Y') : 'Lifetime / Never' }}
                                </span>
                            </p>
                        </div>

                        <!-- Renew Form -->
                        @if ($currentPackage && $currentPackage->billing_type !== 'lifetime')
                            <form action="{{ route('billing.checkout') }}" method="POST" class="mt-auto pt-3">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $currentPackage->id }}">
                                <input type="hidden" name="action" value="renew">
                                <button type="submit" class="btn w-100 fw-bold py-2"
                                    style="background-color: #3b281c; color: white; border-radius: 8px;">
                                    Renew Plan ({{ strtoupper($currentPackage->currency ?? 'USD') }}
                                    {{ number_format($currentPackage->price, 2) }})
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upgrade Options -->
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <h4 class="font-serif fw-bold mb-0" style="color: #3b281c;">Available Upgrades</h4>
                    <span class="badge bg-warning text-dark ms-3 rounded-pill">Recommended</span>
                </div>

                <div class="row g-3">
                    @forelse($packages as $pkg)
                        @if ($pkg->id !== $user->package_id)
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0 h-100 transition-hover"
                                    style="border-radius: 15px;">
                                    <div class="card-body p-4 text-center d-flex flex-column">

                                        @if (stripos($pkg->name, 'premium') !== false || stripos($pkg->name, 'lifetime') !== false)
                                            <div class="position-absolute top-0 end-0 mt-3 me-3">
                                                <i class="bi bi-star-fill text-warning fs-5"></i>
                                            </div>
                                        @endif

                                        <h5 class="fw-bold text-uppercase mt-2" style="color: #3b281c;">
                                            {{ $pkg->name }}</h5>

                                        <h3 class="fw-bold my-3" style="color: #d4af37;">
                                            {{ strtoupper($pkg->currency ?? 'USD') }}
                                            {{ number_format($pkg->price, 2) }}
                                            <span class="fs-6 text-muted fw-normal d-block mt-1">
                                                @if ($pkg->billing_type === 'yearly')
                                                    /year
                                                @elseif($pkg->billing_type === 'monthly')
                                                    /month
                                                @elseif($pkg->billing_type === 'lifetime')
                                                    one-time
                                                @endif
                                            </span>
                                        </h3>

                                        <ul
                                            class="list-unstyled text-start small text-muted mb-4 flex-grow-1 border-top pt-3">
                                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i>
                                                <strong>{{ $pkg->memories_limit == -1 ? 'Unlimited' : $pkg->memories_limit }}</strong>
                                                Memorial Spaces</li>
                                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i>
                                                <strong>{{ $pkg->max_photos == -1 ? 'Unlimited' : $pkg->max_photos }}</strong>
                                                Photos</li>
                                            <li class="mb-2"><i class="bi bi-check-circle-fill text-gold me-2"></i>
                                                <strong>{{ $pkg->max_videos == -1 ? 'Unlimited' : $pkg->max_videos }}</strong>
                                                Videos</li>
                                            @if ($pkg->events_limit)
                                                <li class="mb-2"><i
                                                        class="bi bi-check-circle-fill text-gold me-2"></i>
                                                    <strong>{{ $pkg->events_limit == -1 ? 'Unlimited' : $pkg->events_limit }}</strong>
                                                    Events</li>
                                            @endif
                                        </ul>

                                        <!-- Upgrade Form -->
                                        <form action="{{ route('billing.checkout') }}" method="POST" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                                            <input type="hidden" name="action" value="upgrade">

                                            <!-- Note: Stripe checkout button styling -->
                                            <button type="submit" class="btn btn-outline-warning w-100 fw-bold py-2"
                                                style="border-color: #d4af37; color: #3b281c;">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Upgrade to
                                                {{ $pkg->name }}
                                            </button>
                                            <small class="text-muted d-block mt-2" style="font-size: 0.7rem;"><i
                                                    class="bi bi-lock-fill text-success"></i> Secure Stripe
                                                Checkout</small>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-12 text-center py-5 bg-white rounded-3 shadow-sm border-0">
                            <p class="text-muted mb-0">No upgrade packages available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .transition-hover {
            transition: all 0.3s ease;
        }

        .transition-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
            border-color: #d4af37 !important;
        }

        .text-gold {
            color: #d4af37;
        }
    </style>
</body>

</html>
