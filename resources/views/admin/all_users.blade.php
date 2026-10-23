<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management & Moderation - Memorial Dashboard</title>
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
            
            <!-- Section Header & Filter Bar Container -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-xl-6 col-lg-6 col-12">
                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-people-fill text-warning me-2"></i> User Management & Moderation
                    </h4>
                    <p class="text-muted small m-0">Monitor registered accounts, regulate user status, and manage memorial creator designations.</p>
                </div>
                <div class="col-xl-6 col-lg-6 col-12 text-lg-end">
                    <span class="badge bg-white text-dark shadow-sm border p-2 me-2">
                        <i class="bi bi-shield-check text-success me-1"></i> Middleware Protection Active
                    </span>
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-sm btn-outline-dark shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Add Staff Account
                    </a>
                </div>
            </div>

            <!-- Flash Notifications -->
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

            <!-- KPI Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-primary-subtle text-primary rounded-4">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Total Registered</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-warning-subtle text-warning rounded-4">
                                <i class="bi bi-award fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Memory Creators</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['memory_creators'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-success-subtle text-success rounded-4">
                                <i class="bi bi-check2-circle fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Active Accounts</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['active'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-danger-subtle text-danger rounded-4">
                                <i class="bi bi-slash-circle fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-semibold d-block">Suspended / Banned</small>
                                <h4 class="fw-bold text-dark m-0">{{ $stats['suspended'] + $stats['banned'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Dedicated Views/Tabs -->
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-2 border-0">
                        <li class="nav-item">
                            <a class="nav-link py-3 px-4 fw-semibold {{ $tab !== 'memories' ? 'active text-warning border-bottom border-warning border-3' : 'text-muted' }}"
                                href="{{ route('admin.users.index', array_merge(request()->query(), ['tab' => 'all'])) }}">
                                <i class="bi bi-person-lines-fill me-2"></i> All Registered Users
                                <span class="badge bg-secondary-subtle text-dark ms-2 rounded-pill">{{ $stats['total'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 px-4 fw-semibold {{ $tab === 'memories' ? 'active text-warning border-bottom border-warning border-3' : 'text-muted' }}"
                                href="{{ route('admin.users.index', array_merge(request()->query(), ['tab' => 'memories'])) }}">
                                <i class="bi bi-flower1 me-2 text-warning"></i> Memory-Listed Users
                                <span class="badge bg-warning-subtle text-dark ms-2 rounded-pill">{{ $stats['memory_creators'] }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Query Filters & Search Bar -->
                <div class="card-body p-3 bg-light border-bottom">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
                        <input type="hidden" name="tab" value="{{ $tab }}">

                        <!-- Real-time / Form Search -->
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search name, email, or IP..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-xl-2 col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Filter Status (All)</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>
                        </div>

                        <!-- Country Filter -->
                        <div class="col-xl-2 col-lg-2 col-md-3 col-6">
                            <select name="country" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Filter Country (All)</option>
                                @foreach($filterOptions['countries'] as $c)
                                    <option value="{{ $c }}" {{ request('country') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State Filter -->
                        <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                            <select name="state" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Filter State (All)</option>
                                @foreach($filterOptions['states'] as $s)
                                    <option value="{{ $s }}" {{ request('state') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Role Filter -->
                        <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Filter Role (All)</option>
                                @foreach($filterOptions['roles'] as $r)
                                    <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Actions -->
                        <div class="col-xl-1 col-lg-2 col-md-4 col-12 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-dark w-100" title="Apply Filters">
                                <i class="bi bi-funnel"></i>
                            </button>
                            @if(request()->hasAny(['search', 'status', 'country', 'state', 'role']))
                                <a href="{{ route('admin.users.index', ['tab' => $tab]) }}" class="btn btn-sm btn-outline-secondary" title="Reset Filters">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-muted text-uppercase">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Location</th>
                                <th>Registration IP</th>
                                <th>Role</th>
                                <th>Memorial Profile</th>
                                <th>Account Status</th>
                                <th>Joined</th>
                                <th class="text-end pe-4">Moderation Actions</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($users as $user)
                                <tr>
                                    <!-- User Info -->
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-warning-subtle text-dark fw-bold d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Country & State -->
                                    <td>
                                        @if($user->country || $user->state)
                                            <span class="text-dark">
                                                <i class="bi bi-geo-alt text-muted me-1"></i>
                                                {{ implode(', ', array_filter([$user->state, $user->country])) }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic">Not specified</span>
                                        @endif
                                    </td>

                                    <!-- Registration IP -->
                                    <td>
                                        @if($user->registration_ip)
                                            <span class="badge bg-light text-dark border font-monospace">
                                                <i class="bi bi-hdd-network me-1 text-muted"></i> {{ $user->registration_ip }}
                                            </span>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>

                                    <!-- Assigned Role -->
                                    <td>
                                        @php $userRole = $user->roles->first()?->name ?? $user->role ?? 'General User'; @endphp
                                        @if($userRole === 'Super Admin')
                                            <span class="badge bg-purple text-white px-2 py-1 rounded-pill" style="background-color: #6f42c1;">Super Admin</span>
                                        @elseif($userRole === 'Admin')
                                            <span class="badge bg-primary text-white px-2 py-1 rounded-pill">Admin</span>
                                        @elseif($userRole === 'Employee')
                                            <span class="badge bg-info text-dark px-2 py-1 rounded-pill">Employee</span>
                                        @elseif($userRole === 'Memory Created User')
                                            <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Memory Creator</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-dark px-2 py-1 rounded-pill">General User</span>
                                        @endif
                                    </td>

                                    <!-- Memory-Listed Flag -->
                                    <td>
                                        @if($user->isMemoryCreator())
                                            <form method="POST" action="{{ route('admin.users.toggle_memory_creator', $user->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning text-dark border-0 p-0" title="Click to toggle memory creator designation">
                                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">
                                                        <i class="bi bi-star-fill me-1 text-danger"></i> Memorial Creator
                                                    </span>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.toggle_memory_creator', $user->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary border-0 p-0" title="Click to grant memory creator designation">
                                                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill">
                                                        Standard User
                                                    </span>
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                    <!-- Account Status -->
                                    <td>
                                        @if($user->isActive())
                                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i> Active
                                            </span>
                                        @elseif($user->isSuspended())
                                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                                <i class="bi bi-pause-circle-fill me-1"></i> Suspended
                                            </span>
                                        @elseif($user->isBanned())
                                            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-x-circle-fill me-1"></i> Banned
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Joined Date -->
                                    <td class="text-muted">
                                        {{ $user->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        @if($user->id !== 1 && !$user->hasRole('Super Admin') && $user->id !== auth()->id())
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Moderate
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2">
                                                    <!-- Reinstate / Activate -->
                                                    @if(!$user->isActive())
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="dropdown-item py-1 text-success">
                                                                    <i class="bi bi-check-circle me-2"></i> Reinstate / Activate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <!-- Suspend -->
                                                    @if(!$user->isSuspended())
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}"
                                                                onsubmit="return confirm('Suspend user {{ $user->name }}? They will be locked out from dashboard access.');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="suspended">
                                                                <button type="submit" class="dropdown-item py-1 text-warning">
                                                                    <i class="bi bi-pause-circle me-2"></i> Suspend Account
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <!-- Ban -->
                                                    @if(!$user->isBanned())
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}"
                                                                onsubmit="return confirm('Permanently ban user {{ $user->name }}?');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="banned">
                                                                <button type="submit" class="dropdown-item py-1 text-danger">
                                                                    <i class="bi bi-x-circle me-2"></i> Ban Account
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></li>

                                                    <!-- Toggle Memory Creator -->
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.toggle_memory_creator', $user->id) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item py-1">
                                                                <i class="bi bi-star me-2 text-warning"></i>
                                                                {{ $user->is_memory_creator ? 'Remove Creator Flag' : 'Mark as Memory Creator' }}
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <!-- Delete -->
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                                            onsubmit="return confirm('Permanently delete account for {{ $user->name }}? This action is irreversible.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item py-1 text-danger">
                                                                <i class="bi bi-trash me-2"></i> Delete User
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary-subtle text-muted">Protected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-6 d-block mb-2 text-muted"></i>
                                        No users found matching your query filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="card-footer bg-white py-3 px-4 border-top">
                        {{ $users->links('pagination::bootstrap-5') }}
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
</body>
</html>