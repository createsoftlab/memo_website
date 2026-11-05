<!-- Navigation Bar with Mobile Offcanvas Slide Menu -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="px-4 container-fluid">
        <a class="navbar-brand me-4" href="{{ url('/') }}">
            <i class="bi bi-fire"></i> Forever Missed
        </a>

        <!-- Offcanvas Mobile Trigger Button -->
        <button class="text-white border-0 navbar-toggler" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <i class="text-white bi bi-list fs-2"></i>
        </button>

        <!-- Slide Drawer (Offcanvas) -->
        <div class="offcanvas offcanvas-end offcanvas-memorial" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="pb-3 offcanvas-header border-bottom border-secondary">
                <h5 class="offcanvas-title text-gold fw-bold" id="offcanvasNavbarLabel">
                    <i class="bi bi-fire me-1"></i> Forever Missed
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                <ul class="mb-3 navbar-nav me-auto mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door"></i> HOME</a>
                    </li>
                    <!-- <li class="nav-item">
              <a class="nav-link" href="{{ url('/all_memories') }}"><i class="bi bi-heart-fill"></i> Memories</a>
            </li> -->

                    @if ($user)

                    @if ($user->role === 'general_user')
                    <!-- Memories Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="text-white nav-link dropdown-toggle" href="{{ url('/all_memories') }}"
                            id="memoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-heart-fill me-1"></i> Memories
                        </a>
                        <ul class="py-2 bg-white border-0 shadow-sm dropdown-menu" aria-labelledby="memoriesDropdown">
                            <li>
                                <a class="py-2 dropdown-item text-dark" href="{{ url('/all_memories') }}">
                                    <i class="bi bi-collection-fill me-2 text-warning"></i> All Memories
                                </a>
                            </li>
                            <li>
                                <hr class="my-1 dropdown-divider">
                            </li>
                            <li>
                                <a class="py-2 dropdown-item text-dark fw-bold" href="{{ url('/create_memory') }}">
                                    <i class="bi bi-plus-circle-fill me-2 text-warning"></i> Create Memory
                                </a>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/all_memories') }}"><i class="bi bi-info-circle"></i> All Memories</a>
                    </li>
                    @endif

                    @else
                    <!-- Memories Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="text-white nav-link dropdown-toggle" href="{{ url('/all_memories') }}"
                            id="memoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-heart-fill me-1"></i> Memories
                        </a>
                        <ul class="py-2 bg-white border-0 shadow-sm dropdown-menu" aria-labelledby="memoriesDropdown">
                            <li>
                                <a class="py-2 dropdown-item text-dark" href="{{ url('/all_memories') }}">
                                    <i class="bi bi-collection-fill me-2 text-warning"></i> All Memories
                                </a>
                            </li>
                            <li>
                                <hr class="my-1 dropdown-divider">
                            </li>
                            <li>
                                <a class="py-2 dropdown-item text-dark fw-bold" href="{{ url('/create_memory') }}">
                                    <i class="bi bi-plus-circle-fill me-2 text-warning"></i> Create Memory
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/about') }}"><i class="bi bi-info-circle"></i> ABOUT
                            US</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/contact') }}"><i class="bi bi-envelope"></i>
                            CONTACT US</a>
                    </li>
                </ul>

                <div
                    class="gap-2 pt-3 mt-auto d-flex flex-column flex-lg-row align-items-lg-center mt-lg-0 pt-lg-0 border-top border-lg-0 border-secondary">
                    @if ($user)

                    @if ($user->role === 'general_user')

                    {{-- Logout Button --}}
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="text-center btn btn-nav-signup w-100">
                            <i class="bi bi-box-arrow-right"></i> LOGOUT
                        </button>
                    </form>

                    @else

                    {{-- Dashboard Button --}}
                    <a href="{{ in_array($user->role, ['admin', 'super_admin'])? route('admin.dashboard'): route('memoryUser.dashboard') }}" class="text-center btn btn-nav-signup">
                        <i class="bi bi-speedometer2"></i> DASHBOARD
                    </a>

                    @endif

                    @else

                    {{-- Login Button --}}
                    <a href="{{ route('login') }}" class="text-center btn btn-nav-login">
                        <i class="bi bi-box-arrow-in-right"></i> LOGIN
                    </a>

                    {{-- Register Button --}}
                    <a href="{{ route('register') }}" class="text-center btn btn-nav-signup">
                        <i class="bi bi-person-plus"></i> SIGN UP
                    </a>

                    @endif

                </div>
            </div>
        </div>
    </div>
</nav>
