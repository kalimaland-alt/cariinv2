// ============================================================
// CariIn - Main JS
// ============================================================

document.addEventListener('DOMContentLoaded', function () {
    // Hero tabs (Semua/Dijual/Disewa)
    document.querySelectorAll('.hero-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.hero-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const input = document.getElementById('heroTypeInput');
            if (input) input.value = this.dataset.type || '';
        });
    });

    // Auto-init Leaflet map on property detail page
    const mapEl = document.getElementById('propertyMap');
    if (mapEl && typeof L !== 'undefined') {
        const lat = parseFloat(mapEl.dataset.lat);
        const lng = parseFloat(mapEl.dataset.lng);
        const title = mapEl.dataset.title || 'Lokasi Properti';
        if (!isNaN(lat) && !isNaN(lng)) {
            const map = L.map('propertyMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map).bindPopup(title).openPopup();
        }
    }

    // Auto-hide alert after 5s
    document.querySelectorAll('.alert-dismissible').forEach(el => {
        setTimeout(() => {
            if (typeof bootstrap !== 'undefined') {
                const inst = bootstrap.Alert.getOrCreateInstance(el);
                inst.close();
            }
        }, 5000);
    });
});

// Property card quick actions (wishlist + compare)
function addToCompareCard(propertyId) {
    const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/compare/add/' + propertyId;
    fetch(url, { method: 'POST' })
        .then(r => r.json())
        .then(d => {
            if (d.ok) {
                if (confirm('Ditambahkan ke perbandingan (' + d.count + '). Buka halaman Bandingkan?')) {
                    window.location = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/compare';
                }
            } else { alert(d.message || 'Gagal menambahkan.'); }
        });
}

function toggleWishCard(propertyId, btn) {
    const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/wishlist/toggle/' + propertyId;
    fetch(url, { method: 'POST', headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(r => r.json())
        .then(d => {
            if (d.login) { window.location = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/login'; return; }
            const i = btn.querySelector('i');
            if (d.added) {
                i.classList.remove('bi-heart'); i.classList.add('bi-heart-fill');
                btn.style.color = '#EF4444';
            } else {
                i.classList.add('bi-heart'); i.classList.remove('bi-heart-fill');
                btn.style.color = '';
            }
        });
}
