<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>

    @include('admin.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        @include('admin.header')

        <!-- Page Body -->
        <div class="container-fluid py-4">

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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the following errors:</h6>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Create Event Form Section -->
            <div class="card border-0 shadow-sm bg-white p-4 mb-4">
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" id="createEventForm">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="fw-bold text-dark m-0"><i class="bi bi-plus-circle text-warning me-2"></i> Create New Event</h5>
                    </div>
                    <p class="text-muted small">Fill in the details below to publish your memorial service, annual remembrance, or streaming details.</p>

                    <div class="row g-3">
                        <div class="col-12 col-md-7 mb-2">
                            <label class="form-label small fw-bold">Event Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="e.g., Annual Memorial Alms Giving" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-5 mb-2">
                            <label class="form-label small fw-bold">Event Status Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('event_type') is-invalid @enderror" name="event_type" required>
                                <option value="upcoming" {{ old('event_type', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming Event</option>
                                <option value="live" {{ old('event_type') == 'live' ? 'selected' : '' }}>Live Event (Streaming)</option>
                                <option value="past" {{ old('event_type') == 'past' ? 'selected' : '' }}>Past Event</option>
                            </select>
                            @error('event_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Date & End Date -->
                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label small fw-bold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label small fw-bold">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Time & End Time -->
                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label small fw-bold">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label small fw-bold">End Time</label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time') }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Venue & Map -->
                    <div class="row g-3">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label small fw-bold">Venue Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}" placeholder="e.g., Memorial Hall">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label small fw-bold">Google Map Embed Link</label>
                            <input type="text" class="form-control @error('map_link') is-invalid @enderror" name="map_link" value="{{ old('map_link') }}" placeholder="Paste embed link">
                            @error('map_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Live Stream URL -->
                    <div class="mb-3 live-stream-section">
                        <label class="form-label small fw-bold"><i class="bi bi-camera-video text-danger"></i> Live Broadcast URL</label>
                        <input type="url" class="form-control @error('live_stream_url') is-invalid @enderror" name="live_stream_url" value="{{ old('live_stream_url') }}" placeholder="https://youtube.com/embed/...">
                        @error('live_stream_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Event Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Write details...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Featured Event Image</label>
                        <input type="file" class="form-control event-image-input @error('banner_image') is-invalid @enderror" name="banner_image" id="eventImageInput" accept="image/*">
                        @error('banner_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="event-img-preview-container mt-2 position-relative d-none" id="previewContainer">
                            <img src="" id="imagePreview" class="img-thumbnail object-fit-cover w-100 preview-img" style="max-height: 250px;" alt="Preview">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle remove-event-img-btn" id="removeImgBtn">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-check p-2 bg-light rounded border d-flex align-items-center justify-content-between px-3 h-100">
                                <label class="form-check-label small fw-bold text-dark"><i class="bi bi-heart-fill text-danger me-1"></i> Allow Donations</label>
                                <input class="form-check-input fs-5 m-0" type="checkbox" name="allow_donation" value="1" {{ old('allow_donation', '1') ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <div class="form-check form-switch p-2 bg-light rounded border d-flex align-items-center justify-content-between px-3 h-100">
                                <label class="form-check-label small fw-bold text-dark"><i class="bi bi-lock-fill text-warning me-1"></i> Make Event Private</label>
                                <input class="form-check-input fs-5 m-0" type="checkbox" role="switch" name="is_private" value="1" {{ old('is_private') ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 pt-2 border-top flex-wrap">
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 rounded-pill">
                            <i class="bi bi-check2-circle me-1"></i> Create Event 
                        </button>
                        <a href="{{ route('admin.events.my') }}" class="btn btn-outline-secondary fw-bold px-4 rounded-pill">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

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

        // Image Preview Handler
        const imgInput = document.getElementById('eventImageInput');
        const previewContainer = document.getElementById('previewContainer');
        const imgPreview = document.getElementById('imagePreview');
        const removeBtn = document.getElementById('removeImgBtn');

        if (imgInput) {
            imgInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imgPreview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                imgInput.value = '';
                imgPreview.src = '';
                previewContainer.classList.add('d-none');
            });
        }
    </script>
</body>
</html>