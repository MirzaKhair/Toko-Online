# AGENTS.md

## Language Rules

Semua output dari AI Agent wajib menggunakan bahasa Indonesia.

Aturan bahasa:

- Gunakan bahasa Indonesia untuk semua penjelasan.
- Gunakan bahasa Indonesia untuk laporan perubahan file.
- Gunakan bahasa Indonesia untuk pesan error dan hasil testing jika memungkinkan.
- Gunakan bahasa Indonesia untuk komentar kode jika komentar memang diperlukan.
- Gunakan bahasa Indonesia untuk nama fitur, penjelasan UI, dan dokumentasi.
- Jangan memberikan jawaban dalam bahasa Inggris kecuali:
  - Nama fungsi atau sintaks bawaan framework.
  - Nama package, library, command, atau istilah teknis yang memang menggunakan bahasa Inggris.
  - Pengguna secara khusus meminta bahasa Inggris.

Jika terdapat istilah teknis bahasa Inggris yang tidak bisa diterjemahkan dengan baik, gunakan istilah aslinya dan berikan penjelasan singkat dalam bahasa Indonesia.

## Project Overview

Project ini adalah website toko online sederhana untuk satu toko atau satu pemilik toko.

Website dapat digunakan untuk menjual berbagai jenis produk, seperti:

- Makanan
- Minuman
- Pakaian
- Elektronik
- Aksesoris
- Produk umum lainnya

Project ini bukan marketplace dan tidak menggunakan sistem multi-vendor.

## Technology Stack

Gunakan teknologi berikut:

- Laravel
- PHP
- MySQL
- Blade
- Tailwind CSS
- Vite
- JavaScript
- Laravel Session untuk keranjang guest

Jangan menambahkan React atau Vue kecuali diminta secara khusus.

## User Roles

### Customer

Customer tidak perlu login atau register.

Customer dapat:

- Melihat homepage
- Melihat daftar produk
- Melihat kategori
- Mencari produk
- Melihat detail produk
- Memilih varian produk
- Menambahkan produk ke keranjang
- Mengubah jumlah produk
- Menghapus produk dari keranjang
- Checkout sebagai guest
- Mengisi nama, nomor telepon, alamat, dan catatan
- Memilih metode pembayaran Cash atau QRIS
- Melihat nomor pesanan
- Melacak pesanan menggunakan nomor pesanan dan nomor telepon

### Admin

Admin wajib login.

Admin dapat:

- Melihat dashboard
- Mengelola kategori
- Mengelola produk
- Mengelola varian produk
- Mengelola stok
- Mengaktifkan atau menonaktifkan produk
- Melihat pesanan
- Mengatur ongkir manual
- Memverifikasi pembayaran QRIS
- Mengubah status pesanan
- Melihat laporan penjualan
- Mengatur identitas toko

## Order Status

Gunakan status pesanan berikut:

1. pending
2. confirmed
3. processing
4. shipped
5. completed
6. cancelled

Alur normal:

pending → confirmed → processing → shipped → completed

## Payment Status

Gunakan status pembayaran berikut:

1. unpaid
2. waiting_verification
3. paid
4. rejected
5. cancelled

## Payment Methods

Metode pembayaran V1:

- cash
- qris

QRIS masih menggunakan sistem manual.

Customer dapat mengunggah bukti pembayaran QRIS.
Admin harus memeriksa bukti pembayaran sebelum mengubah status menjadi paid.

Jangan menganggap pembayaran berhasil hanya karena customer menekan tombol "Saya Sudah Membayar".

## Shipping

Ongkir menggunakan sistem manual oleh admin.

Saat customer membuat pesanan:

- shipping_cost dapat bernilai 0
- Admin menentukan ongkir setelah pesanan masuk
- Total akhir dihitung dari subtotal produk ditambah ongkir

Rumus:

subtotal + shipping_cost = total_amount

## Product Rules

Produk dapat memiliki:

- Nama
- Kategori
- Deskripsi
- Foto
- Harga
- Stok
- Status aktif/nonaktif
- Varian atau tanpa varian

Contoh varian:

- Warna
- Ukuran
- Rasa
- Level pedas

Setiap kombinasi varian dapat memiliki:

- SKU
- Harga sendiri
- Stok sendiri
- Foto opsional
- Status aktif/nonaktif

Stok tidak boleh menjadi negatif.

## Database Tables

Rancangan tabel utama:

- users
- categories
- products
- product_options
- product_option_values
- product_variants
- orders
- order_items
- order_status_histories
- payment_proofs
- store_settings

## Database Rules

### orders

Simpan data:

- order_number
- customer_name
- customer_phone
- customer_address
- customer_note
- subtotal
- shipping_cost
- total_amount
- payment_method
- payment_status
- order_status
- admin_note

### order_items

Simpan snapshot data produk saat transaksi:

- product_name
- variant_name
- price
- quantity
- subtotal

Jangan hanya bergantung pada data produk saat ini karena harga dan nama produk dapat berubah setelah transaksi.

### order_status_histories

Setiap perubahan status pesanan harus dicatat.

Simpan:

- order_id
- status
- note
- changed_by

### payment_proofs

Digunakan untuk bukti pembayaran QRIS.

Simpan:

- order_id
- file_path
- status
- verified_at
- verified_by
- admin_note

### store_settings

Digunakan untuk menyimpan:

- Nama toko
- Logo
- Deskripsi
- Alamat
- Nomor WhatsApp
- Email
- Foto QRIS
- Ongkir default jika diperlukan

Website hanya menggunakan satu toko.

## Coding Rules

- Jangan menghapus file penting tanpa izin.
- Jangan mengubah konfigurasi besar tanpa menjelaskan alasannya.
- Jangan mengganti teknologi utama tanpa izin.
- Jangan membuat fitur yang belum diminta.
- Jangan membuat data dummy yang terlihat seperti data asli tanpa penjelasan.
- Gunakan Laravel best practice.
- Gunakan migration, model, controller, request validation, dan route yang rapi.
- Gunakan Eloquent Relationship.
- Gunakan Form Request untuk validasi yang kompleks.
- Gunakan transaksi database saat membuat pesanan.
- Validasi stok sebelum checkout.
- Pastikan stok tidak berkurang dua kali.
- Gunakan authorization untuk fitur admin.
- Jangan menyimpan password dalam bentuk plain text.
- Jangan menaruh password atau API key di dalam source code.
- Gunakan file `.env` untuk konfigurasi rahasia.

## File Organization

Gunakan struktur view berikut:

resources/views/
├── customer/
│   ├── home.blade.php
│   ├── products/
│   ├── cart/
│   ├── checkout/
│   └── tracking/
└── admin/
    ├── dashboard.blade.php
    ├── products/
    ├── categories/
    ├── orders/
    ├── reports/
    └── settings/

Gunakan folder berikut jika diperlukan:

- public/images
- public/js
- resources/css
- resources/js

## UI/UX Direction

Gunakan desain:

- Modern
- Minimalis
- Responsive
- Mobile-first
- Bersih dan mudah digunakan

Warna utama:

- Putih
- Biru sebagai warna aksen

Customer dan admin harus memiliki layout yang berbeda.

## Development Workflow

Sebelum mengubah file:

1. Baca struktur project yang sudah ada.
2. Periksa file terkait.
3. Jelaskan rencana perubahan.
4. Ubah hanya file yang diperlukan.
5. Jalankan pengecekan atau testing.
6. Laporkan file yang diubah.
7. Laporkan perintah yang dijalankan.
8. Jelaskan jika ada error.

Jangan langsung membuat seluruh fitur dalam satu langkah besar.

Kerjakan fitur secara bertahap dan tunggu instruksi berikutnya.

## Current Project Status

Saat ini:

- Project Laravel sudah dibuat.
- Database MySQL sudah dikonfigurasi.
- Migration bawaan Laravel sudah berhasil dijalankan.
- Fitur toko belum mulai dibuat.
- Tahap berikutnya adalah menyiapkan struktur dasar project dan autentikasi admin.