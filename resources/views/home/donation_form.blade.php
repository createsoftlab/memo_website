<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $memory ??= null;
        $event ??= null;
        $currency ??= 'USD';

        $memorialName = trim(collect([
            $memory?->profile?->first_name,
            $memory?->profile?->middle_name,
            $memory?->profile?->last_name,
        ])->filter()->implode(' ')) ?: 'Cherished Loved One';

        $heroTitle = $event
            ? 'Event Donation: ' . $event->title
            : 'In Memory of ' . $memorialName;

        $defaultDonationFor = $event
            ? $event->title . ' (Memorial Event)'
            : 'In Memory of ' . $memorialName;

        $visibilityOld = old('donation_visibility', 'public');
        $visibilityLabel = $visibilityOld === 'private'
            ? 'Private (Do not display on site)'
            : 'Publish on Site (Visible to public)';
    @endphp
    <title>{{ $heroTitle }} - Forever Missed</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/donation.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/about.css') }}">
</head>

<body>

    @include('home.header')

    <!-- Hero Header -->
    <header class="donationform-hero d-flex align-items-center text-white mb-5">
        <div class="container py-4 text-center text-lg-start">
            <span class="badge bg-gold text-dark fw-bold mb-2 uppercase tracking-wide">
                {{ $event ? 'Event Contribution' : 'Memorial Contribution' }}
            </span>
            <h1 class="font-serif display-5 fw-bold mb-2">{{ $heroTitle }}</h1>
            <p class="lead mb-0 text-light-tan">
                @if($event)
                    Supporting this memorial gathering in loving memory of {{ $memorialName }}.
                @else
                    Your contribution helps support the family and preserve treasured memories forever.
                @endif
            </p>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-light">

                    <!-- Form Header -->
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark font-serif">
                            <i class="bi bi-gift text-memorial me-2"></i>
                            {{ $event ? 'Make an Event Donation' : 'Make a Memorial Donation' }}
                        </h3>
                        <p class="text-muted small">
                            @if($event)
                                Dedicated to <strong>{{ $event->title }}</strong>
                                @if(!empty($event->start_date))
                                    ({{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }})
                                @endif
                                — {{ $memorialName }}.
                            @else
                                Your contribution helps us honor memories of {{ $memorialName }} and support the family.
                            @endif
                        </p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 small mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 small mb-4" role="alert">
                            <strong class="d-block mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Please check the form fields:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Donation Form connected to Stripe -->
                    <form action="{{ route('donation.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="memory_id" value="{{ $memory?->id }}">
                        <input type="hidden" name="event_id" value="{{ $event->id ?? '' }}">
                        <input type="hidden" name="currency" value="{{ $currency }}">

                        <!-- 1. What is this donation for? -->
                        <div class="mb-3">
                            <label for="donationFor" class="form-label fw-bold text-dark">Donation For (Event / Memorial Name)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-memorial"><i class="bi bi-heart-fill"></i></span>
                                <input type="text"
                                       class="form-control @error('donation_for') is-invalid @enderror"
                                       id="donationFor"
                                       name="donation_for"
                                       value="{{ old('donation_for', $defaultDonationFor) }}"
                                       required>
                            </div>
                            <div class="form-text">
                                @if($event)
                                    <i class="bi bi-calendar-event me-1 text-memorial"></i> Event-specific contribution dedicated to <strong>{{ $event->title }}</strong>.
                                @else
                                    <i class="bi bi-flower1 me-1 text-memorial"></i> General memorial fund contribution in honor of <strong>{{ $memorialName }}</strong>.
                                @endif
                            </div>
                        </div>

                        <!-- 2. Donor's Name -->
                        <div class="mb-3">
                            <label for="donorName" class="form-label fw-bold text-dark">Your Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-memorial"><i class="bi bi-person-fill"></i></span>
                                <input type="text"
                                       class="form-control @error('donor_name') is-invalid @enderror"
                                       id="donorName"
                                       name="donor_name"
                                       value="{{ old('donor_name', Auth::user()?->name ?? '') }}"
                                       placeholder="Enter your full name"
                                       required>
                            </div>
                        </div>

                        <!-- 3. Email & Contact Number -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donorEmail" class="form-label fw-bold text-dark">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-memorial"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email"
                                           class="form-control @error('donor_email') is-invalid @enderror"
                                           id="donorEmail"
                                           name="donor_email"
                                           value="{{ old('donor_email', Auth::user()?->email ?? '') }}"
                                           placeholder="name@example.com"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="donorPhone" class="form-label fw-bold text-dark">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-memorial"><i class="bi bi-telephone-fill"></i></span>
                                    <input type="tel"
                                           class="form-control @error('donor_phone') is-invalid @enderror"
                                           id="donorPhone"
                                           name="donor_phone"
                                           value="{{ old('donor_phone', Auth::user()?->phone ?? '') }}"
                                           placeholder="+94 77 123 4567"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Donation Amount -->
                        <div class="mb-3">
                            <label for="donationAmount" class="form-label fw-bold text-dark">Donation Amount ({{ $currency }})</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-memorial fw-bold">{{ $currency }}</span>
                                <input type="number"
                                       class="form-control @error('donation_amount') is-invalid @enderror"
                                       id="donationAmount"
                                       name="donation_amount"
                                       value="{{ old('donation_amount') }}"
                                       placeholder="{{ strtoupper($currency) === 'LKR' ? '5000' : '50' }}"
                                       min="1"
                                       step="any"
                                       required>
                            </div>
                        </div>

                        <!-- 5. Referred By -->
                        <div class="mb-4">
                            <label for="referredBy" class="form-label fw-bold text-dark">Referred By (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-memorial"><i class="bi bi-person-badge-fill"></i></span>
                                <input type="text"
                                       class="form-control @error('referred_by') is-invalid @enderror"
                                       id="referredBy"
                                       name="referred_by"
                                       value="{{ old('referred_by') }}"
                                       placeholder="Type name if referred by someone">
                            </div>
                            <div class="form-text">Mention if you are sending this on behalf of a family or group.</div>
                        </div>

                        <!-- 6. Visibility Status Dropdown -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Donation Visibility</label>
                            <input type="hidden" id="donationVisibilityInput" name="donation_visibility" value="{{ $visibilityOld }}" required>

                            <div class="dropdown w-100">
                                <button class="btn custom-filter-input text-start d-flex justify-content-between align-items-center w-100 bg-white border"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        id="visibilityDropdownBtn">
                                    <span id="selectedVisibilityText">{{ $visibilityLabel }}</span>
                                    <i class="bi bi-chevron-down small text-secondary"></i>
                                </button>
                                <ul class="dropdown-menu w-100 shadow-sm border-card bg-white">
                                    <li>
                                        <a class="dropdown-item py-2" href="#"
                                           onclick="selectVisibility(event, 'public', 'Publish on Site (Visible to public)')">
                                            Publish on Site (Visible to public)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="#"
                                           onclick="selectVisibility(event, 'private', 'Private (Do not display on site)')">
                                            Private (Do not display on site)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-text text-muted small mt-2">
                                <i class="bi bi-info-circle me-1"></i> If set to <strong>Publish</strong>, your donation will be displayed on the site. If set to <strong>Private</strong>, it will remain hidden.
                            </div>
                        </div>

                        <!-- 7. Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-memorial-submit py-3 text-white fw-bold shadow-sm">
                                <i class="bi bi-credit-card-fill me-2"></i> Donate Now (Online Payment)
                            </button>

                            <button type="button" class="btn btn-outline-dark py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#bankTransferModal">
                                <i class="bi bi-bank me-2"></i> Direct Bank Transfer Details
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Bank Transfer Details Modal -->
    <div class="modal fade" id="bankTransferModal" tabindex="-1" aria-labelledby="bankTransferModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-memorial-dark text-white">
                    <h5 class="modal-title fw-bold" id="bankTransferModalLabel">
                        <i class="bi bi-bank me-2"></i>Direct Bank Transfer Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <p class="text-muted small mb-3">
                        You can directly transfer your donation to the official memorial bank account below and share the receipt via WhatsApp.
                    </p>
                    <ul class="list-unstyled mb-3 text-dark small">
                        <li class="mb-2"><strong>Bank Name:</strong> Commercial Bank</li>
                        <li class="mb-2"><strong>Account Name:</strong> Forever Missed Memorial Fund</li>
                        <li class="mb-2"><strong>Account Number:</strong> 1002345678</li>
                        <li class="mb-2"><strong>Branch:</strong> Borella Branch</li>
                    </ul>
                    <div class="d-grid">
                        <a href="https://wa.me/94771234567?text={{ urlencode('Hello, I have made a direct bank transfer for: ' . $defaultDonationFor . '. Here is my receipt.') }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn btn-success btn-sm fw-bold py-2">
                            <i class="bi bi-whatsapp me-1"></i> Send Bank Slip via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('home.footer')

    <script>
        function selectVisibility(event, value, text) {
            event.preventDefault();
            document.getElementById('donationVisibilityInput').value = value;
            document.getElementById('selectedVisibilityText').innerText = text;
        }
    </script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
