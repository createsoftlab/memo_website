<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Forever Missed Memorial Directory</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS Files -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/main.css') }}">
    <!-- Signup Specific External CSS -->
    <link rel="stylesheet" href="{{ asset('memo_website_dev_frontend/assets/css/signup.css') }}">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

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

                <div
                    class="offcanvas-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                    <ul class="mb-3 navbar-nav me-auto mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}"><i class="bi bi-house-door"></i> HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/all_memories') }}"><i class="bi bi-heart-fill"></i>
                                Memories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/about') }}"><i class="bi bi-info-circle"></i> ABOUT
                                US</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/contact') }}"><i class="bi bi-envelope"></i> CONTACT
                                US</a>
                        </li>
                    </ul>

                    <div
                        class="gap-2 pt-3 mt-auto d-flex flex-column flex-lg-row align-items-lg-center mt-lg-0 pt-lg-0 border-top border-lg-0 border-secondary">
                        <a href="{{ url('login') }}" class="text-center btn btn-nav-login"><i
                                class="bi bi-box-arrow-in-right"></i> LOGIN</a>
                        <a href="{{ url('register') }}" class="text-center btn btn-nav-signup active"><i
                                class="bi bi-person-plus"></i> SIGN UP</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content (Sign Up Form Section) -->
    <main class="py-5 flex-grow-1 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                    <!-- Signup Card -->
                    <div class="p-4 signup-card p-md-5">
                        <div class="mb-4 text-center">
                            <h2 class="font-serif fw-bold text-brown">Create an Account</h2>
                            <p class="text-muted small">Join Forever Missed to create memorials & honor cherished
                                legacies</p>
                        </div>

                        <form action="{{ route('register') }}" method="POST" id="signupForm">
                            @csrf

                            <input type="hidden" name="redirect_to"
                                value="{{ request('redirect_to', session('url.intended', url('/'))) }}">
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="fullName" class="form-label fw-bold small">Full Name *</label>
                                <input type="text" class="py-2 form-control" id="fullName" name="name"
                                    placeholder="John Doe">
                                @error('name')
                                <div class="mt-1 text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold small">Email Address *</label>
                                <input type="text" class="py-2 form-control" id="email" name="email"
                                    placeholder="name@example.com">
                                @error('email')
                                <div class="mt-1 text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>


                            <div class="mb-3 row">
                                <!-- 1. Country Custom Dropdown -->
                                @if (empty($visitorIp))
                                <div class="mb-3 col-12 col-md-6 mb-md-0">
                                    <label class="form-label fw-bold">Country *</label>
                                    <div class="dropdown w-100">
                                        <button
                                            class="px-3 py-2 bg-white border btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary text-truncate dropdown-toggle-custom"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-truncate">🇱🇰 Sri Lanka</span>
                                            <i class="flex-shrink-0 bi bi-chevron-down ms-2"></i>
                                        </button>
                                        <ul class="shadow-sm dropdown-menu w-100"
                                            style="max-height: 250px; overflow-y: auto;">
                                            <li><a class="dropdown-item active" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'LK')">🇱🇰
                                                    Sri Lanka</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'US')">🇺🇸
                                                    United States</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'GB')">🇬🇧
                                                    United Kingdom</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'AU')">🇦🇺
                                                    Australia</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'CA')">🇨🇦
                                                    Canada</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'IN')">🇮🇳
                                                    India</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'AE')">🇦🇪
                                                    United Arab Emirates</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="country" id="account_country" value="LK">
                                </div>
                                @elseif ($visitorCountry)
                                <div class="mb-3 col-12 col-md-6 mb-md-0">
                                    <label for="country" class="form-label fw-bold small">Country *</label>
                                    <input type="text" class="py-2 form-control" id="country" name="country"
                                        value="{{ $visitorCountry }}" readonly>
                                    @error('country')
                                    <div class="mt-1 text-danger small">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                @else
                                <div class="mb-3 col-12 col-md-6 mb-md-0">
                                    <label class="form-label fw-bold">Country *</label>
                                    <div class="dropdown w-100">
                                        <button
                                            class="px-3 py-2 bg-white border btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center text-secondary text-truncate dropdown-toggle-custom"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-truncate">🇱🇰 Sri Lanka</span>
                                            <i class="flex-shrink-0 bi bi-chevron-down ms-2"></i>
                                        </button>
                                        <ul class="shadow-sm dropdown-menu w-100"
                                            style="max-height: 250px; overflow-y: auto;">
                                            <li><a class="dropdown-item active" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'LK')">🇱🇰
                                                    Sri Lanka</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'US')">🇺🇸
                                                    United States</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'GB')">🇬🇧
                                                    United Kingdom</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'AU')">🇦🇺
                                                    Australia</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'CA')">🇨🇦
                                                    Canada</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'IN')">🇮🇳
                                                    India</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="selectDropdownOption(this, 'account_country', 'AE')">🇦🇪
                                                    United Arab Emirates</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="country" id="account_country" value="LK">
                                </div>
                                @endif

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold" id="regionLabel">State *</label>
                                    <input type="text" name="state" id="stateProvinceInput"
                                        class="py-2 form-control w-100">
                                    @error('state')
                                    <div class="mt-1 text-danger small">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="mb-3 row">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold" id="regionLabel">City</label>
                                    <input type="text" name="city" id="stateProvinceInput"
                                        class="py-2 form-control w-100">
                                    @error('city')
                                    <div class="mt-1 text-danger small">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div> --}}

                            <!-- Country Code & Contact Number (Mobile Friendly Grid Layout) -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold small">Contact Number *</label>
                                <div class="row g-2">
                                    <div class="col-4 col-sm-4">
                                        <select class="py-2 form-select" id="countryCode" name="countryCode" required>
                                            <option value="+94" selected>LK (+94)</option>
                                            <option value="+1">US (+1)</option>
                                            <option value="+44">UK (+44)</option>
                                            <option value="+61">AU (+61)</option>
                                            <option value="+91">IN (+91)</option>
                                            <option value="+971">AE (+971)</option>
                                            <option value="+81">JP (+81)</option>
                                        </select>
                                    </div>
                                    <div class="col-8 col-sm-8">
                                        <input type="tel" class="py-2 form-control" id="phone" name="phone"
                                            placeholder="77 123 4567">
                                        @error('phone')
                                        <div class="mt-1 text-danger small">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Create Password Field -->
                            <div class="mb-3">
                                <label for="passwordInput" class="form-label fw-bold small">
                                    Create Password *
                                </label>

                                <div class="input-group">
                                    <input type="password"
                                        class="py-2 form-control @error('password') is-invalid @enderror"
                                        id="passwordInput" name="password" required minlength="8">

                                    <button class="px-3 btn btn-outline-secondary" type="button" id="togglePasswordBtn"
                                        aria-label="Toggle password visibility">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </button>
                                </div>

                                <!-- Live Password Validation -->
                                <div id="password-error" class="mt-1 small" aria-live="polite"></div>

                                @error('password')
                                <div class="mt-1 text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-3">
                                <label for="confirmPasswordInput" class="form-label fw-bold small">
                                    Confirm Password *
                                </label>

                                <div class="input-group">
                                    <input type="password"
                                        class="py-2 form-control @error('password_confirmation') is-invalid @enderror"
                                        id="confirmPasswordInput" name="password_confirmation"
                                        autocomplete="new-password">

                                    <button class="px-3 btn btn-outline-secondary" type="button"
                                        id="toggleConfirmPasswordBtn" autocomplete="new-password"
                                        aria-label="Toggle confirm password visibility">
                                        <i class="bi bi-eye-slash" id="toggleConfirmIcon"></i>
                                    </button>
                                </div>

                                <!-- Live Confirm Password Validation -->
                                <div id="confirm-password-error" class="mt-1 small" aria-live="polite"></div>

                                @error('password_confirmation')
                                <div class="mt-1 text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Random Password Generator -->
                            <div class="mb-3 text-end">
                                <button type="button"
                                    class="p-0 bg-transparent border-0 btn btn-sm btn-outline-gold text-memorial-accent fw-semibold"
                                    id="generatePasswordBtn">
                                    <i class="bi bi-magic me-1"></i> Generate Secure Password
                                </button>
                            </div>

                            <!-- Terms & Conditions Checkbox -->

<!-- Terms & Conditions Checkbox -->
<div class="mb-3 form-check">
    <input type="checkbox"
        class="form-check-input"
        id="termsCheck"
        name="terms"
        value="1"
        {{ old('terms') ? 'checked' : '' }}
        required>

    <label class="form-check-label small text-muted" for="termsCheck">
        I agree to the
        <a href="#" class="text-memorial-accent text-decoration-none">
            Terms of Service
        </a>
        &
        <a href="{{ url('/privacy') }}"
           class="text-memorial-accent text-decoration-none">
            Privacy Policy
        </a>
    </label>

    @error('terms')
        <div class="mt-1 text-danger small">{{ $message }}</div>
    @enderror
</div>

<!-- Offline CAPTCHA -->
<div id="captchaSection" class="mb-3" style="display: none;">

    <label class="form-label fw-bold small">
        Security Verification *
    </label>

    <div class="p-3 border rounded bg-light">

        <p class="mb-2 small text-muted">
            Enter the characters shown in the image.
        </p>

        <div class="d-flex align-items-center gap-2 mb-3">

            <img
                id="captchaImage"
                alt="CAPTCHA verification"
                width="190"
                height="60"
                class="border rounded bg-white"
                style="object-fit: contain;">

            <button
                type="button"
                id="refreshCaptcha"
                class="btn btn-outline-secondary"
                title="Refresh CAPTCHA">

                <i class="bi bi-arrow-clockwise"></i>

            </button>
        </div>

        <input
            type="text"
            id="captchaInput"
            name="captcha"
            class="form-control"
            placeholder="Enter the 6 characters"
            maxlength="6"
            autocomplete="off"
            autocapitalize="characters">

        <div
            id="captchaMessage"
            class="mt-2 small"
            aria-live="polite">
        </div>

        @error('captcha')
            <div class="mt-1 text-danger small">
                {{ $message }}
            </div>
        @enderror

    </div>
</div>
<!-- Submit Button -->
<div class="mb-3 d-grid">
    <button type="submit"
        id="createAccountBtn"
        class="py-2 btn btn-gold-action fw-bold"
        disabled>
        <i class="bi bi-person-plus me-1"></i>
        CREATE ACCOUNT
    </button>
</div>


                            <!-- Login Link -->
                            <div class="mt-4 text-center">
                                <p class="mb-0 small text-muted">Already have an account? <a href="{{ url('/login') }}"
                                        class="text-memorial-accent fw-bold text-decoration-none">Sign in here</a></p>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="pt-5 pb-3 text-white bg-memorial-dark border-top border-secondary">
        <div class="container">
            <div class="mb-4 row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3 text-gold fw-bold"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                    <p class="leading-relaxed small text-light-tan">
                        A dignified digital platform dedicated to preserving legacies, connecting families, and honoring
                        cherished memories forever.
                    </p>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="mb-3 text-gold fw-bold">Quick Links</h6>
                    <ul class="gap-2 list-unstyled small d-flex flex-column text-light-tan">
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
                    <h6 class="mb-3 text-gold fw-bold">Contact Support</h6>
                    <ul class="gap-2 list-unstyled small d-flex flex-column text-light-tan">
                        <li><i class="bi bi-telephone-fill text-gold me-2"></i>+1 (800) 555-0199</li>
                        <li><i class="bi bi-envelope-fill text-gold me-2"></i>support@forevermissed.com</li>
                        <li><i class="bi bi-geo-alt-fill text-gold me-2"></i>Global Headquarters</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3 text-gold fw-bold">Follow Us</h6>
                    <div class="gap-2 d-flex">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Facebook"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Twitter"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Instagram"><i
                                class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <hr class="border-secondary">

            <div class="pt-2 text-center small text-white-50">
                <p class="mb-0">&copy; 2026 Forever Missed Memorial Directory. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript for Eye Toggles & Random Password Generator -->

<script>
document.addEventListener("DOMContentLoaded", () => {

    const passwordInput = document.getElementById("passwordInput");
    const confirmPasswordInput = document.getElementById("confirmPasswordInput");

    const togglePasswordBtn = document.getElementById("togglePasswordBtn");
    const toggleConfirmPasswordBtn = document.getElementById("toggleConfirmPasswordBtn");

    const toggleIcon = document.getElementById("toggleIcon");
    const toggleConfirmIcon = document.getElementById("toggleConfirmIcon");

    const passwordError = document.getElementById("password-error");
    const confirmError = document.getElementById("confirm-password-error");

    const generatePasswordBtn = document.getElementById("generatePasswordBtn");

    // ==========================================
    // 1. PASSWORD VISIBILITY TOGGLE
    // ==========================================

    togglePasswordBtn.addEventListener("click", function () {

        const type = passwordInput.type === "password" ? "text" : "password";

        passwordInput.type = type;

        toggleIcon.className = type === "password"
            ? "bi bi-eye-slash"
            : "bi bi-eye";
    });

    // ==========================================
    // 2. CONFIRM PASSWORD VISIBILITY TOGGLE
    // ==========================================

    toggleConfirmPasswordBtn.addEventListener("click", function () {

        const type = confirmPasswordInput.type === "password" ? "text" : "password";

        confirmPasswordInput.type = type;

        toggleConfirmIcon.className = type === "password"
            ? "bi bi-eye-slash"
            : "bi bi-eye";
    });

    // ==========================================
    // 3. PASSWORD VALIDATION
    // ==========================================

    function validatePassword() {

        const password = passwordInput.value;

        if (password.length === 0) {
            passwordError.textContent = "";
            passwordError.style.display = "none";
            passwordInput.setCustomValidity("");
            validateConfirmPassword();
            return;
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

            const errorMessage = "Password must include: " + messages.join(", ");

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
    }

    // ==========================================
    // 4. CONFIRM PASSWORD VALIDATION
    // ==========================================

    function validateConfirmPassword() {

        const password = passwordInput.value;
        const confirm = confirmPasswordInput.value;

        if (confirm.length === 0) {

            confirmError.textContent = "";
            confirmError.style.display = "none";
            confirmPasswordInput.setCustomValidity("");
            return;
        }

        if (password !== confirm) {

            confirmError.textContent = "Passwords do not match.";
            confirmError.style.color = "red";
            confirmError.style.display = "block";

            confirmPasswordInput.setCustomValidity("Passwords do not match.");

        } else {

            confirmError.textContent = "Passwords match ✅";
            confirmError.style.color = "green";
            confirmError.style.display = "block";

            confirmPasswordInput.setCustomValidity("");
        }
    }

    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", validateConfirmPassword);

    // ==========================================
    // 5. SECURE RANDOM NUMBER GENERATOR
    // ==========================================

    function secureRandom(max) {

        if (!Number.isSafeInteger(max) || max <= 0 || max > 4294967296) {
            throw new RangeError("Invalid random range");
        }

        const range = 4294967296;
        const limit = Math.floor(range / max) * max;
        const array = new Uint32Array(1);

        let value;

        do {
            crypto.getRandomValues(array);
            value = array[0];
        } while (value >= limit);

        return value % max;
    }

    // ==========================================
    // 6. SECURE PASSWORD GENERATOR
    // ==========================================

    function generateSecurePassword(length = 12) {

        const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const lowercase = "abcdefghijklmnopqrstuvwxyz";
        const numbers = "0123456789";
        const symbols = "@#$%&*!";

        const allChars = uppercase + lowercase + numbers + symbols;

        // Guarantee all required character types
        const passwordChars = [
            uppercase[secureRandom(uppercase.length)],
            lowercase[secureRandom(lowercase.length)],
            numbers[secureRandom(numbers.length)],
            symbols[secureRandom(symbols.length)]
        ];

        // Fill remaining characters
        for (let i = passwordChars.length; i < length; i++) {

            passwordChars.push(
                allChars[secureRandom(allChars.length)]
            );
        }

        // Secure Fisher-Yates shuffle
        for (let i = passwordChars.length - 1; i > 0; i--) {

            const j = secureRandom(i + 1);

            [passwordChars[i], passwordChars[j]] =
                [passwordChars[j], passwordChars[i]];
        }

        return passwordChars.join("");
    }

    // ==========================================
    // 7. GENERATE PASSWORD BUTTON
    // ==========================================

    generatePasswordBtn.addEventListener("click", function () {

        const password = generateSecurePassword(12);

        passwordInput.value = password;
        confirmPasswordInput.value = password;

        // Show generated password
        passwordInput.type = "text";
        confirmPasswordInput.type = "text";

        toggleIcon.className = "bi bi-eye";
        toggleConfirmIcon.className = "bi bi-eye";

        // Trigger password validation
        validatePassword();
        validateConfirmPassword();
    });

});
</script>

    <script>
        function selectDropdownOption(element, inputId, value) {
            // 1. Stop default anchor click behavior
            event.preventDefault();

            // 2. Get the selected text and emoji/content from the clicked item
            const selectedText = element.innerHTML;

            // 3. Find the parent dropdown wrapper
            const dropdownParent = element.closest('.dropdown');

            // 4. Update the button text to show the selected country
            const button = dropdownParent.querySelector('.dropdown-toggle-custom span');
            if (button) {
                button.innerHTML = selectedText;
            }

            // 5. Update the hidden input value so it submits correctly to the backend
            const hiddenInput = document.getElementById(inputId);
            if (hiddenInput) {
                hiddenInput.value = value;
            }

            // 6. Manage active class styling for dropdown items
            const items = dropdownParent.querySelectorAll('.dropdown-item');
            items.forEach(item => item.classList.remove('active'));
            element.classList.add('active');
        }
    </script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("signupForm");
    const terms = document.getElementById("termsCheck");
    const captchaSection = document.getElementById("captchaSection");
    const captchaImage = document.getElementById("captchaImage");
    const captchaInput = document.getElementById("captchaInput");
    const refreshButton = document.getElementById("refreshCaptcha");
    const captchaMessage = document.getElementById("captchaMessage");
    const submitButton = document.getElementById("createAccountBtn");

    const imageUrl = @json(route('captcha.image'));
    const verifyUrl = @json(route('captcha.verify'));
    const csrfToken = document.querySelector(
        'input[name="_token"]'
    ).value;

    let captchaVerified = false;
    let verifying = false;
    let requestVersion = 0;

    // Update submit button
    function updateSubmitButton() {
        submitButton.disabled = !terms.checked || !captchaVerified;
    }

    // Show verification message
    function showCaptchaMessage(message, type = "danger") {
        captchaMessage.textContent = message;
        captchaMessage.className = "mt-2 small text-" + type;
    }

    // Generate new CAPTCHA
    function resetCaptcha() {
        requestVersion++;
        captchaVerified = false;
        verifying = false;

        captchaInput.value = "";
        captchaInput.disabled = false;

        showCaptchaMessage("");

        if (terms.checked) {
            captchaImage.src = imageUrl + "?v=" + Date.now();
        }

        updateSubmitButton();
    }

    // Terms checkbox
    terms.addEventListener("change", function () {

        captchaSection.style.display =
            this.checked ? "block" : "none";

        resetCaptcha();

        if (this.checked) {
            captchaSection.scrollIntoView({
                behavior: "smooth",
                block: "nearest"
            });
        }
    });

    // Refresh CAPTCHA
    refreshButton.addEventListener("click", function () {
        resetCaptcha();
    });

    // CAPTCHA input verification
    captchaInput.addEventListener("input", async function () {

        const answer = captchaInput.value.trim().toUpperCase();

        captchaVerified = false;
        updateSubmitButton();

        if (answer.length !== 6 || verifying) {
            if (!verifying) showCaptchaMessage("");
            return;
        }

        verifying = true;
        captchaInput.disabled = true;

        const currentVersion = requestVersion;

        showCaptchaMessage("Verifying CAPTCHA...", "secondary");

        try {

            const response = await fetch(verifyUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                credentials: "same-origin",
                body: JSON.stringify({
                    captcha: answer
                })
            });

            const result = await response.json();

            if (currentVersion !== requestVersion) return;

            if (response.ok && result.valid) {

                captchaVerified = true;

                showCaptchaMessage(
                    "CAPTCHA verified successfully!",
                    "success"
                );

            } else {

                showCaptchaMessage(
                    result.message || "Incorrect CAPTCHA. Please refresh and try again."
                );

            }

        } catch (error) {

            if (currentVersion === requestVersion) {
                showCaptchaMessage(
                    "Verification failed. Please try again."
                );
            }

        } finally {

            if (currentVersion === requestVersion) {
                verifying = false;
                captchaInput.disabled = false;
                updateSubmitButton();
            }
        }
    });

    // Prevent unverified registration
    form.addEventListener("submit", function (event) {

        if (!terms.checked || !captchaVerified) {
            event.preventDefault();

            showCaptchaMessage(
                "Please complete CAPTCHA verification."
            );

            return;
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
        }
    });

    // Initial state
    if (terms.checked) {
        captchaSection.style.display = "block";
        resetCaptcha();
    } else {
        updateSubmitButton();
    }

});
</script>

</body>

</html>
