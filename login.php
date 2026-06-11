<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect(BASE_URL . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
    if (strpos($ref, 'login.php') === false && strpos($ref, 'register.php') === false && strpos($ref, 'logout.php') === false) {
        $_SESSION['redirect_after_login'] = $ref;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            if ($user['role'] === 'admin') {
                redirect(BASE_URL . '/admin/index.php');
            } else {
                if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                    $url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    redirect($url);
                } else {
                    redirect(BASE_URL . '/customer/index.php');
                }
            }
        } else {
            $error = 'Incorrect email or password.';
        }
    } else {
        $error = 'No account found with that email.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Jevani Store</title>
    <meta name="description" content="Sign in to your Jevani Store account.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --black: #09090b;
            --white: #18181b;
            --gray-50: #09090b;
            --gray-100: #121217;
            --gray-200: rgba(255,255,255,0.08);
            --gray-400: #a1a1aa;
            --gray-600: #a1a1aa;
            --gray-900: #ffffff;
            --accent: #adff2f;
            --accent-hover: #c0ff00;
            --error: #ef4444;
            --success: #22c55e;
            --font: 'Inter', sans-serif;
            --serif: 'Playfair Display', serif;
        }

        html, body { height: 100%; }

        body {
            font-family: var(--font);
            background: url('<?php echo BASE_URL; ?>/assets/craft_workshop.jpg') center/cover no-repeat;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(9, 9, 11, 0.75);
            backdrop-filter: blur(8px);
            z-index: 0;
        }

        /* ── Centered Card Layout ── */
        .auth-left { display: none !important; }
        
        .auth-right {
            flex: none;
            width: 100%;
            max-width: 480px;
            background: rgba(24, 24, 27, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
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
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: var(--font);
            color: var(--gray-900);
            background: rgba(9, 9, 11, 0.5);
            transition: all 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent);
            background: rgba(9, 9, 11, 0.8);
            box-shadow: 0 0 0 4px rgba(173,255,47,0.08);
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

        .auth-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            color: var(--gray-400);
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .forgot-pwd-link {
            font-size: 0.85rem;
            color: var(--gray-400);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-pwd-link:hover { color: var(--accent); text-decoration: underline; }

        .auth-btn {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: #000000;
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
            box-shadow: 0 8px 20px rgba(173, 255, 47, 0.25);
        }

        .auth-btn:active { transform: translateY(0); }

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

        .auth-form-footer a:hover { color: var(--accent); }

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
            .auth-right { padding: 32px 24px; margin: 16px; }
            .auth-mobile-logo img { height: 50px; }
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
            color: var(--gray-400);
            font-size: 0.78rem;
        }

        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        /* Quick login (admin testing) */
        .quick-login-btn {
            width: 100%;
            padding: 13px;
            background: transparent;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.85rem;
            font-family: var(--font);
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .quick-login-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(173, 255, 47, 0.05);
        }

        .quick-login-btn svg { width: 16px; height: 16px; color: var(--accent); }

        /* Footer links */
        .auth-form-footer {
            margin-top: 28px;
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

        .auth-form-footer a:hover { color: var(--accent); }

        /* Responsive */
        .auth-mobile-logo {
            display: none;
            margin-bottom: 24px;
            text-align: center;
        }
        .auth-mobile-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        @media (max-width: 900px) {
            .auth-left { display: none; }
            .auth-right { padding: 32px 24px; }
            .auth-mobile-logo { display: block; }
        }

        @media (max-width: 480px) {
            .auth-right { padding: 24px 20px; align-items: flex-start; padding-top: 48px; }
        }
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
                Carry what<br>
                <span>defines</span> you.
            </div>
            <p class="auth-brand-desc">
                Premium bags crafted for the modern Indian woman. Every stitch, a statement. Every drop, a story.
            </p>
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

            <div class="auth-form-header">
                <div class="auth-form-eyebrow">Welcome back</div>
                <h1 class="auth-form-title">Sign in to your account</h1>
                <p class="auth-form-subtitle">Don't have one? <a href="<?php echo BASE_URL; ?>/register.php" style="color: var(--accent); font-weight:500;">Create account</a></p>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" id="login-form">
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
                        <input type="password" id="password" name="password" class="form-input has-toggle" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePwd('password', this)" tabindex="-1">
                            <svg id="eye-password" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="forgot-link-row">
                    <a href="<?php echo BASE_URL; ?>/forgot_password.php" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="auth-btn" id="sign-in-btn">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Sign In
                </button>
            </form>



            <div class="auth-form-footer">
                New to Jevani? <a href="<?php echo BASE_URL; ?>/register.php">Create a free account</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePwd(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden
                ? '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
                : '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        }

        // Button loading state on submit
        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('sign-in-btn');
            btn.innerHTML = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Signing in...';
            btn.disabled = true;
        });
    </script>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</body>
</html>
