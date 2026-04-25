<nav class="navbar navbar-expand-lg navbar-carin sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
            <span class="brand-mark">CI</span>
            <span>Cari<span class="brand-text-accent">In</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/search') ?>">Properti</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/agen') ?>">Agen</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/panduan') ?>">Panduan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/blog') ?>">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url('/compare') ?>"><i class="bi bi-bar-chart-line"></i> Bandingkan</a></li>
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                <?php if ($auth_user ?? null): ?>
                    <li class="nav-item">
                        <a href="<?= site_url('/ads/create') ?>" class="btn btn-emerald btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Pasang Iklan
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <?php if (! empty($auth_user['avatar_url'])): ?>
                                <img src="<?= esc($auth_user['avatar_url']) ?>" class="avatar-sm" alt="">
                            <?php else: ?>
                                <span class="avatar-sm avatar-initial"><?= strtoupper(substr($auth_user['name'] ?? 'U', 0, 1)) ?></span>
                            <?php endif; ?>
                            <span class="d-none d-lg-inline"><?= esc($auth_user['name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($auth_user['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="<?= site_url('/admin') ?>"><i class="bi bi-shield-lock me-2"></i>Admin CMS</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="<?= site_url('/dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('/my-ads') ?>"><i class="bi bi-card-list me-2"></i>Iklan Saya</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('/wishlist') ?>"><i class="bi bi-heart me-2"></i>Wishlist</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('/chat') ?>"><i class="bi bi-chat-dots me-2"></i>Pesan</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('/topup') ?>"><i class="bi bi-coin me-2"></i>Top Up Poin</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('/dashboard/profile') ?>"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= site_url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a href="<?= site_url('/login') ?>" class="nav-link">Masuk</a></li>
                    <li class="nav-item">
                        <a href="<?= site_url('/register') ?>" class="btn btn-emerald btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Pasang Iklan
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
