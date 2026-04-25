<div class="container-fluid p-4">
    <h4 class="mb-3">Manajemen Pengguna</h4>
    <div class="card card-soft">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Free Slot</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= esc($u['name']) ?></td>
                            <td class="small text-muted"><?= esc($u['email']) ?></td>
                            <td><span class="badge <?= $u['role'] === 'admin' ? 'bg-brown' : 'bg-sage' ?> text-white"><?= esc($u['role']) ?></span></td>
                            <td>
                                <?php if ($u['status'] === 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Suspend</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $u['free_slot_used'] ? 'Terpakai' : 'Tersedia' ?></td>
                            <td>
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <?php if ($u['status'] === 'active'): ?>
                                        <form method="post" action="<?= site_url('/admin/users/suspend/' . $u['id']) ?>" style="display:inline">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-outline-danger">Suspend</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="<?= site_url('/admin/users/activate/' . $u['id']) ?>" style="display:inline">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-outline-success">Aktifkan</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
