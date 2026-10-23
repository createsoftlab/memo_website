<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Directory & RBAC - Memorial Dashboard</title>
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
            
            <!-- Section Header & Action -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-xl-8 col-lg-7 col-12">
                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-people-fill text-warning me-2"></i> Staff Directory & RBAC
                    </h4>
                    <p class="text-muted small m-0">Provision, monitor, and regulate platform administrators and operational employees.</p>
                </div>
                <div class="col-xl-4 col-lg-5 col-12 text-lg-end">
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-warning text-dark fw-bold px-3 py-2 shadow-sm rounded-3">
                        <i class="bi bi-person-plus-fill me-1"></i> Provision New Staff
                    </a>
                </div>
            </div>

            <!-- Credentials Alert Banner (if newly created or reset) -->
            @if(session('temporary_credentials'))
                @php $creds = session('temporary_credentials'); @endphp
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="p-2 rounded-circle bg-warning text-dark">
                                    <i class="bi bi-shield-lock-fill fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-gold mb-1">Temporary Staff Credentials Generated</h5>
                                    <p class="text-light small mb-2">
                                        These credentials have been dispatched via queued notification to the user's email. You may also securely copy them now:
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge bg-secondary p-2">Email: <strong class="text-white">{{ $creds['email'] }}</strong></span>
                                        <span class="badge bg-warning text-dark p-2 font-monospace">Password: <strong>{{ $creds['password'] }}</strong></span>
                                        <span class="badge bg-info text-dark p-2">Role: {{ $creds['role'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="copyPasswordText('{{ $creds['password'] }}')">
                                <i class="bi bi-clipboard me-1"></i> Copy Password
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Standard Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Staff Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-primary-subtle text-primary rounded-4">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Total Staff</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['total'] ?? $employees->total() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-info-subtle text-info rounded-4">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Administrators</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['admins'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-warning-subtle text-warning rounded-4">
                                <i class="bi bi-person-workspace fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Employees</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['employees'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-success-subtle text-success rounded-4">
                                <i class="bi bi-check-circle-fill fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Active Status</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['active'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar Container -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 mb-4">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-2 align-items-center">
                    <!-- Search Field -->
                    <div class="col-lg-5 col-md-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                placeholder="Search by name or email..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-lg-3 col-md-4 col-6">
                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="Super Admin" {{ request('role') === 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Employee" {{ request('role') === 'Employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-lg-3 col-md-4 col-6">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>

                    <!-- Reset & Submit Buttons -->
                    <div class="col-lg-1 col-md-4 col-12 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-dark w-100"><i class="bi bi-funnel"></i></button>
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary" title="Clear Filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Staff Listing Table Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th class="ps-4">Staff Member</th>
                                <th>Role</th>
                                <th>Assigned Permissions</th>
                                <th>Account Status</th>
                                <th>Joined</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($employees as $emp)
                                <tr>
                                    <!-- User Avatar & Info -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-warning-subtle text-dark fw-bold d-flex align-items-center justify-content-center"
                                                style="width: 42px; height: 42px; font-size: 0.95rem;">
                                                {{ strtoupper(substr($emp->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $emp->name }}</div>
                                                <div class="text-muted small">{{ $emp->email }}</div>
                                                @if($emp->country || $emp->state)
                                                    <span class="text-muted" style="font-size: 0.75rem;">
                                                        <i class="bi bi-geo-alt"></i> {{ implode(', ', array_filter([$emp->state, $emp->country])) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role -->
                                    <td>
                                        @php $roleName = $emp->roles->first()?->name ?? 'Staff'; @endphp
                                        @if($roleName === 'Super Admin')
                                            <span class="badge bg-purple text-white px-3 py-2 rounded-pill shadow-sm" style="background-color: #6f42c1;">
                                                <i class="bi bi-stars me-1"></i> Super Admin
                                            </span>
                                        @elseif($roleName === 'Admin')
                                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
                                                <i class="bi bi-shield-lock me-1"></i> Admin
                                            </span>
                                        @else
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">
                                                <i class="bi bi-person me-1"></i> Employee
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Permissions -->
                                    <td>
                                        @php
                                            $directCount = $emp->permissions->count();
                                            $totalPerms = $emp->getAllPermissions()->count();
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                            <span class="badge bg-light text-dark border">
                                                {{ $totalPerms }} Total Capabilities
                                            </span>
                                            @if($directCount > 0)
                                                <span class="badge bg-warning-subtle text-dark border">
                                                    +{{ $directCount }} Custom Direct
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if($emp->isActive())
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i> Active
                                            </span>
                                        @elseif($emp->isSuspended())
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                <i class="bi bi-pause-circle-fill me-1"></i> Suspended
                                            </span>
                                        @elseif($emp->isBanned())
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-x-circle-fill me-1"></i> Banned
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Joined Date -->
                                    <td class="text-muted">
                                        {{ $emp->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2">
                                                <li>
                                                    <a class="dropdown-item py-1" href="{{ route('admin.employees.edit', $emp->id) }}">
                                                        <i class="bi bi-pencil me-2 text-primary"></i> Edit Privileges
                                                    </a>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.employees.resend_credentials', $emp->id) }}"
                                                        onsubmit="return confirm('Generate and dispatch new temporary credentials to {{ $emp->email }}?');">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-1">
                                                            <i class="bi bi-send me-2 text-warning"></i> Reset & Email Password
                                                        </button>
                                                    </form>
                                                </li>
                                                @if($emp->id !== auth()->id() && !$emp->hasRole('Super Admin'))
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}"
                                                            onsubmit="return confirm('Are you sure you want to permanently revoke and delete this staff account?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item py-1 text-danger">
                                                                <i class="bi bi-trash me-2"></i> Revoke Account
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-6 d-block mb-2 text-muted"></i>
                                        No staff accounts found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($employees->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top">
                        {{ $employees->links('pagination::bootstrap-5') }}
                    </div>
                @endif
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
        function copyPasswordText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Temporary password copied to clipboard!');
            });
        }
    </script>
</body>
</html>