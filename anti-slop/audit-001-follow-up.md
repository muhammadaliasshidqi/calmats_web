# Tindak lanjut audit antislop CALMATS

Pekerjaan yang diminta: seluruh temuan prioritas HIGH dalam `audit-001-2026-11-06.md`, serta pemisahan navbar dan footer.

## Perubahan

- Form ringkasan cart kini menampilkan label untuk nama, nomor WhatsApp, alamat, dan catatan. Placeholder serta border input pada permukaan gelap dibuat lebih terang.
- Tombol menu mobile kini mengumumkan label dinamis Buka/Tutup, status `aria-expanded`, dan relasi panel melalui `aria-controls`.
- Dropdown sorting kini memiliki relasi trigger/listbox dan navigasi panah atas/bawah yang dibatasi pada opsi pertama dan terakhir. Escape tetap menutup dropdown dan mengembalikan fokus ke trigger.
- Komponen gambar kini menetapkan dimensi. Hero memakai prioritas fetch tinggi tanpa lazy loading; gambar lainnya tetap lazy.
- Teks berkontras rendah pada panel cart dan input caption komunitas dibuat lebih terang.
- Navbar dan footer sudah berupa komponen Blade terpisah (`components/navbar.blade.php` dan `components/footer.blade.php`), dipanggil secara mandiri oleh layout. Tidak diperlukan ekstraksi ulang; struktur tersebut dipertahankan.

## Verifikasi

- Pemeriksaan source memastikan atribut label menu, label form, `aria-controls`, penanganan panah dropdown, dimensi gambar, dan prioritas hero hadir.
- Kontras belum diukur secara otomatis, dan interaksi belum dijalankan lewat browser atau keyboard. Build serta click-through belum dilakukan.
- Tidak menambahkan atau menjalankan test.
