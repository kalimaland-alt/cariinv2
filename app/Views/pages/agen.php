<section class="py-5 bg-cream">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Agen</li>
            </ol>
        </nav>
        <h1 class="section-title mb-1">Agen Properti Terpercaya</h1>
        <p class="text-muted">Temukan agen berpengalaman yang membantu Anda menemukan properti impian.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (empty($agents)): ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5>Belum ada agen terdaftar</h5>
                <p class="text-muted">Jadilah agen pertama di CariIn!</p>
                <a href="<?= site_url('/register') ?>" class="btn btn-emerald">Daftar sebagai Agen</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($agents as $a): ?>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="agent-card">
                            <div class="agent-avatar">
                                <?php if (! empty($a['avatar_url'])): ?>
                                    <img src="<?= esc($a['avatar_url']) ?>" alt="">
                                <?php else: ?>
                                    <?= strtoupper(substr($a['name'] ?? 'A', 0, 1)) ?>
                                <?php endif; ?>
                            </div>
                            <h6><?= esc($a['name']) ?></h6>
                            <div class="agent-role">Agen Properti</div>
                            <div class="agent-meta">
                                <i class="bi bi-house-door"></i> <?= (int) $a['listing_count'] ?> iklan aktif
                            </div>
                            <?php if (! empty($a['phone'])): ?>
                                <a href="<?= wa_link($a['phone'], 'Halo, saya lihat profil Anda di CariIn') ?>"
                                   target="_blank" class="btn btn-outline-emerald btn-sm mt-3 w-100">
                                    <i class="bi bi-whatsapp"></i> Hubungi
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
