<!-- HERO V2 -->
<section class="hero-v2">
    <div class="hero-v2-inner">
        <div class="container">
            <span class="hero-badge"><i class="bi bi-graph-up-arrow"></i> <?= number_format($stats['total_properties'] ?? 0) ?>+ properti terbaru di Indonesia</span>
            <h1 class="hero-v2-title">Temukan Properti <span class="accent">Impian</span> Anda</h1>
            <p class="hero-v2-subtitle">Ribuan pilihan rumah, tanah, apartemen, dan properti komersial di seluruh Indonesia.</p>

            <form class="hero-search-v2" action="<?= site_url('/search') ?>" method="get" id="heroSearchForm">
                <div class="hero-tabs">
                    <button type="button" class="hero-tab active" data-type="">Semua</button>
                    <button type="button" class="hero-tab" data-type="sell">Dijual</button>
                    <button type="button" class="hero-tab" data-type="rent">Disewa</button>
                </div>
                <input type="hidden" name="type" id="heroTypeInput" value="">
                <div class="hero-search-row">
                    <input type="text" name="q" class="hero-search-input" placeholder="Cari lokasi, nama properti, atau kata kunci...">
                    <select name="category" class="hero-cat-select">
                        <option value="">Kategori</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= esc($c['slug']) ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="hero-btn-cari"><i class="bi bi-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- STATS BAR -->
<section class="hero-stats-bar">
    <div class="container">
        <div class="row g-0">
            <div class="col-6 col-md-3">
                <strong><?= number_format($stats['total_properties'] ?? 0) ?></strong>
                <span>Total Properti</span>
            </div>
            <div class="col-6 col-md-3">
                <strong><?= number_format($stats['total_agents'] ?? 0) ?></strong>
                <span>Agen Terpercaya</span>
            </div>
            <div class="col-6 col-md-3">
                <strong><?= number_format($stats['total_success'] ?? 0) ?></strong>
                <span>Transaksi Berhasil</span>
            </div>
            <div class="col-6 col-md-3">
                <strong><?= number_format($stats['total_cities'] ?? 0) ?></strong>
                <span>Kota Tersedia</span>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES V2 -->
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h2 class="section-title mb-1">Jelajahi Kategori</h2>
            <p class="text-muted mb-0">Pilih tipe properti yang sedang Anda cari.</p>
        </div>
        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= site_url('/category/' . $cat['slug']) ?>" class="category-card-v2 text-decoration-none">
                        <div class="cat-v2-icon"><i class="bi <?= esc($cat['icon']) ?>"></i></div>
                        <div class="cat-v2-name"><?= esc($cat['name']) ?></div>
                        <div class="cat-v2-count"><?= number_format($cat['count'] ?? 0) ?> listing</div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED PROPERTIES -->
<section class="py-5 bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="section-title mb-1">Properti Terbaru</h2>
                <p class="text-muted mb-0"><?= count($featured) ?> properti ditemukan</p>
            </div>
            <a href="<?= site_url('/search') ?>" class="btn btn-outline-emerald btn-sm">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <?php if (empty($featured)): ?>
            <div class="empty-state">
                <i class="bi bi-house-slash"></i>
                <h5>Belum ada properti dipublikasikan</h5>
                <p class="text-muted">Jadilah yang pertama memasang iklan!</p>
                <a href="<?= site_url('/register') ?>" class="btn btn-emerald"><i class="bi bi-plus-circle me-1"></i> Daftar & Pasang Iklan</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($featured as $p): ?>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <?= view('partials/property_card', ['p' => $p]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Cara Kerja CariIn</h2>
            <p class="text-muted">Pasang iklan properti dalam 3 langkah mudah.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="how-card">
                    <div class="how-number">1</div>
                    <i class="bi bi-person-plus how-icon"></i>
                    <h5>Daftar / Login</h5>
                    <p class="text-muted small mb-0">Email + password atau Google. Dapatkan 1 slot iklan GRATIS.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-card">
                    <div class="how-number">2</div>
                    <i class="bi bi-pencil-square how-icon"></i>
                    <h5>Pasang Iklan</h5>
                    <p class="text-muted small mb-0">Isi data properti, upload foto, pin lokasi — sistem akan verifikasi admin.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-card">
                    <div class="how-number">3</div>
                    <i class="bi bi-chat-dots how-icon"></i>
                    <h5>Terhubung</h5>
                    <p class="text-muted small mb-0">Calon pembeli langsung chat/WhatsApp ke Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>
