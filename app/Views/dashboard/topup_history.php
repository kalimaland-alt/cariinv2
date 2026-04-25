<?php /** @var array $rows */ ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Riwayat Top Up</h4>
        <a href="<?= site_url('/topup') ?>" class="btn btn-emerald btn-sm"><i class="bi bi-plus-circle"></i> Top Up Lagi</a>
    </div>

    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#Trx</th><th>Tanggal</th><th>Poin</th><th>Nominal</th><th>Metode</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada top up.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><small><?= esc($r['transaction_id']) ?></small></td>
                            <td><small><?= date('d M Y H:i', strtotime($r['created_at'])) ?></small></td>
                            <td><strong><?= number_format((int)$r['points']) ?></strong></td>
                            <td>Rp <?= number_format((int)$r['amount_rp'], 0, ',', '.') ?></td>
                            <td><?= esc($r['payment_method']) ?></td>
                            <td>
                                <?php $s=$r['status']; $cls=$s==='success'?'success':($s==='pending'?'warning':'danger'); ?>
                                <span class="badge bg-<?= $cls ?>"><?= esc($s) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
