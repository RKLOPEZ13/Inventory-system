<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AssetTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="auth-bg">
        <div class="auth-bg-glow"></div>
    </div>

    <div class="auth-layout">
        <!-- Left panel (branding) -->
        <div class="auth-brand">
            <div class="brand-content">
                <div class="logo">
                    <img src="../assets/img/NCIA-inventory.png" alt="NCIA logo" class="logo-image">
                </div>  

                <h2 class="brand-headline">Manage<br>company assets<br>with confidence.</h2>
                <p class="brand-desc">Real-time tracking of every item deployed across your organization.</p>
            </div>
        </div>

        <!-- Right panel (form) -->
        <div class="auth-form-panel">
            <div class="auth-form-wrap">
                <a href="../index.php" class="back-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back
                </a>

                <div class="form-header">
                    <h1 class="form-title">Welcome back</h1>
                    <p class="form-subtitle">Sign in to access the inventory system</p>
                </div>

                <form class="login-form" id="loginForm" method="POST" action="../api/auth.php">
                    <input type="hidden" name="csrf_token" value="<?= bin2hex(random_bytes(16)) ?>">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <label for="username" class="form-label">Username or Email</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input type="text" id="username" name="username" class="form-input" placeholder="admin" required autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password
                            <a href="#" class="forgot-link">Forgot password?</a>
                        </label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" class="pw-toggle" id="pwToggle" aria-label="Toggle password visibility">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="eyeIcon">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="btn-text">Sign In</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>

                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
</body>
</html>
