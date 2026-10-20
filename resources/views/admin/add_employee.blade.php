<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Employe - Memorial Dashboard</title>
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
</head>

<body>

    @include('admin.sidebar')

    <!-- Main Content -->
    
        <div class="main-content">

    <!-- Top Navbar -->

    @include('admin.header')

            <!-- Page Body -->
            <div class="container-fluid py-4 overflow-hidden ">

                <!-- Section Header -->
                <div class="row align-items-center mb-4 g-3">
                    <div class="col-xl-8 col-lg-8 col-12">
                        <h4 class="fw-bold text-dark m-0 font-serif">
                            <i class="bi bi-person-plus text-warning me-2"></i> Register New Employee / Sub-Admin
                        </h4>
                        <p class="text-muted small m-0">Create new staff accounts and assign specific module access
                            permissions.</p>
                    </div>
                </div>

                <!-- Add Employee Form Card -->
                <div class="row  justify-content-center">
                    <div class="col-xl-9 col-lg-10 col-12 ">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <form>

                                <!-- Basic Info Section -->
                                <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom">
                                    <i class="bi bi-info-circle me-2"></i> Basic Information
                                </h6>

                                <div class="row g-3 mb-4">
                                    <!-- Full Name -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-muted">Full Name</label>
                                        <input type="text" class="form-control rounded-3"
                                            placeholder="Enter employee full name" required>
                                    </div>

                                    <!-- Username -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-muted">Username</label>
                                        <input type="text" class="form-control rounded-3"
                                            placeholder="e.g., lakshan_admin" required>
                                    </div>

                                    <!-- Email with Copy Option -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-muted">Email Address</label>
                                        <div class="input-group">
                                            <input type="email" id="empEmail" class="form-control rounded-start-3"
                                                placeholder="employee@example.com" required>
                                            <button class="btn btn-outline-secondary border" type="button"
                                                onclick="copyText('empEmail')" title="Copy Email">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Role Selection -->
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-muted">Assigned Role</label>
                                        <select class="form-select rounded-3">
                                            <option selected disabled>Choose Role...</option>
                                            <option value="moderator">Moderator</option>
                                            <option value="editor">Content Editor</option>
                                            <option value="manager">Manager</option>
                                            <option value="support">Support Agent</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Credentials Section (Auto-Generate & Copy Password) -->
                                <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom">
                                    <i class="bi bi-shield-lock me-2"></i> Security Credentials
                                </h6>

                                <div class="row g-3 mb-4">
                                    <!-- Temporary Password Field with Type & Auto-Generate -->
                                    <div class="col-md-8">
                                        <label class="form-label small fw-semibold text-muted">Temporary
                                            Password</label>
                                        <div class="input-group">
                                            <!-- Input එක readonly නැති නිසා type කරන්නත් පුළුවන් -->
                                            <input type="text" id="empPassword"
                                                class="form-control rounded-start-3"
                                                placeholder="Type password or generate..." required>

                                            <!-- Auto-Generate Button -->
                                            <button class="btn btn-outline-warning text-dark fw-semibold"
                                                type="button" onclick="generatePassword()"
                                                title="Auto Generate Password">
                                                <i class="bi bi-magic me-1"></i> Generate
                                            </button>

                                            <!-- Copy Button -->
                                            <button class="btn btn-outline-secondary border rounded-end-3"
                                                type="button" onclick="copyText('empPassword')"
                                                title="Copy Password">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                        <div class="form-text small text-muted">Type manually or click generate to
                                            create a secure password.</div>
                                    </div>
                                </div>

                                <!-- Access Levels & Modules Section -->
                                <h6 class="fw-bold text-brown mb-3 pb-2 border-bottom">
                                    <i class="bi bi-grid-3x3-gap me-2"></i> Module Access Permissions
                                </h6>

                                <div class="table-responsive mb-4">
                                    <table
                                        class="table table-bordered align-middle text-center small permissions-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-start">Module Name</th>
                                                <th>View</th>
                                                <th>Add</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                                <th>Approve </th>
                                                <th>Reject</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-start fw-semibold text-dark"><i
                                                        class="bi bi-calendar-event me-2 text-warning"></i> Events
                                                    Management</td>

                                                <td data-label="View"><input class="form-check-input" type="checkbox"
                                                        checked></td>
                                                <td data-label="Add"><input class="form-check-input" type="checkbox">
                                                </td>
                                                <td data-label="Edit"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Delete"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Approve"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Reject"><input class="form-check-input"
                                                        type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-start fw-semibold text-dark"><i
                                                        class="bi bi-heart me-2 text-danger"></i> Memories Directory
                                                </td>
                                                <td data-label="View"><input class="form-check-input" type="checkbox"
                                                        checked></td>
                                                <td data-label="Add"><input class="form-check-input" type="checkbox">
                                                </td>
                                                <td data-label="Edit"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Delete"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Approve"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Reject"><input class="form-check-input"
                                                        type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-start fw-semibold text-dark"><i
                                                        class="bi bi-people me-2 text-primary"></i> Employees / Admins
                                                </td>
                                                <td data-label="View"><input class="form-check-input" type="checkbox"
                                                        checked></td>
                                                <td data-label="Add"><input class="form-check-input" type="checkbox">
                                                </td>
                                                <td data-label="Edit"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Delete"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Approve"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Reject"><input class="form-check-input"
                                                        type="checkbox"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-start fw-semibold text-dark"><i
                                                        class="bi bi-gear me-2 text-secondary"></i> Settings &
                                                    Configurations</td>
                                                <td data-label="View"><input class="form-check-input" type="checkbox"
                                                        checked></td>
                                                <td data-label="Add"><input class="form-check-input" type="checkbox">
                                                </td>
                                                <td data-label="Edit"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Delete"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Approve"><input class="form-check-input"
                                                        type="checkbox"></td>
                                                <td data-label="Reject"><input class="form-check-input"
                                                        type="checkbox"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Form Submit Buttons -->
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="reset" class="btn btn-light border px-4">Reset</button>
                                    <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                                        <i class="bi bi-check-circle me-1"></i> Save Employee
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
                                A dignified digital platform dedicated to preserving legacies, connecting families, and
                                honoring cherished memories forever.
                            </p>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <h6 class="text-gold fw-bold mb-3">Quick Links</h6>
                            <ul class="list-unstyled small d-flex flex-column gap-2 text-light-tan">
                                <li><a href="{{ url('admin/index') }}"
                                        class="text-light-tan text-decoration-none">Home</a></li>
                                <li><a href="{{ url('admin/all_memories') }}"
                                        class="text-light-tan text-decoration-none">Memories</a></li>
                                <li><a href="{{ url('admin/about') }}"
                                        class="text-light-tan text-decoration-none">About Us</a></li>
                                <li><a href="{{ url('admin/contact') }}"
                                        class="text-light-tan text-decoration-none">Contact Us</a></li>
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
                                <a href="#"
                                    class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                        class="bi bi-facebook"></i></a>
                                <a href="#"
                                    class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
                                        class="bi bi-twitter-x"></i></a>
                                <a href="#"
                                    class="btn btn-outline-light btn-sm rounded-circle footer-social-btn"><i
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







    <!-- Bootstrap 5 JS and Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!--mobile slidebr script  -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Force initialize default active tab securely
        document.addEventListener('DOMContentLoaded', function() {
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

            // Optional alert or visual feedback
            alert("Copied to clipboard: " + copyText.value);
        }
    </script>




</body>

</html>
