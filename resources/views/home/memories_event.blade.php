<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - {{ $memory->profile?->first_name }} {{ $memory->profile?->last_name }} Memorial</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/events.css') }}">
</head>

<body class="bg-memorial-light">

    @include('home.header')

    @php
        $profile ??= $memory->profile;
        $backgroundMusic ??= \App\Models\Music::where('profile_id', $profile?->id)->first();
        $profileImage ??= \App\Models\Image::where('profile_id', $profile?->id)->where('type', 'profile')->first();

        $memorialFullName =
            trim(
                collect([$profile?->first_name, $profile?->middle_name, $profile?->last_name])
                    ->filter()
                    ->implode(' '),
            ) ?:
            'Memorial';

        $birthYear = $profile?->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('Y') : null;

        $deathYear = $profile?->date_of_death ? \Carbon\Carbon::parse($profile->date_of_death)->format('Y') : null;

        $events ??= collect();
        $eventPresentations ??= [];
        $totalDonationAmount ??= 0;
        $donorCount ??= 0;
        $recentTributes ??= collect();
    @endphp

    <!-- Hero Header -->
    <header class="memorial-hero d-flex align-items-end">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-8">
                    <h1 class="memorial-title text-white mb-0">{{ $memory->profile?->first_name }}
                        {{ $memory->profile?->last_name }}</h1>
                    @if ($birthYear || $deathYear)
                        <p class="fs-4 text-white-50 font-serif mb-0">
                            {{ collect([$birthYear, $deathYear])->filter()->implode(' - ') }}
                        </p>
                    @endif
                </div>
                <div class="col-4 text-end">
                    <img src="{{ $profileImage ? asset($profileImage->path) : asset('images/profile_photo.png') }}"
                        class="memorial-avatar rounded-3 shadow" alt="{{ $memorialFullName }}">
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

            <!-- Hidden HTML5 Audio Element (Autoplay without muted) -->
            <audio id="memorialAudio" autoplay loop>
                <source src="{{ asset($backgroundMusic->path) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        @endif
    </header>

    <!-- Navigation Tabs Bar -->
    <div class="memorial-tabs-bar sticky-top">
        <div class="container">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memory_index/' . $memory->profile->slug) }}">ABOUT</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_life/' . $memory->profile->slug) }}">LIFE</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_gallery/' . $memory->profile->slug) }}">GALLERY</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_stories/' . $memory->profile->slug) }}">STORIES</a></li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ url('/memories_timeline/' . $memory->profile->slug) }}">TIMELINE</a></li>
                <li class="nav-item"><a class="nav-link active"
                        href="{{ url('/memories_event/' . $memory->profile->slug) }}">EVENTS</a></li>
                <li class="nav-item"><a class="nav-link "
                        href="{{ url('/memories_donation/' . $memory->profile->slug) }}">DONATIONS</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Layout -->
    <main class="container my-4">
        <div class="row g-4">

            <!-- Left Column: Memorial Events & Live Banner -->
            <div class="col-lg-8 d-flex flex-column gap-4">

                @forelse($events as $event)
                    @php
                        $presentation =
                            $eventPresentations[$event->id] ??
                            \App\Http\Controllers\EventController::buildPresentation($event);
                        $eventDate =
                            $presentation['eventDate'] ??
                            ($event->event_date ? \Carbon\Carbon::parse($event->event_date) : null);
                        $endDate = $presentation['endDate'] ?? null;
                        $formattedTime = $presentation['formattedTime'] ?? null;
                        $isUpcoming = $presentation['isUpcoming'] ?? false;
                        $countdownLabel = $presentation['countdownLabel'] ?? 'Scheduled Event';
                        $participationMode = $presentation['participationMode'] ?? ($event->participation_mode ?? null);
                        $rawStreamUrl =
                            $presentation['rawStreamUrl'] ??
                            ($event->live_stream_url ?? ($event->broadcast_link ?? ($event->stream_link ?? null)));
                        $streamEmbedUrl = $presentation['streamEmbedUrl'] ?? null;
                        $mapEmbedUrl = $presentation['mapEmbedUrl'] ?? ($event->map_embed_url ?? null);
                        $eventVenue = $event->venue ?? $event->location;
                    @endphp

                    <!-- Live Event Stream Banner (Shown if live stream link exists or event is live) -->
                    @if (!empty($rawStreamUrl) || $event->event_type === 'live')
                        <div
                            class="live-banner rounded p-3 text-white d-flex flex-wrap align-items-center justify-content-between gap-3 shadow-sm animate-main-column">
                            <div class="d-flex align-items-center gap-3">
                                <span
                                    class="live-badge rounded-pill px-3 py-1 fw-bold text-uppercase d-flex align-items-center gap-2">
                                    <span class="pulse-dot"></span>
                                    {{ $event->event_type === 'live' ? 'LIVE NOW' : 'LIVE / UPCOMING' }}
                                </span>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $event->title }}</h6>
                                    <small class="opacity-75">
                                        @if ($event->event_type === 'live')
                                            Live Webcast is active now
                                        @elseif($event->event_type === 'past')
                                            Recorded ceremony stream available below
                                        @else
                                            Live Webcast available for this event
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <a href="#eventVideo-{{ $event->id }}"
                                class="btn btn-light btn-sm text-dark fw-bold rounded-pill px-3">
                                <i class="bi bi-broadcast me-1 text-danger"></i> Watch Stream
                            </a>
                        </div>
                    @endif

                    <!-- Event Reminder Notice Card -->
                    <div
                        class="sidebar-card p-3 rounded border-start border-4 border-warning shadow-sm animate-main-column">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-warning bg-opacity-20 p-2 rounded text-warning-emphasis">
                                <i class="bi bi-bell-fill fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1">{{ $event->title }} Notice</h6>
                                <p class="small text-muted mb-2">
                                    {{ \Illuminate\Support\Str::limit($event->description ?: 'You are cordially invited to attend this memorial service.', 220) }}
                                </p>
                                @if ($isUpcoming || $event->event_type === 'live')
                                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                                        <span class="badge bg-memorial-accent text-white py-2 px-3 rounded-pill fs-7">
                                            <i class="bi bi-clock-history me-1"></i> {{ $countdownLabel }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Main Detailed Event Card -->
                    <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card animate-main-column">
                        @if (!empty($event->banner_image))
                            <div class="rounded overflow-hidden mb-4 border border-memorial shadow-sm">
                                <img src="{{ asset($event->banner_image) }}" alt="{{ $event->title }}"
                                    class="w-100 object-fit-cover" style="max-height: 280px;">
                            </div>
                        @endif

                        <div
                            class="d-flex flex-wrap justify-content-between align-items-start gap-2 border-bottom pb-3 mb-4">
                            <div>
                                <h4 class="fw-bold text-dark mb-1">{{ $event->title }}</h4>
                                <p class="text-muted small mb-0">Organized in loving memory of
                                    {{ $memory->profile?->first_name }} {{ $memory->profile?->last_name }}.</p>
                            </div>
                            <span class="badge bg-memorial-accent text-white rounded-pill px-3 py-2 text-uppercase">
                                {{ ucfirst($event->event_type ?? 'upcoming') }}
                            </span>
                        </div>

                        <div class="event-details d-flex flex-column gap-3 mb-4">
                            <!-- Date & Time -->
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-calendar-event fs-5 text-memorial-accent mt-1"></i>
                                <div>
                                    <strong class="text-dark d-block">Date &amp; Time</strong>
                                    <span class="small-textbold">
                                        @if ($eventDate)
                                            {{ $eventDate->format('l, F d, Y') }}
                                            @if ($endDate && !$endDate->isSameDay($eventDate))
                                                — {{ $endDate->format('l, F d, Y') }}
                                            @endif
                                            @if ($formattedTime)
                                                at {{ $formattedTime }}
                                            @endif
                                        @else
                                            Date to be announced
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Participation Mode -->
                            @if (!empty($participationMode))
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-camera-video fs-5 text-memorial-accent mt-1"></i>
                                    <div>
                                        <strong class="text-dark d-block">Participation Mode</strong>
                                        <span class="small-textbold small">{{ $participationMode }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Description -->
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-info-circle fs-5 text-memorial-accent mt-1"></i>
                                <div>
                                    <strong class="text-dark d-block">Event Description &amp; Instructions</strong>
                                    <p class="small-textbold small mb-0" style="white-space: pre-line;">
                                        {{ $event->description ?: 'Additional schedule details will be shared by the family.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Venue Location -->
                            @if (!empty($eventVenue))
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-geo-alt fs-5 text-memorial-accent mt-1"></i>
                                    <div>
                                        <strong class="text-dark d-block">Venue Location</strong>
                                        <span class="small-textbold small">{{ $eventVenue }}</span>
                                        @if (!empty($event->map_link))
                                            <div class="mt-1">
                                                <a href="{{ $event->map_link }}" target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="small text-memorial-accent fw-semibold text-decoration-none">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i> Open Exact Pin in
                                                    Google Maps
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Google Map (If map_link, coordinates, or venue exists) -->
                        @if (!empty($mapEmbedUrl))
                            <div class="map-container rounded mb-4 overflow-hidden shadow-sm border">
                                <iframe src="{{ $mapEmbedUrl }}" width="100%" height="200" style="border:0;"
                                    allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        @endif

                        <!-- Live Broadcast Video Embed -->
                        @if (!empty($streamEmbedUrl))
                            <div id="eventVideo-{{ $event->id }}" class="pt-3 border-top">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Live Event Broadcast</h6>
                                        <p class="small text-muted mb-0">Livestreamed service for family and friends
                                            globally.</p>
                                    </div>
                                    @if (!empty($rawStreamUrl))
                                        <a href="{{ $rawStreamUrl }}" target="_blank" rel="noopener noreferrer"
                                            class="btn btn-outline-memorial-dark btn-sm rounded-pill px-3">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Open Stream
                                        </a>
                                    @endif
                                </div>
                                <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm bg-black mt-3">
                                    <iframe src="{{ $streamEmbedUrl }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif

                        @if ($event->allow_donation ?? true)
                            <div
                                class="pt-3 mt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <span class="small text-muted">Support this memorial gathering with a
                                    contribution:</span>
                                <a href="{{ route('donation.form', ['slug' => $memory->profile?->slug ?? $memory->slug, 'event_id' => $event->id]) }}"
                                    class="btn btn-warning btn-sm fw-bold rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-gift me-1"></i> Donate for this Event
                                </a>
                            </div>
                        @endif
                    </div>

                @empty
                    <!-- Empty State -->
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                        <i class="bi bi-calendar-x text-muted fs-1 d-block mb-2"></i>
                        <h5 class="fw-bold text-dark">No Events Scheduled</h5>
                        <p class="text-muted small mb-0">There are currently no upcoming memorial services or events
                            listed for this memorial space.</p>
                    </div>
                @endforelse

            </div>

            <!-- Right Column: Sidebar (Donations, Share & Info) -->
            <div class="col-lg-4 d-flex flex-column gap-3">

                <!-- Donation / Meritorious Contributions Card -->
                <div class="main-content-card p-3 rounded shadow-sm bg-memorial-card">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-heart-fill text-danger fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">Flowers / Donations</h6>
                    </div>
                    <p class="small fw-semibold mb-3">
                        Please consider a meritorious contribution or floral tribute in honor of
                        {{ $memorialFullName }}.
                    </p>

                    <div class="donation-progress bg-white p-3 rounded border mb-3">
                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                            <span
                                class="fs-4 fw-bold text-dark">${{ number_format((float) $totalDonationAmount, 2) }}</span>
                            <span class="small fw-semibold">
                                Raised by {{ $donorCount }}
                                {{ \Illuminate\Support\Str::plural('contribution', $donorCount) }}
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-memorial-accent"
                                style="width: {{ $totalDonationAmount > 0 ? min(100, max(15, (int) round(($totalDonationAmount / 500) * 100))) : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    @php
                        // පිටුවේ Events තිබේ නම් පළමු Event එකේ ID එක ලබා ගනී, නැත්නම් null
                        $defaultEventId = $events->first()?->id;
                    @endphp

                    <a href="{{ route('donation.form', ['slug' => $memory->profile?->slug ?? $memory->slug, 'event_id' => $defaultEventId]) }}"
                        class="btn btn-memorial-submit w-100 py-2 text-white fw-bold mb-3 text-decoration-none text-center d-block shadow-sm">
                        <i class="bi bi-gift me-1"></i> Make a Donation / Tribute
                    </a>

                    <!-- Recent Contributions -->
                    <h6 class="fw-bold small text-dark border-bottom pb-2 mb-2">Recent Contributions</h6>
                    @forelse($recentTributes as $tribute)
                        @php
                            $contributorName = $tribute->user?->name ?: ($tribute->message ?: 'Memorial Supporter');
                        @endphp
                        <div
                            class="d-flex justify-content-between align-items-center small py-1 {{ !$loop->last ? 'border-bottom border-light-subtle' : '' }}">
                            <span class="small-textbold text-truncate me-2">
                                {{ $tribute->tribute_type === 'candle' ? '🕯️' : '🌹' }} {{ $contributorName }}
                            </span>
                            <span
                                class="fw-bold text-dark">${{ number_format((float) $tribute->total_amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="small text-muted mb-0">No contributions yet. Be the first to honor
                            {{ $memorialFullName }}.</p>
                    @endforelse
                </div>

                <!-- Share Memorial / Event Section -->
                <div class="main-content-card p-3 rounded shadow-sm">
                    <h6 class="fw-bold mb-2 text-dark">Share this memorial event</h6>
                    <p class="small fw-semibold mb-3">Help notify relatives and friends by sharing this invitation
                        page.</p>

                    <div class="d-flex gap-2">
                        <button type="button" id="shareEventFacebook"
                            class="btn btn-facebook flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                            title="Share on Facebook">
                            <i class="bi bi-facebook fs-5"></i>
                        </button>
                        <button type="button" id="shareEventWhatsapp"
                            class="btn btn-whatsapp flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                            title="Share on WhatsApp">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </button>
                        <button type="button" id="copyEventLinkBtn"
                            class="btn btn-outline-secondary flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                            title="Copy Link">
                            <i class="bi bi-link-45deg fs-5"></i>
                        </button>
                    </div>
                    <div id="copyLinkFeedback" class="small text-success fw-semibold mt-2 d-none">
                        <i class="bi bi-check-circle-fill me-1"></i> Link copied to clipboard!
                    </div>
                </div>

                <!-- Invite Panel -->
                <div class="main-content-card p-3 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0 me-2 text-dark">Invite
                            {{ $memory->profile?->first_name ? $memory->profile->first_name . "'s" : 'family and' }}
                            friends</h6>
                        <i class="bi bi-person-add fs-4 text-muted"></i>
                    </div>
                    <button type="button" id="inviteCopyBtn" class="btn btn-invite w-100 py-2">
                        <i class="bi bi-person-plus me-1"></i> Invite now
                    </button>
                </div>

                <!-- Page Views & Access Panel -->
                <div class="sidebar-card p-3 rounded shadow-sm">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-calendar-check fs-5 text-dark"></i>
                        <h6 class="fw-bold mb-0 text-dark">
                            {{ $events->count() }}
                            {{ \Illuminate\Support\Str::plural('Memorial Event', $events->count()) }}
                        </h6>
                    </div>
                    <div
                        class="d-flex align-items-center gap-2 small text-muted pt-2 border-top border-secondary-subtle">
                        <i
                            class="bi {{ ($profile?->privacy ?? 'public') === 'private' ? 'bi-lock' : 'bi-globe' }}"></i>
                        <span>{{ ($profile?->privacy ?? 'public') === 'private' ? 'Private memorial access' : 'Open public access' }}</span>
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

    <!-- JavaScript Files -->
    <script src="{{ asset('memo_website_dev_frontend/assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const animatedEls = document.querySelectorAll('.animate-main-column');
            if ('IntersectionObserver' in window) {
                const observerOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.05
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('reveal');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                animatedEls.forEach(el => {
                    observer.observe(el);
                });
            } else {
                animatedEls.forEach(el => el.classList.add('reveal'));
            }

            const shareUrl = window.location.href;
            const shareTitle = @json($memorialFullName . ' - Memorial Events');

            const fbBtn = document.getElementById('shareEventFacebook');
            if (fbBtn) {
                fbBtn.addEventListener('click', function() {
                    window.open(
                        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
                        '_blank',
                        'noopener,noreferrer,width=600,height=450'
                    );
                });
            }

            const waBtn = document.getElementById('shareEventWhatsapp');
            if (waBtn) {
                waBtn.addEventListener('click', function() {
                    const text = encodeURIComponent(`${shareTitle} - ${shareUrl}`);
                    window.open(`https://wa.me/?text=${text}`, '_blank', 'noopener,noreferrer');
                });
            }

            const copyFeedback = document.getElementById('copyLinkFeedback');
            async function copyEventPageLink() {
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(shareUrl);
                    } else {
                        const tempInput = document.createElement('input');
                        tempInput.value = shareUrl;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                    }
                    if (copyFeedback) {
                        copyFeedback.classList.remove('d-none');
                        setTimeout(() => copyFeedback.classList.add('d-none'), 3000);
                    }
                } catch (err) {
                    alert('Copy link: ' + shareUrl);
                }
            }

            const copyBtn = document.getElementById('copyEventLinkBtn');
            if (copyBtn) copyBtn.addEventListener('click', copyEventPageLink);

            const inviteBtn = document.getElementById('inviteCopyBtn');
            if (inviteBtn) inviteBtn.addEventListener('click', copyEventPageLink);
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const audio = document.getElementById('memorialAudio');
            if (!audio) return;

            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

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
            if (!audio) return;

            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

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
</body>

</html>
