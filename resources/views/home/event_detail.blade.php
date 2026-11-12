<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $event->title ?? $event->event_title }} - Memorial Event Details</title>

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
    $eventTitle = $event->title ?? $event->event_title ?? 'Memorial Event';
    $eventVenue = $event->venue ?? $event->location;
    $isPublicProfile = $memorialContext['isPublicProfile'] ?? false;
    $memorialName = $memorialContext['memorialName'] ?? null;
    $profileImage = $memorialContext['profileImage'] ?? null;
    $heroImage = $event->banner_image ?: $profileImage;

    $birthYear = ($isPublicProfile && $profile?->date_of_birth)
        ? \Carbon\Carbon::parse($profile->date_of_birth)->format('Y')
        : null;
    $deathYear = ($isPublicProfile && $profile?->date_of_death)
        ? \Carbon\Carbon::parse($profile->date_of_death)->format('Y')
        : null;

    $formattedEventDate = $eventDate
        ? $eventDate->format('l, F d, Y')
        : 'Date to be announced';

    $formattedTime = null;
    if (!empty($event->start_time)) {
        try {
            $formattedTime = \Carbon\Carbon::parse($event->start_time)->format('g:i A');
        } catch (\Throwable) {
            $formattedTime = $event->start_time;
        }
        if (!empty($event->end_time)) {
            try {
                $formattedTime .= ' - ' . \Carbon\Carbon::parse($event->end_time)->format('g:i A');
            } catch (\Throwable) {
                $formattedTime .= ' - ' . $event->end_time;
            }
        }
    }

    $donationTargetUrl = ($isPublicProfile && $profile?->slug)
        ? url('/memory_index/' . $profile->slug . '#tributeTab')
        : url('/donation_form');
  @endphp

  <!-- Hero Header -->
  <header class="memorial-hero d-flex align-items-end">
    <div class="container py-4">
      <div class="row align-items-center g-3">
        <div class="col-8">
          <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 small">
              <li class="breadcrumb-item">
                <a href="{{ url('/') }}" class="text-warning text-decoration-none">Home</a>
              </li>
              <li class="breadcrumb-item">
                <a href="{{ route('events.index') }}" class="text-warning text-decoration-none">Events</a>
              </li>
              <li class="breadcrumb-item active text-white-50" aria-current="page">{{ \Illuminate\Support\Str::limit($eventTitle, 40) }}</li>
            </ol>
          </nav>

          <h1 class="memorial-title text-white mb-1">{{ $eventTitle }}</h1>

          @if($memorialName)
            <p class="fs-5 text-white-50 font-serif mb-0">
              In loving memory of {{ $memorialName }}
              @if($birthYear || $deathYear)
                ({{ collect([$birthYear, $deathYear])->filter()->implode(' - ') }})
              @endif
            </p>
          @elseif($event->user?->name)
            <p class="fs-6 text-white-50 font-serif mb-0">
              Hosted by {{ $event->user->name }}
            </p>
          @endif
        </div>

        <div class="col-4 text-end">
          <img src="{{ $heroImage ? asset($heroImage) : asset('images/profile_photo.png') }}"
               class="memorial-avatar rounded-3 shadow"
               alt="{{ $eventTitle }}">
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Layout -->
  <main class="container my-4">
    <div class="row g-4">

      <!-- Left Column: Memorial Event & Live Stream Details -->
      <div class="col-lg-8 d-flex flex-column gap-4">

        <!-- Live Event Stream Banner -->
        <div class="live-banner rounded p-3 text-white d-flex flex-wrap align-items-center justify-content-between gap-3 shadow-sm animate-main-column">
          <div class="d-flex align-items-center gap-3">
            <span class="live-badge rounded-pill px-3 py-1 fw-bold text-uppercase d-flex align-items-center gap-2">
              <span class="pulse-dot"></span>
              @if($event->event_type === 'live')
                LIVE
              @elseif($event->event_type === 'past')
                PAST EVENT
              @else
                LIVE / UPCOMING
              @endif
            </span>
            <div>
              <h6 class="mb-0 fw-bold">{{ $eventTitle }}</h6>
              <small class="opacity-75">
                @if($streamEmbedUrl)
                  @if($event->event_type === 'live')
                    Live Webcast is active now
                  @elseif($event->event_type === 'past')
                    Recorded ceremony stream available below
                  @else
                    Live Webcast will start on {{ $formattedEventDate }}
                  @endif
                @else
                  {{ $eventVenue ?: 'Memorial Remembrance Service' }}
                @endif
              </small>
            </div>
          </div>
          <a href="#eventVideo" class="btn btn-light btn-sm text-dark fw-bold rounded-pill px-3">
            <i class="bi bi-broadcast me-1 text-danger"></i> Watch Stream
          </a>
        </div>

        <!-- Notification Banner / Reminder Announcement -->
        <div class="sidebar-card p-3 rounded border-start border-4 border-warning shadow-sm animate-main-column">
          <div class="d-flex align-items-start gap-3">
            <div class="bg-warning bg-opacity-20 p-2 rounded text-warning-emphasis">
              <i class="bi bi-bell-fill fs-4"></i>
            </div>
            <div class="flex-grow-1">
              <h6 class="fw-bold text-dark mb-1">{{ $eventTitle }} — Commemoration Notice</h6>
              <p class="small text-muted mb-2">
                @if($memorialName)
                  The family and loved ones of <strong>{{ $memorialName }}</strong> warmly invite relatives, friends, and well-wishers to join this memorial gathering{{ $streamEmbedUrl ? ' in person or via the live webcast below' : '' }}.
                @else
                  Relatives, friends, and well-wishers are warmly invited to join this memorial commemoration{{ $streamEmbedUrl ? ' in person or via the live webcast below' : '' }}.
                @endif
              </p>
              <!-- Countdown / Scheduled Date Widget -->
              <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                <span class="badge bg-memorial-accent text-white py-2 px-3 rounded-pill fs-7">
                  <i class="bi bi-clock-history me-1"></i> {{ $countdownLabel }}
                </span>
                <span class="badge bg-white text-dark border border-memorial py-2 px-3 rounded-pill">
                  <i class="bi bi-calendar3 me-1 text-memorial-accent"></i> {{ $formattedEventDate }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Event Card -->
        <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card animate-main-column">
          @if($event->banner_image)
            <div class="rounded overflow-hidden mb-4 border border-memorial shadow-sm">
              <img src="{{ asset($event->banner_image) }}"
                   alt="{{ $eventTitle }}"
                   class="w-100 object-fit-cover"
                   style="max-height: 320px;">
            </div>
          @endif

          <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 border-bottom pb-3 mb-4">
            <div>
              <h4 class="fw-bold text-dark mb-1">{{ $event->title ?? $event->event_title }}</h4>
              <p class="text-muted small mb-0">
                @if($memorialName)
                  Commemorating <strong>{{ $memorialName }}</strong>
                @else
                  Organized by <strong>{{ $event->user?->name ?? 'Memorial Host' }}</strong>
                @endif
              </p>
            </div>
            <span class="badge bg-memorial-accent text-white rounded-pill px-3 py-2 text-uppercase">
              {{ ucfirst($event->event_type ?? 'upcoming') }}
            </span>
          </div>

          <!-- Event Details List -->
          <div class="event-details d-flex flex-column gap-3 mb-4">

            <div class="d-flex align-items-start gap-3">
              <i class="bi bi-calendar-event fs-5 text-memorial-accent mt-1"></i>
              <div>
                <strong class="text-dark d-block">Date &amp; Time</strong>
                <span class="small-textbold">
                  {{ $formattedEventDate }}
                  @if($endDate && $eventDate && !$endDate->isSameDay($eventDate))
                    — {{ $endDate->format('l, F d, Y') }}
                  @endif
                  @if($formattedTime)
                    at {{ $formattedTime }}
                  @endif
                </span>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3">
              <i class="bi bi-camera-video fs-5 text-memorial-accent mt-1"></i>
              <div>
                <strong class="text-dark d-block">Participation Mode</strong>
                <span class="small-textbold small">{{ $participationMode }}</span>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3">
              <i class="bi bi-info-circle fs-5 text-memorial-accent mt-1"></i>
              <div>
                <strong class="text-dark d-block">Event Description &amp; Instructions</strong>
                <p class="small-textbold small mb-0" style="white-space: pre-line;">{{ $event->description ?: 'Detailed schedule and family instructions will be shared here ahead of the service.' }}</p>
              </div>
            </div>

            <div class="d-flex align-items-start gap-3">
              <i class="bi bi-geo-alt fs-5 text-memorial-accent mt-1"></i>
              <div>
                <strong class="text-dark d-block">Venue Location</strong>
                <span class="small-textbold small">
                  {{ $eventVenue ?: 'Virtual / Online Stream Only' }}
                </span>
                @if($event->map_link)
                  <div class="mt-1">
                    <a href="{{ $event->map_link }}" target="_blank" rel="noopener noreferrer" class="small text-memorial-accent fw-semibold text-decoration-none">
                      <i class="bi bi-box-arrow-up-right me-1"></i> Open Exact Location in Google Maps
                    </a>
                  </div>
                @endif
              </div>
            </div>

          </div>

          <!-- Location Map Embed -->
          @if($mapEmbedUrl)
            <div class="map-container rounded mb-4 overflow-hidden shadow-sm border">
              <iframe
                src="{{ $mapEmbedUrl }}"
                width="100%"
                height="240"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Event Location Map">
              </iframe>
            </div>
          @endif

          <!-- Event Video Stream Section -->
          <div id="eventVideo" class="pt-3 border-top">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
              <div>
                <h6 class="fw-bold text-dark mb-1">Live Event Broadcast</h6>
                <p class="small text-muted mb-0">This service is streamed below so family members and friends globally can participate.</p>
              </div>
              @if($rawStreamUrl)
                <a href="{{ $rawStreamUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-memorial-dark btn-sm rounded-pill px-3">
                  <i class="bi bi-box-arrow-up-right me-1"></i> Open Stream
                </a>
              @endif
            </div>

            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm bg-black mt-3">
              @if($streamEmbedUrl)
                <iframe
                  src="{{ $streamEmbedUrl }}"
                  title="{{ $eventTitle }} Live Broadcast"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  referrerpolicy="strict-origin-when-cross-origin"
                  allowfullscreen>
                </iframe>
              @else
                <div class="d-flex align-items-center justify-content-center text-white flex-column p-4 text-center">
                  <i class="bi bi-play-circle fs-1 text-warning mb-2"></i>
                  <span class="small text-light">Livestream will activate when the event starts.</span>
                </div>
              @endif
            </div>
          </div>

        </div>

      </div>

      <!-- Right Column: Sidebar (Donations, Share & Memorial Info) -->
      <div class="col-lg-4 d-flex flex-column gap-3">

        <!-- Donation / Meritorious Contributions Card -->
        <div class="main-content-card p-3 rounded shadow-sm bg-memorial-card">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-heart-fill text-danger fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark">Flowers / Donations</h6>
          </div>

          <p class="small fw-semibold mb-3">
            @if($memorialName)
              Offer meritorious tributes, flowers, or candles in loving memory of {{ $memorialName }}.
            @else
              Support this memorial event and offer flowers or candles in remembrance.
            @endif
          </p>

          <div class="donation-progress bg-white p-3 rounded border mb-3">
            <div class="d-flex justify-content-between align-items-baseline mb-2">
              <span class="fs-4 fw-bold text-dark">${{ number_format($totalDonationAmount, 2) }}</span>
              <span class="small fw-semibold">
                {{ $donorCount }} {{ \Illuminate\Support\Str::plural('Contribution', $donorCount) }}
              </span>
            </div>
            <div class="d-flex justify-content-between small text-muted pt-2 border-top">
              <span>🌹 {{ $totalFlowers }} {{ \Illuminate\Support\Str::plural('Flower', $totalFlowers) }}</span>
              <span>🕯️ {{ $totalCandles }} {{ \Illuminate\Support\Str::plural('Candle', $totalCandles) }}</span>
            </div>
          </div>

          @if($event->allow_donation)
            <a href="{{ $donationTargetUrl }}" class="btn btn-memorial-submit w-100 py-2 text-white fw-bold mb-3 text-decoration-none text-center">
              <i class="bi bi-gift me-1"></i>
              {{ ($isPublicProfile && $profile?->slug) ? 'Offer Flowers / Contribute' : 'Make a Donation' }}
            </a>
          @else
            <div class="alert alert-light border small text-muted text-center py-2 mb-3">
              Contributions are currently closed for this event.
            </div>
          @endif

          <!-- Recent Contributions -->
          <h6 class="fw-bold small text-dark border-bottom pb-2 mb-2">Recent Contributions</h6>
          @forelse($tributes->take(3) as $tribute)
            @php
              $contributorName = $tribute->user?->name
                  ?: ($tribute->message ?: 'Memorial Supporter');
            @endphp
            <div class="d-flex justify-content-between align-items-center small py-2 {{ !$loop->last ? 'border-bottom border-light-subtle' : '' }}">
              <span class="small-textbold text-truncate me-2">
                {{ $tribute->tribute_type === 'candle' ? '🕯️' : '🌹' }}
                {{ $contributorName }}
              </span>
              <span class="fw-bold text-dark">${{ number_format((float) $tribute->total_amount, 2) }}</span>
            </div>
          @empty
            <p class="small text-muted mb-0">No contributions recorded yet. Be the first to honor this occasion.</p>
          @endforelse
        </div>

        <!-- Share Memorial / Event Section -->
        <div class="main-content-card p-3 rounded shadow-sm">
          <h6 class="fw-bold mb-2 text-dark">Share this memorial event</h6>
          <p class="small fw-semibold mb-3">Help notify relatives and friends by sharing this invitation page.</p>

          <div class="d-flex gap-2">
            <button type="button"
                    id="shareEventFacebook"
                    class="btn btn-facebook flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                    title="Share on Facebook">
              <i class="bi bi-facebook fs-5"></i>
            </button>
            <button type="button"
                    id="shareEventWhatsapp"
                    class="btn btn-whatsapp flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                    title="Share on WhatsApp">
              <i class="bi bi-whatsapp fs-5"></i>
            </button>
            <button type="button"
                    id="copyEventLinkBtn"
                    class="btn btn-outline-secondary flex-fill py-2 d-flex align-items-center justify-content-center gap-1"
                    title="Copy Link">
              <i class="bi bi-link-45deg fs-5"></i>
            </button>
          </div>
          <div id="copyLinkFeedback" class="small text-success fw-semibold mt-2 d-none">
            <i class="bi bi-check-circle-fill me-1"></i> Event link copied to clipboard!
          </div>
        </div>

        <!-- Memorial Profile Info Badge -->
        <div class="sidebar-card p-3 rounded shadow-sm bg-memorial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="{{ $profileImage ? asset($profileImage) : asset('images/profile_photo.png') }}"
                 alt="{{ $memorialName ?: $eventTitle }}"
                 class="rounded-circle object-fit-cover border border-2 border-white shadow-sm"
                 style="width: 56px; height: 56px;">
            <div class="min-w-0">
              <span class="badge bg-memorial-accent text-white mb-1">Memorial Profile</span>
              <h6 class="fw-bold mb-0 text-dark text-truncate">
                @if($memorialName)
                  {{ $memorialName }}
                @elseif($profile && !$isPublicProfile)
                  Private Memorial
                @else
                  {{ $event->user?->name ?? 'Memorial Host' }}
                @endif
              </h6>
              @if($birthYear || $deathYear)
                <small class="text-muted">{{ collect([$birthYear, $deathYear])->filter()->implode(' - ') }}</small>
              @elseif($isPublicProfile && $profile?->country)
                <small class="text-muted">{{ $profile->country }}</small>
              @endif
            </div>
          </div>

          @if($isPublicProfile && $profile?->slug)
            <a href="{{ url('/memory_index/' . $profile->slug) }}" class="btn btn-invite w-100 py-2 mb-2 text-decoration-none text-center">
              <i class="bi bi-journal-bookmark-fill me-1"></i> Visit Memorial Page
            </a>
          @endif

          <a href="{{ route('events.index') }}" class="btn btn-outline-memorial-dark w-100 py-2 text-decoration-none text-center">
            <i class="bi bi-arrow-left me-1"></i> Back to All Events
          </a>
        </div>

        <!-- Event Visibility & Status Panel -->
        <div class="sidebar-card p-3 rounded shadow-sm">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small fw-bold text-dark"><i class="bi bi-calendar-check me-1"></i> Event Status</span>
            <span class="badge {{ $event->event_type === 'live' ? 'bg-danger' : ($event->event_type === 'past' ? 'bg-secondary' : 'bg-success') }}">
              {{ ucfirst($event->event_type ?? 'upcoming') }}
            </span>
          </div>
          <div class="d-flex align-items-center gap-2 small text-muted pt-2 border-top border-secondary-subtle">
            <i class="bi {{ $event->is_private ? 'bi-lock' : 'bi-globe' }}"></i>
            <span>{{ $event->is_private ? 'Private event access' : 'Open public access' }}</span>
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
            A dignified digital platform dedicated to preserving legacies, connecting families, and honoring cherished memories forever.
          </p>
        </div>

        <div class="col-lg-2 col-md-6">
          <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
            <li><a href="{{ url('/') }}" class="text-light-tan text-decoration-none">Home</a></li>
            <li><a href="{{ url('/all_memories') }}" class="text-light-tan text-decoration-none">Memories</a></li>
            <li><a href="{{ route('events.index') }}" class="text-light-tan text-decoration-none">Events</a></li>
            <li><a href="{{ url('/about') }}" class="text-light-tan text-decoration-none">About Us</a></li>
            <li><a href="{{ url('/contact') }}" class="text-light-tan text-decoration-none">Contact Us</a></li>
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
            <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
            <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
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
    document.addEventListener("DOMContentLoaded", function () {
      const elements = document.querySelectorAll('.animate-main-column');
      if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('reveal');
              obs.unobserve(entry.target);
            }
          });
        }, { root: null, rootMargin: '0px', threshold: 0.05 });

        elements.forEach(el => observer.observe(el));
      } else {
        elements.forEach(el => el.classList.add('reveal'));
      }

      const shareUrl = window.location.href;
      const shareTitle = @json($eventTitle);

      const fbBtn = document.getElementById('shareEventFacebook');
      if (fbBtn) {
        fbBtn.addEventListener('click', function () {
          window.open(
            `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
            '_blank',
            'noopener,noreferrer,width=600,height=450'
          );
        });
      }

      const waBtn = document.getElementById('shareEventWhatsapp');
      if (waBtn) {
        waBtn.addEventListener('click', function () {
          const text = encodeURIComponent(`${shareTitle} - ${shareUrl}`);
          window.open(`https://wa.me/?text=${text}`, '_blank', 'noopener,noreferrer');
        });
      }

      const copyBtn = document.getElementById('copyEventLinkBtn');
      const feedbackEl = document.getElementById('copyLinkFeedback');
      if (copyBtn) {
        copyBtn.addEventListener('click', async function () {
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
            if (feedbackEl) {
              feedbackEl.classList.remove('d-none');
              setTimeout(() => feedbackEl.classList.add('d-none'), 3000);
            }
          } catch (err) {
            alert('Copy this event link: ' + shareUrl);
          }
        });
      }
    });
  </script>
</body>
</html>

