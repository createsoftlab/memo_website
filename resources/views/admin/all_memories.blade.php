<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Memories - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        
/* Global Toast */
#globalToast {
    min-width: 300px;
    border-radius: 10px;
}

/* Countdown progress track */
.toast-progress-track {
    width: 100%;
    height: 4px;
    background: #e9ecef;
    overflow: hidden;
}

/* Countdown progress line */
.toast-progress-bar {
    width: 100%;
    height: 100%;
    background: #198754;
    transform-origin: left;
    transform: scaleX(1);
}

/* Progress decreases from full to empty */
.toast-progress-bar.running {
    animation: toastCountdown 3s linear forwards;
}

@keyframes toastCountdown {
    from {
        transform: scaleX(1);
    }

    to {
        transform: scaleX(0);
    }
}

    </style>
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
                        <i class="bi bi-collection text-warning me-2"></i> All Memories
                    </h4>
                    <p class="text-muted small m-0">View and manage all user submitted memories here.</p>
                </div>

                <!-- Custom Filter & Search Bar -->

<!-- Search and Filter Section -->
<div class="col-xl-8 col-lg-8 col-12">
    <div class="row g-2 justify-content-lg-end">

        <!-- Search Box -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="input-group input-group-sm shadow-sm w-100">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                    id="memorySearch"
                    class="form-control border-start-0 ps-0"
                    placeholder="Search memories...">
            </div>
        </div>

        <!-- Status Filter Dropdown -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
            <div class="dropdown w-100">
                <button
                    class="btn btn-sm btn-white bg-white border w-100 d-flex justify-content-between align-items-center shadow-sm text-muted dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="font-size: 0.85rem; padding: 6px 10px;">

                    <span id="selectedStatus">Filter Status</span>
                </button>

                <ul class="dropdown-menu w-100 shadow border-0"
                    style="font-size: 0.85rem;">

                    <li>
                        <a class="dropdown-item status-filter py-1"
                            href="#" data-value="">
                            All Status
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter py-1"
                            href="#" data-value="1">
                            Approved
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter py-1"
                            href="#" data-value="2">
                            Rejected
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter py-1"
                            href="#" data-value="0">
                            Pending
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Privacy Filter Dropdown -->
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">
            <div class="dropdown w-100">
                <button
                    class="btn btn-sm btn-white bg-white border w-100 d-flex justify-content-between align-items-center shadow-sm text-muted dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="font-size: 0.85rem; padding: 6px 10px;">

                    <span id="selectedPrivacy">Filter Privacy</span>
                </button>

                <ul class="dropdown-menu w-100 shadow border-0"
                    style="font-size: 0.85rem;">

                    <li>
                        <a class="dropdown-item privacy-filter py-1"
                            href="#" data-value="">
                            All Privacy
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item privacy-filter py-1"
                            href="#" data-value="public">
                            Public
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item privacy-filter py-1"
                            href="#" data-value="private">
                            Private
                        </a>
                    </li>

                </ul>
            </div>
        </div>

    </div>
</div>

<!-- Memories Card Grid Section -->
<div class="row g-4" id="memoriesGrid">

    @forelse ($memories as $memory)

        @php
            $profile = $memory->profile;

            $fullName = trim(implode(' ', array_filter([
                $profile?->first_name,
                $profile?->middle_name,
                $profile?->last_name
            ])));

            $approval = $memory->approval ?? 0;
            $privacy = strtolower($profile?->privacy ?? 'public');

            $profileImage = $profile?->images?->first()?->path
                ?? 'images/profile_photo.png';
        @endphp

        <!-- Memory Card Item -->
        <div class="col-xxl-3 col-xl-4 col-md-6 col-12 memory-item"
            data-name="{{ strtolower($fullName) }}"
            data-id="{{ $memory->id }}"
            data-status="{{ $approval }}"
            data-privacy="{{ $privacy }}">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white memory-card">

                <!-- Card Header / Profile Picture -->
                <div class="position-relative text-center pt-4 bg-light">

                    <img
                        src="{{ asset($profileImage) }}"
                        class="rounded-circle border border-3 border-white shadow-sm object-fit-cover"
                        style="width: 90px; height: 90px;"
                        alt="Profile Picture">

                    <!-- Approval Status Badge -->
                    @if ($approval == 1)

                        <span class="badge bg-success position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                            <i class="bi bi-check-circle me-1"></i>
                            Approved
                        </span>

                    @elseif ($approval == 2)

                        <span class="badge bg-danger position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                            <i class="bi bi-x-circle me-1"></i>
                            Rejected
                        </span>

                    @else

                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm px-2 py-1">
                            <i class="bi bi-clock me-1"></i>
                            Pending
                        </span>

                    @endif

                    <!-- Privacy Badge -->
                    @if ($privacy === 'private')

                        <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2 shadow-sm px-2 py-1">
                            <i class="bi bi-lock me-1"></i>
                            Private
                        </span>

                    @else

                        <span class="badge bg-secondary bg-opacity-75 position-absolute top-0 end-0 m-2 shadow-sm px-2 py-1">
                            <i class="bi bi-globe me-1"></i>
                            Public
                        </span>

                    @endif

                </div>

                <!-- Card Body -->
                <div class="card-body p-3 d-flex flex-column justify-content-between text-center">

                    <div>

                        <!-- Full Name -->
                        <h6 class="fw-bold text-dark mb-1 text-truncate">
                            {{ $fullName }}
                        </h6>

                        <!-- Lifespan -->
                        @if ($profile?->date_of_birth || $profile?->date_of_death)

                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar-event me-1 text-warning"></i>
                                {{ $profile?->date_of_birth ?? 'N/A' }}
                                -
                                {{ $profile?->date_of_death ?? 'N/A' }}
                            </p>

                        @endif

                        <!-- Location -->
                        @if ($profile?->country || $profile?->city)

                            <p class="text-muted small mb-3 text-truncate"
                                style="font-size: 0.8rem;">

                                <i class="bi bi-geo-alt me-1 text-danger"></i>

                                {{ implode(', ', array_filter([
                                    $profile?->city,
                                    $profile?->country
                                ])) }}

                            </p>

                        @endif

                    </div>

                    <!-- Card Actions -->
                    <div>

                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="text-muted small"
                                style="font-size: 0.75rem;">

                                <i class="bi bi-heart-fill text-danger me-1"></i>
                                Tributes Open

                            </span>
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="d-flex flex-wrap gap-1 mb-2">

                            <!-- View Memory -->
                            <button type="button"
                                class="btn btn-xs btn-outline-success flex-fill py-1"
                                style="font-size: 0.75rem;"
                                data-bs-toggle="modal"
                                data-bs-target="#viewMemoryModal1{{ $memory->id }}"
                                title="View Memory">

                                <i class="bi bi-eye"></i>
                                View

                            </button>

                            <!-- Delete Memory -->
                            @if (Auth::user()?->canDelete())

                                <button type="button"
                                    class="btn btn-xs btn-outline-danger flex-fill py-1"
                                    style="font-size: 0.75rem;">

                                    <i class="bi bi-trash"></i>
                                    Delete

                                </button>

                            @endif

                        </div>

                        <!-- Card Footer -->
                        <div class="d-flex align-items-center justify-content-between border-top pt-2">

                            <span class="text-muted small"
                                style="font-size: 0.7rem;">

                                ID: {{ $memory->id }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @empty
    @endforelse

</div>
</div>
<!-- No Results Message -->
<div id="noMemoriesMessage"
    class="text-center py-5 d-none">

    <i class="bi bi-search text-muted"
        style="font-size: 3rem;"></i>

    <h5 class="mt-3 text-dark">
        No Memories Found
    </h5>

    <p class="text-muted">
        No memories match your search or selected filters.
    </p>

</div>



        </div>
    </div>
    </div>

    <!-- ========================================== -->
    <!-- VIEW MEMORY MODAL WITH TABS (CARD 1)       -->
    <!-- ========================================== -->

    @foreach ($memories as $memory)


        <div class="modal fade" id="viewMemoryModal1{{ $memory->id }}" tabindex="-1"
            aria-labelledby="viewMemoryModalLabel1{{ $memory->id }}" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4">

                    <!-- Modal Header -->
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title font-serif" id="viewMemoryModalLabel1{{ $memory->id }}">
                            <i class="bi bi-info-circle text-warning me-2"></i>
                            {{ $memory->profile?->first_name }} {{ $memory->profile?->middle_name }} {{ $memory->profile?->last_name }}
                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-0">

                        <!-- Modal Navigation Tabs -->
                        <ul class="nav nav-tabs px-4 pt-3 bg-light border-bottom" id="memoryTabs{{ $memory->id }}"
                            role="tablist">

                            <!-- About Tab -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold text-dark" id="about-tab{{ $memory->id }}"
                                    data-bs-toggle="tab" data-bs-target="#about-pane{{ $memory->id }}"
                                    type="button" role="tab" aria-controls="about-pane{{ $memory->id }}"
                                    aria-selected="true">
                                    <i class="bi bi-person me-1"></i>
                                    About
                                </button>
                            </li>

                            <!-- Life Story Tab -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-dark" id="life-tab{{ $memory->id }}"
                                    data-bs-toggle="tab" data-bs-target="#life-pane{{ $memory->id }}"
                                    type="button" role="tab" aria-controls="life-pane{{ $memory->id }}"
                                    aria-selected="false">
                                    <i class="bi bi-journal-text me-1"></i>
                                    Life Story
                                </button>
                            </li>

                            <!-- Gallery Tab -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-dark" id="gallery-tab{{ $memory->id }}"
                                    data-bs-toggle="tab" data-bs-target="#gallery-pane{{ $memory->id }}"
                                    type="button" role="tab" aria-controls="gallery-pane{{ $memory->id }}"
                                    aria-selected="false">
                                    <i class="bi bi-images me-1"></i>
                                    Gallery
                                </button>
                            </li>

                            {{-- <!-- Tributes Tab -->
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold text-dark" id="tributes-tab{{ $memory->id }}"
                                    data-bs-toggle="tab" data-bs-target="#tributes-pane{{ $memory->id }}"
                                    type="button" role="tab" aria-controls="tributes-pane{{ $memory->id }}"
                                    aria-selected="false">
                                    <i class="bi bi-heart me-1"></i>
                                    Tributes
                                </button>
                            </li> --}}
                        </ul>

                        <!-- Tab Content Panes -->
                        <div class="tab-content p-4" id="memoryTabContent{{ $memory->id }}">

                            <!-- ABOUT PANE -->
                            <div class="tab-pane fade show active" id="about-pane{{ $memory->id }}"
                                role="tabpanel" aria-labelledby="about-tab{{ $memory->id }}">

                                <div class="row g-4 align-items-center">

                                    <div class="col-md-4 text-center">

                                        <img src="{{ asset($memory->profile?->images?->first()?->path ?? 'images/profile_photo.png') }}"
                                            class="rounded-circle shadow-sm object-fit-cover border mb-2"
                                            style="width: 120px; height: 120px;" alt="Profile">

                                        <div class="d-flex justify-content-center gap-2 mt-2">

                                            @if ($memory?->status == 1)
                                                <span class="badge bg-success">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>
                                            @endif

                                            @if ($memory->profile?->privacy === 'private')
                                                <span class="badge bg-secondary">
                                                    Private
                                                </span>
                                            @else
                                                <span class="badge bg-dark">
                                                    Public
                                                </span>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="col-md-8">

                                        <h4 class="fw-bold text-dark mb-1">
                                            {{ $memory->profile?->first_name }} {{ $memory->profile?->middle_name }} {{ $memory->profile?->last_name }}
                                        </h4>

                                        <p class="text-muted small mb-3">
                                            {{ Str::limit($memory?->about, 100) }}
                                        </p>

                                        <ul class="list-unstyled small text-muted mb-0">

                                            <!-- Lifespan -->
                                            @if ($memory->profile?->date_of_birth || $memory->profile?->date_of_death)
                                                <li class="mb-2">
                                                    <i class="bi bi-calendar-event text-warning me-2"></i>
                                                    <strong>Lifespan:</strong>
                                                    {{ $profile?->date_of_birth ?? 'N/A' }}
                                                    -
                                                    {{ $profile?->date_of_death ?? 'N/A' }}
                                                </li>
                                            @endif

                                            <!-- Location -->
                                            @if ($memory->profile?->country || $memory->profile?->city)
                                                <li class="mb-2">
                                                    <i class="bi bi-geo-alt text-danger me-2"></i>
                                                    <strong>Location:</strong>
                                                    {{ implode(', ', array_filter([$memory->profile?->city, $memory->profile?->country])) }}
                                                </li>
                                            @endif

                                            <!-- Donations -->
                                            <li class="mb-2">
                                                <i class="bi bi-heart-fill text-danger me-2"></i>
                                                <strong>Donations:</strong>
                                                Enabled & Active
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- LIFE STORY PANE -->
                            <div class="tab-pane fade" id="life-pane{{ $memory->id }}" role="tabpanel"
                                aria-labelledby="life-tab{{ $memory->id }}">

                                @if ($memory->about)
                                    
                                <h6 class="fw-bold text-dark">
                                    Biography & Life History
                                </h6>
                                @endif
                                <p class="text-muted small">
                                    {{ $memory?->about }}
                                </p>

                            </div>

                            <!-- GALLERY PANE -->
                            <div class="tab-pane fade" id="gallery-pane{{ $memory->id }}" role="tabpanel"
                                aria-labelledby="gallery-tab{{ $memory->id }}">

                                <h6 class="fw-bold text-dark mb-3">
                                    Photo & Video Memories
                                </h6>

                                <div class="row g-2">
                                    @foreach ($memory->profile?->photos ?? [] as $photo)
                                    <div class="col-4">
                                        <img src="{{ asset($photo->file_path) }}"
                                            class="img-fluid rounded shadow-sm w-100 object-fit-cover"
                                            style="height: 100px;" alt="Gallery image">
                                    </div>
                                    @endforeach

                                </div>
                            </div>

                            {{-- <!-- TRIBUTES PANE -->
                            <div class="tab-pane fade" id="tributes-pane{{ $memory->id }}" role="tabpanel"
                                aria-labelledby="tributes-tab{{ $memory->id }}">

                                <h6 class="fw-bold text-dark mb-3">
                                    Condolences & Messages
                                </h6>

                                <div class="border rounded p-2 mb-2 bg-light">
                                    <small class="text-muted d-block">
                                        <strong>Amaan Silva:</strong>
                                        "An incredible soul who will always
                                        be remembered in our hearts."
                                    </small>
                                </div>

                                <div class="border rounded p-2 bg-light">
                                    <small class="text-muted d-block">
                                        <strong>Priyantha Perera:</strong>
                                        "Rest in peace. Thank you for everything
                                        you've done for the community."
                                    </small>
                                </div>

                            </div> --}}

                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">

                        <span class="text-muted small">
                            Memory ID: #MEM-{{ str_pad($memory->id, 3, '0', STR_PAD_LEFT) }}
                        </span>


                        <div class="d-flex gap-2">

                            <form action="{{ route('admin.approveOrRejectMemories', $memory->id) }}" method="POST"
                                class="d-flex gap-2">

                                @csrf

                                <!-- Approve Button -->
                                <button type="submit" name="approval" value="1"
                                    class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-check-lg"></i>
                                    Approve
                                </button>

                                <!-- Reject Button -->
                                <button type="submit" name="approval" value="2"
                                    class="btn btn-outline-warning text-dark btn-sm">
                                    <i class="bi bi-x-lg"></i>
                                    Reject
                                </button>

                            </form>

                            <!-- Close Button -->
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                Close
                            </button>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endforeach


    <!-- Global Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3"
     style="z-index: 11000;">

    <div id="globalToast"
         class="toast border-0 shadow overflow-hidden"
         role="alert"
         aria-live="assertive"
         aria-atomic="true">

        <div class="toast-header">

            <i id="globalToastIcon"
               class="bi bi-check-circle-fill text-success me-2"></i>

            <strong class="me-auto" id="globalToastTitle">
                Success
            </strong>

            <small>Just now</small>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="toast"
                    aria-label="Close"></button>

        </div>

        <div class="toast-body" id="globalToastMessage"></div>

        <!-- Animated countdown line -->
        <div class="toast-progress-track">
            <div id="globalToastProgress"
                 class="toast-progress-bar"></div>
        </div>

    </div>
</div>

    <!-- ========================================== -->
    <!-- VIEW MEMORY MODAL WITH TABS (CARD 2)       -->
    <!-- ========================================== -->
    <div class="modal fade" id="viewMemoryModal2" tabindex="-1" aria-labelledby="viewMemoryModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title font-serif" id="viewMemoryModalLabel2"><i
                            class="bi bi-info-circle text-warning me-2"></i> Memory Profile: Arthur Pendelton</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">

                    <!-- Modal Navigation Tabs -->
                    <ul class="nav nav-tabs px-4 pt-3 bg-light border-bottom" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold text-dark" data-bs-toggle="tab"
                                data-bs-target="#about-pane-2" type="button" role="tab"><i
                                    class="bi bi-person me-1"></i> About</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-dark" data-bs-toggle="tab"
                                data-bs-target="#life-pane-2" type="button" role="tab"><i
                                    class="bi bi-journal-text me-1"></i> Life Story</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-dark" data-bs-toggle="tab"
                                data-bs-target="#gallery-pane-2" type="button" role="tab"><i
                                    class="bi bi-images me-1"></i> Gallery</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-dark" data-bs-toggle="tab"
                                data-bs-target="#tributes-pane-2" type="button" role="tab"><i
                                    class="bi bi-heart me-1"></i> Tributes</button>
                        </li>
                    </ul>

                    <!-- Tab Content Panes -->
                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="about-pane-2" role="tabpanel">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4 text-center">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?fit=crop&w=200&h=200"
                                        class="rounded-circle shadow-sm object-fit-cover border mb-2"
                                        style="width: 120px; height: 120px;" alt="Profile">
                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        <span class="badge bg-secondary">Private</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h4 class="fw-bold text-dark mb-1">Arthur Pendelton</h4>
                                    <p class="text-muted small mb-3">A private family archive to remember and honor
                                        relatives in a quiet, intimate setting.</p>
                                    <ul class="list-unstyled small text-muted mb-0">
                                        <li class="mb-2"><i
                                                class="bi bi-calendar-event text-warning me-2"></i><strong>Lifespan:</strong>
                                            1950 - 2026</li>
                                        <li class="mb-2"><i
                                                class="bi bi-geo-alt text-danger me-2"></i><strong>Location:</strong>
                                            Kandy, Sri Lanka</li>
                                        <li class="mb-2"><i
                                                class="bi bi-heart text-muted me-2"></i><strong>Donations:</strong>
                                            Disabled</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="life-pane-2" role="tabpanel">
                            <h6 class="fw-bold text-dark">Biography & Life History</h6>
                            <p class="text-muted small">Private records and historical notes belonging to Arthur
                                Pendelton.</p>
                        </div>
                        <div class="tab-pane fade" id="gallery-pane-2" role="tabpanel">
                            <h6 class="fw-bold text-dark mb-3">Photo & Video Memories</h6>
                            <p class="text-muted small">No public photos uploaded yet.</p>
                        </div>
                        <div class="tab-pane fade" id="tributes-pane-2" role="tabpanel">
                            <h6 class="fw-bold text-dark mb-3">Condolences & Messages</h6>
                            <p class="text-muted small">Private profile - no public messages visible.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">
                    <span class="text-muted small">Memory ID: #MEM-002</span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm"><i class="bi bi-check-lg"></i>
                            Approve</button>
                        @if (Auth::user()?->canDelete())
                            <button type="button" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i>
                                Delete</button>
                        @endif
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Live Search and Filter JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('memorySearch');

    const statusFilters = document.querySelectorAll('.status-filter');
    const privacyFilters = document.querySelectorAll('.privacy-filter');

    const memoryItems = document.querySelectorAll(
        '#memoriesGrid .memory-item'
    );

    const noMemoriesMessage = document.getElementById(
        'noMemoriesMessage'
    );

    const selectedStatus = document.getElementById('selectedStatus');
    const selectedPrivacy = document.getElementById('selectedPrivacy');

    let currentStatus = '';
    let currentPrivacy = '';

    // Main filter function
    function filterMemories() {

        const searchValue = searchInput.value.trim().toLowerCase();

        let visibleCount = 0;

        memoryItems.forEach(function (item) {

            const name = item.dataset.name || '';
            const id = item.dataset.id || '';
            const status = item.dataset.status || '0';
            const privacy = item.dataset.privacy || '';

            // Search by name or memory ID
            const matchesSearch =
                name.includes(searchValue) ||
                id.includes(searchValue);

            // Match approval status
            const matchesStatus =
                currentStatus === '' ||
                status === currentStatus;

            // Match privacy
            const matchesPrivacy =
                currentPrivacy === '' ||
                privacy === currentPrivacy;

            // Show only matching memories
            if (
                matchesSearch &&
                matchesStatus &&
                matchesPrivacy
            ) {

                item.classList.remove('d-none');
                visibleCount++;

            } else {

                item.classList.add('d-none');

            }

        });

        // No results handling
        if (noMemoriesMessage) {

            if (visibleCount === 0) {
                noMemoriesMessage.classList.remove('d-none');
            } else {
                noMemoriesMessage.classList.add('d-none');
            }

        }

    }

    // Live search event
    if (searchInput) {

        searchInput.addEventListener('input', function () {
            filterMemories();
        });

    }

    // Status dropdown selection
    statusFilters.forEach(function (filter) {

        filter.addEventListener('click', function (event) {

            event.preventDefault();

            currentStatus = this.dataset.value;

            selectedStatus.textContent = this.textContent.trim();

            filterMemories();

        });

    });

    // Privacy dropdown selection
    privacyFilters.forEach(function (filter) {

        filter.addEventListener('click', function (event) {

            event.preventDefault();

            currentPrivacy = this.dataset.value;

            selectedPrivacy.textContent = this.textContent.trim();

            filterMemories();

        });

    });

    // Initial filter execution
    filterMemories();

});
</script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Force initialize default active tab securely
        document.addEventListener('DOMContentLoaded', function() {
            const defaultTabEl = document.querySelector('#memorialTabs a[href="#tab-basic"]');
            if (defaultTabEl) {
                const tab = new bootstrap.Tab(defaultTabEl);
                tab.show();
            }
        });
    </script>

//toast message script
    <script>
document.addEventListener('DOMContentLoaded', function () {

    const toastElement = document.getElementById('globalToast');
    const progressBar = document.getElementById('globalToastProgress');

    if (!toastElement || !progressBar || typeof bootstrap === 'undefined') {
        return;
    }

    const toast = bootstrap.Toast.getOrCreateInstance(toastElement, {
        animation: true,
        autohide: true,
        delay: 3000
    });

    const types = {
        success: {
            title: 'Success',
            icon: 'bi bi-check-circle-fill text-success me-2',
            color: '#198754'
        },
        error: {
            title: 'Error',
            icon: 'bi bi-x-circle-fill text-danger me-2',
            color: '#dc3545'
        },
        warning: {
            title: 'Warning',
            icon: 'bi bi-exclamation-triangle-fill text-warning me-2',
            color: '#ffc107'
        },
        info: {
            title: 'Information',
            icon: 'bi bi-info-circle-fill text-primary me-2',
            color: '#0d6efd'
        }
    };

    let progressTimer = null;

    window.showGlobalToast = function (message, type = 'success') {

        const config = types[type] || types.success;

        document.getElementById('globalToastTitle').textContent =
            config.title;

        document.getElementById('globalToastMessage').textContent =
            message;

        document.getElementById('globalToastIcon').className =
            config.icon;

        // Set progress color
        progressBar.style.backgroundColor = config.color;

        // Reset progress animation
        progressBar.classList.remove('running');

        void progressBar.offsetWidth;

        progressBar.classList.add('running');

        // Reset previous countdown
        clearTimeout(progressTimer);

        // Restart Bootstrap toast
        toast.show();

        // Hide after exactly 3 seconds
        progressTimer = setTimeout(function () {
            toast.hide();
        }, 3000);
    };

    // Laravel session messages
    @if (session('success'))
        showGlobalToast(@json(session('success')), 'success');
    @elseif (session('error'))
        showGlobalToast(@json(session('error')), 'error');
    @elseif (session('warning'))
        showGlobalToast(@json(session('warning')), 'warning');
    @elseif (session('info'))
        showGlobalToast(@json(session('info')), 'info');
    @elseif ($errors->any())
        showGlobalToast(@json($errors->first()), 'error');
    @endif

});
</script>

</body>

</html>
