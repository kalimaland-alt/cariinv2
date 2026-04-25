<?php
/** @var array $p */ /** @var array $details */ /** @var array $images */ /** @var array $categories */
$flashError = session()->getFlashdata('error');

$catFormType = [];
foreach ($categories as $c) {
    $catFormType[$c['id']] = $c['form_type'] ?? 'building';
}
?>
<div class="container-fluid p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Edit Iklan</h4>
            <p class="text-muted mb-0">Setelah edit, iklan akan kembali ke status <em>pending review</em>.</p>
        </div>
        <a href="<?= site_url('/my-ads') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($flashError): ?><div class="alert alert-danger"><?= esc($flashError) ?></div><?php endif; ?>

    <form action=\"<?= site_url('/ads/update/' . hashid((int)$p['id'])) ?>\" method=\"post\" enctype=\"multipart/form-data\">
        <?= csrf_field() ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Informasi Utama</h5>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Iklan</label>
                        <input type="text" name="title" class="form-control" value="<?= esc($p['title']) ?>" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipe Iklan</label>
                            <div class="btn-group w-100">
                                <input type="radio" class="btn-check" name="type" id="typeSell" value="sell" <?= $p['type']==='sell'?'checked':'' ?>>
                                <label class="btn btn-outline-success" for="typeSell">Jual</label>
                                <input type="radio" class="btn-check" name="type" id="typeRent" value="rent" <?= $p['type']==='rent'?'checked':'' ?>>
                                <label class="btn btn-outline-warning" for="typeRent">Sewa</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="category_id" id="categorySelect" class="form-select" required>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= esc($c['id']) ?>" <?= $p['category_id']==$c['id']?'selected':'' ?>><?= esc($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Harga (Rp)</label>
                            <input type="text" name="price" id="priceInput" class="form-control" value="<?= number_format((int)$p['price'], 0, ',', '.') ?>" required>
                        </div>
                        <div class="col-md-6" id="periodWrapper" style="display: <?= $p['type']==='rent'?'block':'none' ?>;">
                            <label class="form-label fw-bold">Periode Sewa</label>
                            <select name="price_period" class="form-select">
                                <option value="monthly" <?= $p['price_period']==='monthly'?'selected':'' ?>>Per Bulan</option>
                                <option value="yearly"  <?= $p['price_period']==='yearly'?'selected':'' ?>>Per Tahun</option>
                                <option value="daily"   <?= $p['price_period']==='daily'?'selected':'' ?>>Per Hari</option>
                                <option value="-"       <?= $p['price_period']==='-'?'selected':'' ?>>Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4"><?= esc($p['description']) ?></textarea>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Spesifikasi & Dokumen</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Luas Tanah (m²)</label>
                            <input type="number" name="land_area" class="form-control" value="<?= esc($details['land_area'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Luas Bangunan (m²)</label>
                            <input type="number" name="building_area" class="form-control" value="<?= esc($details['building_area'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Lantai</label>
                            <input type="number" name="floors" class="form-control" value="<?= esc($details['floors'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Kamar Tidur</label>
                            <input type="number" name="bedrooms" class="form-control" value="<?= esc($details['bedrooms'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Kamar Mandi</label>
                            <input type="number" name="bathrooms" class="form-control" value="<?= esc($details['bathrooms'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Dapur</label>
                            <input type="number" name="kitchens" class="form-control" value="<?= esc($details['kitchens'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Legal</label>
                            <select name="legal_status" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php foreach (['SHM','HGB','AJB','Girik','Waris','SHGB'] as $ls): ?>
                                    <option value="<?= $ls ?>" <?= ($p['legal_status'] ?? $details['legal_status'] ?? '')===$ls?'selected':'' ?>><?= $ls ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kondisi Sertifikat</label>
                            <select name="doc_status" class="form-select">
                                <option value="on_hand" <?= ($p['doc_status'] ?? '')==='on_hand'?'selected':'' ?>>Di Pemilik</option>
                                <option value="at_bank" <?= ($p['doc_status'] ?? '')==='at_bank'?'selected':'' ?>>Di Bank</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hadap</label>
                            <select name="orientation" class="form-select">
                                <option value="">- Pilih -</option>
                                <?php foreach (['N'=>'Utara','S'=>'Selatan','E'=>'Timur','W'=>'Barat','NE'=>'Timur Laut','NW'=>'Barat Laut','SE'=>'Tenggara','SW'=>'Barat Daya'] as $k=>$v): ?>
                                    <option value="<?= $k ?>" <?= ($p['orientation'] ?? '')===$k?'selected':'' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Lokasi Properti</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label fw-bold">Provinsi</label><input type="text" name="province" class="form-control" value="<?= esc($p['province']) ?>"></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Kab/Kota</label><input type="text" name="city" class="form-control" value="<?= esc($p['city']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Kecamatan</label><input type="text" name="district" class="form-control" value="<?= esc($p['district']) ?>"></div>
                        <div class="col-md-6"><label class="form-label">Desa/Kelurahan</label><input type="text" name="village" class="form-control" value="<?= esc($p['village'] ?? '') ?>"></div>
                        <div class="col-12"><label class="form-label">Alamat Detail</label><textarea name="address" class="form-control" rows="2"><?= esc($p['address']) ?></textarea></div>
                    </div>
                    <div id="editMap" style="height:320px;border-radius:12px;border:2px solid #eee;"></div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-6"><input type="text" name="latitude" id="latInput" class="form-control form-control-sm bg-light" value="<?= esc($p['latitude']) ?>" readonly></div>
                        <div class="col-md-6"><input type="text" name="longitude" id="lngInput" class="form-control form-control-sm bg-light" value="<?= esc($p['longitude']) ?>" readonly></div>
                    </div>
                    <input type="hidden" name="maps_url" id="mapsUrl" value="<?= esc($p['maps_url']) ?>">
                </div>

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3"><i class="bi bi-images me-2"></i>Foto Properti</h5>
                    <?php if (! empty($images)): ?>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php foreach ($images as $img): ?>
                                <div class="position-relative">
                                    <img src="<?= esc(property_image_url($img['file_name'])) ?>" style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;">
                                    <a href="<?= site_url('/ads/delete-image/' . hashid((int)$img['id'])) ?>" onclick="return confirm('Hapus foto ini?')" class="btn btn-sm btn-danger position-absolute" style="top:-6px;right:-6px;width:24px;height:24px;padding:0;border-radius:50%;line-height:1;display:flex;align-items:center;justify-content:center;">×</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <label class="form-label small">Tambah foto baru (boleh multiple)</label>
                    <input type="file" name="images[]" id="imagesInput" multiple accept="image/jpeg,image/png,image/webp" class="form-control">
                    <div id="imgPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                </div>

                <div class="card bg-dark text-white border-0 p-4 mb-5 shadow-sm">
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow"><i class="bi bi-save me-1"></i> SIMPAN PERUBAHAN</button>
                </div>

            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Format Harga
document.getElementById('priceInput')?.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g, '');
    e.target.value = v ? Number(v).toLocaleString('id-ID') : '';
});

// Toggle Periode Sewa
document.querySelectorAll('input[name="type"]').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('periodWrapper').style.display = (document.getElementById('typeRent').checked ? 'block' : 'none');
    });
});

// Sync Spesifikasi Berdasarkan Kategori
const catTypes = <?= json_encode($catFormType) ?>;
function syncSpec() {
    const sel = document.getElementById('categorySelect');
    const ft = catTypes[sel.value] || 'building';
    document.querySelectorAll('.spec-building').forEach(el => {
        el.style.display = (ft === 'land' ? 'none' : '');
        el.querySelectorAll('input,select').forEach(i => i.disabled = (ft === 'land'));
    });
}
document.getElementById('categorySelect').addEventListener('change', syncSpec);
syncSpec(); // Jalankan saat load

// Preview Gambar
document.getElementById('imagesInput')?.addEventListener('change', function (e) {
    const prev = document.getElementById('imgPreview');
    prev.innerHTML = '';
    Array.from(e.target.files).forEach(f => {
        const url = URL.createObjectURL(f);
        const img = document.createElement('img');
        img.src = url; img.className = 'rounded border';
        img.style.cssText = 'width:96px;height:96px;object-fit:cover;';
        prev.appendChild(img);
    });
});

// Map Leaflet
const lat = <?= (float)($p['latitude'] ?: -6.2088) ?>;
const lng = <?= (float)($p['longitude'] ?: 106.8456) ?>;
const map = L.map('editMap').setView([lat, lng], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'© OSM' }).addTo(map);
let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

function syncCoord(p) {
    document.getElementById('latInput').value = p.lat.toFixed(7);
    document.getElementById('lngInput').value = p.lng.toFixed(7);
    document.getElementById('mapsUrl').value = `https://www.google.com/maps?q=${p.lat},${p.lng}`;
}

marker.on('dragend', e => syncCoord(e.target.getLatLng()));
map.on('click', e => { marker.setLatLng(e.latlng); syncCoord(e.latlng); });
</script>