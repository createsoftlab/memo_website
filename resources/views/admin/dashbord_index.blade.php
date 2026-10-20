<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>

    @include('admin.sidebar')

    <!-- Main Content -->
    <div class="main-content">
    <!-- Top Navbar -->
    @include('admin.header')

  

            <!-- Dashboard Body -->
            <div class="container-fluid p-4">
    
    <!-- Welcome Banner with Robot -->
    <div class="welcome-banner mb-4 d-flex align-items-center gap-3 p-3 rounded-4 shadow-sm">
        <!-- <div class="robot-avatar">
            <img src="https://cdn-icons-png.flaticon.com/512/4712/4712109.png" alt="Robot" width="60">
        </div> -->
        <div class="speech-bubble px-4 py-2 bg-white rounded-pill shadow-sm">
            <h6 class="m-0 fw-bold text-dark font-serif" style="font-size: 0.95rem;">WELCOME BACK, USER NAME...</h6>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side Main Area -->
        <div class="col-lg-8">
            
            <!-- Top Stat Cards (3 Cards) -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card p-3 rounded-4 position-relative overflow-hidden text-white shadow-sm" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400') center/cover;">
                        <small class="text-uppercase tracking-wider fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">NEW USERS</small>
                        <h3 class="fw-bold my-1 font-serif">All Listings</h3>
                        <h2 class="display-6 fw-bold mb-1">04</h2>
                        <small class="text-white-50" style="font-size: 0.75rem;">Total number of listings</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card p-3 rounded-4 position-relative overflow-hidden text-white shadow-sm" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=400') center/cover;">
                        <small class="text-uppercase tracking-wider fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">ENQUIRIES</small>
                        <h3 class="fw-bold my-1 font-serif">Enquiries</h3>
                        <h2 class="display-6 fw-bold mb-1">03</h2>
                        <small class="text-white-50" style="font-size: 0.75rem;">Total number of Enquiry</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card p-3 rounded-4 position-relative overflow-hidden text-white shadow-sm" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1577495508048-b635879837f1?w=400') center/cover;">
                        <small class="text-uppercase tracking-wider fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Events </small>
                        <h3 class="fw-bold my-1 font-serif">Evennts</h3>
                        <h2 class="display-6 fw-bold mb-1">38</h2>
                        <small class="text-white-50" style="font-size: 0.75rem;">Total number of followings</small>
                    </div>
                </div>
            </div>

<!-- Grid Profile Cards (2x2 Grid) -->
<div class="row g-4">
    <!-- Profile Card 1 -->
    <div class="col-md-6">
        <div class="custom-profile-card rounded-4 overflow-hidden shadow-sm border">
            <div class="card-banner banner-1 p-3 text-white">
                <div class="avatar-title-wrapper d-flex align-items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100" class="rounded-circle border border-2 border-white shadow-sm" width="50" height="50" alt="Avatar">
                    <h5 class="m-0 fw-bold text-white font-serif">Profile page</h5>
                </div>
            </div>
            <div class="p-3 pt-4 bg-white">
                <button class="btn btn-sm px-4 fw-bold rounded-pill btn-theme-gold">START NOW</button>
            </div>
        </div>
    </div>

    <!-- Profile Card 2 -->
    <div class="col-md-6">
        <div class="custom-profile-card rounded-4 overflow-hidden shadow-sm border">
            <div class="card-banner banner-2 p-3 text-white">
                <div class="avatar-title-wrapper d-flex align-items-center gap-3">
<img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100" class="profile-avatar" alt="Avatar">
                    <h5 class="m-0 fw-bold text-white font-serif">Event  page</h5>
                </div>
            </div>
            <div class="p-3 pt-4 bg-white">
               <button class="btn btn-sm px-4 fw-bold rounded-pill btn-theme-gold">START NOW</button>
            </div>
        </div>
    </div>

    <!-- Profile Card 3 -->
    <div class="col-md-6">
        <div class="custom-profile-card rounded-4 overflow-hidden shadow-sm border">
            <div class="card-banner banner-3 p-3 text-white">
                <div class="avatar-title-wrapper d-flex align-items-center gap-3">
                 <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100" class="profile-avatar" alt="Avatar">
                    <h5 class="m-0 fw-bold text-white font-serif" style="font-size: 0.95rem;">package/ Price  </h5>
                </div>
            </div>
            <div class="p-3 pt-4 bg-white">
               <button class="btn btn-sm px-4 fw-bold rounded-pill btn-theme-gold">START NOW</button>
            </div>
        </div>
    </div>

    <!-- Profile Card 4 -->
    <div class="col-md-6">
        <div class="custom-profile-card rounded-4 overflow-hidden shadow-sm border">
            <div class="card-banner banner-4 p-3 text-white">
                <div class="avatar-title-wrapper d-flex align-items-center gap-3">
        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100" class="profile-avatar" alt="Avatar">
                    <h5 class="m-0 fw-bold text-white font-serif">Settings</h5>
                </div>
            </div>
            <div class="p-3 pt-4 bg-white">
               <button class="btn btn-sm px-4 fw-bold rounded-pill btn-theme-gold">START NOW</button>
            </div>
        </div>
    </div>
</div>

        </div>

        <!-- Right Side Widgets Area -->
        <div class="col-lg-4">
            
            <!-- Promote My Business Card -->
            <div class="promote-card p-4 rounded-4 text-white position-relative overflow-hidden shadow-sm mb-4" style="background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=400') center/cover;">
                <h4 class="fw-bold font-serif mb-2"> Create Memories </h4>
                <p class="small text-white-50 mb-3" style="font-size: 0.8rem;">Your listing show at the top of the recommended category page</p>
                <button class="btn btn-warning btn-sm px-4 fw-bold rounded-pill text-dark" style="background-color: #ffb700; border: none;">START NOW</button>
            </div>

          
            <!-- Community Members Card -->
            <div class="community-card p-4 rounded-4 text-white position-relative overflow-hidden shadow-sm" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1477959858617-67f30bc75b82?w=400') center/cover;">
                <h4 class="fw-bold font-serif mb-2">Support </h4>
                <p class="small text-white-50 mb-3" style="font-size: 0.8rem;">Follow your favorite business coach and grow your status in grow network.</p>
                <button class="btn btn-warning btn-sm px-4 fw-bold rounded-pill text-dark" style="background-color: #ffb700; border: none;">Contact Us</button>
            </div>
        </div>
        <!-- Footer -->
<footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-4 col-md-6">
        <h5 class="text-gold fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
        <p class="small text-light-tan leading-relaxed">
          A dignified digital platform dedicated to preserving legacies, connecting families, and honoring cherished memories forever.
        </p>
      </div>

      <div class="col-lg-2 col-md-6">
        <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
        <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
          <li><a href="{{ url('admin/index') }}" class="text-light-tan text-decoration-none">Home</a></li>
          <li><a href="{{ url('admin/all_memories') }}" class="text-light-tan text-decoration-none">Memories</a></li>
          <li><a href="{{ url('admin/about') }}" class="text-light-tan text-decoration-none">About Us</a></li>
          <li><a href="{{ url('admin/contact') }}" class="text-light-tan text-decoration-none">Contact Us</a></li>
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
          <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i class="bi bi-facebook"></i></a>
          <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
    </div>

    <hr class="border-secondary">

    <div class="text-center small text-white-50 pt-2">
      <p class="mb-0">&copy; 2026 Forever Missed Memorial Directory. All rights reserved.</p>
    </div>
  </div>
</footer>
        
    </div>
</div>
</div>



</div>

    

    <!-- Bootstrap JS and Responsive Toggle Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    </script>
</body>
</html>