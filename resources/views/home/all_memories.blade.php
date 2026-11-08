<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forever Missed - Memorial Directory</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/memories.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
</head>

<body>

    @include('home.header')


    <!-- Hero Header -->



    <!-- Hero Header with Background Music & Toggle Button -->
    <header class="memorial-hero d-flex align-items-center text-white mb-4 position-relative">
        <div class="container py-3">

            <!-- Top Row: Breadcrumb & Audio Control Button -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                class="text-warning text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/all_memories') }}"
                                class="text-warning text-decoration-none">All Memories</a></li>
                        <!-- <li class="breadcrumb-item active text-white-50" aria-current="page">Memory Name</li> -->
                    </ol>
                </nav>

                <!-- Background Music Toggle Button -->
                <button id="audioToggleBtn"
                    class="btn btn-outline-warning btn-sm rounded-pill px-3 py-1 d-flex align-items-center gap-2 bg-dark bg-opacity-50"
                    onclick="toggleBackgroundMusic()" title="Toggle Background Music">
                    <i id="audioIcon" class="bi bi-volume-up-fill fs-6 text-warning"></i>
                    <span id="audioText" class="small">Mute Music</span>
                </button>
            </div>

            <!-- Header Content -->
            <div class="text-center text-lg-start">
                <h3 class="fw-bold mb-1">{{ $visitorCountry ?: 'Country' }}</h3>
                <span class="badge bg-warning text-dark mt-1">Memories</span>
            </div>

        </div>

        <!-- Hidden HTML5 Audio Element (Autoplay without muted) -->
        <audio id="memorialAudio" autoplay loop>
            <source src="{{ asset('memo_website_dev_frontend/assets/audio/sad.mp3') }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </header>



    <!-- Main Content -->
    <main class="container pb-5">
        <div class="tab-content" id="memorialTabsContent">

            <!-- Directory Tab -->
            <div class="tab-pane fade show active" id="directory" role="tabpanel">
                <div class="card p-4 rounded-3 shadow-sm border-0 bg-white">

                    <!-- Filter Section -->

                    <!-- Filter Section -->
                    <div class="border-bottom pb-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="fw-bold text-dark mb-0">
                                <i class="bi bi-person-lines-fill me-2 text-warning"></i>Memorial Directory
                            </h4>
                            <span
                                class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-semibold">{{ $totalMemories > 0 ? $totalMemories : 'No' }}
                                Records</span>
                        </div>

                        <div class="row g-2">

                            <!-- Search Input -->
                            <div class="col-12 col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>

                                    <input type="text" id="memorialSearch" class="form-control border-start-0 ps-0"
                                        placeholder="Search by name...">
                                </div>
                            </div>

                            <!-- Passed Away Reason Dropdown -->
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="dropdown w-100">

                                    <button
                                        class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border"
                                        type="button" data-bs-toggle="dropdown">
                                        <span id="selectedCategory">All Categories</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </button>

                                    <ul class="dropdown-menu w-100 shadow-sm" style="max-height:280px;overflow-y:auto;">

                                        <li>
                                            <button type="button" class="dropdown-item category-option" data-value="">
                                                All Categories
                                            </button>
                                        </li>

                                        @foreach (['Natural Causes', 'Old Age', 'Cancer', 'Heart Disease', 'Heart Attack', 'Stroke', 'COVID-19', 'Other Illness', 'Accident', 'Road Accident', 'Natural Disaster', 'Suicide', 'Homicide', 'Other', 'Unknown', 'Prefer Not to Disclose'] as $reason)
                                            <li>
                                                <button type="button" class="dropdown-item category-option"
                                                    data-value="{{ $reason }}">
                                                    {{ $reason }}
                                                </button>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>

                            <!-- Country Dropdown -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="dropdown w-100">

                                    <button
                                        class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary bg-white border"
                                        type="button" data-bs-toggle="dropdown">
                                        <span id="selectedCountry">All Countries</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </button>

                                    <ul class="dropdown-menu w-100 shadow-sm" id="countryDropdown"
                                        style="max-height:280px;overflow-y:auto;">

                                        <!-- JavaScript generates country options -->

                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>


                    <section class="py-5">
                        <div class="container">

                            <!-- Row 1 (Cards 1, 2, 3) - First Scroll Animation -->
                            <div class="row g-4 scroll-animate mb-4">

                                @forelse ($memories as $memory)
                                    <!-- Card 1 -->
                                    <div class="col-lg-4 col-md-6 grid-item memorial-item"
                                        style="--i: {{ ($loop->index % 3) + 1 }};"
                                        data-name="{{ trim(
                                            implode(
                                                ' ',
                                                array_filter([$memory->profile->first_name, $memory->profile->middle_name, $memory->profile->last_name]),
                                            ),
                                        ) }}"
                                        data-category="{{ $memory->profile->passed_away_reason }}"
                                        data-country="{{ $memory->profile->country }}">
                                        <div class="card h-100 border-0 shadow-sm memorial-grid-card">
                                            <div class="position-relative overflow-hidden rounded-top">
                                                @if ($memory->profile->privacy === 'public')
                                                <img src="{{ asset($memory->profile?->images->first()?->path ?? 'images/profile_photo.png') }}"
                                                    class="card-img-top memorial-grid-img" alt="Memorial Profile"
                                                    style="width: 100%; height: 220px; object-fit: contain; object-position: center; background-color: #f5f5f5;">
                                                @else
                                                <img src="{{ asset('images/profile_photo.png') }}"
                                                    class="card-img-top memorial-grid-img" alt="Memorial Profile"
                                                    style="width: 100%; height: 220px; object-fit: contain; object-position: center; background-color: #f5f5f5;">
                                                @endif
                                                <span
                                                    class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2">{{ $memory->profile?->passed_away_reason }}
                                                </span>
                                            </div>
                                            <div class="card-body p-3 d-flex flex-column">
                                                @if ($memory->profile->privacy === 'private')
                                                    <h6 class="fw-bold text-dark mb-1">
                                                        {{ $memory->profile?->access_username }}</h6>
                                                @else
                                                    <h6 class="fw-bold text-dark mb-1">
                                                        {{ $memory->profile?->first_name }}
                                                        {{ $memory->profile?->middle_name }}
                                                        {{ $memory->profile?->last_name }}</h6>
                                                @endif
                                                @if ($memory->profile?->date_of_birth && $memory->profile?->date_of_death)
                                                    <p class="small text-muted mb-2">
                                                        {{ $memory->profile?->date_of_birth }}
                                                        {{ $memory->profile?->date_of_death }} •
                                                @endif
                                                {{ $memory->profile?->country }}</p>

                                                {{-- <a href="#"
                                                class="small text-warning text-decoration-none text-truncate mb-2">
                                                <i class="bi bi-link-45deg"></i> ivan-e-ramirez.forevermissed.com
                                            </a> --}}
                                                @if ($memory->profile->privacy === 'public')
                                                <p class="small text-secondary mb-3 text-truncate-3">
                                                    {{ Str::limit($memory?->about, 100) }}
                                                </p>
                                                @endif

                                                @if ($memory->profile->privacy === 'private')
                                                    <button type="button"
                                                        class="btn btn-outline-memorial btn-sm mt-auto w-100 fw-semibold"
                                                        data-bs-toggle="modal" data-bs-target="#privateMemorialModal"
                                                        data-memorial-slug="{{ $memory->profile->slug }}"
                                                        data-memorial-name="{{ trim($memory->profile->first_name . ' ' . $memory->profile->last_name) }}">

                                                        <i class="bi bi-lock-fill me-1"></i>View Memorial
                                                    </button>
                                                @else
                                                    <a href="{{ url('/memory_index/' . $memory->profile->slug) }}"
                                                        class="btn btn-outline-memorial btn-sm mt-auto w-100 fw-semibold">

                                                        View Memorial
                                                    </a>
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                @empty

                                    <div id="noMemoriesMessage" class="col-12 text-center py-5 d-none">
                                        <i class="bi bi-journal-x display-4 text-muted"></i>
                                        <h5 class="mt-3 text-muted">No Memories Found</h5>
                                        <p class="small text-muted">
                                            No memorials match your selected filters.
                                        </p>
                                    </div>
                                @endforelse
                                <div id="noMemoriesMessage" class="col-12 text-center py-5 d-none">
                                    <i class="bi bi-journal-x display-4 text-muted"></i>
                                    <h5 class="mt-3 text-muted">No Memories Found</h5>
                                    <p class="small text-muted">
                                        No memorials match your selected filters.
                                    </p>
                                </div>

                            </div>

                        </div>
                    </section>

                    <!-- Pagination -->
                    <nav id="memorialPagination"
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-5 pt-3 border-top gap-3"
                        aria-label="Memorial Directory Pagination">

                        <span class="small text-muted" id="paginationInfo"></span>

                        <ul class="pagination pagination-sm mb-0" id="paginationLinks">
                        </ul>

                    </nav>

                </div>
            </div>

        </div>


        <!-- Demo Modal -->

        <!-- Private Memorial Modal -->
        <div class="modal fade" id="privateMemorialModal" tabindex="-1" aria-labelledby="privateMemorialModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark" id="privateMemorialModalLabel">
                            <i class="bi bi-shield-lock text-warning me-2"></i>
                            Private Access Required
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center py-4">

                        <div class="mb-3">
                            <i class="bi bi-lock text-warning display-4"></i>
                        </div>

                        <h6 class="fw-bold mb-1" id="privateMemorialName"></h6>

                        <p class="small text-muted mb-4">
                            Do you have permission to view this memorial?
                        </p>

                        <form id="privateAccessForm">

                            @csrf

                            <input type="hidden" id="privateMemorialSlug" name="slug">

                            <div class="mb-3 text-start">

                                <label for="memorialPassword" class="form-label small fw-semibold text-secondary">
                                    Enter Passcode
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-key"></i>
                                    </span>

                                    <input type="password" class="form-control" id="memorialPassword"
                                        name="password" placeholder="Enter password..." required>

                                    <button type="button" class="btn btn-outline-secondary"
                                        id="toggleMemorialPassword">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                                <div id="passwordError" class="form-text text-danger d-none">
                                    Incorrect password. Please try again.
                                </div>

                            </div>

                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mt-2"
                                id="accessMemorialBtn">

                                Access Memorial
                                <i class="bi bi-arrow-right-short"></i>

                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Password Access Modal -->
        <div class="modal fade" id="privateMemorialModal" tabindex="-1" aria-labelledby="privateMemorialModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark" id="privateMemorialModalLabel">
                            <i class="bi bi-shield-lock text-warning me-2"></i>Private Access Required
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-lock text-warning display-4"></i>
                        </div>
                        <h6 class="fw-bold mb-1">David Arthur Smith</h6>
                        <p class="small text-muted mb-4">Do you have permission to view this memorial?</p>

                        <form id="privateAccessForm" onsubmit="event.preventDefault(); accessMemorial();">
                            <div class="mb-3 text-start">
                                <label for="memorialPassword"
                                    class="form-label small fw-semibold text-secondary">Enter
                                    Passcode</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="memorialPassword"
                                        placeholder="Enter password..." required>
                                </div>
                                <div id="passwordError" class="form-text text-danger d-none">Incorrect
                                    password. Please try again.</div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mt-2">
                                Access Memorial <i class="bi bi-arrow-right-short"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-gold fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                    <p class="small text-light-tan leading-relaxed">
                        A dignified digital platform dedicated to preserving legacies, connecting families, and honoring
                        cherished memories forever.
                    </p>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                        <li><a href="{{ url('/') }}" class="text-light-tan text-decoration-none">Home</a></li>
                        <li><a href="{{ url('/all_memories') }}"
                                class="text-light-tan text-decoration-none">Memories</a></li>
                        <li><a href="{{ url('/about') }}" class="text-light-tan text-decoration-none">About Us</a>
                        </li>
                        <li><a href="{{ url('/contact') }}" class="text-light-tan text-decoration-none">Contact
                                Us</a></li>
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
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i
                                class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <hr class="border-secondary">

            <div class="text-center small text-white-50 pt-2">
                <p class="mb-0">&copy; 2026 Forever Missed Memorial Directory. All rights reserved.</p>
            </div>
        </div>
    </footer>


    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==========================================
            // CONFIGURATION
            // ==========================================

            const ITEMS_PER_PAGE = 6;

            const visitorCountry = @json($visitorCountry ?? '');

            const searchInput = document.getElementById('memorialSearch');
            const selectedCategoryText = document.getElementById('selectedCategory');
            const selectedCountryText = document.getElementById('selectedCountry');

            const countryDropdown = document.getElementById('countryDropdown');
            const paginationLinks = document.getElementById('paginationLinks');
            const paginationInfo = document.getElementById('paginationInfo');
            const paginationNav = document.getElementById('memorialPagination');
            const noMemoriesMessage = document.getElementById('noMemoriesMessage');

            const allMemories = Array.from(
                document.querySelectorAll('.memorial-item')
            );

            let currentPage = 1;
            let selectedCategory = '';
            let selectedCountry = visitorCountry.trim();

            // ==========================================
            // NORMALIZE TEXT
            // ==========================================

            function normalize(value) {
                return String(value || '')
                    .trim()
                    .toLowerCase();
            }

            // ==========================================
            // GENERATE COUNTRY DROPDOWN
            // ==========================================

            function generateCountryDropdown() {

                const countries = [...new Set(
                    allMemories
                    .map(item => item.dataset.country?.trim())
                    .filter(Boolean)
                )].sort((a, b) => a.localeCompare(b));

                countryDropdown.innerHTML = '';

                const allOption = document.createElement('li');
                const allButton = document.createElement('button');

                allButton.type = 'button';
                allButton.className = 'dropdown-item country-option';
                allButton.dataset.value = '';
                allButton.textContent = 'All Countries';

                allOption.appendChild(allButton);
                countryDropdown.appendChild(allOption);

                countries.forEach(country => {

                    const li = document.createElement('li');
                    const button = document.createElement('button');

                    button.type = 'button';
                    button.className = 'dropdown-item country-option';
                    button.dataset.value = country;
                    button.textContent = country;

                    li.appendChild(button);
                    countryDropdown.appendChild(li);

                });

                // Keep visitor country available even if no memorials exist there
                if (selectedCountry &&
                    !countries.some(country =>
                        normalize(country) === normalize(selectedCountry)
                    )) {

                    const li = document.createElement('li');
                    const button = document.createElement('button');

                    button.type = 'button';
                    button.className = 'dropdown-item country-option';
                    button.dataset.value = selectedCountry;
                    button.textContent = selectedCountry;

                    li.appendChild(button);
                    countryDropdown.appendChild(li);
                }
            }

            // ==========================================
            // UPDATE DROPDOWN LABELS
            // ==========================================

            function updateDropdownLabels() {

                selectedCategoryText.textContent =
                    selectedCategory || 'All Categories';

                selectedCountryText.textContent =
                    selectedCountry || 'All Countries';

                document.querySelectorAll('.category-option').forEach(option => {
                    option.classList.toggle(
                        'active',
                        normalize(option.dataset.value) === normalize(selectedCategory)
                    );
                });

                document.querySelectorAll('.country-option').forEach(option => {
                    option.classList.toggle(
                        'active',
                        normalize(option.dataset.value) === normalize(selectedCountry)
                    );
                });
            }

            // ==========================================
            // FILTER MEMORIES
            // ==========================================

            function getFilteredMemories() {

                const search = normalize(searchInput.value);

                return allMemories.filter(item => {

                    const name = normalize(item.dataset.name);
                    const category = normalize(item.dataset.category);
                    const country = normalize(item.dataset.country);

                    const matchesName =
                        search === '' || name.includes(search);

                    const matchesCategory =
                        selectedCategory === '' ||
                        category === normalize(selectedCategory);

                    const matchesCountry =
                        selectedCountry === '' ||
                        country === normalize(selectedCountry);

                    return matchesName &&
                        matchesCategory &&
                        matchesCountry;
                });
            }

            // ==========================================
            // RENDER PAGINATION
            // ==========================================

            function renderPagination(totalItems, totalPages) {

                paginationLinks.innerHTML = '';

                if (totalItems === 0) {
                    paginationInfo.textContent = 'Showing 0 of 0 Memorials';
                    paginationNav.classList.add('d-none');
                    return;
                }

                paginationNav.classList.remove('d-none');

                const start = (currentPage - 1) * ITEMS_PER_PAGE + 1;
                const end = Math.min(currentPage * ITEMS_PER_PAGE, totalItems);

                paginationInfo.innerHTML =
                    `Showing <strong>${start} - ${end}</strong> of ` +
                    `<strong>${totalItems}</strong> Memorials`;

                function addPage(label, page, disabled = false, active = false) {

                    const li = document.createElement('li');
                    li.className = 'page-item';

                    if (disabled) li.classList.add('disabled');
                    if (active) li.classList.add('active');

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'page-link';
                    button.textContent = label;
                    button.disabled = disabled;

                    if (active) {
                        button.setAttribute('aria-current', 'page');
                    }

                    button.addEventListener('click', function() {
                        if (disabled || active) return;

                        currentPage = page;
                        renderMemories();
                    });

                    li.appendChild(button);
                    paginationLinks.appendChild(li);
                }

                // Previous
                addPage('«', currentPage - 1, currentPage === 1);

                // Page numbers
                for (let page = 1; page <= totalPages; page++) {
                    addPage(
                        page,
                        page,
                        false,
                        page === currentPage
                    );
                }

                // Next
                addPage(
                    '»',
                    currentPage + 1,
                    currentPage === totalPages
                );
            }

            // ==========================================
            // RENDER MEMORIES
            // ==========================================

            function renderMemories() {

                const filteredMemories = getFilteredMemories();

                const totalItems = filteredMemories.length;

                const totalPages = Math.max(
                    1,
                    Math.ceil(totalItems / ITEMS_PER_PAGE)
                );

                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }

                const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
                const endIndex = startIndex + ITEMS_PER_PAGE;

                // Hide all cards
                allMemories.forEach(item => {
                    item.classList.add('d-none');
                });

                // Show current page cards
                filteredMemories
                    .slice(startIndex, endIndex)
                    .forEach(item => {
                        item.classList.remove('d-none');
                    });

                // Empty state
                noMemoriesMessage.classList.toggle(
                    'd-none',
                    totalItems !== 0
                );

                renderPagination(totalItems, totalPages);
                updateDropdownLabels();
            }

            // ==========================================
            // NAME SEARCH
            // ==========================================

            searchInput.addEventListener('input', function() {
                currentPage = 1;
                renderMemories();
            });

            // ==========================================
            // CATEGORY FILTER
            // ==========================================

            document.querySelectorAll('.category-option').forEach(option => {

                option.addEventListener('click', function() {

                    selectedCategory = this.dataset.value || '';

                    currentPage = 1;
                    renderMemories();

                });

            });

            // ==========================================
            // COUNTRY FILTER
            // ==========================================

            countryDropdown.addEventListener('click', function(event) {

                const option = event.target.closest('.country-option');

                if (!option) return;

                selectedCountry = option.dataset.value || '';

                currentPage = 1;
                renderMemories();

            });

            // ==========================================
            // INITIALIZE
            // ==========================================

            generateCountryDropdown();
            renderMemories();

        });
    </script>
    <!--
  <script>
      function createFlower() {
          const flower = document.createElement('div');
          flower.classList.add('falling-flower');

          // මල් සඳහා භාවිත කළ හැකි Emoji (නැතහොත් පින්තූරයක් භාවිත කළ හැක)
          const flowers = ['🌸', '🌺', '🌹', '🌼', '🌷'];
          flower.innerText = flowers[Math.floor(Math.random() * flowers.length)];

          // Random ස්ථානයක් සහ ප්‍රමාණය තීරණය කිරීම
          flower.style.left = Math.random() * 100 + 'vw';
          flower.style.animationDuration = Math.random() * 3 + 2 + 's'; // තත්පර 2-5 අතර වේගය
          flower.style.fontSize = Math.random() * 10 + 15 + 'px'; // ප්‍රමාණය 15px-25px

          document.body.appendChild(flower);

          // වැටී අවසන් වූ පසු DOM එකෙන් ඉවත් කිරීම
          setTimeout(() => {
              flower.remove();
          }, 5000);
      }

      // තත්පර 0.3 කට වරක් අලුත් මලක් සෑදීම
      setInterval(createFlower, 300);
  </script> -->


    <script>
        function startFlowerEffect() {

            const flowerInterval = setInterval(createFlower, 300);


            setTimeout(() => {
                clearInterval(flowerInterval);
            }, 5000);
        }

        function createFlower() {
            const flower = document.createElement('div');
            flower.classList.add('falling-flower');


            const flowers = ['🌸', '🌺', '🌹', '🌼', '🌷'];
            flower.innerText = flowers[Math.floor(Math.random() * flowers.length)];


            flower.style.left = Math.random() * 100 + 'vw';
            flower.style.animationDuration = Math.random() * 3 + 2 + 's';
            flower.style.fontSize = Math.random() * 10 + 15 + 'px';

            document.body.appendChild(flower);


            setTimeout(() => {
                flower.remove();
            }, 5000);
        }


        window.addEventListener('DOMContentLoaded', startFlowerEffect);
    </script>


    <!--password check script -->


    <script>
        function checkDemoPassword() {
            const pwd = document.getElementById('demoPassword').value;
            const err = document.getElementById('demoError');

            // Demo Password
            if (pwd === '1234') {
                err.classList.add('d-none');
                window.location.href = "{{ url('/memories_index') }}"; // Click move to page 
            } else {
                err.classList.remove('d-none');
            }
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('demoPassword');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>

    <!--ply music -->

    <!-- <script>
        function toggleBackgroundMusic() {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            if (audio.paused) {
                audio.play().then(() => {

                    audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                    audioText.innerText = "Mute Music";
                }).catch(error => {
                    console.log("Audio autoplay restricted by browser:", error);
                });
            } else {
                audio.pause();

                audioIcon.className = "bi bi-volume-mute-fill fs-6";
                audioText.innerText = "Play Music";
            }
        }
    </script> -->


    <!-- audio script -->

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            const isMuted = localStorage.getItem('memorialAudioMuted');

            if (isMuted === 'false') {

                audio.muted = false;
                audio.play().catch(error => console.log("Autoplay restricted"));
                audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                audioText.innerText = "Mute Music";
            } else {

                audio.muted = true;
                audio.play().catch(error => console.log("Autoplay restricted"));
                audioIcon.className = "bi bi-volume-mute-fill fs-6";
                audioText.innerText = "Play Music";
            }
        });


        function toggleBackgroundMusic() {
            const audio = document.getElementById('memorialAudio');
            const audioIcon = document.getElementById('audioIcon');
            const audioText = document.getElementById('audioText');

            if (audio.muted) {
                audio.muted = false;
                audio.play();
                audioIcon.className = "bi bi-volume-up-fill fs-6 text-warning";
                audioText.innerText = "Mute Music";
                localStorage.setItem('memorialAudioMuted', 'false');
            } else {
                audio.muted = true;
                audioIcon.className = "bi bi-volume-mute-fill fs-6";
                audioText.innerText = "Play Music";
                localStorage.setItem('memorialAudioMuted', 'true');
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.40 // s
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("active");
                        observer.unobserve(entry.target); // end observe
                    }
                });
            }, observerOptions);

            // .scroll-animate class එක තියෙන හැම section එකක්ම එකතු කරගන්නවා
            const animatedSections = document.querySelectorAll(".scroll-animate");
            animatedSections.forEach(sec => observer.observe(sec));
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const modal = document.getElementById("privateMemorialModal");
            const form = document.getElementById("privateAccessForm");

            if (!modal || !form) return;

            const memorialName =
                document.getElementById("privateMemorialName");

            const memorialSlug =
                document.getElementById("privateMemorialSlug");

            const passwordInput =
                document.getElementById("memorialPassword");

            const passwordError =
                document.getElementById("passwordError");

            const submitBtn =
                document.getElementById("accessMemorialBtn");

            const toggleBtn =
                document.getElementById("toggleMemorialPassword");


            // ==========================================
            // 1. OPEN MODAL WITH MEMORIAL DETAILS
            // ==========================================

            modal.addEventListener("show.bs.modal", function(event) {

                const button = event.relatedTarget;

                if (!button) return;

                memorialName.textContent =
                    button.getAttribute("data-memorial-name") || "";

                memorialSlug.value =
                    button.getAttribute("data-memorial-slug") || "";

                passwordInput.value = "";
                passwordInput.type = "password";

                passwordError.classList.add("d-none");

                toggleBtn.querySelector("i").className = "bi bi-eye";

                submitBtn.disabled = false;

            });


            // ==========================================
            // 2. SHOW / HIDE PASSWORD
            // ==========================================

            toggleBtn.addEventListener("click", function() {

                const icon = this.querySelector("i");

                if (passwordInput.type === "password") {

                    passwordInput.type = "text";
                    icon.className = "bi bi-eye-slash";

                } else {

                    passwordInput.type = "password";
                    icon.className = "bi bi-eye";

                }

            });


            // ==========================================
            // 3. CHECK PASSWORD USING LARAVEL
            // ==========================================

            form.addEventListener("submit", async function(event) {

                event.preventDefault();

                passwordError.classList.add("d-none");

                submitBtn.disabled = true;
                submitBtn.textContent = "Checking...";

                try {

                    const response = await fetch(
                        "{{ route('memorial.verifyPassword') }}", {
                            method: "POST",

                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": form.querySelector('[name="_token"]').value
                            },

                            credentials: "same-origin",

                            body: JSON.stringify({
                                slug: memorialSlug.value,
                                password: passwordInput.value
                            })
                        }
                    );

                    const data = await response.json();

                    // ==========================================
                    // CORRECT PASSWORD
                    // ==========================================

                    if (response.ok && data.success) {

                        window.location.href = data.redirect;
                        return;
                    }

                    // ==========================================
                    // INCORRECT PASSWORD
                    // ==========================================

                    passwordError.textContent =
                        data.message ||
                        data.errors?.password?.[0] ||
                        "Incorrect password. Please try again.";

                    passwordError.classList.remove("d-none");

                    passwordInput.focus();

                } catch (error) {

                    passwordError.textContent =
                        "Something went wrong. Please try again.";

                    passwordError.classList.remove("d-none");

                } finally {

                    submitBtn.disabled = false;

                    submitBtn.innerHTML =
                        'Access Memorial <i class="bi bi-arrow-right-short"></i>';

                }

            });

        });
    </script>

</body>

</html>
