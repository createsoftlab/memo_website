<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Member - Memorial Dashboard</title>
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
                        <i class="bi bi-pencil-square text-warning me-2"></i> Edit Staff Account: {{ $employee->name }}
                    </h4>
                    <p class="text-muted small m-0">Modify role privileges, adjust granular permissions, or update staff state.</p>
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

            <!-- Edit Employee Form Card -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11 col-12">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                        <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Basic Info Section -->
                            <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom d-flex align-items-center">
                                <i class="bi bi-info-circle me-2 text-warning"></i> 1. Staff Profile & Account Status
                            </h6>

                            <div class="row g-3 mb-4">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                                        value="{{ old('name', $employee->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Work Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror"
                                        value="{{ old('email', $employee->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role Selection -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Assigned Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role', $employee->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Account Status -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Account Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select rounded-3 @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                                        <option value="suspended" {{ old('status', $employee->status) === 'suspended' ? 'selected' : '' }}>Suspended (Temporary Freeze)</option>
                                        <option value="banned" {{ old('status', $employee->status) === 'banned' ? 'selected' : '' }}>Banned (Permanent Revocation)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Country</label>
                                    <input type="text" name="country" class="form-control rounded-3"
                                        value="{{ old('country', $employee->country) }}">
                                </div>

                                <!-- State -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">State / Region</label>
                                    <input type="text" name="state" class="form-control rounded-3"
                                        value="{{ old('state', $employee->state) }}">
                                </div>
                            </div>

                            <!-- Granular Module Permissions Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <h6 class="fw-bold text-brown m-0 d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap me-2 text-warning"></i> 2. Granular Module Permissions
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
                                Checkboxes with an <span class="badge bg-secondary-subtle text-dark">inherited</span> tag indicate permissions granted automatically by their primary role. You can assign direct overrides below:
                            </p>

                            <div class="row g-3 mb-4">
                                @foreach($permissions as $category => $categoryPermissions)
                                    <div class="col-lg-6 col-12">
                                        <div class="card h-100 border shadow-none rounded-3">
                                            <div class="card-header bg-light py-2 px-3 fw-bold small text-dark d-flex justify-content-between align-items-center">
                                                <span>{{ $category }}</span>
                                                <span class="badge bg-white text-muted border">{{ count($categoryPermissions) }}</span>
                                            </div>
                                            <div class="card-body p-3">
                                                @foreach($categoryPermissions as $perm)
                                                    @php
                                                        $isDirect = in_array($perm->name, old('permissions', $assignedPermissions), true);
                                                        $isInherited = in_array($perm->name, $rolePermissions, true);
                                                    @endphp
                                                    <div class="form-check mb-2 d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <input class="form-check-input perm-checkbox" type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $perm->name }}"
                                                                id="perm_{{ $perm->id }}"
                                                                {{ ($isDirect || $isInherited) ? 'checked' : '' }}>
                                                            <label class="form-check-label small ms-1" for="perm_{{ $perm->id }}">
                                                                <strong>{{ $perm->name }}</strong>
                                                            </label>
                                                        </div>
                                                        @if($isInherited)
                                                            <span class="badge bg-info-subtle text-info border small" style="font-size: 0.65rem;">
                                                                role-granted
                                                            </span>
                                                        @elseif($isDirect)
                                                            <span class="badge bg-primary-subtle text-primary border small" style="font-size: 0.65rem;">
                                                                direct
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Form Submit Buttons -->
                            <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                                <a href="{{ route('admin.employees.index') }}" class="btn btn-light border px-4">Cancel</a>
                                <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Update Staff Account
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