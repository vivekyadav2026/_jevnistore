<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect(BASE_URL . '/index.php');
}

$error = '';
$success = '';
$prefill_email = trim($_GET['email'] ?? $_POST['email'] ?? '');

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
                $_SESSION['user_id']   = $insert->insert_id;
                $_SESSION['role']      = $role;
                $_SESSION['user_name'] = $name;

                $url = $_SESSION['redirect_after_login'] ?? BASE_URL . '/customer/index.php';
                unset($_SESSION['redirect_after_login']);
                redirect($url);
            } else {
                $error = 'Registration failed. Please try again.';
            }
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
    <title>Create Account | Nørva Store</title>
    <meta name="description" content="Create your Nørva Store account.">
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
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }

        .auth-card-container {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            border: 1.5px solid #1a1a1a;
            position: relative;
        }

        @media (max-width: 480px) {
            .auth-card-container {
                padding: 28px 20px;
                border-radius: 20px;
            }
        }

        .auth-logo-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .auth-logo-header img {
            height: 38px;
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
            font-size: 0.85rem;
            color: #666666;
            margin-bottom: 24px;
            font-weight: 400;
            text-align: center;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            width: 100%;
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
            margin-bottom: 20px;
            width: 100%;
            overflow: hidden;
        }

        /* Force Google GSI button to stay within card */
        .auth-btn-stack > div,
        .g_id_signin,
        .g_id_signin iframe {
            max-width: 100% !important;
            width: 100% !important;
        }

        /* Divider */
        .auth-divider-or {
            position: relative;
            text-align: center;
            margin: 20px 0 18px 0;
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

        /* Form Controls */
        .form-group-item {
            margin-bottom: 14px;
        }
        .form-group-item label {
            display: block;
            font-size: 0.76rem;
            font-weight: 700;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .form-group-input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid #1a1a1a;
            font-size: 0.9rem;
            color: #1a1a1a;
            background: #ffffff;
            outline: none;
            font-family: inherit;
        }
        .form-group-input:focus {
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }

        .submit-create-btn {
            width: 100%;
            padding: 14px;
            background: #1a1a1a;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }
        .submit-create-btn:hover {
            background: #333333;
            transform: translateY(-1px);
        }

        /* Terms & Sign In Footer Links */
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
            font-weight: 600;
        }

        .auth-signin-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.88rem;
            color: #555555;
        }
        .auth-signin-footer a {
            color: #e60067;
            font-weight: 700;
            text-decoration: none;
        }
        .auth-signin-footer a:hover {
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

        <h1 class="auth-title">Create account</h1>
        <p class="auth-subtitle">Join Nørva for exclusive drops &amp; rewards</p>

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
                 data-text="signup_with"
                 data-size="large"
                 data-logo_alignment="left"
                 data-width="360">
            </div>
        </div>

        <div class="auth-divider-or">
            <span>or create with email</span>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="" id="register-form">
            
            <div class="form-group-item">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required class="form-group-input">
            </div>

            <div class="form-group-item">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($prefill_email); ?>" required class="form-group-input">
            </div>

            <div class="form-group-item">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Minimum 6 characters" required class="form-group-input">
            </div>

            <div class="form-group-item">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required class="form-group-input">
            </div>

            <button type="submit" class="submit-create-btn" id="create-btn">
                Create Account
            </button>

        </form>

        <div class="auth-terms-text">
            By creating an account, you agree to our <a href="<?php echo BASE_URL; ?>/terms_of_service.php">Terms of service</a> and <a href="<?php echo BASE_URL; ?>/privacy_policy.php">Privacy policy</a>
        </div>

        <div class="auth-signin-footer">
            Already have an account? <a href="<?php echo BASE_URL; ?>/login.php">Sign in</a>
        </div>

    </div>

    <script>
        lucide.createIcons();

        function handleGoogleCredentialResponse(response) {
            const data = new FormData();
            data.append('credential', response.credential);

            fetch('<?php echo BASE_URL; ?>/google_callback.php', {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(resData => {
                if (resData.status === 'success') {
                    window.location.href = resData.redirect;
                } else {
                    alert('Google login failed: ' + (resData.message || 'Error'));
                }
            })
            .catch(err => {
                console.error('Google Auth Network error:', err);
            });
        }
    </script>
</body>
</html>
