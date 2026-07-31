<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect(BASE_URL . '/admin/index.php');
    } else {
        redirect(BASE_URL . '/index.php');
    }
}

$error = '';
$step = 'email';
$email = trim($_POST['email'] ?? $_GET['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
    if (strpos($ref, 'login.php') === false && strpos($ref, 'register.php') === false && strpos($ref, 'logout.php') === false) {
        $_SESSION['redirect_after_login'] = $ref;
    }
}

if (!empty($email) && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['step']) && $_GET['step'] == 'password') {
    $step = 'password';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && empty($password)) {
        // Step 1: Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            // User exists -> Ask for password
            $step = 'password';
        } else {
            // New user -> Redirect to register with email pre-filled
            redirect(BASE_URL . '/register.php?email=' . urlencode($email));
            exit;
        }
    } elseif (!empty($email) && !empty($password)) {
        // Step 2: Authenticate password
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
                    $url = $_SESSION['redirect_after_login'] ?? BASE_URL . '/customer/index.php';
                    unset($_SESSION['redirect_after_login']);
                    redirect($url);
                }
            } else {
                $error = 'Incorrect password. Please try again.';
                $step = 'password';
            }
        } else {
            $error = 'No account found with that email address.';
            $step = 'email';
        }
    }
}

$google_client_id = getGoogleClientId();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sign In | Nørva Store</title>
    <meta name="description" content="Sign in or create an account at Nørva Store.">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars(getSetting('site_logo') ? BASE_URL . '/assets/' . getSetting('site_logo') : BASE_URL . '/assets/logo.png'); ?>?v=3">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box !important; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #d6d3d1;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 16px;
            -webkit-font-smoothing: antialiased;
        }

        .auth-card-container {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 32px 32px 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            border: 1.5px solid #1a1a1a;
            position: relative;
        }

        @media (max-width: 480px) {
            .auth-card-container {
                padding: 32px 20px 24px 20px;
                border-radius: 20px;
            }
        }

        .auth-logo-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .auth-logo-header img {
            height: 40px;
            width: auto;
            object-fit: contain;
            filter: brightness(0.1) !important;
        }

        .auth-title {
            font-size: 1.85rem;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
            text-align: center;
        }

        .auth-subtitle {
            font-size: 0.88rem;
            color: #666666;
            margin-bottom: 24px;
            font-weight: 400;
            text-align: center;
        }

        .auth-alert-error {
            background: #fef2f2;
            border: 1.5px solid #ef4444;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Buttons Stack */
        .auth-btn-stack {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 22px;
            width: 100%;
            overflow: hidden;
        }

        /* Force Google GSI button within card */
        .auth-btn-stack > div,
        .g_id_signin,
        .g_id_signin iframe {
            max-width: 100% !important;
            width: 100% !important;
        }

        .btn-google-continue {
            width: 100%;
            padding: 14px 20px;
            background: #ffffff;
            color: #1a1a1a;
            border: 1.5px solid #e5e7eb;
            border-radius: 14px;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            position: relative;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }
        .btn-google-continue:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .badge-last-used {
            position: absolute;
            bottom: -11px;
            left: 50%;
            transform: translateX(-50%);
            background: #f3f4f6;
            color: #555555;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 99px;
            border: 1px solid #e5e7eb;
            text-transform: capitalize;
            letter-spacing: 0.2px;
        }

        /* Divider */
        .auth-divider-or {
            position: relative;
            text-align: center;
            margin: 24px 0 20px 0;
        }
        .auth-divider-or::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        .auth-divider-or span {
            position: relative;
            background: #ffffff;
            padding: 0 12px;
            font-size: 0.8rem;
            color: #888888;
            font-weight: 500;
        }

        /* Inline Input Box with Embedded Forward Arrow */
        .email-input-box-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 12px;
        }

        .email-input-field {
            width: 100%;
            padding: 14px 50px 14px 18px;
            border-radius: 14px;
            border: 2px solid #e60067;
            font-size: 0.95rem;
            color: #1a1a1a;
            background: #ffffff;
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .email-input-field:focus {
            box-shadow: 0 0 0 4px rgba(230, 0, 103, 0.12);
        }

        .arrow-submit-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #1a1a1a;
            color: #ffffff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .arrow-submit-btn:hover {
            background: #000000;
            transform: translateY(-50%) scale(1.05);
        }

        /* Password Field */
        .pwd-input-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 12px;
        }
        .pwd-input-field {
            width: 100%;
            padding: 14px 44px 14px 18px;
            border-radius: 14px;
            border: 1.5px solid #1a1a1a;
            font-size: 0.95rem;
            color: #1a1a1a;
            background: #ffffff;
            outline: none;
        }

        /* Terms & Privacy Links */
        .auth-terms-text {
            font-size: 0.75rem;
            color: #666666;
            text-align: center;
            margin-top: 16px;
            line-height: 1.4;
        }
        .auth-terms-text a {
            color: #1a1a1a;
            text-decoration: underline;
            font-weight: 500;
        }

        .auth-privacy-link {
            display: block;
            text-align: center;
            margin-top: 28px;
            font-size: 0.85rem;
            color: #e60067;
            font-weight: 600;
            text-decoration: none;
        }
        .auth-privacy-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-card-container">
        
        <!-- Header Logo -->
        <div class="auth-logo-header">
            <a href="<?php echo BASE_URL; ?>/index.php">
                <img src="<?php echo BASE_URL; ?>/assets/<?php echo htmlspecialchars(getSetting('site_logo') ?: 'logo.png'); ?>" alt="Nørva Store" onerror="this.src='<?php echo BASE_URL; ?>/assets/logo.png';">
            </a>
        </div>

        <h1 class="auth-title">Sign in</h1>
        <p class="auth-subtitle">Sign in or create an account</p>

        <?php if ($error): ?>
        <div class="auth-alert-error">
            <i data-lucide="alert-circle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <!-- Buttons Stack -->
        <div class="auth-btn-stack">
            <!-- Official Google Identity Services Popup & One-Tap Integration -->
            <div id="g_id_onload"
                 data-client_id="<?php echo htmlspecialchars($google_client_id); ?>"
                 data-callback="handleGoogleCredentialResponse"
                 data-auto_prompt="false">
            </div>

            <div class="g_id_signin"
                 data-type="standard"
                 data-shape="rectangular"
                 data-theme="outline"
                 data-text="continue_with"
                 data-size="large"
                 data-logo_alignment="left"
                 data-width="376">
            </div>
        </div>

        <div class="auth-divider-or">
            <span>or</span>
        </div>

        <!-- Email & Password Form -->
        <form method="POST" action="" id="login-form">
            
            <div class="email-input-box-wrapper">
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required autocomplete="email" class="email-input-field" <?php echo ($step === 'password') ? 'readonly' : ''; ?>>
                
                <?php if ($step === 'email'): ?>
                <button type="submit" class="arrow-submit-btn" aria-label="Continue">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
                <?php endif; ?>
            </div>

            <?php if ($step === 'email'): ?>
            <div style="text-align: right; margin-top: 6px; margin-bottom: 14px;">
                <a href="<?php echo BASE_URL; ?>/forgot_password.php" style="font-size: 0.78rem; color: #e60067; text-decoration: none; font-weight: 600;">Forgot password?</a>
            </div>
            <?php endif; ?>

            <?php if ($step === 'password'): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="font-size: 0.75rem; color: #666;">Enter password for <?php echo htmlspecialchars($email); ?></span>
                <a href="<?php echo BASE_URL; ?>/login.php" style="font-size: 0.72rem; color: #e60067; font-weight: 600; text-decoration: none;">Change email</a>
            </div>

            <div class="pwd-input-wrapper">
                <input type="password" name="password" id="password" placeholder="Password" required class="pwd-input-field" autofocus autocomplete="current-password">
                <button type="submit" class="arrow-submit-btn" style="background: #e60067;" aria-label="Sign In">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
            
            <div style="text-align: right; margin-top: 6px; margin-bottom: 14px;">
                <a href="<?php echo BASE_URL; ?>/forgot_password.php" style="font-size: 0.78rem; color: #e60067; text-decoration: none; font-weight: 600;">Forgot password?</a>
            </div>
            <?php endif; ?>

        </form>

        <div class="auth-terms-text">
            By continuing, you agree to our <a href="<?php echo BASE_URL; ?>/terms_of_service.php">Terms of service</a>
        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 0.88rem; color: #555555;">
            Don't have an account? <a href="<?php echo BASE_URL; ?>/register.php" style="color: #e60067; font-weight: 700; text-decoration: none;">Create account</a>
        </div>

        <a href="<?php echo BASE_URL; ?>/privacy_policy.php" class="auth-privacy-link">Privacy policy</a>

    </div>

    <script>
        lucide.createIcons();

        function handleGoogleCredentialResponse(response) {
            // Show instant loading state so user knows redirect is happening
            const btnStack = document.querySelector('.auth-btn-stack');
            if (btnStack) {
                btnStack.innerHTML = '<div style="display:flex; align-items:center; justify-content:center; gap:10px; padding:12px; background:#f4f4f5; border-radius:12px; font-weight:600; font-size:0.88rem; color:#1a1a1a;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Logging in... Please wait...</div>';
            }

            const data = new FormData();
            data.append('credential', response.credential);

            fetch('<?php echo BASE_URL; ?>/google_callback.php', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(resData => {
                if (resData.status === 'success') {
                    window.location.replace(resData.redirect);
                } else {
                    alert('Google login failed: ' + (resData.message || 'Error'));
                    window.location.reload();
                }
            })
            .catch(err => {
                console.error('Google Auth Network error:', err);
                window.location.reload();
            });
        }
    </script>
    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
</body>
</html>

