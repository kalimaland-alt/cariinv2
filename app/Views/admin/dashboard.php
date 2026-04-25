<div class="container-fluid p-4">
    <h4 class="mb-1">📊 Admin Dashboard</h4>
    <p class="text-muted mb-4">Statistik platform CariIn secara keseluruhan.</p>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-xl-2">
            <div class="stat-card">
                <div class="stat-icon bg-sage-light text-sage-dark"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-label">Total User</div>
                    <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="stat-card">
                <div class="stat-icon bg-brown-light text-brown"><i class="bi bi-person-check"></i></div>
                <div>
                    <div class="stat-label">Member</div>
                    <div class="stat-value"><?= number_format($stats['total_members']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="stat-card">
                <div class="stat-icon bg-sage-light text-sage-dark"><i class="bi bi-house"></i></div>
                <div>
                    <div class="stat-label">Total Iklan</div>
                    <div class="stat-value"><?= number_format($stats['total_properties']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="stat-card">
                <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-label">Menunggu</div>
                    <div class="stat-value"><?= number_format($stats['pending_review']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="stat-card">
                <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-label">Published</div>
                    <div class="stat-value"><?= number_format($stats['published']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-2">
            <div class="stat-card">
                <div class="stat-icon bg-brown-light text-brown"><i class="bi bi-cash-coin"></i></div>
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value fs-6"><?= rupiah($stats['total_revenue']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-soft p-4">
        <h6 class="mb-2">👋 Selamat datang di CariIn Admin CMS</h6>
        <p class="text-muted mb-0 small">
            Fungsi moderasi iklan, manajemen user, dan log transaksi akan aktif di <strong>Iterasi 3</strong>.
            Untuk saat ini, Anda sudah bisa melihat data user & kategori yang sudah diseed.
        </p>
    </div>
</div>
