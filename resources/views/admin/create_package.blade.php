<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing & Packages Configuration - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
</head>

<body>

    @include('admin.sidebar')

    <div class="main-content">
        @include('admin.header')

        <div class="container py-5">

            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="font-serif fw-bold text-dark" style="color: #3b281a;">Admin Control: Pricing & Packages
                    </h2>
                    <p class="text-muted small">Create and configure subscription packages or individual add-on items
                        (Flowers, Candles)</p>
                </div>
            </div>

            <!-- Alerts for Success and Validation Errors -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Container Card -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="admin-form-card p-4 p-md-5">

                        <ul class="nav nav-pills mb-4 justify-content-center gap-2" id="creationTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-4" id="package-tab" data-bs-toggle="pill"
                                    data-bs-target="#packageContent" type="button" role="tab">Create
                                    Package</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4" id="item-tab" data-bs-toggle="pill"
                                    data-bs-target="#itemContent" type="button" role="tab">Create Single Item
                                    (Flower / Candle)</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="creationTabContent">

                            <!-- 1. PACKAGE CREATION FORM -->
                            <div class="tab-pane fade show active" id="packageContent" role="tabpanel">
                                <form action="{{ route('admin.packages.store') }}" method="POST">
                                    @csrf
                                    <h4 class="form-section-title fs-5"><i class="bi bi-box-seam me-2"></i> Package
                                        Configuration</h4>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold small">Package Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control" placeholder="e.g. Gold Memorial Plan" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold small">Currency</label>
                                            <select name="currency" class="form-select" required>
                                                <option value="usd" {{ old('currency', 'usd') == 'usd' ? 'selected' : '' }}>USD ($)</option>
                                                <option value="lkr" {{ old('currency') == 'lkr' ? 'selected' : '' }}>LKR (Rs)</option>
                                                <option value="eur" {{ old('currency') == 'eur' ? 'selected' : '' }}>EUR (€)</option>
                                                <option value="gbp" {{ old('currency') == 'gbp' ? 'selected' : '' }}>GBP (£)</option>
                                                <option value="aud" {{ old('currency') == 'aud' ? 'selected' : '' }}>AUD (A$)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold small">Price</label>
                                            <input type="number" step="0.01" name="price"
                                                value="{{ old('price') }}" class="form-control" placeholder="79.95"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small">Billing Type</label>
                                            <select name="billing_type" class="form-select">
                                                <option value="monthly"
                                                    {{ old('billing_type') == 'monthly' ? 'selected' : '' }}>Monthly
                                                </option>
                                                <option value="yearly"
                                                    {{ old('billing_type') == 'yearly' ? 'selected' : '' }}>Yearly
                                                </option>
                                                <option value="lifetime"
                                                    {{ old('billing_type') == 'lifetime' ? 'selected' : '' }}>Lifetime
                                                    (One-time)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Available Country (Code)</label>
                                            <select name="country" class="form-select">
                                                <option value="all">All Countries (Global)</option>
                                                <option value="lk">Sri Lanka (LK)</option>
                                                <option value="us">United States (US)</option>
                                                <option value="uk">United Kingdom (UK)</option>
                                                <option value="au">Australia (AU)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Business Profile Type</label>
                                            <select name="business_profile" class="form-select">
                                                <option value="all">All Profiles</option>
                                                <option value="funeral_director">Funeral Director / Parlor</option>
                                                <option value="individual">Individual / Family</option>
                                                <option value="cemetery">Cemetery Management</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Time Duration Limit
                                                (Validity)</label>
                                            <div class="input-group">
                                                <input type="number" name="duration_value"
                                                    value="{{ old('duration_value') }}" class="form-control"
                                                    placeholder="365">
                                                <select name="duration_unit" class="form-select"
                                                    style="max-width: 140px;">
                                                    <option value="days">Days</option>
                                                    <option value="months">Months</option>
                                                    <option value="years">Years</option>
                                                    <option value="unlimited">Unlimited</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Free Trial Period
                                                (Days)</label>
                                            <input type="number" name="trial_days"
                                                value="{{ old('trial_days', 0) }}" class="form-control"
                                                placeholder="e.g. 14 (Leave 0 for no trial)">
                                        </div>
                                    </div>

                                    <!-- Memory Creator / Owner Quotas -->
                                    <h5 class="form-section-title fs-6 mt-4"><i class="bi bi-person-badge me-2"></i>
                                        Memory Creator / Owner Quotas (-1 indicates Unlimited)</h5>
                                    <div class="row g-3 mb-4 bg-light p-3 rounded-3 border">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Events Limit</label>
                                            <input type="number" name="events_limit"
                                                value="{{ old('events_limit', 1) }}"
                                                class="form-control form-control-sm"
                                                placeholder="e.g. 1">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Max Photo Uploads</label>
                                            <input type="number" name="max_photos"
                                                value="{{ old('max_photos', 20) }}"
                                                class="form-control form-control-sm"
                                                placeholder="e.g. 20">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Max Video Uploads</label>
                                            <input type="number" name="max_videos"
                                                value="{{ old('max_videos', 2) }}"
                                                class="form-control form-control-sm" placeholder="e.g. 2">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Max Audio Uploads</label>
                                            <input type="number" name="max_audios"
                                                value="{{ old('max_audios', 5) }}"
                                                class="form-control form-control-sm" placeholder="e.g. 5">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label small fw-bold">Max Comments Limit</label>
                                            <input type="number" name="max_comments"
                                                value="{{ old('max_comments', 100) }}"
                                                class="form-control form-control-sm"
                                                placeholder="e.g. 100">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label small fw-bold">Candles Allocation</label>
                                            <input type="number" name="candles_limit"
                                                value="{{ old('candles_limit', 0) }}"
                                                class="form-control form-control-sm"
                                                placeholder="Free virtual candles included">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label small fw-bold">Flowers Allocation</label>
                                            <input type="number" name="flowers_limit"
                                                value="{{ old('flowers_limit', 0) }}"
                                                class="form-control form-control-sm"
                                                placeholder="Free virtual flowers included">
                                        </div>
                                    </div>

                                    <!-- Feature Toggles (Checkboxes) -->
                                    <h5 class="form-section-title fs-6 mt-4"><i class="bi bi-toggle-on me-2"></i>
                                        Feature Access Toggles</h5>
                                    <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="has_ai_features" id="has_ai_features" value="1"
                                                    {{ old('has_ai_features') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold small" for="has_ai_features">
                                                    Enable AI Features (Storyteller, Tribute Writer)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="has_donations" id="has_donations" value="1"
                                                    {{ old('has_donations') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold small" for="has_donations">
                                                    Enable Memorial Donations & Fundraiser Support
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- General Public / Guest Limits -->
                                    <h5 class="form-section-title fs-6 mt-4"><i class="bi bi-people me-2"></i>
                                        General Public / Guest Limits (-1 = Unlimited, 0 = Disabled)</h5>
                                    <div class="row g-3 mb-4 bg-light p-3 rounded-3 border">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Guest Story Limit</label>
                                            <input type="number" name="guest_story_limit"
                                                value="{{ old('guest_story_limit', 5) }}"
                                                class="form-control form-control-sm"
                                                placeholder="e.g. 5">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Guest Photo Limit</label>
                                            <input type="number" name="guest_photo_limit"
                                                value="{{ old('guest_photo_limit', 10) }}"
                                                class="form-control form-control-sm"
                                                placeholder="e.g. 10">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold">Guest Video Limit</label>
                                            <input type="number" name="guest_video_limit"
                                                value="{{ old('guest_video_limit', 1) }}"
                                                class="form-control form-control-sm"
                                                placeholder="e.g. 1">
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-dark w-100 py-2 fw-bold text-uppercase">Publish Package</button>
                                </form>
                            </div>

                            <!-- 2. SINGLE ITEMS FORM -->
                            <div class="tab-pane fade" id="itemContent" role="tabpanel">
                                <form action="{{ route('admin.single-items.store') }}" method="POST">
                                    @csrf
                                    <h4 class="form-section-title fs-5"><i class="bi bi-flower1 me-2"></i> Single Item
                                        Price & Extend Setup</h4>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Item Category</label>
                                            <select name="type" class="form-select" required>
                                                <option value="candle">Virtual Candle</option>
                                                <option value="flower">Virtual Flower Bouquet</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Item Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control"
                                                placeholder="e.g. Red Rose / Eternal Flame Candle" required>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small">Applicable Country</label>
                                            <select name="country" class="form-select">
                                                <option value="all">All Countries (Global)</option>
                                                <option value="lk">Sri Lanka (LK)</option>
                                                <option value="us">United States (US)</option>
                                                <option value="uk">United Kingdom (UK)</option>
                                                <option value="au">Australia (AU)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small">Currency</label>
                                            <select name="currency" class="form-select" required>
                                                <option value="usd" {{ old('currency', 'usd') == 'usd' ? 'selected' : '' }}>USD ($)</option>
                                                <option value="lkr" {{ old('currency') == 'lkr' ? 'selected' : '' }}>LKR (Rs)</option>
                                                <option value="eur" {{ old('currency') == 'eur' ? 'selected' : '' }}>EUR (€)</option>
                                                <option value="gbp" {{ old('currency') == 'gbp' ? 'selected' : '' }}>GBP (£)</option>
                                                <option value="aud" {{ old('currency') == 'aud' ? 'selected' : '' }}>AUD (A$)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small">Base Price</label>
                                            <input type="number" step="0.01" name="price"
                                                value="{{ old('price') }}" class="form-control" placeholder="4.99"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Initial Active Duration
                                                (Validity)</label>
                                            <div class="input-group">
                                                <input type="number" name="initial_duration_days"
                                                    value="{{ old('initial_duration_days', 7) }}"
                                                    class="form-control" placeholder="7" required>
                                                <span class="input-group-text">Days</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-primary fw-bold">Extend
                                                Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Fee</span>
                                                <input type="number" step="0.01" name="extend_price"
                                                    value="{{ old('extend_price', 2.99) }}" class="form-control"
                                                    placeholder="2.99" required>
                                                <span class="input-group-text">per</span>
                                                <input type="number" name="extend_duration_days"
                                                    value="{{ old('extend_duration_days', 30) }}"
                                                    class="form-control" style="max-width: 80px;" required>
                                                <span class="input-group-text">Days</span>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem;">Allows users to pay
                                                an extension fee to keep the flower/candle displayed longer.</small>
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-warning w-100 py-2 fw-bold text-uppercase text-dark">Save Single
                                        Item Pricing</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
