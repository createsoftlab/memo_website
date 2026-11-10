<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Packages - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Corrected CSS Paths -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/pricing.css') }}">

    <style>
        .current-package-banner {
            background: linear-gradient(135deg, #fcf8f2 0%, #f4eae1 100%);
            border: 2px solid #d4af37;
            position: relative;
            overflow: hidden;
        }

        .current-package-banner.expired-banner {
            background: linear-gradient(135deg, #fcf2f2 0%, #fae1e1 100%);
            border: 2px solid #dc3545;
        }

        .remaining-time-box {
            background: rgba(255, 255, 255, 0.7);
            border-left: 4px solid #d4af37;
        }

        .current-package-banner.expired-banner .remaining-time-box {
            border-left: 4px solid #dc3545;
        }

        .pricing-card {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(45, 30, 20, 0.08);
            border-color: #d4af37 !important;
        }

        .pricing-toggle-wrapper .btn-toggle.active {
            background-color: #212529 !important;
            color: #ffffff !important;
        }
    </style>
</head>

<body>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-wrapper">

        <!-- Sidebar -->
        @include('memory_user.sidebar')

        <!-- Main Content -->
        <div class="main-content">

            <!-- Top Navbar -->
            @include('memory_user.header')

            <!-- Container-fluid used for wide layout -->
            <div class="container-fluid p-4 overflow-hidden">

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row align-items-center mb-4 g-3">
                    <div class="col-xl-4 col-lg-4 col-12">
                        <h4 class="fw-bold text-dark m-0 font-serif"><i class="bi bi-box-seam text-warning me-2"></i> My
                            Package</h4>
                    </div>
                </div>

                <!-- Fixed Date Calculation Logic -->
                @php
                    $isExpired = false;
                    $daysLeft = 0;

                    if ($user->package_expires_at) {
                        $expiryDate = \Carbon\Carbon::parse($user->package_expires_at)->endOfDay();

                        if ($expiryDate->isPast()) {
                            $isExpired = true;
                        } else {
                            $daysLeft = (int) abs(now()->diffInDays($expiryDate, false));
                            if ($daysLeft === 0) {
                                $daysLeft = 1;
                            }
                        }
                    }

                    $hasLifetime = $currentPackage && $currentPackage->billing_type === 'lifetime';
                @endphp

                <!-- Current Active Plan Banner -->
                <div class="mb-5">
                    <div
                        class="current-package-banner rounded-4 p-4 shadow-sm {{ $isExpired ? 'expired-banner' : '' }}">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-7 col-12">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if ($isExpired)
                                        <span class="badge bg-danger rounded-pill fw-bold text-uppercase px-3 py-2"><i
                                                class="bi bi-exclamation-circle-fill me-1"></i> Expired Plan</span>
                                    @else
                                        <span class="badge bg-success rounded-pill fw-bold text-uppercase px-3 py-2"><i
                                                class="bi bi-check-circle-fill me-1"></i> Active Plan</span>
                                    @endif
                                </div>

                                <h3 class="fw-bold text-dark mb-1">{{ $currentPackage->name ?? 'No Active Plan' }}</h3>

                                <div class="remaining-time-box p-3 rounded-3 d-inline-block w-100 mt-3">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div>
                                            <span class="d-block text-muted" style="font-size: 0.75rem;">REMAINING
                                                TIME</span>
                                            @if ($hasLifetime)
                                                <span class="fw-bold text-dark fs-6"><i
                                                        class="bi bi-infinity text-warning me-1"></i> Lifetime
                                                    Access</span>
                                            @elseif($isExpired)
                                                <span class="fw-bold text-danger fs-6"><i
                                                        class="bi bi-x-circle-fill me-1"></i> 0 Days Left</span>
                                            @else
                                                <span class="fw-bold text-dark fs-6"><i
                                                        class="bi bi-clock-history text-warning me-1"></i>
                                                    {{ $daysLeft }} {{ Str::plural('Day', $daysLeft) }} Left</span>
                                            @endif
                                        </div>
                                        <div class="border-start ps-3">
                                            <span class="d-block text-muted" style="font-size: 0.75rem;">EXPIRY
                                                DATE</span>
                                            @if ($hasLifetime)
                                                <span class="fw-bold text-success fs-6"><i
                                                        class="bi bi-calendar-check me-1"></i> Never Expires</span>
                                            @else
                                                <span
                                                    class="fw-bold {{ $isExpired ? 'text-danger' : 'text-dark' }} fs-6">
                                                    <i
                                                        class="bi {{ $isExpired ? 'bi-calendar-x' : 'bi-calendar-event' }} me-1"></i>
                                                    {{ $user->package_expires_at ? \Carbon\Carbon::parse($user->package_expires_at)->format('M d, Y') : 'N/A' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5 col-12 text-lg-end text-start">
                                <div class="d-flex flex-column gap-2 justify-content-center">
                                    <a href="#pricing-section"
                                        class="btn btn-warning fw-bold text-dark py-2 px-4 rounded-pill shadow-sm"><i
                                            class="bi bi-arrow-up-circle-fill me-1"></i> Upgrade / Change Plan</a>

                                    @if ($currentPackage && !$hasLifetime && $currentPackage->price > 0)
                                        <form action="{{ route('billing.checkout') }}" method="POST" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{ $currentPackage->id }}">
                                            <input type="hidden" name="action" value="renew">
                                            <button type="submit"
                                                class="btn btn-outline-dark fw-bold rounded-pill w-100 py-1"
                                                style="font-size: 0.85rem;"><i class="bi bi-arrow-clockwise me-1"></i>
                                                Renew Same Plan</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 justify-content-center mb-5" id="packagesGrid">
                    @forelse($packages as $pkg)
                        @php
                            $isFree = (float) $pkg->price <= 0;
                            $isFeatured = $pkg->billing_type === 'yearly' || stripos($pkg->name, 'premium') !== false;
                            $currSymbol =
                                strtolower($pkg->currency ?? 'usd') === 'usd'
                                    ? '$'
                                    : strtoupper($pkg->currency ?? 'USD') . ' ';

                            // User ගේ රට සහ Package එකේ රට ගළපා බැලීම (case-insensitive)
                            $userCountry = strtolower(Auth::user()->country ?? '');
                            $pkgCountry = strtolower($pkg->country ?? 'all');
                            $isCountryMatch = $pkgCountry === 'all' || $pkgCountry === $userCountry;
                        @endphp

                        {{-- දැනට භාවිතා කරන පැකේජය නොවීම සහ රට ගැළපෙනවා නම් පමණක් පෙන්වීම --}}
                        @if ($pkg->id !== $user->package_id && $isCountryMatch)
                            <div class="col-12 col-md-6 col-lg-4 package-col" data-billing="{{ $pkg->billing_type }}"
                                data-price="{{ $pkg->price }}">
                                <div
                                    class="pricing-card {{ $isFeatured ? 'card-featured border-warning' : 'border' }} h-100 d-flex flex-column p-4 text-center rounded-4 bg-white shadow-sm position-relative">
                                    @if ($isFeatured)
                                        <span
                                            class="position-absolute top-0 start-50 translate-middle badge bg-warning text-dark px-3 py-1 fw-bold text-uppercase">Most
                                            Popular</span>
                                    @endif

                                    <span
                                        class="plan-subtitle text-uppercase font-serif {{ $isFeatured ? 'fw-bold mt-2 text-dark' : 'text-muted fw-bold' }}">{{ $pkg->name }}</span>

                                    <div class="price-wrapper my-3">
                                        @if ($isFree)
                                            <span class="price-val fw-bold display-5 text-dark">Free</span>
                                        @else
                                            <span class="currency">{{ $currSymbol }}</span>
                                            <span
                                                class="price-val fw-bold display-5 text-dark">{{ number_format($pkg->price, 2) }}</span>
                                            <span class="period text-muted">
                                                @if ($pkg->billing_type === 'yearly')
                                                    /yr
                                                @elseif($pkg->billing_type === 'monthly')
                                                    /mo
                                                @elseif($pkg->billing_type === 'lifetime')
                                                    <small class="text-muted d-block fs-6">one-time</small>
                                                @elseif($pkg->duration_value)
                                                    /{{ $pkg->duration_value }} {{ $pkg->duration_unit }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Memory Quotas -->
                                    <ul
                                        class="list-unstyled text-start small text-muted my-3 px-2 flex-grow-1 border-top border-bottom py-3">
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-warning me-2 fs-6"></i>
                                            <span><strong>{{ $pkg->memories_limit == -1 ? 'Unlimited' : $pkg->memories_limit }}</strong>
                                                Memorial Spaces</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-warning me-2 fs-6"></i>
                                            <span><strong>{{ $pkg->max_photos == -1 ? 'Unlimited' : $pkg->max_photos }}</strong>
                                                Photos</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-warning me-2 fs-6"></i>
                                            <span><strong>{{ $pkg->max_videos == -1 ? 'Unlimited' : $pkg->max_videos }}</strong>
                                                Videos</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill text-warning me-2 fs-6"></i>
                                            <span><strong>{{ $pkg->max_audios == -1 ? 'Unlimited' : $pkg->max_audios }}</strong>
                                                Audio Tracks</span>
                                        </li>
                                        @if ($pkg->events_limit)
                                            <li class="mb-1 d-flex align-items-center">
                                                <i class="bi bi-check-circle-fill text-warning me-2 fs-6"></i>
                                                <span><strong>{{ $pkg->events_limit == -1 ? 'Unlimited' : $pkg->events_limit }}</strong>
                                                    Events</span>
                                            </li>
                                        @endif
                                    </ul>

                                    <div class="mt-auto pt-3">
                                        @if ($isFree)
                                            <button type="button"
                                                class="btn btn-outline-secondary w-100 py-2 fw-bold text-uppercase disabled">Downgrade
                                                Unavailable</button>
                                        @else
                                            <form action="{{ route('billing.checkout') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                                                <input type="hidden" name="action" value="upgrade">
                                                <button type="submit"
                                                    class="btn {{ $isFeatured ? 'btn-warning text-dark' : 'btn-outline-dark' }} w-100 py-2 fw-bold text-uppercase shadow-sm">Upgrade
                                                    to {{ $pkg->name }}</button>
                                            </form>
                                            <span class="d-block text-muted small mt-2"><i
                                                    class="bi bi-lock-fill text-success me-1"></i>Secure Stripe
                                                Checkout</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">No upgrade packages are currently available for your country.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer within the container -->
                <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary mt-5 rounded-4">
                    <div class="container-fluid px-4">
                        <div class="row g-4 mb-4">
                            <div class="col-lg-4 col-md-6">
                                <h5 class="text-gold fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                                <p class="small text-light-tan leading-relaxed">
                                    A dignified digital platform dedicated to preserving legacies, connecting families,
                                    and
                                    honoring cherished memories forever.
                                </p>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                                <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                                    <li><a href="{{ url('admin/index') }}"
                                            class="text-light-tan text-decoration-none">Home</a></li>
                                    <li><a href="{{ url('admin/all_memories') }}"
                                            class="text-light-tan text-decoration-none">Memories</a></li>
                                    <li><a href="{{ url('admin/about') }}"
                                            class="text-light-tan text-decoration-none">About Us</a></li>
                                    <li><a href="{{ url('admin/contact') }}"
                                            class="text-light-tan text-decoration-none">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <h6 class="text-gold fw-bold mb-3">Contact Support</h6>
                                <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                                    <li><i class="bi bi-telephone-fill text-gold me-2"></i>+1 (800) 555-0199</li>
                                    <li><i class="bi bi-envelope-fill text-gold me-2"></i>support@forevermissed.com
                                    </li>
                                    <li><i class="bi bi-geo-alt-fill text-gold me-2"></i>Global Headquarters</li>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <h6 class="text-gold fw-bold mb-3">Follow Us</h6>
                                <div class="d-flex gap-2">
                                    <a href="#"
                                        class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                            class="bi bi-facebook"></i></a>
                                    <a href="#"
                                        class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                            class="bi bi-twitter-x"></i></a>
                                    <a href="#"
                                        class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                            class="bi bi-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <hr class="border-secondary">
                        <div class="text-center small text-white-50 pt-2">
                            <p class="mb-0">&copy; 2026 Forever Missed Memorial Directory. All rights reserved.</p>
                        </div>
                    </div>
                </footer>

            </div>
        </div>
    </div> <!-- Dashboard Wrapper Ends Here -->

    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterBilling(type) {
            const buttons = ['btn-all-plans', 'btn-monthly', 'btn-yearly'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.classList.remove('active', 'bg-dark', 'text-white');
                    btn.classList.add('text-muted');
                }
            });
            const activeBtn = document.getElementById('btn-' + (type === 'all' ? 'all-plans' : type));
            if (activeBtn) {
                activeBtn.classList.add('active', 'bg-dark', 'text-white');
                activeBtn.classList.remove('text-muted');
            }
            const cols = document.querySelectorAll('.package-col');
            cols.forEach(col => {
                const billing = col.getAttribute('data-billing');
                const price = parseFloat(col.getAttribute('data-price') || 0);
                if (type === 'all') {
                    col.style.display = 'block';
                } else if (type === 'monthly') {
                    col.style.display = (billing === 'monthly' || price === 0 || billing === 'lifetime') ? 'block' :
                        'none';
                } else if (type === 'yearly') {
                    col.style.display = (billing === 'yearly' || price === 0 || billing === 'lifetime') ? 'block' :
                        'none';
                }
            });
        }

        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }
    </script>
</body>

</html>
