<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit My Events - Memorial Dashboard</title>
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
        <div class="container py-5">
        
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="font-serif fw-bold text-dark" style="color: #3b281a;">Admin Control: Pricing & Packages</h2>
                <p class="text-muted small">Create and configure subscription packages or individual add-on items (Flowers, Candles)</p>
            </div>
        </div>

        <!-- Form Container Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="admin-form-card p-4 p-md-5">
                    
                    <!-- Tab Switcher: Package vs Single Items -->
                    <ul class="nav nav-pills mb-4 justify-content-center gap-2" id="creationTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4" id="package-tab" data-bs-toggle="pill" data-bs-target="#packageContent" type="button" role="tab">Create Package</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4" id="item-tab" data-bs-toggle="pill" data-bs-target="#itemContent" type="button" role="tab">Create Single Item (Flower / Candle)</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="creationTabContent">
                        
                        <!-- ================= 1. PACKAGE CREATION FORM ================= -->
                        <div class="tab-pane fade show active" id="packageContent" role="tabpanel">
                            <form action="#" method="POST">
                                <h4 class="form-section-title fs-5"><i class="bi bi-box-seam me-2"></i> Package Configuration</h4>
                                
                                <div class="row g-3 mb-3">
                                    <!-- Package Name -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Package Name</label>
                                        <input type="text" class="form-control" placeholder="e.g. Gold Memorial Plan" required>
                                    </div>
                                    <!-- Price -->
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small">Price ($)</label>
                                        <input type="number" step="0.01" class="form-control" placeholder="79.95" required>
                                    </div>
                                    <!-- Billing Cycle -->
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small">Billing Type</label>
                                        <select class="form-select">
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                            <option value="lifetime">Lifetime (One-time)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <!-- Target Country Selection -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Available Country</label>
                                        <select class="form-select">
                                            <option value="all">All Countries (Global)</option>
                                            <option value="lk">Sri Lanka</option>
                                            <option value="us">United States</option>
                                            <option value="uk">United Kingdom</option>
                                            <option value="au">Australia</option>
                                        </select>
                                    </div>
                                    <!-- Business Profile Selection -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Business Profile Type</label>
                                        <select class="form-select">
                                            <option value="all">All Profiles</option>
                                            <option value="funeral_director">Funeral Director / Parlor</option>
                                            <option value="individual">Individual / Family</option>
                                            <option value="cemetery">Cemetery Management</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <!-- Time Duration Settings -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Time Duration Limit (Validity)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="365">
                                            <select class="form-select" style="max-width: 140px;">
                                                <option value="days">Days</option>
                                                <option value="months">Months</option>
                                                <option value="years">Years</option>
                                                <option value="unlimited">Unlimited</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Trial Duration -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Free Trial Period (Days)</label>
                                        <input type="number" class="form-control" placeholder="e.g. 14 (Leave 0 for no trial)">
                                    </div>
                                </div>

                                <!-- Features & Limits Section -->
                                <h5 class="form-section-title fs-6 mt-4"><i class="bi bi-sliders me-2"></i> Features & Item Limits</h5>
                                <div class="row g-3 mb-4 bg-light p-3 rounded-3 border">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Max Photo Uploads</label>
                                        <input type="number" class="form-control form-control-sm" placeholder="e.g. 50 or -1 for unlimited">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Max Video Uploads</label>
                                        <input type="number" class="form-control form-control-sm" placeholder="e.g. 5">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Max Comments Limit</label>
                                        <input type="number" class="form-control form-control-sm" placeholder="e.g. 100 or -1 for unlimited">
                                    </div>
                                    
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label small fw-bold">Candles Allocation / Feature Limit</label>
                                        <input type="number" class="form-control form-control-sm" placeholder="Free virtual candles included">
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label class="form-label small fw-bold">Flowers Allocation / Feature Limit</label>
                                        <input type="number" class="form-control form-control-sm" placeholder="Free virtual flowers included">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold text-uppercase">Publish Package</button>
                            </form>
                        </div>

                        <!-- ================= 2. SINGLE ITEMS (FLOWERS / CANDLES) FORM ================= -->
                        <div class="tab-pane fade" id="itemContent" role="tabpanel">
                            <form action="#" method="POST">
                                <h4 class="form-section-title fs-5"><i class="bi bi-flower1 me-2"></i> Single Item (Flower / Candle) Price & Extend Setup</h4>
                                
                                <div class="row g-3 mb-3">
                                    <!-- Item Type -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Item Category</label>
                                        <select class="form-select" required>
                                            <option value="candle">Virtual Candle</option>
                                            <option value="flower">Virtual Flower Bouquet</option>
                                        </select>
                                    </div>
                                    <!-- Item Name -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Item Name</label>
                                        <input type="text" class="form-control" placeholder="e.g. Red Rose / Eternal Flame Candle" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <!-- Country -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Applicable Country</label>
                                        <select class="form-select">
                                            <option value="all">All Countries (Global)</option>
                                            <option value="lk">Sri Lanka</option>
                                            <option value="us">United States</option>
                                        </select>
                                    </div>
                                    <!-- Base Price -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Base Price ($)</label>
                                        <input type="number" step="0.01" class="form-control" placeholder="4.99" required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <!-- Initial Time Duration Display -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Initial Active Duration (Validity)</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="7" value="7" required>
                                            <span class="input-group-text">Days</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Extend Price & Duration Setup -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-primary fw-bold">Extend Price (Renewal for time extension)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control" placeholder="2.99" required>
                                            <span class="input-group-text">per</span>
                                            <input type="number" class="form-control" placeholder="30" value="30" style="max-width: 80px;" required>
                                            <span class="input-group-text">Days Extension</span>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Allows users to pay an extension fee to keep the flower/candle displayed longer.</small>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-uppercase text-dark">Save Single Item Pricing</button>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>

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
</body>
</html>