<?php
// ============================================
// File: login.php
// Landing Page & Halaman Login
// ============================================
session_start();

// Jika sudah login, redirect ke index
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit;
}

require 'koneksi.php';

$error = '';
$show_transition = false;

// Proses login (metode POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitasi($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Kredensial default (untuk keperluan UAS)
    // Username: admin | Password: admin123
    $default_user = 'admin';
    $default_pass = 'admin123';

    // Percabangan: validasi login
    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi!';
    } elseif ($username === $default_user && $password === $default_pass) {
        // Login berhasil - set session
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        $show_transition = true;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Sistem Inventaris Barang InvenTrack">
    <title>Login | InvenTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ============ LOGIN PAGE STYLES ============ */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            background: #050a18;
        }

        /* Animated mesh background */
        .login-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .login-bg .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
        }

        .login-bg .orb-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.2), transparent 70%);
            top: -15%;
            right: -10%;
            animation: orbMove1 12s infinite;
        }

        .login-bg .orb-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.15), transparent 70%);
            bottom: -10%;
            left: -8%;
            animation: orbMove2 15s infinite;
        }

        .login-bg .orb-3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08), transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: orbMove3 10s infinite;
        }

        @keyframes orbMove1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-60px, 40px) scale(1.1); }
            50% { transform: translate(-30px, -30px) scale(0.9); }
            75% { transform: translate(40px, 20px) scale(1.05); }
        }

        @keyframes orbMove2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -40px) scale(1.1); }
            66% { transform: translate(-30px, 30px) scale(0.95); }
        }

        @keyframes orbMove3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.3); opacity: 0.3; }
        }

        /* Grid lines background */
        .login-bg .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(124, 58, 237, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124, 58, 237, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 70%);
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(124, 58, 237, 0.4);
            border-radius: 50%;
            animation: particleFloat linear infinite;
        }

        .particle:nth-child(2) { width: 2px; height: 2px; background: rgba(6, 182, 212, 0.3); }
        .particle:nth-child(3) { width: 4px; height: 4px; background: rgba(124, 58, 237, 0.2); }
        .particle:nth-child(4) { width: 2px; height: 2px; background: rgba(245, 158, 11, 0.3); }
        .particle:nth-child(5) { width: 3px; height: 3px; background: rgba(6, 182, 212, 0.4); }
        .particle:nth-child(6) { width: 2px; height: 2px; background: rgba(124, 58, 237, 0.3); }
        .particle:nth-child(7) { width: 3px; height: 3px; background: rgba(16, 185, 129, 0.3); }
        .particle:nth-child(8) { width: 2px; height: 2px; background: rgba(124, 58, 237, 0.2); }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }

        /* Login container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            animation: loginAppear 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes loginAppear {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Brand header */
        .login-brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-brand-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(6, 182, 212, 0.1));
            border: 1px solid rgba(124, 58, 237, 0.15);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.2rem;
            position: relative;
            animation: iconFloat 3s ease-in-out infinite;
        }

        .login-brand-icon i {
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 12px rgba(124, 58, 237, 0.4));
        }

        .login-brand-icon::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.3), rgba(6, 182, 212, 0.2), transparent);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .login-brand-icon:hover::after {
            opacity: 1;
        }

        .login-brand h1 {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #ffffff 0%, #c4b5fd 50%, var(--secondary-light) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s linear infinite;
            margin-bottom: 0.4rem;
        }

        .login-brand p {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 400;
        }

        /* Login card */
        .login-card {
            background: rgba(10, 15, 30, 0.6);
            backdrop-filter: blur(24px) saturate(1.4);
            -webkit-backdrop-filter: blur(24px) saturate(1.4);
            border: 1px solid rgba(148, 163, 184, 0.08);
            border-radius: 24px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 5%;
            right: 5%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(124, 58, 237, 0.4), rgba(6, 182, 212, 0.2), transparent);
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 20%, rgba(124, 58, 237, 0.04) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Login form inputs */
        .login-input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .login-input-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            letter-spacing: 0.03em;
        }

        .login-input-group label i {
            color: var(--primary-light);
            margin-right: 0.3rem;
        }

        .login-input-wrapper {
            position: relative;
        }

        .login-input-wrapper .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .login-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.08);
            color: var(--text-primary);
            border-radius: 14px;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            font-size: 0.92rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .login-input::placeholder {
            color: var(--text-muted);
        }

        .login-input:focus {
            outline: none;
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2), 0 0 30px rgba(124, 58, 237, 0.08);
        }

        .login-input:focus + .input-focus-effect {
            opacity: 1;
        }

        .login-input-wrapper:focus-within .input-icon {
            color: var(--primary-light);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1rem;
            z-index: 2;
            transition: color 0.3s ease;
            padding: 0.25rem;
        }

        .password-toggle:hover {
            color: var(--primary-light);
        }

        /* Login button */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.9rem;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 24px rgba(124, 58, 237, 0.3);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.03em;
            margin-top: 0.5rem;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 36px rgba(124, 58, 237, 0.45);
        }

        .btn-login:active {
            transform: translateY(-1px) scale(0.98);
        }

        /* Error alert */
        .login-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: var(--danger-light);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        /* Credentials hint */
        .login-hint {
            text-align: center;
            margin-top: 1.8rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(148, 163, 184, 0.06);
        }

        .login-hint p {
            color: var(--text-muted);
            font-size: 0.78rem;
            margin-bottom: 0.5rem;
        }

        .login-hint .credentials {
            display: inline-flex;
            gap: 1.5rem;
            background: rgba(124, 58, 237, 0.06);
            border: 1px solid rgba(124, 58, 237, 0.1);
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-size: 0.78rem;
            color: var(--text-secondary);
        }

        .login-hint .credentials code {
            color: var(--primary-light);
            font-weight: 600;
            background: none;
            font-size: 0.8rem;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .login-footer .ai-badge {
            font-size: 0.7rem;
        }

        /* ============ TRANSITION OVERLAY ============ */
        .transition-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: #050a18;
            opacity: 0;
            pointer-events: none;
        }

        .transition-overlay.active {
            opacity: 1;
            pointer-events: all;
            animation: overlayFadeIn 0.5s ease forwards;
        }

        @keyframes overlayFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Transition content */
        .transition-content {
            text-align: center;
            animation: transitionPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards;
            opacity: 0;
            transform: scale(0.8);
        }

        @keyframes transitionPop {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .transition-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(6, 182, 212, 0.1));
            border: 1px solid rgba(124, 58, 237, 0.2);
            border-radius: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            animation: transitionSpin 1.5s ease-in-out 0.5s;
        }

        @keyframes transitionSpin {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .transition-icon i {
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .transition-text {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .transition-subtext {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Loading dots */
        .loading-dots {
            display: flex;
            gap: 6px;
            justify-content: center;
            margin-top: 2rem;
        }

        .loading-dots span {
            width: 8px;
            height: 8px;
            background: var(--primary-light);
            border-radius: 50%;
            animation: dotBounce 1.2s ease-in-out infinite;
        }

        .loading-dots span:nth-child(2) { animation-delay: 0.15s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.3s; }

        @keyframes dotBounce {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.3; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        /* Radial pulse behind transition */
        .transition-pulse {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            animation: transitionPulse 2s ease-in-out infinite;
        }

        @keyframes transitionPulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.5); opacity: 0.2; }
        }

        /* Wipe transition */
        .transition-wipe {
            position: fixed;
            inset: 0;
            z-index: 10000;
            background: linear-gradient(135deg, var(--primary-dark), #050a18);
            transform: scaleX(0);
            transform-origin: left;
            pointer-events: none;
        }

        .transition-wipe.active {
            animation: wipeIn 0.6s cubic-bezier(0.7, 0, 0.3, 1) 2s forwards,
                       wipeOut 0.6s cubic-bezier(0.7, 0, 0.3, 1) 2.5s forwards;
        }

        @keyframes wipeIn {
            from { transform: scaleX(0); transform-origin: left; }
            to { transform: scaleX(1); transform-origin: left; }
        }

        @keyframes wipeOut {
            from { transform: scaleX(1); transform-origin: right; }
            to { transform: scaleX(0); transform-origin: right; }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 1.8rem 1.5rem;
                border-radius: 20px;
            }

            .login-brand h1 {
                font-size: 1.6rem;
            }

            .login-brand-icon {
                width: 60px;
                height: 60px;
                font-size: 1.6rem;
            }

            .login-hint .credentials {
                flex-direction: column;
                gap: 0.3rem;
            }
        }
    </style>
</head>
<body>

    <!-- ============ TRANSITION OVERLAY ============ -->
    <div class="transition-overlay" id="transitionOverlay">
        <div class="transition-pulse"></div>
        <div class="transition-content">
            <div class="transition-icon">
                <i class="bi bi-check-lg"></i>
            </div>
            <div class="transition-text">Login Berhasil!</div>
            <div class="transition-subtext">Mempersiapkan dashboard Anda...</div>
            <div class="loading-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <!-- Wipe transition -->
    <div class="transition-wipe" id="transitionWipe"></div>

    <!-- ============ BACKGROUND ============ -->
    <div class="login-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-overlay"></div>
    </div>

    <!-- Floating particles -->
    <div class="particles" id="particles">
        <div class="particle" style="left:10%; animation-duration:8s; animation-delay:0s;"></div>
        <div class="particle" style="left:25%; animation-duration:12s; animation-delay:2s;"></div>
        <div class="particle" style="left:40%; animation-duration:10s; animation-delay:1s;"></div>
        <div class="particle" style="left:55%; animation-duration:9s; animation-delay:3s;"></div>
        <div class="particle" style="left:70%; animation-duration:11s; animation-delay:0.5s;"></div>
        <div class="particle" style="left:85%; animation-duration:7s; animation-delay:2.5s;"></div>
        <div class="particle" style="left:15%; animation-duration:13s; animation-delay:1.5s;"></div>
        <div class="particle" style="left:60%; animation-duration:14s; animation-delay:4s;"></div>
    </div>

    <!-- ============ LOGIN CONTENT ============ -->
    <div class="login-page">
        <div class="login-container">

            <!-- Brand -->
            <div class="login-brand">
                <div class="login-brand-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <h1>InvenTrack</h1>
                <p>Sistem Inventaris Barang &mdash; Masuk untuk mengelola inventaris</p>
            </div>

            <!-- Login Card -->
            <div class="login-card">

                <?php if (!empty($error)) : ?>
                    <div class="login-error">
                        <i class="bi bi-shield-exclamation"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php" id="formLogin">
                    <!-- Username -->
                    <div class="login-input-group">
                        <label><i class="bi bi-person"></i> Username</label>
                        <div class="login-input-wrapper">
                            <i class="bi bi-person-fill input-icon"></i>
                            <input type="text" class="login-input" name="username" id="username"
                                   placeholder="Masukkan username" required autocomplete="username"
                                   value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="login-input-group">
                        <label><i class="bi bi-lock"></i> Password</label>
                        <div class="login-input-wrapper">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" class="login-input" name="password" id="password"
                                   placeholder="Masukkan password" required autocomplete="current-password">
                            <button type="button" class="password-toggle" id="togglePassword" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-login" id="btnLogin">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

                <!-- Credentials hint -->
                <div class="login-hint">
                    <p>Demo Credentials</p>
                    <div class="credentials">
                        <span><i class="bi bi-person-fill me-1"></i>User: <code>admin</code></span>
                        <span><i class="bi bi-key-fill me-1"></i>Pass: <code>admin123</code></span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> InvenTrack &mdash; UAS Pemrograman Web</p>
                <p>Dikembangkan dengan bantuan <span class="ai-badge">🤖 GenAI</span></p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });

        // ============ TRANSITION ANIMATION ============
        <?php if ($show_transition) : ?>
        (function() {
            const overlay = document.getElementById('transitionOverlay');
            const wipe = document.getElementById('transitionWipe');

            // Step 1: Show overlay with check animation
            overlay.classList.add('active');

            // Step 2: Trigger wipe transition
            setTimeout(function() {
                wipe.classList.add('active');
            }, 800);

            // Step 3: Redirect to dashboard during wipe
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2600);
        })();
        <?php endif; ?>
    </script>

</body>
</html>
