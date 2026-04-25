<?php
use App\Models\SettingModel;

// Resilient: kalau tabel `settings` belum ada (migration belum dijalankan), pakai default
$s = [];
try {
    $s = (new SettingModel())->getAll();
} catch (\Throwable $e) {
    log_message('warning', 'SettingModel failed (run `php spark migrate`?): ' . $e->getMessage());
    $s = [];
}

$copyright = str_replace('{year}', date('Y'), $s['footer_copyright'] ?? '© ' . date('Y') . ' CariIn. Semua hak dilindungi.');
?>
<footer class="site-footer">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="<?= site_url('/') ?>" class="footer-brand">
                    <span class="brand-mark brand-mark-brown">CI</span>
                    <strong>CariIn</strong>
                </a>
                <p class="mt-3 text-muted small">
                    <?= esc($s['footer_description'] ?? 'Marketplace properti Indonesia yang menghubungkan penjual & pembeli dengan lebih cepat, aman, dan terpercaya.') ?>
                </p>
                <div class="mt-2">
                    <?php if (! empty($s['footer_facebook'])): ?><a class="me-2" href="<?= esc($s['footer_facebook']) ?>" target="_blank"><i class="bi bi-facebook"></i></a><?php endif; ?>
                    <?php if (! empty($s['footer_instagram'])): ?><a class="me-2" href="<?= esc($s['footer_instagram']) ?>" target="_blank"><i class="bi bi-instagram"></i></a><?php endif; ?>
                    <?php if (! empty($s['footer_twitter'])): ?><a class="me-2" href="<?= esc($s['footer_twitter']) ?>" target="_blank"><i class="bi bi-twitter"></i></a><?php endif; ?>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Jelajahi</h6>
                <ul class="footer-links">
                    <li><a href="<?= site_url('/search?type=sell') ?>">Beli Properti</a></li>
                    <li><a href="<?= site_url('/search?type=rent') ?>">Sewa Properti</a></li>
                    <li><a href="<?= site_url('/category/rumah') ?>">Rumah</a></li>
                    <li><a href="<?= site_url('/category/tanah') ?>">Tanah</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Akun</h6>
                <ul class="footer-links">
                    <?php if (auth_user()): ?>
                        <li><a href="<?= site_url('/dashboard') ?>">Dashboard</a></li>
                        <li><a href="<?= site_url('/ads/create') ?>">Pasang Iklan</a></li>
                    <?php else: ?>
                        <li><a href="<?= site_url('/login') ?>">Login</a></li>
                        <li><a href="<?= site_url('/register') ?>">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="footer-title">Kontak</h6>
                <?php if (! empty($s['footer_email'])): ?><p class="small text-muted mb-1"><i class="bi bi-envelope me-2"></i><?= esc($s['footer_email']) ?></p><?php endif; ?>
                <?php if (! empty($s['footer_phone'])): ?><p class="small text-muted mb-1"><i class="bi bi-telephone me-2"></i><?= esc($s['footer_phone']) ?></p><?php endif; ?>
                <?php if (! empty($s['footer_address'])): ?><p class="small text-muted mb-1"><i class="bi bi-geo-alt me-2"></i><?= esc($s['footer_address']) ?></p><?php endif; ?>
            </div>
        </div>
        <hr class="mt-4 mb-3 opacity-25">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted">
            <span><?= esc($copyright) ?></span>
            
        </div>
    </div>
</footer>
