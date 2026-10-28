<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">

        <!-- Sidebar Profile -->
        <div class="sidebar-profile p-3 d-flex align-items-center gap-3">
            @php
                $currentUser = Auth::user();
                $avatarUrl =
                    $currentUser?->avatar_url ??
                    (!empty($currentUser?->avatar)
                        ? (str_starts_with($currentUser->avatar, 'http')
                            ? $currentUser->avatar
                            : asset($currentUser->avatar))
                        : 'https://ui-avatars.com/api/?name=' .
                            urlencode($currentUser?->name ?? 'Admin') .
                            '&background=D4AF37&color=ffffff&bold=true');
                $roleLabel =
                    $currentUser?->role_label ??
                    (!empty($currentUser?->role)
                        ? ucfirst(str_replace('_', ' ', $currentUser->role))
                        : 'Administrator');
            @endphp
            <img src="{{ $avatarUrl }}"
                class="rounded-circle object-fit-cover border border-warning border-opacity-25 flex-shrink-0"
                width="45" height="45" alt="Profile">

            <div class="overflow-hidden">
                <h6 class="m-0 text-white fw-bold text-truncate" style="font-size: 0.9rem;"
                    title="{{ $currentUser?->name ?? 'Guest' }}">
                    {{ $currentUser?->name ?? 'Guest' }}
                </h6>
                <span
                    class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 mt-1 text-truncate"
                    style="font-size: 0.72rem; font-weight: 500;">
                    {{ $roleLabel }}
                </span>
            </div>
        </div>

        <ul class="nav flex-column py-3">

            @if (Auth::user()?->canAccessModule('dashboard') ?? true)
                <!-- My Dashboard -->
                @php
                    $dashboardActive =
                        request()->routeIs('admin.dashboard') ||
                        request()->is('admin/dashboard*') ||
                        request()->is('admin');
                @endphp
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $dashboardActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-speedometer2 me-2"></i>
                            My Dashboard
                        </span>
                    </a>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('memories'))
                <!-- All Memories Dropdown -->
                @php
                    $allMemoriesActive =
                        request()->routeIs('admin.all_memories') || request()->is('admin/all_memories*');
                    $createMemoryActive =
                        request()->routeIs('admin.create_memory') || request()->is('admin/create_memory*');
                    $memoriesActive =
                        $allMemoriesActive ||
                        $createMemoryActive ||
                        request()->routeIs('admin.edit_memory') ||
                        request()->is('admin/edit_memory*') ||
                        request()->is('admin/my_memories*');
                @endphp
                <li class="nav-item">
                    <a href="#memoriesSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $memoriesActive ? 'true' : 'false' }}" aria-controls="memoriesSubmenu"
                        class="nav-link d-flex justify-content-between align-items-center {{ $memoriesActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-collection me-2"></i>
                            All Memories
                        </span>
                        <i class="bi bi-chevron-down small dropdown-arrow transition-icon"></i>
                    </a>

                    <div class="collapse submenu-collapse {{ $memoriesActive ? 'show' : '' }}" id="memoriesSubmenu">
                        <ul class="list-unstyled submenu py-1 m-0">
                            <li>
                                <a href="{{ url('admin/all_memories') }}"
                                    class="nav-link sub-link {{ $allMemoriesActive ? 'active' : '' }}">
                                    All Memories
                                </a>
                            </li>
                            @if (!in_array(Auth::user()?->role, ['super_admin', 'admin']))
                                <li>
                                    <a href="{{ url('admin/create_memory') }}"
                                        class="nav-link sub-link {{ $createMemoryActive ? 'active' : '' }}">
                                        Create Memory
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('events'))
                <!-- Events Dropdown -->
                @php
                    $allEventsActive =
                        request()->routeIs('admin.events.all', 'admin.all_events') ||
                        request()->is('admin/all_events*') ||
                        request()->is('admin/events/all*');
                    $myEventsActive =
                        request()->routeIs('admin.events.my', 'admin.my_events') ||
                        request()->is('admin/my_events*') ||
                        request()->is('admin/events/my*');
                    $createEventActive =
                        request()->routeIs('admin.events.create', 'admin.create_my_event') ||
                        request()->is('admin/create_my_event*') ||
                        request()->is('admin/events/create*');
                    $eventsActive =
                        $allEventsActive ||
                        $myEventsActive ||
                        $createEventActive ||
                        request()->routeIs('admin.events.*', 'admin.edit_my_event', 'admin.view_my_event') ||
                        request()->is('admin/events*') ||
                        request()->is('admin/edit_my_event*') ||
                        request()->is('admin/view_my_event*');
                @endphp
                <li class="nav-item">
                    <a href="#eventSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $eventsActive ? 'true' : 'false' }}" aria-controls="eventSubmenu"
                        class="nav-link d-flex justify-content-between align-items-center {{ $eventsActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-calendar-event me-2"></i>
                            Events
                        </span>
                        <i class="bi bi-chevron-down small dropdown-arrow transition-icon"></i>
                    </a>

                    <div class="collapse submenu-collapse {{ $eventsActive ? 'show' : '' }}" id="eventSubmenu">
                        <ul class="list-unstyled submenu py-1 m-0">
                            <li>
                                <a href="{{ route('admin.events.all') }}"
                                    class="nav-link sub-link {{ $allEventsActive ? 'active' : '' }}">
                                    All Events
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.events.my') }}"
                                    class="nav-link sub-link {{ $myEventsActive ? 'active' : '' }}">
                                    My Events
                                </a>
                            </li>
                            @if (!in_array(Auth::user()?->role, ['super_admin', 'admin']))
                                <li>
                                    <a href="{{ route('admin.events.create') }}"
                                        class="nav-link sub-link {{ $createEventActive ? 'active' : '' }}">
                                        Create Event
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()?->isSuperAdmin())
                <!-- User Management Dropdown (Strictly Super Admin Only) -->
                @php
                    $allUsersActive =
                        request()->routeIs('admin.users.index', 'admin.all_users') ||
                        request()->is('admin/users') ||
                        request()->is('admin/all_users*');
                    $addEmployeeActive =
                        request()->routeIs('admin.employees.create', 'admin.add_employee') ||
                        request()->is('admin/employees/create*') ||
                        request()->is('admin/add_employee*');
                    $viewEmployeeActive =
                        request()->routeIs('admin.view_employee') || request()->is('admin/view_employee*');
                    $sendEmailActive = request()->routeIs('admin.bulkemail') || request()->is('admin/bulkemail*');
                    $usersActive =
                        $allUsersActive ||
                        $addEmployeeActive ||
                        $viewEmployeeActive ||
                        $sendEmailActive ||
                        request()->routeIs('admin.users.*', 'admin.employees.*', 'admin.edit_employee') ||
                        request()->is('admin/users*') ||
                        request()->is('admin/employees*') ||
                        request()->is('admin/edit_user*');
                @endphp
                <li class="nav-item">
                    <a href="#usersSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $usersActive ? 'true' : 'false' }}" aria-controls="usersSubmenu"
                        class="nav-link d-flex justify-content-between align-items-center {{ $usersActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-people me-2"></i>
                            User Management
                        </span>
                        <i class="bi bi-chevron-down small dropdown-arrow transition-icon"></i>
                    </a>

                    <div class="collapse submenu-collapse {{ $usersActive ? 'show' : '' }}" id="usersSubmenu">
                        <ul class="list-unstyled submenu py-1 m-0">
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link sub-link {{ $allUsersActive ? 'active' : '' }}">
                                    All Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.employees.create') }}"
                                    class="nav-link sub-link {{ $addEmployeeActive ? 'active' : '' }}">
                                    Add Employee
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/view_employee') }}"
                                    class="nav-link sub-link {{ $viewEmployeeActive ? 'active' : '' }}">
                                    View Employee
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/bulkemail') }}"
                                    class="nav-link sub-link {{ $sendEmailActive ? 'active' : '' }}">
                                    Send Email
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('payments'))
                <!-- Payments -->
                @php
                    $paymentsActive = request()->routeIs('admin.payments') || request()->is('admin/payments*');
                @endphp
                <li class="nav-item">
                    <a href="{{ url('admin/payments') }}" class="nav-link {{ $paymentsActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-currency-dollar me-2"></i>
                            Payments
                        </span>
                        <i class="bi bi-chevron-right small opacity-50"></i>
                    </a>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('packages'))
                <!-- Packages Dropdown -->
                @php
                    $myPackageActive = request()->routeIs('admin.my_package') || request()->is('admin/my_package*');
                    $createPackageActive =
                        request()->routeIs('admin.create_package') || request()->is('admin/create_package*');
                    $packagesActive =
                        $myPackageActive ||
                        $createPackageActive ||
                        request()->routeIs('admin.view_pakages') ||
                        request()->is('admin/view_pakages*') ||
                        request()->is('admin/packages*');
                @endphp
                <li class="nav-item">
                    <a href="#packagesSubmenu" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ $packagesActive ? 'true' : 'false' }}" aria-controls="packagesSubmenu"
                        class="nav-link d-flex justify-content-between align-items-center {{ $packagesActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-box-seam me-2"></i>
                            Packages
                        </span>
                        <i class="bi bi-chevron-down small dropdown-arrow transition-icon"></i>
                    </a>

                    <div class="collapse submenu-collapse {{ request()->is('admin/view_pakages*') || request()->is('admin/create_package*') || request()->is('admin/packages*') ? 'show' : '' }}"
                        id="packagesSubmenu">
                        <ul class="list-unstyled submenu py-1 m-0">
                            <li>
                                <a href="{{ url('admin/view_pakages') }}"
                                    class="nav-link sub-link {{ request()->is('admin/view_pakages*') ? 'active' : '' }}">
                                    View Packages
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/create_package') }}"
                                    class="nav-link sub-link {{ request()->is('admin/create_package*') || request()->is('admin/packages/create*') ? 'active' : '' }}">
                                    Create Package
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('profile_pages'))
                <!-- Profile Pages -->
                @php
                    $profileActive =
                        request()->routeIs('admin.profile_settings', 'profile.edit') ||
                        request()->is('admin/profile_settings*') ||
                        request()->is('profile*');
                @endphp
                <li class="nav-item">
                    <a href="{{ url('admin/profile_settings') }}"
                        class="nav-link {{ $profileActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-person-badge me-2"></i>
                            Profile Pages
                        </span>
                        <i class="bi bi-chevron-right small opacity-50"></i>
                    </a>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('reports'))
                <!-- Reports -->
                @php
                    $reportsActive = request()->routeIs('admin.reports') || request()->is('admin/reports*');
                @endphp
                <li class="nav-item">
                    <a href="{{ url('admin/reports') }}" class="nav-link {{ $reportsActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-activity me-2"></i>
                            Reports
                        </span>
                        <i class="bi bi-chevron-right small opacity-50"></i>
                    </a>
                </li>
            @endif

            @if (Auth::user()?->canAccessModule('settings'))
                <!-- Settings -->
                @php
                    $settingsActive = request()->is('admin/settings*');
                @endphp
                <li class="nav-item mt-3">
                    <a href="{{ url('admin/settings') }}"
                        class="nav-link text-warning {{ $settingsActive ? 'active' : '' }}">
                        <span>
                            <i class="bi bi-gear me-2"></i>
                            Settings
                        </span>
                        <i class="bi bi-chevron-right small opacity-50"></i>
                    </a>
                </li>
            @endif

            <!-- Back to Home -->
            @php
                $homeActive = request()->routeIs('home') || (request()->is('/') && !request()->is('admin*'));
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
