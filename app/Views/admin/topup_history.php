<?php /** @var array $rows */ ?>
<div class="container-fluid p-4">
    <h4 class="mb-3">Riwayat Top Up Member</h4>

    <?php foreach (['success','error','info'] as $f): ?>
        <?php if ($m = session()->getFlashdata($f)): ?>
            <div class="alert alert-<?= $f==='error'?'danger':$f ?>"><?= esc($m) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#Trx</th><th>User</th><th>Tanggal</th><th>Poin</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><small><?= esc($r['transaction_id']) ?></small></td>
                        <td>
                            <div class="small fw-bold"><?= esc($r['user_name']) ?></div>
                            <div class="text-muted small"><?= esc($r['user_email']) ?></div>
                        </td>
                        <td><small><?= date('d M Y H:i', strtotime($r['created_at'])) ?></small></td>
                        <td><?= number_format((int)$r['points']) ?></td>
                        <td>Rp <?= number_format((int)$r['amount_rp'], 0, ',', '.') ?></td>
                        <td><span class="badge bg-<?= $r['status']==='success'?'success':($r['status']==='pending'?'warning':'secondary') ?>"><?= esc($r['status']) ?></span></td>
                        <td>
                            <?php if ($r['status'] === 'pending'): ?>
                                <form action="<?= site_url('/admin/finance/topup/'.$r['id'].'/approve') ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Setujui top up ini?')"><i class="bi bi-check"></i> Approve</button>
                                </form>
                                <form action="<?= site_url('/admin/finance/topup/'.$r['id'].'/reject') ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak top up ini?')"><i class="bi bi-x"></i></button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rows)): ?><tr><td colspan="7" class="text-center text-muted py-4">Belum ada top up.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
