<div class="container-fluid p-4">
    <h4 class="mb-1">🛡️ Moderasi Iklan</h4>
    <p class="text-muted mb-4">Review, setujui, atau tolak iklan properti dari member.</p>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon bg-mint text-forest"><i class="bi bi-card-list"></i></div><div><div class="stat-label">Total Iklan</div><div class="stat-value"><?= $stats['total'] ?></div></div></div></div>
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div><div><div class="stat-label">Menunggu</div><div class="stat-value"><?= $stats['pending'] ?></div></div></div></div>
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div><div><div class="stat-label">Disetujui</div><div class="stat-value"><?= $stats['approved'] ?></div></div></div></div>
        <div class="col-md-3"><div class="stat-card"><div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i></div><div><div class="stat-label">Ditolak</div><div class="stat-value"><?= $stats['rejected'] ?></div></div></div></div>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link <?= $currentStatus==='pending_review'?'active':'' ?>" href="?status=pending_review">Menunggu Review (<?= $stats['pending'] ?>)</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentStatus==='published'?'active':'' ?>" href="?status=published">Tayang (<?= $stats['approved'] ?>)</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentStatus==='rejected'?'active':'' ?>" href="?status=rejected">Ditolak (<?= $stats['rejected'] ?>)</a></li>
    </ul>

    <?php if (empty($rows)): ?>
        <div class="empty-state"><i class="bi bi-inbox"></i><h5>Tidak ada iklan di status ini</h5></div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($rows as $r): ?>
                <div class="col-12">
                    <div class="card-soft p-3 d-flex flex-column flex-md-row gap-3 align-items-center">
                        <img src="<?= property_image_url($r['cover_image']) ?>" alt="" style="width:150px;height:110px;object-fit:cover;border-radius:var(--radius);">
                        <div class="flex-grow-1">
                            <div class="d-flex gap-2 mb-1">
                                <span class="badge-type <?= $r['type']==='sell'?'badge-sell':'badge-rent' ?>"><?= $r['type']==='sell'?'DIJUAL':'DISEWA' ?></span>
                                <small class="text-muted"><?= esc($r['category_name']) ?></small>
                            </div>
                            <h6 class="mb-1"><?= esc($r['title']) ?></h6>
                            <div class="small text-muted mb-1">
                                <i class="bi bi-geo-alt"></i> <?= esc($r['city'] ?? '-') ?> &middot;
                                <span class="fw-bold text-forest"><?= price_compact($r['price']) ?></span>
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-person"></i> <?= esc($r['seller_name']) ?> (<?= esc($r['seller_email']) ?>)
                                &middot; <i class="bi bi-calendar"></i> <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= site_url('/property/'.$r['slug']) ?>" target="_blank" class="btn btn-outline-emerald btn-sm"><i class="bi bi-eye"></i> Detail</a>
                            <?php if ($r['status'] === 'pending_review'): ?>
                                <form method="post" action="<?= site_url('/admin/moderation/approve/'.$r['id']) ?>" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Setujui</button>
                                </form>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reject<?= $r['id'] ?>">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                                <!-- Reject modal -->
                                <div class="modal fade" id="reject<?= $r['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form class="modal-content" method="post" action="<?= site_url('/admin/moderation/reject/'.$r['id']) ?>">
                                            <?= csrf_field() ?>
                                            <div class="modal-header"><h6 class="modal-title">Tolak: <?= esc($r['title']) ?></h6></div>
                                            <div class="modal-body">
                                                <label class="small fw-semibold">Alasan Penolakan</label>
                                                <textarea name="reason" class="form-control" rows="3" required placeholder="Contoh: Foto buram, info tidak lengkap, harga tidak wajar..."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button class="btn btn-danger btn-sm">Tolak Iklan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
