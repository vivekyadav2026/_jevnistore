<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect(BASE_URL . '/index.php');
}

$error   = '';
$success = false;
$step    = 'request';   // request | reset
$token_valid = false;

// ── STEP 2: Verify token from URL ─────────────────────────────────────
if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND reset_token = ? AND reset_token_expires > NOW()");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $step = 'reset';
        $token_valid = true;
    } else {
        $error = 'This reset link is invalid or has expired. Please request a new one.';
    }
}

// ── HANDLE: Password Reset Submit ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    $token    = $_POST['token'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
        $step = 'reset';
        $token_valid = true;
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
        $step = 'reset';
        $token_valid = true;
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND reset_token = ? AND reset_token_expires > NOW()");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $upd = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE email = ?");
            $upd->bind_param("ss", $hashed, $email);
            $upd->execute();
            $success = true;
            $step = 'done';
        } else {
            $error = 'Reset link is invalid or expired. Please request a new one.';
            $step = 'request';
        }
    }
}

// ── HANDLE: Send Reset Email Request ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'request_reset') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            // Check if reset_token column exists; if not, alter table
            $col_check = $conn->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
            if ($col_check->num_rows === 0) {
                $conn->query("ALTER TABLE users ADD COLUMN reset_token VARCHAR(64) NULL, ADD COLUMN reset_token_expires DATETIME NULL");
            }

            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $upd = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            $upd->bind_param("sss", $token, $expires, $email);
            $upd->execute();

            $reset_link = BASE_URL . '/forgot_password.php?token=' . $token . '&email=' . urlencode($email);

            // In production: send $reset_link by email via PHPMailer / mail()
            // For now: display the link on the page for testing
            $success = true;
            $step = 'sent';
            // Store link so we can display it in dev mode
            $_SESSION['_dev_reset_link'] = $reset_link;
        } else {
            // Don't reveal that the email doesn't exist – show the same success message
            $success = true;
            $step = 'sent';
        }
    }
}

$dev_link = $_SESSION['_dev_reset_link'] ?? '';
unset($_SESSION['_dev_reset_link']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Jevani Store</title>
    <meta name="description" content="Reset your Jevani Store account password.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
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

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--gray-400);
            text-decoration: none;
            margin-bottom: 32px;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--gray-900); }
        .back-link svg { width: 14px; height: 14px; }

        /* Icon header */
        .icon-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 32px;
        }

        .icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .icon-circle.indigo {
            background: rgba(173, 255, 47, 0.1);
            border: 1px solid rgba(173, 255, 47, 0.2);
            color: var(--accent);
        }

        .icon-circle.green {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .icon-circle svg { width: 26px; height: 26px; }

        .auth-form-eyebrow {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .auth-form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .auth-form-desc {
            font-size: 0.88rem;
            color: var(--gray-400);
            line-height: 1.6;
        }

        /* Alerts */
        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            line-height: 1.5;
            animation: alertIn 0.3s ease;
        }

        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-alert.error   { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; }
        .auth-alert.success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #22c55e; }
        .auth-alert.info    { background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .auth-alert svg { flex-shrink: 0; width: 16px; height: 16px; margin-top: 2px; }

        /* Fields */
        .form-field { margin-bottom: 18px; }

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
            padding: 13px 13px 13px 40px;
            border: 1.5px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: var(--font);
            color: var(--gray-900);
            background: var(--gray-50);
            transition: all 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--accent);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
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

        /* Buttons */
        .auth-btn {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: #000000;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: var(--font);
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 4px;
            text-decoration: none;
        }

        .auth-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(173, 255, 47, 0.25);
        }

        .auth-btn:active { transform: translateY(0); }

        /* Sent state */
        .sent-box {
            text-align: center;
        }

        .sent-icon {
            width: 72px;
            height: 72px;
            background: rgba(59, 130, 246, 0.1);
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #3b82f6;
        }

        .sent-icon svg { width: 32px; height: 32px; }

        .sent-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 10px;
        }

        .sent-desc {
            font-size: 0.875rem;
            color: var(--gray-400);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        /* Dev mode reset link */
        .dev-link-box {
            background: rgba(234, 179, 8, 0.1);
            border: 1px solid rgba(234, 179, 8, 0.25);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 0.8rem;
        }

        .dev-link-box strong {
            display: block;
            color: #eab308;
            margin-bottom: 6px;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .dev-link-box a {
            color: var(--accent);
            word-break: break-all;
            line-height: 1.5;
        }

        /* Done state */
        .done-icon {
            width: 72px;
            height: 72px;
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #22c55e;
        }

        .done-icon svg { width: 32px; height: 32px; }

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
        }

        .auth-form-footer a:hover { color: var(--accent); }

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

        @media (max-width: 900px) {
            .auth-left { display: none; }
            .auth-right { padding: 32px 24px; margin: 16px; }
            .auth-mobile-logo img { height: 50px; }
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <!-- Left Panel -->
    <div class="auth-left">
        <a href="<?php echo BASE_URL; ?>/index.php" class="auth-logo">
            <img src="<?php echo BASE_URL; ?>/assets/logo.png" alt="Jevani Store">
        </a>

        <div class="auth-brand-content">
            <div class="auth-brand-quote">
                Secure &<br>
                <span>simple</span> recovery.
            </div>

            <div class="security-cards">
                <div class="security-card">
                    <div class="security-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <div class="security-card-title">Secure link sent to your email</div>
                        <div class="security-card-desc">Your reset link is encrypted and expires in 1 hour for your protection.</div>
                    </div>
                </div>
                <div class="security-card">
                    <div class="security-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <div class="security-card-title">Strong password recommended</div>
                        <div class="security-card-desc">Use at least 6 characters with a mix of letters, numbers and symbols.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-left-footer">
            <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
            <a href="<?php echo BASE_URL; ?>/login.php">Sign In</a>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="auth-right">
        <div class="auth-form-box">
            
            <a href="<?php echo BASE_URL; ?>/index.php" class="auth-mobile-logo">
                <img src="<?php echo BASE_URL; ?>/assets/logo.png" alt="Jevani Store">
            </a>

            <?php if ($step === 'request'): ?>
            <!-- ── Step 1: Enter email ── -->
            <a href="<?php echo BASE_URL; ?>/login.php" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Sign In
            </a>

            <div class="icon-header">
                <div class="icon-circle indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.72 9.5 19.79 19.79 0 0 1 1.64 4.8 2 2 0 0 1 3.61 2.6h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.1a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.62 17.52z"/></svg>
                </div>
                <div class="auth-form-eyebrow">Account Recovery</div>
                <h1 class="auth-form-title">Forgot your password?</h1>
                <p class="auth-form-desc">Enter the email linked to your account and we'll send you a secure reset link.</p>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" id="forgot-form">
                <input type="hidden" name="action" value="request_reset">
                <div class="form-field">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required autocomplete="email">
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="send-btn">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2 11 13"/><path d="M22 2 15 22 11 13 2 9l20-7z"/></svg>
                    Send Reset Link
                </button>
            </form>

            <div class="auth-form-footer">
                Remember it now? <a href="<?php echo BASE_URL; ?>/login.php">Sign in</a>
            </div>

            <?php elseif ($step === 'sent'): ?>
            <!-- ── Step 2: Email sent confirmation ── -->
            <div class="sent-box">
                <div class="sent-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div class="sent-title">Check your email</div>
                <p class="sent-desc">
                    If your email is registered with us, you'll receive a password reset link shortly.<br><br>
                    The link will expire in <strong>1 hour</strong>. Check your spam folder if you don't see it.
                </p>

                <?php if ($dev_link): ?>
                <div class="dev-link-box">
                    <strong>🛠 Dev Mode — Reset Link (no email configured)</strong>
                    <a href="<?php echo htmlspecialchars($dev_link); ?>"><?php echo htmlspecialchars($dev_link); ?></a>
                </div>
                <?php endif; ?>

                <a href="<?php echo BASE_URL; ?>/login.php" class="auth-btn" style="text-decoration:none; display:inline-flex; width:auto; padding: 13px 32px; margin: 0 auto;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Back to Sign In
                </a>

                <div class="auth-form-footer" style="margin-top: 20px;">
                    Didn't get an email? <a href="<?php echo BASE_URL; ?>/forgot_password.php" style="color: var(--accent);">Try again</a>
                </div>
            </div>

            <?php elseif ($step === 'reset' && $token_valid): ?>
            <!-- ── Step 3: Enter new password ── -->
            <a href="<?php echo BASE_URL; ?>/login.php" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Sign In
            </a>

            <div class="icon-header">
                <div class="icon-circle indigo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <div class="auth-form-eyebrow">Set New Password</div>
                <h1 class="auth-form-title">Create a new password</h1>
                <p class="auth-form-desc">Choose a strong password that you haven't used before.</p>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" id="reset-form">
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">

                <div class="form-field">
                    <label for="new_password">New Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" id="new_password" name="new_password" class="form-input has-toggle" placeholder="Min. 6 characters" required autocomplete="new-password">
                        <button type="button" class="toggle-password" onclick="togglePwd('new_password', this)" tabindex="-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="form-field">
                    <label for="confirm_password">Confirm New Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input has-toggle" placeholder="Repeat password" required autocomplete="new-password">
                        <button type="button" class="toggle-password" onclick="togglePwd('confirm_password', this)" tabindex="-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="reset-btn">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Reset Password
                </button>
            </form>

            <?php elseif ($step === 'done'): ?>
            <!-- ── Done ── -->
            <div class="sent-box">
                <div class="done-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div class="sent-title">Password updated! 🎉</div>
                <p class="sent-desc">Your password has been successfully reset.<br>You can now sign in with your new password.</p>
                <a href="<?php echo BASE_URL; ?>/login.php" class="auth-btn" style="text-decoration:none; display:inline-flex; width:auto; padding: 14px 36px; margin: 0 auto;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Go to Sign In
                </a>
            </div>

            <?php else: ?>
            <!-- ── Invalid/expired link ── -->
            <a href="<?php echo BASE_URL; ?>/forgot_password.php" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Try again
            </a>

            <div class="auth-alert error" style="margin-top: 40px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo htmlspecialchars($error ?: 'Invalid or expired reset link.'); ?>
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

        const forgotForm = document.getElementById('forgot-form');
        if (forgotForm) {
            forgotForm.addEventListener('submit', function() {
                const btn = document.getElementById('send-btn');
                btn.innerHTML = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Sending...';
                btn.disabled = true;
            });
        }

        const resetForm = document.getElementById('reset-form');
        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                const pwd = document.getElementById('new_password').value;
                const cpwd = document.getElementById('confirm_password').value;
                if (pwd !== cpwd) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return;
                }
                const btn = document.getElementById('reset-btn');
                btn.innerHTML = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Resetting...';
                btn.disabled = true;
            });
        }
    </script>

    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
</body>
</html>
