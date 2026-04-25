<?php /** @var array $settings */ ?>
<div class="container-fluid p-4">
    <h4 class="mb-1">Pengaturan Footer & Sistem</h4>
    <p class="text-muted mb-4">Atur konten footer yang muncul di seluruh halaman publik.</p>

    <?php if ($m = session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc($m) ?></div><?php endif; ?>

    <form action="<?= site_url('/admin/settings/save') ?>" method="post">
        <?= csrf_field() ?>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card card-soft p-4">
                    <h5 class="mb-3"><i class="bi bi-card-text me-2"></i>Footer Content</h5>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="footer_description" class="form-control" rows="3"><?= esc($settings['footer_description'] ?? '') ?></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Email Kontak</label><input type="email" name="footer_email" class="form-control" value="<?= esc($settings['footer_email'] ?? '') ?>"></div>
                        <div class="col-md-6"><label class="form-label">No. Telepon/WA</label><input type="text" name="footer_phone" class="form-control" value="<?= esc($settings['footer_phone'] ?? '') ?>"></div>
                        <div class="col-12"><label class="form-label">Alamat</label><input type="text" name="footer_address" class="form-control" value="<?= esc($settings['footer_address'] ?? '') ?>"></div>
                        <div class="col-md-4"><label class="form-label">Facebook URL</label><input type="url" name="footer_facebook" class="form-control" value="<?= esc($settings['footer_facebook'] ?? '') ?>"></div>
                        <div class="col-md-4"><label class="form-label">Instagram URL</label><input type="url" name="footer_instagram" class="form-control" value="<?= esc($settings['footer_instagram'] ?? '') ?>"></div>
                        <div class="col-md-4"><label class="form-label">Twitter URL</label><input type="url" name="footer_twitter" class="form-control" value="<?= esc($settings['footer_twitter'] ?? '') ?>"></div>
                        <div class="col-12">
                            <label class="form-label">Copyright</label>
                            <input type="text" name="footer_copyright" class="form-control" value="<?= esc($settings['footer_copyright'] ?? '') ?>">
                            <small class="text-muted">Gunakan <code>{year}</code> untuk tahun otomatis.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card card-soft p-4">
                    <h5 class="mb-3"><i class="bi bi-coin me-2"></i>Sistem Poin</h5>
                    <div class="mb-3">
                        <label class="form-label">Harga 1 Poin (Rp)</label>
                        <input type="number" name="point_rate" class="form-control" value="<?= esc($settings['point_rate'] ?? '1000') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Slot Iklan (Poin)</label>
                        <input type="number" name="slot_price_points" class="form-control" value="<?= esc($settings['slot_price_points'] ?? '20') ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-3 py-2">
                    <i class="bi bi-save me-1"></i> Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
