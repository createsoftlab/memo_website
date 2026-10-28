<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donations & Causes - Lallan Writer Memorial</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom Stylesheets -->
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/donation.css') }}">
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
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_life') }}">LIFE</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_gallery') }}">GALLERY</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_stories') }}">STORIES</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_timeline') }}">TIMELINE</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_event') }}">EVENTS</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ url('/memories_donation') }}">DONATIONS</a></li>
      </ul>
    </div>
  </div>

  <!-- Main Content Layout -->
  <main class="container my-4">
    <div class="row g-4">
      
      <!-- Left Column: Impact, Expenses & Contribution Form -->
      <div class="col-lg-8 d-flex flex-column gap-4">

        <!-- Intro Card -->
        <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card">
          <h4 class="fw-bold text-dark mb-2"><i class="bi bi-heart-fill text-danger me-2"></i>In Loving Memory & Meritorious Causes</h4>
          <p class=" fw-semibold mb-0">
            In honor of late Lallan Writer, all financial and material donations are directed towards community welfare projects, elders' care homes, and organizing annual memorial alms-giving ceremonies. Transparent details of funds and their impact are listed below.
          </p>
        </div>

        <!-- How Donations Were Spent (Impact & Expenses) -->
        <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card">
          <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="bi bi-journal-check me-2 text-memorial-accent"></i>Where Your Contributions Go 
          </h5>

          <div class="row g-3">
            <!-- Impact Item 1 -->
            <!-- <div class="col-md-6">
              <div class="card h-100 border-0 shadow-sm overflow-hidden">
                <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?fit=crop&w=500&h=250" class="card-img-top" alt="Elderly Home Meals">
                <div class="card-body p-3">
                  <span class="badge bg-success mb-2">Completed</span>
                  <h6 class="fw-bold text-dark mb-1">Meals & Medical Care for Elders Home</h6>
                  <p class="small text-muted mb-2">Provided full-day meals and medical supplies to Suwasevana Elders Home on the 3rd-month remembrance.</p>
                  <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="small text-muted">Spent Amount:</span>
                    <span class="fw-bold text-dark">$1,200.00</span>
                  </div>
                </div>
              </div>
            </div> -->

          <!-- Impact Section Row: Cards 2 ක් එකම row එකේ -->
<div class="row g-4">
  
  <!-- Impact Item 1 -->
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm overflow-hidden impact-card-modern">
      <div class="impact-img-wrapper">
        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?fit=crop&w=500&h=250" class="card-img-top impact-img" alt="School Book Donation">
        <span class="badge bg-success position-absolute top-0 end-0 m-3 shadow-sm px-3 py-1 rounded-pill">Completed</span>
      </div>
      
      <div class="card-body p-4 d-flex flex-column justify-content-between">
        <div>
          <h5 class="fw-bold text-dark mb-2">Scholarship Books & Stationery</h5>
          <p class="text-muted-bold mb-3">Distributed essential stationery packages and school bags to 50 underprivileged students in the village.</p>
        </div>

        <div>
          <!-- Spent Amount Section -->
          <div class="d-flex justify-content-between align-items-center py-2 px-3 bg-light rounded mb-3 border">
            <span class="small text-secondary fw-semibold">Spent Amount:</span>
            <span class="fw-bold text-dark fs-6">$850.00</span>
          </div>
          
          <!-- Buttons Group (Proceed & View More) -->
          <div class="d-flex flex-column gap-2">
            <a href="#contributeModal" class="btn btn-memorial-submit text-white py-2 fw-semibold rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
              <i class="bi bi-credit-card"></i> Proceed to Contribute
            </a>
            <a href="#" class="btn btn-outline-memorial py-2 fw-semibold rounded-pill d-flex align-items-center justify-content-center gap-2">
              <span>View More</span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Impact Item 2 (Duplicate / Second Card for the Row) -->
  <div class="col-md-6">
    <div class="card h-100 border-0 shadow-sm overflow-hidden impact-card-modern">
      <div class="impact-img-wrapper">
        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?fit=crop&w=500&h=250" class="card-img-top impact-img" alt="Community Meal Program">
        <span class="badge bg-success position-absolute top-0 end-0 m-3 shadow-sm px-3 py-1 rounded-pill">Completed</span>
      </div>
      
      <div class="card-body p-4 d-flex flex-column justify-content-between">
        <div>
          <h5 class="fw-bold text-dark mb-2">Community Alms & Meal Program</h5>
          <p class="text-muted-bold mb-3">Organized a community meal distribution program benefiting over 120 elderly and low-income families.</p>
        </div>

        <div>
          <!-- Spent Amount Section -->
          <div class="d-flex justify-content-between align-items-center py-2 px-3 bg-light rounded mb-3 border">
            <span class="small text-secondary fw-semibold">Spent Amount:</span>
            <span class="fw-bold text-dark fs-6">$1,200.00</span>
          </div>
          
          <!-- Buttons Group (Proceed & View More) -->
          <div class="d-flex flex-column gap-2">
            <a href="#contributeModal" class="btn btn-memorial-submit text-white py-2 fw-semibold rounded-pill d-flex align-items-center justify-content-center gap-2 shadow-sm">
              <i class="bi bi-credit-card"></i> Proceed to Contribute
            </a>
            <a href="#" class="btn btn-outline-memorial py-2 fw-semibold rounded-pill d-flex align-items-center justify-content-center gap-2">
              <span>View More</span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Modern Pagination Section -->
<div class="row mt-4">
  <div class="col-12">
    <nav aria-label="Impact items navigation">
      <ul class="pagination justify-content-center custom-pagination">
        <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i> Previous</a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Next">Next <i class="bi bi-chevron-right"></i></a>
        </li>
      </ul>
    </nav>
  </div>
</div>


          </div>
        </div>

     

<!-- Donor Wall / Contributors List -->
<div class="main-content-card p-4 rounded-3 shadow-sm bg-memorial-card donor-wall-card animate-donor-section">

  <!-- Header Title Section -->
  <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div class="d-flex align-items-center gap-2">
      <div class="icon-circle bg-memorial-accent-light text-memorial-accent p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #fef3c7; color: #854d0e;">
        <i class="bi bi-heart-fill fs-5"></i>
      </div>
      <div>
        <h5 class="fw-bold text-dark mb-0">Honored Contributors</h5>
        <small class="text-muted">Thank you for your generous support</small>
      </div>
    </div>
    <span class="badge rounded-pill bg-memorial-accent px-3 py-2 fw-semibold shadow-sm" style="background-color: #854d0e; color: #fff;">10 Donors</span>
  </div>

  <!-- Modern Responsive Cards Grid -->
  <div class="donor-grid-container">
    
    <!-- Donor Card 1 -->
    <div class="modern-donor-card p-3 rounded-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="d-flex align-items-center gap-3">
          <span class="donor-avatar">JR</span>
          <div>
            <h6 class="fw-bold text-dark mb-0">James Reynolds</h6>
            <span class="text-muted tiny-date"><i class="bi bi-calendar3 me-1"></i> Aug 10, 2026</span>
          </div>
        </div>
        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1.5 fs-6">$200.00</span>
      </div>
      <p class="fst-italic text-secondary small mb-0 ps-2 border-start border-3 border-warning">"May his soul rest in eternal peace."</p>
    </div>

    <!-- Donor Card 2 -->
    <div class="modern-donor-card p-3 rounded-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="d-flex align-items-center gap-3">
          <span class="donor-avatar">AG</span>
          <div>
            <h6 class="fw-bold text-dark mb-0">Adam Green</h6>
            <span class="text-muted tiny-date"><i class="bi bi-calendar3 me-1"></i> Aug 08, 2026</span>
          </div>
        </div>
        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1.5 fs-6">$150.00</span>
      </div>
      <p class="fst-italic text-secondary small mb-0 ps-2 border-start border-3 border-warning">"In memory of a great human being."</p>
    </div>

    <!-- Donor Card 3 -->
    <div class="modern-donor-card p-3 rounded-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="d-flex align-items-center gap-3">
          <span class="donor-avatar">DL</span>
          <div>
            <h6 class="fw-bold text-dark mb-0">Darlene Leonardo</h6>
            <span class="text-muted tiny-date"><i class="bi bi-calendar3 me-1"></i> Jul 28, 2026</span>
          </div>
        </div>
        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1.5 fs-6">$300.00</span>
      </div>
      <p class="fst-italic text-secondary small mb-0 ps-2 border-start border-3 border-warning">"Deepest sympathies to the family."</p>
    </div>

    <!-- Donor Card 4 -->
    <div class="modern-donor-card p-3 rounded-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="d-flex align-items-center gap-3">
          <span class="donor-avatar">PF</span>
          <div>
            <h6 class="fw-bold text-dark mb-0">Perera Family</h6>
            <span class="text-muted tiny-date"><i class="bi bi-calendar3 me-1"></i> Jul 15, 2026</span>
          </div>
        </div>
        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1.5 fs-6">$500.00</span>
      </div>
      <p class="fst-italic text-secondary small mb-0 ps-2 border-start border-3 border-warning">"නිවන් සුව ලැබේවා!"</p>
    </div>

    <!-- Donor Card 5 -->
    <div class="modern-donor-card p-3 rounded-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="d-flex align-items-center gap-3">
          <span class="donor-avatar bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center"><i class="bi bi-person-fill"></i></span>
          <div>
            <h6 class="fw-bold text-dark mb-0">Anonymous Donor</h6>
            <span class="text-muted tiny-date"><i class="bi bi-calendar3 me-1"></i> Jul 02, 2026</span>
          </div>
        </div>
        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1.5 fs-6">$100.00</span>
      </div>
      <p class="text-muted small mb-0 ps-2 border-start border-3 border-secondary">-</p>
    </div>

  </div>
</div>
      </div>

      <!-- Right Column: Donation Overview & Quick Share Sidebar -->
      <div class="col-lg-4 d-flex flex-column gap-3">

        <!-- Donation Summary Widget Card -->
        <div class="main-content-card p-3 rounded shadow-sm bg-memorial-card">
          <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Donation Campaign Summary</h6>
          
          <div class="text-center py-2 bg-white rounded border mb-3">
            <span class="small fw-semibold  d-block">Total Funds Raised</span>
            <span class="fs-2 fw-bold text-memorial-accent">$3,375.00</span>
            <span class="small fw-semibold  text-muted d-block mt-1">Goal: $5,000.00</span>
          </div>

          <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar bg-memorial-accent" style="width: 68%;"></div>
          </div>

          <div class="d-flex justify-content-between small fw-semibold  border-top pt-2">
            <span>Total Donors: <strong>10</strong></span>
            <span>Spent: <strong>$2,050.00</strong></span>
          </div>
        </div>

        <!-- Share Options Card -->
        <div class="main-content-card  p-3 rounded shadow-sm">
          <h6 class="fw-bold mb-2 text-dark">Share Donation Campaign</h6>
          <p class="small small fw-semibold mb-3">Help us reach the goal by sharing this page with friends and family.</p>
          
          <div class="d-flex gap-2">
            <button class="btn btn-facebook flex-fill py-2 d-flex align-items-center justify-content-center gap-1" title="Share on Facebook">
              <i class="bi bi-facebook fs-5"></i>
            </button>
            <button class="btn btn-whatsapp flex-fill py-2 d-flex align-items-center justify-content-center gap-1" title="Share on WhatsApp">
              <i class="bi bi-whatsapp fs-5"></i>
            </button>
            <button class="btn btn-outline-secondary flex-fill py-2 d-flex align-items-center justify-content-center gap-1" onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!');" title="Copy Link">
              <i class="bi bi-link-45deg fs-5"></i>
            </button>
          </div>
        </div>

        <!-- Transparency & Contact Card -->
        <div class="main-content-card p-3 rounded shadow-sm">
          <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-check text-success me-1"></i> Transparent & Verified</h6>
          <p class="small fw-semibold  mb-0">
            All receipts and bank transfers are managed and audited directly by the family administrator (Connor Graham).
          </p>
        </div>

        <!-- Page Views Info Panel -->
        <div class="main-content-card  p-3 rounded shadow-sm">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-eye fs-5 text-dark"></i>
            <h6 class="fw-bold mb-0 text-dark">184 Page Views</h6>
          </div>
          <div class="d-flex align-items-center gap-2 small fw-semibold  pt-2 border-top border-secondary-subtle">
            <i class="bi bi-globe"></i>
            <span>Open public access</span>
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

  <!-- JavaScript Files -->
  <script src="{{ asset('memo_website_dev_frontend/assets/js/main.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



  <script>
    document.addEventListener("DOMContentLoaded", function () {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    const donorSection = document.querySelector('.animate-donor-section');
    if (donorSection) {
        observer.observe(donorSection);
    }
});
  </script>

</body>
</html>