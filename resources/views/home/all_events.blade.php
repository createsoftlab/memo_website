<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Memorial Events &amp; Commemorations - Forever Missed</title>

  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom Stylesheets -->
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/events.css') }}">

  <style>
    .event-stream-card {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .event-stream-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 26px rgba(83, 51, 37, 0.14) !important;
    }
    .event-card-media {
      height: 190px;
      background: linear-gradient(135deg, #2d1a10 0%, #533325 55%, #8c4c2d 100%);
    }
    .event-card-media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .pagination .page-link {
      color: var(--btn-submit-bg);
      border-color: var(--border-input);
    }
    .pagination .page-item.active .page-link {
      background-color: var(--btn-submit-bg);
      border-color: var(--btn-submit-bg);
      color: #ffffff;
    }
    .pagination .page-link:hover {
      background-color: var(--bg-card);
      color: var(--btn-submit-hover);
    }
  </style>
</head>
<body class="bg-memorial-light">

  @include('home.header')

  <!-- Hero Header -->
  <header class="memorial-hero d-flex align-items-end text-white">
    <div class="container py-4">
      <div class="row align-items-end g-3">
        <div class="col-lg-9">
          <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 small">
              <li class="breadcrumb-item">
                <a href="{{ url('/') }}" class="text-warning text-decoration-none">Home</a>
              </li>
              <li class="breadcrumb-item active text-white-50" aria-current="page">Events</li>
            </ol>
          </nav>
          <span class="badge bg-gold text-dark fw-bold mb-2 text-uppercase tracking-wide">
            <i class="bi bi-calendar-heart me-1"></i> Global Memorial Gatherings
          </span>
          <h1 class="memorial-title text-white mb-1">Memorial Events &amp; Commemorations</h1>
          <p class="text-white-50 mb-0">
            Join remembrance ceremonies, annual alms-giving services, and live memorial broadcasts honoring cherished lives.
          </p>
        </div>
        <div class="col-lg-3 text-lg-end">
          <span class="badge bg-memorial-dark border border-warning text-warning px-3 py-2 rounded-pill fs-6 shadow-sm">
            <i class="bi bi-calendar-event me-1"></i>
            {{ $events->total() }} {{ \Illuminate\Support\Str::plural('Event', $events->total()) }}
          </span>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content Layout -->
  <main class="container my-4 pb-4">

    <!-- Filter & Search Bar -->
    <div class="sidebar-card p-3 p-md-4 rounded shadow-sm bg-memorial-card mb-4 animate-main-column">
      <form action="{{ route('events.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-lg-6 col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <i class="bi bi-search"></i>
            </span>
            <input type="text"
                   name="search"
                   class="form-control border-start-0 ps-0"
                   placeholder="Search events by title, venue, or memorial name..."
                   value="{{ request('search') }}">
          </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-7">
          <select name="event_type" class="form-select">
            <option value="">All Event Types</option>
            <option value="live" {{ request('event_type') === 'live' ? 'selected' : '' }}>Live Stream Now</option>
            <option value="upcoming" {{ request('event_type') === 'upcoming' ? 'selected' : '' }}>Upcoming Events</option>
            <option value="past" {{ request('event_type') === 'past' ? 'selected' : '' }}>Past Commemorations</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-2 col-sm-5 d-flex gap-2">
          <button type="submit" class="btn btn-memorial flex-fill fw-semibold">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
          @if(request()->filled('search') || request()->filled('event_type'))
            <a href="{{ route('events.index') }}" class="btn btn-outline-memorial-dark" title="Clear Filters">
              <i class="bi bi-x-lg"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <!-- Events Grid / Stream -->
    <div class="row g-4">
      @forelse($events as $event)
        @php
          $ctx = $memorialContexts[$event->id] ?? [];
          $profile = $ctx['profile'] ?? null;
          $memorialName = $ctx['memorialName'] ?? null;
          $isPublicProfile = $ctx['isPublicProfile'] ?? false;
          $bannerImage = $ctx['bannerImage'] ?? null;

          $eventTitle = $event->title ?? $event->event_title ?? 'Memorial Event';
          $eventLocation = $event->venue ?? $event->location;
          $rawStreamUrl = $event->live_stream_url ?? $event->stream_link ?? $event->broadcast_link ?? null;
          $hasStream = !empty(trim((string) $rawStreamUrl));

          $rawDate = $event->start_date ?? $event->date ?? $event->event_date;
          $formattedDate = $rawDate
              ? \Carbon\Carbon::parse($rawDate)->format('l, F d, Y')
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
        @endphp

        <div class="col-lg-4 col-md-6 d-flex">
          <article class="sidebar-card bg-memorial-card rounded shadow-sm overflow-hidden event-stream-card animate-main-column d-flex flex-column w-100">

            <!-- Event Banner & Live Indicator -->
            <div class="position-relative event-card-media">
              @if($bannerImage)
                <img src="{{ asset($bannerImage) }}" alt="{{ $eventTitle }}">
              @else
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white-50 p-3">
                  <i class="bi {{ $hasStream ? 'bi-broadcast text-warning' : 'bi-calendar2-heart text-gold' }} display-4 mb-2"></i>
                  <span class="small text-uppercase tracking-wide fw-semibold text-light-tan">Memorial Service</span>
                </div>
              @endif

              <!-- Live Stream / Status Badge -->
              <div class="position-absolute top-0 start-0 m-3 d-flex flex-wrap gap-2">
                @if($event->event_type === 'live')
                  <span class="live-badge rounded-pill px-3 py-1 fw-bold text-uppercase d-inline-flex align-items-center gap-2 bg-dark bg-opacity-75">
                    <span class="pulse-dot"></span> LIVE
                  </span>
                @elseif($hasStream && $event->event_type !== 'past')
                  <span class="live-badge rounded-pill px-3 py-1 fw-bold text-uppercase d-inline-flex align-items-center gap-2 bg-dark bg-opacity-75">
                    <span class="pulse-dot"></span> LIVE / UPCOMING
                  </span>
                @elseif($event->event_type === 'past')
                  <span class="badge bg-dark bg-opacity-75 text-light rounded-pill px-3 py-1 fw-semibold text-uppercase">
                    <i class="bi bi-clock-history me-1"></i> Past Event
                  </span>
                @else
                  <span class="badge bg-memorial-accent text-white rounded-pill px-3 py-1 fw-semibold text-uppercase shadow-sm">
                    <i class="bi bi-calendar-check me-1"></i> Upcoming
                  </span>
                @endif
              </div>

              @if($event->allow_donation)
                <span class="badge bg-white text-dark position-absolute top-0 end-0 m-3 shadow-sm rounded-pill px-2 py-1 small" title="Donations & Tributes Enabled">
                  <i class="bi bi-heart-fill text-danger me-1"></i> Donations
                </span>
              @endif
            </div>

            <!-- Card Content -->
            <div class="p-4 d-flex flex-column flex-grow-1">
              <h5 class="fw-bold text-dark font-serif mb-2">{{ $eventTitle }}</h5>

              <!-- Associated Memorial -->
              @if($memorialName)
                <p class="small fw-semibold text-memorial-accent mb-3">
                  <i class="bi bi-flower1 me-1"></i>
                  In memory of:
                  @if($isPublicProfile && $profile?->slug)
                    <a href="{{ url('/memory_index/' . $profile->slug) }}" class="text-memorial-accent text-decoration-underline">
                      {{ $memorialName }}
                    </a>
                  @else
                    {{ $memorialName }}
                  @endif
                </p>
              @elseif($profile && !$isPublicProfile)
                <p class="small fw-semibold text-memorial-accent mb-3">
                  <i class="bi bi-lock-fill me-1"></i> In memory of: Private Memorial
                </p>
              @elseif($event->user?->name)
                <p class="small fw-semibold text-memorial-accent mb-3">
                  <i class="bi bi-person-heart me-1"></i> Hosted by: {{ $event->user->name }}
                </p>
              @endif

              <!-- Date, Time & Venue -->
              <div class="d-flex flex-column gap-2 small mb-3 bg-white bg-opacity-75 p-3 rounded border border-memorial">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-calendar-event text-memorial-accent mt-1"></i>
                  <span class="small-textbold">{{ $formattedDate }}</span>
                </div>
                @if($formattedTime)
                  <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-clock text-memorial-accent mt-1"></i>
                    <span class="small-textbold">{{ $formattedTime }}</span>
                  </div>
                @endif
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-geo-alt text-memorial-accent mt-1"></i>
                  <span class="small-textbold">
                    {{ $eventLocation ?: ($hasStream ? 'Virtual / Online Live Stream' : 'Location to be confirmed') }}
                  </span>
                </div>
              </div>

              <!-- Description Snippet -->
              <p class="small text-muted mb-4 flex-grow-1">
                {{ \Illuminate\Support\Str::limit($event->description ?: 'Join family, friends, and well-wishers to honor and commemorate a cherished life together.', 120) }}
              </p>

              <!-- Primary Action Button -->
              <div class="pt-3 border-top border-memorial d-flex align-items-center justify-content-between gap-2 mt-auto">
                <a href="{{ route('events.show', $event->id) }}" class="btn btn-memorial btn-sm rounded-pill px-3">
                  View Event Details &amp; Stream
                </a>
                @if($hasStream)
                  <span class="small text-danger fw-semibold" title="Live broadcast available">
                    <i class="bi bi-camera-video-fill me-1"></i> Stream
                  </span>
                @endif
              </div>
            </div>

          </article>
        </div>
      @empty
        <div class="col-12">
          <div class="sidebar-card p-5 rounded shadow-sm bg-memorial-card text-center animate-main-column">
            <div class="mb-3">
              <i class="bi bi-calendar-x display-4 text-memorial-accent"></i>
            </div>
            <h4 class="fw-bold text-dark font-serif mb-2">No Memorial Events Scheduled</h4>
            <p class="text-muted small mb-4 max-width-700 mx-auto">
              @if(request()->filled('search') || request()->filled('event_type'))
                No memorial events match your current search or filter criteria. Try clearing your filters to view all scheduled gatherings.
              @else
                There are currently no upcoming or live memorial events published. Please check back later or explore our memorial tributes directory.
              @endif
            </p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              @if(request()->filled('search') || request()->filled('event_type'))
                <a href="{{ route('events.index') }}" class="btn btn-memorial rounded-pill px-4">
                  <i class="bi bi-arrow-counterclockwise me-1"></i> Show All Events
                </a>
              @endif
              <a href="{{ url('/all_memories') }}" class="btn btn-outline-memorial-dark rounded-pill px-4">
                <i class="bi bi-heart-fill me-1"></i> Browse Memories
              </a>
            </div>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination Links -->
    @if($events->hasPages())
      <div class="mt-5 d-flex justify-content-center">
        {{ $events->links('pagination::bootstrap-5') }}
      </div>
    @endif

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
      if (!('IntersectionObserver' in window)) {
        elements.forEach(el => el.classList.add('reveal'));
        return;
      }

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('reveal');
            obs.unobserve(entry.target);
          }
        });
      }, { root: null, rootMargin: '0px', threshold: 0.05 });

      elements.forEach(el => observer.observe(el));
    });
  </script>
</body>
</html>

