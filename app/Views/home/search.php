<section class="py-4 bg-cream">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item active">Pencarian</li>
            </ol>
        </nav>
        <h1 class="h3 mb-3">
            <?php if (! empty($category)): ?>
                Kategori: <?= esc($category['name']) ?>
            <?php else: ?>
                Hasil Pencarian
            <?php endif; ?>
            <small class="text-muted fs-6">(<?= $result['total'] ?> properti)</small>
        </h1>

        <form action="<?= site_url('/search') ?>" method="get" class="card card-soft p-3 mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kata kunci</label>
                    <input name="q" type="text" class="form-control" value="<?= esc($filters['keyword'] ?? '') ?>" placeholder="Jakarta, BSD...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tipe</label>
                    <select name="type" class="form-select">
                        <option value="">Semua</option>
                        <option value="sell" <?= ($filters['type'] ?? '') === 'sell' ? 'selected' : '' ?>>Dijual</option>
                        <option value="rent" <?= ($filters['type'] ?? '') === 'rent' ? 'selected' : '' ?>>Disewa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= esc($c['slug']) ?>" <?= ($filters['category'] ?? '') === $c['slug'] ? 'selected' : '' ?>>
                                <?= esc($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Provinsi</label>
                    <select name="province" id="srchProv" class="form-select">
                        <option value="">Semua Provinsi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Kab/Kota</label>
                    <select name="city" id="srchCity" class="form-select">
                        <option value="">Semua Kota</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Harga Min</label>
                    <input name="min_price" type="number" class="form-control" value="<?= esc($filters['min_price'] ?? '') ?>" placeholder="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Harga Max</label>
                    <input name="max_price" type="number" class="form-control" value="<?= esc($filters['max_price'] ?? '') ?>" placeholder="∞">
                </div>
                <div class="col-md-2 d-grid align-self-end">
                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i> Cari</button>
                </div>
                <div class="col-md-2 d-grid align-self-end">
                    <a href="<?= site_url('/search') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (empty($result['data'])): ?>
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <h5>Tidak ada properti yang cocok</h5>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda.</p>
                <a href="<?= site_url('/search') ?>" class="btn btn-outline-success">Reset Pencarian</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($result['data'] as $p): ?>
                    <div class="col-sm-6 col-lg-4">
                        <?= view('partials/property_card', ['p' => $p]) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($result['last_page'] > 1): ?>
                <?php $qs = $_GET; ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $result['last_page']; $i++): ?>
                            <?php $qs['page'] = $i; ?>
                            <li class="page-item <?= $i === $result['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query($qs) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<script>
const WILAYAH_API = 'https://emsifa.github.io/api-wilayah-indonesia/api';
const provSel = document.getElementById('srchProv');
const citySel = document.getElementById('srchCity');
const selectedProv = <?= json_encode($filters['province'] ?? '') ?>;
const selectedCity = <?= json_encode($filters['city'] ?? '') ?>;

async function loadProvincesSrch(){
    try {
        const r = await fetch(`${WILAYAH_API}/provinces.json`);
        const list = await r.json();
        list.forEach(p => {
            const o = document.createElement('option');
            o.value = p.name; o.dataset.id = p.id; o.textContent = p.name;
            if (p.name === selectedProv) o.selected = true;
            provSel.appendChild(o);
        });
        if (selectedProv) loadCitiesSrch();
    } catch (e) { console.error(e); }
}
async function loadCitiesSrch(){
    const id = provSel.selectedOptions[0]?.dataset.id;
    citySel.innerHTML = '<option value="">Semua Kota</option>';
    if (!id) return;
    try {
        const r = await fetch(`${WILAYAH_API}/regencies/${id}.json`);
        const list = await r.json();
        list.forEach(x => {
            const o = document.createElement('option');
            o.value = x.name; o.textContent = x.name;
            if (x.name === selectedCity) o.selected = true;
            citySel.appendChild(o);
        });
    } catch (e) { console.error(e); }
}
provSel.addEventListener('change', loadCitiesSrch);
loadProvincesSrch();
</script>
