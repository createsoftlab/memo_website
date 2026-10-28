<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Life - Lallan Writer Memorial</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom Stylesheets -->
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/life.css') }}">
</head>
<body class="bg-memorial-light">

        @include('home.header')

  <!-- Hero Header -->
  <header class="memorial-hero d-flex align-items-end">
    <div class="container py-4">
      <div class="row align-items-center">
        <div class="col-8">
          <h1 class="memorial-title text-white mb-0">Lallan Writer</h1>
          <p class="fs-4 text-white-50 font-serif mb-0">1945 - 2021</p>
        </div>
        <div class="col-4 text-end">
          <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?fit=crop&w=300&h=300" class="memorial-avatar rounded-3 shadow" alt="Lallan Writer">
        </div>
      </div>
    </div>
  </header>

  <!-- Navigation Tabs Bar -->
  <div class="memorial-tabs-bar sticky-top">
    <div class="container">
      <ul class="nav nav-tabs border-0">
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_index') }}">ABOUT</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ url('/memories_life') }}">LIFE</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_gallery') }}">GALLERY</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_stories') }}">STORIES</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_timeline') }}">TIMELINE</a></li>
                <li class="nav-item"><a class="nav-link " href="{{ url('/memories_event') }}">EVENTS</a></li>

                                                <li class="nav-item"><a class="nav-link " href="{{ url('/memories_donation') }}">DONATIONS</a></li>


      </ul>
    </div>
  </div>

  <!-- Main Content Layout -->
  <main class="container my-4">
    <div class="row g-4">
      
<div class="col-lg-8 d-flex flex-column gap-3">

    <!-- Story 1: Early Life -->
    <div class="life-story-card p-4 rounded shadow-sm position-relative reveal-card" data-delay="100">
      <div class="badge-wrapper mb-2">
          <span class="badge custom-badge bg-memorial-brown px-3 py-2 fs-6 d-inline-flex align-items-center gap-2 shadow-sm">
              <i class="bi bi-heart-fill"></i> Early Life
          </span>
      </div>
      <h4 class="fw-bold text-dark mb-1">Birth & Childhood</h4>
      <p class="small text-muted mb-3">Posted on August 14 &bull; By <span class="text-memorial-accent fw-semibold">Family Admin</span></p>
      
      <div class="story-content text-secondary mb-3">
        <p class="mb-0 story-highlight-text">Born and brought up with a deep affection for literature and classical arts. From a very young age, Lallan spent endless hours reading classical literature and writing short stories that inspired his close friends and family.</p>
      </div>

      <!-- <button class="btn btn-sm btn-link text-muted text-decoration-none p-0 share-story-btn">
        <i class="bi bi-share me-1"></i> Share
      </button> -->
    </div>

    <!-- Story 2: Career & Achievements -->
    <div class="life-story-card p-4 rounded shadow-sm position-relative reveal-card" data-delay="200">
      <div class="badge-wrapper mb-2">
          <span class="badge custom-badge bg-memorial-brown px-3 py-2 fs-6 d-inline-flex align-items-center gap-2 shadow-sm">
              <i class="bi bi-heart-fill"></i> Career & Achievements
          </span>
      </div>
      <h4 class="fw-bold text-dark mb-1">Career & Achievements</h4>
      <p class="small text-muted mb-3">Posted on August 14 &bull; By <span class="text-memorial-accent fw-semibold">Family Admin</span></p>
      
      <div class="story-content text-secondary mb-3">
        <p class="mb-0 story-highlight-text">Published numerous poems, stories, and inspired thousands of students worldwide. Throughout his illustrious literary career, he mentored hundreds of budding poets and left a lasting imprint on contemporary prose.</p>
      </div>

      <!-- <button class="btn btn-sm btn-link text-muted text-decoration-none p-0 share-story-btn">
        <i class="bi bi-share me-1"></i> Share
      </button> -->
    </div>

    <!-- Contribution Notice Box -->
    <div class="life-story-card p-4 rounded shadow-sm d-flex align-items-center gap-3 reveal-card" data-delay="300">
      <i class="bi bi-lock-fill fs-3 text-muted"></i>
      <p class="small text-muted mb-0">
        If you would like to contribute to Lallan's biography, please send your story over to the <a href="#" class="text-memorial-accent fw-bold text-decoration-none">memorial administrator</a>.
      </p>
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
        <button class="btn btn-facebook w-100 py-2 d-flex align-items-center justify-content-center gap-2" id="shareFbBtn">
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
          <div class="d-flex align-items-center gap-2 small text-muted pt-2 border-top border-secondary-subtle">
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


  <!-- JavaScript Files (Standardized Order) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('memo_website_dev_frontend/assets/js/main.js') }}"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.12 // Element eken 12% k screen ekata aawama trigger wei
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const delay = el.getAttribute('data-delay') || 0;

                // Apply dynamic delay if set
                setTimeout(() => {
                    el.classList.add('active');
                }, parseInt(delay));

                // Unobserve after animation runs once
                observer.unobserve(el);
            }
        });
    }, observerOptions);

    // Target all reveal-card elements
    const revealCards = document.querySelectorAll('.reveal-card');
    revealCards.forEach(card => observer.observe(card));
});
  </script>
</body>
</html>