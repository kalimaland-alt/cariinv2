<div class="container-fluid p-4">
    <h4 class="mb-3">Log Transaksi QRIS</h4>
    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Transaction ID</th><th>User</th><th>Amount</th><th>Status</th><th>Paid At</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= $r['id'] ?></td>
                                <td class="small"><?= esc($r['transaction_id']) ?></td>
                                <td><?= $r['user_id'] ?></td>
                                <td><?= rupiah($r['amount']) ?></td>
                                <td><span class="badge bg-<?= $r['status'] === 'success' ? 'success' : 'secondary' ?>"><?= $r['status'] ?></span></td>
                                <td class="small"><?= $r['paid_at'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
