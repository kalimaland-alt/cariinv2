<?php
$coverUrl = property_image_url($p['cover_image'] ?? null);
$badgeClass = ($p['type'] ?? 'sell') === 'sell' ? 'badge-sell' : 'badge-rent';
$badgeText  = ($p['type'] ?? 'sell') === 'sell' ? 'DIJUAL' : 'DISEWA';
?>
<article class="property-card position-relative">
    <a href="<?= site_url('/property/' . $p['slug']) ?>" class="property-card-media">
        <img src="<?= esc($coverUrl) ?>" alt="<?= esc($p['title']) ?>" loading="lazy">
        <span class="badge-type <?= $badgeClass ?>"><?= $badgeText ?></span>
        <?php if (! empty($p['category_name'])): ?>
            <span class="badge-cat"><?= esc($p['category_name']) ?></span>
        <?php endif; ?>
    </a>

    <!-- Quick action buttons -->
    <div class="property-card-actions">
        <button type="button" class="card-action-btn" title="Bandingkan" onclick="addToCompareCard(<?= (int)$p['id'] ?>)">
            <i class="bi bi-bar-chart-line"></i>
        </button>
        <?php if (auth_user()): ?>
            <button type="button" class="card-action-btn" title="Wishlist" onclick="toggleWishCard(<?= (int)$p['id'] ?>, this)">
                <i class="bi bi-heart"></i>
            </button>
        <?php endif; ?>
    </div>

    <div class="property-card-body">
        <h3 class="property-card-title">
            <a href="<?= site_url('/property/' . $p['slug']) ?>"><?= esc($p['title']) ?></a>
        </h3>
        <p class="property-card-location">
            <i class="bi bi-geo-alt"></i>
            <?= esc(trim(($p['city'] ?? '') . ($p['province'] ? ', ' . $p['province'] : ''), ', ')) ?: 'Lokasi tidak diketahui' ?>
        </p>
        <div class="property-card-price">
            <?= price_compact($p['price']) ?>
            <?php if (($p['type'] ?? 'sell') === 'rent' && ($p['price_period'] ?? '-') !== '-'): ?>
                <small class="text-muted">/ <?= $p['price_period'] === 'monthly' ? 'bulan' : 'tahun' ?></small>
            <?php endif; ?>
        </div>
    </div>
</article>
