    <!-- Navigation Bar with Mobile Offcanvas Slide Menu -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand me-4" href="{{ url('/') }}">
                <i class="bi bi-fire"></i> Forever Missed
            </a>

            <!-- Offcanvas Mobile Trigger Button -->
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <i class="bi bi-list text-white fs-2"></i>
            </button>

            <!-- Slide Drawer (Offcanvas) -->
            <div class="offcanvas offcanvas-end offcanvas-memorial" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header border-bottom border-secondary pb-3">
                    <h5 class="offcanvas-title text-gold fw-bold" id="offcanvasNavbarLabel">
                        <i class="bi bi-fire me-1"></i> Forever Missed
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div
                    class="offcanvas-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                    <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door"></i> HOME</a>
                        </li>
                        <!-- <li class="nav-item">
              <a class="nav-link" href="{{ url('/all_memories') }}"><i class="bi bi-heart-fill"></i> Memories</a>
            </li> -->


                        <!-- Memories Dropdown Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="{{ url('/all_memories') }}"
                                id="memoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-heart-fill me-1"></i> Memories
                            </a>
                            <ul class="dropdown-menu shadow-sm border-0 bg-white py-2"
                                aria-labelledby="memoriesDropdown">
                                <li>
                                    <a class="dropdown-item py-2 text-dark" href="{{ url('/all_memories') }}">
                                        <i class="bi bi-collection-fill me-2 text-warning"></i> All Memories
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 text-dark fw-bold" href="{{ url('/create_memory') }}">
                                        <i class="bi bi-plus-circle-fill me-2 text-warning"></i> Create Memory
                                    </a>
                                </li>
                            </ul>
                        </li>


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
                        class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mt-auto mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0 border-secondary">
                        @if ($user)
                            <a href="{{ in_array($user->role, ['admin', 'super_admin']) ? route('admin.dashboard') : route('memoryUser.dashboard') }}"
                                class="btn btn-nav-signup text-center">
                                <i class="bi bi-speedometer2"></i> DASHBOARD
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-nav-login text-center">
                                <i class="bi bi-box-arrow-in-right"></i> LOGIN
                            </a>

                            <a href="{{ route('register') }}" class="btn btn-nav-signup text-center">
                                <i class="bi bi-person-plus"></i> SIGN UP
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
