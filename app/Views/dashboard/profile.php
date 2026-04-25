<?php
/** @var array $user */
$flashSuccess = session()->getFlashdata('success');
$flashError   = session()->getFlashdata('error');
?>
<div class="container-fluid p-4">
    <h4 class="mb-1">Profil Saya</h4>
    <p class="text-muted mb-4">Kelola data profil, foto, dan keamanan akun Anda.</p>

    <?php if ($flashSuccess): ?>
        <div class="alert alert-success"><?= esc($flashSuccess) ?></div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="alert alert-danger"><?= esc($flashError) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <!-- Profile Info -->
            <div class="card card-soft p-4 mb-4">
                <h5 class="mb-3"><i class="bi bi-person-circle me-2"></i>Informasi Profil</h5>
                <form action="<?= site_url('/dashboard/profile/update') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <?php if (! empty($user['avatar_url'])): ?>
                            <img src="<?= esc($user['avatar_url']) ?>" id="avatarPreview" class="avatar-lg" alt="avatar" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                        <?php else: ?>
                            <span id="avatarPreview" class="avatar-lg avatar-initial" style="width:80px;height:80px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;background:#87A96B;color:#fff;font-size:24px;font-weight:bold;"><?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?></span>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <label class="form-label small text-muted">Foto Profil (JPG/PNG/WEBP, maks 2MB)</label>
                            <input type="file" name="avatar" accept="image/*" class="form-control" id="avatarInput">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="<?= esc($user['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" disabled>
                        <small class="text-muted">Email tidak bisa diubah.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="tel" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="<?= esc($user['phone'] ?? '') ?>">
                        <small class="text-muted">Digunakan untuk kontak WA pembeli/penyewa.</small>
                    </div>

                    <button type="submit" class="btn btn-emerald">
                        <i class="bi bi-check-circle me-1"></i> Simpan Profil
                    </button>
                </form>
            </div>

            <!-- Password -->
            <div class="card card-soft p-4">
                <h5 class="mb-3"><i class="bi bi-lock me-2"></i>Keamanan / Password</h5>
                <form action="<?= site_url('/dashboard/profile/password') ?>" method="post">
                    <?= csrf_field() ?>
                    <?php if (! empty($user['password_hash'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                    <?php else: ?>
                        <p class="small text-muted">Akun Anda belum memiliki password (login via Google). Buat password untuk bisa login manual.</p>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Password Baru (min. 6 karakter)</label>
                        <input type="password" name="new_password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                    </div>
                    <button type="submit" class="btn btn-outline-brown"><i class="bi bi-shield-check me-1"></i> Ubah Password</button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-soft p-4">
                <h5 class="mb-3"><i class="bi bi-coin me-2"></i>Saldo Poin</h5>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="display-6 fw-bold text-success"><?= number_format((int) ($user['points_balance'] ?? 0)) ?></span>
                    <small class="text-muted">poin</small>
                </div>
                <p class="small text-muted">Poin digunakan untuk membuka slot iklan tambahan.</p>
                <a href="<?= site_url('/topup') ?>" class="btn btn-brown w-100"><i class="bi bi-plus-circle me-1"></i>Top Up Poin</a>
                <a href="<?= site_url('/topup/history') ?>" class="btn btn-link w-100 mt-1">Riwayat Top Up</a>
            </div>

            <div class="card card-soft p-4 mt-3">
                <h6 class="mb-2">Status Akun</h6>
                <dl class="mb-0 small">
                    <dt class="text-muted">Role</dt>
                    <dd><span class="badge bg-sage text-white"><?= esc($user['role'] ?? 'member') ?></span></dd>
                    <dt class="text-muted">Status</dt>
                    <dd><span class="badge bg-success"><?= esc($user['status'] ?? 'active') ?></span></dd>
                    <dt class="text-muted">Anggota Sejak</dt>
                    <dd><?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatarInput')?.addEventListener('change', function(e){
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev){
        const prev = document.getElementById('avatarPreview');
        if (prev.tagName === 'IMG') {
            prev.src = ev.target.result;
        } else {
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.id = 'avatarPreview';
            img.className = 'avatar-lg';
            img.style.cssText = 'width:80px;height:80px;border-radius:50%;object-fit:cover;';
            prev.replaceWith(img);
        }
    };
    reader.readAsDataURL(file);
});
</script>
