<?php /** @var array $topup */ /** @var ?string $clientKey */ /** @var bool $isProd */ ?>
<div class="container-fluid p-4">
    <h4 class="mb-3">Pembayaran Top Up</h4>
    <div class="card card-soft p-4" style="max-width: 560px;">
        <p class="mb-2"><strong>Nomor Transaksi:</strong> <?= esc($topup['transaction_id']) ?></p>
        <p class="mb-2"><strong>Jumlah Poin:</strong> <?= number_format((int)$topup['points']) ?> poin</p>
        <p class="mb-2"><strong>Total Bayar:</strong> Rp <?= number_format((int)$topup['amount_rp'], 0, ',', '.') ?></p>
        <p class="mb-3"><strong>Status:</strong>
            <span id="payStatus" class="badge bg-<?= $topup['status'] === 'success' ? 'success' : ($topup['status']==='pending'?'warning':'secondary') ?>"><?= esc($topup['status']) ?></span>
        </p>

        <?php if (! empty($topup['snap_token']) && $clientKey): ?>
            <button id="payBtn" class="btn btn-success w-100 mb-2"><i class="bi bi-qr-code-scan me-1"></i>Bayar Sekarang (Midtrans)</button>
            <small class="text-muted">Anda akan diarahkan ke Snap Midtrans (QRIS, GoPay, Bank Transfer, dll).</small>
            <script src="https://app<?= $isProd ? '' : '.sandbox' ?>.midtrans.com/snap/snap.js" data-client-key="<?= esc($clientKey) ?>"></script>
            <script>
            document.getElementById('payBtn').addEventListener('click', () => {
                snap.pay('<?= esc($topup['snap_token']) ?>', {
                    onSuccess: () => location.href = '<?= site_url('/topup/history') ?>',
                    onPending: () => location.href = '<?= site_url('/topup/history') ?>',
                    onError:   () => alert('Pembayaran gagal. Silakan coba lagi.'),
                    onClose:   () => alert('Anda menutup popup pembayaran.'),
                });
            });
            </script>
        <?php else: ?>
            <div class="alert alert-warning small">
                <i class="bi bi-hourglass-split me-1"></i>
                Midtrans QRIS belum dikonfigurasi. Status order tetap <strong>pending</strong>. Silakan tunggu admin mengaktifkan Midtrans, atau hubungi admin untuk konfirmasi manual.
            </div>
        <?php endif; ?>

        <a href="<?= site_url('/topup/history') ?>" class="btn btn-outline-secondary mt-2">Lihat Riwayat</a>
    </div>
</div>
