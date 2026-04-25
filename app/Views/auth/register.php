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

        <h1 class="auth-title">Daftar Gratis 🎉</h1>
        <p class="auth-subtitle">Buat akun baru & dapatkan <strong>1 slot iklan GRATIS</strong>.</p>

        <form method="post" action="<?= site_url('/register') ?>" autocomplete="on">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required minlength="2" maxlength="150"
                       value="<?= esc(old('name')) ?>" placeholder="Contoh: Budi Santoso" autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required
                       value="<?= esc(old('email')) ?>" placeholder="nama@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">No. WhatsApp (opsional)</label>
                <input type="tel" name="phone" class="form-control" maxlength="20"
                       value="<?= esc(old('phone')) ?>" placeholder="081234567890">
                <small class="text-muted">Untuk menerima kontak dari calon pembeli.</small>
            </div>
            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="Min. 6 karakter">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirm" class="form-control" required minlength="6" placeholder="Ulangi password">
                </div>
            </div>

            <div class="form-check small mb-3">
                <input class="form-check-input" type="checkbox" id="tos" required>
                <label class="form-check-label text-muted" for="tos">
                    Saya menyetujui <a href="#" class="text-emerald">Syarat & Ketentuan</a> CariIn
                </label>
            </div>

            <button type="submit" class="btn btn-emerald w-100">
                <i class="bi bi-person-plus"></i> Daftar Sekarang
            </button>
        </form>

        <div class="auth-divider"><span>atau</span></div>

        <a href="<?= site_url('/auth/google') ?>" class="btn btn-google w-100">
            <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.5 33.2 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.1 29.2 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.3-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C33.9 6.1 29.2 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.8-2 13.3-5.2l-6.1-5.2C29.2 35 26.7 36 24 36c-5.1 0-9.5-2.8-11.3-6.9l-6.6 5.1C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.9 2.4-2.5 4.4-4.5 5.9l6.1 5.2C40.1 35.7 44 30.3 44 24c0-1.2-.1-2.3-.4-3.5z"/></svg>
            <span class="ms-2">Daftar dengan Google</span>
        </a>

        <p class="text-center small text-muted mt-4 mb-0">
            Sudah punya akun? <a href="<?= site_url('/login') ?>" class="text-emerald fw-bold">Masuk</a>
        </p>
    </div>
</div>
