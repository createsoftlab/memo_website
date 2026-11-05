<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stories - {{ trim(collect([$profile->first_name, $profile->middle_name, $profile->last_name])->filter()->implode(" ")) }} | Forever Missed</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom Stylesheets -->
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/stories.css') }}">
</head>
<body class="bg-memorial-light">

        @include('home.header')

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
          <img src="{{ $profileImage? asset($profileImage->path): asset('images/profile_photo.png') }}" class="memorial-avatar rounded-3 shadow" alt="">
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
        <li class="nav-item"><a class="nav-link" href="{{ url('/memory_index/' . $memory->profile->slug) }}">ABOUT</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_life/' . $memory->profile->slug) }}">LIFE</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_gallery/' . $memory->profile->slug) }}">GALLERY</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ url('/memories_stories/' . $memory->profile->slug) }}">STORIES</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_timeline/'. $memory->profile->slug) }}">TIMELINE</a></li>
        <li class="nav-item"><a class="nav-link " href="{{ url('/memories_event/'. $memory->profile->slug) }}">EVENTS</a></li>
       <li class="nav-item"><a class="nav-link " href="{{ url('/memories_donation/' . $memory->profile->slug) }}">DONATIONS</a></li>
      </ul>
    </div>
  </div>

  <!-- Main Content Layout -->
  <main class="container my-4">
    <div class="row g-4">
      
      <!-- Left Column: Story Creation & Posted Stories -->
      <div class="col-lg-8 d-flex flex-column gap-4">
        <p class="story-intro-text small-textbold small mb-0">
          This section is where friends and family can share personal memories of {{ $memory->profile?->last_name }}.
          Whether it is a time you spent together, something that made you laugh, or anything you want
          others to know, we encourage you to tell your story here.
        </p>

        <!-- Share a Story Form Card -->
        <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card">
          <h5 class="fw-bold text-dark mb-3">Share a story</h5>

          @if($errors->any())
            <div class="alert alert-danger" role="alert">
              Please correct the highlighted fields and try again.
            </div>
          @endif

          <form id="storyForm" action="{{ route('story-comments.store', $profile->id) }}" method="POST">
            @csrf
            <div class="mb-3">
              <input type="text" name="title" maxlength="255"
                     class="form-control story-title-input @error('title') is-invalid @enderror"
                     placeholder="Title (Optional)" value="{{ old('title') }}">
              @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <input type="text" name="contributor_name" maxlength="255"
                     class="form-control story-title-input @error('contributor_name') is-invalid @enderror"
                     placeholder="Who is this from? (Optional)" value="{{ old('contributor_name') }}">
              @error('contributor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="editor-container rounded border bg-white overflow-hidden mb-3">
              <div class="editor-toolbar d-flex flex-wrap align-items-center gap-1 p-2 border-bottom bg-light"></div>
              <textarea id="storyContent" name="content" maxlength="10000" rows="6" required
                        class="form-control border-0 p-3 story-textarea @error('content') is-invalid @enderror"
                        placeholder="Write your story here...">{{ old('content') }}</textarea>
            </div>
            @error('content')<div class="text-danger small mb-3">{{ $message }}</div>@enderror

            <div class="text-end">
              <button type="submit" class="btn story-submit-btn text-white px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="bi bi-send-fill submit-icon"></i><span>Publish</span>
              </button>
              {{-- <button type="button" id="generateStoryAI"
                      class="btn btn-ai-generate text-white px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="bi bi-stars ai-sparkles-icon"></i><span>Generate With AI</span>
              </button> --}}
            </div>
          </form>
        </div>

        <!-- Shared Stories Feed -->
        <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card" id="memorableStories">
          <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex justify-content-between align-items-center">
            <span>Memorable Stories</span>
            @if($storyComments->hasPages())
              <div class="story-pagination-nav d-flex gap-1">
                @if($storyComments->onFirstPage())
                  <button type="button" class="btn btn-sm btn-outline-secondary story-page-btn" disabled aria-label="Previous page"><i class="bi bi-chevron-left"></i></button>
                @else
                  <a class="btn btn-sm btn-outline-secondary story-page-btn" aria-label="Previous page" href="{{ $storyComments->previousPageUrl() }}#memorableStories"><i class="bi bi-chevron-left"></i></a>
                @endif
                @if($storyComments->hasMorePages())
                  <a class="btn btn-sm btn-outline-secondary story-page-btn" aria-label="Next page" href="{{ $storyComments->nextPageUrl() }}#memorableStories"><i class="bi bi-chevron-right"></i></a>
                @else
                  <button type="button" class="btn btn-sm btn-outline-secondary story-page-btn" disabled aria-label="Next page"><i class="bi bi-chevron-right"></i></button>
                @endif
              </div>
            @endif
          </h5>

          <div class="stories-feed-container d-flex flex-column gap-3">
            @forelse($storyComments as $comment)
              @php
                $contributorName = $comment->contributor_name ?: 'Anonymous';
                $initials = collect(preg_split('/\s+/u', trim($contributorName)))
                    ->filter()->take(2)
                    ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');
                $avatarColors = ['#854d0e', '#0ea5e9', '#7c3aed', '#059669', '#be123c'];
                $avatarColor = $avatarColors[$comment->id % count($avatarColors)];
                $badgeClass = match(strtolower($comment->relationship ?? '')) {
                    'family' => 'badge-family',
                    'partner' => 'badge-partner',
                    default => 'badge-friend',
                };
              @endphp
              <div class="story-page-item card border-0 bg-white shadow-sm">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <div class="story-user-initials" style="background-color: {{ $avatarColor }};">{{ $initials }}</div>
                      <div>
                        <h6 class="mb-0 fw-semibold text-dark small">{{ $contributorName }}</h6>
                        <span class="text-muted" style="font-size: 0.7rem;">{{ $comment->created_at->diffForHumans() }}</span>
                      </div>
                    </div>
                    @if($comment->relationship)
                      <span class="badge {{ $badgeClass }}">{{ ucfirst($comment->relationship) }}</span>
                    @endif
                  </div>
                  @if($comment->title)
                    <h6 class="card-title fw-bold text-dark mb-1 fs-6">{{ $comment->title }}</h6>
                  @endif
                  <p class="card-text small mb-0 text-muted" style="white-space: pre-line;">{{ $comment->content }}</p>
                </div>
              </div>
            @empty
              <div class="text-center py-4">
                <i class="bi bi-journal-text fs-1 text-muted"></i>
                <h6 class="fw-semibold text-dark mt-2">No stories shared yet</h6>
                <p class="small text-muted mb-0">Be the first to share a beautiful memory.</p>
              </div>
            @endforelse
          </div>

          @if($storyComments->hasPages())
            <div class="story-pagination-footer d-flex justify-content-center align-items-center gap-2 mt-3 flex-wrap">
              @foreach($storyComments->getUrlRange(1, $storyComments->lastPage()) as $page => $url)
                <a href="{{ $url }}#memorableStories"
                   class="story-dot-btn text-decoration-none {{ $storyComments->currentPage() == $page ? 'active' : '' }}"
                   @if($storyComments->currentPage() == $page) aria-current="page" @endif>{{ $page }}</a>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      <!-- Right Column: Sidebar Panels -->
      <div class="col-lg-4 d-flex flex-column gap-3">

        <!-- Invite Panel -->
        <div class="main-content-card p-3 rounded shadow-sm">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="fw-bold mb-0 me-2 text-dark">Invite {{ $profile->first_name }}'s family and friends</h6>
            <i class="bi bi-person-add fs-4 text-muted"></i>
          </div>
          <button class="btn btn-invite w-100 py-2">
            <i class="bi bi-person-plus me-1"></i> Invite now
          </button>
        </div>

        <!-- Share Button -->
        <button class="btn btn-facebook w-100 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm" id="shareFbBtn">
          <i class="bi bi-facebook fs-5 text-primary"></i>
          <span>Share on Facebook</span>
        </button>

        <!-- Notification Preferences Card -->
        <!-- <div class="sidebar-card p-3 rounded shadow-sm">
          <h6 class="fw-bold mb-1 text-dark">Notification preferences</h6>
          <p class="small text-muted mb-3" id="subStatus">You are not subscribed</p>
          <button class="btn btn-outline-memorial-dark w-100 py-2" id="subscribeBtn">Subscribe</button>
        </div> -->

        <!-- Photos Spotlight Card -->
        <div class="main-content-card   p-3 rounded shadow-sm">
          <h6 class="fw-bold mb-3 text-dark">Profile </h6>
          <div class="rounded overflow-hidden">
            <img src="{{ $profileImage? asset($profileImage->path): asset('images/profile_photo.png') }}" class="img-fluid rounded w-100" alt="Spotlight Photo">
          </div>
        </div>

        <!-- Recent Updates Card -->
        <!-- <div class="sidebar-card p-3 rounded shadow-sm">
          <h6 class="fw-bold mb-2 text-dark">Recent updates</h6>
          <p class="small text-muted mb-2">Friday, August 14</p>
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-journal-text fs-5 text-muted"></i>
            <span class="small text-dark"><strong>Connor Graham</strong> added 4 life story chapters.</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-image fs-5 text-muted"></i>
            <span class="small text-dark"><strong>Connor Graham</strong> added 5 photos.</span>
          </div>
        </div> -->

        <!-- Admin Info Panel -->
        <!-- <div class="sidebar-card p-3 rounded shadow-sm">
          <p class="small text-muted mb-1">This website is administered by:</p>
          <p class="small fw-bold text-memorial-accent mb-0">Connor Graham</p>
        </div> -->

        <!-- Page Views Info Panel -->
        <div class="main-content-card  p-3 rounded shadow-sm">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-eye fs-5 text-dark"></i>
            <h6 class="fw-bold mb-0 text-dark">Memorial Profile</h6>
          </div>
          <div class="d-flex align-items-center gap-2 small text-muted pt-2 border-top border-secondary-subtle">
            <i class="bi bi-globe"></i>
            <span>Open access</span>
          </div>
        </div>

      </div>

    </div>
  </main>


    @if(session('success'))
      <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
        <div id="storySuccessToast" class="toast border-0 shadow" role="status" aria-live="polite" aria-atomic="true">
          <div class="toast-header">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">{{ session('success') }}</div>
          <div class="progress rounded-0" style="height: 3px;">
            <div id="storyToastProgress" class="progress-bar bg-success" style="width: 100%; transition: width 3s linear;"></div>
          </div>
        </div>
      </div>
    @endif

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
    const successToast = document.getElementById('storySuccessToast');
    if (successToast && typeof bootstrap !== 'undefined') {
        bootstrap.Toast.getOrCreateInstance(successToast, { delay: 3000 }).show();
        const progress = document.getElementById('storyToastProgress');
        if (progress) requestAnimationFrame(() => requestAnimationFrame(() => { progress.style.width = '0%'; }));
    }

    const cards = document.querySelectorAll('.story-page-item');

    // Intersection Observer හරහා සයිට් එක 스크롤 (scroll) වෙද්දී කාඩ්ස් පෙනෙන ආකාරය පාලනය කිරීම
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15 // කාඩ් එකෙන් 15%ක් පෙනෙන විට animation එක පටන් ගනී
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('scroll-animated');
                // එක වරක් animation එක වුණාට පසු නැවත observer අවශ්‍ය නැති නම්:
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    cards.forEach(card => {
        observer.observe(card);
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