<?php /** @var array $rows */ ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-1">Bandingkan Properti</h3>
                <p class="text-muted mb-0"><?= count($rows) ?> properti dipilih (maks 4)</p>
            </div>
            <?php if (! empty($rows)): ?>
                <a href="<?= site_url('/compare/clear') ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Bersihkan</a>
            <?php endif; ?>
        </div>

        <?php if (empty($rows)): ?>
            <div class="card card-soft p-5 text-center">
                <i class="bi bi-bar-chart display-4 text-muted"></i>
                <h5 class="mt-3">Belum ada properti dipilih</h5>
                <p class="text-muted">Klik tombol "Bandingkan" pada kartu properti untuk menambahkannya ke perbandingan.</p>
                <a href="<?= site_url('/search') ?>" class="btn btn-success mx-auto" style="width:fit-content;">Cari Properti</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle bg-white">
                    <thead>
                        <tr>
                            <th style="width: 180px;">Atribut</th>
                            <?php foreach ($rows as $r): ?>
                                <th>
                                    <img src="<?= esc(property_image_url($r['cover_image'])) ?>" style="width:140px;height:90px;object-fit:cover;border-radius:6px;">
                                    <div class="small fw-bold mt-2"><a href="<?= site_url('/property/'.$r['slug']) ?>" target="_blank"><?= esc($r['title']) ?></a></div>
                                    <a href="<?= site_url('/compare/remove/'.$r['id']) ?>" class="btn btn-sm btn-link text-danger p-0">Hapus</a>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><th>Tipe</th><?php foreach ($rows as $r): ?><td><span class="badge bg-<?= $r['type']==='sell'?'success':'warning' ?>"><?= $r['type']==='sell'?'Jual':'Sewa' ?></span></td><?php endforeach; ?></tr>
                        <tr><th>Harga</th><?php foreach ($rows as $r): ?><td><strong><?= rupiah($r['price']) ?></strong></td><?php endforeach; ?></tr>
                        <tr><th>Kategori</th><?php foreach ($rows as $r): ?><td><?= esc($r['category_name']) ?></td><?php endforeach; ?></tr>
                        <tr><th>Lokasi</th><?php foreach ($rows as $r): ?><td><?= esc(($r['city'] ?? '') . ', ' . ($r['province'] ?? '')) ?></td><?php endforeach; ?></tr>
                        <tr><th>Status Legal</th><?php foreach ($rows as $r): ?><td><?= esc($r['legal_status'] ?? '-') ?></td><?php endforeach; ?></tr>
                        <tr><th>Hadap</th><?php foreach ($rows as $r): ?><td><?= esc(orientation_label($r['orientation'] ?? '')) ?></td><?php endforeach; ?></tr>
                        <tr><th>Penjual</th><?php foreach ($rows as $r): ?><td><?= esc($r['seller_name']) ?></td><?php endforeach; ?></tr>
                        <tr><th>Aksi</th>
                            <?php foreach ($rows as $r): ?>
                                <td>
                                    <a href="<?= site_url('/property/'.$r['slug']) ?>" class="btn btn-success btn-sm w-100">Lihat Detail</a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
