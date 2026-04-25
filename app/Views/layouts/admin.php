<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin - CariIn') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">
</head>
<body class="admin-body">
<div class="dashboard-wrap">
    <aside class="dashboard-sidebar admin-sidebar">
        <a href="<?= site_url('/admin') ?>" class="sidebar-brand">
            <span class="brand-mark brand-mark-brown">CI</span>
            <span>CariIn <small class="text-muted">Admin</small></span>
        </a>
        <nav class="sidebar-nav">
            <a class="sidebar-link" href="<?= site_url('/admin') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="sidebar-link" href="<?= site_url('/admin/moderation') ?>"><i class="bi bi-shield-check"></i> Moderasi Iklan</a>
            <a class="sidebar-link" href="<?= site_url('/admin/users') ?>"><i class="bi bi-people"></i> Pengguna</a>
            <a class="sidebar-link" href="<?= site_url('/admin/categories') ?>"><i class="bi bi-tags"></i> Kategori</a>
            <a class="sidebar-link" href="<?= site_url('/admin/finance') ?>"><i class="bi bi-graph-up-arrow"></i> Keuangan</a>
            <a class="sidebar-link" href="<?= site_url('/admin/finance/topup-history') ?>"><i class="bi bi-receipt"></i> Riwayat Top Up</a>
            <a class="sidebar-link" href="<?= site_url('/admin/transactions') ?>"><i class="bi bi-credit-card-2-back"></i> Transaksi Slot</a>
            <a class="sidebar-link" href="<?= site_url('/admin/settings') ?>"><i class="bi bi-gear"></i> Pengaturan</a>
            <div class="sidebar-divider"></div>
            <a class="sidebar-link" href="<?= site_url('/') ?>"><i class="bi bi-house"></i> Situs Publik</a>
            <a class="sidebar-link text-danger" href="<?= site_url('/logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </nav>
    </aside>
    <div class="dashboard-main">
        <header class="dashboard-topbar">
            <div><strong><?= esc($title ?? 'Admin Dashboard') ?></strong></div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-brown text-white">ADMIN</span>
                <span class="text-muted d-none d-md-inline"><?= esc($auth_user['name'] ?? 'Admin') ?></span>
            </div>
        </header>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mx-3 mt-3"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mx-3 mt-3"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info mx-3 mt-3"><?= esc(session()->getFlashdata('info')) ?></div>
        <?php endif; ?>
        <div class="dashboard-content">
            <?= $page_content ?? '' ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
