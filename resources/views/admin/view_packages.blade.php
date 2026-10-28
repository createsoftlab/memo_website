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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <style>
        .text-gold {
            color: #d4af37 !important;
        }

        .card-item-box {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .card-item-box:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
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
            <!-- Top Navbar Include -->
            @include('admin.header')

            <!-- Page Body -->
            <div class="container py-5">

                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="font-serif fw-bold text-dark" style="color: #3b281a;">Admin Control: Pricing &
                            Packages</h2>
                        <p class="text-muted small">Manage existing items/packages below, or use the form to create and
                            configure new ones.</p>
                    </div>
                </div>

                <!-- Session Notifications -->
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

                <!-- ================= EXISTING PACKAGES & ITEMS CARDS DISPLAY ================= -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                        <h4 class="font-serif fw-bold text-dark mb-4"><i class="bi bi-grid me-2"></i> Existing Active
                            Packages & Items</h4>

                        <div class="row g-4">
                            <!-- 1. Packages Loop -->
                            @forelse($packages as $pkg)
                                <div class="col-md-6">
                                    <div
                                        class="card-item-box p-4 h-100 d-flex flex-column justify-content-between shadow-sm">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-primary px-3 py-1">Package Plan</span>
                                                <h5 class="text-success fw-bold m-0">
                                                    ${{ number_format($pkg->price, 2) }}
                                                    <small class="text-muted fw-normal fs-6">/
                                                        {{ $pkg->billing_type }}</small>
                                                </h5>
                                            </div>
                                            <h4 class="font-serif fw-bold text-dark">{{ $pkg->name }}</h4>
                                            <p class="text-muted small mb-2">
                                                <strong>Validity:</strong> {{ $pkg->duration_value ?? 'Unlimited' }}
                                                {{ $pkg->duration_unit }} |
                                                <strong>Trial:</strong> {{ $pkg->trial_days }} Days
                                            </p>
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                <span class="badge bg-light text-dark border"><i
                                                        class="bi bi-images me-1"></i>
                                                    {{ $pkg->max_photos == -1 ? 'Unlimited' : $pkg->max_photos }}
                                                    Photos</span>
                                                <span class="badge bg-light text-dark border"><i
                                                        class="bi bi-camera-video me-1"></i> {{ $pkg->max_videos }}
                                                    Videos</span>
                                                <span class="badge bg-light text-dark border"><i
                                                        class="bi bi-chat-dots me-1"></i>
                                                    {{ $pkg->max_comments == -1 ? 'Unlimited' : $pkg->max_comments }}
                                                    Comments</span>
                                                <span class="badge bg-light text-dark border"><i
                                                        class="bi bi-fire me-1"></i> {{ $pkg->candles_limit }}
                                                    Candles</span>
                                                <span class="badge bg-light text-dark border"><i
                                                        class="bi bi-flower1 me-1"></i> {{ $pkg->flowers_limit }}
                                                    Flowers</span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-dark w-100 fw-bold edit-pkg-btn"
                                                data-id="{{ $pkg->id }}"
                                                data-action="{{ route('admin.packages.update', $pkg->id) }}"
                                                data-name="{{ $pkg->name }}" data-price="{{ $pkg->price }}"
                                                data-billing="{{ $pkg->billing_type }}"
                                                data-country="{{ $pkg->country }}"
                                                data-profile="{{ $pkg->business_profile }}"
                                                data-validity="{{ $pkg->duration_value }}"
                                                data-validity-unit="{{ $pkg->duration_unit }}"
                                                data-trial="{{ $pkg->trial_days }}"
                                                data-photos="{{ $pkg->max_photos }}"
                                                data-videos="{{ $pkg->max_videos }}"
                                                data-comments="{{ $pkg->max_comments }}"
                                                data-candles="{{ $pkg->candles_limit }}"
                                                data-flowers="{{ $pkg->flowers_limit }}">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Now
                                            </button>
                                            <form action="{{ route('admin.packages.destroy', $pkg->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Kya aap is package ko delete karna chahte hain?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center border">Koi package available nahi hai.
                                        Niche form se naya package banayein.</div>
                                </div>
                            @endforelse

                            <!-- 2. Single Items Loop -->
                            @forelse($singleItems as $item)
                                <div class="col-md-6">
                                    <div
                                        class="card-item-box p-4 h-100 d-flex flex-column justify-content-between shadow-sm">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-success px-3 py-1">Single Item
                                                    ({{ ucfirst($item->type) }})</span>
                                                <h5 class="text-success fw-bold m-0">
                                                    ${{ number_format($item->price, 2) }}
                                                    <small class="text-muted fw-normal fs-6">/
                                                        {{ $item->initial_duration_days }} days</small>
                                                </h5>
                                            </div>
                                            <h4 class="font-serif fw-bold text-dark">{{ $item->name }}</h4>
                                            <p class="text-muted small mb-3">
                                                <strong>Extension Rate:</strong>
                                                ${{ number_format($item->extend_price, 2) }} per
                                                {{ $item->extend_duration_days }} days<br>
                                                <strong>Country:</strong>
                                                {{ $item->country == 'all' ? 'All Countries (Global)' : strtoupper($item->country) }}
                                            </p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-warning text-dark w-100 fw-bold edit-item-btn"
                                                data-id="{{ $item->id }}"
                                                data-action="{{ route('admin.single-items.update', $item->id) }}"
                                                data-type="{{ $item->type }}" data-name="{{ $item->name }}"
                                                data-country="{{ $item->country }}" data-price="{{ $item->price }}"
                                                data-duration="{{ $item->initial_duration_days }}"
                                                data-extprice="{{ $item->extend_price }}"
                                                data-extdays="{{ $item->extend_duration_days }}">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Now
                                            </button>
                                            <form action="{{ route('admin.single-items.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Kya aap is item ko delete karna chahte hain?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center border">Koi single item (flower/candle)
                                        nahi mila.</div>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                <!-- ================= FORM CONTAINER CARD (CREATE / EDIT) ================= -->
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="admin-form-card p-4 p-md-5 shadow-sm bg-white rounded-4" id="formSection">

                            <!-- Tab Switcher -->
                            <ul class="nav nav-pills mb-4 justify-content-center gap-2" id="creationTab"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill px-4" id="package-tab"
                                        data-bs-toggle="pill" data-bs-target="#packageContent" type="button"
                                        role="tab">Create / Edit Package</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-4" id="item-tab" data-bs-toggle="pill"
                                        data-bs-target="#itemContent" type="button" role="tab">Create / Edit
                                        Single Item</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="creationTabContent">

                                <!-- ================= 1. PACKAGE CONFIG FORM ================= -->
                                <div class="tab-pane fade show active" id="packageContent" role="tabpanel">
                                    <form id="packageForm" action="{{ route('admin.packages.store') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" id="packageHttpMethod" value="POST">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="form-section-title fs-5 m-0" id="pkgFormTitle"><i
                                                    class="bi bi-box-seam me-2"></i> Package Configuration</h4>
                                            <button type="button" class="btn btn-sm btn-light border d-none"
                                                id="cancelPkgEditBtn">Cancel Edit</button>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Package Name</label>
                                                <input type="text" name="name" id="pkgName"
                                                    class="form-control" placeholder="e.g. Gold Memorial Plan"
                                                    required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold small">Price ($)</label>
                                                <input type="number" step="0.01" name="price" id="pkgPrice"
                                                    class="form-control" placeholder="79.95" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold small">Billing Type</label>
                                                <select name="billing_type" id="pkgBilling" class="form-select">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="yearly">Yearly</option>
                                                    <option value="lifetime">Lifetime</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Available Country</label>
                                                <select name="country" id="pkgCountry" class="form-select">
                                                    <option value="all">All Countries (Global)</option>
                                                    <option value="lk">Sri Lanka</option>
                                                    <option value="us">United States</option>
                                                    <option value="uk">United Kingdom</option>
                                                    <option value="au">Australia</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Business Profile
                                                    Type</label>
                                                <select name="business_profile" id="pkgProfile" class="form-select">
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
                                                    <input type="number" name="duration_value" id="pkgValidity"
                                                        class="form-control" placeholder="365">
                                                    <select name="duration_unit" id="pkgValidityUnit"
                                                        class="form-select" style="max-width: 140px;">
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
                                                <input type="number" name="trial_days" id="pkgTrial"
                                                    class="form-control" placeholder="e.g. 14 (Leave 0 for no trial)">
                                            </div>
                                        </div>

                                        <!-- Features & Limits Section -->
                                        <div class="p-3 border rounded-3 bg-light mb-4">
                                            <h6 class="fw-semibold text-dark mb-3"><i class="bi bi-sliders me-1"></i>
                                                Features & Item Limits</h6>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-semibold">Max Photo
                                                        Uploads</label>
                                                    <input type="number" name="max_photos" id="pkgPhotos"
                                                        class="form-control form-control-sm"
                                                        placeholder="e.g. 50 or -1 for unlimited">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-semibold">Max Video
                                                        Uploads</label>
                                                    <input type="number" name="max_videos" id="pkgVideos"
                                                        class="form-control form-control-sm" placeholder="e.g. 5">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-semibold">Max Comments
                                                        Limit</label>
                                                    <input type="number" name="max_comments" id="pkgComments"
                                                        class="form-control form-control-sm"
                                                        placeholder="e.g. 100 or -1 for unlimited">
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">Candles
                                                        Allocation</label>
                                                    <input type="number" name="candles_limit" id="pkgCandles"
                                                        class="form-control form-control-sm"
                                                        placeholder="Free virtual candles included">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold">Flowers
                                                        Allocation</label>
                                                    <input type="number" name="flowers_limit" id="pkgFlowers"
                                                        class="form-control form-control-sm"
                                                        placeholder="Free virtual flowers included">
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" id="pkgSubmitBtn"
                                            class="btn btn-dark w-100 py-2 fw-bold text-uppercase">Publish
                                            Package</button>
                                    </form>
                                </div>

                                <!-- ================= 2. SINGLE ITEM FORM ================= -->
                                <div class="tab-pane fade" id="itemContent" role="tabpanel">
                                    <form id="itemForm" action="{{ route('admin.single-items.store') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" id="itemHttpMethod" value="POST">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="form-section-title fs-5 m-0" id="itemFormTitle"><i
                                                    class="bi bi-flower1 me-2"></i> Single Item Price & Extend Setup
                                            </h4>
                                            <button type="button" class="btn btn-sm btn-light border d-none"
                                                id="cancelItemEditBtn">Cancel Edit</button>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Item Category</label>
                                                <select name="type" id="itemCategory" class="form-select"
                                                    required>
                                                    <option value="candle">Virtual Candle</option>
                                                    <option value="flower">Virtual Flower Bouquet</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Item Name</label>
                                                <input type="text" name="name" id="itemName"
                                                    class="form-control"
                                                    placeholder="e.g. Red Rose / Eternal Flame Candle" required>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Applicable Country</label>
                                                <select name="country" id="itemCountry" class="form-select">
                                                    <option value="all">All Countries (Global)</option>
                                                    <option value="lk">Sri Lanka</option>
                                                    <option value="us">United States</option>
                                                    <option value="uk">United Kingdom</option>
                                                    <option value="au">Australia</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Base Price ($)</label>
                                                <input type="number" step="0.01" name="price" id="itemPrice"
                                                    class="form-control" placeholder="4.99" required>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Initial Active Duration
                                                    (Validity)</label>
                                                <div class="input-group">
                                                    <input type="number" name="initial_duration_days"
                                                        id="itemDuration" class="form-control" placeholder="7"
                                                        required>
                                                    <span class="input-group-text bg-light">Days</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small text-primary fw-bold">Extend
                                                    Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" name="extend_price"
                                                        id="itemExtPrice" class="form-control" placeholder="2.99"
                                                        required>
                                                    <span class="input-group-text">per</span>
                                                    <input type="number" name="extend_duration_days"
                                                        id="itemExtDays" class="form-control" placeholder="30"
                                                        style="max-width: 90px;" required>
                                                    <span class="input-group-text bg-light">Days</span>
                                                </div>
                                                <small class="text-muted" style="font-size: 11px;">Allows users to pay
                                                    an extension fee to keep the flower/candle displayed longer.</small>
                                            </div>
                                        </div>

                                        <button type="submit" id="itemSubmitBtn"
                                            class="btn btn-warning w-100 py-2 fw-bold text-uppercase text-dark">Save
                                            Single Item Pricing</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Interactive Handlers for Edit & Switching -->
    <script>
        const defaultPackageAction = "{{ route('admin.packages.store') }}";
        const defaultItemAction = "{{ route('admin.single-items.store') }}";

        // Package Edit Action
        document.querySelectorAll('.edit-pkg-btn').forEach(button => {
            button.addEventListener('click', function() {
                const packageTab = new bootstrap.Tab(document.getElementById('package-tab'));
                packageTab.show();

                const form = document.getElementById('packageForm');
                form.action = this.getAttribute('data-action');
                document.getElementById('packageHttpMethod').value = 'PUT';

                document.getElementById('pkgName').value = this.getAttribute('data-name');
                document.getElementById('pkgPrice').value = this.getAttribute('data-price');
                document.getElementById('pkgBilling').value = this.getAttribute('data-billing');
                document.getElementById('pkgCountry').value = this.getAttribute('data-country');
                document.getElementById('pkgProfile').value = this.getAttribute('data-profile');
                document.getElementById('pkgValidity').value = this.getAttribute('data-validity');
                document.getElementById('pkgValidityUnit').value = this.getAttribute(
                    'data-validity-unit') || 'days';
                document.getElementById('pkgTrial').value = this.getAttribute('data-trial');
                document.getElementById('pkgPhotos').value = this.getAttribute('data-photos');
                document.getElementById('pkgVideos').value = this.getAttribute('data-videos');
                document.getElementById('pkgComments').value = this.getAttribute('data-comments');
                document.getElementById('pkgCandles').value = this.getAttribute('data-candles');
                document.getElementById('pkgFlowers').value = this.getAttribute('data-flowers');

                document.getElementById('pkgFormTitle').innerHTML =
                    '<i class="bi bi-pencil-square me-2 text-warning"></i> Edit Package Configuration';
                document.getElementById('pkgSubmitBtn').innerText = 'Update Package';
                document.getElementById('cancelPkgEditBtn').classList.remove('d-none');

                document.getElementById('formSection').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Cancel Package Edit
        document.getElementById('cancelPkgEditBtn').addEventListener('click', function() {
            const form = document.getElementById('packageForm');
            form.reset();
            form.action = defaultPackageAction;
            document.getElementById('packageHttpMethod').value = 'POST';
            document.getElementById('pkgFormTitle').innerHTML =
                '<i class="bi bi-box-seam me-2"></i> Package Configuration';
            document.getElementById('pkgSubmitBtn').innerText = 'Publish Package';
            this.classList.add('d-none');
        });

        // Single Item Edit Action
        document.querySelectorAll('.edit-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemTab = new bootstrap.Tab(document.getElementById('item-tab'));
                itemTab.show();

                const form = document.getElementById('itemForm');
                form.action = this.getAttribute('data-action');
                document.getElementById('itemHttpMethod').value = 'PUT';

                document.getElementById('itemCategory').value = this.getAttribute('data-type');
                document.getElementById('itemName').value = this.getAttribute('data-name');
                document.getElementById('itemCountry').value = this.getAttribute('data-country');
                document.getElementById('itemPrice').value = this.getAttribute('data-price');
                document.getElementById('itemDuration').value = this.getAttribute('data-duration');
                document.getElementById('itemExtPrice').value = this.getAttribute('data-extprice');
                document.getElementById('itemExtDays').value = this.getAttribute('data-extdays');

                document.getElementById('itemFormTitle').innerHTML =
                    '<i class="bi bi-pencil-square me-2 text-warning"></i> Edit Single Item';
                document.getElementById('itemSubmitBtn').innerText = 'Update Single Item';
                document.getElementById('cancelItemEditBtn').classList.remove('d-none');

                document.getElementById('formSection').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Cancel Item Edit
        document.getElementById('cancelItemEditBtn').addEventListener('click', function() {
            const form = document.getElementById('itemForm');
            form.reset();
            form.action = defaultItemAction;
            document.getElementById('itemHttpMethod').value = 'POST';
            document.getElementById('itemFormTitle').innerHTML =
                '<i class="bi bi-flower1 me-2"></i> Single Item Price & Extend Setup';
            document.getElementById('itemSubmitBtn').innerText = 'Save Single Item Pricing';
            this.classList.add('d-none');
        });

        // Mobile Sidebar Toggle
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
