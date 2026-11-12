<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Community Uploads - Memorial Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">

    <style>
        /* ==============================
           MEDIA CARDS
        ============================== */

        .media-card {
            height: 350px;
            transition: transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .media-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.09) !important;
        }

        .media-preview {
            height: 225px;
            width: 100%;
            background: #f8f9fa;
            position: relative;
            overflow: hidden;
        }

        .media-image,
        .media-video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .media-video {
            background: #151515;
        }

        .media-play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 3rem;
            pointer-events: none;
            text-shadow: 0 2px 12px rgba(0, 0, 0, .5);
        }

        .audio-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f0e8, #e9dfcf);
        }

        .audio-visual {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: #9c7b3c;
        }

        .audio-visual i {
            font-size: 4rem;
        }

        .audio-visual span {
            font-size: .85rem;
            font-weight: 600;
        }

        .media-card .card-body {
            min-height: 125px;
        }

        .media-card .card-body h6 {
            font-size: .9rem;
        }

        .media-card .btn {
            font-size: .78rem;
            font-weight: 500;
        }

        /* ==============================
           EMPTY STATE
        ============================== */

        #mediaEmptyState {
            min-height: 250px;
        }

        /* ==============================
           CONFIRMATION MODAL
        ============================== */

        .confirm-modal-icon {
            font-size: 3.5rem;
        }

        #mediaConfirmModal .modal-content {
            border-radius: 18px;
        }

        /* ==============================
           GLOBAL TOAST
        ============================== */

        #globalToast {
            min-width: 300px;
            border-radius: 10px;
        }

        .toast-progress-track {
            width: 100%;
            height: 4px;
            background: #e9ecef;
            overflow: hidden;
        }

        .toast-progress-bar {
            width: 100%;
            height: 100%;
            background: #198754;
            transform-origin: left;
            transform: scaleX(1);
        }

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

        @media (max-width: 576px) {
            .media-card {
                height: 350px;
            }
        }
    </style>
</head>

<body>

    @include('memory_user.sidebar')

    <!-- Main Content -->
    <div class="main-content">

        @include('memory_user.header')

        <!-- Page Body -->
        <div class="container-fluid py-4 overflow-hidden">

            <!-- Header -->
            <div class="row align-items-center mb-4 g-3">

                <div class="col-xl-4 col-lg-4 col-12">

                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-collection text-warning me-2"></i>
                        Community Uploads
                    </h4>

                    <p class="text-muted small m-0">
                        Manage media contributed by the community
                    </p>

                </div>

                <!-- Filter -->
                <div class="col-xl-8 col-lg-8 col-12">

                    <div class="row g-2 justify-content-lg-end">

                        <div class="col-xl-4 col-lg-4 col-md-5 col-sm-6 col-12">

                            <div class="dropdown w-100">

                                <button
                                    class="btn btn-sm btn-white bg-white border w-100
                                    d-flex justify-content-between align-items-center
                                    shadow-sm text-muted dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="font-size: .85rem; padding: 8px 12px;">

                                    <span id="selectedMediaType">
                                        <i class="bi bi-image me-2"></i>Images
                                    </span>

                                </button>

                                <ul class="dropdown-menu w-100 shadow border-0">

                                    <li>
                                        <a class="dropdown-item media-filter active" href="#" data-type="image">

                                            <i class="bi bi-image me-2"></i>
                                            Images ({{ $photos->count() }})

                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item media-filter" href="#" data-type="video">

                                            <i class="bi bi-camera-video me-2"></i>
                                            Videos ({{ $videos->count() }})

                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item media-filter" href="#" data-type="audio">

                                            <i class="bi bi-music-note-beamed me-2"></i>
                                            Audios ({{ $audios->count() }})

                                        </a>
                                    </li>

                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Grid -->
            <div class="row g-4" id="mediaGrid">

                <!-- ==============================
                     IMAGE CARDS
                ============================== -->

                @foreach ($photos as $photo)
                    <div class="col-xxl-3 col-xl-4 col-md-6 col-12 media-item" data-type="image">

                        <div
                            class="card border-0 shadow-sm rounded-4
                            overflow-hidden bg-white media-card">

                            <div class="media-preview">

                                <img src="{{ asset($photo->file_path) }}" alt="Community uploaded image" loading="lazy"
                                    class="media-image">

                                <span
                                    class="badge bg-warning text-dark
                                    position-absolute top-0 start-0 m-2">

                                    <i class="bi bi-clock me-1"></i>
                                    Pending

                                </span>

                                <span
                                    class="badge bg-dark position-absolute
                                    top-0 end-0 m-2">

                                    <i class="bi bi-image"></i>

                                </span>

                            </div>

                            <div class="card-body p-3 d-flex flex-column">

                                <h6 class="fw-semibold text-dark text-truncate mb-1">
                                    {{ $photo->file_name ?? 'Community Image' }}
                                </h6>

                                <p class="text-muted small mb-3">

                                    <i class="bi bi-calendar3 me-1"></i>

                                    {{ $photo->created_at?->format('M d, Y') }}

                                </p>

                                <div class="d-flex gap-2 mt-auto">

                                    <button type="button" class="btn btn-outline-success btn-sm flex-fill"
                                        data-action="approve" data-media-id="{{ $photo->id }}"
                                        data-media-type="image" onclick="confirmMediaAction(this)">

                                        <i class="bi bi-check-circle me-1"></i>
                                        Approve

                                    </button>

                                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill"
                                        data-action="delete" data-media-id="{{ $photo->id }}" data-media-type="image"
                                        onclick="confirmMediaAction(this)">

                                        <i class="bi bi-trash me-1"></i>
                                        Delete

                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach


                <!-- ==============================
                     VIDEO CARDS
                ============================== -->

                @foreach ($videos as $video)
                    <div class="col-xxl-3 col-xl-4 col-md-6 col-12 media-item d-none" data-type="video">

                        <div
                            class="card border-0 shadow-sm rounded-4
                            overflow-hidden bg-white media-card">

                            <div class="media-preview">

                                <video class="media-video" preload="metadata" controls playsinline>

                                    <source src="{{ asset($video->file_path) }}"
                                        type="{{ $video->mime_type ?? 'video/mp4' }}">

                                </video>


                                <span
                                    class="badge bg-warning text-dark
                                    position-absolute top-0 start-0 m-2">

                                    <i class="bi bi-clock me-1"></i>
                                    Pending

                                </span>

                                <span
                                    class="badge bg-dark position-absolute
                                    top-0 end-0 m-2">

                                    <i class="bi bi-camera-video"></i>

                                </span>

                            </div>

                            <div class="card-body p-3 d-flex flex-column">

                                <h6 class="fw-semibold text-dark text-truncate mb-1">
                                    {{ $video->file_name ?? 'Community Video' }}
                                </h6>

                                <p class="text-muted small mb-3">

                                    <i class="bi bi-calendar3 me-1"></i>

                                    {{ $video->created_at?->format('M d, Y') }}

                                </p>

                                <div class="d-flex gap-2 mt-auto">

                                    <button type="button" class="btn btn-outline-success btn-sm flex-fill"
                                        data-action="approve" data-media-id="{{ $video->id }}"
                                        data-media-type="video" onclick="confirmMediaAction(this)">

                                        <i class="bi bi-check-circle me-1"></i>
                                        Approve

                                    </button>

                                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill"
                                        data-action="delete" data-media-id="{{ $video->id }}"
                                        data-media-type="video" onclick="confirmMediaAction(this)">

                                        <i class="bi bi-trash me-1"></i>
                                        Delete

                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach


                <!-- ==============================
                     AUDIO CARDS
                ============================== -->

                @foreach ($audios as $audio)
                    <div class="col-xxl-3 col-xl-4 col-md-6 col-12 media-item d-none" data-type="audio">

                        <div
                            class="card border-0 shadow-sm rounded-4
                            overflow-hidden bg-white media-card">

                            <div class="media-preview audio-preview">

                                <div class="audio-visual w-100 px-3">

                                    <i class="bi bi-music-note-beamed"></i>

                                    <span>Audio Recording</span>

                                    <audio controls preload="metadata" class="w-100 mt-3">

                                        <source src="{{ asset($audio->file_path) }}"
                                            type="{{ $audio->mime_type ?: 'audio/mpeg' }}">

                                        Your browser does not support audio playback.

                                    </audio>

                                </div>

                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                    <i class="bi bi-clock me-1"></i> Pending
                                </span>

                                <span class="badge bg-dark position-absolute top-0 end-0 m-2">
                                    <i class="bi bi-soundwave"></i>
                                </span>

                            </div>

                            <div class="card-body p-3 d-flex flex-column">

                                <h6 class="fw-semibold text-dark text-truncate mb-1">
                                    {{ $audio->file_name ?? 'Community Audio' }}
                                </h6>

                                <p class="text-muted small mb-3">

                                    <i class="bi bi-calendar3 me-1"></i>

                                    {{ $audio->created_at?->format('M d, Y') }}

                                </p>

                                <div class="d-flex gap-2 mt-auto">

                                    <button type="button" class="btn btn-outline-success btn-sm flex-fill"
                                        data-action="approve" data-media-id="{{ $audio->id }}"
                                        data-media-type="audio" onclick="confirmMediaAction(this)">

                                        <i class="bi bi-check-circle me-1"></i>
                                        Approve

                                    </button>

                                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill"
                                        data-action="delete" data-media-id="{{ $audio->id }}"
                                        data-media-type="audio" onclick="confirmMediaAction(this)">

                                        <i class="bi bi-trash me-1"></i>
                                        Delete

                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Empty State -->
            <div id="mediaEmptyState" class="text-center py-5 d-none">

                <i id="emptyMediaIcon" class="bi bi-image text-muted" style="font-size: 3rem;"></i>

                <h5 class="fw-semibold mt-3" id="emptyMediaTitle">
                    No Images Found
                </h5>

                <p class="text-muted small">
                    There are currently no pending community uploads
                    in this category.
                </p>

            </div>

        </div>
    </div>


    <!-- ==============================
         APPROVE / DELETE CONFIRMATION MODAL
    ============================== -->

    <div class="modal fade" id="mediaConfirmModal" tabindex="-1" aria-labelledby="confirmModalTitle"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header border-0 pb-0">

                    <h5 class="modal-title fw-bold" id="confirmModalTitle">
                        Confirm Action
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body text-center py-4">

                    <i id="confirmModalIcon" class="bi bi-question-circle text-warning confirm-modal-icon">
                    </i>

                    <h5 class="fw-semibold mt-3" id="confirmModalMessage">
                        Are you sure?
                    </h5>

                    <p class="text-muted small mb-0" id="confirmModalDescription">
                    </p>

                </div>

                <div class="modal-footer border-0 justify-content-center pb-4">

                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        No
                    </button>

                    <form id="mediaActionForm" method="POST">

                        @csrf

                        <button type="submit" id="confirmActionButton" class="btn btn-success px-4">
                            Yes, Approve
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>


    <!-- ==============================
         GLOBAL TOAST
    ============================== -->

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11000;">

        <div id="globalToast" class="toast border-0 shadow overflow-hidden" role="alert" aria-live="assertive"
            aria-atomic="true">

            <div class="toast-header">

                <i id="globalToastIcon" class="bi bi-check-circle-fill text-success me-2"></i>

                <strong class="me-auto" id="globalToastTitle">
                    Success
                </strong>

                <small>Just now</small>

                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>

            </div>

            <div class="toast-body" id="globalToastMessage"></div>

            <div class="toast-progress-track">
                <div id="globalToastProgress" class="toast-progress-bar"></div>
            </div>

        </div>
    </div>


    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Sidebar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebar) {

                sidebarToggle.addEventListener('click', function() {

                    sidebar.classList.toggle('show');

                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('show');
                    }

                });
            }

            if (sidebarOverlay && sidebar) {

                sidebarOverlay.addEventListener('click', function() {

                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');

                });
            }

        });
    </script>


    <!-- Media Filter JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const filters = document.querySelectorAll('.media-filter');
            const mediaItems = document.querySelectorAll('.media-item');

            const selectedMediaType = document.getElementById('selectedMediaType');
            const emptyState = document.getElementById('mediaEmptyState');
            const emptyTitle = document.getElementById('emptyMediaTitle');
            const emptyIcon = document.getElementById('emptyMediaIcon');

            const mediaTypes = {
                image: {
                    label: 'Images',
                    icon: 'bi bi-image'
                },
                video: {
                    label: 'Videos',
                    icon: 'bi bi-camera-video'
                },
                audio: {
                    label: 'Audios',
                    icon: 'bi bi-music-note-beamed'
                }
            };

            function filterMedia(type) {

                let visibleCount = 0;

                mediaItems.forEach(function(item) {

                    if (item.dataset.type === type) {

                        item.classList.remove('d-none');
                        visibleCount++;

                    } else {

                        item.classList.add('d-none');

                    }

                });

                const selected = mediaTypes[type];

                selectedMediaType.innerHTML =
                    '<i class="' + selected.icon + ' me-2"></i>' +
                    selected.label;

                emptyTitle.textContent =
                    'No ' + selected.label + ' Found';

                emptyIcon.className =
                    selected.icon + ' text-muted';

                if (visibleCount === 0) {

                    emptyState.classList.remove('d-none');

                } else {

                    emptyState.classList.add('d-none');

                }

                filters.forEach(function(filter) {

                    filter.classList.toggle(
                        'active',
                        filter.dataset.type === type
                    );

                });
            }

            filters.forEach(function(filter) {

                filter.addEventListener('click', function(event) {

                    event.preventDefault();

                    filterMedia(this.dataset.type);

                });

            });

            // Default category
            filterMedia('image');

        });
    </script>


    <!-- Approve / Delete: AJAX without page reload -->
    <script>
        const mediaRoutes = {
            approve: @json(route('communityMedia.approve', ['type' => '__TYPE__', 'id' => '__ID__'])),
            delete: @json(route('communityMedia.delete', ['type' => '__TYPE__', 'id' => '__ID__']))
        };
        let pendingMediaButton = null;

        function confirmMediaAction(element) {
            const action = element.dataset.action;
            if (!['approve', 'delete'].includes(action)) return;
            pendingMediaButton = element;
            const approving = action === 'approve';
            document.getElementById('confirmModalTitle').textContent = approving ? 'Approve Media' : 'Delete Media';
            document.getElementById('confirmModalMessage').textContent = approving
                ? 'Are you sure you want to approve this media?'
                : 'Are you sure you want to delete this media?';
            document.getElementById('confirmModalDescription').textContent = approving
                ? 'This media will become approved and visible on the memorial.'
                : 'This action will permanently delete the media file and database record.';
            document.getElementById('confirmModalIcon').className = approving
                ? 'bi bi-check-circle text-success confirm-modal-icon'
                : 'bi bi-trash text-danger confirm-modal-icon';
            const confirmButton = document.getElementById('confirmActionButton');
            confirmButton.className = approving ? 'btn btn-success px-4' : 'btn btn-danger px-4';
            confirmButton.textContent = approving ? 'Yes, Approve' : 'Yes, Delete';
            bootstrap.Modal.getOrCreateInstance(document.getElementById('mediaConfirmModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('mediaActionForm').addEventListener('submit', async function (event) {
                event.preventDefault();
                if (!pendingMediaButton) return;

                const item = pendingMediaButton.closest('.media-item');
                const {action, mediaId, mediaType} = pendingMediaButton.dataset;
                const url = mediaRoutes[action]
                    .replace('__TYPE__', encodeURIComponent(mediaType))
                    .replace('__ID__', encodeURIComponent(mediaId));
                const submitButton = document.getElementById('confirmActionButton');
                submitButton.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    const result = await response.json();
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Unable to process the request.');
                    }
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('mediaConfirmModal')).hide();
                    item.remove();
                    const filter = document.querySelector('.media-filter[data-type="' + mediaType + '"]');
                    if (filter) {
                        const remaining = document.querySelectorAll('.media-item[data-type="' + mediaType + '"]').length;
                        const label = {image: 'Images', video: 'Videos', audio: 'Audios'}[mediaType];
                        filter.innerHTML = '<i class="bi ' + {image: 'bi-image', video: 'bi-camera-video', audio: 'bi-music-note-beamed'}[mediaType] + ' me-2"></i>' + label + ' (' + remaining + ')';
                    }
                    if (!document.querySelector('.media-item:not(.d-none)')) {
                        document.getElementById('mediaEmptyState').classList.remove('d-none');
                    }
                    window.showGlobalToast(result.message, 'success');
                } catch (error) {
                    window.showGlobalToast(error.message || 'Request failed.', 'error');
                } finally {
                    submitButton.disabled = false;
                    pendingMediaButton = null;
                }
            });
        });
    </script>


    <!-- Global Toast JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const toastElement = document.getElementById('globalToast');
            const progressBar = document.getElementById('globalToastProgress');

            if (!toastElement || !progressBar ||
                typeof bootstrap === 'undefined') {
                return;
            }

            const toast = bootstrap.Toast.getOrCreateInstance(toastElement, {
                animation: true,
                autohide: true,
                delay: 3000
            });

            let progressTimer = null;

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

            window.showGlobalToast = function(message, type = 'success') {

                const config = types[type] || types.success;

                document.getElementById('globalToastTitle').textContent =
                    config.title;

                document.getElementById('globalToastMessage').textContent =
                    message;

                document.getElementById('globalToastIcon').className =
                    config.icon;

                progressBar.style.backgroundColor = config.color;

                progressBar.classList.remove('running');

                void progressBar.offsetWidth;

                progressBar.classList.add('running');

                clearTimeout(progressTimer);

                toast.show();

                progressTimer = setTimeout(function() {
                    toast.hide();
                }, 3000);
            };

            // Laravel session messages
            @if (session('success'))
                window.showGlobalToast(@json(session('success')), 'success');
            @elseif (session('error'))
                window.showGlobalToast(@json(session('error')), 'error');
            @elseif (session('warning'))
                window.showGlobalToast(@json(session('warning')), 'warning');
            @elseif (session('info'))
                window.showGlobalToast(@json(session('info')), 'info');
            @elseif ($errors->any())
                window.showGlobalToast(@json($errors->first()), 'error');
            @endif

        });
    </script>

</body>

</html>
