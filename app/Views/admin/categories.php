<div class="container-fluid p-4">
    <h4 class="mb-3">Kategori Properti</h4>
    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Slug</th><th>Form Type</th><th>Icon</th><th>Aktif</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><strong><?= esc($c['name']) ?></strong></td>
                            <td><code><?= esc($c['slug']) ?></code></td>
                            <td><span class="badge <?= $c['form_type'] === 'building' ? 'bg-sage' : 'bg-brown' ?>"><?= esc($c['form_type']) ?></span></td>
                            <td><i class="bi <?= esc($c['icon']) ?>"></i> <small class="text-muted"><?= esc($c['icon']) ?></small></td>
                            <td><?= $c['is_active'] ? '✅' : '❌' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <p class="text-muted small mt-3">Tambah/edit kategori akan aktif di Iterasi 3.</p>
</div>
