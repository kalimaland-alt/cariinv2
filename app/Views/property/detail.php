<?php
$coverCandidate = null;
if (! empty($images)) {
    $coverCandidate = $images[0];
    foreach ($images as $img) {
        if (! empty($img['is_cover'])) { $coverCandidate = $img; break; }
    }
}
$formType = $property['form_type'] ?? 'building';
$specsMap = [
    'building' => [
        'land_area'     => ['Luas Tanah',    'bi-aspect-ratio', ' m²'],
        'building_area' => ['Luas Bangunan', 'bi-rulers',       ' m²'],
        'bedrooms'      => ['Kamar Tidur',   'bi-door-closed',  ''],
        'bathrooms'     => ['Kamar Mandi',   'bi-droplet',      ''],
        'floors'        => ['Jumlah Lantai', 'bi-layers',       ''],
        'kitchens'      => ['Dapur',         'bi-basket',       ''],
    ],
    'land' => [
        'land_area'    => ['Luas Lahan',        'bi-aspect-ratio', ' m²'],
        'land_shape'   => ['Bentuk Lahan',      'bi-bounding-box', ''],
        'road_width'   => ['Lebar Akses Jalan', 'bi-signpost',     ' m'],
        'road_access'  => ['Akses Kendaraan',   'bi-truck',        ''],
    ],
];
$specs = $specsMap[$formType] ?? $specsMap['building'];
$waText = "Halo, saya tertarik dengan iklan \"" . ($property['title'] ?? '') . "\" di CariIn. Apakah masih tersedia?";
$shareUrl = current_url();
$shareText = $property['title'] . ' - ' . rupiah($property['price']) . ' di CariIn';
$shareImg  = property_image_url($coverCandidate['file_name'] ?? null);
?>
<!-- Open Graph / SEO -->
<meta property="og:title" content="<?= esc($property['title']) ?>" />
<meta property="og:description" content="<?= esc(mb_strimwidth(strip_tags((string)($property['description'] ?? '')), 0, 160, '...')) ?>" />
<meta property="og:image" content="<?= esc($shareImg) ?>" />
<meta property="og:url" content="<?= esc($shareUrl) ?>" />
<meta property="og:type" content="product" />
<meta name="twitter:card" content="summary_large_image" />
<section class="py-4 bg-cream">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('/category/' . $property['category_slug']) ?>"><?= esc($property['category_name']) ?></a></li>
                <li class="breadcrumb-item active"><?= esc($property['city'] ?? 'Detail') ?></li>
            </ol>
        </nav>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <!-- GALLERY -->
        <div class="property-gallery mb-4">
            <div class="gallery-main">
                <img id="galleryMain" src="<?= property_image_url($coverCandidate['file_name'] ?? null) ?>" alt="">
                <?php if ($property['status'] !== 'published'): ?>
                    <span class="gallery-status-badge">STATUS: <?= strtoupper(str_replace('_', ' ', $property['status'])) ?></span>
                <?php endif; ?>
            </div>
            <?php if (! empty($images) && count($images) > 1): ?>
                <div class="gallery-thumbs">
                    <?php foreach ($images as $img): ?>
                        <img src="<?= property_image_url($img['file_name']) ?>" alt=""
                             onclick="document.getElementById('galleryMain').src=this.src"
                             class="gallery-thumb">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                    <span class="badge-type <?= $property['type']==='sell'?'badge-sell':'badge-rent' ?>"><?= $property['type']==='sell'?'DIJUAL':'DISEWA' ?></span>
                    <span class="badge-cat"><?= esc($property['category_name']) ?></span>
                    <span class="badge-verified"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>

                    <!-- Share buttons -->
                    <div class="ms-auto d-flex gap-1 align-items-center share-buttons">
                        <small class="text-muted me-1">Bagikan:</small>
                        <a href="https://wa.me/?text=<?= urlencode($shareText . ' ' . $shareUrl) ?>" target="_blank" class="btn btn-sm btn-success" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>" target="_blank" class="btn btn-sm" style="background:#1877F2;color:#fff;" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($shareText) ?>&url=<?= urlencode($shareUrl) ?>" target="_blank" class="btn btn-sm" style="background:#1DA1F2;color:#fff;" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                        <button type="button" onclick="copyToInstagram()" class="btn btn-sm" style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;" title="Salin link untuk Instagram"><i class="bi bi-instagram"></i></button>
                        <button type="button" onclick="navigator.clipboard.writeText('<?= esc($shareUrl) ?>').then(()=>alert('Link disalin!'))" class="btn btn-sm btn-outline-secondary" title="Salin Link"><i class="bi bi-link-45deg"></i></button>
                        <button type="button" onclick="addToCompare(<?= (int)$property['id'] ?>)" class="btn btn-sm btn-outline-warning" title="Bandingkan"><i class="bi bi-bar-chart-line"></i></button>
                    </div>
                </div>
                <h1 class="h2 mb-2"><?= esc($property['title']) ?></h1>
                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt"></i>
                    <?= esc(trim(($property['address'] ?? '') . ' ' . ($property['district'] ?? '') . ', ' . ($property['city'] ?? '') . ', ' . ($property['province'] ?? ''), ', ')) ?>
                </p>

                <div class="price-display mb-4">
                    <?= rupiah($property['price']) ?>
                    <?php if ($property['type']==='rent' && $property['price_period']!=='-'): ?>
                        <small>/ <?= $property['price_period']==='monthly'?'bulan':'tahun' ?></small>
                    <?php endif; ?>
                </div>

                <!-- Trust row -->
                <div class="trust-row">
                    <div class="trust-item"><i class="bi bi-shield-check"></i><div><strong>Properti Terverifikasi</strong><small>Data diverifikasi admin CariIn</small></div></div>
                    <div class="trust-item"><i class="bi bi-patch-check"></i><div><strong>Agen Terpercaya</strong><small>Identitas penjual tervalidasi</small></div></div>
                    <div class="trust-item"><i class="bi bi-lock"></i><div><strong>Transaksi Aman</strong><small>Tidak ada transfer ke CariIn</small></div></div>
                </div>

                <h5 class="section-h mt-4">Spesifikasi</h5>
                <div class="specs-grid">
                    <?php foreach ($specs as $key => $meta): ?>
                        <?php if (isset($details[$key]) && $details[$key] !== ''): ?>
                            <div class="spec-item">
                                <i class="bi <?= $meta[1] ?>"></i>
                                <div><div class="spec-label"><?= esc($meta[0]) ?></div><div class="spec-value"><?= esc($details[$key]) . esc($meta[2]) ?></div></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($formType==='building'): ?>
                        <?php foreach (['front_yard'=>'Halaman Depan','back_yard'=>'Halaman Belakang','fence'=>'Pagar'] as $k=>$lbl): ?>
                            <?php if (isset($details[$k])): ?>
                                <div class="spec-item">
                                    <i class="bi <?= $details[$k] ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' ?>"></i>
                                    <div><div class="spec-label"><?= esc($lbl) ?></div><div class="spec-value"><?= $details[$k]?'Ada':'Tidak Ada' ?></div></div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (! empty($property['orientation'])): ?>
                        <div class="spec-item"><i class="bi bi-compass"></i><div><div class="spec-label">Menghadap</div><div class="spec-value"><?= esc(orientation_label($property['orientation'])) ?></div></div></div>
                    <?php endif; ?>
                    <div class="spec-item"><i class="bi bi-file-earmark-text"></i><div><div class="spec-label">Legal</div><div class="spec-value"><?= esc($property['legal_status'] ?? '-') ?></div></div></div>
                    <div class="spec-item"><i class="bi bi-safe"></i><div><div class="spec-label">Dokumen</div><div class="spec-value"><?= $property['doc_status']==='at_bank'?'Di Bank':'On Hand' ?></div></div></div>
                </div>

                <h5 class="section-h mt-5">Deskripsi</h5>
                <div class="description-box"><?= nl2br(esc($property['description'] ?? 'Tidak ada deskripsi.')) ?></div>

                <?php if (! empty($property['latitude']) && ! empty($property['longitude'])): ?>
                    <h5 class="section-h mt-5">Lokasi di Peta</h5>
                    <div id="propertyMap" class="property-map"
                         data-lat="<?= esc($property['latitude']) ?>"
                         data-lng="<?= esc($property['longitude']) ?>"
                         data-title="<?= esc($property['title']) ?>"></div>
                    <?php if (! empty($property['maps_url'])): ?>
                        <a href="<?= esc($property['maps_url']) ?>" target="_blank" class="btn btn-outline-emerald btn-sm mt-2">
                            <i class="bi bi-geo-alt"></i> Buka di Google Maps
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <aside class="seller-card sticky-top" style="top: 90px;">
                    <div class="seller-header">
                        <?php if (! empty($property['seller_avatar'])): ?>
                            <img src="<?= esc($property['seller_avatar']) ?>" class="avatar-md" alt="">
                        <?php else: ?>
                            <span class="avatar-md avatar-initial"><?= strtoupper(substr($property['seller_name'] ?? 'U', 0, 1)) ?></span>
                        <?php endif; ?>
                        <div>
                            <div class="fw-bold"><?= esc($property['seller_name'] ?? 'Penjual') ?></div>
                            <small class="text-muted"><i class="bi bi-patch-check text-emerald"></i> Agen Terverifikasi</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <?php if (! empty($property['seller_phone'])): ?>
                            <a href="<?= wa_link($property['seller_phone'], $waText) ?>" target="_blank" class="btn btn-success">
                                <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                            </a>
                        <?php endif; ?>
                        <?php if (auth_user() && (int)$property['user_id'] !== (int)session()->get('user_id')): ?>
                            <a href="<?= site_url('/chat/start/' . $property['id']) ?>" class="btn btn-outline-success">
                                <i class="bi bi-chat-dots"></i> Chat dengan Penjual
                            </a>
                        <?php endif; ?>
                        <?php if (auth_user()): ?>
                            <button type="button" id="wishBtn" onclick="toggleWishlist(<?= (int)$property['id'] ?>)" class="btn <?= $isWished ? 'btn-danger' : 'btn-outline-danger' ?>">
                                <i class="bi <?= $isWished ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                                <span id="wishLabel"><?= $isWished ? 'Tersimpan di Wishlist' : 'Simpan ke Wishlist' ?></span>
                            </button>
                        <?php else: ?>
                            <a href="<?= site_url('/login') ?>" class="btn btn-outline-danger">
                                <i class="bi bi-heart"></i> Login untuk Simpan
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Seller rating summary -->
                    <?php if ($ratingSummary['total'] > 0): ?>
                        <div class="mt-3 small">
                            <strong class="text-warning">★ <?= number_format($ratingSummary['avg'], 1) ?></strong>
                            <span class="text-muted">/ 5 (<?= $ratingSummary['total'] ?> ulasan)</span>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <!-- KPR CALCULATOR -->
                    <div class="kpr-widget">
                        <h6 class="mb-2"><i class="bi bi-calculator"></i> Estimasi KPR</h6>
                        <div class="mb-2">
                            <label>DP (%)</label>
                            <input type="number" id="kprDp" class="form-control form-control-sm" value="20" min="0" max="100">
                        </div>
                        <div class="mb-2">
                            <label>Tenor (tahun)</label>
                            <select id="kprTenor" class="form-select form-select-sm">
                                <option value="5">5 tahun</option>
                                <option value="10">10 tahun</option>
                                <option value="15" selected>15 tahun</option>
                                <option value="20">20 tahun</option>
                                <option value="25">25 tahun</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Bunga (% / tahun)</label>
                            <input type="number" id="kprRate" class="form-control form-control-sm" value="7.5" step="0.1">
                        </div>
                        <div class="kpr-result">
                            <small class="text-muted">Cicilan per bulan</small>
                            <strong id="kprMonthly">-</strong>
                        </div>
                    </div>

                    <hr>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="bi bi-eye me-2"></i><?= number_format($property['views']) ?> kali dilihat</li>
                        <li><i class="bi bi-calendar me-2"></i>Tayang <?= date('d M Y', strtotime($property['published_at'] ?? $property['created_at'])) ?></li>
                    </ul>
                </aside>
            </div>
        </div>

        <!-- RATING & REVIEWS -->
        <div class="row mt-5">
            <div class="col-lg-8">
                <h5 class="section-h">Ulasan untuk Penjual</h5>

                <?php if (auth_user() && (int)$property['user_id'] !== (int)session()->get('user_id')): ?>
                    <div class="card card-soft p-3 mb-3">
                        <h6 class="mb-2">Beri Rating Penjual</h6>
                        <form action="<?= site_url('/rating/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="property_id" value="<?= (int)$property['id'] ?>">
                            <div class="mb-2 star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i===5?'checked':'' ?>>
                                    <label for="star<?= $i ?>">★</label>
                                <?php endfor; ?>
                            </div>
                            <textarea name="review" class="form-control mb-2" rows="2" placeholder="Tulis pengalaman Anda dengan penjual ini..."></textarea>
                            <button class="btn btn-success btn-sm">Kirim Ulasan</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (empty($ratingList)): ?>
                    <p class="text-muted small">Belum ada ulasan untuk penjual ini.</p>
                <?php else: ?>
                    <?php foreach ($ratingList as $rt): ?>
                        <div class="card card-soft p-3 mb-2">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <?php if (! empty($rt['reviewer_avatar'])): ?>
                                    <img src="<?= esc($rt['reviewer_avatar']) ?>" class="avatar-sm">
                                <?php else: ?>
                                    <span class="avatar-sm avatar-initial"><?= strtoupper(substr($rt['reviewer_name'] ?? 'U', 0, 1)) ?></span>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <div class="fw-bold small"><?= esc($rt['reviewer_name']) ?></div>
                                    <small class="text-muted"><?= date('d M Y', strtotime($rt['created_at'])) ?></small>
                                </div>
                                <div class="text-warning"><?= str_repeat('★', (int)$rt['rating']) . str_repeat('☆', 5 - (int)$rt['rating']) ?></div>
                            </div>
                            <?php if (! empty($rt['review'])): ?><p class="mb-0 small"><?= esc($rt['review']) ?></p><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- PROPERTI SERUPA -->
        <?php if (! empty($similar)): ?>
            <h5 class="section-h mt-5">Properti Serupa</h5>
            <div class="row g-4">
                <?php foreach ($similar as $s): ?>
                    <div class="col-sm-6 col-lg-3"><?= view('partials/property_card', ['p' => $s]) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
// KPR calculator
(function() {
    const price = <?= (int) $property['price'] ?>;
    const dpEl = document.getElementById('kprDp');
    const tenorEl = document.getElementById('kprTenor');
    const rateEl = document.getElementById('kprRate');
    const outEl = document.getElementById('kprMonthly');
    function calc() {
        const dpPct = parseFloat(dpEl.value) || 0;
        const tenorY = parseInt(tenorEl.value);
        const rate = parseFloat(rateEl.value) / 100 / 12;
        const loan = price * (1 - dpPct / 100);
        const n = tenorY * 12;
        let monthly = 0;
        if (rate > 0) {
            monthly = loan * rate * Math.pow(1+rate, n) / (Math.pow(1+rate, n) - 1);
        } else {
            monthly = loan / n;
        }
        outEl.textContent = 'Rp ' + Math.round(monthly).toLocaleString('id-ID');
    }
    [dpEl, tenorEl, rateEl].forEach(e => e.addEventListener('input', calc));
    calc();
})();

// Wishlist toggle
function toggleWishlist(propertyId){
    fetch('<?= site_url('/wishlist/toggle/') ?>' + propertyId, {
        method: 'POST',
        headers: {'X-Requested-With':'XMLHttpRequest'}
    }).then(r => r.json()).then(d => {
        if (d.login) { window.location='<?= site_url('/login') ?>'; return; }
        const btn = document.getElementById('wishBtn');
        const lbl = document.getElementById('wishLabel');
        if (d.added) {
            btn.classList.remove('btn-outline-danger'); btn.classList.add('btn-danger');
            btn.querySelector('i').classList.remove('bi-heart'); btn.querySelector('i').classList.add('bi-heart-fill');
            lbl.textContent = 'Tersimpan di Wishlist';
        } else {
            btn.classList.add('btn-outline-danger'); btn.classList.remove('btn-danger');
            btn.querySelector('i').classList.add('bi-heart'); btn.querySelector('i').classList.remove('bi-heart-fill');
            lbl.textContent = 'Simpan ke Wishlist';
        }
    });
}

// Add to compare
function addToCompare(propertyId){
    fetch('<?= site_url('/compare/add/') ?>' + propertyId, { method: 'POST' })
        .then(r => r.json())
        .then(d => {
            if (d.ok) {
                if (confirm('Ditambahkan ke perbandingan ('+d.count+'). Lihat sekarang?')) {
                    window.location = '<?= site_url('/compare') ?>';
                }
            } else {
                alert(d.message || 'Gagal menambahkan.');
            }
        });
}

// Instagram (no direct share API - copy link + open IG)
function copyToInstagram(){
    navigator.clipboard.writeText('<?= esc($shareUrl) ?>');
    alert('Link disalin! Buka Instagram & paste di Story atau Bio Anda.');
    window.open('https://www.instagram.com', '_blank');
}
</script>
