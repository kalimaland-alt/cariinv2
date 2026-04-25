<section class="py-5 bg-cream">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Blog</li>
            </ol>
        </nav>
        <h1 class="section-title mb-1">Blog CariIn</h1>
        <p class="text-muted">Tips properti, berita pasar, dan panduan investasi.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php
            $posts = [
                ['title' => 'Tips Memilih Rumah Pertama untuk Keluarga Muda', 'excerpt' => 'Panduan lengkap untuk pasangan muda yang ingin membeli rumah pertama mereka.', 'img' => 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800&auto=format&fit=crop&q=70', 'cat' => 'Tips'],
                ['title' => 'Investasi Tanah vs Apartemen: Mana yang Lebih Menguntungkan?', 'excerpt' => 'Analisis pro-kontra investasi tanah dan apartemen dari sisi ROI jangka panjang.', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&auto=format&fit=crop&q=70', 'cat' => 'Investasi'],
                ['title' => 'Cara Mengajukan KPR dengan Bunga Rendah di 2026', 'excerpt' => 'Tips & trik agar pengajuan KPR Anda cepat disetujui dengan bunga kompetitif.', 'img' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&auto=format&fit=crop&q=70', 'cat' => 'KPR'],
            ];
            foreach ($posts as $p):
            ?>
                <div class="col-md-6 col-lg-4">
                    <article class="property-card">
                        <div class="property-card-media">
                            <img src="<?= esc($p['img']) ?>" alt="" loading="lazy">
                            <span class="badge-cat"><?= esc($p['cat']) ?></span>
                        </div>
                        <div class="property-card-body">
                            <h3 class="property-card-title"><a href="#"><?= esc($p['title']) ?></a></h3>
                            <p class="text-muted small"><?= esc($p['excerpt']) ?></p>
                            <a href="#" class="btn btn-link p-0 text-emerald fw-semibold mt-auto">Baca selengkapnya →</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <p class="text-muted small">Artikel lainnya akan segera hadir. Stay tuned! 🚀</p>
        </div>
    </div>
</section>
