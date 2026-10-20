<!-- Top Navbar -->
    <nav class="navbar navbar-dark top-navbar px-3 py-2">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-light d-lg-none btn-sm" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <span class="navbar-brand mb-0 h6 text-gold font-serif ms-1">Dashboard</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small d-none d-sm-inline">{{ $user?->name ?? 'Guest' }}</span>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit"
                            class="btn btn-sm btn-warning fw-bold text-dark px-3">
                        Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="btn btn-sm btn-warning fw-bold text-dark px-3 text-decoration-none">
                    Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>