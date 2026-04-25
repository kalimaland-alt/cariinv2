<?php /** @var array $packages */ /** @var array $user */ /** @var int $pointRate */ ?>
<div class="container-fluid p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1">Top Up Poin</h4>
            <p class="text-muted mb-0">1 poin = Rp <?= number_format($pointRate, 0, ',', '.') ?>. Gunakan poin untuk membeli slot iklan tambahan.</p>
        </div>
        <div class="text-end">
            <small class="text-muted d-block">Saldo saat ini</small>
            <strong class="h4 text-success mb-0"><?= number_format((int)($user['points_balance'] ?? 0)) ?> poin</strong>
        </div>
    </div>

    <?php if ($m = session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc($m) ?></div><?php endif; ?>

    <div class="row g-3">
        <?php foreach ($packages as $p): ?>
            <div class="col-md-3 col-sm-6">
                <form action="<?= site_url('/topup/create') ?>" method="post" class="card card-soft p-3 h-100 text-center">
                    <?= csrf_field() ?>
                    <input type="hidden" name="points" value="<?= $p['points'] + $p['bonus'] ?>">
                    <input type="hidden" name="amount" value="<?= $p['price'] ?>">
                    <div class="display-6 fw-bold text-brown"><?= $p['points'] ?></div>
                    <small class="text-muted">poin</small>
                    <?php if ($p['bonus'] > 0): ?>
                        <div class="mt-1"><span class="badge bg-success">+<?= $p['bonus'] ?> bonus</span></div>
                    <?php endif; ?>
                    <hr>
                    <div class="h5 text-sage">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
                    <button class="btn btn-emerald w-100 mt-2"><i class="bi bi-lightning-charge me-1"></i>Beli</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="alert alert-info mt-4 small">
        <i class="bi bi-info-circle me-1"></i>
        Pembayaran via QRIS Midtrans aktif setelah admin melengkapi Server/Client Key Midtrans. Saat ini order akan berstatus <strong>pending</strong> dan menunggu konfirmasi admin.
    </div>
</div>
