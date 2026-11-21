# Analisis Kesesuaian Requirements CALMATS

Tanggal audit: 21 November 2026.

Dokumen pembanding:

- `CALMATS_Dokumen_Hasil_Interview_dan_Kebutuhan_Sistem (1).docx`
- `CALMATS_Development_Guide.md`

Dokumen tersebut diperlakukan sebagai spesifikasi produk. Implementasi dan status di bawah diverifikasi terhadap source code dan automated test di repository.

## Ringkasan

Fondasi MVP sudah sesuai: landing page, katalog, detail produk, akun pelanggan, cart guest/server, WhatsApp checkout, About, Community, moderasi, dan dashboard admin sudah tersedia. Audit ini juga menyambungkan beberapa alur yang sebelumnya hanya tampil di UI atau memakai sumber data berbeda.

## Sudah tersedia dan terhubung

| Area | Status | Catatan |
|---|---|---|
| Landing, About, navbar, footer | Selesai | Responsif dan memakai layout bersama. |
| Registrasi, login, logout, reset password, profil | Selesai | Password di-hash oleh Laravel. |
| Admin authentication dan proteksi | Selesai | Pelanggan biasa ditolak dari panel admin. |
| Draft / Published | Selesai | Draft tidak tampil pada katalog, detail, featured collection, atau API. Waktu publikasi disimpan. |
| Katalog Published | Selesai | Search nama, kategori, availability, sorting, dan Load More dapat digabungkan. |
| Search katalog | Selesai | Normalisasi spasi, partial-name search, debounce, seluruh hasil tampil saat pencarian aktif, dan sorting dipaksa ke terbaru. |
| Availability | Selesai | Filter Ready Stock, Pre-order, dan Sold Out tersedia. |
| Foto per warna | Selesai | Admin dapat mengunggah foto per warna; foto detail dan cart mengikuti warna. |
| Validasi publikasi | Selesai | Produk tidak dapat dipublikasikan dari admin jika warna, foto warna, atau varian wajib belum lengkap. |
| Pilihan varian | Selesai | Warna/ukuran tidak dipilih otomatis; perubahan warna mereset ukuran; ukuran tanpa stok dinonaktifkan. |
| Kontrol jumlah detail | Selesai | Jumlah dibatasi stok dan maksimal 20. |
| Validasi add-to-cart | Selesai | Harga, publikasi, dan stok diperiksa ulang ke server sebelum item masuk cart. |
| Guest cart | Selesai | Disimpan pada local storage khusus guest. |
| Account cart | Selesai | Disimpan ke database dan dipisahkan per akun pada browser. |
| Merge setelah login | Selesai | Guest cart dijumlahkan sekali, dibatasi stok, disinkronkan, lalu sumber guest dihapus agar idempotent. |
| Validasi checkout | Selesai | Server memeriksa publikasi, harga, stok, dan jumlah sebelum membuka WhatsApp. Perubahan meminta pemeriksaan ulang. |
| WhatsApp fallback | Selesai | Copy message dan tombol mencoba membuka WhatsApp lagi tersedia. |
| Pengaturan WhatsApp admin | Selesai | Nomor internasional divalidasi dan dipakai bersama oleh checkout dan footer. |
| Community | Selesai | Upload login-only, Pending, Approved/Rejected, dan public feed Approved-only. |
| Automated test | Selesai | Mencakup auth, akses admin, katalog, cart, checkout validation, Community, dan settings. |

## Belum dibuat atau masih parsial

### Prioritas tinggi

1. **Harga per varian**
   Harga saat ini masih pada tingkat produk, bukan kombinasi warna/ukuran. Perlu memindahkan data varian dari JSON sederhana ke entitas/tabel varian agar harga final terendah dapat dihitung dengan benar.

2. **Promosi artikel dan varian**
   Implementasi saat ini hanya `price` dan `compare_at_price` pada produk. Belum ada jenis diskon persentase/nominal, target varian, aktivasi manual terpisah, dan aturan prioritas promosi artikel.

3. **Galeri multi-foto**
   Saat ini tersedia satu foto utama dan satu foto per warna. Requirements meminta beberapa foto artikel, beberapa foto warna, serta urutan galeri.

4. **Penyesuaian stok berbasis delta dan konfirmasi**
   Admin saat ini mengisi nilai stok akhir langsung. Belum ada alur tambah/kurang dengan angka positif, ringkasan konfirmasi, alasan opsional, dan validasi ulang bila data berubah sebelum konfirmasi.

5. **CRUD kategori terpisah**
   Kategori masih berupa pilihan tetap pada form produk. Belum ada entitas dan menu admin kategori yang aman terhadap rename/delete kategori yang sedang dipakai.

### Prioritas menengah

6. **Notifikasi penyesuaian saat merge login**
   Jumlah sudah dibatasi dan merge sudah idempotent, tetapi belum ada banner khusus yang merinci item mana yang disesuaikan saat login.

7. **Refresh data ketika halaman kembali aktif**
   Add-to-cart dan checkout sudah divalidasi server, tetapi katalog belum otomatis refresh ketika tab kembali aktif.

8. **Optimasi gambar otomatis**
   Validasi tipe/ukuran tersedia, tetapi belum ada konversi WebP/AVIF, resize server-side, atau thumbnail variants.

9. **Dokumentasi deployment dan CI**
   README tersedia, tetapi belum ada pipeline CI, checklist UAT formal, backup, monitoring, dan deployment guide khusus production.

10. **Pengujian browser nyata**
    Automated HTTP test dan build tersedia. Pengujian keyboard, browser mobile, resize, popup blocker, dan UAT stakeholder masih perlu dilakukan manual/E2E.

## Sengaja tidak dibuat karena di luar MVP

- Payment gateway.
- Konfirmasi pesanan otomatis.
- Order management dan pelacakan lengkap.
- Riwayat pesanan pelanggan.
- Wishlist.
- Alamat tersimpan.
- Multi-role admin kompleks.
- Filter harga/warna/ukuran di katalog.
- Penjadwalan promosi/publikasi.
- Pilihan bahasa.
- Fuzzy search.

## Catatan arsitektur

Produk, warna, ukuran, stok, dan foto warna saat ini disimpan pada model produk dengan kolom JSON. Struktur ini cukup untuk katalog sederhana, tetapi harga/promo per varian, galeri multi-foto, dan stock adjustment yang aman sebaiknya memakai tabel `categories`, `product_variants`, `product_images`, dan `promotions`. Perubahan itu perlu migrasi data dan sebaiknya dikerjakan sebagai fase tersendiri agar data produk yang sudah ada tidak rusak.
