<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">

        <!-- Sidebar Profile -->
        <div class="sidebar-profile p-3 d-flex align-items-center gap-3">
            <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?fit=crop&w=100&h=100"
                class="rounded-circle"
                width="45"
                height="45"
                alt="Profile">

            <div>
                <h6 class="m-0 text-white fw-bold" style="font-size: 0.9rem;">
                    {{ auth()->user()?->name ?? $user?->name ?? 'User' }}
                </h6>
                <small class="text-gold" style="font-size: 0.75rem;">
                    {{ auth()->user()?->roles->first()?->name ?? auth()->user()?->created_at?->format('d M Y') ?? '' }}
                </small>
            </div>
        </div>

        <ul class="nav flex-column py-3">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ url('admin/dashboard') }}"
                    class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span>
                        <i class="bi bi-speedometer2 me-2"></i>
                        My Dashboard
                    </span>
                </a>
            </li>

            <!-- All Memories Dropdown -->
            @php
                $memoriesActive = request()->is('admin/create-memory')
                    || request()->is('admin/all_memories');
            @endphp

            <li class="nav-item">
                <a href="#memoriesSubmenu"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $memoriesActive ? 'true' : 'false' }}"
                    class="nav-link dropdown-toggle d-flex justify-content-between align-items-center {{ $memoriesActive ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-collection me-2"></i>
                        All Memories
                    </span>

                    <i class="bi bi-chevron-down small dropdown-arrow"></i>
                </a>

                <ul class="collapse list-unstyled submenu {{ $memoriesActive ? 'show' : '' }}"
                    id="memoriesSubmenu">

                    <li>
                        <a href="{{ url('admin/create_memory') }}"
                            class="nav-link sub-link {{ request()->is('admin/create_memory') ? 'active' : '' }}">
                            My Memories
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/all_memories') }}"
                            class="nav-link sub-link {{ request()->is('admin/all_memories') ? 'active' : '' }}">
                            All Memories
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/create_memory') }}"
                            class="nav-link sub-link {{ request()->is('admin/create_memory') ? 'active' : '' }}">
                            Create Memories
                        </a>
                    </li>

                </ul>
            </li>

            <!-- Events Dropdown -->
            @php
                $eventsActive = request()->is('admin/events*')
                    || request()->is('admin/my_events*')
                    || request()->is('admin/all_events*')
                    || request()->is('admin/create_my_event*')
                    || request()->is('admin/edit_my_event*');
            @endphp

            <li class="nav-item">
                <a href="#eventSubmenu"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $eventsActive ? 'true' : 'false' }}"
                    class="nav-link dropdown-toggle d-flex justify-content-between align-items-center {{ $eventsActive ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-calendar-event me-2"></i>
                        Events
                    </span>

                    <i class="bi bi-chevron-down small dropdown-arrow"></i>
                </a>

                <ul class="collapse list-unstyled submenu {{ $eventsActive ? 'show' : '' }}"
                    id="eventSubmenu">

                    <li>
                        <a href="{{ route('admin.events.my') }}"
                            class="nav-link sub-link {{ request()->routeIs('admin.events.my') || request()->is('admin/my_events*') ? 'active' : '' }}">
                            My Events
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.events.all') }}"
                            class="nav-link sub-link {{ request()->routeIs('admin.events.all') || request()->is('admin/all_events*') ? 'active' : '' }}">
                            All Events
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.events.create') }}"
                            class="nav-link sub-link {{ request()->routeIs('admin.events.create') || request()->is('admin/create_my_event*') ? 'active' : '' }}">
                            Create Events
                        </a>
                    </li>

                </ul>
            </li>

            <!-- User Management Dropdown -->
            @php
                $usersActive = request()->is('admin/all_users')
                    || request()->is('admin/users*')
                    || request()->is('admin/employees*')
                    || request()->is('admin/add_employee')
                    || request()->is('admin/view_employee')
                    || request()->is('admin/bulkemail');
            @endphp

            <li class="nav-item">
                <a href="#usersSubmenu"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $usersActive ? 'true' : 'false' }}"
                    class="nav-link dropdown-toggle d-flex justify-content-between align-items-center {{ $usersActive ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-person me-2"></i>
                        User Management
                    </span>

                    <i class="bi bi-chevron-down small dropdown-arrow"></i>
                </a>

                <ul class="collapse list-unstyled submenu {{ $usersActive ? 'show' : '' }}"
                    id="usersSubmenu">

                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link sub-link {{ request()->is('admin/users*') || request()->is('admin/all_users') ? 'active' : '' }}">
                            All Users
                        </a>
                    </li>

                    @if(!auth()->check() || auth()->user()->can('manage-admins') || auth()->user()->isSuperAdmin())
                        <li>
                            <a href="{{ route('admin.employees.create') }}"
                                class="nav-link sub-link {{ request()->is('admin/employees/create') || request()->is('admin/add_employee') ? 'active' : '' }}">
                                Add Employee
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.employees.index') }}"
                                class="nav-link sub-link {{ (request()->is('admin/employees') && !request()->is('admin/employees/create')) || request()->is('admin/view_employee') ? 'active' : '' }}">
                                View Employee
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ url('admin/bulkemail') }}"
                            class="nav-link sub-link {{ request()->is('admin/bulkemail') ? 'active' : '' }}">
                            Send Email
                        </a>
                    </li>

                </ul>
            </li>

            <!-- Payments -->
            <li class="nav-item">
                <a href="{{ url('admin/payments') }}"
                    class="nav-link {{ request()->is('admin/payments') ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-currency-dollar me-2"></i>
                        Payments
                    </span>

                    <i class="bi bi-chevron-right small"></i>
                </a>
            </li>

            <!-- Packages Dropdown -->
            @php
                $packagesActive = request()->is('admin/my_package')
                    || request()->is('admin/create_package');
            @endphp

            <li class="nav-item">
                <a href="#packagesSubmenu"
                    data-bs-toggle="collapse"
                    aria-expanded="{{ $packagesActive ? 'true' : 'false' }}"
                    class="nav-link dropdown-toggle d-flex justify-content-between align-items-center {{ $packagesActive ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-box-seam me-2"></i>
                        Packages
                    </span>

                    <i class="bi bi-chevron-down small dropdown-arrow"></i>
                </a>

                <ul class="collapse list-unstyled submenu {{ $packagesActive ? 'show' : '' }}"
                    id="packagesSubmenu">

                    <li>
                        <a href="{{ url('admin/my_package') }}"
                            class="nav-link sub-link {{ request()->is('admin/my_package') ? 'active' : '' }}">
                            My Packages
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('admin/create_package') }}"
                            class="nav-link sub-link {{ request()->is('admin/create_package') ? 'active' : '' }}">
                            Create Package
                        </a>
                    </li>

                </ul>
            </li>

            <!-- Profile Pages -->
            <li class="nav-item">
                <a href="{{ url('admin/profile_settings') }}"
                    class="nav-link {{ request()->is('admin/profile_settings') ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-person-badge me-2"></i>
                        Profile Pages
                    </span>

                    <i class="bi bi-chevron-right small"></i>
                </a>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a href="{{ url('admin/reports') }}"
                    class="nav-link {{ request()->is('admin/reports') ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-activity me-2"></i>
                        Reports
                    </span>

                    <i class="bi bi-chevron-right small"></i>
                </a>
            </li>

            <!-- Settings -->
            <li class="nav-item mt-4">
                <a href="{{ url('admin/profile_settings') }}"
                    class="nav-link text-warning {{ request()->is('admin/profile_settings') ? 'active' : '' }}">

                    <span>
                        <i class="bi bi-gear me-2"></i>
                        Settings
                    </span>

                    <i class="bi bi-chevron-right small"></i>
                </a>
            </li>

        </ul>
    </nav>
