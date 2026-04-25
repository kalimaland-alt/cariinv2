<?php /** @var string $token */ /** @var string $email */ ?>
<div class="auth-screen">
    <div class="auth-card">
        <a href="<?= site_url('/') ?>" class="auth-brand">
            <span class="brand-mark">CI</span>
            <span>Cari<span class="text-emerald">In</span></span>
        </a>

        <?php if ($m = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 px-3 small mb-3"><?= esc($m) ?></div>
        <?php endif; ?>

        <h1 class="auth-title">Buat Password Baru</h1>
        <p class="auth-subtitle">Untuk akun: <strong><?= esc($email) ?></strong></p>

        <form method="post" action="<?= site_url('/reset-password') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token) ?>">
            <div class="mb-3">
                <label class="form-label small fw-semibold">Password Baru</label>
                <input type="password" name="password" class="form-control" required minlength="6" placeholder="Min. 6 karakter" autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Konfirmasi Password</label>
                <input type="password" name="password_confirm" class="form-control" required minlength="6" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="btn btn-emerald w-100">
                <i class="bi bi-shield-check"></i> Simpan Password Baru
            </button>
        </form>

        <p class="text-center small text-muted mt-4 mb-0">
            <a href="<?= site_url('/login') ?>" class="text-muted">Kembali ke Login</a>
        </p>
    </div>
</div>
