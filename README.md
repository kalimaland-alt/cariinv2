# 🏡 CariIn - Real Estate Marketplace

Platform marketplace properti (jual/sewa) berbasis **CodeIgniter 4 + MySQL**, dilengkapi dengan Google OAuth 2.0, QRIS Midtrans, Leaflet Maps, dan admin CMS.

---

## 📸 Preview

- **Public portal** — Landing, pencarian, detail properti
- **Member dashboard** — Kelola iklan, pasang iklan, profil
- **Admin CMS** (`/admin`) — Moderasi iklan, manajemen user, log transaksi

---

## 🧰 Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.1+, CodeIgniter 4.4 |
| Database | MySQL 8.0 / MariaDB 10.3+ |
| Frontend | Bootstrap 5.3, jQuery 3.7, Leaflet.js 1.9 |
| Auth | Google OAuth 2.0 (`league/oauth2-google`) |
| Payment | Midtrans Snap QRIS (`midtrans/midtrans-php`) |

---

## 🎨 Design System

- **Palet:** Sage Green `#87A96B` + Warm Brown `#8B6F47`
- **Font:** Plus Jakarta Sans (heading) + Inter (body)

---

## 🚀 Instalasi di Lokal (XAMPP / Laragon)

### Prasyarat
- PHP **8.1+**
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx) — XAMPP atau Laragon direkomendasikan
- Git (opsional)

### Langkah 1 — Clone / Copy Project

```bash
# Jika dari git:
git clone <repo-url> carin
cd carin

# Atau copy folder ini ke:
#   Windows XAMPP : C:\xampp\htdocs\carin
#   Windows Laragon: C:\laragon\www\carin
#   Linux/Mac     : /var/www/html/carin
```

### Langkah 2 — Install Dependencies

```bash
composer install
```

> Jika belum punya Composer: https://getcomposer.org/download/

### Langkah 3 — Konfigurasi Environment

```bash
cp .env.example .env
```

Edit `.env`:

```ini
app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = carin_db
database.default.username = root
database.default.password =

# Isi setelah dapat credential Google Console
google.clientId = 'YOUR_CLIENT_ID.apps.googleusercontent.com'
google.clientSecret = 'YOUR_SECRET'
google.redirectUri = 'http://localhost:8080/auth/google/callback'

# Isi setelah dapat credential Midtrans sandbox
midtrans.serverKey = 'SB-Mid-server-xxx'
midtrans.clientKey = 'SB-Mid-client-xxx'
midtrans.isProduction = false
```

### Langkah 4 — Setup Database

**Opsi A — Via Migration (Recommended):**

```bash
# Buat database dulu (via phpMyAdmin atau CLI):
mysql -u root -e "CREATE DATABASE carin_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Jalankan migrasi
php spark migrate

# Seed data (kategori + admin default)
php spark db:seed DatabaseSeeder
```

**Opsi B — Import SQL manual:**

```bash
# Import schema langsung dari file SQL
mysql -u root carin_db < database/carin_schema.sql
```

Atau via **phpMyAdmin**:
1. Buat database `carin_db`
2. Klik tab **Import** → upload `database/carin_schema.sql`

### Langkah 5 — Jalankan Server

**Opsi A — Built-in CI4 server:**

```bash
php spark serve
```
→ Buka http://localhost:8080

**Opsi B — Via XAMPP/Laragon:**

Pastikan folder `carin/` ada di `htdocs/` atau `www/`, lalu buka:  
→ http://localhost/carin/public

Atau buat virtual host yang menunjuk ke `public/`.

---

## 🔑 Setup Google OAuth 2.0

1. Buka https://console.cloud.google.com
2. Buat project baru (atau pilih yang ada)
3. Menu: **APIs & Services → OAuth consent screen** → pilih **External** → isi data aplikasi
4. Menu: **APIs & Services → Credentials → Create Credentials → OAuth Client ID**
   - Application type: **Web application**
   - Authorized redirect URIs: `http://localhost:8080/auth/google/callback`
5. Copy **Client ID** & **Client Secret** ke `.env` di baris:
   ```
   google.clientId = '...'
   google.clientSecret = '...'
   ```

---

## 💳 Setup Midtrans Sandbox

1. Daftar gratis di https://dashboard.sandbox.midtrans.com
2. Settings → **Access Keys** — copy:
   - Server Key → `.env` `midtrans.serverKey`
   - Client Key → `.env` `midtrans.clientKey`
3. Aktifkan metode pembayaran **QRIS** di dashboard Midtrans
4. Settings → **Payment Notification URL** (untuk webhook):
   - URL: `http://<your-domain>/payment/notification`
   - Untuk testing lokal, gunakan **ngrok**: `ngrok http 8080` lalu pakai URL ngrok

> ⚠️ Integrasi Midtrans baru aktif di **Iterasi 2** (source code saat ini adalah Iterasi 1).

---

## 👤 Akun Admin Default

Jika Anda menjalankan seeder/SQL:

- **Email:** `admin@carin.local`
- **Password:** `admin123` (hanya jika ditambahkan form login manual)
- **Role:** admin → akses `/admin`

> Untuk login normal via Google, admin pertama bisa diubah manual di database: ubah kolom `role = 'admin'` pada user yang mendaftar via Google.

---

## 📁 Struktur Folder

```
carin/
├── app/
│   ├── Config/              # Konfigurasi CI4 (Routes, Filters, Database, Auth)
│   ├── Controllers/         # Home, Auth, Property, Dashboard, Ads, Payment, Admin/*
│   ├── Models/              # User, Category, Property, PropertyDetail, PropertyImage, Payment
│   ├── Filters/             # AuthFilter, AdminFilter
│   ├── Libraries/           # GoogleAuth (wrapper OAuth)
│   ├── Helpers/             # app_helper (rupiah, wa_link, orientation_label, dsb.)
│   ├── Database/
│   │   ├── Migrations/      # 10 tabel (users, categories, properties, dst.)
│   │   └── Seeds/           # CategorySeeder, AdminUserSeeder
│   └── Views/
│       ├── layouts/         # public, dashboard, admin, blank (login)
│       ├── partials/        # navbar, footer, property_card
│       ├── home/            # index, search
│       ├── property/        # detail
│       ├── auth/            # login
│       ├── dashboard/       # index, profile
│       └── admin/           # dashboard, users, categories, transactions
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
│       ├── css/app.css      # Design system Sage + Brown
│       ├── js/app.js        # Leaflet init + helpers
│       ├── img/             # logo, placeholder
│       └── uploads/properties/ (chmod 777 untuk upload)
├── writable/                # Cache, logs, sessions (chmod 777)
├── database/
│   └── carin_schema.sql     # SQL standalone (backup/alternatif migrasi)
├── .env.example             # Template environment
├── composer.json
└── spark                    # CLI CI4
```

---

## 🗺️ Routes Utama

### Public (tanpa login)
- `GET /` — Landing
- `GET /search` — Cari properti + filter
- `GET /property/{slug}` — Detail properti
- `GET /category/{slug}` — Daftar properti per kategori
- `GET /login` — Halaman login
- `GET /auth/google` — Redirect ke Google
- `GET /auth/google/callback` — Callback OAuth
- `GET /logout`

### Member (filter: `auth`)
- `GET /dashboard` — Dashboard ringkasan
- `GET /my-ads` — Daftar iklan saya *(Iterasi 2)*
- `GET /ads/create` — Buat iklan *(Iterasi 2)*
- `GET /payment/new-slot` — Beli slot iklan *(Iterasi 2)*

### Admin (filter: `admin`)
- `GET /admin` — Dashboard admin
- `GET /admin/moderation` — Moderasi iklan *(Iterasi 3)*
- `GET /admin/users` — Manajemen user
- `GET /admin/categories` — Kelola kategori
- `GET /admin/transactions` — Log transaksi QRIS

---

## 📋 Scope Iterasi 1 (Rilis Ini)

✅ **Selesai:**
- Setup project CI4 + composer dependencies
- 10 migration + seeder (kategori + admin default)
- Design system Sage + Brown
- Google OAuth 2.0 flow (login/logout)
- Auth & Admin filters
- Landing page (hero, search, featured, categories, how it works)
- Halaman pencarian dengan filter (tipe, kategori, harga, kota)
- Halaman detail properti (gallery, specs, Leaflet map, WA CTA)
- Dashboard member (placeholder, siap dikembangkan)
- Admin CMS skeleton (dashboard stats, users, categories, transactions)

🚧 **Belum (Iterasi 2-5):**
- Iterasi 2: CRUD iklan dinamis (form building/land) + multi-upload Dropzone + Leaflet picker + QRIS Midtrans
- Iterasi 3: Admin moderasi (approve/reject) + manajemen kategori
- Iterasi 4: Wishlist + Compare + Chat antar user + Rating seller
- Iterasi 5: Polish, SEO, deployment guide

---

## 🚢 Deployment ke Production

### Shared Hosting (cPanel)
1. Upload semua file ke hosting
2. Pindahkan isi `public/` → `public_html/`
3. Edit `public_html/index.php`:
   ```php
   require FCPATH . '../../carin/app/Config/Paths.php';
   ```
4. Pastikan folder `writable/` dan `public/assets/uploads/` punya permission 755/777
5. Import `database/carin_schema.sql` via phpMyAdmin
6. Edit `.env` dengan credential production
7. Update `Authorized redirect URI` di Google Console ke domain production

### VPS / Railway / Fly.io
- Gunakan Dockerfile PHP 8.1 + Apache/Nginx
- Env variables dari dashboard cloud provider
- Database: MySQL managed atau PlanetScale

---

## 🐛 Troubleshooting

**Error "The configuration file .env does not exist"**  
→ Copy `.env.example` → `.env`, lalu restart server.

**Error "SQLSTATE[HY000] [2002] Connection refused"**  
→ Pastikan MySQL service jalan & credential `.env` benar.

**Error Google OAuth "redirect_uri_mismatch"**  
→ Pastikan URL di `.env` (`google.redirectUri`) **sama persis** dengan yang didaftarkan di Google Console.

**Halaman 404 di semua URL kecuali homepage**  
→ Enable `mod_rewrite` di Apache, atau pastikan `.htaccess` di folder `public/` aktif.

**Folder `writable/` / `uploads/` tidak bisa ditulis**  
```bash
chmod -R 777 writable/
chmod -R 777 public/assets/uploads/
```

---

## 📜 Lisensi

MIT — Bebas digunakan dan dimodifikasi.

---

## 🙏 Credits

- [CodeIgniter 4](https://codeigniter.com/) - PHP Framework
- [Bootstrap 5](https://getbootstrap.com/) - UI Framework
- [Leaflet](https://leafletjs.com/) + [OpenStreetMap](https://www.openstreetmap.org/) - Maps
- [Bootstrap Icons](https://icons.getbootstrap.com/) - Icons
- [league/oauth2-google](https://github.com/thephpleague/oauth2-google) - Google OAuth
- [midtrans/midtrans-php](https://github.com/Midtrans/midtrans-php) - Payment
