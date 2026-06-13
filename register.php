<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect(BASE_URL . '/index.php');
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
    if (strpos($ref, 'login.php') === false && strpos($ref, 'register.php') === false && strpos($ref, 'logout.php') === false) {
        $_SESSION['redirect_after_login'] = $ref;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (strlen($name) < 2) {
        $error = 'Please enter your full name (at least 2 characters).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role   = 'customer';
            $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $name, $email, $hashed, $role);
            if ($insert->execute()) {
                $_SESSION['user_id'] = $insert->insert_id;
                $_SESSION['role'] = $role;
                $_SESSION['user_name'] = $name;

                if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                    $url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    redirect($url);
                } else {
                    redirect(BASE_URL . '/customer/index.php');
                }
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Jevani Store</title>
    <meta name="description" content="Create your Jevani Store account and start shopping premium bags.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --black: #1a1a1a;
            --white: #d6d3d1;
            --gray-50: #ffffff;
            --gray-100: #f4f4f5;
            --gray-200: rgba(0, 0, 0, 0.08);
            --gray-400: #555555;
            --gray-600: #333333;
            --gray-900: #1a1a1a;
            --accent: #1a1a1a;
            --accent-hover: #333333;
            --error: #ef4444;
            --success: #22c55e;
            --font: 'Inter', sans-serif;
            --serif: 'Playfair Display', serif;
        }

        html, body { height: 100%; }

        body {
            font-family: var(--font);
            background-color: #d6d3d1;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        body::before {
            display: none;
        }

        /* ── Centered Card Layout ── */
        .auth-left { display: none !important; }
        
        .auth-right {
            flex: none;
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            z-index: 1;
            margin: 20px;
        }

        .auth-form-box {
            width: 100%;
            max-width: 100%;
        }

        .auth-form-header { margin-bottom: 32px; text-align: center; }

        .auth-form-eyebrow {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .auth-form-title {
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .auth-form-subtitle {
            margin-top: 8px;
            font-size: 0.9rem;
            color: var(--gray-400);
        }

        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            line-height: 1.5;
            animation: alertIn 0.3s ease;
        }

        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-alert.error  { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; }
        .auth-alert.success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #22c55e; }
        .auth-alert svg { flex-shrink: 0; width: 16px; height: 16px; margin-top: 2px; }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-field { margin-bottom: 16px; }

        .form-field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 7px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            width: 16px;
            height: 16px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1.5px solid rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: var(--font);
            color: var(--gray-900);
            background: #ffffff;
            transition: all 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
        }

        .form-input.has-toggle { padding-right: 42px; }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 2px;
            transition: color 0.2s;
        }

        .toggle-password:hover { color: var(--gray-900); }
        .toggle-password svg { width: 16px; height: 16px; }

        /* Password strength */
        .pwd-strength-bar {
            height: 3px;
            border-radius: 999px;
            background: var(--gray-200);
            margin-top: 8px;
            overflow: hidden;
        }

        .pwd-strength-fill {
            height: 100%;
            border-radius: 999px;
            width: 0%;
            transition: width 0.3s, background 0.3s;
        }

        .pwd-strength-label {
            font-size: 0.75rem;
            margin-top: 5px;
            color: var(--gray-400);
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: var(--font);
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .auth-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .auth-btn:active { transform: translateY(0); }

        .terms-text {
            font-size: 0.78rem;
            color: var(--gray-400);
            text-align: center;
            margin-top: 14px;
            line-height: 1.5;
        }

        .terms-text a { color: var(--gray-600); text-decoration: underline; }

        .auth-form-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.875rem;
            color: var(--gray-400);
        }

        .auth-form-footer a {
            color: var(--gray-900);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-form-footer a:hover { color: var(--accent-hover); }

        .success-box {
            text-align: center;
            padding: 40px 0;
        }

        .success-icon {
            width: 64px;
            height: 64px;
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #22c55e;
        }

        .success-icon svg { width: 28px; height: 28px; }

        .success-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .success-desc {
            font-size: 0.9rem;
            color: var(--gray-400);
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* Responsive */
        .auth-mobile-logo {
            display: block;
            margin-bottom: 32px;
            text-align: center;
        }
        .auth-mobile-logo img {
            height: 64px; /* INCREASED LOGO SIZE */
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        @media (max-width: 480px) {
            .form-grid-2 { grid-template-columns: 1fr; }
            .auth-right { padding: 32px 24px; margin: 16px; }
            .auth-mobile-logo img { height: 50px; }
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <!-- Left Branding Panel -->
    <div class="auth-left">
        <a href="<?php echo BASE_URL; ?>/index.php" class="auth-logo">
            <img src="<?php echo BASE_URL; ?>/assets/logo.png" alt="Jevani Store">
        </a>

        <div class="auth-brand-content">
            <div class="auth-brand-quote">
                Your style,<br>
                your <span>story</span> begins.
            </div>

            <div class="auth-perks">
                <div class="auth-perk">
                    <div class="auth-perk-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    </div>
                    Free doorstep delivery across India
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    Exclusive member-only early access
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </div>
                    Save favourites to your wishlist
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    Track orders in real-time
                </div>
            </div>
        </div>

        <div class="auth-left-footer">
            <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
            <a href="<?php echo BASE_URL; ?>/shop.php">Shop</a>
            <a href="<?php echo BASE_URL; ?>/about.php">About</a>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="auth-right">
        <div class="auth-form-box">
            
            <a href="<?php echo BASE_URL; ?>/index.php" class="auth-mobile-logo">
                <img src="<?php echo BASE_URL; ?>/assets/logo.png" alt="Jevani Store">
            </a>

            <?php if ($success): ?>
            <div class="success-box">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="success-title">You're all set! 🎉</div>
                <p class="success-desc">Your Jevani account has been created.<br>Sign in to start shopping.</p>
                <a href="<?php echo BASE_URL; ?>/login.php" class="auth-btn" style="text-decoration:none; display:inline-flex; width:auto; padding: 14px 36px;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Go to Sign In
                </a>
            </div>
            <?php else: ?>

            <div class="auth-form-header">
                <div class="auth-form-eyebrow">Join Jevani</div>
                <h1 class="auth-form-title">Create your account</h1>
                <p class="auth-form-subtitle">Already have one? <a href="<?php echo BASE_URL; ?>/login.php" style="color: var(--accent); font-weight:500;">Sign in</a></p>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" id="register-form">
                <div class="form-field">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Your full name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required autocomplete="name">
                    </div>
                </div>

                <div class="form-field">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autocomplete="email">
                    </div>
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="password" name="password" class="form-input has-toggle" placeholder="Min. 6 characters" required autocomplete="new-password" oninput="updateStrength(this.value)">
                        <button type="button" class="toggle-password" onclick="togglePwd('password', this)" tabindex="-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div class="pwd-strength-bar"><div class="pwd-strength-fill" id="pwd-fill"></div></div>
                    <div class="pwd-strength-label" id="pwd-label">Enter a password</div>
                </div>

                <div class="form-field">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input has-toggle" placeholder="Re-enter password" required autocomplete="new-password">
                        <button type="button" class="toggle-password" onclick="togglePwd('confirm_password', this)" tabindex="-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="register-btn">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Create Account
                </button>

                <p class="terms-text">By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            </form>

            <div class="auth-form-footer">
                Already have an account? <a href="<?php echo BASE_URL; ?>/login.php">Sign in</a>
            </div>

            <?php endif; ?>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePwd(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden
                ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
                : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        }

        function updateStrength(val) {
            const fill = document.getElementById('pwd-fill');
            const label = document.getElementById('pwd-label');
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [
                { pct: '0%',   color: 'rgba(255,255,255,0.15)', text: 'Enter a password' },
                { pct: '20%',  color: '#ef4444', text: 'Very weak' },
                { pct: '40%',  color: '#f97316', text: 'Weak' },
                { pct: '60%',  color: '#eab308', text: 'Fair' },
                { pct: '80%',  color: '#22c55e', text: 'Strong' },
                { pct: '100%', color: '#16a34a', text: '💪 Very strong' },
            ];

            const lvl = val.length === 0 ? levels[0] : levels[Math.min(score, 5)];
            fill.style.width = lvl.pct;
            fill.style.background = lvl.color;
            label.textContent = lvl.text;
            label.style.color = lvl.color === 'rgba(255,255,255,0.15)' ? '#a1a1aa' : lvl.color;
        }

        document.getElementById('register-form').addEventListener('submit', function(e) {
            const pwd = document.getElementById('password').value;
            const cpwd = document.getElementById('confirm_password').value;
            if (pwd !== cpwd) {
                e.preventDefault();
                alert('Passwords do not match.');
                return;
            }
            const btn = document.getElementById('register-btn');
            btn.innerHTML = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Creating account...';
            btn.disabled = true;
        });
    </script>

    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
</body>
</html>
