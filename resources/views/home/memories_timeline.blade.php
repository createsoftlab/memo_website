<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline - Lallan Writer Memorial</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/timeline.css') }}">
</head>

<body class="bg-memorial-light">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-memorial-dark px-3 py-2">
        <div class="container-fluid">
            <a class="navbar-brand text-warning fs-6 fw-bold" href="{{ url('/') }}">
                <i class="bi bi-fire me-1"></i> Forever Missed
            </a>
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end bg-memorial-dark text-white" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-header border-bottom border-secondary d-lg-none">
                    <h5 class="offcanvas-title text-warning"><i class="bi bi-fire me-1"></i> Navigation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div
                    class="offcanvas-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between w-100">
                    <ul class="navbar-nav text-uppercase small gap-lg-3 me-lg-auto mb-3 mb-lg-0">
                        <li class="nav-item"><a class="nav-link py-2" href="{{ url('/') }}"><i
                                    class="bi bi-house-door me-1"></i> Home</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/all_memories') }}">
                                <i class="bi bi-heart-fill"></i> Memories
                            </a>
                        <li class="nav-item"><a class="nav-link py-2" href="{{ url('/') }}"><i
                                    class="bi bi-info-circle me-1"></i> About Us</a></li>
                        <li class="nav-item"><a class="nav-link py-2" href="#"><i class="bi bi-envelope me-1"></i>
                                Contact Us</a></li>
                    </ul>
                    <div id="authButtons" class="d-flex flex-column flex-lg-row gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm text-uppercase" id="loginBtn"><i
                                class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                        <a href="#" class="btn btn-warning btn-sm text-uppercase fw-semibold" id="signupBtn"><i
                                class="bi bi-person-plus me-1"></i> Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

  @php

    // $coverImage = \App\Models\Image::where('profile_id', $memory->profile?->id)->where('type', 'cover')->first();
    $backgroundMusic = \App\Models\Music::where('profile_id', $memory->profile?->id)->first();
    // Get profile image from images table
    $profileImage = \App\Models\Image::where('profile_id', $memory->profile?->id)
        ->where('type', 'profile')
        ->first();

    $birthYear = $profile->date_of_birth
        ? \Carbon\Carbon::parse($profile->date_of_birth)->format('Y')
        : null;

    $deathYear = $profile->date_of_death
        ? \Carbon\Carbon::parse($profile->date_of_death)->format('Y')
        : null;
  @endphp

    <!-- Hero Header -->
    <header class="memorial-hero d-flex align-items-end">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-8">
                  <h1 class="memorial-title text-white mb-0">{{ $memory->profile?->first_name }} {{ $memory->profile?->last_name }}</h1>
          @if ($birthYear || $deathYear)
          <p class="fs-4 text-white-50 font-serif mb-0">
            {{ collect([$birthYear, $deathYear])->filter()->implode(' - ') }}
          </p>
          @endif
                </div>
                <div class="col-4 text-end">
                    <img src="{{ $profileImage? asset($profileImage->path): asset('images/profile_photo.png') }}"
                        class="memorial-avatar rounded-3 shadow" alt="Lallan Writer">
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
                <li class="nav-item"><a class="nav-link active"
                        href="{{ url('/memories_timeline/' . $memory->profile->slug) }}">TIMELINE</a></li>
                <li class="nav-item"><a class="nav-link "
                        href="{{ url('/memories_event/' . $memory->profile->slug) }}">EVENTS</a></li>
                <li class="nav-item"><a class="nav-link "
                        href="{{ url('/memories_donation/' . $memory->profile->slug) }}">DONATIONS</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Layout -->
    <main class="container my-4">
        <div class="row g-4">

            <!-- Left Column: Vertical Timeline -->
            <div class="col-lg-8">
                @if ($memory->profile?->first_name)
                    <h4 class="fw-bold text-dark mb-4">{{ $memory->profile->first_name }}'s timeline</h4>
                @endif
                <div class="vertical-timeline position-relative ps-4">

                    @forelse ($timelines as $timeline)
                        <!-- Timeline Item -->
                        <div class="timeline-entry position-relative mb-4 pb-2 timeline-item">

                            <span class="timeline-year badge bg-light text-secondary border mb-2">
                                {{ $timeline->title }}
                            </span>

                            <div class="d-flex align-items-start gap-3">

                                <div
                                    class="timeline-icon rounded-circle d-flex align-items-center justify-content-center text-memorial-accent bg-white border border-2">
                                    <i class="bi bi-star fs-5"></i>
                                </div>

                                <div class="timeline-body">

                                    <h6 class="fw-bold mb-1 text-dark">
                                        {{ $timeline->description }}
                                    </h6>

                                    @if ($timeline->event_date)
                                        <p class="small fw-bold mb-0">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $timeline->event_date }}
                                        </p>
                                    @endif

                                    @if ($timeline->location)
                                        <p class="small fw-bold mb-0">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $timeline->location }}
                                        </p>
                                    @endif

                                </div>
                            </div>

                            <!-- Timeline Image -->
                            @if ($timeline->image_path)
                                <div class="timeline-media ms-5 ps-2 mt-3">
                                    <img src="{{ asset($timeline->image_path) }}"
                                        class="img-fluid rounded border shadow-sm"
                                        style="width: 100%; height: 200px; object-fit: cover;"
                                        alt="{{ $timeline->title }}">
                                </div>
                            @endif

                        </div>

                    @empty

                        <!-- No Timeline Message -->
                        <div id="noTimelinesMessage" class="col-12 text-center py-5">

                            <i class="bi bi-clock-history display-4 text-muted"></i>

                            <h5 class="mt-3 text-muted">
                                No Timeline Events Yet
                            </h5>

                            <p class="small text-muted mb-0">
                                Life's special moments and milestones have not been added yet.
                                Check back later to explore this memorial's journey.
                            </p>

                        </div>
                    @endforelse



                </div>
            </div>

            <!-- Right Column: Sidebar Panels -->
            <div class="col-lg-4 d-flex flex-column gap-3">

                <!-- Invite & Donate Card -->
                <div class="sidebar-card p-3 rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="fw-bold mb-0 me-2 text-dark">Invite Lallan's family and friends</h6>
                        <i class="bi bi-person-add fs-4 text-muted"></i>
                    </div>
                    <button class="btn btn-invite w-100 py-2 mb-2">
                        <i class="bi bi-person-plus me-1"></i> Invite now
                    </button>
                    <button class="btn btn-donate w-100 py-2">
                        <i class="bi bi-heart-fill me-1"></i> Donate now
                    </button>
                </div>

                <!-- Share Button -->
                <button class="btn btn-facebook w-100 py-2 d-flex align-items-center justify-content-center gap-2"
                    id="shareFbBtn">
                    <i class="bi bi-facebook fs-5 text-primary"></i>
                    <span>Share on Facebook</span>
                </button>

                <!-- Notification Preferences Card -->
                <!-- <div class="sidebar-card p-3 rounded">
          <h6 class="fw-bold mb-1 text-dark">Notification preferences</h6>
          <p class="small text-muted mb-3" id="subStatus">You are not subscribed</p>
          <button class="btn btn-outline-memorial-dark w-100 py-2" id="subscribeBtn">Subscribe</button>
        </div> -->

                <!-- Recent Updates -->
                <!-- <div class="sidebar-card p-3 rounded">
          <h6 class="fw-bold mb-2 text-dark">Recent updates</h6>
          <p class="small text-muted mb-2">Friday, August 14</p>
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-journal-text fs-5 text-muted"></i>
            <span class="small text-dark"><strong>Family Admin</strong> added 2 life story chapters.</span>
          </div>
        </div> -->

                <!-- Page Views Info -->
                <div class="sidebar-card p-3 rounded">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-eye fs-5 text-dark"></i>
                        <h6 class="fw-bold mb-0 text-dark">24 Views</h6>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('memo_website_dev_frontend/assets/js/main.js') }}"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15 // අයිතමයෙන් 15ක් තිරයට පෙනුනු විගස ට්‍රිගර් වේ
            };

            const timelineObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal');
                        observer.unobserve(entry
                        .target); // එක සැරයක් ඇනිමේෂන් එක වුණාට පසු නැවත අවශ්‍ය නොවේ
                    }
                });
            }, observerOptions);

            // සියලුම timeline items සඳහා observer එක සම්බන්ධ කිරීම
            document.querySelectorAll('.timeline-item').forEach(item => {
                timelineObserver.observe(item);
            });
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            const isMuted = localStorage.getItem('memorialAudioMuted');

            if (isMuted === 'false') {

                audio.muted = false;
                audio.play().catch(error => console.log("Autoplay restricted"));
                audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                audioText.innerText = "Mute Music";
            } else {

                audio.muted = true;
                audio.play().catch(error => console.log("Autoplay restricted"));
                audioIcon.className = "bi bi-volume-mute-fill fs-6";
                audioText.innerText = "Play Music";
            }
        });


        function toggleBackgroundMusic() {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            if (audio.muted) {
                audio.muted = false;
                audio.play();
                audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                audioText.innerText = "Mute Music";
                localStorage.setItem('memorialAudioMuted', 'false');
            } else {
                audio.muted = true;
                audioIcon.className = "bi bi-volume-mute-fill fs-6";
                audioText.innerText = "Play Music";
                localStorage.setItem('memorialAudioMuted', 'true');
            }
        }
    </script>
    
</body>

</html>
