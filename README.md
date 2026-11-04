# CALMATS — Landing Page

Landing page untuk **CALMATS**, brand fashion dengan estetika elegan, minimalist, dan streetwear yang membawa cerita di setiap produk.

Tahap saat ini: **storefront dengan pengelolaan produk**. Homepage, katalog database, detail varian produk, halaman brand, community, cart tersinkron, login Laravel, dashboard admin Filament, serta pemesanan melalui WhatsApp sudah tersedia. Pembayaran dan ongkir otomatis belum dibuat.

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel (versi 12) |
| Templating | Blade (komponen di `resources/views/components`) |
| Styling | Tailwind CSS v4 |
| Interaksi | Alpine.js |
| Bundler | Vite |
| Dashboard admin | Filament 4 |
| Database lokal | SQLite (struktur kompatibel PostgreSQL) |

Database lokal tetap memakai SQLite agar mudah dijalankan. Untuk deployment, koneksi dapat dipindahkan ke PostgreSQL lewat `.env` tanpa mengubah model Produk.

## Yang sudah jadi

- Navbar (desktop + menu mobile, indikator cart, link Login)
- Hero, Featured Collection, Brand Story, Editorial, Community, CTA, Footer
- Halaman Shop dengan search, filter kategori, sorting, load more, dan halaman detail produk
- Detail produk dengan pilihan warna/ukuran, stok per varian, serta harga promo
- Halaman About dan Community
- Community Feed dengan upload foto/caption, preview, status moderasi, dan publikasi kiriman yang disetujui
- Cart guest di browser dan sinkronisasi SQLite untuk pengguna login, termasuk foto, varian, quantity, subtotal, serta konfirmasi hapus
- Ringkasan pesanan dan handoff WhatsApp dengan fallback salin pesan
- Login, registrasi, lupa/reset password, logout, halaman profil, serta login admin khusus menggunakan autentikasi Laravel
- Model, migration, dan factory Produk
- Dashboard admin Filament untuk CRUD produk dan upload foto
- Moderasi kiriman Community dari dashboard admin (menunggu, disetujui, atau ditolak)
- Tampilan responsif yang sudah diaudit untuk desktop, tablet, smartphone, navigasi, formulir, katalog, cart, autentikasi, dan dashboard admin
- Fallback otomatis: kalau foto belum ada, tampil kotak abu-abu berlabel "placeholder"

## Yang belum jadi

- Katalog produk sengaja masih kosong sampai data dan foto resmi CALMATS tersedia.
- Pembayaran, ongkir otomatis, dan riwayat pesanan belum dibuat.
- URL WhatsApp dan Instagram resmi perlu diisi lewat `.env`.
- Foto asli (hero, story, editorial, komunitas, dan produk) belum ada.

## Cara menjalankan di komputer kamu

### 1. Yang harus terpasang

- **PHP 8.2 atau lebih baru** (XAMPP atau Laragon boleh)
- **Composer** (getcomposer.org)
- **Node.js 20.19 atau lebih baru** (nodejs.org)
- **Git** (git-scm.com)

Cek dengan perintah: `php -v`, `composer -V`, `node -v`, `git --version`.

Catatan XAMPP: aktifkan ekstensi `zip`, `fileinfo`, dan `sqlite3` di `php.ini` kalau muncul error soal ekstensi.

### 2. Ambil proyek

```bash
git clone https://github.com/USERNAME/calmats-web.git
cd calmats-web
```

(Ganti `USERNAME` dengan username GitHub pemilik repo.)

### 3. Install dependensi

```bash
composer install
npm install
```

### 4. Siapkan file environment

```bash
copy .env.example .env
php artisan key:generate
```

### 5. Siapkan database bawaan

Proyek ini memakai SQLite bawaan Laravel untuk pengembangan lokal. Jalankan migration:

```bash
php artisan migrate
```

Kalau ditanya "database does not exist, create it?", jawab **Yes**.

### 6. Jalankan (butuh 2 terminal sekaligus)

Terminal 1:
```bash
npm run dev
```

Terminal 2:
```bash
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser.

### 7. Siapkan akun dashboard admin

Buat akun melalui perintah interaktif berikut:

```bash
php artisan make:filament-user
php artisan calmats:admin email-admin@example.com
```

Isi nama, email, dan password milik pengelola, lalu jalankan perintah kedua menggunakan email yang sama untuk memberikan akses admin. Setelah selesai, buka halaman login khusus admin di **http://127.0.0.1:8000/admin/login**. Akun pelanggan biasa tidak dapat mengakses dashboard admin.

Jika ingin mengunggah foto produk, pastikan link storage sudah dibuat:

```bash
php artisan storage:link
```

## Struktur penting

```
routes/web.php                              -> daftar route (home + placeholder)
resources/css/app.css                       -> warna, font, dan style dasar (Tailwind v4 @theme)
resources/js/app.js                         -> Alpine.js, store cart, animasi reveal
resources/views/home.blade.php              -> susunan halaman utama
resources/views/shop/                       -> katalog dan detail produk
resources/views/about.blade.php             -> cerita dan prinsip brand
resources/views/community.blade.php         -> halaman komunitas
resources/views/cart.blade.php              -> keranjang berbasis browser
resources/views/auth/                       -> login, register, reset password, dan profil
app/Models/Product.php                     -> model katalog produk
database/migrations/*create_products*      -> struktur tabel produk
app/Filament/Resources/Products/           -> CRUD produk dashboard admin
config/calmats.php                          -> URL sosial
resources/views/components/
  layouts/app.blade.php                     -> kerangka HTML semua halaman
  navbar, hero, featured-collection,
  product-card, brand-story,
  editorial-highlight, community,
  brand-cta, footer                         -> bagian-bagian homepage
  img.blade.php                             -> komponen gambar dengan fallback placeholder
public/images/calmats/                      -> taruh semua foto dan logo di sini
```

## Foto yang perlu diisi

Taruh di `public/images/calmats/` (nama harus persis sama, huruf besar-kecil berpengaruh):

| File | Dipakai di |
|---|---|
| `Logo.png` | Navbar |
| `hero.jpg` | Hero |
| `story.jpg` | Brand Story |
| `editorial-1.jpg`, `editorial-2.jpg`, `editorial-3.jpg` | Editorial |

Foto produk dan komunitas ditambahkan lewat komponen masing-masing.

## Konfigurasi kanal resmi

Isi nilai berikut di `.env` ketika URL resmi sudah tersedia:

```env
CALMATS_WHATSAPP_URL=https://wa.me/62xxxxxxxxxxx
CALMATS_INSTAGRAM_URL=https://instagram.com/namaakun
```

Jika belum diisi, footer akan menampilkan status "segera hadir" dan tidak mengarah ke tautan generik.

## PostgreSQL untuk deployment

Struktur tabel Produk dapat digunakan di PostgreSQL. Siapkan database PostgreSQL, lalu ubah bagian database pada `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=calmats
DB_USERNAME=postgres
DB_PASSWORD=kata_sandi_database
```

Setelah koneksi tersedia, jalankan `php artisan migrate`. Jangan menyimpan password database asli di `.env.example` atau Git.

## Desain (jangan diubah tanpa diskusi)

- Warna: latar `#F7F6F3`, teks `#1D1E20`, abu muda `#E4E2DD`, aksen `#4D5B62`. Didefinisikan di `resources/css/app.css`.
- Font: **Familjen Grotesk** (UI) dan **Newsreader** (headline dan storytelling).
- Prinsip: sedikit card, border, dan shadow. Foto, layout, dan tipografi yang jadi bintang. Animasi dibuat ringan dan menghormati pengaturan reduced motion.

## Ide langkah berikutnya

1. Masukkan data produk resmi melalui dashboard admin
2. Tambahkan foto asli CALMATS melalui dashboard admin
3. Siapkan server PostgreSQL untuk deployment
4. Tambahkan pembayaran dan perhitungan ongkir otomatis jika dibutuhkan
5. Tambahkan riwayat dan status pesanan pelanggan

## Cara kerja sama lewat Git

Sebelum mulai kerja:
```bash
git pull
```

Setelah selesai mengubah sesuatu:
```bash
git add .
git commit -m "Jelaskan perubahan kamu"
git push
```

Saran: kerjakan fitur baru di branch sendiri supaya tidak bentrok.
```bash
git checkout -b fitur-halaman-shop
git push -u origin fitur-halaman-shop
```
Lalu buka Pull Request di GitHub untuk digabungkan ke `main`.

## Masalah umum

| Masalah | Solusi |
|---|---|
| Error "Vite manifest not found" | `npm run dev` belum dijalankan |
| Tampilan tanpa style | Pastikan `npm install` sudah selesai dan `npm run dev` berjalan |
| Error `Unable to locate file in Vite manifest` atau `alpinejs` tidak ditemukan | Jalankan `npm install alpinejs` |
| Halaman error soal database atau session | Jalankan `php artisan migrate` |
| Logo atau foto tidak muncul | Cek nama file dan folder di `public/images/calmats/` (huruf besar-kecil berpengaruh) |
