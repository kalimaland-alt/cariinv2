<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard - CariIn') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">
</head>
<body class="dashboard-body">
<div class="dashboard-wrap">
    <aside class="dashboard-sidebar">
        <a href="<?= site_url('/') ?>" class="sidebar-brand">
            <span class="brand-mark">CI</span>
            <span>CariIn</span>
        </a>
        <nav class="sidebar-nav">
            <a class="sidebar-link" href="<?= site_url('/dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="sidebar-link" href="<?= site_url('/my-ads') ?>"><i class="bi bi-card-list"></i> Iklan Saya</a>
            <a class="sidebar-link" href="<?= site_url('/ads/create') ?>"><i class="bi bi-plus-circle"></i> Buat Iklan</a>
            <a class="sidebar-link" href="<?= site_url('/wishlist') ?>"><i class="bi bi-heart"></i> Wishlist</a>
            <a class="sidebar-link" href="<?= site_url('/chat') ?>"><i class="bi bi-chat-dots"></i> Pesan</a>
            <a class="sidebar-link" href="<?= site_url('/topup') ?>"><i class="bi bi-coin"></i> Top Up Poin</a>
            <a class="sidebar-link" href="<?= site_url('/topup/history') ?>"><i class="bi bi-clock-history"></i> Riwayat Top Up</a>
            <a class="sidebar-link" href="<?= site_url('/dashboard/profile') ?>"><i class="bi bi-person-circle"></i> Profil</a>
            <div class="sidebar-divider"></div>
            <a class="sidebar-link" href="<?= site_url('/') ?>"><i class="bi bi-house"></i> Kembali ke Situs</a>
            <a class="sidebar-link text-danger" href="<?= site_url('/logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </nav>
    </aside>
    <div class="dashboard-main">
        <header class="dashboard-topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-sage d-lg-none" onclick="document.querySelector('.dashboard-sidebar').classList.toggle('open')">
                    <i class="bi bi-list"></i>
                </button>
                <strong><?= esc($title ?? 'Dashboard') ?></strong>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted d-none d-md-inline">Halo, <?= esc($auth_user['name'] ?? 'User') ?></span>
                <?php if (! empty($auth_user['avatar_url'])): ?>
                    <img src="<?= esc($auth_user['avatar_url']) ?>" alt="" class="avatar-sm">
                <?php else: ?>
                    <span class="avatar-sm avatar-initial"><?= strtoupper(substr($auth_user['name'] ?? 'U', 0, 1)) ?></span>
                <?php endif; ?>
            </div>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mx-3 mt-3"><i class="bi bi-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mx-3 mt-3"><i class="bi bi-exclamation-triangle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info mx-3 mt-3"><i class="bi bi-info-circle"></i> <?= esc(session()->getFlashdata('info')) ?></div>
        <?php endif; ?>
        <?php if (auth_user() && session()->get('email_verified') === false): ?>
            <div class="alert alert-warning mx-3 mt-3 d-flex align-items-center justify-content-between">
                <div><i class="bi bi-envelope-exclamation me-2"></i> Email Anda belum diverifikasi. Cek inbox/spam.</div>
                <a href="<?= site_url('/resend-verification') ?>" class="btn btn-sm btn-warning">Kirim ulang link</a>
            </div>
        <?php endif; ?>

        <div class="dashboard-content">
            <?= $page_content ?? '' ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>
</html>
