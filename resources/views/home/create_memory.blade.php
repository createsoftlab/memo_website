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
            <p class="lead mb-0 text-light-tan">Have questions or need assistance? Reach out to our dedicated support team anytime.</p>
        </div>
    </header>

    <!-- Stepper Navigation -->
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
        <!-- FORM STARTS HERE -->
        <form action="{{ route('home.create_memory.store') }}" method="POST" id="payment-form">
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

                @php
                    $selectedStatus = old('memorial_status', 'passed_away');
                    $selectedReason = old('passed_away_reason', '');
                    $reasons = [ 'Natural Causes', 'Old Age', 'Cancer', 'Heart Disease', 'Heart Attack', 'Stroke', 'COVID-19', 'Other Illness', 'Accident', 'Road Accident', 'Natural Disaster', 'Suicide', 'Homicide', 'Other', 'Unknown', 'Prefer Not to Disclose' ];
                @endphp

                <div class="mb-3 row">
                    <div class="mb-3 col-12 col-md-6 mb-md-0">
                        <label class="form-label fw-bold">Memorial Status *</label>
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom @error('memorial_status') is-invalid @enderror" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">
                                    {{ $selectedStatus === 'passed_away' ? 'Passed Away' : 'Living Memory' }}
                                </span>
                                <i class="flex-shrink-0 bi bi-chevron-down ms-2"></i>
                            </button>
                            <ul class="shadow-sm dropdown-menu w-100">
                                <li><a class="dropdown-item {{ $selectedStatus === 'passed_away' ? 'active' : '' }}" href="#" onclick="event.preventDefault(); selectDropdownOption(this, 'memorial_status', 'passed_away')">Passed Away</a></li>
                                <li><a class="dropdown-item {{ $selectedStatus === 'living_memory' ? 'active' : '' }}" href="#" onclick="event.preventDefault(); selectDropdownOption(this, 'memorial_status', 'living_memory')">Living Memory</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="memorial_status" id="memorial_status" value="{{ $selectedStatus }}">
                    </div>

                    <div class="col-12 col-md-6 {{ $selectedStatus === 'living_memory' ? 'd-none' : '' }}" id="passedAwayReasonContainer">
                        <label class="form-label fw-bold">Passed Away Reason</label>
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom @error('passed_away_reason') is-invalid @enderror" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">{{ $selectedReason ?: 'Select Passed Away Reason' }}</span>
                                <i class="flex-shrink-0 bi bi-chevron-down ms-2"></i>
                            </button>
                            <ul class="shadow-sm dropdown-menu w-100">
                                @foreach ($reasons as $reason)
                                    <li><a class="dropdown-item {{ $selectedReason === $reason ? 'active' : '' }}" href="#" onclick="event.preventDefault(); selectDropdownOption(this, 'passed_away_reason', @js($reason))">{{ $reason }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <input type="hidden" name="passed_away_reason" id="passed_away_reason" value="{{ $selectedReason }}" {{ $selectedStatus === 'living_memory' ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label">First name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control w-100">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Middle name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control w-100">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label">Last name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control w-100">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Gender</label>
                        <div class="mt-2">
                            <input type="radio" name="gender" value="Male" id="male" @checked(old('gender') == 'Male')>
                            <label for="male" class="me-3">Male</label>
                            <input type="radio" name="gender" value="Female" id="female" @checked(old('gender') == 'Female')>
                            <label for="female">Female</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label fw-bold">Country *</label>
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">🇱🇰 Sri Lanka</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm" style="max-height: 250px; overflow-y: auto;">
                                <li><a class="dropdown-item active" href="#" onclick="selectDropdownOption(this, 'account_country', 'Sri Lanka')">Sri Lanka</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'account_country', 'United State')">United States</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="country" id="account_country" value="LK">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold" id="regionLabel">City / Town *</label>
                        <input type="text" name="city" id="stateProvinceInput" class="form-control w-100 py-2" placeholder="Enter your District , Province or state ">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">Relationship</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item active" href="#" onclick="selectDropdownOption(this, 'relationship', '')">Relationship</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'relationship', 'Parent')">Parent</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'relationship', 'Spouse')">Spouse</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'relationship', 'Child')">Child</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'relationship', 'Friend')">Friend</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="relationship" id="relationship" value="">
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border text-truncate py-2 px-3 dropdown-toggle-custom" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate">Special designation (if applies)</span>
                                <i class="bi bi-chevron-down flex-shrink-0 ms-2"></i>
                            </button>
                            <ul class="dropdown-menu w-100 shadow-sm">
                                <li><a class="dropdown-item active" href="#" onclick="selectDropdownOption(this, 'designation', '')">Special designation</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'designation', 'Veteran')">Veteran</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdownOption(this, 'designation', 'First Responder')">First Responder</a></li>
                            </ul>
                        </div>
                        <input type="hidden" name="designation" id="designation" value="">
                    </div>
                </div>

                <div class="mb-4">
                    <a class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center py-2 px-3 dropdown-toggle-btn text-decoration-none" data-bs-toggle="collapse" href="#moreDetailsBox" role="button" aria-expanded="false" aria-controls="moreDetailsBox">
                        <span><i class="bi bi-chevron-down me-2"></i> More details (optional)</span>
                    </a>
                    <div class="collapse mt-3" id="moreDetailsBox">
                        <div class="info-box p-3 p-md-4 border rounded bg-light">
                            <p class="fw-bold mb-3 text-secondary">This information can also be updated at a later time:</p>
                            <label class="form-label small text-muted">Born</label>
                            <div class="row mb-3">
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="birth_year" class="form-control" placeholder="Year"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="birth_month" class="form-control" placeholder="Month"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="birth_day" class="form-control" placeholder="Day"></div>
                                <div class="col-12 col-md-3 mb-2"><input type="text" name="birth_location" class="form-control" placeholder="City or town"></div>
                            </div>
                            <label class="form-label small text-muted">Passed away</label>
                            <div class="row">
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="passed_year" class="form-control" placeholder="Year"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="passed_month" class="form-control" placeholder="Month"></div>
                                <div class="col-6 col-md-2 mb-2"><input type="number" name="passed_day" class="form-control" placeholder="Day"></div>
                                <div class="col-12 col-md-3 mb-2"><input type="text" name="death_location" class="form-control" placeholder="City or town"></div>
                            </div>
                        </div>
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
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="privacy" value="public" id="public" checked onchange="togglePrivacySettings()">
                        <label class="form-check-label fw-bold" for="public">Public Memorial</label>
                        <p class="small text-muted">Anyone can find and view this memorial website.</p>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="privacy" value="private" id="private" onchange="togglePrivacySettings()">
                        <label class="form-check-label fw-bold" for="private">Private (Password Protected)</label>
                        <p class="small text-muted">Only people with the password can access the memorial.</p>
                    </div>

                    <div id="privateSettingsBox" class="p-3 border rounded bg-light mt-3 d-none">
                        <div class="mb-3">
                            <label for="private_username" class="form-label fw-bold small">Access Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="private_username" name="private_username" placeholder="Type custom name or click random">
                                <button class="btn btn-outline-secondary" type="button" onclick="generateRandomUsername()" title="Generate Random Name">
                                    <i class="bi bi-shuffle"></i> Random
                                </button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="private_password" class="form-label fw-bold small">Access Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" id="private_password" name="private_password" placeholder="Type custom password or generate">
                                <button class="btn btn-outline-secondary" type="button" onclick="generateRandomPassword()" title="Generate Random Password"><i class="bi bi-shuffle"></i> Random</button>
                                <button class="btn btn-outline-primary" type="button" onclick="copyPassword()" title="Copy Password"><i class="bi bi-clipboard"></i> Copy</button>
                                <button class="btn btn-outline-success" type="button" onclick="sharePassword()" title="Share Details"><i class="bi bi-share"></i> Share</button>
                            </div>
                            <div id="copyAlert" class="small text-success mt-1 d-none">Password copied to clipboard!</div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 border rounded bg-white mt-4">
                        <label class="form-label fw-bold mb-3">Memorial Publication Status</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" value="published" id="statusPublish" checked>
                            <label class="form-check-label fw-bold text-success" for="statusPublish"><i class="bi bi-globe me-1"></i> Publish Now</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="draft" id="statusDraft">
                            <label class="form-check-label fw-bold text-secondary" for="statusDraft"><i class="bi bi-file-earmark-text me-1"></i> Save as Draft</label>
                        </div>
                    </div>
                </div>

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

                            <div class="text-center mb-5">
                                <h2 class="font-serif text-brown fw-bold display-6 mb-2 text-uppercase">Choose Your Memorial Plan</h2>
                                <div class="pricing-toggle-wrapper mx-auto d-inline-flex align-items-center p-1 rounded-pill mt-3">
                                    <button type="button" class="btn btn-toggle active" id="btn-all-plans" onclick="filterBilling('all')">All Plans</button>
                                    <button type="button" class="btn btn-toggle" id="btn-monthly" onclick="filterBilling('monthly')">Monthly</button>
                                    <button type="button" class="btn btn-toggle" id="btn-yearly" onclick="filterBilling('yearly')">Yearly <span class="badge badge-discount ms-1">Save 15%</span></button>
                                </div>
                            </div>

                            @php
                                $packages = $packages ?? \App\Models\Package::where('is_active', true)->get();
                            @endphp

                            <!-- Dynamic Pricing Cards Grid -->
                            <div class="row g-4 justify-content-center mb-5" id="packagesGrid">
                                @forelse($packages as $pkg)
                                    @php
                                        $isFree = (float) $pkg->price <= 0;
                                        $isFeatured = $pkg->billing_type === 'yearly' || stripos($pkg->name, 'premium') !== false;
                                        $currSymbol = strtolower($pkg->currency ?? 'usd') === 'usd' ? '$' : strtoupper($pkg->currency ?? 'USD') . ' ';
                                    @endphp
                                    <div class="col-12 col-md-6 col-lg-4 package-col" data-billing="{{ $pkg->billing_type }}" data-price="{{ $pkg->price }}">
                                        <div class="pricing-card {{ $isFeatured ? 'card-featured' : '' }} h-100 d-flex flex-column p-4 text-center"
                                            id="package-card-{{ $pkg->id }}"
                                            onclick="selectPackage({{ $pkg->id }}, {{ $pkg->price }}, '{{ addslashes($pkg->name) }}', '{{ $pkg->currency ?? 'USD' }}')">

                                            @if ($isFeatured)
                                                <span class="popular-badge">Most Popular</span>
                                            @endif

                                            <span class="plan-subtitle text-uppercase font-serif {{ $isFeatured ? 'text-brown' : 'text-muted' }} fw-bold">
                                                {{ $pkg->name }}
                                            </span>

                                            <div class="price-wrapper my-3">
                                                @if ($isFree)
                                                    <span class="price-val fw-bold display-5 text-brown">Free</span>
                                                @else
                                                    <span class="currency">{{ $currSymbol }}</span>
                                                    <span class="price-val fw-bold display-5 text-brown">{{ number_format($pkg->price, 2) }}</span>
                                                    <span class="period text-muted">
                                                        @if ($pkg->billing_type === 'yearly') /yr
                                                        @elseif($pkg->billing_type === 'monthly') /mo
                                                        @elseif($pkg->billing_type === 'lifetime') <small class="text-muted d-block fs-6">one-time</small>
                                                        @elseif($pkg->duration_value) /{{ $pkg->duration_value }} {{ $pkg->duration_unit }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>

                                            <input type="radio" name="package_id" id="pkg_radio_{{ $pkg->id }}" value="{{ $pkg->id }}" class="d-none package-radio" {{ old('package_id', optional($packages->first())->id) == $pkg->id ? 'checked' : '' }}>

                                            <button type="button" class="btn {{ $isFeatured ? 'btn-gold shadow-sm' : ($isFree ? 'btn-outline-brown' : 'btn-outline-gold') }} w-100 mt-auto py-2 fw-bold text-uppercase plan-select-btn" id="btn-select-{{ $pkg->id }}">
                                                <span class="btn-text">Select Plan</span>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4">
                                        <p class="text-muted">No memorial packages are currently active. Please contact support.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- REDIRECT CHECKOUT BANNER (Shown when Paid Plan is Selected) -->
                            <div id="paymentDetailsSection" class="p-4 p-md-5 rounded-4 border bg-white mb-5 shadow-sm d-none">
                                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom flex-wrap gap-2">
                                    <div>
                                        <h4 class="font-serif text-brown fw-bold mb-1">
                                            <i class="bi bi-credit-card-2-front text-gold me-2"></i>Checkout Details
                                        </h4>
                                        <p class="text-muted small mb-0">You will be redirected to Stripe's secure checkout page to enter your payment details.</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            <i class="bi bi-lock-fill text-success me-1"></i> Stripe Secured Checkout
                                        </span>
                                    </div>
                                </div>

                                <div class="alert alert-light border d-flex justify-content-between align-items-center mb-0 py-3 px-4 rounded-3">
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
                            <div id="freePlanNotice" class="p-4 p-md-5 rounded-4 border bg-white mb-5 shadow-sm text-center d-none">
                                <div class="d-inline-flex p-3 rounded-circle bg-light border mb-3 shadow-sm">
                                    <i class="bi bi-gift-fill text-gold fs-2"></i>
                                </div>
                                <h4 class="font-serif fw-bold text-brown mb-2">Free Plan Selected</h4>
                                <p class="text-muted small mb-0 max-w-md mx-auto">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i> No credit card or payment details required. Click below to activate your memorial immediately.
                                </p>
                            </div>

                        </div>
                    </section>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3 flex-wrap gap-2">
                    <button type="button" class="btn btn-secondary px-4 py-2" onclick="switchStep(2)">
                        <i class="bi bi-arrow-left me-1"></i> Back to Step 2
                    </button>
                    <!-- Form Submit Button -->
                    <button type="submit" class="btn btn-gold px-5 py-2 fw-bold text-uppercase shadow-sm" id="btn-submit-step3">
                        <span id="btn-submit-text">Checkout</span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- General JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const memorialStatus = document.getElementById('memorial_status');
            const reasonContainer = document.getElementById('passedAwayReasonContainer');
            const reasonInput = document.getElementById('passed_away_reason');

            function togglePassedAwayReason() {
                const isPassedAway = memorialStatus.value === 'passed_away';
                reasonContainer.classList.toggle('d-none', !isPassedAway);
                reasonInput.disabled = !isPassedAway;
            }

            window.selectDropdownOption = function(element, inputId, value) {
                const input = document.getElementById(inputId);
                const dropdown = element.closest('.dropdown');
                const buttonText = dropdown.querySelector('button span');
                input.value = value;
                buttonText.textContent = element.textContent.trim();
                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    item.classList.remove('active');
                });
                element.classList.add('active');
                if (inputId === 'memorial_status') {
                    togglePassedAwayReason();
                }
            };

            togglePassedAwayReason();
        });

        function switchStep(stepNumber) {
            for (let i = 1; i <= 3; i++) {
                document.getElementById('step-content-' + i).classList.add('d-none');
                document.getElementById('tab-step-' + i).classList.remove('active');
            }
            document.getElementById('step-content-' + stepNumber).classList.remove('d-none');
            document.getElementById('tab-step-' + stepNumber).classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function togglePrivacySettings() {
            const isPrivate = document.getElementById('private').checked;
            const privateBox = document.getElementById('privateSettingsBox');
            if (isPrivate) {
                privateBox.classList.remove('d-none');
                if (!document.getElementById('private_password').value) generateRandomPassword();
                if (!document.getElementById('private_username').value) generateRandomUsername();
            } else {
                privateBox.classList.add('d-none');
            }
        }

        function generateRandomUsername() {
            const randomNames = ["GuestUser", "FamilyVisitor", "Visitor99", "FriendMemorial", "AnonymousUser", "MemoryKeeper"];
            const randomName = randomNames[Math.floor(Math.random() * randomNames.length)] + Math.floor(Math.random() * 1000);
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
                setTimeout(() => { alertBox.classList.add('d-none'); }, 2000);
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
                    } else { col.classList.add('d-none'); }
                } else if (type === 'yearly') {
                    if (billing === 'yearly' || price === 0 || billing === 'lifetime') {
                        col.classList.remove('d-none');
                    } else { col.classList.add('d-none'); }
                }
            });
        }

        // Package Selection & Button Logic
        let currentSelectedPkgId = null;

        function selectPackage(pkgId, price, name, currency) {
            currentSelectedPkgId = pkgId;
            const numPrice = parseFloat(price);

            const radio = document.getElementById('pkg_radio_' + pkgId);
            if (radio) radio.checked = true;

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

            // Update Submit Button Based on Free/Paid
            if (numPrice > 0) {
                if (paymentSection) paymentSection.classList.remove('d-none');
                if (freeNotice) freeNotice.classList.add('d-none');
                if (submitBtnText) submitBtnText.textContent = 'Checkout';
            } else {
                if (paymentSection) paymentSection.classList.add('d-none');
                if (freeNotice) freeNotice.classList.remove('d-none');
                if (submitBtnText) submitBtnText.textContent = 'Complete & Activate Free Plan';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const defaultRadio = document.querySelector('.package-radio:checked') || document.querySelector('.package-radio');
            if (defaultRadio) {
                const card = defaultRadio.closest('.pricing-card');
                if (card) card.click();
            }

            @if (isset($errors) && $errors->has('package_id'))
                switchStep(3);
            @endif
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
