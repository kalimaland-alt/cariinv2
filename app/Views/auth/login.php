<div class="auth-screen">
    <div class="auth-card">
        <a href="<?= site_url('/') ?>" class="auth-brand">
            <span class="brand-mark">CI</span>
            <span>Cari<span class="text-emerald">In</span></span>
        </a>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 px-3 small mb-3">
                <i class="bi bi-exclamation-triangle"></i> <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 px-3 small mb-3">
                <i class="bi bi-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <h1 class="auth-title">Masuk ke Akun</h1>
        <p class="auth-subtitle">Masuk untuk pasang iklan, simpan favorit, & chat penjual.</p>

        <form method="post" action="<?= site_url('/login') ?>" autocomplete="on">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required
                       value="<?= esc(old('email')) ?>" placeholder="nama@email.com" autofocus>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-semibold d-flex justify-content-between">
                    <span>Password</span>
                    <a href="<?= site_url('/forgot-password') ?>" class="text-muted small text-decoration-none">Lupa password?</a>
                </label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••" minlength="6">
            </div>
            <button type="submit" class="btn btn-emerald w-100 mt-3">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>
        </form>

        <div class="auth-divider"><span>atau</span></div>

        <a href="<?= site_url('/auth/google') ?>" class="btn btn-google w-100">
            <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.5 33.2 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.1 29.2 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.3-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.1 29.2 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35 26.7 36 24 36c-5.1 0-9.5-2.8-11.3-6.9l-6.6 5.1C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.9 2.4-2.5 4.4-4.5 5.9l6.1 5.2C40.1 35.7 44 30.3 44 24c0-1.2-.1-2.3-.4-3.5z"/></svg>
            <span class="ms-2">Masuk dengan Google</span>
        </a>

        <p class="text-center small text-muted mt-4 mb-0">
            Belum punya akun? <a href="<?= site_url('/register') ?>" class="text-emerald fw-bold">Daftar Gratis</a>
        </p>

        <a href="<?= site_url('/') ?>" class="btn btn-link w-100 mt-2 text-muted small">
            <i class="bi bi-arrow-left"></i> Kembali ke beranda
        </a>
    </div>
</div>
