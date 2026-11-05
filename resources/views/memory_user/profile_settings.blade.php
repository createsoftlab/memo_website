<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events - Memorial Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <!-- Custom External Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_dashbord/assets/css/dashboard-main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        
/* Global Toast */
#globalToast {
    min-width: 300px;
    border-radius: 10px;
}

/* Countdown progress track */
.toast-progress-track {
    width: 100%;
    height: 4px;
    background: #e9ecef;
    overflow: hidden;
}

/* Countdown progress line */
.toast-progress-bar {
    width: 100%;
    height: 100%;
    background: #198754;
    transform-origin: left;
    transform: scaleX(1);
}

/* Progress decreases from full to empty */
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

    </style>
</head>

<body>

    @include('memory_user.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        @include('memory_user.header')

        <!-- Profile Settings Content -->
        <div class="container-fluid p-4">

            <div class="row g-4">

        <!-- ==============================
             PROFILE PICTURE UPDATE
        =============================== -->
                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm h-100">


                        <div class="card-body p-4">

                            <h5 class="font-serif text-brown fw-bold mb-1">
                                <i class="bi bi-person-circle me-2 text-warning"></i>
                                Profile Picture
                            </h5>

                            <p class="text-muted small mb-4">
                                Update your profile picture that appears on your dashboard.
                            </p>

                            <form id="profilePictureForm" method="POST" action="{{ route('userProfile_imageSettings') }}"
                                enctype="multipart/form-data">

                                @csrf

                                <!-- Profile Preview -->
                                <div class="text-center mb-4">

                                    <div class="position-relative d-inline-block">

                                        <img id="profilePreview" src="{{ $UserProfileImage
                                        ? asset($UserProfileImage->path)
                                        : asset('images/profile_photo.png') }}"
                                            class="rounded-circle shadow border border-4 border-warning" width="150"
                                            height="150" alt="Profile Picture" style="object-fit: cover;">

                                        <!-- Reset Preview -->
                                        <button type="button" id="removeProfileBtn"
                                            class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 shadow"
                                            style="width:32px;height:32px;padding:0;" title="Remove">

                                            <i class="bi bi-x"></i>

                                        </button>
                                    </div>

                                    <div class="mt-3">
                                        <span class="small text-muted">
                                            Profile Picture Preview
                                        </span>
                                    </div>

                                </div>

                                <!-- Upload -->
                                <div class="mb-3">

                                    <label for="profilePictureInput" class="form-label fw-semibold small">
                                        Choose New Profile Picture
                                    </label>

                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                        id="profilePictureInput" name="profile_image"
                                        accept="image/png,image/jpeg,image/webp">

                                    @error('profile_image')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <div class="form-text">
                                        JPG, PNG or WEBP. Recommended size: 500 × 500px.
                                    </div>

                                </div>

                                <!-- Update Button -->
                                <div class="d-grid">

                                    <button type="submit" class="btn btn-warning fw-bold text-dark"
                                        id="updateProfileBtn">

                                        <i class="bi bi-cloud-arrow-up me-2"></i>
                                        Update Profile Picture

                                    </button>

                                </div>

                            </form>

                        </div>


                    </div>

                </div>



        <!-- ==============================
             CHANGE PASSWORD
        =============================== -->
                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <h5 class="font-serif text-brown fw-bold mb-1">
                                <i class="bi bi-shield-lock me-2 text-warning"></i>
                                Change Password
                            </h5>

                            <p class="text-muted small mb-4">
                                Update your account password to keep your account secure.
                            </p>



<form method="POST" action="{{ route('userProfile_passwordSettings') }}"
      id="changePasswordForm">

    @csrf

    <!-- Current Password -->
    <div class="mb-3">
        <label for="currentPassword" class="form-label small fw-semibold">
            Current Password
        </label>

        <div class="input-group">
            <input type="password"
                   class="form-control"
                   id="currentPassword"
                   name="current_password"
                   placeholder="Enter current password"
                   autocomplete="current-password"
                   required>

            <button type="button"
                    class="btn btn-outline-secondary password-toggle"
                    data-target="currentPassword"
                    title="Show / Hide Password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <!-- New Password -->
    <div class="mb-3">
        <label for="newPassword" class="form-label small fw-semibold">
            New Password
        </label>

        <div class="input-group">
            <input type="password"
                   class="form-control"
                   id="newPassword"
                   name="new_password"
                   placeholder="Enter new password"
                   autocomplete="new-password"
                   minlength="8"
                   required>

            <button type="button"
                    class="btn btn-outline-secondary password-toggle"
                    data-target="newPassword"
                    title="Show / Hide Password">
                <i class="bi bi-eye"></i>
            </button>
        </div>

        <small id="passwordError" class="d-block mt-1"></small>
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="confirmPassword" class="form-label small fw-semibold">
            Confirm New Password
        </label>

        <div class="input-group">
            <input type="password"
                   class="form-control"
                   id="confirmPassword"
                   name="new_password_confirmation"
                   placeholder="Confirm new password"
                   autocomplete="new-password"
                   required>

            <button type="button"
                    class="btn btn-outline-secondary password-toggle"
                    data-target="confirmPassword"
                    title="Show / Hide Password">
                <i class="bi bi-eye"></i>
            </button>
        </div>

        <small id="confirmError" class="d-block mt-1"></small>
    </div>

    <!-- Password Requirements -->
    <div class="bg-light border rounded p-3 mb-4">
        <div class="small fw-semibold text-brown mb-2">
            Password requirements
        </div>

        <ul class="small text-muted mb-0 ps-3">
            <li>At least 8 characters</li>
            <li>Use uppercase and lowercase letters</li>
            <li>Include at least one number</li>
        </ul>
    </div>

    <!-- Change Password Button -->
    <div class="d-grid">
        <button type="submit"
                class="btn btn-dark fw-bold"
                id="changePasswordBtn">

            <i class="bi bi-key me-2"></i>
            Change Password
        </button>
    </div>

</form>


                            </div>

                        </div>

                    </div>

                </div>

            </div>



<!-- Global Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3"
     style="z-index: 11000;">

    <div id="globalToast"
         class="toast border-0 shadow overflow-hidden"
         role="alert"
         aria-live="assertive"
         aria-atomic="true">

        <div class="toast-header">

            <i id="globalToastIcon"
               class="bi bi-check-circle-fill text-success me-2"></i>

            <strong class="me-auto" id="globalToastTitle">
                Success
            </strong>

            <small>Just now</small>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="toast"
                    aria-label="Close"></button>

        </div>

        <div class="toast-body" id="globalToastMessage"></div>

        <!-- Animated countdown line -->
        <div class="toast-progress-track">
            <div id="globalToastProgress"
                 class="toast-progress-bar"></div>
        </div>

    </div>
</div>







        <!-- Footer -->
        <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary">
            <div class="container">
                <div class="row g-4 mb-4">
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-gold fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                        <p class="small text-light-tan leading-relaxed">
                            A dignified digital platform dedicated to preserving legacies, connecting families, and
                            honoring cherished memories forever.
                        </p>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                        <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                            <li><a href="{{ url('admin/index') }}" class="text-light-tan text-decoration-none">Home</a>
                            </li>
                            <li><a href="{{ url('admin/all_memories') }}"
                                    class="text-light-tan text-decoration-none">Memories</a></li>
                            <li><a href="{{ url('admin/about') }}" class="text-light-tan text-decoration-none">About
                                    Us</a></li>
                            <li><a href="{{ url('admin/contact') }}" class="text-light-tan text-decoration-none">Contact
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
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                    class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                    class="bi bi-twitter-x"></i></a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
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
        </div>
    </div>
    </div>

    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!--mobile slidebr script  -->
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

        // Force initialize default active tab securely
        document.addEventListener('DOMContentLoaded', function () {
            const defaultTabEl = document.querySelector('#memorialTabs a[href="#tab-basic"]');
            if (defaultTabEl) {
                const tab = new bootstrap.Tab(defaultTabEl);
                tab.show();
            }
        });

    </script>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const toastElement = document.getElementById('globalToast');
    const progressBar = document.getElementById('globalToastProgress');

    if (!toastElement || !progressBar || typeof bootstrap === 'undefined') {
        return;
    }

    const toast = bootstrap.Toast.getOrCreateInstance(toastElement, {
        animation: true,
        autohide: true,
        delay: 3000
    });

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

    let progressTimer = null;

    window.showGlobalToast = function (message, type = 'success') {

        const config = types[type] || types.success;

        document.getElementById('globalToastTitle').textContent =
            config.title;

        document.getElementById('globalToastMessage').textContent =
            message;

        document.getElementById('globalToastIcon').className =
            config.icon;

        // Set progress color
        progressBar.style.backgroundColor = config.color;

        // Reset progress animation
        progressBar.classList.remove('running');

        void progressBar.offsetWidth;

        progressBar.classList.add('running');

        // Reset previous countdown
        clearTimeout(progressTimer);

        // Restart Bootstrap toast
        toast.show();

        // Hide after exactly 3 seconds
        progressTimer = setTimeout(function () {
            toast.hide();
        }, 3000);
    };

    // Laravel session messages
    @if (session('success'))
        showGlobalToast(@json(session('success')), 'success');
    @elseif (session('error'))
        showGlobalToast(@json(session('error')), 'error');
    @elseif (session('warning'))
        showGlobalToast(@json(session('warning')), 'warning');
    @elseif (session('info'))
        showGlobalToast(@json(session('info')), 'info');
    @elseif ($errors->any())
        showGlobalToast(@json($errors->first()), 'error');
    @endif

});
</script>


// toast message script
<script>
document.addEventListener("DOMContentLoaded", function () {

    const profileInput =
        document.getElementById("profilePictureInput");

    const profilePreview =
        document.getElementById("profilePreview");

    const removeProfileBtn =
        document.getElementById("removeProfileBtn");

    // Default image from public/images
    const defaultProfileImage =
        "{{ asset('images/profile_photo.png') }}";

    let profileObjectUrl = null;


    // ============================
    // LIVE IMAGE PREVIEW
    // ============================

    profileInput.addEventListener("change", function () {

        const file = this.files[0];

        if (profileObjectUrl) {
            URL.revokeObjectURL(profileObjectUrl);
            profileObjectUrl = null;
        }

        if (!file) {
            return;
        }

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!allowedTypes.includes(file.type)) {

            alert("Please select a valid image file.");

            this.value = "";
            return;
        }

        profileObjectUrl = URL.createObjectURL(file);

        profilePreview.src = profileObjectUrl;

    });


    // ============================
    // REMOVE / DEFAULT IMAGE
    // ============================

    removeProfileBtn.addEventListener("click", function () {

        profileInput.value = "";

        if (profileObjectUrl) {
            URL.revokeObjectURL(profileObjectUrl);
            profileObjectUrl = null;
        }

        profilePreview.src = defaultProfileImage;

    });

});
</script>



    <script>
  
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("changePasswordForm");

    const currentPasswordInput =
        document.getElementById("currentPassword");

    const passwordInput =
        document.getElementById("newPassword");

    const confirmPasswordInput =
        document.getElementById("confirmPassword");

    const passwordError =
        document.getElementById("passwordError");

    const confirmError =
        document.getElementById("confirmError");

    // ==========================================
    // 1. PASSWORD VISIBILITY TOGGLE
    // ==========================================

    document.querySelectorAll(".password-toggle")
        .forEach(function (button) {

            button.addEventListener("click", function () {

                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector("i");

                if (!input) return;

                if (input.type === "password") {
                    input.type = "text";

                    icon.classList.remove("bi-eye");
                    icon.classList.add("bi-eye-slash");

                } else {
                    input.type = "password";

                    icon.classList.remove("bi-eye-slash");
                    icon.classList.add("bi-eye");
                }
            });
        });


    // ==========================================
    // 2. NEW PASSWORD VALIDATION
    // ==========================================

    function validatePassword() {

        const password = passwordInput.value;

        if (password.length === 0) {

            passwordError.textContent = "";
            passwordError.style.display = "none";

            passwordInput.setCustomValidity("");

            validateConfirmPassword();

            return false;
        }

        const conditions = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password)
        };

        let messages = [];

        if (!conditions.length) {
            messages.push("8+ characters");
        }

        if (!conditions.uppercase) {
            messages.push("Uppercase letter");
        }

        if (!conditions.lowercase) {
            messages.push("Lowercase letter");
        }

        if (!conditions.number) {
            messages.push("Number");
        }

        if (messages.length > 0) {

            const errorMessage =
                "Password must include: " + messages.join(", ");

            passwordError.textContent = errorMessage;
            passwordError.style.color = "red";
            passwordError.style.display = "block";

            passwordInput.setCustomValidity(errorMessage);

        } else {

            passwordError.textContent = "Strong password ✅";
            passwordError.style.color = "green";
            passwordError.style.display = "block";

            passwordInput.setCustomValidity("");
        }

        validateConfirmPassword();

        return messages.length === 0;
    }


    // ==========================================
    // 3. CONFIRM PASSWORD VALIDATION
    // ==========================================

    function validateConfirmPassword() {

        const password = passwordInput.value;
        const confirm = confirmPasswordInput.value;

        if (confirm.length === 0) {

            confirmError.textContent = "";
            confirmError.style.display = "none";

            confirmPasswordInput.setCustomValidity("");

            return false;
        }

        if (password !== confirm) {

            confirmError.textContent = "Passwords do not match.";
            confirmError.style.color = "red";
            confirmError.style.display = "block";

            confirmPasswordInput.setCustomValidity(
                "Passwords do not match."
            );

            return false;

        } else {

            confirmError.textContent = "Passwords match ✅";
            confirmError.style.color = "green";
            confirmError.style.display = "block";

            confirmPasswordInput.setCustomValidity("");

            return true;
        }
    }


    // ==========================================
    // 4. LIVE VALIDATION
    // ==========================================

    passwordInput.addEventListener(
        "input",
        validatePassword
    );

    confirmPasswordInput.addEventListener(
        "input",
        validateConfirmPassword
    );


    // ==========================================
    // 5. FORM SUBMISSION VALIDATION
    // ==========================================

    form.addEventListener("submit", function (event) {

        const isPasswordValid = validatePassword();
        const isConfirmValid = validateConfirmPassword();

        if (!currentPasswordInput.value.trim()) {

            event.preventDefault();

            currentPasswordInput.setCustomValidity(
                "Please enter your current password."
            );

            currentPasswordInput.reportValidity();

            return;
        }

        currentPasswordInput.setCustomValidity("");

        if (!isPasswordValid || !isConfirmValid) {

            event.preventDefault();

            if (!isPasswordValid) {
                passwordInput.reportValidity();
            } else {
                confirmPasswordInput.reportValidity();
            }

            return;
        }

        // Valid form: allow normal Laravel submission.
    });

    currentPasswordInput.addEventListener("input", function () {
        currentPasswordInput.setCustomValidity("");
    });

});

    </script>



</body>

</html>
