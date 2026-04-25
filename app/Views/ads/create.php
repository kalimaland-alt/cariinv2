<?php
/** @var array $categories */ 
/** @var bool $freeSlotUsed */ 
/** @var int $adCount */
/** @var int $slotPrice */ 
/** @var int $balance */

$flashError = session()->getFlashdata('error');

// Map kategori id → form_type untuk sembunyikan field bangunan jika kategori tanah
$catFormType = [];
foreach ($categories as $c) {
    $catFormType[$c['id']] = $c['form_type'] ?? 'building';
}
?>

<div class="container-fluid p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">Pasang Iklan Baru</h4>
            <p class="text-muted mb-0">Lengkapi detail properti sesuai data asli.</p>
        </div>
        <a href="<?= site_url('/my-ads') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($flashError): ?>
        <div class="alert alert-danger shadow-sm"><?= esc($flashError) ?></div>
    <?php endif; ?>

    <form action="<?= site_url('/ads/store') ?>" method="post" enctype="multipart/form-data" id="adsForm">
        <?= csrf_field() ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Informasi Utama</h5>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Iklan <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Rumah Minimalis Modern Hook">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipe Iklan <span class="text-danger">*</span></label>
                            <div class="btn-group w-100">
                                <input type="radio" class="btn-check" name="type" id="typeSell" value="sell" checked>
                                <label class="btn btn-outline-success" for="typeSell">Jual</label>
                                <input type="radio" class="btn-check" name="type" id="typeRent" value="rent">
                                <label class="btn btn-outline-warning" for="typeRent">Sewa</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="categorySelect" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= esc($c['id']) ?>" data-form-type="<?= esc($c['form_type']) ?>"><?= esc($c['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="text" name="price" id="priceInput" class="form-control" inputmode="numeric" required placeholder="0">
                        </div>
                        <div class="col-md-6" id="periodWrapper" style="display:none;">
                            <label class="form-label fw-bold">Periode Sewa</label>
                            <select name="price_period" class="form-select">
                                <option value="-">- Pilih -</option>
                                <option value="daily">Per Hari</option>
                                <option value="monthly">Per Bulan</option>
                                <option value="yearly">Per Tahun</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Spesifikasi & Dokumen</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Luas Tanah (m²)</label>
                            <input type="number" name="land_area" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Luas Bangunan (m²)</label>
                            <input type="number" name="building_area" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Kamar Tidur</label>
                            <input type="number" name="bedrooms" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-4 spec-building">
                            <label class="form-label">Kamar Mandi</label>
                            <input type="number" name="bathrooms" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Legal</label>
                            <select name="legal_status" class="form-select">
                                <option value="">- Pilih -</option>
                                <option value="SHM">SHM</option><option value="HGB">HGB</option>
                                <option value="AJB">AJB</option><option value="Girik">Girik</option>
                                <option value="Waris">Waris</option><option value="SHGB">SHGB</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kondisi Sertifikat</label>
                            <select name="doc_status" class="form-select">
                                <option value="on_hand">Di Pemilik (On Hand)</option>
                                <option value="at_bank">Di Bank</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hadap</label>
                            <select name="orientation" class="form-select">
                                <option value="">- Pilih Hadap -</option>
                                <option value="N">Utara</option><option value="S">Selatan</option>
                                <option value="E">Timur</option><option value="W">Barat</option>
                                <option value="NE">Timur Laut</option><option value="NW">Barat Laut</option>
                                <option value="SE">Tenggara</option><option value="SW">Barat Daya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Lokasi Properti</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Provinsi <span class="text-danger">*</span></label>
                            <select name="province" id="provSelect" class="form-select" required>
                                <option value="">Memuat data...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kab/Kota <span class="text-danger">*</span></label>
                            <select name="city" id="citySelect" class="form-select" required disabled>
                                <option value="">Pilih Provinsi dulu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <select name="district" id="districtSelect" class="form-select" disabled></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelurahan</label>
                            <select name="village" id="villageSelect" class="form-select" disabled></select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Detail (Jalan, No, RT/RW)</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Cari Lokasi di Peta</label>
                        <div class="input-group">
                            <input type="text" id="mapSearchInput" class="form-control" placeholder="Masukkan nama daerah...">
                            <button type="button" id="mapSearchBtn" class="btn btn-primary btn-sm">Cari</button>
                        </div>
                        <div id="mapSearchResults" class="list-group mt-1 shadow-sm" style="position: absolute; z-index: 1000; width: 90%;"></div>
                    </div>

                    <div id="pickerMap" style="height:400px; border-radius:12px; border: 1px solid #eee;" class="mb-2"></div>
                    
                    <div class="row g-2">
                        <div class="col-md-6"><input type="text" name="latitude" id="latInput" class="form-control form-control-sm bg-light" readonly placeholder="Lat"></div>
                        <div class="col-md-6"><input type="text" name="longitude" id="lngInput" class="form-control form-control-sm bg-light" readonly placeholder="Lng"></div>
                    </div>
                    <input type="hidden" name="maps_url" id="mapsUrl">
                </div>

                <div class="card border-0 shadow-sm mb-4 p-4">
                    <h5 class="mb-3">Galeri Foto</h5>
                    <input type="file" name="images[]" id="imagesInput" multiple accept="image/*" class="form-control">
                    <div id="imgPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                </div>

                <div class="card bg-dark text-white border-0 p-4 mb-5 shadow-sm">
                    <div class="row align-items-center text-center text-md-start">
                        <div class="col-md-8">
                            <h6 class="text-white-50 mb-1">Status Slot Iklan</h6>
                            <p class="small mb-0">
                                Slot Gratis: <strong><?= $freeSlotUsed ? 'Habis' : 'Tersedia' ?></strong>
                                <?php if($freeSlotUsed): ?>
                                    | <span class="text-warning fw-bold">Biaya: <?= number_format($slotPrice) ?> Poin</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <?php if ($freeSlotUsed && $balance < $slotPrice): ?>
                                <a href="<?= site_url('/topup') ?>" class="btn btn-warning w-100 fw-bold">Isi Poin Sekarang</a>
                            <?php else: ?>
                                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow">PUBLIKASIKAN</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/** * KONFIGURASI API WILAYAH (DATABASE LOKAL)
 * Mengacu pada Routes.php Anda: api/wilayah/...
 */
const BASE_API = "<?= site_url('api/wilayah') ?>";

// Format Harga
document.getElementById('priceInput')?.addEventListener('input', e => {
    let v = e.target.value.replace(/\D/g, '');
    e.target.value = v ? Number(v).toLocaleString('id-ID') : '';
});

// Fungsi Ambil Data dari Controller Wilayah.php
async function callWilayah(path) {
    try {
        const response = await fetch(`${BASE_API}/${path}`);
        if (!response.ok) return [];
        return await response.json();
    } catch (e) {
        console.error("Gagal memuat data wilayah:", e);
        return [];
    }
}

// Fungsi Populate Dropdown
async function fillDropdown(elementId, path, placeholder) {
    const dropdown = document.getElementById(elementId);
    dropdown.innerHTML = `<option value="">-- ${placeholder} --</option>`;
    dropdown.disabled = true;

    const data = await callWilayah(path);
    if (data && data.length > 0) {
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.name; // Simpan Nama untuk dikirim ke Controller Ads
            opt.dataset.id = item.id; // Simpan ID untuk query anak (kabupaten/kecamatan)
            opt.textContent = item.name;
            dropdown.appendChild(opt);
        });
        dropdown.disabled = false;
    }
}

// Event Listeners Wilayah (Cascading)
document.getElementById('provSelect').addEventListener('change', function() {
    const id = this.selectedOptions[0]?.dataset.id;
    if (id) {
        fillDropdown('citySelect', `regencies/${id}`, 'Pilih Kab/Kota');
    }
    document.getElementById('districtSelect').innerHTML = '';
    document.getElementById('villageSelect').innerHTML = '';
});

document.getElementById('citySelect').addEventListener('change', function() {
    const id = this.selectedOptions[0]?.dataset.id;
    if (id) fillDropdown('districtSelect', `districts/${id}`, 'Pilih Kecamatan');
});

document.getElementById('districtSelect').addEventListener('change', function() {
    const id = this.selectedOptions[0]?.dataset.id;
    if (id) fillDropdown('villageSelect', `villages/${id}`, 'Pilih Kelurahan');
});

// Load Provinsi Pertama Kali
fillDropdown('provSelect', 'provinces', 'Pilih Provinsi');


// --- LOGIKA PETA (Leaflet) ---
const map = L.map('pickerMap').setView([-2.5489, 118.0149], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker = null;
function updateMarker(lat, lng) {
    if (marker) marker.setLatLng([lat, lng]);
    else marker = L.marker([lat, lng], {draggable: true}).addTo(map);
    
    document.getElementById('latInput').value = lat.toFixed(7);
    document.getElementById('lngInput').value = lng.toFixed(7);
    document.getElementById('mapsUrl').value = `https://www.google.com/maps?q=${lat},${lng}`;
    
    marker.on('dragend', function(e) {
        const p = e.target.getLatLng();
        updateMarker(p.lat, p.lng);
    });
}

map.on('click', e => updateMarker(e.latlng.lat, e.latlng.lng));

// --- FITUR CARI LOKASI ---
const searchBtn = document.getElementById('mapSearchBtn');
const searchInput = document.getElementById('mapSearchInput');
const resultsBox = document.getElementById('mapSearchResults');

async function doSearch() {
    const q = searchInput.value;
    if (q.length < 3) return;
    resultsBox.innerHTML = '<div class="list-group-item">Mencari...</div>';
    
    try {
        const r = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&countrycodes=id&limit=5`);
        const data = await r.json();
        resultsBox.innerHTML = '';
        data.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'list-group-item list-group-item-action small text-start';
            btn.textContent = item.display_name;
            btn.onclick = () => {
                const lat = parseFloat(item.lat);
                const lon = parseFloat(item.lon);
                map.setView([lat, lon], 15);
                updateMarker(lat, lon);
                resultsBox.innerHTML = '';
            };
            resultsBox.appendChild(btn);
        });
    } catch (e) { resultsBox.innerHTML = '<div class="list-group-item text-danger">Gagal mencari lokasi.</div>'; }
}

searchBtn.addEventListener('click', doSearch);
searchInput.addEventListener('keydown', e => { if(e.key === 'Enter') { e.preventDefault(); doSearch(); }});

// Preview Foto
document.getElementById('imagesInput')?.addEventListener('change', function(e) {
    const prev = document.getElementById('imgPreview');
    prev.innerHTML = '';
    Array.from(e.target.files).forEach(f => {
        const url = URL.createObjectURL(f);
        prev.innerHTML += `<img src="${url}" class="rounded border" style="width:80px;height:80px;object-fit:cover;">`;
    });
});

// Category spec toggle
const catTypes = <?= json_encode($catFormType) ?>;
document.getElementById('categorySelect').addEventListener('change', function() {
    const type = catTypes[this.value] || 'building';
    document.querySelectorAll('.spec-building').forEach(el => el.style.display = (type === 'land' ? 'none' : ''));
});
</script>