<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provision Staff - Memorial Dashboard</title>
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

        <!-- Page Body -->
        <div class="container-fluid py-4 overflow-hidden">

            <!-- Section Header -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-xl-8 col-lg-8 col-12">
                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-person-plus text-warning me-2"></i> Register New Employee / Sub-Admin
                    </h4>
                    <p class="text-muted small m-0">Provision new staff accounts with auto-generated credentials and assign granular module permissions.</p>
                </div>
                <div class="col-xl-4 col-lg-4 col-12 text-lg-end">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm rounded-3">
                        <i class="bi bi-arrow-left me-1"></i> Back to Staff Directory
                    </a>
                </div>
            </div>

            <!-- Alerts Container -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the errors below:</h6>
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Add Employee Form Card -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11 col-12">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                        <form method="POST" action="{{ route('admin.employees.store') }}" id="staffCreateForm">
                            @csrf

                            <!-- Basic Info Section -->
                            <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-info-circle me-2 text-warning"></i> 1. Basic Staff Information
                            </h6>

                            <div class="row g-3 mb-4">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                        placeholder="e.g., Jennifer Aniston" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Work Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                                        placeholder="staff@memorialportal.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role Selection -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Assigned Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror" required id="roleSelector">
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Choose Staff Role...</option>
                                        <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin (Elevated Permissions)</option>
                                        <option value="Employee" {{ old('role') === 'Employee' || !old('role') ? 'selected' : '' }}>Employee (Operational Staff)</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Country</label>
                                    <input type="text" name="country" class="form-control rounded-3 @error('country') is-invalid @enderror"
                                        placeholder="e.g., United States" value="{{ old('country') }}">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- State / Region -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">State / Province</label>
                                    <input type="text" name="state" class="form-control rounded-3 @error('state') is-invalid @enderror"
                                        placeholder="e.g., California" value="{{ old('state') }}">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Automated Credentials Section -->
                            <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-shield-lock me-2 text-warning"></i> 2. Automated Credential Dispatch
                            </h6>

                            <div class="p-3 bg-light rounded-3 mb-4 border">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="p-2 rounded-circle bg-warning-subtle text-warning">
                                        <i class="bi bi-key-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Automatic Secure Password Generation</h6>
                                        <p class="text-muted small mb-1">
                                            A secure 12-character temporary password will be automatically generated upon submission.
                                            A queued notification (<span class="badge bg-secondary-subtle text-dark">StaffCredentialsNotification</span>)
                                            will immediately email the credentials and dashboard login URL to the new staff member.
                                        </p>
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-envelope-check me-1"></i> Auto-Email Enabled
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Granular Module Permissions Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h6 class="fw-bold text-brown m-0 d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap me-2 text-warning"></i> 3. Granular Module Permissions
                                </h6>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllPerms">
                                        <i class="bi bi-check-all me-1"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllPerms">
                                        Clear All
                                    </button>
                                </div>
                            </div>

                            <p class="text-muted small mb-4">
                                Assign custom granular permissions to extend or fine-tune this account's capabilities beyond their default role assignment.
                            </p>

                            <div class="row g-3 mb-4">
                                @forelse($permissions as $category => $categoryPermissions)
                                    <div class="col-lg-6 col-12">
                                        <div class="card h-100 border shadow-none rounded-3">
                                            <div class="card-header bg-light py-2 px-3 fw-bold small text-dark d-flex justify-content-between align-items-center">
                                                <span>{{ $category }}</span>
                                                <span class="badge bg-white text-muted border">{{ count($categoryPermissions) }}</span>
                                            </div>
                                            <div class="card-body p-3">
                                                @foreach($categoryPermissions as $perm)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input perm-checkbox" type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $perm->name }}"
                                                            id="perm_{{ $perm->id }}"
                                                            {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="perm_{{ $perm->id }}">
                                                            <strong class="text-dark">{{ $perm->name }}</strong>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info small">No custom permissions found in system. Run seeders to populate.</div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Form Submit Buttons -->
                            <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                                <a href="{{ route('admin.employees.index') }}" class="btn btn-light border px-4">Cancel</a>
                                <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                                    <i class="bi bi-person-check me-1"></i> Provision Staff Member
                                </button>
                            </div>

                        </form>
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
                    <div class="col-lg-2 col-md-6">
                        <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                        <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                            <li><a href="{{ url('admin/dashboard') }}" class="text-light-tan text-decoration-none">Dashboard</a></li>
                            <li><a href="{{ route('admin.employees.index') }}" class="text-light-tan text-decoration-none">Staff Directory</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-top border-secondary pt-3 text-center small text-light-tan">
                    &copy; {{ date('Y') }} Memorial Portal. All rights reserved.
                </div>
            </div>
        </footer>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('selectAllPerms')?.addEventListener('click', function() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = true);
        });
        document.getElementById('deselectAllPerms')?.addEventListener('click', function() {
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
        });
    </script>
</body>
</html>
