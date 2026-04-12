<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetTrack - Company Inventory System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/landing.css">
</head>
<body>
    <!-- Background -->
    <div class="bg-grid"></div>
    <div class="bg-glow bg-glow-1"></div>
    <div class="bg-glow bg-glow-2"></div>

    <div class="landing-wrapper">

        <!-- Hero -->
        <main class="landing-main">
            <div class="hero-content">
                <div class="logo">
                    <img src="assets/img/NCIA-inventory.png" alt="NCIA logo" class="logo-image">
                    <div class="hero-tag">Inventory Management</div>
                </div>  
                <h1 class="hero-title">
                    Track Every Asset,<br>
                    <span class="text-gradient">Every Deployment</span>
                </h1>
                <p class="hero-subtitle">
                    A centralized system to monitor company items
                </p>
            </div>

            <!-- Access Cards -->
            <div class="access-cards">
                <!-- User Card -->
                <div class="access-card access-card--user" onclick="goToLogin()">
                    <div class="card-icon-wrap card-icon-wrap--user">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">User Access</h2>
                        <p class="card-desc">Staff and administrators with full system access. Manage inventory, users, and reports.</p>
                        <div class="card-features">
                            <span class="feature-tag">Full Dashboard</span>
                            <span class="feature-tag">Manage Items</span>
                            <span class="feature-tag">Reports</span>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="card-badge">Login Required</div>
                </div>

                <!-- Viewer Card -->
                <div class="access-card access-card--viewer" onclick="openViewerModal()">
                    <div class="card-icon-wrap card-icon-wrap--viewer">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">Viewer Access</h2>
                        <p class="card-desc">Read-only access for stakeholders. View inventory status and reports without editing.</p>
                        <div class="card-features">
                            <span class="feature-tag">View Inventory</span>
                            <span class="feature-tag">View Reports</span>
                            <span class="feature-tag">Read Only</span>
                        </div>
                    </div>
                    <div class="card-arrow">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="card-badge card-badge--viewer">Passcode</div>
                </div>
            </div>
        </main>
    </div>

    <!-- Viewer Passcode Modal -->
    <div class="modal-overlay" id="viewerModal" onclick="closeViewerModal(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeViewerModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
            <div class="modal-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h3 class="modal-title">Viewer Access</h3>
            <p class="modal-subtitle">Enter the passcode to view inventory</p>
            <div class="passcode-inputs" id="passcodeInputs">
                <input type="password" class="passcode-dot" maxlength="1" data-index="0" inputmode="numeric">
                <input type="password" class="passcode-dot" maxlength="1" data-index="1" inputmode="numeric">
                <input type="password" class="passcode-dot" maxlength="1" data-index="2" inputmode="numeric">
                <input type="password" class="passcode-dot" maxlength="1" data-index="3" inputmode="numeric">
                <input type="password" class="passcode-dot" maxlength="1" data-index="4" inputmode="numeric">
                <input type="password" class="passcode-dot" maxlength="1" data-index="5" inputmode="numeric">
            </div>
            <div class="modal-error" id="modalError"></div>
            <button class="btn-confirm" id="confirmBtn" onclick="confirmPasscode()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                Enter as Viewer
            </button>
            <p class="modal-hint">Default passcode: <strong>123456</strong></p>
        </div>
    </div>

    <script src="assets/js/landing.js"></script>
</body>
</html>
