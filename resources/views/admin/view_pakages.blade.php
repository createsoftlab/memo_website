<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages & Single Items - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">

    <style>
        /* === FULL-WIDTH FIX: Stops the page & navbar from shrinking === */
        html, body {
            width: 100% !important;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .dashboard-wrapper {
            display: flex !important;
            width: 100% !important;
            min-height: 100vh;
        }

        .main-content {
            flex: 1 1 auto !important;
            width: 100% !important;
            min-width: 0 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .top-navbar {
            width: 100% !important;
        }

        /* === UI Enhancements === */
        .text-gold { color: #d4af37 !important; }
        .card-item-box {
            border: 1px solid rgba(0,0,0,0.08);
            border-radius: 14px;
            transition: all 0.25s ease;
            background: #ffffff;
        }
        .card-item-box:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.07);
            transform: translateY(-2px);
        }
        .nav-pills .nav-link {
            font-weight: 500;
            color: #555;
            padding: 9px 24px;
        }
        .nav-pills .nav-link.active {
            background-color: #3b281a !important;
            color: #fff !important;
        }
    </style>
</head>
<body>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-wrapper">
        <!-- Sidebar Include -->
        @include('admin.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            @include('admin.header')

            <!-- Page Body -->
            <div class="container-fluid px-4 py-4 flex-grow-1">

                <!-- Page Top Header -->
                <div class="row align-items-center mb-4">
                    <div class="col-md-8">
                        <h2 class="font-serif fw-bold text-dark m-0" style="color: #3b281a;">Pricing & Packages Management</h2>
                        <p class="text-muted small m-0 mt-1">Configure memorial subscription tiers, memory creator quotas, guest limits, and tribute items</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('admin.create_package') }}" class="btn btn-dark fw-bold px-4 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Add New Package / Item
                        </a>
                    </div>
                </div>

                <!-- Session Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Navigation Tabs -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="packageTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill shadow-sm" id="subs-tab" data-bs-toggle="pill" data-bs-target="#subs-content" type="button" role="tab">
                            <i class="bi bi-box-seam me-2"></i> Subscription Packages
                            <span class="badge bg-light text-dark ms-2">{{ $packages->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill shadow-sm" id="single-tab" data-bs-toggle="pill" data-bs-target="#single-content" type="button" role="tab">
                            <i class="bi bi-flower1 me-2"></i> Single Items (Candles & Flowers)
                            <span class="badge bg-light text-dark ms-2">{{ $singleItems->count() }}</span>
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content Area -->
                <div class="tab-content" id="packageTabContent">

                    <!-- TAB 1: SUBSCRIPTION PACKAGES -->
                    <div class="tab-pane fade show active" id="subs-content" role="tabpanel">
                        <div class="row g-4">
                            @forelse($packages as $pkg)
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="card-item-box p-4 h-100 d-flex flex-column justify-content-between shadow-sm">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-primary px-3 py-1 text-uppercase">{{ $pkg->billing_type }}</span>
                                                <h4 class="text-success fw-bold m-0">
                                                    {{ strtoupper($pkg->currency ?? 'usd') }} {{ number_format($pkg->price, 2) }}
                                                    <small class="text-muted fw-normal fs-6">/ {{ $pkg->billing_type }}</small>
                                                </h4>
                                            </div>

                                            <h4 class="font-serif fw-bold text-dark mt-2 mb-1">{{ $pkg->name }}</h4>

                                            <div class="text-muted small mb-3">
                                                <span><i class="bi bi-calendar-check me-1"></i> Validity: <strong>{{ $pkg->duration_value ? $pkg->duration_value . ' ' . ucfirst($pkg->duration_unit) : 'Unlimited' }}</strong></span> &bull;
                                                <span>Trial: <strong>{{ $pkg->trial_days }} Days</strong></span> &bull;
                                                <span>Country: <strong>{{ $pkg->country == 'all' ? 'Global' : strtoupper($pkg->country) }}</strong></span>
                                            </div>

                                            <!-- Creator Limits -->
                                            <div class="mb-2">
                                                <small class="text-muted fw-bold d-block mb-1">CREATOR LIMITS:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-calendar-event text-primary me-1"></i> {{ $pkg->events_limit == -1 ? 'Unlimited' : $pkg->events_limit }} Events</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-images text-primary me-1"></i> {{ $pkg->max_photos == -1 ? 'Unlimited' : $pkg->max_photos }} Photos</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-camera-video text-danger me-1"></i> {{ $pkg->max_videos }} Videos</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-mic text-secondary me-1"></i> {{ $pkg->max_audios }} Audios</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-chat-dots text-info me-1"></i> {{ $pkg->max_comments == -1 ? 'Unlimited' : $pkg->max_comments }} Comments</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-fire text-warning me-1"></i> {{ $pkg->candles_limit }} Candles</span>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-flower1 text-success me-1"></i> {{ $pkg->flowers_limit }} Flowers</span>
                                                </div>
                                            </div>

                                            <!-- Guest Limits -->
                                            <div class="mb-2">
                                                <small class="text-muted fw-bold d-block mb-1">GUEST / PUBLIC LIMITS:</small>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <span class="badge bg-light text-secondary border">Stories: {{ $pkg->guest_story_limit == -1 ? 'Unlimited' : $pkg->guest_story_limit }}</span>
                                                    <span class="badge bg-light text-secondary border">Photos: {{ $pkg->guest_photo_limit == -1 ? 'Unlimited' : $pkg->guest_photo_limit }}</span>
                                                    <span class="badge bg-light text-secondary border">Videos: {{ $pkg->guest_video_limit == -1 ? 'Unlimited' : $pkg->guest_video_limit }}</span>
                                                </div>
                                            </div>

                                            <!-- Feature Toggles -->
                                            <div class="d-flex gap-2 mt-2">
                                                <span class="badge {{ $pkg->has_ai_features ? 'bg-info text-dark' : 'bg-light text-muted border' }}">
                                                    <i class="bi bi-cpu me-1"></i> AI {{ $pkg->has_ai_features ? 'Active' : 'Off' }}
                                                </span>
                                                <span class="badge {{ $pkg->has_donations ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                                    <i class="bi bi-heart me-1"></i> Donations {{ $pkg->has_donations ? 'Active' : 'Off' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 pt-3 border-top mt-3">
                                            <button type="button" class="btn btn-sm btn-outline-dark w-100 fw-bold edit-pkg-btn"
                                                data-action="{{ route('admin.packages.update', $pkg->id) }}"
                                                data-name="{{ $pkg->name }}"
                                                data-price="{{ $pkg->price }}"
                                                data-currency="{{ $pkg->currency }}"
                                                data-billing="{{ $pkg->billing_type }}"
                                                data-country="{{ $pkg->country }}"
                                                data-profile="{{ $pkg->business_profile }}"
                                                data-validity="{{ $pkg->duration_value }}"
                                                data-validity-unit="{{ $pkg->duration_unit }}"
                                                data-trial="{{ $pkg->trial_days }}"
                                                data-events="{{ $pkg->events_limit }}"
                                                data-photos="{{ $pkg->max_photos }}"
                                                data-videos="{{ $pkg->max_videos }}"
                                                data-audios="{{ $pkg->max_audios }}"
                                                data-comments="{{ $pkg->max_comments }}"
                                                data-candles="{{ $pkg->candles_limit }}"
                                                data-flowers="{{ $pkg->flowers_limit }}"
                                                data-ai="{{ $pkg->has_ai_features ? '1' : '0' }}"
                                                data-donations="{{ $pkg->has_donations ? '1' : '0' }}"
                                                data-guest-story="{{ $pkg->guest_story_limit }}"
                                                data-guest-photo="{{ $pkg->guest_photo_limit }}"
                                                data-guest-video="{{ $pkg->guest_video_limit }}">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Plan
                                            </button>

                                            <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Package">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                                        <i class="bi bi-box-seam fs-1 text-muted"></i>
                                        <h5 class="mt-2 text-dark">No Subscription Packages Available</h5>
                                        <p class="text-muted small">Create and configure your first pricing plan.</p>
                                        <a href="{{ route('admin.create_package') }}" class="btn btn-sm btn-dark px-3 py-2">Create Package</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB 2: SINGLE ITEMS (CANDLES & FLOWERS) -->
                    <div class="tab-pane fade" id="single-content" role="tabpanel">
                        <div class="row g-4">
                            @forelse($singleItems as $item)
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="card-item-box p-4 h-100 d-flex flex-column justify-content-between shadow-sm">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge {{ $item->type == 'candle' ? 'bg-warning text-dark' : 'bg-success' }} px-3 py-1 text-capitalize">
                                                    <i class="bi {{ $item->type == 'candle' ? 'bi-fire' : 'bi-flower1' }} me-1"></i> {{ $item->type }}
                                                </span>
                                                <h4 class="text-success fw-bold m-0">
                                                    {{ strtoupper($item->currency ?? 'usd') }} {{ number_format($item->price, 2) }}
                                                    <small class="text-muted fw-normal fs-6">/ {{ $item->initial_duration_days }} days</small>
                                                </h4>
                                            </div>

                                            <h4 class="font-serif fw-bold text-dark mt-2 mb-1">{{ $item->name }}</h4>

                                            <p class="text-muted small mb-3">
                                                <strong>Active Validity:</strong> {{ $item->initial_duration_days }} Days<br>
                                                <strong>Extension Cost:</strong> {{ strtoupper($item->currency ?? 'usd') }} {{ number_format($item->extend_price, 2) }} per {{ $item->extend_duration_days }} extra days<br>
                                                <strong>Country:</strong> {{ $item->country == 'all' ? 'Global' : strtoupper($item->country) }}
                                            </p>
                                        </div>

                                        <div class="d-flex gap-2 pt-3 border-top mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-warning text-dark w-100 fw-bold edit-item-btn"
                                                data-action="{{ route('admin.single-items.update', $item->id) }}"
                                                data-type="{{ $item->type }}"
                                                data-name="{{ $item->name }}"
                                                data-country="{{ $item->country }}"
                                                data-currency="{{ $item->currency }}"
                                                data-price="{{ $item->price }}"
                                                data-duration="{{ $item->initial_duration_days }}"
                                                data-extprice="{{ $item->extend_price }}"
                                                data-extdays="{{ $item->extend_duration_days }}">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Item
                                            </button>

                                            <form action="{{ route('admin.single-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this single item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Single Item">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                                        <i class="bi bi-flower1 fs-1 text-muted"></i>
                                        <h5 class="mt-2 text-dark">No Single Tribute Items Available</h5>
                                        <p class="text-muted small">Configure virtual candle or flower pricing.</p>
                                        <a href="{{ route('admin.create_package') }}" class="btn btn-sm btn-warning text-dark fw-bold px-3 py-2">Create Single Item</a>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- ================= EDIT PACKAGE MODAL ================= -->
    <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="editPackageForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-dark text-white rounded-top-4">
                        <h5 class="modal-title font-serif" id="editPackageModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i> Edit Package Plan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Package Name</label>
                                <input type="text" name="name" id="modalPkgName" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">Currency</label>
                                <select name="currency" id="modalPkgCurrency" class="form-select" required>
                                    <option value="usd">USD ($)</option>
                                    <option value="lkr">LKR (Rs)</option>
                                    <option value="eur">EUR (€)</option>
                                    <option value="gbp">GBP (£)</option>
                                    <option value="aud">AUD (A$)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">Price</label>
                                <input type="number" step="0.01" name="price" id="modalPkgPrice" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Billing Type</label>
                                <select name="billing_type" id="modalPkgBilling" class="form-select">
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="lifetime">Lifetime</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Available Country</label>
                                <select name="country" id="modalPkgCountry" class="form-select">
                                    <option value="all">All Countries (Global)</option>
                                    <option value="lk">Sri Lanka (LK)</option>
                                    <option value="us">United States (US)</option>
                                    <option value="uk">United Kingdom (UK)</option>
                                    <option value="au">Australia (AU)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Business Profile Type</label>
                                <select name="business_profile" id="modalPkgProfile" class="form-select">
                                    <option value="all">All Profiles</option>
                                    <option value="funeral_director">Funeral Director / Parlor</option>
                                    <option value="individual">Individual / Family</option>
                                    <option value="cemetery">Cemetery Management</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Duration Limit (Validity)</label>
                                <div class="input-group">
                                    <input type="number" name="duration_value" id="modalPkgValidity" class="form-control" placeholder="365">
                                    <select name="duration_unit" id="modalPkgValidityUnit" class="form-select" style="max-width: 130px;">
                                        <option value="days">Days</option>
                                        <option value="months">Months</option>
                                        <option value="years">Years</option>
                                        <option value="unlimited">Unlimited</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Free Trial (Days)</label>
                                <input type="number" name="trial_days" id="modalPkgTrial" class="form-control" placeholder="0">
                            </div>
                        </div>

                        <!-- Creator Quotas -->
                        <div class="p-3 border rounded-3 bg-light mb-3">
                            <h6 class="fw-semibold text-dark mb-2"><i class="bi bi-person-badge me-1"></i> Memory Creator Limits (-1 = Unlimited)</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-md-3">
                                    <label class="form-label small">Events Limit</label>
                                    <input type="number" name="events_limit" id="modalPkgEvents" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Max Photos</label>
                                    <input type="number" name="max_photos" id="modalPkgPhotos" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Max Videos</label>
                                    <input type="number" name="max_videos" id="modalPkgVideos" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Max Audios</label>
                                    <input type="number" name="max_audios" id="modalPkgAudios" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small">Max Comments</label>
                                    <input type="number" name="max_comments" id="modalPkgComments" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Free Candles</label>
                                    <input type="number" name="candles_limit" id="modalPkgCandles" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Free Flowers</label>
                                    <input type="number" name="flowers_limit" id="modalPkgFlowers" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Guest Limits -->
                        <div class="p-3 border rounded-3 bg-light mb-3">
                            <h6 class="fw-semibold text-dark mb-2"><i class="bi bi-people me-1"></i> Guest / General Public Limits</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small">Guest Story Limit</label>
                                    <input type="number" name="guest_story_limit" id="modalPkgGuestStory" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Guest Photo Limit</label>
                                    <input type="number" name="guest_photo_limit" id="modalPkgGuestPhoto" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">Guest Video Limit</label>
                                    <input type="number" name="guest_video_limit" id="modalPkgGuestVideo" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Feature Toggles -->
                        <div class="p-3 border rounded-3 bg-light">
                            <h6 class="fw-semibold text-dark mb-2"><i class="bi bi-toggle-on me-1"></i> Feature Access Toggles</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_ai_features" id="modalPkgAI" value="1">
                                        <label class="form-check-label small fw-semibold" for="modalPkgAI">AI Story & Tribute Features</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_donations" id="modalPkgDonations" value="1">
                                        <label class="form-check-label small fw-semibold" for="modalPkgDonations">Donation & Memorial Fundraiser</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark fw-bold px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= EDIT SINGLE ITEM MODAL ================= -->
    <div class="modal fade" id="editSingleItemModal" tabindex="-1" aria-labelledby="editSingleItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="editSingleItemForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-warning text-dark rounded-top-4">
                        <h5 class="modal-title font-serif fw-bold" id="editSingleItemModalLabel"><i class="bi bi-pencil-square me-2"></i> Edit Single Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Item Type</label>
                            <select name="type" id="modalItemType" class="form-select" required>
                                <option value="candle">Virtual Candle</option>
                                <option value="flower">Virtual Flower Bouquet</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Item Name</label>
                            <input type="text" name="name" id="modalItemName" class="form-control" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Country</label>
                                <select name="country" id="modalItemCountry" class="form-select">
                                    <option value="all">Global</option>
                                    <option value="lk">Sri Lanka (LK)</option>
                                    <option value="us">United States (US)</option>
                                    <option value="uk">United Kingdom (UK)</option>
                                    <option value="au">Australia (AU)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Currency</label>
                                <select name="currency" id="modalItemCurrency" class="form-select" required>
                                    <option value="usd">USD ($)</option>
                                    <option value="lkr">LKR (Rs)</option>
                                    <option value="eur">EUR (€)</option>
                                    <option value="gbp">GBP (£)</option>
                                    <option value="aud">AUD (A$)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Base Price</label>
                                <input type="number" step="0.01" name="price" id="modalItemPrice" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Initial Active Duration</label>
                            <div class="input-group">
                                <input type="number" name="initial_duration_days" id="modalItemDuration" class="form-control" required>
                                <span class="input-group-text bg-light">Days</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-primary fw-bold">Extension Pricing</label>
                            <div class="input-group">
                                <span class="input-group-text">Fee</span>
                                <input type="number" step="0.01" name="extend_price" id="modalItemExtPrice" class="form-control" required>
                                <span class="input-group-text">per</span>
                                <input type="number" name="extend_duration_days" id="modalItemExtDays" class="form-control" required>
                                <span class="input-group-text bg-light">Days</span>
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Renewal cost charged to extend display time.</small>
                        </div>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark fw-bold px-4">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal Binding & Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Package Modal Trigger
            const packageModal = new bootstrap.Modal(document.getElementById('editPackageModal'));
            document.querySelectorAll('.edit-pkg-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = document.getElementById('editPackageForm');
                    form.action = this.getAttribute('data-action');

                    document.getElementById('modalPkgName').value = this.getAttribute('data-name') || '';
                    document.getElementById('modalPkgPrice').value = this.getAttribute('data-price') || '';
                    document.getElementById('modalPkgCurrency').value = (this.getAttribute('data-currency') || 'usd').toLowerCase();
                    document.getElementById('modalPkgBilling').value = this.getAttribute('data-billing') || 'monthly';
                    document.getElementById('modalPkgCountry').value = this.getAttribute('data-country') || 'all';
                    document.getElementById('modalPkgProfile').value = this.getAttribute('data-profile') || 'all';
                    document.getElementById('modalPkgValidity').value = this.getAttribute('data-validity') || '';
                    document.getElementById('modalPkgValidityUnit').value = this.getAttribute('data-validity-unit') || 'days';
                    document.getElementById('modalPkgTrial').value = this.getAttribute('data-trial') || '0';
                    document.getElementById('modalPkgEvents').value = this.getAttribute('data-events') || '1';
                    document.getElementById('modalPkgPhotos').value = this.getAttribute('data-photos') || '20';
                    document.getElementById('modalPkgVideos').value = this.getAttribute('data-videos') || '2';
                    document.getElementById('modalPkgAudios').value = this.getAttribute('data-audios') || '5';
                    document.getElementById('modalPkgComments').value = this.getAttribute('data-comments') || '100';
                    document.getElementById('modalPkgCandles').value = this.getAttribute('data-candles') || '0';
                    document.getElementById('modalPkgFlowers').value = this.getAttribute('data-flowers') || '0';
                    document.getElementById('modalPkgGuestStory').value = this.getAttribute('data-guest-story') || '5';
                    document.getElementById('modalPkgGuestPhoto').value = this.getAttribute('data-guest-photo') || '10';
                    document.getElementById('modalPkgGuestVideo').value = this.getAttribute('data-guest-video') || '1';

                    document.getElementById('modalPkgAI').checked = this.getAttribute('data-ai') === '1';
                    document.getElementById('modalPkgDonations').checked = this.getAttribute('data-donations') === '1';

                    packageModal.show();
                });
            });

            // Single Item Modal Trigger
            const singleItemModal = new bootstrap.Modal(document.getElementById('editSingleItemModal'));
            document.querySelectorAll('.edit-item-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = document.getElementById('editSingleItemForm');
                    form.action = this.getAttribute('data-action');

                    document.getElementById('modalItemType').value = this.getAttribute('data-type');
                    document.getElementById('modalItemName').value = this.getAttribute('data-name');
                    document.getElementById('modalItemCountry').value = this.getAttribute('data-country');
                    document.getElementById('modalItemCurrency').value = (this.getAttribute('data-currency') || 'usd').toLowerCase();
                    document.getElementById('modalItemPrice').value = this.getAttribute('data-price');
                    document.getElementById('modalItemDuration').value = this.getAttribute('data-duration');
                    document.getElementById('modalItemExtPrice').value = this.getAttribute('data-extprice');
                    document.getElementById('modalItemExtDays').value = this.getAttribute('data-extdays');

                    singleItemModal.show();
                });
            });

            // Mobile Sidebar Toggle Handlers
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
        });
    </script>
</body>
</html>
