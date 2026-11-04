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

    <!-- Stripe JS (Payment Gateway එකට අත්‍යවශ්‍යයි) -->
    <script src="https://js.stripe.com/v3/"></script>

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
        /* Stripe Element Styling */
        #card-element {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
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
        <form action="{{ route('home.create_memory.store') }}" method="POST" id="payment-form">
            @csrf

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <strong class="d-block mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Please review the form errors:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- STEP 1: About your loved one -->
            <div class="form-step-content" id="step-content-1">
                <h2 class="mb-4">This memorial is dedicated to:</h2>

                <!-- Memorial Status & Type Custom Dropdowns -->
                <div class="row mb-3">
                    <!-- Memorial Status Custom Dropdown -->
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Memorial Status</label>
                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">Passed away</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item active" href="#"
                                        onclick="selectDropdownOption(this, 'memorial_status', 'passed_away')">Passed
                                        away</a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'memorial_status', 'living_memory')">Living
                                        memory</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="memorial_status" id="memorial_status" value="passed_away">
                    </div>

                    <!-- Who is this for? Custom Dropdown -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Passed Away Reason</label>
                        <div class="dropdown w-100">
                            <button
                                class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">Living memory</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item active" href="#"
                                        onclick="selectDropdownOption(this, 'passed_away_reason', 'living_memory')">Living
                                        memory
                                    </a></li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="selectDropdownOption(this, 'passed_away_reason', 'passed_away')">
                                        Passed Away
                                        Memory</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="passed_away_reason" id="passed_away_reason" value="living_memory">
                    </div>
                </div>

                <!-- First & Middle Name -->
                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label">First name *</label>
                        <input type="text" name="first_name" class="form-control w-100">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Middle name</label>
                        <input type="text" name="middle_name" class="form-control w-100">
                    </div>
                </div>

                <!-- Last Name & Gender -->
                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Last name *</label>
                        <input type="text" name="last_name" class="form-control w-100">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Gender</label>
                        <div class="mt-2">
                            <input type="radio" name="gender" value="Male" id="male"> <label
                                for="male" class="me-3">Male</label>
                            <input type="radio" name="gender" value="Female" id="female"> <label
                                for="female">Female</label>
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

                <!-- Memorial Web Address -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Memorial web address:</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text font-monospace small">https://</span>
                        <input type="text" name="web_address" class="form-control" placeholder="your-loved-name">
                        <span class="input-group-text font-monospace small text-truncate">.forevermissed.com</span>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-gold px-5" onclick="switchStep(2)">Continue</button>
                </div>
            </div>


            <!-- STEP 2: Privacy Options -->
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

                    <!-- Private Details Box -->
                    <div id="privateSettingsBox" class="p-3 border rounded bg-light mt-3 d-none">
                        <!-- Username Field -->
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

                        <!-- Password Field -->
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
                    <div class="mb-4 p-3 border rounded bg-white mt-4">
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

                            @php
                                $packages = $packages ?? \App\Models\Package::where('is_active', true)->get();
                            @endphp

                            <!-- Dynamic Pricing Cards Grid -->
                            <div class="row g-4 justify-content-center mb-5" id="packagesGrid">
                                @forelse($packages as $pkg)
                                    @php
                                        $isFree = ((float) $pkg->price <= 0);
                                        $isFeatured = ($pkg->billing_type === 'yearly') || (stripos($pkg->name, 'premium') !== false);
                                        $currSymbol = strtolower($pkg->currency ?? 'usd') === 'usd' ? '$' : strtoupper($pkg->currency ?? 'USD') . ' ';
                                    @endphp
                                    <div class="col-12 col-md-6 col-lg-4 package-col" data-billing="{{ $pkg->billing_type }}" data-price="{{ $pkg->price }}">
                                        <div class="pricing-card {{ $isFeatured ? 'card-featured' : '' }} h-100 d-flex flex-column p-4 text-center"
                                             id="package-card-{{ $pkg->id }}"
                                             onclick="selectPackage({{ $pkg->id }}, {{ $pkg->price }}, '{{ addslashes($pkg->name) }}', '{{ $pkg->currency ?? 'USD' }}')">

                                            @if($isFeatured)
                                                <span class="popular-badge">Most Popular</span>
                                            @endif

                                            <span class="plan-subtitle text-uppercase font-serif {{ $isFeatured ? 'text-brown' : 'text-muted' }} fw-bold">
                                                {{ $pkg->name }}
                                            </span>

                                            <div class="price-wrapper my-3">
                                                @if($isFree)
                                                    <span class="price-val fw-bold display-5 text-brown">Free</span>
                                                @else
                                                    <span class="currency">{{ $currSymbol }}</span>
                                                    <span class="price-val fw-bold display-5 text-brown">{{ number_format($pkg->price, 2) }}</span>
                                                    <span class="period text-muted">
                                                        @if($pkg->billing_type === 'yearly')
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

                                            @if($pkg->trial_days > 0)
                                                <span class="trial-note small text-gold mb-2 fw-semibold">
                                                    <i class="bi bi-clock-history me-1"></i>{{ $pkg->trial_days }}-day free trial
                                                </span>
                                            @else
                                                <span class="trial-note small text-muted mb-2">&nbsp;</span>
                                            @endif

                                            <!-- Limits & Quotas -->
                                            <ul class="list-unstyled text-start small text-muted my-3 px-2 flex-grow-1 border-top border-bottom py-3">
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->memories_limit == -1 ? 'Unlimited' : $pkg->memories_limit }}</strong> Memorial {{ Str::plural('Space', $pkg->memories_limit) }}</span>
                                                </li>
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->max_photos == -1 ? 'Unlimited' : $pkg->max_photos }}</strong> Photos</span>
                                                </li>
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->max_videos == -1 ? 'Unlimited' : $pkg->max_videos }}</strong> Videos</span>
                                                </li>
                                                <li class="mb-2 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->max_audios == -1 ? 'Unlimited' : $pkg->max_audios }}</strong> Audio Tracks</span>
                                                </li>
                                                @if($pkg->events_limit)
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill text-gold me-2 fs-6"></i>
                                                    <span><strong>{{ $pkg->events_limit == -1 ? 'Unlimited' : $pkg->events_limit }}</strong> Events</span>
                                                </li>
                                                @endif
                                            </ul>

                                            <!-- Hidden Package Radio -->
                                            <input type="radio" name="package_id" id="pkg_radio_{{ $pkg->id }}" value="{{ $pkg->id }}" class="d-none package-radio" {{ (old('package_id', optional($packages->first())->id) == $pkg->id) ? 'checked' : '' }}>

                                            <!-- Select Plan Button -->
                                            <button type="button" class="btn {{ $isFeatured ? 'btn-gold shadow-sm' : ($isFree ? 'btn-outline-brown' : 'btn-outline-gold') }} w-100 mt-auto py-2 fw-bold text-uppercase plan-select-btn" id="btn-select-{{ $pkg->id }}">
                                                <span class="btn-text">Select Plan</span>
                                            </button>

                                            @if($isFree)
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
                                        <p class="text-muted">No memorial packages are currently active. Please contact support.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Payment Details Section (Stripe Integration) -->
                            <div id="paymentDetailsSection" class="p-4 p-md-5 rounded-4 border bg-white mb-5 shadow-sm d-none">
                                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom flex-wrap gap-2">
                                    <div>
                                        <h4 class="font-serif text-brown fw-bold mb-1">
                                            <i class="bi bi-credit-card-2-front text-gold me-2"></i>Payment Details
                                        </h4>
                                        <p class="text-muted small mb-0">Enter your card credentials to activate your memorial package safely.</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            <i class="bi bi-lock-fill text-success me-1"></i> Stripe Secured Checkout
                                        </span>
                                    </div>
                                </div>

                                <!-- Selected Plan Summary Banner -->
                                <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4 py-3 px-4 rounded-3">
                                    <div>
                                        <span class="text-muted small d-block">Selected Plan:</span>
                                        <strong class="text-brown fs-5" id="summaryPlanName">-</strong>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted small d-block">Total Due Today:</span>
                                        <span class="fs-4 fw-bold text-brown" id="summaryPlanPrice">$0.00</span>
                                    </div>
                                </div>

                                <!-- STRIPE PAYMENT FIELDS -->
                                <div class="row g-3">
                                    <!-- Optional Name on Card Field -->
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold small text-brown">Name on Card <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                            <!-- Name attribute is removed to keep it out of server request, handled locally -->
                                            <input type="text" id="card_name" class="form-control" placeholder="Full name on card" autocomplete="cc-name">
                                        </div>
                                    </div>

                                    <!-- Stripe Elements Container -->
                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-brown">Card Details <span class="text-danger">*</span></label>
                                        <!-- Stripe Elements Placeholder -->
                                        <div id="card-element" class="form-control p-3 bg-light" style="height: auto;">
                                            <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert" class="text-danger mt-2 small fw-bold"></div>
                                    </div>

                                    <!-- Hidden input for Stripe Token -->
                                    <input type="hidden" name="stripeToken" id="stripeToken">

                                    <div class="col-12 mt-3">
                                        <div class="small text-muted d-flex align-items-center">
                                            <i class="bi bi-shield-check text-success fs-5 me-2"></i>
                                            <span>Your card details are tokenized and securely processed directly by Stripe. We do not store sensitive payment information on our servers.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Plan Selected Notice -->
                            <div id="freePlanNotice" class="p-4 p-md-5 rounded-4 border bg-white mb-5 shadow-sm text-center d-none">
                                <div class="d-inline-flex p-3 rounded-circle bg-light border mb-3 shadow-sm">
                                    <i class="bi bi-gift-fill text-gold fs-2"></i>
                                </div>
                                <h4 class="font-serif fw-bold text-brown mb-2">Free Plan Selected</h4>
                                <p class="text-muted small mb-0 max-w-md mx-auto">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i> No credit card or payment details required. Click below to activate your memorial immediately with essential core features.
                                </p>
                            </div>

                            <!-- Features Comparison Table -->
                            <div class="comparison-wrapper rounded-4 p-3 p-md-5">
                                <h3 class="font-serif text-brown text-center mb-4 fs-4 fw-bold">Compare Features &
                                    Capabilities</h3>

                                <!-- Mobile Swipe Indicator Helper -->
                                <div class="d-block d-md-none text-center text-muted small mb-2 fst-italic">
                                    <i class="bi bi-arrow-left-right me-1"></i> Swipe table horizontally to view all
                                    plans
                                </div>

                                <!-- Responsive Table Container with Horizontal Scroll Support -->
                                <div class="table-responsive-custom">
                                    <table class="table table-borderless text-center align-middle pricing-table mb-0">
                                        <thead>
                                            <tr class="border-bottom">
                                                <th
                                                    class="text-start fs-6 font-serif text-brown pb-3 feature-col-header">
                                                    Website Features</th>
                                                <th class="pb-3 text-muted plan-col-header" style="width: 20%;">Basic
                                                </th>
                                                <th class="pb-3 text-gold-dark fw-bold plan-col-header"
                                                    style="width: 20%;">Premium</th>
                                                <th class="pb-3 text-brown fw-bold plan-col-header"
                                                    style="width: 20%;">
                                                    Lifetime</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-start feature-col">
                                                    <strong class="d-block text-dark">No Ads</strong>
                                                    <span class="text-muted small">Preserved memorial space completely
                                                        ad-free.</span>
                                                </td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                            </tr>

                                            <tr>
                                                <td class="text-start feature-col">
                                                    <strong class="d-block text-dark">Beautiful Themes</strong>
                                                    <span class="text-muted small">Access full library of themes to
                                                        personalize pages.</span>
                                                </td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                            </tr>

                                            <tr>
                                                <td class="text-start feature-col">
                                                    <strong class="d-block text-dark">Photo Gallery</strong>
                                                    <span class="text-muted small">Add photos from phone, Google
                                                        Photos,
                                                        or computer.</span>
                                                </td>
                                                <td><span class="badge bg-light text-dark fw-normal">5 Photos</span>
                                                </td>
                                                <td><span
                                                        class="badge bg-gold-light text-dark fw-semibold">Unlimited</span>
                                                </td>
                                                <td><span
                                                        class="badge bg-gold-light text-dark fw-semibold">Unlimited</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start feature-col">
                                                    <strong class="d-block text-dark">Background Music & Video
                                                        Galleries</strong>
                                                    <span class="text-muted small">Embed media clips and custom audio
                                                        tracks.</span>
                                                </td>
                                                <td><i class="bi bi-dash-lg text-muted"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                            </tr>

                                            <tr>
                                                <td class="text-start feature-col">
                                                    <strong class="d-block text-dark">Advanced Privacy
                                                        Controls</strong>
                                                    <span class="text-muted small">Keep memorials private or invite
                                                        selected contributors.</span>
                                                </td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                                <td><i class="bi bi-check-circle-fill text-gold"></i></td>
                                            </tr>

                                            <!-- Never Expires Row -->
                                            <tr class="border-top">
                                                <td class="text-start pt-4 feature-col">
                                                    <strong class="d-block text-dark">Never Expires</strong>
                                                    <span class="text-muted small">Make a one-time payment to keep your
                                                        memorial online permanently - no expiration date or extra
                                                        fees.</span>
                                                </td>
                                                <td class="pt-4"><i class="bi bi-dash-lg text-muted"></i></td>
                                                <td class="pt-4"><i class="bi bi-dash-lg text-muted"></i></td>
                                                <td class="pt-4"><i
                                                        class="bi bi-check-circle-fill text-gold fs-5"></i>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <!-- Table Foot Inside Table -->
                                        <tfoot>
                                            <tr class="border-top table-action-row-tr">
                                                <td class="text-start pt-4 feature-col align-top">
                                                    <span class="d-block fw-bold text-brown font-serif">Get
                                                        Started</span>
                                                    <span class="text-muted small">Choose the right plan to
                                                        proceed</span>
                                                </td>

                                                <!-- Basic Plan Column Footer -->
                                                <td class="pt-4 align-top">
                                                    <div class="table-col-action p-2">
                                                        <span class="d-block text-muted small">Get started for
                                                            free</span>
                                                        <span class="fs-4 fw-bold text-dark d-block my-1">Free</span>
                                                        <div class="mt-2">
                                                            <a href="javascript:void(0)" onclick="selectPackageByType('free')"
                                                                class="btn btn-secondary btn-sm w-100 fw-semibold text-uppercase py-2">Select</a>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Premium Plan Column Footer -->
                                                <td class="pt-4 align-top">
                                                    <div class="table-col-action highlight-table-action p-3 rounded-3">
                                                        <span class="d-block text-gold small fw-bold">14-day
                                                            trial</span>
                                                        <span class="fs-4 fw-bold text-brown d-block my-1">$79.<span
                                                                class="fs-6">95</span><span
                                                                class="small text-muted fw-normal">/yr</span></span>
                                                        <div class="mt-2">
                                                            <a href="javascript:void(0)" onclick="selectPackageByType('yearly')"
                                                                class="btn btn-gold btn-sm w-100 fw-semibold text-uppercase py-2 shadow-sm">Start
                                                                Trial</a>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Lifetime Plan Column Footer -->
                                                <td class="pt-4 align-top">
                                                    <div class="table-col-action p-2">
                                                        <span class="d-block text-muted small">14-day trial</span>
                                                        <span class="fs-4 fw-bold text-brown d-block my-1">$159.<span
                                                                class="fs-6">95</span></span>
                                                        <div class="mt-2">
                                                            <a href="javascript:void(0)" onclick="selectPackageByType('lifetime')"
                                                                class="btn btn-outline-gold btn-sm w-100 fw-semibold text-uppercase py-2">Start
                                                                Trial</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Guarantee and Security Footer Section -->
                                <div class="row mt-5 pt-4 border-top align-items-center g-4">
                                    <div class="col-12 col-lg-5">
                                        <h6 class="font-serif text-brown fw-bold text-uppercase mb-2"
                                            style="font-size: 0.85rem; letter-spacing: 0.5px;">30-Day Money-Back
                                            Guarantee</h6>
                                        <p class="text-muted small mb-0">We want you to be completely satisfied with
                                            your memorial. If you wish to cancel your Premium or Lifetime plan for any
                                            reason within the first 30 days, we will issue a full refund.</p>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <h6 class="font-serif text-brown fw-bold text-uppercase mb-2"
                                            style="font-size: 0.85rem; letter-spacing: 0.5px;">Purchase with Confidence
                                        </h6>
                                        <p class="text-muted small mb-0">Protects the security of your information with
                                            the latest encryption techniques and Secure Socket Layer (SSL) transmission
                                            protocol.</p>
                                    </div>

                                    <div class="col-12 col-lg-3 text-lg-end text-center">
                                        <div
                                            class="ssl-badge-box d-inline-flex align-items-center justify-content-center p-2 rounded bg-light border">
                                            <i class="bi bi-shield-lock-fill text-warning fs-4 me-2"></i>
                                            <div class="text-start">
                                                <span class="d-block fw-bold text-dark"
                                                    style="font-size: 0.75rem; line-height: 1;">SSL SECURE</span>
                                                <span class="text-muted" style="font-size: 0.65rem;">PROTECTED
                                                    CONNECTION</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3 flex-wrap gap-2">
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="switchStep(2)">
                        <i class="bi bi-arrow-left me-1"></i> Back to Step 2
                    </button>
                    <button type="submit" class="btn btn-gold px-5 py-2 fw-bold text-uppercase shadow-sm" id="btn-submit-step3">
                        <span id="btn-submit-text">Complete & Activate / Purchase</span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>

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
        function togglePrivacySettings() {
            const isPrivate = document.getElementById('private').checked;
            const privateBox = document.getElementById('privateSettingsBox');

            if (isPrivate) {
                privateBox.classList.remove('d-none');
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

        function generateRandomUsername() {
            const randomNames = ["GuestUser", "FamilyVisitor", "Visitor99", "FriendMemorial", "AnonymousUser",
                "MemoryKeeper"
            ];
            const randomName = randomNames[Math.floor(Math.random() * randomNames.length)] + Math.floor(Math.random() *
                1000);
            document.getElementById('private_username').value = randomName;
        }

        function generateRandomPassword() {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!";
            let password = "";
            for (let i = 0; i < 10; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('private_password').value = password;
        }

        function copyPassword() {
            const passwordInput = document.getElementById('private_password');
            passwordInput.select();
            passwordInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(passwordInput.value).then(() => {
                const alertBox = document.getElementById('copyAlert');
                alertBox.classList.remove('d-none');
                setTimeout(() => {
                    alertBox.classList.add('d-none');
                }, 2000);
            });
        }

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

    <!-- Step 3 Plan Selection Scripts -->
    <script>
        let currentSelectedPkgId = null;

        function selectPackage(pkgId, price, name, currency) {
            currentSelectedPkgId = pkgId;
            const numPrice = parseFloat(price);

            // 1. Radio check
            const radio = document.getElementById('pkg_radio_' + pkgId);
            if (radio) {
                radio.checked = true;
            }

            // 2. Styling cards
            document.querySelectorAll('.pricing-card').forEach(card => {
                card.classList.remove('selected-plan');
                const btn = card.querySelector('.plan-select-btn .btn-text');
                if (btn) btn.textContent = 'Select Plan';
            });

            const activeCard = document.getElementById('package-card-' + pkgId);
            if (activeCard) {
                activeCard.classList.add('selected-plan');
                const activeBtn = activeCard.querySelector('.plan-select-btn .btn-text');
                if (activeBtn) activeBtn.textContent = 'Selected ✓';
            }

            // 3. Summary info
            const summaryName = document.getElementById('summaryPlanName');
            const summaryPrice = document.getElementById('summaryPlanPrice');
            const submitBtnText = document.getElementById('btn-submit-text');
            const paymentSection = document.getElementById('paymentDetailsSection');
            const freeNotice = document.getElementById('freePlanNotice');

            const currSymbol = (currency && currency.toLowerCase() === 'usd') ? '$' : (currency ? currency.toUpperCase() + ' ' : '$');

            if (summaryName) summaryName.textContent = name;
            if (summaryPrice) {
                summaryPrice.textContent = (numPrice <= 0) ? 'Free ($0.00)' : currSymbol + numPrice.toFixed(2);
            }

            // 4. Toggle Payment vs Free
            if (numPrice > 0) {
                if (paymentSection) paymentSection.classList.remove('d-none');
                if (freeNotice) freeNotice.classList.add('d-none');
                if (submitBtnText) submitBtnText.textContent = 'Complete & Purchase (' + currSymbol + numPrice.toFixed(2) + ')';
                setPaymentFieldsRequired(true);
            } else {
                if (paymentSection) paymentSection.classList.add('d-none');
                if (freeNotice) freeNotice.classList.remove('d-none');
                if (submitBtnText) submitBtnText.textContent = 'Complete & Activate Free Plan';
                setPaymentFieldsRequired(false);
            }
        }

        function setPaymentFieldsRequired(isRequired) {
            const fields = ['card_name']; // Removed card_number, cvv, expiry_date as Stripe Element handles them
            fields.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.required = isRequired;
                }
            });
        }

        function filterBilling(type) {
            const buttons = ['btn-all-plans', 'btn-monthly', 'btn-yearly'];
            buttons.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.classList.remove('active');
            });

            const activeBtn = document.getElementById('btn-' + (type === 'all' ? 'all-plans' : type));
            if (activeBtn) activeBtn.classList.add('active');

            const cols = document.querySelectorAll('.package-col');
            cols.forEach(col => {
                const billing = col.getAttribute('data-billing');
                const price = parseFloat(col.getAttribute('data-price') || 0);

                if (type === 'all') {
                    col.classList.remove('d-none');
                } else if (type === 'monthly') {
                    if (billing === 'monthly' || price === 0 || billing === 'lifetime') {
                        col.classList.remove('d-none');
                    } else {
                        col.classList.add('d-none');
                    }
                } else if (type === 'yearly') {
                    if (billing === 'yearly' || price === 0 || billing === 'lifetime') {
                        col.classList.remove('d-none');
                    } else {
                        col.classList.add('d-none');
                    }
                }
            });
        }

        function selectPackageByType(type) {
            let targetCard = null;
            if (type === 'free') {
                targetCard = document.querySelector('.package-col[data-price="0"] .pricing-card, .package-col[data-price="0.00"] .pricing-card');
            } else if (type === 'yearly') {
                targetCard = document.querySelector('.package-col[data-billing="yearly"] .pricing-card');
            } else if (type === 'lifetime') {
                targetCard = document.querySelector('.package-col[data-billing="lifetime"]:not([data-price="0"]):not([data-price="0.00"]) .pricing-card');
            }

            if (targetCard) {
                targetCard.click();
                const paymentSection = document.getElementById('paymentDetailsSection');
                const freeNotice = document.getElementById('freePlanNotice');
                const targetElement = (type === 'free') ? freeNotice : paymentSection;
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    </script>

    <!-- Stripe API Integration Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Automatically select the default package
            const defaultRadio = document.querySelector('.package-radio:checked') || document.querySelector('.package-radio');
            if (defaultRadio) {
                const card = defaultRadio.closest('.pricing-card');
                if (card) {
                    card.click();
                }
            }

            @if (isset($errors) && ($errors->has('package_id') || $errors->has('stripeToken')))
                switchStep(3);
            @endif

            // ==========================================
            // STRIPE ELEMENTS SETUP
            // ==========================================

            // Set up Stripe.js and Elements to use in checkout form
            var stripe = Stripe('{{ env("STRIPE_KEY") }}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {style: style});

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.on('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {

                // Check if the currently selected plan is a paid plan
                var activePackageCol = document.querySelector('.package-col #package-card-' + currentSelectedPkgId);
                var numPrice = 0;

                if (activePackageCol) {
                    numPrice = parseFloat(activePackageCol.closest('.package-col').getAttribute('data-price') || 0);
                }

                var isPaidPlan = numPrice > 0;

                if (isPaidPlan) {
                    event.preventDefault(); // Stop normal form submit

                    // Show Loading state on button
                    var submitBtnText = document.getElementById('btn-submit-text');
                    var submitBtn = document.getElementById('btn-submit-step3');
                    var originalText = submitBtnText.textContent;

                    submitBtnText.textContent = 'Processing Payment...';
                    submitBtn.disabled = true;

                    // Get cardholder name
                    var cardName = document.getElementById('card_name').value;

                    stripe.createToken(card, { name: cardName }).then(function(result) {
                        if (result.error) {
                            // Inform the user if there was an error.
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;

                            // Re-enable button
                            submitBtnText.textContent = originalText;
                            submitBtn.disabled = false;
                        } else {
                            // Send the token to your server.
                            document.getElementById('stripeToken').value = result.token.id;
                            form.submit();
                        }
                    });
                }
                // If it's a free plan, form will submit normally bypassing Stripe
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
