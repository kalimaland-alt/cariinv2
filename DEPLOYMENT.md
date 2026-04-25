# 🚀 CariIn — Deployment & Upgrade Guide

## Daftar Isi
1. [Upgrade dari versi sebelumnya](#upgrade)
2. [Setup Midtrans QRIS](#midtrans)
3. [Deployment ke Production](#deploy)
4. [SEO & Performance](#seo)
5. [Troubleshooting](#trouble)

---

## <a id="upgrade"></a>1. Upgrade dari versi sebelumnya

Anda menerima ZIP `carin-bundle.zip` yang berisi **kode lengkap (Iterasi 1 + 2 + 3 + 4 + 5)**. Jika sebelumnya Anda sudah punya folder `carin/` yang lama, ikuti langkah ini:

### Opsi A — Reset Penuh (paling aman, recommended)
1. Backup folder lama: `mv carin carin-backup-$(date +%Y%m%d)`
2. Backup database lama: `mysqldump -u root carin_db > carin_db_backup.sql`
3. Drop database lama: `mysql -u root -e "DROP DATABASE carin_db; CREATE DATABASE carin_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`
4. Extract `carin-bundle.zip` ke lokasi project (contoh: `htdocs/carin/`)
5. Copy `.env` lama: `cp ../carin-backup-*/.env ./carin/.env` (atau buat dari `.env.example`)
6. Install dependencies: `cd carin && composer install`
7. Migrate + seed: `php spark migrate && php spark db:seed DatabaseSeeder`
8. (Opsional) Pin tahap demo data: `php spark db:seed DemoPropertySeeder`

### Opsi B — Migrate Inkremental (kalau ada data penting di DB lama)
1. Backup database tetap dilakukan
2. Extract ZIP, copy `.env` lama
3. `composer install`
4. `php spark migrate` — akan otomatis run migrasi baru saja:
   - `2026-01-25-000001_AddPointsBalanceToUsers`
   - `2026-01-25-000002_CreateSettings`
   - `2026-01-25-000003_CreateTopups`
   - `2026-01-25-000004_AddVillageAndExpandEnums`
5. Login admin → `/admin/settings` isi data footer & sistem poin

---

## <a id="midtrans"></a>2. Setup Midtrans QRIS

Sistem sudah siap menerima Midtrans. Saat creds tersedia, cukup edit `.env`:

```ini
midtrans.serverKey   = 'SB-Mid-server-XXXXXXXXXXXX'
midtrans.clientKey   = 'SB-Mid-client-XXXXXXXXXXXX'
midtrans.isProduction = false
```

### Cara dapat creds:
1. Daftar di [https://dashboard.sandbox.midtrans.com](https://dashboard.sandbox.midtrans.com) (gratis)
2. **Settings → Access Keys** — copy Server Key & Client Key
3. **Settings → Payment Methods** — aktifkan **QRIS** (+ GoPay, ShopeePay, BCA VA, dll opsional)
4. **Settings → Payment Notification URL** — isi: `https://yourdomain.com/topup/notification`
   - Untuk testing lokal: gunakan **ngrok** → `ngrok http 8080` → pakai URL ngrok di Midtrans dashboard

### Cara kerja:
- Saat creds ada, member klik "Beli" → backend create Snap token Midtrans → user diarahkan ke halaman pembayaran
- Saat user bayar (QRIS/GoPay/Bank Transfer) → Midtrans kirim webhook ke `/topup/notification`
- Sistem otomatis update status topup ke `success` & menambah poin ke saldo user
- Tanpa creds, system fallback ke **manual approve admin** lewat `/admin/finance/topup-history`

---

## <a id="deploy"></a>3. Deployment ke Production

### Shared Hosting (cPanel)
1. Upload semua file via FTP/File Manager
2. Pindahkan isi folder `public/` → `public_html/` (atau bikin subfolder)
3. Edit `public_html/index.php` agar `FCPATH` menunjuk ke folder atas:
   ```php
   require FCPATH . '../carin-private/app/Config/Paths.php';
   ```
4. Set permission: `chmod -R 755 carin/` & `chmod -R 777 carin/writable/ carin/public/assets/uploads/`
5. Import `database/carin_schema.sql` via phpMyAdmin **ATAU** jalankan `php spark migrate` via SSH
6. Edit `.env` dengan credential production
7. Update `Authorized redirect URI` di Google Console → domain production
8. Update Midtrans `Payment Notification URL` → domain production

### VPS / Cloud (Railway, Fly.io, Vercel PHP runtime)
- Gunakan Dockerfile PHP 8.1+ dengan Apache atau Nginx + PHP-FPM
- Env vars dari dashboard provider
- DB: managed MySQL (PlanetScale, AWS RDS, atau cluster MariaDB)
- File upload: pertimbangkan migrasi ke S3-compatible storage untuk skalabilitas

### Sample Apache vhost
```apache
<VirtualHost *:80>
    ServerName carin.id
    DocumentRoot /var/www/carin/public
    <Directory /var/www/carin/public>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/carin-error.log
</VirtualHost>
```

---

## <a id="seo"></a>4. SEO & Performance

### SEO Built-in
- ✅ `/sitemap.xml` — auto-generated dari semua iklan published
- ✅ `/robots.txt` — di `public/robots.txt`
- ✅ Open Graph meta tags di halaman detail (Facebook/Twitter card preview)
- ✅ Title tag dinamis per halaman
- 💡 **Tips**: Submit `https://carin.id/sitemap.xml` ke Google Search Console & Bing Webmaster

### Performance
- Gunakan CDN untuk asset static (Cloudflare gratis)
- Aktifkan opcache PHP (`opcache.enable=1`)
- Gunakan Redis/Memcached untuk session storage di production
- Compress images sebelum upload (atau pakai library Intervention Image)
- Lazy-load gambar properti (sudah bawaan via `loading="lazy"` di property_card)

---

## <a id="trouble"></a>5. Troubleshooting

| Problem | Solusi |
|---|---|
| `SQLSTATE[HY000] [2002]` | MySQL service belum jalan / credential `.env` salah |
| Halaman 404 di semua URL kecuali home | Enable `mod_rewrite` Apache; pastikan `.htaccess` di `public/` aktif |
| `redirect_uri_mismatch` Google | URL di `.env` `google.redirectUri` HARUS sama persis dengan yang didaftar di Google Console |
| Foto profile/properti tidak ke-upload | `chmod -R 777 public/assets/uploads/` |
| Composer error `class not found` | Jalankan `composer dump-autoload -o` |
| Migration error duplicate column | Hapus `migrations` table di DB & ulangi (atau pakai Opsi A reset) |
| Midtrans Snap popup tidak muncul | Cek browser console; pastikan `clientKey` benar & `Snap.js` ter-load |
| Webhook Midtrans tidak masuk | Cek log `writable/logs/`, pastikan URL di Midtrans dashboard correct & accessible |
| Footer kosong / tidak update | Login admin → `/admin/settings` → isi data → Simpan |

---

## Daftar fitur Iterasi (status final)

| Fitur | Status | Routing |
|---|---|---|
| Auth Google + Email/Password | ✅ | `/login`, `/register`, `/auth/google` |
| Landing + Search + Filter | ✅ | `/`, `/search` |
| Detail Properti + KPR Calculator | ✅ | `/property/{slug}` |
| CRUD iklan member | ✅ | `/my-ads`, `/ads/create` |
| Profile Settings (foto/HP/password) | ✅ | `/dashboard/profile` |
| Top Up Poin (Midtrans-ready) | ✅ | `/topup`, `/topup/history` |
| Wishlist | ✅ | `/wishlist` |
| Compare (max 4 properti) | ✅ | `/compare` |
| Chat Buyer ↔ Seller | ✅ | `/chat` |
| Rating Seller | ✅ | inline di detail |
| Share WA/FB/Twitter/IG/Link | ✅ | inline di detail + card |
| Admin Moderasi | ✅ | `/admin/moderation` |
| Admin Finance Dashboard | ✅ | `/admin/finance` |
| Admin Settings (Footer + Sistem Poin) | ✅ | `/admin/settings` |
| SEO sitemap + robots | ✅ | `/sitemap.xml`, `/robots.txt` |

---

## Dukungan
Untuk masalah implementasi, cek log di `writable/logs/log-{date}.log` atau buka issue di repo GitHub.

**Selamat menggunakan CariIn! 🏡**

---

## 6. Setup SMTP Email (untuk Lupa Password & Verifikasi Email)

Edit `.env`:
```ini
mail.protocol   = 'smtp'
mail.SMTPHost   = 'smtp.gmail.com'
mail.SMTPPort   = 587
mail.SMTPUser   = 'youremail@gmail.com'
mail.SMTPPass   = 'YOUR_APP_PASSWORD_16_CHAR'
mail.SMTPCrypto = 'tls'
mail.fromEmail  = 'noreply@carin.id'
mail.fromName   = 'CariIn'
```

### Provider yang direkomendasikan:

| Provider | Host | Port | Keterangan |
|---|---|---|---|
| **Gmail** | `smtp.gmail.com` | 587 | Wajib pakai **App Password** 16-digit (bukan password akun). Generate di https://myaccount.google.com/apppasswords |
| **Mailtrap** | `smtp.mailtrap.io` | 2525 | Untuk testing — email tidak benar terkirim, hanya masuk inbox sandbox |
| **SendGrid** | `smtp.sendgrid.net` | 587 | User: `apikey`, Pass: API Key. Free 100 email/hari |
| **Mailgun** | `smtp.mailgun.org` | 587 | Free tier 5,000 email/bulan |
| **Brevo** | `smtp-relay.brevo.com` | 587 | Free 300 email/hari |

### Test SMTP:
1. Edit `.env` dengan kredensial benar
2. Buka `/forgot-password`, masukkan email yg terdaftar
3. Cek inbox; kalau gagal cek log `writable/logs/log-{date}.log`

### Mode fallback (kalau SMTP belum dikonfigurasi):
- Link reset/verifikasi otomatis di-log ke `writable/logs/`
- Format log: `[FORGOT_PASSWORD] Manual link for {email}: {url}`
- Admin bisa copy-paste & kirim ke user manual via WA/email
- Sistem tetap jalan, hanya fitur email saja yang manual

---

## 7. Logika Pasang Iklan (Slot & Poin)

- **Slot 1**: GRATIS (sekali per akun)
- **Slot 2 dan seterusnya**: butuh poin (default 20 poin/slot, configurable di `/admin/settings`)
- Poin diisi via **Top Up** (Midtrans QRIS atau approve manual admin)
- 1 poin = Rp 1.000 (configurable)

### Jika saldo kurang:
- Saat klik "Pasang Iklan", system redirect ke `/topup` dgn pesan "Slot gratis sudah terpakai. Anda butuh X poin..."
- Member harus top up dulu, baru bisa pasang iklan baru
