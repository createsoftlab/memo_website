<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
</head>
<body>

    @include('admin.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        @include('admin.header')

        <!-- Page Body -->
        <div class="container-fluid py-4 overflow-hidden">

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Section Header & Filter Bar Container -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold text-dark m-0 font-serif">
                                <i class="bi bi-calendar-check text-warning me-2"></i> My Events
                            </h4>
                            <p class="text-muted small m-0">View and manage all your created events here.</p>
                        </div>
                    </div>
                </div>

                <!-- Custom Filter & Search Bar -->
                <div class="col-xl-8 col-lg-8 col-12">
                    <form action="{{ route('admin.events.my') }}" method="GET" class="row g-2 justify-content-lg-end align-items-center">
                        <!-- Search Box -->
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="input-group input-group-sm shadow-sm w-100">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search events..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Status Filter Dropdown -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6">
                            <select name="status" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Privacy Filter Dropdown -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6">
                            <select name="privacy" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                                <option value="">All Privacy</option>
                                <option value="public" {{ request('privacy') === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ request('privacy') === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <!-- Create Event Action -->
                        <div class="col-auto">
                            <a href="{{ route('admin.events.create') }}" class="btn btn-warning btn-sm fw-bold text-dark shadow-sm px-3">
                                <i class="bi bi-plus-lg me-1"></i> New Event
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Events Card Grid Section -->
            <div class="row g-4">
                @forelse($events as $event)
                    <div class="col-xxl-3 col-xl-4 col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white event-card">
                            <div class="position-relative">
                                <img src="{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?fit=crop&w=500&h=300' }}" 
                                     class="card-img-top object-fit-cover" 
                                     style="height: 180px;" 
                                     alt="{{ $event->title }}">
                                
                                <!-- Status Badge -->
                                @if($event->status === 'approved')
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Approved
                                    </span>
                                @elseif($event->status === 'rejected')
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i> Rejected
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-hourglass-split me-1"></i> Pending
                                    </span>
                                @endif

                                <!-- Privacy Badge -->
                                @if($event->is_private)
                                    <span class="badge bg-secondary position-absolute top-0 end-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-lock me-1"></i> Private
                                    </span>
                                @else
                                    <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2 shadow-sm px-2 py-1">
                                        <i class="bi bi-globe me-1"></i> Public
                                    </span>
                                @endif
                            </div>

                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $event->title }}">
                                        {{ $event->title }}
                                    </h6>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar-event me-1 text-warning"></i> 
                                        {{ $event->start_date ? $event->start_date->format('M d, Y') : 'Date not set' }}
                                        @if($event->start_time)
                                            | {{ date('h:i A', strtotime($event->start_time)) }}
                                        @endif
                                    </p>
                                    <p class="text-muted small mb-3 text-truncate" style="font-size: 0.8rem;">
                                        <i class="bi bi-geo-alt me-1 text-danger"></i> 
                                        {{ $event->location ?? 'Online / Unspecified Location' }}
                                    </p>
                                </div>

                                <!-- Action Icons (View, Edit, Delete) -->
                                <div class="border-top pt-2 mt-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="text-muted small" style="font-size: 0.75rem;">
                                            @if($event->allow_donation)
                                                <i class="bi bi-heart-fill text-danger me-1"></i> Donations On
                                            @else
                                                <i class="bi bi-heart text-muted me-1"></i> Donations Off
                                            @endif
                                        </span>
                                        <span class="badge bg-light text-secondary border" style="font-size: 0.7rem;">
                                            {{ ucfirst($event->event_type) }}
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-1">
                                        <span class="text-muted small" style="font-size: 0.7rem;">ID: #EVT-{{ str_pad($event->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        
                                        <div class="d-flex gap-2 align-items-center">
                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-light border text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit Event">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- Delete Button (Super Admin Only) -->
                                            @if(Auth::user()?->canDelete())
                                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete Event">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm p-5 text-center bg-white rounded-4">
                            <i class="bi bi-calendar-x text-muted display-4 mb-3"></i>
                            <h5 class="fw-bold text-dark font-serif">No Events Found</h5>
                            <p class="text-muted small mb-3">You have not created any events yet, or no events match your current filter criteria.</p>
                            <div>
                                <a href="{{ route('admin.events.create') }}" class="btn btn-warning fw-bold text-dark px-4 rounded-pill">
                                    <i class="bi bi-plus-lg me-1"></i> Create Your First Event
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $events->links('pagination::bootstrap-5') }}
                </div>
            @endif

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
    </script>

</body>
</html>