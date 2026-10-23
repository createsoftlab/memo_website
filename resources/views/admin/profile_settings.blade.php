<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile & Security Settings - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>

    @include('admin.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        @include('admin.header')
    
        <!-- Profile Settings Content -->
        <div class="container-fluid p-4">

            <!-- Section Header -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-12">
                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-person-gear text-warning me-2"></i> Account & Security Settings
                    </h4>
                    <p class="text-muted small m-0">Manage your personal profile details, geographic location, and change your account password.</p>
                </div>
            </div>

            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('password_success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-shield-check me-2"></i> {{ session('password_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please check the required fields:</h6>
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">

                <!-- ==============================
                     BASIC PROFILE INFORMATION
                =============================== -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">

                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h5 class="font-serif text-brown fw-bold m-0">
                                    <i class="bi bi-person-circle me-2 text-warning"></i>
                                    Personal Profile
                                </h5>
                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">
                                    <i class="bi bi-check2-circle me-1"></i> Active
                                </span>
                            </div>

                            <p class="text-muted small mb-4">
                                Update your display name, country of residence, and state.
                            </p>

                            <!-- Profile Avatar Header -->
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4 border">
                                <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 55px; height: 55px; font-size: 1.25rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark m-0">{{ auth()->user()->name }}</h6>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                    <div>
                                        <span class="badge bg-secondary-subtle text-dark border small mt-1">
                                            {{ auth()->user()->roles->first()?->name ?? auth()->user()->role ?? 'User' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Info Form -->
                            <form method="POST" action="{{ route('admin.profile.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email (Disabled / Readonly) -->
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted">Email Address (Primary Identity)</label>
                                    <input type="email" class="form-control bg-light" value="{{ auth()->user()->email }}" disabled readonly>
                                    <div class="form-text small text-muted">Email changes must be requested through system administration.</div>
                                </div>

                                <div class="row g-2 mb-4">
                                    <!-- Country -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-muted">Country</label>
                                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                                            placeholder="e.g., United States" value="{{ old('country', auth()->user()->country) }}">
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- State -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-muted">State / Region</label>
                                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                                            placeholder="e.g., California" value="{{ old('state', auth()->user()->state) }}">
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning fw-bold text-dark py-2 shadow-sm">
                                        <i class="bi bi-save me-1"></i> Save Profile Settings
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- ==============================
                     PASSWORD MANAGEMENT
                =============================== -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">

                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h5 class="font-serif text-brown fw-bold m-0">
                                    <i class="bi bi-shield-lock me-2 text-warning"></i>
                                    Password Management
                                </h5>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    <i class="bi bi-lock me-1"></i> Secure
                                </span>
                            </div>

                            <p class="text-muted small mb-4">
                                Update your password regularly. Verification of your current password is required.
                            </p>

                            <!-- Password Update Form -->
                            <form method="POST" action="{{ route('admin.profile.password') }}">
                                @csrf
                                @method('PUT')

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label small fw-semibold text-muted">
                                        Current Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="currentPassword" placeholder="Enter current password" required>
                                        <button type="button" class="btn btn-outline-secondary password-toggle"
                                            data-target="currentPassword" title="Show / Hide Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label small fw-semibold text-muted">
                                        New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="newPassword" placeholder="Enter new strong password" required>
                                        <button type="button" class="btn btn-outline-secondary password-toggle"
                                            data-target="newPassword" title="Show / Hide Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label small fw-semibold text-muted">
                                        Confirm New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation"
                                            class="form-control" id="confirmPassword"
                                            placeholder="Confirm new password" required>
                                        <button type="button" class="btn btn-outline-secondary password-toggle"
                                            data-target="confirmPassword" title="Show / Hide Password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Password Requirement -->
                                <div class="bg-light border rounded p-3 mb-4">
                                    <div class="small fw-semibold text-brown mb-2">
                                        Security policy requirements:
                                    </div>
                                    <ul class="small text-muted mb-0 ps-3">
                                        <li>Minimum 8 characters in length</li>
                                        <li>Must match the confirmation field exactly</li>
                                        <li>Verified against your current password</li>
                                    </ul>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-dark fw-bold py-2 shadow-sm">
                                        <i class="bi bi-key me-2"></i> Update Password Securely
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Footer -->
        <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary mt-5">
            <div class="container">
                <div class="row g-4 mb-4">
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-gold fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                        <p class="small text-light-tan leading-relaxed">
                            A dignified digital platform dedicated to preserving legacies, connecting families, and honoring cherished memories forever.
                        </p>
                    </div>
                </div>
                <div class="border-top border-secondary pt-3 text-center small text-light-tan">
                    &copy; {{ date('Y') }} Memorial Portal. All rights reserved.
                </div>
            </div>
        </footer>

    </div>

    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>
</body>
</html>