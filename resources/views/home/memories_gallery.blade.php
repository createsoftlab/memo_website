<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery - {{ $profile->first_name }} {{ $profile->last_name }} Memorial</title>
  
  <!-- Bootstrap 5 CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Custom Stylesheets -->
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/gallery.css') }}">

  <!-- Consistent layout for both server-rendered and AJAX-added gallery items -->
  <style>
    /* Explicit CSS grid prevents theme styles or Bootstrap columns from shifting cards. */
    #galleryPhotosGrid { display: grid !important; grid-template-columns: repeat(3, minmax(0, 1fr)) !important; gap: 16px !important; margin: 0 !important; width: 100% !important; align-items: stretch !important; }
    #galleryPhotosGrid > .gallery-photo-col { display: block !important; width: 100% !important; max-width: none !important; min-width: 0 !important; flex: none !important; padding: 0 !important; margin: 0 !important; position: static !important; float: none !important; transform: none !important; clear: none !important; }
    #galleryPhotosGrid .gallery-item-wrapper { display: block !important; position: relative !important; width: 100% !important; height: 260px !important; overflow: hidden !important; margin: 0 !important; padding: 0 !important; transform: none !important; opacity: 1 !important; visibility: visible !important; }
    #galleryPhotosGrid .gallery-item-wrapper img.gallery-thumb { display: block !important; width: 100% !important; height: 100% !important; max-width: none !important; max-height: none !important; object-fit: cover !important; object-position: center !important; float: none !important; position: static !important; margin: 0 !important; transform: none !important; }
    #galleryPhotosGrid .gallery-item-wrapper .badge { z-index: 3 !important; }
    #galleryPhotosGrid > [data-gallery-empty] { grid-column: 1 / -1 !important; width: 100% !important; }
    @media (max-width: 767.98px) { #galleryPhotosGrid { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; } #galleryPhotosGrid .gallery-item-wrapper { height: 200px !important; } }

    /* Selected photo thumbnails: fill their cards without empty lower space. */
    #imagePreviewContainer { display:grid !important; grid-template-columns:repeat(4,minmax(0,1fr)) !important; gap:10px !important; margin-left:0 !important; margin-right:0 !important; align-items:start !important; }
    #imagePreviewContainer > .col { display:block !important; width:100% !important; max-width:none !important; min-width:0 !important; padding:0 !important; margin:0 !important; flex:none !important; }
    #imagePreviewContainer .preview-thumb-card { position:relative !important; width:100% !important; height:150px !important; min-height:0 !important; padding:0 !important; margin:0 !important; overflow:hidden !important; border-radius:10px !important; background:#f8f9fa; }
    #imagePreviewContainer .preview-thumb-card > img { display:block !important; width:100% !important; height:100% !important; max-width:none !important; max-height:none !important; object-fit:cover !important; object-position:center !important; margin:0 !important; padding:0 !important; position:static !important; }
    #imagePreviewContainer .delete-thumb-btn { position:absolute !important; top:6px !important; right:6px !important; z-index:5 !important; width:26px !important; height:26px !important; border:0 !important; border-radius:50% !important; background:rgba(0,0,0,.7) !important; color:white !important; display:flex !important; align-items:center !important; justify-content:center !important; padding:0 !important; }
    @media (max-width:575.98px) { #imagePreviewContainer { grid-template-columns:repeat(2,minmax(0,1fr)) !important; } }
    /* Standalone Stories-style notification: avoids site-wide .toast/.alert overrides. */
    #fmGalleryNoticeHost { position:fixed !important; top:16px !important; right:16px !important; z-index:2147483000 !important; width:min(380px,calc(100vw - 32px)) !important; pointer-events:none !important; }
    #fmGalleryNotice { display:none; width:100% !important; background:#fff !important; color:#212529 !important; border-radius:8px !important; overflow:hidden !important; box-shadow:0 8px 25px rgba(0,0,0,.18) !important; border:1px solid #e9ecef !important; pointer-events:auto !important; font-family:system-ui,-apple-system,Segoe UI,sans-serif !important; }
    #fmGalleryNotice.fm-visible { display:block !important; animation:fmNoticeIn .18s ease-out both; }
    #fmGalleryNotice .fm-notice-header { display:flex !important; align-items:center !important; gap:8px !important; padding:10px 14px !important; background:#fff !important; border-bottom:1px solid #eee !important; color:#212529 !important; }
    #fmGalleryNotice .fm-notice-title { font-weight:700 !important; font-size:14px !important; flex:1; }
    #fmGalleryNotice .fm-notice-close { border:0 !important; background:transparent !important; color:#666 !important; cursor:pointer !important; font-size:20px !important; line-height:1 !important; padding:0 2px !important; }
    #fmGalleryNotice .fm-notice-body { padding:12px 14px !important; background:#fff !important; color:#343a40 !important; font-size:14px !important; white-space:pre-line !important; }
    #fmGalleryNotice .fm-notice-track { height:3px !important; background:#e9ecef !important; }
    #fmGalleryNotice .fm-notice-bar { height:100% !important; width:100%; background:#198754 !important; transition:none; }
    @keyframes fmNoticeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
  </style>
</head>
<body class="bg-memorial-light">

       @include('home.header')

  @php

    // $coverImage = \App\Models\Image::where('profile_id', $memory->profile?->id)->where('type', 'cover')->first();
    $backgroundMusic = \App\Models\Music::where('profile_id', $memory->profile?->id)->first();
    // Get profile image from images table
    $profileImage = \App\Models\Image::where('profile_id', $memory->profile?->id)
        ->where('type', 'profile')
        ->first();

    $birthYear = $profile->date_of_birth
        ? \Carbon\Carbon::parse($profile->date_of_birth)->format('Y')
        : null;

    $deathYear = $profile->date_of_death
        ? \Carbon\Carbon::parse($profile->date_of_death)->format('Y')
        : null;
  @endphp

  @php
    $canUpload = auth()->check() && (int) auth()->id() === (int) $profile->user_id;
    $visiblePhotos = $canUpload ? $photos : $photos->where('status', 1);
    $visibleVideos = $canUpload ? $videos : $videos->where('status', 1);
    $visibleAudios = $canUpload ? $audios : $audios->where('status', 1);
  @endphp

  <!-- Hero Header -->
  <header class="memorial-hero d-flex align-items-end">
    <div class="container py-4">
      <div class="row align-items-center">
        <div class="col-8">
          <h1 class="memorial-title text-white mb-0">{{ $memory->profile?->first_name }} {{ $memory->profile?->last_name }}</h1>
          @if ($birthYear || $deathYear)
          <p class="fs-4 text-white-50 font-serif mb-0">
            {{ collect([$birthYear, $deathYear])->filter()->implode(' - ') }}
          </p>
          @endif
        </div>
        <div class="col-4 text-end">
          <img src="{{ $profileImage?->path ? asset($profileImage->path) : asset('images/profile_photo.png') }}" class="memorial-avatar rounded-3 shadow" alt="Memorial profile">
        </div>
      </div>
    </div>
          @if ($backgroundMusic?->path) 
        <!-- Background Music Toggle Button -->
        <button id="audioToggleBtn"
            class="btn btn-outline-warning btn-sm rounded-pill px-3 py-1 d-flex align-items-center gap-2 bg-dark bg-opacity-50"
            onclick="toggleBackgroundMusic()" title="Toggle Background Music">
            <i id="audioIcon" class="bi bi-volume-up-fill fs-6 text-warning"></i>
            <span id="audioText" class="small">Mute Music</span>
        </button>
                                                                                           
        <!-- Hidden HTML5 Audio Element (Autoplay without muted) -->
        <audio id="memorialAudio" autoplay loop>
            <source src="{{ asset($backgroundMusic->path) }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
        @endif
  </header>

  <!-- Navigation Tabs Bar -->
  <div class="memorial-tabs-bar sticky-top">
    <div class="container">
      <ul class="nav nav-tabs border-0">
        <li class="nav-item"><a class="nav-link" href="{{ url('/memory_index/' . $memory->profile->slug) }}">ABOUT</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_life/' . $memory->profile->slug) }}">LIFE</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ url('/memories_gallery/' . $memory->profile->slug) }}">GALLERY</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_stories/' . $memory->profile->slug) }}">STORIES</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/memories_timeline/'. $memory->profile->slug) }}">TIMELINE</a></li>
        <li class="nav-item"><a class="nav-link " href="{{ url('/memories_event/'. $memory->profile->slug) }}">EVENTS</a></li>
       <li class="nav-item"><a class="nav-link " href="{{ url('/memories_donation/' . $memory->profile->slug) }}">DONATIONS</a></li>
      </ul>
    </div>
  </div>

  <!-- Main Content Layout -->
  <main class="container my-4">
    <div class="row g-4">
      
      <!-- Left Column: Gallery Tabs & Upload Section -->
      <div class="col-lg-8 d-flex flex-column gap-4">

        <!-- Main Gallery Container Card -->
        <div class="sidebar-card p-4 rounded shadow-sm bg-memorial-card">
          
          <!-- Inner Sub-Tabs Header (PHOTO, MOVIE, AUDIO) -->
          <ul class="nav nav-tabs border-bottom border-secondary-subtle mb-4 flex-nowrap justify-content-around text-uppercase fw-bold text-center" id="gallerySubTabs" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
              <button class="nav-link active sub-tab-link w-100" id="photo-tab" data-bs-toggle="tab" data-bs-target="#photo-tab-pane" type="button" role="tab">PHOTO</button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
              <button class="nav-link sub-tab-link w-100" id="movie-tab" data-bs-toggle="tab" data-bs-target="#movie-tab-pane" type="button" role="tab">MOVIE</button>
            </li>
            <li class="nav-item flex-fill" role="presentation">
              <button class="nav-link sub-tab-link w-100" id="audio-tab" data-bs-toggle="tab" data-bs-target="#audio-tab-pane" type="button" role="tab">AUDIO</button>
            </li>
          </ul>

          <!-- Tab Content Area -->
          <div class="tab-content" id="gallerySubTabContent">
            
            <!-- PHOTO TAB -->
            <div class="tab-pane fade show active" id="photo-tab-pane" role="tabpanel" tabindex="0">
              <!-- Action Control Buttons -->
              @if($canUpload)
              <div class="d-flex gap-2 mb-4">
             <button class="btn btn-add-photos btn-sm font-monospace fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2" onclick="document.getElementById('upload-section').scrollIntoView({behavior: 'smooth'});">
    <i class="bi bi-image-fill me-1"></i> Add photos
</button>
                <!-- <button class="btn btn-outline-memorial-dark btn-sm font-monospace fw-semibold">
                  <i class="bi bi-play-circle me-1"></i> Start slideshow
                </button> -->
              </div>

              @endif
              <!-- Photos Grid (Responsive 3 Columns) -->
              <div id="galleryPhotosGrid">
                @forelse ($visiblePhotos as $photo)
                <div class="gallery-photo-col">
                  <div class="gallery-item-wrapper rounded overflow-hidden shadow-sm position-relative">
                    @if((int) $photo->status === 0)
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 z-1">Pending Approval</span>
                    @endif
                    <img src="{{ asset($photo->file_path) }}" class="img-fluid gallery-thumb" alt="{{ $photo->file_name }}">
                  </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 text-muted" data-gallery-empty> No photos uploaded yet.</div>
                @endforelse
              </div>


              <br>
 @if($canUpload)
 <!-- Add Media Dynamic Upload Section Box -->
<div class="sidebar-card p-4 rounded-4 shadow-sm bg-white border border-light reveal-card mb-4" id="upload-section">
  <h5 class="fw-bold text-dark mb-3" id="upload-section-title">Add Photos</h5>

  <!-- Upgrade Banner Notice -->
  <div class="upgrade-banner p-3 rounded-3 mb-4 d-flex align-items-center gap-2 bg-light border">
    <i class="bi bi-star-fill text-warning fs-5"></i>
    <div class="small text-secondary">
      Upgrade your website to enable unlimited gallery uploads. <a href="#" class="text-memorial-accent text-decoration-none fw-semibold">View benefits and pricing</a>
    </div>
  </div>

  <!-- Drag and Drop Dropzone -->
  <div class="upload-dropzone rounded-4 p-4 text-center mb-3 d-flex flex-column align-items-center justify-content-center border border-2 border-dashed position-relative" id="dropzoneArea">
    <input type="file" id="mediaFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" multiple accept=".jpg,.jpeg,.png,.webp,.gif" style="cursor: pointer;">
    <div class="dropzone-content py-3">
      <i class="bi bi-cloud-arrow-up fs-1 text-memorial mb-2"></i>
      <p class="small text-muted mb-0">
        <span class="text-memorial fw-bold">Choose photo files</span> or drag and drop to upload
      </p>
    </div>
  </div>

  <!-- Loading State (Hidden by default) -->
  <div id="uploadLoader" class="text-center py-3 d-none">
    <div class="spinner-border text-memorial spinner-border-sm me-2" role="status"></div>
    <span class="small text-muted fw-semibold">Uploading photos to space...</span>
  </div>

  <!-- Preview Grid for Selected Images -->
  <div id="imagePreviewContainer" class="row g-2 mb-3 row-cols-3 row-cols-sm-4">
    <!-- Image previews will appear here dynamically -->
  </div>

  <!-- Single Centered Submit Button -->
  <div class="text-center mt-3">
    <button type="button" class="btn btn-memorial px-5 py-2 fw-bold rounded-pill shadow-sm opacity-50" id="submitImagesBtn" disabled>
      <i class="bi bi-check2-circle me-1"></i> Add Image
    </button>
  </div>
</div>
@endif
</div>


            <!-- MOVIE TAB -->
            <div class="tab-pane fade" id="movie-tab-pane" role="tabpanel" tabindex="0">
              @if($canUpload)
              <div class="d-flex gap-2 mb-4">
                <button class="btn btn-add-photos btn-sm font-monospace fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2" onclick="document.getElementById('videoupload-section').scrollIntoView({behavior: 'smooth'});">
                  <i class="bi bi-plus-square me-1"></i> Add Video 
                </button>
              </div>
              @endif
              <div class="row g-3" id="galleryVideosGrid">
                @forelse($visibleVideos as $video)
                  <div class="col-12 col-md-6">
                    <div class="rounded border bg-white p-2 shadow-sm">
                      <video controls preload="metadata" class="w-100 rounded" style="max-height:250px;" src="{{ asset($video->file_path) }}"></video>
                      <p class="small text-muted text-truncate mb-0">{{ $video->file_name }}</p>
                      @if((int) $video->status === 0)<span class="badge bg-warning text-dark">Pending Approval</span>@endif
                    </div>
                  </div>
                @empty
                  <div class="col-12 text-center py-5 text-muted bg-white rounded border border-dashed" data-gallery-empty>No videos uploaded yet.</div>
                @endforelse
              </div>

              <br>

  @if($canUpload)
  <!-- Add Video Dynamic Upload Section Box -->
<div class="sidebar-card p-4 rounded-4 shadow-sm bg-white border border-light reveal-card mb-4" id="videoupload-section">
  <h5 class="fw-bold text-dark mb-3" id="upload-section-title">Add videos</h5>

  <!-- Upgrade Banner Notice -->
  <div class="upgrade-banner p-3 rounded-3 mb-4 d-flex align-items-center gap-2 bg-light border">
    <i class="bi bi-star-fill text-warning fs-5"></i>
    <div class="small text-secondary">
      Upgrade your website to enable unlimited gallery uploads. <a href="#" class="text-memorial-accent text-decoration-none fw-semibold">View benefits and pricing</a>
    </div>
  </div>

  <!-- Drag and Drop Dropzone (Video specific IDs) -->
  <div class="upload-dropzone rounded-4 p-4 text-center mb-3 d-flex flex-column align-items-center justify-content-center border border-2 border-dashed position-relative" id="videoDropzoneArea">
    <input type="file" id="videoFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" multiple accept=".mp4,.mov,.webm" style="cursor: pointer;">
    <div class="dropzone-content py-3">
      <i class="bi bi-cloud-arrow-up fs-1 text-memorial mb-2"></i>
      <p class="small text-muted mb-0">
        <span class="text-memorial fw-bold">Choose video files</span> or drag and drop to upload
      </p>
    </div>
  </div>

  <!-- Loading State (Hidden by default) -->
  <div id="videoUploadLoader" class="text-center py-3 d-none">
    <div class="spinner-border text-memorial spinner-border-sm me-2" role="status"></div>
    <span class="small text-muted fw-semibold">Uploading video to space...</span>
  </div>

  <!-- Preview Grid for Selected Videos -->
  <div id="videoPreviewContainer" class="row g-2 mb-3 row-cols-3 row-cols-sm-4">
    <!-- Video previews will appear here dynamically -->
  </div>

  <!-- Single Centered Submit Button -->
  <div class="text-center mt-3">
    <button type="button" class="btn btn-memorial px-5 py-2 fw-bold rounded-pill shadow-sm opacity-50" id="submitVideoBtn" disabled>
      <i class="bi bi-check2-circle me-1"></i> Add Video 
    </button>
  </div>
</div>
            @endif

            </div>

       <!-- AUDIO TAB -->
<div class="tab-pane fade" id="audio-tab-pane" role="tabpanel" tabindex="0">
  
  @if($canUpload)
  <!-- Add Audio Top Button -->
  <div class="d-flex gap-2 mb-4">
    <button class="btn btn-add-photos btn-sm font-monospace fw-semibold d-inline-flex align-items-center gap-1 shadow-sm px-3 py-2" onclick="document.getElementById('audio-upload-section').scrollIntoView({behavior: 'smooth'});">
      <i class="bi bi-music-note-beamed me-1"></i> Add audio
    </button>
  </div>

  @endif
  <div class="row g-3 mb-4" id="galleryAudiosGrid">
    @forelse($visibleAudios as $audio)
      <div class="col-12 col-md-6"><div class="p-3 border rounded bg-white shadow-sm">
        <p class="small text-truncate">{{ $audio->file_name }}</p>
        @if((int) $audio->status === 0)<span class="badge bg-warning text-dark mb-2">Pending Approval</span>@endif
        <audio controls preload="none" class="w-100" src="{{ asset($audio->file_path) }}"></audio>
      </div></div>
    @empty
      <div class="col-12 text-center py-5 text-muted" data-gallery-empty>No audio files uploaded yet.</div>
    @endforelse
  </div>
  @if($canUpload)
  <!-- Add Audio Dynamic Upload Section Box -->
  <div class="sidebar-card p-4 rounded-4 shadow-sm bg-white border border-light reveal-card mb-4" id="audio-upload-section">
    <h5 class="fw-bold text-dark mb-3" id="audio-section-title">Add audio</h5>

    <!-- Upgrade Banner Notice -->
    <div class="upgrade-banner p-3 rounded-3 mb-4 d-flex align-items-center gap-2 bg-light border">
      <i class="bi bi-star-fill text-warning fs-5"></i>
      <div class="small text-secondary">
        Upgrade your website to enable unlimited audio uploads. <a href="#" class="text-memorial-accent text-decoration-none fw-semibold">View benefits and pricing</a>
      </div>
    </div>

    <!-- Drag and Drop Dropzone (Audio specific IDs) -->
    <div class="upload-dropzone rounded-4 p-4 text-center mb-3 d-flex flex-column align-items-center justify-content-center border border-2 border-dashed position-relative" id="audioDropzoneArea">
      <input type="file" id="audioFileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" multiple accept=".mp3,.wav,.ogg,.m4a" style="cursor: pointer;">
      <div class="dropzone-content py-3">
        <i class="bi bi-cloud-arrow-up fs-1 text-memorial mb-2"></i>
        <p class="small text-muted mb-0">
          <span class="text-memorial fw-bold">Choose audio files</span> or drag and drop to upload
        </p>
      </div>
    </div>

    <!-- Loading State (Hidden by default) -->
    <div id="audioUploadLoader" class="text-center py-3 d-none">
      <div class="spinner-border text-memorial spinner-border-sm me-2" role="status"></div>
      <span class="small text-muted fw-semibold">Uploading audio to space...</span>
    </div>

    <!-- Preview Grid for Selected Audio Files -->
    <div id="audioPreviewContainer" class="row g-2 mb-3 row-cols-1 row-cols-sm-2">
      <!-- Audio previews/players will appear here dynamically -->
    </div>

    <!-- Single Centered Submit Button -->
    <div class="text-center mt-3">
      <button type="button" class="btn btn-memorial px-5 py-2 fw-bold rounded-pill shadow-sm opacity-50" id="submitAudioBtn" disabled>
        <i class="bi bi-check2-circle me-1"></i> Add Audio 
      </button>
    </div>
  </div>
  @endif

</div>
     
          </div>

        </div>

      </div>

      <!-- Right Column: Sidebar Panels -->
      <div class="col-lg-4 d-flex flex-column gap-3">

        <!-- Invite & Donate Card -->
        <div class="main-content-card p-3 rounded">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="fw-bold mb-0 me-2 text-dark">Invite {{ $profile->first_name }}'s family and friends</h6>
            <i class="bi bi-person-add fs-4 text-muted"></i>
          </div>
          <button class="btn btn-invite w-100 py-2 mb-2">
            <i class="bi bi-person-plus me-1"></i> Invite now
          </button>
          <button class="btn btn-donate w-100 py-2">
            <i class="bi bi-heart-fill me-1"></i> Donate now
          </button>
        </div>

        <!-- Share Button -->
        <button class="btn btn-facebook w-100 py-2 d-flex align-items-center justify-content-center gap-2" id="shareFbBtn">
          <i class="bi bi-facebook fs-5 text-primary"></i>
          <span>Share on Facebook</span>
        </button>

        <!-- Notification Preferences Card -->
        <!-- <div class="sidebar-card p-3 rounded">
          <h6 class="fw-bold mb-1 text-dark">Notification preferences</h6>
          <p class="small text-muted mb-3" id="subStatus">You are not subscribed</p>
          <button class="btn btn-outline-memorial-dark w-100 py-2" id="subscribeBtn">Subscribe</button>
        </div> -->

        <!-- Photos Spotlight Card -->
        <div class="main-content-card p-3 rounded">
          <h6 class="fw-bold mb-3 text-dark">{{ $visiblePhotos->count() }} Photos</h6>
          <div class="mb-3 rounded overflow-hidden">
            <img src="{{ $profileImage?->path ? asset($profileImage->path) : asset('images/profile_photo.png') }}" class="img-fluid rounded w-100" alt="Spotlight Photo">
          </div>
        </div>

        <!-- Recent Updates Card -->
        <!-- <div class="sidebar-card p-3 rounded">
          <h6 class="fw-bold mb-2 text-dark">Recent updates</h6>
          <p class="small text-muted mb-2">Friday, August 14</p>
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-journal-text fs-5 text-muted"></i>
            <span class="small text-dark"><strong>Connor Graham</strong> added 4 life story chapters.</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-image fs-5 text-muted"></i>
            <span class="small text-dark"><strong>Connor Graham</strong> added 5 photos.</span>
          </div>
        </div> -->

        <!-- Page Views Info -->
        <div class="main-content-card  p-3 rounded">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-eye fs-5 text-dark"></i>
            <h6 class="fw-bold mb-0 text-dark">14 Views</h6>
          </div>
          <div class="d-flex align-items-center gap-2 small text-muted pt-2 border-top border-secondary-subtle">
            <i class="bi bi-globe"></i>
            <span>Open access</span>
          </div>
        </div>

      </div>

    </div>
  </main>


  
  <!-- Independent gallery notification: visually matches Stories success toast. -->
  <div id="fmGalleryNoticeHost" aria-live="polite" aria-atomic="true">
    <div id="fmGalleryNotice" role="status">
      <div class="fm-notice-header">
        <i id="fmGalleryNoticeIcon" class="bi bi-check-circle-fill text-success"></i>
        <strong id="fmGalleryNoticeTitle" class="fm-notice-title">Success</strong>
        <button id="fmGalleryNoticeClose" class="fm-notice-close" type="button" aria-label="Close">&times;</button>
      </div>
      <div id="fmGalleryNoticeBody" class="fm-notice-body"></div>
      <div class="fm-notice-track"><div id="fmGalleryNoticeProgress" class="fm-notice-bar"></div></div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary">
    <div class="container">
      <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
          <h5 class="text-warning fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
          <p class="small text-light-tan leading-relaxed">
            A dignified digital platform dedicated to preserving legacies, connecting families, and honoring cherished memories forever.
          </p>
        </div>

        <div class="col-lg-2 col-md-6">
          <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
            <li><a href="{{ url('/') }}" class="text-light-tan text-decoration-none">Home</a></li>
            <li><a href="{{ url('/all_memories') }}" class="text-light-tan text-decoration-none">Memories</a></li>
            <li><a href="{{ url('/about') }}" class="text-light-tan text-decoration-none">About Us</a></li>
            <li><a href="{{ url('/contact') }}" class="text-light-tan text-decoration-none">Contact Us</a></li>
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
            <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
            <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
          </div>
        </div>
      </div>

      <hr class="border-secondary">

      <div class="text-center small text-white-50 pt-2">
        <p class="mb-0">&copy; 2026 Forever Missed Memorial Directory. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- JavaScript Files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('memo_website_dev_frontend/assets/js/main.js') }}"></script>

  <!-- Suppress only the obsolete upload success alert if another script inserts it. -->
  <script>
    (function () {
      const oldMessage = 'Files uploaded successfully. They are pending approval.';
      function removeLegacyUploadAlerts(root = document) {
        const candidates = root.querySelectorAll
          ? root.querySelectorAll('.alert, .toast, [role="alert"], .notification, .toastify, .swal2-popup')
          : [];
        for (const el of candidates) {
          if (el.closest('#fmGalleryNoticeHost')) continue;
          if ((el.textContent || '').trim() === oldMessage) {
            el.remove();
          }
        }
      }
      document.addEventListener('DOMContentLoaded', function () {
        removeLegacyUploadAlerts();
        const observer = new MutationObserver(() => removeLegacyUploadAlerts());
        observer.observe(document.body, { childList: true, subtree: true });
      });
    })();
  </script>

  <!-- Gallery notifications: no dependency on global .toast or .alert styling. -->
  <script>
    let fmNoticeTimeout = null;
    function hideGalleryToast() {
      document.getElementById('fmGalleryNotice')?.classList.remove('fm-visible');
      if (fmNoticeTimeout !== null) clearTimeout(fmNoticeTimeout);
      fmNoticeTimeout = null;
    }
    function showGalleryToast(message, type = 'success') {
      const notice = document.getElementById('fmGalleryNotice');
      if (!notice) return;
      const ok = type === 'success';
      document.getElementById('fmGalleryNoticeTitle').textContent = ok ? 'Success' : 'Error';
      document.getElementById('fmGalleryNoticeBody').textContent = String(message || '');
      document.getElementById('fmGalleryNoticeIcon').className = ok
        ? 'bi bi-check-circle-fill text-success'
        : 'bi bi-x-circle-fill text-danger';
      const bar = document.getElementById('fmGalleryNoticeProgress');
      bar.style.background = ok ? '#198754' : '#dc3545';
      if (fmNoticeTimeout !== null) clearTimeout(fmNoticeTimeout);
      bar.style.transition = 'none';
      bar.style.width = '100%';
      notice.classList.remove('fm-visible');
      void notice.offsetWidth;
      notice.classList.add('fm-visible');
      void bar.offsetWidth;
      requestAnimationFrame(() => {
        bar.style.transition = 'width 3s linear';
        bar.style.width = '0%';
      });
      fmNoticeTimeout = setTimeout(hideGalleryToast, 3000);
    }
    document.getElementById('fmGalleryNoticeClose')?.addEventListener('click', hideGalleryToast);
    document.addEventListener('DOMContentLoaded', () => {
      @if(session('success'))
        showGalleryToast(@json(session('success')), 'success');
      @elseif(session('error'))
        showGalleryToast(@json(session('error')), 'error');
      @endif
    });
  </script>

     <!-- AJAX gallery uploads: preserves the existing three preview sections -->
   @if($canUpload)
   <script>
   document.addEventListener('DOMContentLoaded', () => {
     const uploadUrl = @json(route('gallery.media.store', $profile->id));
     const csrfToken = @json(csrf_token());
     const configs = [
       { type: 'image', input: 'mediaFileInput', preview: 'imagePreviewContainer', button: 'submitImagesBtn', loader: 'uploadLoader', extensions: ['jpg','jpeg','png','webp','gif'], maxSize: 10 * 1024 * 1024 },
       { type: 'video', input: 'videoFileInput', preview: 'videoPreviewContainer', button: 'submitVideoBtn', loader: 'videoUploadLoader', extensions: ['mp4','mov','webm'], maxSize: 100 * 1024 * 1024 },
       { type: 'audio', input: 'audioFileInput', preview: 'audioPreviewContainer', button: 'submitAudioBtn', loader: 'audioUploadLoader', extensions: ['mp3','wav','ogg','m4a'], maxSize: 20 * 1024 * 1024 }
     ];

     function notify(text, success = true) {
       showGalleryToast(text, success ? 'success' : 'error');
     }

     // Add newly uploaded media immediately, without reloading the page.
     function displayUploadedMedia(type, items) {
       const gridId = { image: 'galleryPhotosGrid', video: 'galleryVideosGrid', audio: 'galleryAudiosGrid' }[type];
       const grid = document.getElementById(gridId);
       if (!grid) return;
       grid.querySelectorAll('[data-gallery-empty]').forEach(element => element.remove());
       items.forEach(item => {
         const col = document.createElement('div');
         col.className = type === 'image' ? 'gallery-photo-col' : 'col-12 col-md-6';
         const badge = document.createElement('span');
         badge.className = 'badge bg-warning text-dark';
         badge.textContent = 'Pending Approval';
         if (type === 'image') {
           const wrapper = document.createElement('div');
           wrapper.className = 'gallery-item-wrapper rounded overflow-hidden shadow-sm position-relative';
           badge.classList.add('position-absolute', 'top-0', 'start-0', 'm-2', 'z-1');
           const img = document.createElement('img');
           img.src = item.url;
           img.alt = item.file_name || 'Memorial photo';
           img.className = 'img-fluid gallery-thumb';
           wrapper.append(badge, img);
           col.append(wrapper);
         } else {
           const wrapper = document.createElement('div');
           wrapper.className = type === 'video' ? 'rounded border bg-white p-2 shadow-sm' : 'p-3 border rounded bg-white shadow-sm';
           const player = document.createElement(type === 'video' ? 'video' : 'audio');
           player.src = item.url;
           player.controls = true;
           player.preload = 'metadata';
           player.className = 'w-100 rounded';
           if (type === 'video') player.style.maxHeight = '250px';
           const name = document.createElement('p');
           name.className = 'small text-muted text-truncate mb-1';
           name.textContent = item.file_name || '';
           wrapper.append(player, name, badge);
           col.append(wrapper);
         }
         grid.prepend(col);
       });
     }

     configs.forEach(config => {
       const input = document.getElementById(config.input);
       const preview = document.getElementById(config.preview);
       const button = document.getElementById(config.button);
       const loader = document.getElementById(config.loader);
       if (!input || !preview || !button || !loader) return;

       let files = [];
       let busy = false;
       let objectUrls = [];
       const updateButton = () => {
         button.disabled = busy || files.length === 0;
         button.classList.toggle('opacity-50', button.disabled);
       };
       function render() {
         objectUrls.forEach(url => URL.revokeObjectURL(url));
         objectUrls = [];
         preview.replaceChildren();
         files.forEach((file, index) => {
           const col = document.createElement('div');
           col.className = 'col';
           const wrapper = document.createElement('div');
           wrapper.className = 'preview-thumb-card shadow-sm position-relative rounded overflow-hidden';
           let media;
           const url = URL.createObjectURL(file);
           objectUrls.push(url);
           if (config.type === 'image') {
             media = document.createElement('img');
             media.src = url;
             media.alt = 'Selected photo';
             media.style.cssText = 'display:block;width:100%;height:100%;object-fit:cover;object-position:center';
           } else if (config.type === 'video') {
             media = document.createElement('video');
             media.src = url;
             media.muted = true;
             media.preload = 'metadata';
             media.style.cssText = 'width:100%;height:120px;object-fit:cover;background:#000';
           } else {
             media = document.createElement('div');
             media.className = 'p-2 bg-light';
             const name = document.createElement('p');
             name.className = 'small text-truncate pe-4';
             name.textContent = file.name;
             const player = document.createElement('audio');
             player.controls = true;
             player.src = url;
             player.style.width = '100%';
             media.append(name, player);
           }
           const remove = document.createElement('button');
           remove.type = 'button';
           remove.className = 'delete-thumb-btn';
           remove.title = 'Remove';
           remove.innerHTML = '<i class="bi bi-x"></i>';
           remove.disabled = busy;
           remove.addEventListener('click', () => {
             files.splice(index, 1);
             render();
           });
           wrapper.append(media, remove);
           col.append(wrapper);
           preview.append(col);
         });
         updateButton();
       }

       function addFiles(incoming) {
         const rejected = [];
         for (const file of incoming) {
           const ext = (file.name.split('.').pop() || '').toLowerCase();
           if (!config.extensions.includes(ext)) {
             rejected.push(file.name + ': unsupported format');
           } else if (file.size > config.maxSize) {
             rejected.push(file.name + ': file too large');
           } else if (files.length >= 20) {
             rejected.push('Maximum 20 files per upload');
             break;
           } else {
             files.push(file);
           }
         }
         if (rejected.length) notify(rejected.join('\n'), false);
         render();
       }

       input.addEventListener('change', () => {
         addFiles(Array.from(input.files));
         input.value = '';
       });
       const dropzone = input.closest('.upload-dropzone');
       if (dropzone) {
         dropzone.addEventListener('dragover', event => {
           event.preventDefault();
           dropzone.classList.add('border-primary');
         });
         dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-primary'));
         dropzone.addEventListener('drop', event => {
           event.preventDefault();
           dropzone.classList.remove('border-primary');
           if (!busy) addFiles(Array.from(event.dataTransfer.files));
         });
       }

       button.addEventListener('click', async () => {
         if (busy || files.length === 0) return;
         busy = true;
         updateButton();
         input.disabled = true;
         loader.classList.remove('d-none');
         const data = new FormData();
         data.append('type', config.type);
         files.forEach(file => data.append('files[]', file, file.name));
         try {
           const response = await fetch(uploadUrl, {
             method: 'POST',
             headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
             body: data,
             credentials: 'same-origin'
           });
           const result = await response.json();
           if (!response.ok || !result.success) {
             const details = result.errors ? Object.values(result.errors).flat().join('\n') : result.message;
             throw new Error(details || 'Upload failed.');
           }
           displayUploadedMedia(config.type, result.media || []);
           files = [];
           notify('Your media has been uploaded and is awaiting approval.', true);
         } catch (error) {
           notify(error.message || 'Unable to upload files.', false);
         } finally {
           busy = false;
           input.disabled = false;
           loader.classList.add('d-none');
           render();
         }
       });
       render();
     });
   });
   </script>
   @endif

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15 // Image eken 15% k screen ekata visible unama trigger wei
    };

    const galleryObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
                observer.unobserve(entry.target); // Eka sarayak animation eka unata passe observe karana eka nawaththanawa
            }
        });
    }, observerOptions);

    // Gallery items okkoma observe karanna danna
    document.querySelectorAll('.gallery-item-wrapper:not(#galleryPhotosGrid .gallery-item-wrapper)').forEach(item => {
        galleryObserver.observe(item);
    });
});
</script>

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
</body>
</html>