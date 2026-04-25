<div class="container-fluid p-4">
    <h4 class="mb-1">Selamat datang, <?= esc($auth_user['name'] ?? 'User') ?> 👋</h4>
    <p class="text-muted mb-4">Kelola iklan properti Anda di sini.</p>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-sage-light text-sage-dark"><i class="bi bi-card-list"></i></div>
                <div>
                    <div class="stat-label">Total Iklan Saya</div>
                    <div class="stat-value">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-brown-light text-brown"><i class="bi bi-eye"></i></div>
                <div>
                    <div class="stat-label">Total View</div>
                    <div class="stat-value">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-gift"></i></div>
                <div>
                    <div class="stat-label">Slot Gratis</div>
                    <div class="stat-value">1 Tersedia</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-soft">
        <div class="card-body">
            <h6 class="mb-2">🚀 Mulai pasang iklan properti Anda</h6>
            <p class="text-muted small mb-3">
                Slot pertama <strong>GRATIS</strong>. Slot ke-2 dst hanya Rp 20.000 via QRIS (Midtrans).
                Fitur CRUD iklan dinamis dengan multi-upload foto + Leaflet map akan aktif di <strong>Iterasi 2</strong>.
            </p>
            <a href="<?= site_url('/ads/create') ?>" class="btn btn-sage">
                <i class="bi bi-plus-circle"></i> Buat Iklan Baru
            </a>
        </div>
    </div>
</div>
