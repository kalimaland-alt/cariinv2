<?php /** @var array $rows */ ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Iklan Saya</h4>
            <p class="text-muted mb-0"><?= count($rows) ?> iklan</p>
        </div>
        <a href="<?= site_url('/ads/create') ?>" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Pasang Iklan Baru</a>
    </div>

    <?php foreach (['success','error','info'] as $f): ?>
        <?php if ($m = session()->getFlashdata($f)): ?>
            <div class="alert alert-<?= $f === 'error' ? 'danger' : $f ?>"><?= esc($m) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty($rows)): ?>
        <div class="card card-soft p-5 text-center">
            <i class="bi bi-house-slash display-4 text-muted"></i>
            <h5 class="mt-3">Belum ada iklan</h5>
            <p class="text-muted">Pasang iklan pertama Anda secara gratis.</p>
            <a href="<?= site_url('/ads/create') ?>" class="btn btn-success mx-auto" style="width:fit-content;">Mulai Pasang Iklan</a>
        </div>
    <?php else: ?>
        <div class="table-responsive card card-soft">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Foto</th><th>Judul</th><th>Kategori</th><th>Tipe</th><th>Harga</th><th>Status</th><th>Dibuat</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><img src="<?= esc(property_image_url($r['cover_image'] ?? null)) ?>" style="width:64px;height:48px;object-fit:cover;border-radius:4px;"></td>
                        <td><a href="<?= site_url('/property/' . ($r['slug'] ?? '')) ?>" target="_blank"><?= esc($r['title']) ?></a></td>
                        <td><?= esc($r['category_name'] ?? '-') ?></td>
                        <td><span class="badge <?= ($r['type']==='sell'?'bg-success':'bg-warning') ?> text-white"><?= $r['type']==='sell'?'Jual':'Sewa' ?></span></td>
                        <td><?= rupiah($r['price']) ?></td>
                        <td>
                            <?php $s = $r['status']; $cls = $s==='published'?'success':($s==='pending_review'?'warning':'secondary'); ?>
                            <span class="badge bg-<?= $cls ?>"><?= esc(str_replace('_',' ', $s)) ?></span>
                        </td>
                        <td><small><?= date('d M Y', strtotime($r['created_at'])) ?></small></td>
                        <td>
                            <a href="<?= site_url('/ads/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil"></i></a>
                            <a href=\"<?= site_url('/ads/edit/' . hashid((int)$r['id'])) ?>\" class=\"btn btn-sm btn-outline-success me-1\"><i class=\"bi bi-pencil\"></i></a>
                            <form action=\"<?= site_url('/ads/delete/' . hashid((int)$r['id'])) ?>\" method=\"post\" class=\"d-inline\" onsubmit=\"return confirm('Hapus iklan ini?');\">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
