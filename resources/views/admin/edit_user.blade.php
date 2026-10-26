<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Memorial Dashboard</title>
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
        <div class="container-fluid py-4 overflow-hidden">
            
            <!-- Section Header -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-xl-8 col-lg-8 col-12">
                    <h4 class="fw-bold text-dark m-0 font-serif">
                        <i class="bi bi-person-gear text-warning me-2"></i> Edit User / Staff Account
                    </h4>
                    <p class="text-muted small m-0">Update account details, role permissions, and access levels for {{ $user->name }}.</p>
                </div>
                <div class="col-xl-4 col-lg-4 col-12 text-lg-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">
                        <i class="bi bi-arrow-left me-1"></i> Back to All Users
                    </a>
                </div>
            </div>

            <!-- Edit User Form Card -->
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10 col-12">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                            <strong><i class="bi bi-exclamation-triangle me-1"></i> Please correct the errors below:</strong>
                            <ul class="mb-0 mt-2 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <!-- Basic Info Section -->
                            <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom">
                                <i class="bi bi-info-circle me-2"></i> Basic Information
                            </h6>

                            <div class="row g-3 mb-4">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Enter full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Username -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Username</label>
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control rounded-3 @error('username') is-invalid @enderror" placeholder="e.g., lakshan_admin" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email with Copy Option -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Email Address</label>
                                    <div class="input-group">
                                        <input type="email" name="email" id="empEmail" value="{{ old('email', $user->email) }}" class="form-control rounded-start-3 @error('email') is-invalid @enderror" placeholder="employee@example.com" required>
                                        <button class="btn btn-outline-secondary border" type="button" onclick="copyText('empEmail')" title="Copy Email">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Phone -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Contact Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control rounded-3 @error('phone') is-invalid @enderror" placeholder="+1 (800) 555-0199">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Country -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Country</label>
                                    <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-control rounded-3 @error('country') is-invalid @enderror" placeholder="e.g., Sri Lanka or United States">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- State / City -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">State / City</label>
                                    <input type="text" name="state" value="{{ old('state', $user->state) }}" class="form-control rounded-3 @error('state') is-invalid @enderror" placeholder="e.g., Colombo or California">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role Selection -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Assigned Role</label>
                                    <select name="role" class="form-select rounded-3 @error('role') is-invalid @enderror" required>
                                        <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="employee" {{ old('role', $user->role) == 'employee' ? 'selected' : '' }}>Employee</option>
                                        <option value="memory_user" {{ old('role', $user->role) == 'memory_user' ? 'selected' : '' }}>Memory Created User</option>
                                        <option value="general_user" {{ old('role', $user->role) == 'general_user' ? 'selected' : '' }}>General User</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Account Status -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Account Status</label>
                                    <select name="status" class="form-select rounded-3 @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Banned</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Credentials Section (Auto-Generate & Copy Password) -->
                            <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom">
                                <i class="bi bi-shield-lock me-2"></i> Security Credentials (Optional Update)
                            </h6>

                            <div class="row g-3 mb-4">
                                <!-- Temporary Password Field with Type & Auto-Generate -->
                                <div class="col-md-8">
                                    <label class="form-label small fw-semibold text-muted">New Password (Leave empty to keep unchanged)</label>
                                    <div class="input-group">
                                        <input type="text" name="password" id="empPassword" class="form-control rounded-start-3 @error('password') is-invalid @enderror" placeholder="Type new password or generate...">
                                        
                                        <!-- Auto-Generate Button -->
                                        <button class="btn btn-outline-warning text-dark fw-semibold" type="button" onclick="generatePassword()" title="Auto Generate Password">
                                            <i class="bi bi-magic me-1"></i> Generate
                                        </button>
                                        
                                        <!-- Copy Button -->
                                        <button class="btn btn-outline-secondary border rounded-end-3" type="button" onclick="copyText('empPassword')" title="Copy Password">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <div class="form-text small text-muted">Leave empty if you do not wish to change the password.</div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Access Levels & Modules Section -->
                            <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom">
                                <i class="bi bi-grid-3x3-gap me-2"></i> Module Access Permissions
                            </h6>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle text-center small permissions-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-start">Module Name</th>
                                            <th>View</th>
                                            <th>Add</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>Approve</th>
                                            <th>Reject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Events Management -->
                                        <tr>
                                            <td class="text-start fw-semibold text-dark">
                                                <i class="bi bi-calendar-event me-2 text-warning"></i> Events Management
                                            </td>
                                            <td data-label="View">
                                                <input class="form-check-input" type="checkbox" name="permissions[events][view]" value="1" {{ (!empty($user->permissions['events']['view']) || old('permissions.events.view')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Add">
                                                <input class="form-check-input" type="checkbox" name="permissions[events][add]" value="1" {{ (!empty($user->permissions['events']['add']) || old('permissions.events.add')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Edit">
                                                <input class="form-check-input" type="checkbox" name="permissions[events][edit]" value="1" {{ (!empty($user->permissions['events']['edit']) || old('permissions.events.edit')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Delete">
                                                <input class="form-check-input" type="checkbox" name="permissions[events][delete]" value="1" {{ (!empty($user->permissions['events']['delete']) || old('permissions.events.delete')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Approve">
                                                <input class="form-check-input" type="checkbox" name="permissions[events][approve]" value="1" {{ (!empty($user->permissions['events']['approve']) || old('permissions.events.approve')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Reject">
                                                <input class="form-check-input" type="checkbox" name="permissions[events][reject]" value="1" {{ (!empty($user->permissions['events']['reject']) || old('permissions.events.reject')) ? 'checked' : '' }}>
                                            </td>
                                        </tr>

                                        <!-- Memories Directory -->
                                        <tr>
                                            <td class="text-start fw-semibold text-dark">
                                                <i class="bi bi-heart me-2 text-danger"></i> Memories Directory
                                            </td>
                                            <td data-label="View">
                                                <input class="form-check-input" type="checkbox" name="permissions[memories][view]" value="1" {{ (!empty($user->permissions['memories']['view']) || old('permissions.memories.view')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Add">
                                                <input class="form-check-input" type="checkbox" name="permissions[memories][add]" value="1" {{ (!empty($user->permissions['memories']['add']) || old('permissions.memories.add')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Edit">
                                                <input class="form-check-input" type="checkbox" name="permissions[memories][edit]" value="1" {{ (!empty($user->permissions['memories']['edit']) || old('permissions.memories.edit')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Delete">
                                                <input class="form-check-input" type="checkbox" name="permissions[memories][delete]" value="1" {{ (!empty($user->permissions['memories']['delete']) || old('permissions.memories.delete')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Approve">
                                                <input class="form-check-input" type="checkbox" name="permissions[memories][approve]" value="1" {{ (!empty($user->permissions['memories']['approve']) || old('permissions.memories.approve')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Reject">
                                                <input class="form-check-input" type="checkbox" name="permissions[memories][reject]" value="1" {{ (!empty($user->permissions['memories']['reject']) || old('permissions.memories.reject')) ? 'checked' : '' }}>
                                            </td>
                                        </tr>

                                        <!-- Employees / Admins -->
                                        <tr>
                                            <td class="text-start fw-semibold text-dark">
                                                <i class="bi bi-people me-2 text-primary"></i> Employees / Admins
                                            </td>
                                            <td data-label="View">
                                                <input class="form-check-input" type="checkbox" name="permissions[employees][view]" value="1" {{ (!empty($user->permissions['employees']['view']) || old('permissions.employees.view')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Add">
                                                <input class="form-check-input" type="checkbox" name="permissions[employees][add]" value="1" {{ (!empty($user->permissions['employees']['add']) || old('permissions.employees.add')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Edit">
                                                <input class="form-check-input" type="checkbox" name="permissions[employees][edit]" value="1" {{ (!empty($user->permissions['employees']['edit']) || old('permissions.employees.edit')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Delete">
                                                <input class="form-check-input" type="checkbox" name="permissions[employees][delete]" value="1" {{ (!empty($user->permissions['employees']['delete']) || old('permissions.employees.delete')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Approve">
                                                <input class="form-check-input" type="checkbox" name="permissions[employees][approve]" value="1" {{ (!empty($user->permissions['employees']['approve']) || old('permissions.employees.approve')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Reject">
                                                <input class="form-check-input" type="checkbox" name="permissions[employees][reject]" value="1" {{ (!empty($user->permissions['employees']['reject']) || old('permissions.employees.reject')) ? 'checked' : '' }}>
                                            </td>
                                        </tr>

                                        <!-- Settings & Configurations -->
                                        <tr>
                                            <td class="text-start fw-semibold text-dark">
                                                <i class="bi bi-gear me-2 text-secondary"></i> Settings & Configurations
                                            </td>
                                            <td data-label="View">
                                                <input class="form-check-input" type="checkbox" name="permissions[settings][view]" value="1" {{ (!empty($user->permissions['settings']['view']) || old('permissions.settings.view')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Add">
                                                <input class="form-check-input" type="checkbox" name="permissions[settings][add]" value="1" {{ (!empty($user->permissions['settings']['add']) || old('permissions.settings.add')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Edit">
                                                <input class="form-check-input" type="checkbox" name="permissions[settings][edit]" value="1" {{ (!empty($user->permissions['settings']['edit']) || old('permissions.settings.edit')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Delete">
                                                <input class="form-check-input" type="checkbox" name="permissions[settings][delete]" value="1" {{ (!empty($user->permissions['settings']['delete']) || old('permissions.settings.delete')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Approve">
                                                <input class="form-check-input" type="checkbox" name="permissions[settings][approve]" value="1" {{ (!empty($user->permissions['settings']['approve']) || old('permissions.settings.approve')) ? 'checked' : '' }}>
                                            </td>
                                            <td data-label="Reject">
                                                <input class="form-check-input" type="checkbox" name="permissions[settings][reject]" value="1" {{ (!empty($user->permissions['settings']['reject']) || old('permissions.settings.reject')) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Form Submit Buttons -->
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light border px-4">Cancel</a>
                                <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                                    <i class="bi bi-check-circle me-1"></i> Update User
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer class="bg-memorial-dark text-white pt-5 pb-3 border-top border-secondary mt-5">
            <div class="container">
                <div class="row g-4 mb-4">
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-gold fw-bold mb-3"><i class="bi bi-fire me-1"></i> Forever Missed</h5>
                        <p class="small text-light-tan leading-relaxed">
                            A dignified digital platform dedicated to preserving legacies, connecting families, and honoring cherished memories forever.
                        </p>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                        <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                            <li><a href="{{ url('admin/dashboard') }}" class="text-light-tan text-decoration-none">Home</a></li>
                            <li><a href="{{ url('admin/all_memories') }}" class="text-light-tan text-decoration-none">Memories</a></li>
                            <li><a href="{{ url('admin/about') }}" class="text-light-tan text-decoration-none">About Us</a></li>
                            <li><a href="{{ url('admin/contact') }}" class="text-light-tan text-decoration-none">Contact Us</a></li>
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
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i class="bi bi-instagram"></i></a>
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

    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- mobile sidebar script -->
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

    <!-- JavaScript for Auto-Generate & Copy Functions -->
    <script>
        // Auto Generate Password function
        function generatePassword() {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!";
            let password = "";
            for (let i = 0; i < 10; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById("empPassword").value = password;
        }

        // Copy Text to Clipboard function
        function copyText(elementId) {
            const copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value);
            
            // Optional visual feedback
            alert("Copied to clipboard: " + copyText.value);
        }
    </script>

</body>
</html>
