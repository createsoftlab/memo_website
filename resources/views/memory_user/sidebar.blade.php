<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">

        @php
            $currentUser = Auth::user() ?? ($user ?? null);
            $hasActivePlan = $currentUser?->hasActivePackage() ?? false;
            $canCreateMem = $currentUser?->canCreateMemory() ?? false;
            $canCreateEvt = $currentUser?->canCreateEvent() ?? false;
            $daysLeft = $currentUser?->remainingDays() ?? 0;
            $memoriesLeft = $currentUser?->remainingMemoriesQuota() ?? 0;
            $eventsLeft = $currentUser?->remainingEventsQuota() ?? 0;
            $upgradeUrl = route('packages.index', ['country' => strtolower($currentUser?->country ?? 'all')]);

            $avatarUrl =
                $currentUser?->avatar_url ??
                (!empty($currentUser?->avatar)
                    ? (str_starts_with($currentUser->avatar, 'http')
                        ? $currentUser->avatar
                        : asset($currentUser->avatar))
                    : 'https://ui-avatars.com/api/?name=' .
                        urlencode($currentUser?->name ?? 'User') .
                        '&background=D4AF37&color=ffffff&bold=true');
            $roleLabel =
                $currentUser?->role_label ??
                (!empty($currentUser?->role)
                    ? ucfirst(str_replace('_', ' ', $currentUser->role))
                    : 'Memory Creator');
        @endphp

        <!-- Sidebar Profile -->
        <div class="sidebar-profile p-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $avatarUrl }}"
                    class="rounded-circle object-fit-cover border border-warning border-opacity-25 flex-shrink-0"
                    width="45"
                    height="45"
                    alt="Profile">

                <div class="overflow-hidden">
                    <h6 class="m-0 text-white fw-bold text-truncate" style="font-size: 0.9rem;" title="{{ $currentUser?->name ?? 'Guest' }}">
                        {{ $currentUser?->name ?? 'Guest' }}
                    </h6>
                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 mt-1 text-truncate"
                        style="font-size: 0.72rem; font-weight: 500;">
                        {{ $roleLabel }}
                    </span>
                </div>
            </div>

            <!-- Active Package Summary Card / Quota Badge -->
            <div class="mt-3 p-2 rounded-3 border {{ $hasActivePlan ? 'border-warning border-opacity-25 bg-black bg-opacity-25' : 'border-danger border-opacity-25 bg-black bg-opacity-25' }}">
                @if($hasActivePlan)
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25" style="font-size: 0.65rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>Active Plan
                        </span>
                        <small class="text-warning fw-bold" style="font-size: 0.7rem;">
                            {{ $daysLeft > 365 ? 'Lifetime' : $daysLeft . 'd left' }}
                        </small>
                    </div>
                    <div class="d-flex justify-content-between text-white-50 small" style="font-size: 0.68rem;">
                        <span>Remaining Quota:</span>
                        <span class="text-light fw-medium">{{ $memoriesLeft > 999 ? '∞' : $memoriesLeft }} Mem | {{ $eventsLeft > 999 ? '∞' : $eventsLeft }} Evt</span>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25" style="font-size: 0.65rem;">
                            <i class="bi bi-lock-fill me-1"></i>No Active Plan
                        </span>
                        <a href="{{ $upgradeUrl }}" class="btn btn-warning btn-sm px-2 py-0 fw-bold text-dark text-decoration-none" style="font-size: 0.68rem;">
                            Buy Package
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <ul class="nav flex-column py-3">

            <!-- Dashboard -->
            @php
                $dashboardActive =
                    request()->routeIs('memoryUser.dashboard') ||
                    request()->is('memory_user/dashboard');
            @endphp
            <li class="nav-item">
                <a href="{{ route('memoryUser.dashboard') }}"
                    class="nav-link {{ $dashboardActive ? 'active' : '' }}">
                    <span>
                        <i class="bi bi-speedometer2 me-2"></i>
                        My Dashboard
                    </span>
                </a>
            </li>

            <!-- Dashboard -->
            @php
                 $myMemoriesActive =
                    request()->routeIs('my_memories') ||
                    request()->is('memory_user/my_memories');
            @endphp
            <li class="nav-item">
                <a href="{{ route('my_memories') }}"
                    class="nav-link {{ $myMemoriesActive ? 'active' : '' }}">
                    <span>
                        <i class="bi bi-speedometer2 me-2"></i>
                        My Memory
                    </span>
                </a>
            </li>

            <!-- Events Dropdown -->
            @php
                $myEventsActive =
                    request()->routeIs('admin.events.my', 'admin.my_events', 'memoryUser.my_events') ||
                    request()->is('admin/my_events*') ||
                    request()->is('memory_user/my_events*') ||
                    request()->is('admin/events/my*');
                $allEventsActive =
                    request()->routeIs('admin.events.all', 'admin.all_events', 'memoryUser.all_events') ||
                    request()->is('admin/all_events*') ||
                    request()->is('memory_user/all_events*') ||
                    request()->is('admin/events/all*');
                $createEventActive =
                    request()->routeIs('admin.events.create', 'admin.create_my_event', 'events.create', 'memoryUser.create_event') ||
                    request()->is('admin/create_my_event*') ||
                    request()->is('admin/events/create*') ||
                    request()->is('memory_user/create_my_event*') ||
                    request()->is('events/create*');
                $eventsActive =
                    $myEventsActive ||
                    $allEventsActive ||
                    $createEventActive ||
                    request()->routeIs('admin.events.*') ||
                    request()->is('admin/events*') ||
                    request()->is('admin/view_my_event*') ||
                    request()->is('admin/edit_my_event*') ||
                    request()->is('memory_user/*event*');
            @endphp

            <li class="nav-item">
                <a href="#eventSubmenu"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="{{ $eventsActive ? 'true' : 'false' }}"
                    aria-controls="eventSubmenu"
                    class="nav-link dropdown-toggle d-flex justify-content-between align-items-center {{ $eventsActive ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-calendar-event me-2"></i>
                        Events
                    </span>

                    <i class="bi bi-chevron-down small dropdown-arrow transition-icon"></i>
                </a>

                <div class="collapse submenu-collapse {{ $eventsActive ? 'show' : '' }}" id="eventSubmenu">
                    <ul class="collapse list-unstyled submenu show py-1 m-0">

                        <li>
                            <a href="{{ route('memoryUser.my_events') }}"
                                class="nav-link sub-link {{ $myEventsActive ? 'active' : '' }}">
                                <i class="bi bi-calendar-check me-2 opacity-75"></i> My Events
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('memoryUser.all_events') }}"
                                class="nav-link sub-link {{ $allEventsActive ? 'active' : '' }}">
                                <i class="bi bi-calendar3 me-2 opacity-75"></i> All Events
                            </a>
                        </li>

                        {{-- Dynamic Package-Gated Create Event Link --}}
                        @if($canCreateEvt)
                            <li>
                                <a href="{{ route('memoryUser.create_event') }}"
                                    class="nav-link sub-link d-flex align-items-center justify-content-between {{ $createEventActive ? 'active' : '' }}">
                                    <span>
                                        <i class="bi bi-calendar-plus-fill me-2 text-warning"></i> Create Event
                                    </span>
                                    <span class="badge bg-warning bg-opacity-25 text-warning" style="font-size: 0.65rem;">
                                        {{ $eventsLeft > 999 ? 'New' : $eventsLeft . ' left' }}
                                    </span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ $upgradeUrl }}"
                                    class="nav-link sub-link d-flex align-items-center justify-content-between text-white-50 opacity-75 text-decoration-none"
                                    title="Active package required to create events">
                                    <span>
                                        <i class="bi bi-lock-fill me-2 text-warning"></i> Create Event
                                    </span>
                                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.65rem;">
                                        Buy Package
                                    </span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </li>

            <!-- Quick Action Buttons -->
            <!-- <li class="nav-item px-3 my-3">
                <div class="p-2 rounded-3 border border-warning border-opacity-25 bg-black bg-opacity-25 text-center">
                    <small class="text-warning text-uppercase fw-bold d-block mb-2" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                        Quick Actions
                    </small>
                    <div class="d-grid gap-2">
                        @if($canCreateMem)
                            <a href="{{ route('memoryUser.create_memory') }}"
                                class="btn btn-warning btn-sm fw-bold text-dark d-flex align-items-center justify-content-center gap-1 shadow-sm">
                                <i class="bi bi-plus-lg"></i> Create Memory
                            </a>
                        @else
                            <a href="{{ $upgradeUrl }}"
                                class="btn btn-outline-warning btn-sm fw-bold d-flex align-items-center justify-content-center gap-1"
                                title="Buy package to create memories">
                                <i class="bi bi-lock-fill"></i> Unlock Memory (Buy Package)
                            </a>
                        @endif

                        @if($canCreateEvt)
                            <a href="{{ route('memoryUser.create_event') }}"
                                class="btn btn-outline-warning btn-sm fw-bold d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-calendar-plus"></i> Create Event
                            </a>
                        @else
                            <a href="{{ $upgradeUrl }}"
                                class="btn btn-outline-secondary btn-sm fw-bold text-light d-flex align-items-center justify-content-center gap-1"
                                title="Buy package to create events">
                                <i class="bi bi-lock-fill text-warning"></i> Unlock Event (Buy Package)
                            </a>
                        @endif
                    </div>
                </div>
            </li> -->

            <!-- Settings -->
            @php
                $settingsActive =
                    request()->routeIs('userProfile_settings') ||
                    request()->is('memory_user/profile_settings*');
            @endphp
            <li class="nav-item">
                <a href="{{ url('memory_user/profile_settings') }}"
                    class="nav-link text-warning {{ $settingsActive ? 'active' : '' }}">
                    <span>
                        <i class="bi bi-gear me-2"></i>
                        Settings
                    </span>
                </a>
            </li>

            <!-- Back to Home -->
            @php
                $homeActive =
                    request()->routeIs('home') ||
                    (request()->is('/') && !request()->is('admin*') && !request()->is('memory_user*'));
            @endphp
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ $homeActive ? 'active' : '' }}">
                    <span>
                        <i class="bi bi-house-door me-2"></i>
                        Back To Home
                    </span>
                </a>
            </li>

        </ul>
    </nav>
