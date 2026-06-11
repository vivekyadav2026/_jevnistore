<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | GENRAGE.</title>
    <link rel="stylesheet" href="/styles.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
            background: #0a0a0a;
        }
        .admin-sidebar {
            width: 250px;
            background: #111;
            border-right: 1px solid var(--border-color);
            padding: 2rem 1rem;
        }
        .admin-sidebar .logo {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 2rem;
            text-align: center;
        }
        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .admin-nav a:hover, .admin-nav a.active {
            background: #222;
            color: var(--accent);
        }
        .admin-main {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .table th, .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #333;
            text-align: left;
            color: var(--text-primary);
        }
        .table th {
            background: #1a1a1a;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .table tr:hover td {
            background: #1a1a1a;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            background: #111;
            border: 1px solid #333;
            color: white;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        /* ── Admin Toast Notification System ── */
        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 300px;
            max-width: 400px;
            padding: 14px 18px;
            border-radius: 10px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            box-shadow: 0 8px 32px rgba(0,0,0,0.6);
            pointer-events: all;
            animation: toastSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
            position: relative;
            overflow: hidden;
        }
        .toast.toast-hide {
            animation: toastSlideOut 0.3s ease-in forwards;
        }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(120%); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastSlideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(120%); }
        }
        .toast-icon { width: 22px; height: 22px; flex-shrink: 0; margin-top: 1px; }
        .toast-body { flex: 1; }
        .toast-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .toast-msg {
            font-size: 0.87rem;
            color: #aaa;
            line-height: 1.4;
        }
        .toast-close {
            background: none;
            border: none;
            color: #555;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .toast-close:hover { color: #fff; }
        .toast-progress {
            position: absolute;
            bottom: 0; left: 0;
            height: 3px;
            border-radius: 0 0 10px 10px;
            animation: toastProgress linear forwards;
        }
        @keyframes toastProgress { from{width:100%;} to{width:0%;} }
        .toast-success .toast-icon, .toast-success .toast-title { color: #22c55e; }
        .toast-success .toast-progress { background: #22c55e; }
        .toast-error   .toast-icon, .toast-error   .toast-title { color: #ef4444; }
        .toast-error   .toast-progress { background: #ef4444; }
        .toast-info    .toast-icon, .toast-info    .toast-title { color: #38bdf8; }
        .toast-info    .toast-progress { background: #38bdf8; }
        .toast-warning .toast-icon, .toast-warning .toast-title { color: #f59e0b; }
        .toast-warning .toast-progress { background: #f59e0b; }
    </style>
</head>
<body>

<div id="toast-container"></div>

<?php $flash = getFlash(); if ($flash): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showToast(<?php echo json_encode($flash['msg']); ?>, '<?php echo $flash['type']; ?>');
    });
</script>
<?php endif; ?>

<script>
    const _toastIcons = {
        success: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        error:   '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        info:    '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        warning: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'
    };
    const _toastTitles = { success:'Success', error:'Error', info:'Info', warning:'Warning' };

    function showToast(message, type = 'success', duration = 4500) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            ${_toastIcons[type] || _toastIcons.info}
            <div class="toast-body">
                <div class="toast-title">${_toastTitles[type] || type}</div>
                <div class="toast-msg">${message}</div>
            </div>
            <button class="toast-close" onclick="_dismissToast(this.parentElement)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="toast-progress" style="animation-duration:${duration}ms"></div>
        `;
        container.appendChild(toast);
        setTimeout(() => _dismissToast(toast), duration);
    }

    function _dismissToast(toast) {
        if (!toast || toast.classList.contains('toast-hide')) return;
        toast.classList.add('toast-hide');
        setTimeout(() => toast.remove(), 300);
    }
</script>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <a href="/index.php" class="logo" style="display: block; text-align: center; margin-bottom: 2rem;">
            <img src="/assets/logo.png" alt="Jevani Store Admin" style="height: 50px; width: auto;">
        </a>
        <nav class="admin-nav">
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i data-lucide="layout-dashboard"></i> Dashboard</a>
            <a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>"><i data-lucide="layers"></i> Categories</a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>"><i data-lucide="box"></i> Products</a>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>"><i data-lucide="shopping-cart"></i> Orders</a>
            <a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>"><i data-lucide="users"></i> Customers</a>
            <a href="/logout.php" style="margin-top: auto; color: #ff4444;"><i data-lucide="log-out"></i> Logout</a>
        </nav>
    </aside>
    
    <main class="admin-main">
        <header class="admin-header">
            <h2 style="margin:0; font-size: 1.5rem; letter-spacing: 1px;">Admin Dashboard</h2>
            <div style="color: var(--text-secondary); font-size: 0.9rem;">
                Logged in as <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
            </div>
        </header>
