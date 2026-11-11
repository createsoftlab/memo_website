<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - {{ $memory->profile?->first_name }} {{ $memory->profile?->last_name }} Memorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/tribute.css') }}">
</head>

<body>

    @php
        $backgroundMusic = \App\Models\Music::where('profile_id', $memory->profile?->id)->first();
        $profileImage = \App\Models\Image::where('profile_id', $memory->profile?->id)
            ->where('type', 'profile')
            ->first();

        $birthYear = $memory->profile?->date_of_birth
            ? \Carbon\Carbon::parse($memory->profile->date_of_birth)->format('Y')
            : null;
        $deathYear = $memory->profile?->date_of_death
            ? \Carbon\Carbon::parse($memory->profile->date_of_death)->format('Y')
            : null;
    @endphp

    @include('home.header')

    <!-- Header & Navigation -->
    <header class="memorial-hero d-flex align-items-end">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-8">
                    <h1 class="memorial-title text-white mb-0">
                        {{ $memory->profile?->first_name }} {{ $memory->profile?->last_name }}
                    </h1>
                    @if ($birthYear || $deathYear)
                        <p class="fs-4 text-white-50 font-serif mb-0">
                            {{ collect([$birthYear, $deathYear])->filter()->implode(' - ') }}
                        </p>
                    @endif
                </div>
                <div class="col-4 text-end">
                    <img src="{{ $profileImage ? asset($profileImage->path) : asset('images/profile_photo.png') }}"
                        class="memorial-avatar rounded-3 shadow" alt="Profile">
                </div>
            </div>
        </div>

        @if ($backgroundMusic?->path)
            <!-- Background Music Toggle Button -->
            <button id="audioToggleBtn"
                class="btn btn-outline-warning btn-sm rounded-pill px-3 py-1 d-flex align-items-center gap-2 bg-dark bg-opacity-50"
                onclick="toggleBackgroundMusic()" title="Toggle Background Music">
                <i id="audioIcon" class="bi bi-volume-up-fill fs-6 text-warning"></i>
                <span id="audioText" class="small">Mute Music</span>
            </button>

            <audio id="memorialAudio" autoplay loop>
                <source src="{{ asset($backgroundMusic->path) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        @endif
    </header>

    <div class="memorial-tabs-bar sticky-top">
        <div class="container">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item"><a class="nav-link active"
                        href="{{ url('/memory_index/' . $memory->profile->slug) }}">ABOUT</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_life/' . $memory->profile->slug) }}">LIFE</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_gallery/' . $memory->profile->slug) }}">GALLERY</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_stories/' . $memory->profile->slug) }}">STORIES</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_timeline/' . $memory->profile->slug) }}">TIMELINE</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_event/' . $memory->profile->slug) }}">EVENTS</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_donation/' . $memory->profile->slug) }}">DONATIONS</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container my-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="p-4 bg-white rounded shadow-sm mb-4 reveal-init">
                    <!-- Memorial Quote -->
                    <h3 class="font-serif fst-italic text-memorial mb-3 reveal-init">
                        <span class="typewriter-text" id="typewriterHeading">{{ $memory?->tribute_quote }}</span>
                    </h3>

                    <!-- Personal Details -->
                    @if ($memory->profile?->date_of_birth && $memory->profile?->date_of_death)
                        <ul class="list-unstyled text-muted mb-3 reveal-left">
                            <li class="mb-1">
                                <i class="bi bi-heart text-memorial me-2"></i>
                                <strong>
                                    {{ (int) \Carbon\Carbon::parse($memory->profile->date_of_birth)->diffInYears(\Carbon\Carbon::parse($memory->profile->date_of_death)) }}
                                    years old
                                </strong>
                            </li>
                    @endif
                    @if ($memory->profile?->date_of_birth && $memory->profile?->birth_location)
                        <li class="mb-1">
                            <i class="bi bi-calendar-event me-2 text-memorial"></i>Born on
                            {{ \Carbon\Carbon::parse($memory->profile->date_of_birth)->format('F d, Y') }}
                            in {{ $memory->profile->birth_location }}
                        </li>
                    @endif
                    @if ($memory->profile?->date_of_death && $memory->profile?->death_location)
                        <li class="mb-1">
                            <i class="bi bi-calendar-x me-2 text-memorial"></i>Passed away
                            {{ \Carbon\Carbon::parse($memory->profile->date_of_death)->format('F d, Y') }}
                            in {{ $memory->profile->death_location }}
                        </li>
                    @endif
                    </ul>

                    @if ($memory->about)
                        <p class="text-muted mb-4 reveal-init">
                            {{ $memory?->about }}
                        </p>
                        <hr class="my-4 text-muted">
                    @endif

                    <!-- Funeral Location & Contact Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 reveal-left">
                            <div class="p-3 bg-light rounded border h-100 shadow-sm">
                                <h5 class="fw-bold text-dark mb-2">
                                    @if ($memory?->funeral_location_name || $memory?->funeral_location_address)
                                        <i class="bi bi-geo-alt-fill text-memorial me-2"></i>Funeral & Wake Location
                                </h5>
                                <p class="mb-1 text-muted"><strong>Location Name:</strong>
                                    {{ $memory?->funeral_location_name }}</p>
                                <p class="mb-0 text-muted"><strong>Address:</strong>
                                    {{ $memory?->funeral_location_address }}</p>
                            @else
                                <i class="bi bi-geo-alt-fill text-memorial me-2"></i>No Details</h5>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 reveal-right">
                            <div class="p-3 bg-light rounded border h-100 shadow-sm">
                                <h5 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-telephone-fill text-memorial me-2"></i>Contact Numbers
                                </h5>
                                <p class="mb-1 text-dark">
                                    <strong>Family Contact:</strong>
                                    <span class="text-memorial fw-bold">{{ $memory?->family_contact_no }}</span>
                                </p>

                                @php
                                    $phone = $memory?->family_contact_no;
                                    $whatsappNumber = preg_replace('/\D/', '', $phone ?? '');
                                    $message = urlencode('Hello, I would like to get details about the funeral.');
                                @endphp

                                @if ($phone)
                                    <div class="mt-2">
                                        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $message }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="btn btn-success btn-sm fw-bold px-3 py-1">
                                            <i class="bi bi-whatsapp me-1"></i> Chat on WhatsApp
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Live Location Map Section -->
                    <div class="p-3 sidebar-card rounded shadow-sm border mb-4 reveal-init">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-map-fill text-memorial me-2"></i>Live
                            Location Map</h5>
                        <div class="overflow-hidden rounded border" style="height: 220px;">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798481354395!2d79.87786417475713!3d6.914692418514488!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2593b4a2bf445%3A0x8673f443b2dbce78!2sJayaratne%20Funeral%20Parlour!5e0!3m2!1sen!2slk!4v1700000000000!5m2!1sen!2slk"
                                width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>

                    @if ($memory->broadcast_link)
                        <div class="p-3 bg-light rounded border border-memorial text-center mb-4 reveal-zoom">
                            <h5 class="fw-bold text-memorial mb-2">
                                <i class="bi bi-broadcast me-2"></i>Live Streaming (Funeral Ceremony)
                            </h5>
                            <p class="small text-muted mb-3">You can watch the live ceremony stream directly below:</p>
                            <a href="{{ $memory->broadcast_link }}" target="_blank"
                                class="btn btn-danger btn-sm px-4 fw-bold">
                                <i class="bi bi-play-circle-fill me-1"></i> Watch Live Stream
                            </a>
                        </div>
                    @else
                        <div class="p-3 bg-light rounded border border-memorial text-center mb-4 reveal-zoom">
                            <h5 class="fw-bold text-memorial mb-2">
                                <i class="bi bi-broadcast me-2"></i>Live Streaming (No Live Currently)
                            </h5>
                            <p class="small text-muted mb-3">You can watch the live ceremony stream directly below:</p>
                            <a href="#" class="btn btn-danger btn-sm px-4 fw-bold disabled">
                                <i class="bi bi-play-circle-fill me-1"></i> Watch Live Stream
                            </a>
                        </div>
                    @endif

                    <div
                        class="ratio ratio-16x9 rounded overflow-hidden border border-2 border-memorial shadow-sm mb-4 reveal-init">
                        <iframe id="liveStreamIframe" src="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- ================= LEAVE A TRIBUTE SECTION ================= -->
                <div class="tribute-card p-4 rounded-4 shadow-sm bg-white border border-light mb-4 reveal-init">
                    <h5 class="fw-bold mb-3 text-brown font-serif">Leave a Tribute</h5>

                    <ul class="nav nav-pills custom-tribute-tabs mb-4 gap-3 justify-content-center" id="tributeTab"
                        role="tablist">
                        <!-- Flower Tab -->
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link active custom-tab-btn flower-tab-btn rounded-pill px-4 py-2.5 fw-bold d-flex align-items-center gap-2"
                                id="flower-tab" data-bs-toggle="pill" data-bs-target="#flowerTabContent"
                                type="button" role="tab">
                                <span class="fs-5 animated-icon">🌸</span> Flower
                            </button>
                        </li>

                        <!-- Candle Tab -->
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link custom-tab-btn candle-tab-btn rounded-pill px-4 py-2.5 fw-bold d-flex align-items-center gap-2"
                                id="candle-tab" data-bs-toggle="pill" data-bs-target="#candleTabContent"
                                type="button" role="tab">
                                <span class="fs-5 animated-flame-icon">🕯</span> Candle
                            </button>
                        </li>

                        <!-- Share a Memory Tab -->
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link custom-tab-btn memory-tab-btn rounded-pill px-4 py-2.5 fw-bold d-flex align-items-center gap-2"
                                id="memory-tab" data-bs-toggle="pill" data-bs-target="#memoryTabContent"
                                type="button" role="tab">
                                <i class="bi bi-chat-heart fs-5"></i> Share a Memory
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="tributeTabContent">
                        <!-- Flower Tab Content -->
                        <div class="tab-pane fade show active" id="flowerTabContent" role="tabpanel">
                            <form id="flowerTributeForm">
                                <label class="form-label small fw-bold text-muted mb-2">Choose a flower bouquet
                                    :</label>
                                <div class="d-flex gap-3 mb-3 fs-3">
                                    <label class="flower-option cursor-pointer">
                                        <input type="radio" name="flowerChoice" value="🌹" checked
                                            class="d-none">
                                        <span class="flower-icon p-2 rounded-circle border d-inline-block">🌹</span>
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <textarea class="form-control" id="flowerText" rows="2"
                                        placeholder="Add a short message with your flowers..."></textarea>
                                </div>
                                <button type="button" class="btn btn-memorial px-4 py-2 fw-bold w-100"
                                    id="btnOpenFlowerModal">
                                    Send Flowers 💐
                                </button>
                            </form>
                        </div>

                        <!-- Candle Tab Content -->
                        <div class="tab-pane fade" id="candleTabContent" role="tabpanel">
                            <form id="candleTributeForm">
                                <div
                                    class="text-center p-4 bg-light rounded-4 mb-3 border position-relative overflow-hidden">
                                    <div class="candle-wrapper d-inline-block position-relative my-2">
                                        <div class="flame"></div>
                                        <div class="wick"></div>
                                        <div class="candle-body"></div>
                                        <div class="candle-plate"></div>
                                    </div>
                                    <h6 class="fw-bold text-dark mt-3">Light a Memorial Candle</h6>
                                    <p class="text-muted small mb-0">“May your light and memory live forever in our
                                        hearts.”</p>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control py-2" id="candleName"
                                        placeholder="Your Name (e.g., Kasun)" required>
                                </div>
                                <button type="button" class="btn btn-memorial px-4 py-2 fw-bold w-100 shadow-sm"
                                    id="btnOpenCandleModal">
                                    <i class="bi bi-fire me-1"></i> Light Candle
                                </button>
                            </form>
                        </div>

                        <!-- Share a Memory Tab Content -->
                        <div class="tab-pane fade" id="memoryTabContent" role="tabpanel">
                            <form id="memoryTributeForm" action="{{ route('memory.message.store') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="memory_id" value="{{ $memory->id }}">

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Write your memory or
                                        message:</label>
                                    <textarea class="form-control mb-2" id="memoryText" name="message" rows="3"
                                        placeholder="I remember the beautiful times we spent together..." required></textarea>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                                onclick="insertEmoji('❤️')">❤️</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                                onclick="insertEmoji('🕊️')">🕊️</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                                onclick="insertEmoji('🙏')">🙏</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-memorial px-4 py-2 fw-bold w-100">
                                    Publish Memory
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @php
                    // Logged-in user's country matching with single_items
                    $userCountry = strtolower(trim(Auth::user()->country ?? 'all'));

                    $dbFlower =
                        \App\Models\SingleItem::where('type', 'flower')
                            ->where('is_active', true)
                            ->where(function ($q) use ($userCountry) {
                                if ($userCountry !== 'all' && !empty($userCountry)) {
                                    $q->where('country', $userCountry);
                                } else {
                                    $q->where('country', 'all');
                                }
                            })
                            ->first() ??
                        (\App\Models\SingleItem::where('type', 'flower')->where('country', 'all')->first() ??
                            \App\Models\SingleItem::where('type', 'flower')->first());

                    $dbCandle =
                        \App\Models\SingleItem::where('type', 'candle')
                            ->where('is_active', true)
                            ->where(function ($q) use ($userCountry) {
                                if ($userCountry !== 'all' && !empty($userCountry)) {
                                    $q->where('country', $userCountry);
                                } else {
                                    $q->where('country', 'all');
                                }
                            })
                            ->first() ??
                        (\App\Models\SingleItem::where('type', 'candle')->where('country', 'all')->first() ??
                            \App\Models\SingleItem::where('type', 'candle')->first());

                    $flowerUnitPrice = (float) ($dbFlower->price ?? 9.99);
                    $candleUnitPrice = (float) ($dbCandle->price ?? 4.99);

                    $flowerCurrency = strtoupper($dbFlower->currency ?? 'USD');
                    $candleCurrency = strtoupper($dbCandle->currency ?? 'USD');

                    $getSymbol = function ($currency) {
                        return match (strtoupper($currency)) {
                            'USD' => '$',
                            'LKR' => 'Rs. ',
                            'EUR' => '€',
                            'GBP' => '£',
                            'AUD' => 'A$',
                            default => $currency . ' ',
                        };
                    };

                    $flowerSymbol = $getSymbol($flowerCurrency);
                    $candleSymbol = $getSymbol($candleCurrency);
                @endphp

                <!-- ================= ORIGINAL TRIBUTE MODAL ================= -->
                <div class="modal fade" id="originalTributeModal" tabindex="-1" aria-labelledby="tributeModalTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                        <div class="modal-content rounded-4 border-0 shadow p-3">
                            <form action="{{ route('tribute.checkout') }}" method="POST" id="originalTributeForm">
                                @csrf
                                <input type="hidden" name="memory_id" value="{{ $memory->id }}">
                                <input type="hidden" name="package_id" id="tributePackageId"
                                    value="{{ $dbFlower->id ?? 1 }}">
                                <input type="hidden" name="tribute_type" id="tributeTypeInput" value="flower">
                                <input type="hidden" name="message" id="tributeMessageInput">

                                <!-- Modal Header -->
                                <div class="modal-header border-0 pb-0 pt-2 px-2">
                                    <h5 class="modal-title font-serif fw-bold" id="tributeModalTitle"
                                        style="color: #5c3a21;">
                                        Complete Your Flower Tribute
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body px-2 py-3">
                                    <!-- Your Name Input -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark mb-1">Your Name:</label>
                                        <input type="text" name="donor_name" id="donorNameInput"
                                            class="form-control py-2" placeholder="Enter your name (e.g., Nimal)"
                                            required>
                                    </div>

                                    <!-- Quantity Input -->
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark mb-1" id="quantityLabel">
                                            Quantity (Price:
                                            {{ $flowerSymbol }}{{ number_format($flowerUnitPrice, 2) }} each):
                                        </label>
                                        <input type="number" name="quantity" id="tributeQuantity"
                                            class="form-control py-2" value="1" min="1" required>
                                    </div>

                                    <!-- Summary Box (Selected Item & Total Price) -->
                                    <div class="p-3 bg-light rounded-3 mb-2 border">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="small fw-bold text-dark me-2" id="selectedItemLabel">Selected
                                                Flower:</span>
                                            <span id="selectedItemIcon" class="fs-5">🌹</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="small fw-bold text-dark me-2">Total Price:</span>
                                            <span class="fw-bold text-success fs-6"
                                                id="totalPriceText">{{ $flowerSymbol }}{{ number_format($flowerUnitPrice, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer border-0 pt-0 pb-2 px-2 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn rounded-pill px-4 fw-bold text-white shadow-sm"
                                        style="background-color: #5c3a21;">Confirm & Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modalElement = document.getElementById('originalTributeModal');
                        const tributeModal = new bootstrap.Modal(modalElement);

                        const flowerPrice = {{ $flowerUnitPrice }};
                        const candlePrice = {{ $candleUnitPrice }};

                        const flowerSymbol = "{{ $flowerSymbol }}";
                        const candleSymbol = "{{ $candleSymbol }}";

                        const flowerPackageId = {{ $dbFlower->id ?? 1 }};
                        const candlePackageId = {{ $dbCandle->id ?? 2 }};

                        let currentUnitPrice = flowerPrice;
                        let currentSymbol = flowerSymbol;

                        function updateTotal() {
                            const qty = parseInt(document.getElementById('tributeQuantity').value) || 1;
                            const total = (currentUnitPrice * qty).toFixed(2);
                            document.getElementById('totalPriceText').textContent = currentSymbol + total;
                        }

                        document.getElementById('tributeQuantity').addEventListener('input', updateTotal);

                        // 1. Send Flowers Button Click
                        const flowerBtn = document.getElementById('btnOpenFlowerModal');
                        if (flowerBtn) {
                            flowerBtn.addEventListener('click', function() {
                                currentUnitPrice = flowerPrice;
                                currentSymbol = flowerSymbol;

                                document.getElementById('tributeModalTitle').textContent =
                                    "Complete Your Flower Tribute";
                                document.getElementById('quantityLabel').textContent =
                                    `Quantity (Price: ${flowerSymbol}${flowerPrice.toFixed(2)} each):`;
                                document.getElementById('selectedItemLabel').textContent = "Selected Flower:";
                                document.getElementById('selectedItemIcon').textContent = "🌹";

                                document.getElementById('tributeTypeInput').value = "flower";
                                document.getElementById('tributePackageId').value = flowerPackageId;
                                document.getElementById('tributeMessageInput').value = document.getElementById(
                                    'flowerText')?.value || '';

                                const previousName = document.getElementById('donorNameInput').value;
                                if (!previousName && document.getElementById('candleName')?.value) {
                                    document.getElementById('donorNameInput').value = document.getElementById(
                                        'candleName').value;
                                }

                                document.getElementById('tributeQuantity').value = 1;
                                updateTotal();

                                tributeModal.show();
                            });
                        }

                        // 2. Light Candle Button Click
                        const candleBtn = document.getElementById('btnOpenCandleModal');
                        if (candleBtn) {
                            candleBtn.addEventListener('click', function() {
                                const candleName = document.getElementById('candleName')?.value || '';
                                if (!candleName.trim()) {
                                    alert('Please enter your name first.');
                                    document.getElementById('candleName').focus();
                                    return;
                                }

                                currentUnitPrice = candlePrice;
                                currentSymbol = candleSymbol;

                                document.getElementById('tributeModalTitle').textContent =
                                    "Complete Your Candle Tribute";
                                document.getElementById('quantityLabel').textContent =
                                    `Quantity (Price: ${candleSymbol}${candlePrice.toFixed(2)} each):`;
                                document.getElementById('selectedItemLabel').textContent = "Selected Candle:";
                                document.getElementById('selectedItemIcon').textContent = "🕯️";

                                document.getElementById('tributeTypeInput').value = "candle";
                                document.getElementById('tributePackageId').value = candlePackageId;
                                document.getElementById('donorNameInput').value = candleName;
                                document.getElementById('tributeMessageInput').value = candleName;

                                document.getElementById('tributeQuantity').value = 1;
                                updateTotal();

                                tributeModal.show();
                            });
                        }
                    });
                </script>

                <!-- ================= RECENT TRIBUTES STREAM & PROFILE STATS SECTION ================= -->
                <div id="tributesStream" class="mt-4">

                    @php
                        // 1. Active සහ Expire නොවූ Flowers සහ Candles ලබා ගැනීම
                        $paidTributes = \App\Models\Tribute::with('user')
                            ->where('memory_id', $memory->id)
                            ->where('status', 'active')
                            ->where(function ($q) {
                                $q->whereNull('expire_date')->orWhere('expire_date', '>', now());
                            })
                            ->get()
                            ->map(function ($item) {
                                $item->feed_type = $item->tribute_type;
                                return $item;
                            });

                        // 2. Share a Memory (Thoughts) ලබා ගැනීම
                        $memoryMessages = \App\Models\MemoryMessage::with('user')
                            ->where('memory_id', $memory->id)
                            ->where('status', 'active')
                            ->get()
                            ->map(function ($item) {
                                $item->feed_type = 'thought';
                                return $item;
                            });

                        // 3. සියල්ල එකතු කර අලුත්ම ඒවා මුලට එන පරිදි Sort කිරීම
                        $allTributes = $paidTributes->concat($memoryMessages)->sortByDesc('created_at');
                    @endphp

                    <!-- Profile Stats / Followers & Tributes Header Card -->
                    <div
                        class="profile-stats-card p-3 rounded-4 shadow-sm bg-white mb-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="profile-main-avatar">
                                <img src="{{ $profileImage ? asset($profileImage->path) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop' }}"
                                    alt="Profile">
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $memory->profile?->first_name }}
                                    {{ $memory->profile?->last_name }}</h6>
                                <span class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-heart-fill text-danger me-1"></i> {{ $allTributes->count() }}
                                    Tributes & Memories
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge px-2.5 py-1.5 fw-semibold"
                                style="background-color: #854d0e; color: #fff; font-size: 0.75rem;">
                                Active Memorial
                            </span>
                        </div>
                    </div>

                    <!-- Section Title & Pagination -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-brown font-serif reveal-left">Recent Tributes</h5>
                        <div class="tribute-pagination-btns d-flex gap-1" id="tributeNavGroup">
                            <button type="button" class="btn btn-sm tribute-nav-btn" id="prevTribute">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm tribute-nav-btn" id="nextTribute">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tributes Cards Container -->
                    <div class="tributes-container d-flex flex-column gap-3" id="tributeCardsList">

                        @forelse($allTributes as $tribute)
                            @php
                                $donorName = $tribute->user->name ?? ($tribute->donor_name ?? 'Kind Contributor');
                                $userAvatar = $tribute->user->profile_photo_path ?? ($tribute->user->avatar ?? null);
                                $initials = strtoupper(substr($donorName, 0, 2));

                                $flowerCount = max(1, (int) ($tribute->flower_count ?? 1));
                                $candleCount = max(1, (int) ($tribute->candle_count ?? 1));
                            @endphp

                            <!-- ================= 1. FLOWER TRIBUTE CARD ================= -->
                            @if ($tribute->feed_type === 'flower')
                                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white modern-tribute-card">
                                    <div class="d-flex align-items-start gap-3">
                                        @if ($userAvatar)
                                            <img src="{{ asset($userAvatar) }}" alt="{{ $donorName }}"
                                                class="tribute-user-avatar">
                                        @else
                                            <div class="tribute-user-initials" style="background-color: #854d0e;">
                                                {{ $initials }}
                                            </div>
                                        @endif

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark small">{{ $donorName }}</span>
                                                <span
                                                    class="text-muted tiny-time">{{ $tribute->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <!-- Quantity එක අනුව මල් ගණන පෙන්වීම -->
                                                <div class="highlighted-icon-box flower-glow-bg d-inline-flex align-items-center justify-content-center gap-1 flex-wrap p-2"
                                                    style="width: auto; min-width: 48px;">
                                                    @for ($i = 0; $i < $flowerCount; $i++)
                                                        <span class="fs-2 animated-flower-item">🌹</span>
                                                    @endfor
                                                </div>
                                                <p class="mb-0 text-dark fw-medium tribute-text">
                                                    “{{ $tribute->message ?: 'Sent flowers in loving memory.' }}”
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ================= 2. CANDLE TRIBUTE CARD ================= -->
                            @elseif($tribute->feed_type === 'candle')
                                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white modern-tribute-card">
                                    <div class="d-flex align-items-start gap-3">
                                        @if ($userAvatar)
                                            <img src="{{ asset($userAvatar) }}" alt="{{ $donorName }}"
                                                class="tribute-user-avatar">
                                        @else
                                            <div class="tribute-user-initials" style="background-color: #854d0e;">
                                                {{ $initials }}
                                            </div>
                                        @endif

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark small">{{ $donorName }}</span>
                                                <span
                                                    class="text-muted tiny-time">{{ $tribute->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <!-- Quantity එක අනුව ඉටිපන්දම් ගණන පෙන්වීම -->
                                                <div class="highlighted-icon-box candle-glow-bg d-inline-flex align-items-center justify-content-center gap-2 flex-wrap p-2"
                                                    style="width: auto; min-width: 48px;">
                                                    @for ($i = 0; $i < $candleCount; $i++)
                                                        <div class="user-candle-icon-wrapper">
                                                            <div class="mini-flame"></div>
                                                            <div class="mini-body"></div>
                                                        </div>
                                                    @endfor
                                                </div>
                                                <p class="mb-0 text-dark fw-medium tribute-text">
                                                    {{ $tribute->message ?: 'Candle lit in loving memory.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ================= 3. THOUGHT / MEMORY CARD ================= -->
                            @elseif($tribute->feed_type === 'thought')
                                <div class="card border-0 shadow-sm p-3 rounded-4 bg-white modern-tribute-card">
                                    <div class="d-flex align-items-start gap-3">
                                        @if ($userAvatar)
                                            <img src="{{ asset($userAvatar) }}" alt="{{ $donorName }}"
                                                class="tribute-user-avatar">
                                        @else
                                            <div class="tribute-user-initials" style="background-color: #5c3a21;">
                                                {{ $initials }}
                                            </div>
                                        @endif

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-dark small">{{ $donorName }}</span>
                                                <span
                                                    class="text-muted tiny-time">{{ $tribute->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="highlighted-icon-box thought-glow-bg">
                                                    <span class="fs-3">💭</span>
                                                </div>
                                                <p class="mb-0 text-dark fw-medium tribute-text">
                                                    “{{ $tribute->message }}”
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @empty
                            <div class="text-center py-4 bg-white rounded-4 border border-dashed">
                                <i class="bi bi-heart text-muted fs-3 d-block mb-1"></i>
                                <p class="text-muted mb-0 small">No tributes yet. Be the first to leave a tribute in
                                    loving memory.</p>
                            </div>
                        @endforelse

                    </div>

                    <!-- Dynamic Pagination Dots Indicator -->
                    <div class="tribute-dots-container d-flex justify-content-center gap-2 mt-3"
                        id="tributeDotsContainer"></div>
                </div>

                <!-- Pagination Script -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const cards = document.querySelectorAll('#tributeCardsList .modern-tribute-card');
                        const prevBtn = document.getElementById('prevTribute');
                        const nextBtn = document.getElementById('nextTribute');
                        const dotsContainer = document.getElementById('tributeDotsContainer');
                        const navGroup = document.getElementById('tributeNavGroup');

                        const itemsPerPage = 3;
                        let currentPage = 1;
                        const totalPages = Math.ceil(cards.length / itemsPerPage);

                        if (cards.length === 0) {
                            if (dotsContainer) dotsContainer.style.display = 'none';
                            if (navGroup) navGroup.style.display = 'none';
                            return;
                        }

                        function buildDots() {
                            if (!dotsContainer) return;
                            dotsContainer.innerHTML = '';

                            if (totalPages <= 1) {
                                dotsContainer.style.display = 'none';
                                if (navGroup) navGroup.style.display = 'none';
                                return;
                            }

                            dotsContainer.style.display = 'flex';
                            if (navGroup) navGroup.style.display = 'flex';

                            for (let i = 1; i <= totalPages; i++) {
                                const dot = document.createElement('span');
                                dot.className = `tribute-dot ${i === currentPage ? 'active' : ''}`;
                                dot.style.cursor = 'pointer';
                                dot.dataset.page = i;

                                dot.addEventListener('click', function() {
                                    goToPage(i);
                                });

                                dotsContainer.appendChild(dot);
                            }
                        }

                        function showPage(page) {
                            currentPage = page;
                            const startIndex = (page - 1) * itemsPerPage;
                            const endIndex = startIndex + itemsPerPage;

                            cards.forEach((card, index) => {
                                if (index >= startIndex && index < endIndex) {
                                    card.style.display = 'block';
                                } else {
                                    card.style.display = 'none';
                                }
                            });

                            if (dotsContainer) {
                                dotsContainer.querySelectorAll('.tribute-dot').forEach(dot => {
                                    if (parseInt(dot.dataset.page) === currentPage) {
                                        dot.classList.add('active');
                                    } else {
                                        dot.classList.remove('active');
                                    }
                                });
                            }

                            if (prevBtn) prevBtn.disabled = (currentPage === 1);
                            if (nextBtn) nextBtn.disabled = (currentPage === totalPages);
                        }

                        function goToPage(page) {
                            if (page >= 1 && page <= totalPages) {
                                showPage(page);
                            }
                        }

                        if (prevBtn) {
                            prevBtn.addEventListener('click', function() {
                                if (currentPage > 1) {
                                    goToPage(currentPage - 1);
                                }
                            });
                        }

                        if (nextBtn) {
                            nextBtn.addEventListener('click', function() {
                                if (currentPage < totalPages) {
                                    goToPage(currentPage + 1);
                                }
                            });
                        }

                        buildDots();
                        showPage(1);
                    });
                </script>
            </div>

            <!-- Common Sidebar -->
            <div class="col-lg-4 d-flex flex-column gap-3">
                <!-- Invite Card -->
                <div class="sidebar-card p-3 rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0 me-2 text-dark">Invite {{ $memory->profile?->first_name }}'s family
                            and friends</h6>
                        <i class="bi bi-person-add fs-4 text-muted"></i>
                    </div>

                    <button class="btn btn-invite w-100 py-2 mb-2">
                        <i class="bi bi-person-plus me-1"></i> Invite now
                    </button>

                    <a href="{{ route('donation.form', $memory->profile?->slug ?? $memory->slug) }}"
                       class="btn btn-donate w-100 py-2 mb-2 text-decoration-none text-center d-block shadow-sm">
                        <i class="bi bi-gift me-1"></i> Make a Donation / Tribute
                    </a>

                    <button class="btn btn-tribute w-100 py-2"
                        onclick="document.getElementById('tributeTabContent').scrollIntoView({behavior: 'smooth'});">
                        <i class="bi bi-chat-heart-fill me-1"></i> Add Tribute
                    </button>
                </div>

                <!-- Share on Facebook Button -->
                <button class="btn btn-facebook w-100 py-2 d-flex align-items-center justify-content-center gap-2"
                    id="shareFbBtn">
                    <i class="bi bi-facebook fs-5 text-primary"></i>
                    <span>Share on Facebook</span>
                </button>

                <!-- Photo Section Card -->
                <div class="sidebar-card p-3 rounded">
                    <h6 class="fw-bold mb-3 text-dark">Photos</h6>
                    <div class="mb-3 rounded overflow-hidden">
                        <img src="{{ $profileImage ? asset($profileImage->path) : asset('images/profile_photo.png') }}"
                            class="img-fluid rounded w-100" alt="{{ $memory->profile?->first_name }}">
                    </div>
                    <button class="btn btn-outline-memorial-dark w-100 py-2">
                        <i class="bi bi-plus-square me-1"></i> Add photos
                    </button>
                </div>

                <!-- Website Admin Info Card -->
                <div class="sidebar-card p-3 rounded">
                    <p class="small text-muted mb-0">This Memory is Listed by:</p>
                    <p class="fw-semibold text-memorial-accent mb-0">Nature Whisper</p>
                </div>

                <!-- Views & Access Card -->
                <div class="sidebar-card p-3 rounded">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-eye fs-5 text-dark"></i>
                        <h6 class="fw-bold mb-0 text-dark">108 Views</h6>
                    </div>
                    <div
                        class="d-flex align-items-center gap-2 small text-muted pt-2 border-top border-secondary-subtle">
                        <i class="bi bi-globe"></i>
                        <span>Open access</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-warning fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                    <p class="small text-light-tan leading-relaxed">
                        A dignified digital platform dedicated to preserving legacies, connecting families, and honoring
                        cherished memories forever.
                    </p>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                        <li><a href="{{ url('/') }}" class="text-light-tan text-decoration-none">Home</a></li>
                        <li><a href="{{ url('/all_memories') }}"
                                class="text-light-tan text-decoration-none">Memories</a></li>
                        <li><a href="{{ url('/about') }}" class="text-light-tan text-decoration-none">About Us</a>
                        </li>
                        <li><a href="{{ url('/contact') }}" class="text-light-tan text-decoration-none">Contact
                                Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-gold fw-bold mb-3">Contact Support</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                        <li><i class="bi bi-telephone-fill text-gold me-2"></i>+1 (800) 555-0199</li>
                        <li><i class="bi bi-envelope-fill text-gold me-2"></i>support@forevermissed.com</li>
                        <li><i class="bi bi-geo-alt-fill text-gold me-2"></i>Global Headquarters</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-gold fw-bold mb-3">Follow Us</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
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

    <!-- Global Helper Scripts -->
    <script>
        function insertEmoji(emoji) {
            const memoryText = document.getElementById('memoryText');
            if (memoryText) {
                memoryText.value += emoji;
                memoryText.focus();
            }
        }
    </script>

    <!-- YouTube Live Streaming Script -->
    <script>
        let backendLiveLink = "https://www.youtube.com/watch?v=rtw9hQWOfaI&list=RDrtw9hQWOfaI&start_radio=1";

        function setupLiveStreaming(url) {
            if (!url || url.trim() === "") return;

            const iframe = document.getElementById('liveStreamIframe');
            let videoId = "";
            if (url.includes("watch?v=")) {
                videoId = url.split("watch?v=")[1].split("&")[0];
            } else if (url.includes("youtu.be/")) {
                videoId = url.split("youtu.be/")[1].split("?")[0];
            }

            if (videoId && iframe) {
                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=0&rel=0`;
            }
        }

        setupLiveStreaming(backendLiveLink);
    </script>

    <!-- Background Music Controller Script -->
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            if (!audio) return;

            const isMuted = localStorage.getItem('memorialAudioMuted');

            if (isMuted === 'false') {
                audio.muted = false;
                audio.play().catch(error => console.log("Autoplay restricted"));
                if (audioIcon) audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                if (audioText) audioText.innerText = "Mute Music";
            } else {
                audio.muted = true;
                audio.play().catch(error => console.log("Autoplay restricted"));
                if (audioIcon) audioIcon.className = "bi bi-volume-mute-fill fs-6";
                if (audioText) audioText.innerText = "Play Music";
            }
        });

        function toggleBackgroundMusic() {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            if (!audio) return;

            if (audio.muted) {
                audio.muted = false;
                audio.play();
                if (audioIcon) audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                if (audioText) audioText.innerText = "Mute Music";
                localStorage.setItem('memorialAudioMuted', 'false');
            } else {
                audio.muted = true;
                if (audioIcon) audioIcon.className = "bi bi-volume-mute-fill fs-6";
                if (audioText) audioText.innerText = "Play Music";
                localStorage.setItem('memorialAudioMuted', 'true');
            }
        }
    </script>

    <!-- Reveal on Scroll Observer -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            const animatedElements = document.querySelectorAll(
                '.reveal-init, .reveal-left, .reveal-right, .reveal-zoom');
            animatedElements.forEach(el => observer.observe(el));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('memo_website_dev_frontend/assets/js/main.js') }}"></script>
</body>

</html>
