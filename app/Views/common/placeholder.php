<div class="container-fluid p-4">
    <div class="card card-soft p-5 text-center">
        <div class="placeholder-icon mb-3">
            <i class="bi bi-hourglass-split" style="font-size: 3rem; color: var(--sage-primary);"></i>
        </div>
        <h3 class="mb-2"><?= esc($heading ?? 'Segera Hadir') ?></h3>
        <p class="text-muted mb-0"><?= esc($message ?? 'Fitur ini sedang dalam pengembangan.') ?></p>
        <div class="mt-4">
            <a href="<?= site_url('/dashboard') ?>" class="btn btn-outline-sage">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
