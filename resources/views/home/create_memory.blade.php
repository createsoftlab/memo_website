<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Memorial - Forever Missed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/creatememory.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/pricing.css') }}">

    <style>
        .pricing-card {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(45, 30, 20, 0.08);
            border-color: #d4af37;
        }

        .pricing-card.selected-plan {
            border: 2.5px solid #d4af37 !important;
            background: linear-gradient(180deg, #ffffff 0%, #fffdf8 100%) !important;
            box-shadow: 0 12px 35px rgba(212, 175, 55, 0.25) !important;
            transform: translateY(-5px);
        }

        .pricing-card.selected-plan .plan-select-btn {
            background-color: #3b281c !important;
            color: #ffffff !important;
            border-color: #3b281c !important;
        }

        .pricing-toggle-wrapper .btn-toggle.active {
            background-color: #3b281c !important;
            color: #ffffff !important;
        }
    </style>

</head>

<body>

    @include('home.header')

    <!-- Hero Header -->
    <header class="creatememory-hero d-flex align-items-center text-white mb-5">
        <div class="container py-4 text-center text-lg-start">
            <span class="badge bg-gold text-dark fw-bold mb-2 uppercase tracking-wide">Get In Touch</span>
            <h1 class="font-serif display-5 fw-bold mb-2">We Are Here For You</h1>
            <p class="lead mb-0 text-light-tan">Have questions or need assistance? Reach out to our dedicated support
                team anytime.</p>
        </div>
    </header>

    <!-- Stepper Navigation (Line and Circles) -->
    <div class="stepper-wrapper mb-5">
        <div class="step active" id="tab-step-1" onclick="switchStep(1)">
            <div class="step-circle">1</div>
            <div class="step-label">About your loved one</div>
        </div>
        <div class="step" id="tab-step-2" onclick="switchStep(2)">
            <div class="step-circle">2</div>
            <div class="step-label">Privacy Options</div>
        </div>
        <div class="step" id="tab-step-3" onclick="switchStep(3)">
            <div class="step-circle">3</div>
            <div class="step-label">Choose your plan</div>
        </div>
    </div>

    <!-- Form Content Container -->
    <div class="memorial-card p-4 p-md-5">
        <form action="{{ route('home.create_memory.store') }}" method="POST">
            @csrf

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong class="d-block mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Please review the
                        form errors:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- STEP 1: About your loved one -->

            <!-- STEP 1: About your loved one -->
            <div class="form-step-content" id="step-content-1">
                <h2 class="mb-4">This memorial is dedicated to:</h2>

                <!-- Memorial Status & Type Custom Dropdowns -->
                @php
                    $selectedStatus = old('memorial_status', 'passed_away');
                    $selectedReason = old('passed_away_reason', '');

                    $reasons = [
                        'Natural Causes',
                        'Old Age',
                        'Cancer',
                        'Heart Disease',
                        'Heart Attack',
                        'Stroke',
                        'COVID-19',
                        'Other Illness',
                        'Accident',
                        'Road Accident',
                        'Natural Disaster',
                        'Suicide',
                        'Homicide',
                        'Other',
                        'Unknown',
                        'Prefer Not to Disclose',
                    ];
                @endphp

                <div class="mb-3 row">

                    <!-- Memorial Status Custom Dropdown -->
                    <div class="mb-3 col-12 col-md-6 mb-md-0">
                        <label class="form-label fw-bold">Memorial Status *</label>

                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom @error('memorial_status') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                <span class="text-truncate">
                                    {{ $selectedStatus === 'passed_away' ? 'Passed Away' : 'Living Memory' }}
                                </span>

                                <i class="flex-shrink-0 bi bi-chevron-down ms-2"></i>
                            </button>

                            <ul class="shadow-sm dropdown-menu w-100">

                                <li>
                                    <a class="dropdown-item {{ $selectedStatus === 'passed_away' ? 'active' : '' }}"
                                        href="#"
                                        onclick="event.preventDefault(); selectDropdownOption(this, 'memorial_status', 'passed_away')">
                                        Passed Away
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ $selectedStatus === 'living_memory' ? 'active' : '' }}"
                                        href="#"
                                        onclick="event.preventDefault(); selectDropdownOption(this, 'memorial_status', 'living_memory')">
                                        Living Memory
                                    </a>
                                </li>

                            </ul>
                        </div>

                        <input type="hidden" name="memorial_status" id="memorial_status"
                            value="{{ $selectedStatus }}">

                        @error('memorial_status')
                            <div class="mt-1 text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Passed Away Reason Dropdown -->
                    <div class="col-12 col-md-6 {{ $selectedStatus === 'living_memory' ? 'd-none' : '' }}"
                        id="passedAwayReasonContainer">

                        <label class="form-label fw-bold">Passed Away Reason</label>

                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom @error('passed_away_reason') is-invalid @enderror"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                <span class="text-truncate">
                                    {{ $selectedReason ?: 'Select Passed Away Reason' }}
                                </span>

                                <i class="flex-shrink-0 bi bi-chevron-down ms-2"></i>
                            </button>

                            <ul class="shadow-sm dropdown-menu w-100">

                                @foreach ($reasons as $reason)
                                    <li>
                                        <a class="dropdown-item {{ $selectedReason === $reason ? 'active' : '' }}"
                                            href="#"
                                            onclick="event.preventDefault(); selectDropdownOption(this, 'passed_away_reason', @js($reason))">
                                            {{ $reason }}
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                        <input type="hidden" name="passed_away_reason" id="passed_away_reason"
                            value="{{ $selectedReason }}"
                            {{ $selectedStatus === 'living_memory' ? 'disabled' : '' }}>

                        @error('passed_away_reason')
                            <div class="mt-1 text-danger small">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                <!-- First & Middle Name -->
                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label">First name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="form-control w-100">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Middle name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                            class="form-control w-100">
                    </div>
                </div>

                <!-- Last Name & Gender -->
                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Last name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="form-control w-100">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Gender</label>
                        <div class="mt-2">
                            <input type="radio" name="gender" value="Male" id="male"
                                @checked(old('gender') == 'Male')>
                            <label for="male" class="me-3">Male</label>

                            <input type="radio" name="gender" value="Female" id="female"
                                @checked(old('gender') == 'Female')>
                            <label for="female">Female</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- 1. Country Custom Dropdown -->
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Country *</label>
                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">🇱🇰 Sri Lanka</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                <li><a class="dropdown-item active" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'Sri Lanka')">Sri
                                        Lanka</a>
                                </li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'United State')">United
                                        State
                                        States</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'United Kingdom')">United
                                        Kingdom
                                        Kingdom</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'Australia')">Australia</a>
                                </li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'Canada')">Canada</a>
                                </li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'India')">India</a>
                                </li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'account_country', 'United ArabEmirates')">United
                                        ArabEmirates</a>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="country" id="account_country" value="LK">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold" id="regionLabel">City / Town *</label>
                        <input type="text" name="city" id="stateProvinceInput" class="form-control w-100 py-2"
                            placeholder="Enter your District , Province or state ">
                    </div>
                </div>

                <!-- Relationship & Designation Custom Dropdowns -->
                <div class="row mb-3">
                    <!-- Relationship Custom Dropdown -->
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">Relationship</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item active" href="#"
                                        onclick="selectDropdownOption(this, 'relationship', '')">Relationship</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'relationship', 'Parent')">Parent</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'relationship', 'Spouse')">Spouse</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'relationship', 'Child')">Child</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'relationship', 'Friend')">Friend</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="relationship" id="relationship" value="">
                    </div>

                    <!-- Special Designation Custom Dropdown -->
                    <div class="col-12 col-md-6">
                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">Special designation (if applies)</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item active" href="#"
                                        onclick="selectDropdownOption(this, 'designation', '')">Special designation (if
                                        applies)</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'designation', 'Veteran')">Veteran</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'designation', 'First Responder')">First
                                        Responder</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="designation" id="designation" value="">
                    </div>
                </div>

                <!-- More Details Dropdown Toggle Button -->
                <div class="mb-4">
                    <a class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center py-2 px-3 dropdown-toggle-btn text-decoration-none"
                        data-bs-toggle="collapse" href="#moreDetailsBox" role="button" aria-expanded="false"
                        aria-controls="moreDetailsBox">
                        <span><i class="bi bi-chevron-down me-2"></i> More details (optional)</span>
                    </a>

                    <!-- Collapsible Box -->
                    <div class="collapse mt-3" id="moreDetailsBox">
                        <div class="info-box p-3 p-md-4 border rounded bg-light">
                            <p class="fw-bold mb-3 text-secondary">This information can also be updated at a later
                                time:
                            </p>

                            <label class="form-label small text-muted">Born</label>
                            <div class="row mb-3">
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="birth_year"
                                        class="form-control" placeholder="Year"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="birth_month"
                                        class="form-control" placeholder="Month"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="birth_day"
                                        class="form-control" placeholder="Day"></div>
                                <div class="col-12 col-md-3 mb-2"><input type="text" name="birth_location"
                                        class="form-control" placeholder="City or town"></div>
                            </div>

                            <label class="form-label small text-muted">Passed away</label>
                            <div class="row">
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="passed_year"
                                        class="form-control" placeholder="Year"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="passed_month"
                                        class="form-control" placeholder="Month"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="passed_day"
                                        class="form-control" placeholder="Day"></div>
                                <div class="col-12 col-md-3 mb-2"><input type="text" name="death_location"
                                        class="form-control" placeholder="City or town"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-gold px-5" onclick="switchStep(2)">Continue</button>
                </div>
            </div>


            <div class="form-step-content d-none" id="step-content-2">
                <h2 class="mb-4">Privacy Options</h2>
                <p class="text-muted mb-4">Control who can view and contribute to this memorial space.</p>

                <div class="mb-4">
                    <!-- Public Memorial Option -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="privacy" value="public" id="public"
                            checked onchange="togglePrivacySettings()">
                        <label class="form-check-label fw-bold" for="public">Public Memorial</label>
                        <p class="small text-muted">Anyone can find and view this memorial website.</p>
                    </div>

                    <!-- Private (Password Protected) Option -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="privacy" value="private"
                            id="private" onchange="togglePrivacySettings()">
                        <label class="form-check-label fw-bold" for="private">Private (Password Protected)</label>
                        <p class="small text-muted">Only people with the password can access the memorial.</p>
                    </div>

                    <!-- Private Details Box (Hidden by default, shows when Private is selected) -->
                    <div id="privateSettingsBox" class="p-3 border rounded bg-light mt-3 d-none">

                        <!-- Username Field (Custom or Random) -->
                        <div class="mb-3">
                            <label for="private_username" class="form-label fw-bold small">Access Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="private_username"
                                    name="private_username" placeholder="Type custom name or click random">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="generateRandomUsername()" title="Generate Random Name">
                                    <i class="bi bi-shuffle"></i> Random
                                </button>
                            </div>
                            <div class="form-text small text-muted">You can type a custom username or click 'Random' if
                                you don't want to show your real name.</div>
                        </div>

                        <!-- Password Field (Custom or Random with Copy & Share) -->
                        <div class="mb-2">
                            <label for="private_password" class="form-label fw-bold small">Access Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" id="private_password"
                                    name="private_password" placeholder="Type custom password or generate">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="generateRandomPassword()" title="Generate Random Password">
                                    <i class="bi bi-shuffle"></i> Random
                                </button>
                                <button class="btn btn-outline-primary" type="button" onclick="copyPassword()"
                                    title="Copy Password">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                                <button class="btn btn-outline-success" type="button" onclick="sharePassword()"
                                    title="Share Details">
                                    <i class="bi bi-share"></i> Share
                                </button>
                            </div>
                            <div class="form-text small text-muted">Type your own custom password or generate a random
                                secure one.</div>
                            <div id="copyAlert" class="small text-success mt-1 d-none">Password copied to clipboard!
                            </div>
                        </div>

                    </div>

                    <!-- Publish or Draft Option -->
                    <div class="mb-4 p-3 border rounded bg-white">
                        <label class="form-label fw-bold mb-3">Memorial Publication Status</label>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" value="published"
                                id="statusPublish" checked>
                            <label class="form-check-label fw-bold text-success" for="statusPublish">
                                <i class="bi bi-globe me-1"></i> Publish Now
                            </label>
                            <p class="small text-muted mb-0">The memorial website will be live and accessible
                                immediately.</p>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="draft"
                                id="statusDraft">
                            <label class="form-check-label fw-bold text-secondary" for="statusDraft">
                                <i class="bi bi-file-earmark-text me-1"></i> Save as Draft
                            </label>
                            <p class="small text-muted mb-0">Save your progress. Only you can view and edit this later.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4" onclick="switchStep(1)">Back</button>
                    <button type="button" class="btn btn-gold px-5" onclick="switchStep(3)">Continue</button>
                </div>
            </div>

            <!-- STEP 3: Choose Your Plan -->
            <div class="form-step-content d-none" id="step-content-3">
                <div class="row g-3 mb-4">
                    <section class="pricing-section py-5">
                        <div class="container-fluid px-md-5 px-3">

                            <!-- Section Title & Toggle Switch -->
                            <div class="text-center mb-5">
                                <h2 class="font-serif text-brown fw-bold display-6 mb-2 text-uppercase">Choose Your
                                    Memorial Plan</h2>
                                <p class="text-muted small uppercase tracking-wide mb-4">Honor your loved ones with
                                    dedicated, permanent spaces</p>

                                <!-- Plan Billing Toggle Switch -->
                                <div
                                    class="pricing-toggle-wrapper mx-auto d-inline-flex align-items-center p-1 rounded-pill">
                                    <button type="button" class="btn btn-toggle active" id="btn-all-plans"
                                        onclick="filterBilling('all')">All Plans</button>
                                    <button type="button" class="btn btn-toggle" id="btn-monthly"
                                        onclick="filterBilling('monthly')">Monthly</button>
                                    <button type="button" class="btn btn-toggle" id="btn-yearly"
                                        onclick="filterBilling('yearly')">
                                        Yearly <span class="badge badge-discount ms-1">Save 15%</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Dynamic Pricing Cards Grid -->
                            @php
                                $packages =
                                    $packages ??
                                    \App\Models\Package::where('is_active', true)
                                        ->where(function ($query) use ($visitorCountry) {
                                            $query->where('country', $visitorCountry)->orWhere('country', 'all');
                                        })
                                        ->get();
                            @endphp
                            <div class="row g-4 justify-content-center mb-5" id="packagesGrid">
                                @forelse($packages as $pkg)
                                    @php
                                        $isFree = (float) $pkg->price <= 0;
                                        $isFeatured =
                                            $pkg->billing_type === 'yearly' || stripos($pkg->name, 'premium') !== false;
                                        $currSymbol =
                                            strtolower($pkg->currency ?? 'usd') === 'usd'
                                                ? '$'
                                                : strtoupper($pkg->currency ?? 'USD') . ' ';
                                    @endphp
                                    <div class="col-12 col-md-6 col-lg-4 package-col"
                                        data-billing="{{ $pkg->billing_type }}" data-price="{{ $pkg->price }}">
                                        <div class="pricing-card {{ $isFeatured ? 'card-featured' : '' }} h-100 d-flex flex-column p-4 text-center"
                                            id="package-card-{{ $pkg->id }}"
                                            onclick="selectPackage({{ $pkg->id }}, {{ $pkg->price }}, '{{ addslashes($pkg->name) }}', '{{ $pkg->currency ?? 'USD' }}')">

                                            @if ($isFeatured)
                                                <span class="popular-badge">Most Popular</span>
                                            @endif

                                            <span
                                                class="plan-subtitle text-uppercase font-serif {{ $isFeatured ? 'text-brown' : 'text-muted' }} fw-bold">
                                                {{ $pkg->name }}
                                            </span>

                                            <div class="price-wrapper my-3">
                                                @if ($isFree)
                                                    <span class="price-val fw-bold display-5 text-brown">Free</span>
                                                @else
                                                    <span class="currency">{{ $currSymbol }}</span>
                                                    <span
                                                        class="price-val fw-bold display-5 text-brown">{{ number_format($pkg->price, 2) }}</span>
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

                                            @if ($pkg->trial_days > 0)
                                                <span class="trial-note small text-gold mb-2 fw-semibold">
                                                    <i class="bi bi-clock-history me-1"></i>{{ $pkg->trial_days }}-day
                                                    free trial
                                                </span>
                                            @else
                                                <span class="trial-note small text-muted mb-2">&nbsp;</span>
                                            @endif

                                            <!-- Limits & Quotas -->
                                            <ul
                                                class="list-unstyled text-start small text-muted my-3 px-2 flex-grow-1 border-top border-bottom py-3">
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->memories_limit == -1 ? 'Unlimited' : $pkg->memories_limit }}</strong>
                                                        Memorial
                                                        {{ Str::plural('Space', $pkg->memories_limit) }}</span>
                                                </li>
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->max_photos == -1 ? 'Unlimited' : $pkg->max_photos }}</strong>
                                                        Photos</span>
                                                </li>
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->max_videos == -1 ? 'Unlimited' : $pkg->max_videos }}</strong>
                                                        Videos</span>
                                                </li>
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->max_audios == -1 ? 'Unlimited' : $pkg->max_audios }}</strong>
                                                        Audio Tracks</span>
                                                </li>
                                                @if ($pkg->events_limit)
                                                    <li class="mb-1 d-flex align-items-center">
                                                        <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                        <span><strong>{{ $pkg->events_limit == -1 ? 'Unlimited' : $pkg->events_limit }}</strong>
                                                            Events</span>
                                                    </li>
                                                @endif
                                            </ul>

                                            <!-- Hidden Package Radio -->
                                            <input type="radio" name="package_id"
                                                id="pkg_radio_{{ $pkg->id }}" value="{{ $pkg->id }}"
                                                class="d-none package-radio"
                                                {{ old('package_id', optional($packages->first())->id) == $pkg->id ? 'checked' : '' }}>

                                            <!-- Select Plan Button -->
                                            <button type="button"
                                                class="btn {{ $isFeatured ? 'btn-gold shadow-sm' : ($isFree ? 'btn-outline-brown' : 'btn-outline-gold') }} w-100 mt-auto py-2 fw-bold text-uppercase plan-select-btn"
                                                id="btn-select-{{ $pkg->id }}">
                                                <span class="btn-text">Select Plan</span>
                                            </button>

                                            @if ($isFree)
                                                <span class="d-block text-muted tiny-note mt-2">
                                                    <i class="bi bi-shield-check me-1"></i>No credit card required
                                                </span>
                                            @else
                                                <span class="d-block text-muted tiny-note mt-2">
                                                    <i class="bi bi-lock-fill me-1 text-success"></i>Secure checkout
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4">
                                        <p class="text-muted">No memorial packages are currently active. Please contact
                                            support.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Selected Plan Summary Banner (Stripe Checkout Prep) -->
                            <div id="paymentDetailsSection"
                                class="p-4 p-md-5 rounded-4 border bg-white mb-5 shadow-sm d-none">
                                <div
                                    class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom flex-wrap gap-2">
                                    <div>
                                        <h4 class="font-serif text-brown fw-bold mb-1">
                                            <i class="bi bi-credit-card-2-front text-gold me-2"></i>Checkout Summary
                                        </h4>
                                        <p class="text-muted small mb-0">You will be redirected to Stripe's secure
                                            checkout page to complete your payment.</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            <i class="bi bi-lock-fill text-success me-1"></i> Stripe Secure Checkout
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="alert alert-light border d-flex justify-content-between align-items-center mb-0 py-3 px-4 rounded-3">
                                    <div>
                                        <span class="text-muted small d-block">Selected Plan:</span>
                                        <strong class="text-brown fs-5" id="summaryPlanName">-</strong>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted small d-block">Total Due Today:</span>
                                        <span class="fs-4 fw-bold text-brown" id="summaryPlanPrice">$0.00</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Plan Selected Notice -->
                            <div id="freePlanNotice"
                                class="p-4 p-md-5 rounded-4 border bg-white mb-5 shadow-sm text-center d-none">
                                <div class="d-inline-flex p-3 rounded-circle bg-light border mb-3 shadow-sm">
                                    <i class="bi bi-gift-fill text-gold fs-2"></i>
                                </div>
                                <h4 class="font-serif fw-bold text-brown mb-2">Free Plan Selected</h4>
                                <p class="text-muted small mb-0 max-w-md mx-auto">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i> No credit card or payment
                                    details required. Click below to activate your memorial immediately with essential
                                    core features.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3 flex-wrap gap-2">
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="switchStep(2)">
                        <i class="bi bi-arrow-left me-1"></i> Back to Step 2
                    </button>
                    <button type="submit" class="btn btn-gold px-5 py-2 fw-bold text-uppercase shadow-sm"
                        id="btn-submit-step3">
                        <span id="btn-submit-text">Checkout</span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>
    </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const memorialStatus = document.getElementById('memorial_status');
            const reasonContainer = document.getElementById('passedAwayReasonContainer');
            const reasonInput = document.getElementById('passed_away_reason');

            function togglePassedAwayReason() {
                const isPassedAway = memorialStatus.value === 'passed_away';

                reasonContainer.classList.toggle('d-none', !isPassedAway);

                // Prevent submission when Living Memory is selected
                reasonInput.disabled = !isPassedAway;
            }

            // Custom dropdown selection
            window.selectDropdownOption = function(element, inputId, value) {

                const input = document.getElementById(inputId);
                const dropdown = element.closest('.dropdown');
                const buttonText = dropdown.querySelector('button span');

                // Update hidden input
                input.value = value;

                // Update dropdown label
                buttonText.textContent = element.textContent.trim();

                // Update active option
                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    item.classList.remove('active');
                });

                element.classList.add('active');

                // Toggle reason dropdown when status changes
                if (inputId === 'memorial_status') {
                    togglePassedAwayReason();
                }
            };

            // Set correct visibility on initial page load
            togglePassedAwayReason();

        });
    </script>

    <!-- JavaScript for Tab Switching Logic -->
    <script>
        function switchStep(stepNumber) {
            for (let i = 1; i <= 3; i++) {
                document.getElementById('step-content-' + i).classList.add('d-none');
                document.getElementById('tab-step-' + i).classList.remove('active');
            }
            document.getElementById('step-content-' + stepNumber).classList.remove('d-none');
            document.getElementById('tab-step-' + stepNumber).classList.add('active');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>


    <script>
        function selectDropdownOption(element, inputName, value) {
            event.preventDefault();
            const dropdown = element.closest('.dropdown');
            const buttonSpan = dropdown.querySelector('.dropdown-toggle-custom span');
            const hiddenInput = document.getElementById(inputName);


            buttonSpan.textContent = element.textContent;

            if (hiddenInput) {
                hiddenInput.value = value;
            }

            dropdown.querySelectorAll('.dropdown-item').forEach(item => item.classList.remove('active'));
            element.classList.add('active');
        }
    </script>



    <!-- JavaScript Functions -->
    <script>
        // Private ඔප්ෂන් එක තෝරාගත් විට Box එක පෙන්වීම හෝ වැසීම
        function togglePrivacySettings() {
            const isPrivate = document.getElementById('private').checked;
            const privateBox = document.getElementById('privateSettingsBox');

            if (isPrivate) {
                privateBox.classList.remove('d-none');
                // මුල් වරට private තෝරන විට input හිස් නම් පමණක් random අගයන් දමා හැඩගැස්වීම (অটোමැටික්)
                if (!document.getElementById('private_password').value) {
                    generateRandomPassword();
                }
                if (!document.getElementById('private_username').value) {
                    generateRandomUsername();
                }
            } else {
                privateBox.classList.add('d-none');
            }
        }

        // අහඹු (Random) Username එකක් සකස් කිරීම (Custom අවශ්‍ය නම් වෙනස් කර ටයිප් කළ හැක)
        function generateRandomUsername() {
            const randomNames = ["GuestUser", "FamilyVisitor", "Visitor99", "FriendMemorial", "AnonymousUser",
                "MemoryKeeper"
            ];
            const randomName = randomNames[Math.floor(Math.random() * randomNames.length)] + Math.floor(Math.random() *
                1000);
            document.getElementById('private_username').value = randomName;
        }

        // අහඹු (Random) ශක්තිමත් Password එකක් සකස් කිරීම (Custom අවශ්‍ය නම් වෙනස් කර ටයිප් කළ හැක)
        function generateRandomPassword() {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!";
            let password = "";
            for (let i = 0; i < 10; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('private_password').value = password;
        }

        // Password එක Clipboard එකට Copy කරගැනීම
        function copyPassword() {
            const passwordInput = document.getElementById('private_password');
            passwordInput.select();
            passwordInput.setSelectionRange(0, 99999); // Mobile devices සඳහා

            navigator.clipboard.writeText(passwordInput.value).then(() => {
                const alertBox = document.getElementById('copyAlert');
                alertBox.classList.remove('d-none');
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 2000);
            });
        }

        // දත්ත Share කරගැනීම (Web Share API හරහා)
        function sharePassword() {
            const username = document.getElementById('private_username').value;
            const password = document.getElementById('private_password').value;
            const shareText =
                `Here is the access detail for the memorial space:\nUsername: ${username}\nPassword: ${password}`;

            if (navigator.share) {
                navigator.share({
                    title: 'Memorial Access Details',
                    text: shareText,
                }).catch((error) => console.log('Sharing failed', error));
            } else {
                navigator.clipboard.writeText(shareText);
                alert("Access details copied to clipboard to share!");
            }
        }
    </script>



    <!-- අතිරේක JavaScript ශ්‍රිතය (Country Code වෙනස් කිරීම සඳහා) -->
    <script>
        function selectCountryCode(element, codeValue) {
            event.preventDefault();
            const dropdown = element.closest('.dropdown');
            const buttonSpan = dropdown.querySelector('.dropdown-toggle-custom span');
            const hiddenInput = document.getElementById('selected_country_code');

            buttonSpan.textContent = codeValue;
            if (hiddenInput) {
                hiddenInput.value = codeValue;
            }

            dropdown.querySelectorAll('.dropdown-item').forEach(item => item.classList.remove('active'));
            element.classList.add('active');
        }
    </script>

    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }
    </script>

    <!-- Step 3 Plan Selection & Filtering Scripts -->
    <script>
        // 1. Package Filtering Logic (බොත්තම් සඳහා)
        function filterBilling(type) {
            // බොත්තම් වල Active පාට වෙනස් කිරීම
            const buttons = ['btn-all-plans', 'btn-monthly', 'btn-yearly'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.classList.remove('active');
                }
            });

            // දැනට ක්ලික් කළ බොත්තමට Active class එක දීම
            const activeBtn = document.getElementById('btn-' + (type === 'all' ? 'all-plans' : type));
            if (activeBtn) {
                activeBtn.classList.add('active');
            }

            // Packages Filter කිරීම
            const cols = document.querySelectorAll('.package-col');
            cols.forEach(col => {
                const billing = col.getAttribute('data-billing') ? col.getAttribute('data-billing').toLowerCase() :
                    '';
                const price = parseFloat(col.getAttribute('data-price') || 0);

                if (type === 'all') {
                    col.classList.remove('d-none'); // සියල්ල පෙන්වන්න
                } else if (type === 'monthly') {
                    // Monthly, Free, සහ Lifetime පෙන්වන්න
                    if (billing === 'monthly' || price === 0 || billing === 'lifetime') {
                        col.classList.remove('d-none');
                    } else {
                        col.classList.add('d-none');
                    }
                } else if (type === 'yearly') {
                    // Yearly, Free, සහ Lifetime පෙන්වන්න
                    if (billing === 'yearly' || price === 0 || billing === 'lifetime') {
                        col.classList.remove('d-none');
                    } else {
                        col.classList.add('d-none');
                    }
                }
            });
        }

        // 2. Package Selection Logic
        let currentSelectedPkgId = null;

        function selectPackage(pkgId, price, name, currency) {
            currentSelectedPkgId = pkgId;
            const numPrice = parseFloat(price);

            // Radio Button තේරීම
            const radio = document.getElementById('pkg_radio_' + pkgId);
            if (radio) {
                radio.checked = true;
            }

            // අනිත් කාඩ් වල පෙනුම සාමාන්‍ය කිරීම
            document.querySelectorAll('.pricing-card').forEach(card => {
                card.classList.remove('selected-plan');
                const btn = card.querySelector('.plan-select-btn .btn-text');
                if (btn) btn.textContent = 'Select Plan';
            });

            // තේරූ කාඩ් එක Highlight කිරීම
            const activeCard = document.getElementById('package-card-' + pkgId);
            if (activeCard) {
                activeCard.classList.add('selected-plan');
                const activeBtn = activeCard.querySelector('.plan-select-btn .btn-text');
                if (activeBtn) activeBtn.textContent = 'Selected ✓';
            }

            // Summary එක Update කිරීම
            const summaryName = document.getElementById('summaryPlanName');
            const summaryPrice = document.getElementById('summaryPlanPrice');
            const submitBtnText = document.getElementById('btn-submit-text');
            const paymentSection = document.getElementById('paymentDetailsSection');
            const freeNotice = document.getElementById('freePlanNotice');

            const currSymbol = (currency && currency.toLowerCase() === 'usd') ? '$' : (currency ? currency.toUpperCase() +
                ' ' : '$');

            if (summaryName) summaryName.textContent = name;
            if (summaryPrice) {
                summaryPrice.textContent = (numPrice <= 0) ? 'Free ($0.00)' : currSymbol + numPrice.toFixed(2);
            }

            // Free සහ Paid ප්ලෑන් වලට අදාළව Payment කොටස පෙන්වීම/සැඟවීම
            if (numPrice > 0) {
                if (paymentSection) paymentSection.classList.remove('d-none');
                if (freeNotice) freeNotice.classList.add('d-none');
                if (submitBtnText) submitBtnText.textContent = 'Checkout (' + currSymbol + numPrice.toFixed(2) + ')';
            } else {
                if (paymentSection) paymentSection.classList.add('d-none');
                if (freeNotice) freeNotice.classList.remove('d-none');
                if (submitBtnText) submitBtnText.textContent = 'Complete & Activate Free Plan';
            }
        }

        // 3. Document Load වීමේදී Default Package එක තේරීම
        document.addEventListener('DOMContentLoaded', function() {
            const defaultRadio = document.querySelector('.package-radio:checked') || document.querySelector(
                '.package-radio');
            if (defaultRadio) {
                const card = defaultRadio.closest('.pricing-card');
                if (card) {
                    card.click();
                }
            }

            // Validation Errors ආවොත් කෙලින්ම Step 3 එකට යාම
            @if (isset($errors) && $errors->has('package_id'))
                switchStep(3);
            @endif
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
