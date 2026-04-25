<div class="auth-screen">
    <div class="auth-card">
        <a href="<?= site_url('/') ?>" class="auth-brand">
            <span class="brand-mark">CI</span>
            <span>Cari<span class="text-emerald">In</span></span>
        </a>

        <?php if ($m = session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 px-3 small mb-3"><?= esc($m) ?></div>
        <?php endif; ?>
        <?php if ($m = session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 px-3 small mb-3"><?= esc($m) ?></div>
        <?php endif; ?>
        <?php if ($m = session()->getFlashdata('info')): ?>
            <div class="alert alert-info py-2 px-3 small mb-3"><?= esc($m) ?></div>
        <?php endif; ?>

        <h1 class="auth-title">Lupa Password?</h1>
        <p class="auth-subtitle">Masukkan email Anda. Kami akan kirim link reset password.</p>

        <form method="post" action="<?= site_url('/forgot-password') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="nama@email.com" autofocus>
            </div>
            <button type="submit" class="btn btn-emerald w-100">
                <i class="bi bi-envelope-paper"></i> Kirim Link Reset
            </button>
        </form>

        <p class="text-center small text-muted mt-4 mb-0">
            Sudah ingat? <a href="<?= site_url('/login') ?>" class="text-emerald fw-bold">Masuk</a>
        </p>
    </div>
</div>
