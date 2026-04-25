<?php /** @var int $totalRevenue */ /** @var int $totalTopup */ /** @var int $totalPayments */ /** @var int $pendingTopup */ /** @var int $totalUsers */ /** @var array $monthly */ /** @var array $topups */ /** @var array $payments */ ?>
<div class="container-fluid p-4">
    <h4 class="mb-1">Dashboard Keuangan</h4>
    <p class="text-muted mb-4">Ringkasan pendapatan, top up, dan transaksi.</p>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-soft p-3">
                <small class="text-muted">Total Pendapatan</small>
                <div class="h4 text-success mb-0">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft p-3">
                <small class="text-muted">Total Top Up Sukses</small>
                <div class="h4 mb-0">Rp <?= number_format($totalTopup, 0, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft p-3">
                <small class="text-muted">Pembayaran Slot Iklan</small>
                <div class="h4 mb-0">Rp <?= number_format($totalPayments, 0, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft p-3">
                <small class="text-muted">Top Up Pending</small>
                <div class="h4 text-warning mb-0"><?= $pendingTopup ?></div>
                <a href="<?= site_url('/admin/finance/topup-history') ?>" class="small">Kelola →</a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card card-soft p-3">
                <h6 class="mb-3">Pendapatan 6 Bulan Terakhir</h6>
                <canvas id="chartMonthly" height="180"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-soft p-3">
                <h6 class="mb-3">Top Up Terbaru</h6>
                <div class="table-responsive" style="max-height:280px;overflow-y:auto;">
                    <table class="table table-sm">
                        <thead><tr><th>#</th><th>Poin</th><th>Nominal</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($topups as $t): ?>
                            <tr>
                                <td><small><?= esc($t['transaction_id']) ?></small></td>
                                <td><?= number_format((int)$t['points']) ?></td>
                                <td>Rp <?= number_format((int)$t['amount_rp'], 0, ',', '.') ?></td>
                                <td><span class="badge bg-<?= $t['status']==='success'?'success':($t['status']==='pending'?'warning':'secondary') ?>"><?= esc($t['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($topups)): ?><tr><td colspan="4" class="text-center text-muted">Belum ada data.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const data = <?= json_encode($monthly) ?>;
new Chart(document.getElementById('chartMonthly'), {
    type: 'bar',
    data: {
        labels: data.map(d => d.label),
        datasets: [{ label: 'Pendapatan (Rp)', data: data.map(d => d.value), backgroundColor: '#87A96B' }]
    },
    options: { plugins:{legend:{display:false}}, scales:{ y:{ ticks:{ callback: v => 'Rp ' + Number(v).toLocaleString('id-ID') }}}}
});
</script>
