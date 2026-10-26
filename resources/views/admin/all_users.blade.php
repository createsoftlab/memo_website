<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users - Memorial Dashboard</title>
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
                <div class="col-xl-4 col-lg-4 col-12">
                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-people text-warning me-2"></i> All Users 
                    </h4>
                    <p class="text-muted small m-0">View, search, and manage user accounts & staff permissions.</p>
                </div>

                <!-- Custom Filter & Search Bar Form -->
                <div class="col-xl-8 col-lg-8 col-12">
                    <form method="GET" action="{{ route('admin.users.index') }}">
                        <div class="row g-2 justify-content-lg-end">
                            <!-- Search Box -->
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                <div class="input-group input-group-sm shadow-sm w-100">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Search name, email, phone...">
                                </div>
                            </div>

                            <!-- Status Filter Dropdown -->
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
                                <select name="status" class="form-select form-select-sm bg-white border w-100 shadow-sm text-muted" onchange="this.form.submit()" style="font-size: 0.85rem; padding: 6px 10px;">
                                    <option value="">Filter Status: All</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
                                </select>
                            </div>

                            <!-- Role / User Type Filter Dropdown -->
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
                                <select name="role" class="form-select form-select-sm bg-white border w-100 shadow-sm text-muted" onchange="this.form.submit()" style="font-size: 0.85rem; padding: 6px 10px;">
                                    <option value="">Filter Role: All Roles</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="memory_user" {{ request('role') === 'memory_user' ? 'selected' : '' }}>Memory Created User</option>
                                    <option value="general_user" {{ request('role') === 'general_user' ? 'selected' : '' }}>General User</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Users Card Grid Section -->
            <div class="row g-4">
                @forelse($users as $user)
                    <div class="col-xxl-3 col-xl-4 col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white memory-card">

                            <!-- Profile Image & Status -->
                            <div class="position-relative text-center pt-4 bg-light">
                                <img src="{{ $user->avatar_url }}"
                                     class="rounded-circle border border-3 border-white shadow-sm object-fit-cover"
                                     style="width: 90px; height: 90px;"
                                     alt="{{ $user->name }}">

                                <!-- Status Badge -->
                                @if($user->status === 'active')
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Active
                                    </span>
                                @elseif($user->status === 'suspended')
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-pause-circle me-1"></i> Suspended
                                    </span>
                                @else
                                    <span class="badge bg-dark position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-slash-circle me-1"></i> Banned
                                    </span>
                                @endif

                                <!-- Role Badge -->
                                <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2 shadow-sm px-2 py-1" style="font-size: 0.7rem;">
                                    {{ $user->role_label }}
                                </span>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-3 d-flex flex-column justify-content-between text-center">
                                <div>
                                    <!-- User Name -->
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $user->name }}">
                                        {{ $user->name }}
                                    </h6>

                                    <!-- Location -->
                                    <p class="text-muted small mb-3 text-truncate" style="font-size: 0.8rem;">
                                        <i class="bi bi-geo-alt me-1 text-danger"></i>
                                        {{ $user->state ? $user->state . ', ' : '' }}{{ $user->country ?? 'Global' }}
                                    </p>

                                    <!-- User Details -->
                                    <div class="border-top border-bottom py-2 mb-3 text-start">
                                        <!-- Email -->
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-envelope text-primary icon-user-card me-2"></i>
                                            <a href="mailto:{{ $user->email }}"
                                               class="small text-decoration-none text-truncate text-user-card"
                                               title="Send Email: {{ $user->email }}">
                                                {{ $user->email }}
                                            </a>
                                        </div>

                                        <!-- Contact -->
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone text-success icon-user-card me-2"></i>
                                            @if($user->phone)
                                                <a href="tel:{{ $user->phone }}"
                                                   class="small text-decoration-none text-user-card"
                                                   title="Call User: {{ $user->phone }}">
                                                    {{ $user->phone }}
                                                </a>
                                            @else
                                                <span class="small text-muted" style="font-size: 0.8rem;">No phone provided</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Role Info -->
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <span class="text-muted small" style="font-size: 0.75rem;">
                                            <i class="bi bi-shield-check text-warning me-1"></i>
                                            {{ $user->role_label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div>
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        <!-- Active Quick Action -->
                                        <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="flex-fill">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-xs {{ $user->status === 'active' ? 'btn-success text-white' : 'btn-outline-success' }} w-100 py-1"
                                                    style="font-size: 0.75rem;" title="Set Active">
                                                <i class="bi bi-check-lg"></i> Active
                                            </button>
                                        </form>

                                        <!-- Band (Banned) Quick Action -->
                                        <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="flex-fill">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="banned">
                                            <button type="submit" class="btn btn-xs {{ $user->status === 'banned' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark' }} w-100 py-1"
                                                    style="font-size: 0.75rem;" title="Ban User">
                                                <i class="bi bi-x-lg"></i> Band
                                            </button>
                                        </form>

                                        <!-- Suspend Quick Action -->
                                        <form method="POST" action="{{ route('admin.users.status', $user->id) }}" class="flex-fill">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="suspended">
                                            <button type="submit" class="btn btn-xs {{ $user->status === 'suspended' ? 'btn-danger text-white' : 'btn-outline-danger' }} w-100 py-1"
                                                    style="font-size: 0.75rem;" title="Suspend User">
                                                <i class="bi bi-pause-circle"></i> Suspend
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Bottom Info and Buttons -->
                                    <div class="d-flex align-items-center justify-content-between border-top pt-2">
                                        <span class="text-muted small" style="font-size: 0.7rem;">
                                            ID: #USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <div class="d-flex gap-2">
                                            <!-- View Details Modal Trigger -->
                                            <button type="button"
                                                    class="btn btn-sm btn-light border text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewMemoryModal{{ $user->id }}"
                                                    title="View User Profile">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <!-- Edit User Profile -->
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="btn btn-sm btn-light border text-warning rounded-circle d-flex align-items-center justify-content-center"
                                               style="width: 32px; height: 32px;"
                                               title="Edit User">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                         VIEW USER / MEMORY DETAILS MODAL
                    ====================================================== -->
                    <div class="modal fade"
                         id="viewMemoryModal{{ $user->id }}"
                         tabindex="-1"
                         aria-labelledby="viewMemoryModalLabel{{ $user->id }}"
                         aria-hidden="true">

                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                                <!-- Modal Header -->
                                <div class="modal-header bg-light border-0">
                                    <div>
                                        <h5 class="modal-title font-serif text-brown fw-bold" id="viewMemoryModalLabel{{ $user->id }}">
                                            <i class="bi bi-person-vcard me-2 text-warning"></i>
                                            User Profile & Details
                                        </h5>
                                        <small class="text-muted">
                                            User ID: #USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                                        </small>
                                    </div>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body p-4">
                                    <!-- Profile -->
                                    <div class="text-center mb-4">
                                        <img src="{{ $user->avatar_url }}"
                                             class="rounded-circle border border-4 border-warning shadow"
                                             style="width: 120px; height: 120px; object-fit: cover;"
                                             alt="{{ $user->name }}">

                                        <h5 class="fw-bold text-dark mt-3 mb-1">
                                            {{ $user->name }}
                                        </h5>

                                        @if($user->status === 'active')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i> Active
                                            </span>
                                        @elseif($user->status === 'suspended')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-pause-circle me-1"></i> Suspended
                                            </span>
                                        @else
                                            <span class="badge bg-dark">
                                                <i class="bi bi-slash-circle me-1"></i> Banned
                                            </span>
                                        @endif

                                        <span class="badge bg-dark ms-1">
                                            <i class="bi bi-shield-check me-1"></i> {{ $user->role_label }}
                                        </span>
                                    </div>

                                    <!-- User Information Card -->
                                    <div class="card border-0 bg-light rounded-3 mb-3">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-brown mb-3">
                                                <i class="bi bi-person-badge me-2"></i>
                                                User Information
                                            </h6>

                                            <div class="row g-3">
                                                <!-- Name -->
                                                <div class="col-md-6">
                                                    <div class="small text-muted mb-1">Full Name</div>
                                                    <div class="fw-semibold">
                                                        <i class="bi bi-person me-2 text-primary"></i>
                                                        {{ $user->name }}
                                                    </div>
                                                </div>

                                                <!-- Username -->
                                                <div class="col-md-6">
                                                    <div class="small text-muted mb-1">Username</div>
                                                    <div class="fw-semibold">
                                                        <i class="bi bi-at me-2 text-info"></i>
                                                        {{ $user->username ?? 'Not set' }}
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-6">
                                                    <div class="small text-muted mb-1">Email Address</div>
                                                    <a href="mailto:{{ $user->email }}" class="fw-semibold text-primary text-decoration-none">
                                                        <i class="bi bi-envelope me-2"></i>
                                                        {{ $user->email }}
                                                    </a>
                                                </div>

                                                <!-- Contact -->
                                                <div class="col-md-6">
                                                    <div class="small text-muted mb-1">Contact Number</div>
                                                    @if($user->phone)
                                                        <a href="tel:{{ $user->phone }}" class="fw-semibold text-success text-decoration-none">
                                                            <i class="bi bi-telephone me-2"></i>
                                                            {{ $user->phone }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Not provided</span>
                                                    @endif
                                                </div>

                                                <!-- Location -->
                                                <div class="col-md-6">
                                                    <div class="small text-muted mb-1">Location</div>
                                                    <div class="fw-semibold">
                                                        <i class="bi bi-geo-alt me-2 text-danger"></i>
                                                        {{ $user->state ? $user->state . ', ' : '' }}{{ $user->country ?? 'Global' }}
                                                    </div>
                                                </div>

                                                <!-- Joined -->
                                                <div class="col-md-6">
                                                    <div class="small text-muted mb-1">Joined Date</div>
                                                    <div class="fw-semibold">
                                                        <i class="bi bi-calendar3 me-2 text-warning"></i>
                                                        {{ $user->created_at ? $user->created_at->format('d F Y') : 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($user->isMemoryUser())
                                        <!-- Memorial & Package Information -->
                                        <div class="card border-0 bg-light rounded-3 mb-3">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-brown mb-3">
                                                    <i class="bi bi-heart me-2 text-danger"></i>
                                                    Memorial & Package Details
                                                </h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-block">Account Type</small>
                                                        <strong class="text-dark">Memory Created User</strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-block">Memorial Package</small>
                                                        <span class="badge bg-warning text-dark"><i class="bi bi-award me-1"></i> Listed Memory Package</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-block">Associated Events</small>
                                                        <strong>{{ $user->events->count() }} Memorial Event(s)</strong>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted d-block">Package Status</small>
                                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active / Published</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Account & Permissions Card -->
                                    <div class="card border-0 bg-light rounded-3 mb-3">
                                        <div class="card-body">
                                            <h6 class="fw-bold text-brown mb-3">
                                                <i class="bi bi-shield-lock me-2"></i>
                                                Account & Role Privileges
                                            </h6>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Assigned Role</small>
                                                    <strong class="text-dark">{{ $user->role_label }}</strong>
                                                </div>

                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Current Status</small>
                                                    <span class="badge {{ $user->status === 'active' ? 'bg-success' : ($user->status === 'suspended' ? 'bg-danger' : 'bg-dark') }}">
                                                        {{ ucfirst($user->status) }}
                                                    </span>
                                                </div>

                                                @if(!empty($user->permissions))
                                                    <div class="col-12">
                                                        <small class="text-muted d-block mb-2">Module Access Permissions</small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($user->permissions as $module => $actions)
                                                                @foreach($actions as $action => $val)
                                                                    @if($val)
                                                                        <span class="badge bg-secondary" style="font-size: 0.72rem;">
                                                                            {{ ucfirst($module) }}: {{ ucfirst($action) }}
                                                                        </span>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer border-0 bg-light">
                                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary">
                                        <i class="bi bi-envelope me-1"></i> Email User
                                    </a>

                                    @if($user->phone)
                                        <a href="tel:{{ $user->phone }}" class="btn btn-outline-success">
                                            <i class="bi bi-telephone me-1"></i> Call User
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning text-dark fw-semibold">
                                        <i class="bi bi-pencil me-1"></i> Edit User
                                    </a>

                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-5 text-center">
                            <i class="bi bi-people display-4 text-muted mb-3"></i>
                            <h5 class="fw-bold text-dark">No Users Found</h5>
                            <p class="text-muted small mb-3">There are no users matching your current search or filter criteria.</p>
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                                    <i class="bi bi-arrow-repeat me-1"></i> Clear Filters
                                </a>
                                <a href="{{ route('admin.employees.create') }}" class="btn btn-sm btn-warning fw-semibold px-3 ms-2">
                                    <i class="bi bi-person-plus me-1"></i> Add New Employee
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Container -->
            @if($users->hasPages())
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        <nav aria-label="Page navigation">
                            <ul class="pagination custom-pagination shadow-sm">
                                {{-- Previous Page Link --}}
                                @if ($users->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link rounded-start-pill px-3">
                                            <i class="bi bi-chevron-left me-1"></i> Previous
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link rounded-start-pill px-3" href="{{ $users->previousPageUrl() }}" rel="prev">
                                            <i class="bi bi-chevron-left me-1"></i> Previous
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                                    @if ($page == $users->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($users->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link rounded-end-pill px-3" href="{{ $users->nextPageUrl() }}" rel="next">
                                            Next <i class="bi bi-chevron-right ms-1"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link rounded-end-pill px-3">
                                            Next <i class="bi bi-chevron-right ms-1"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            @endif

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
                                <li><a href="{{ url('admin/dashboard') }}" class="text-light-tan text-decoration-none">Home</a></li>
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

    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Mobile sidebar script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if(sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if(sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Force initialize default active tab securely
        document.addEventListener('DOMContentLoaded', function () {
            const defaultTabEl = document.querySelector('#memorialTabs a[href="#tab-basic"]');
            if (defaultTabEl) {
                const tab = new bootstrap.Tab(defaultTabEl);
                tab.show();
            }
        });
    </script>
    
</body>
</html>