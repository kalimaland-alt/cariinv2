<?php /** @var array $rows */ ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Wishlist</h4>
            <p class="text-muted mb-0"><?= count($rows) ?> properti disimpan</p>
        </div>
        <a href="<?= site_url('/search') ?>" class="btn btn-outline-emerald btn-sm"><i class="bi bi-search"></i> Cari Properti</a>
    </div>

    <?php if (empty($rows)): ?>
        <div class="card card-soft p-5 text-center">
            <i class="bi bi-heart display-4 text-muted"></i>
            <h5 class="mt-3">Belum ada favorit</h5>
            <p class="text-muted">Klik ikon hati ❤ pada properti untuk menyimpannya di sini.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($rows as $p): ?>
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <?= view('partials/property_card', ['p' => $p]) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
