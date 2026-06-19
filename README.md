# Sistem Informasi Dapur Sehat

[README](README.md) | [Planning](PLANNING.md) | [Fitur](Featured.md) | [Role](Role.md) | [Teknologi & Library](Technology_Library.md)

## 1. Ringkasan Project

**Nama sementara aplikasi:** Sistem Informasi Dapur Sehat  
**Kode internal:** SIDS  
**Jenis aplikasi:** Web application responsive untuk pengelolaan dapur sehat / dapur produksi makanan bergizi.  
**Target pengguna:** Admin pusat, pengelola dapur/SPPG, ahli gizi, bagian purchasing, gudang, keuangan, HR/personil, PIC distribusi, dan manajemen.

Aplikasi ini dibangun sebagai platform operasional dapur terpadu yang mengelola bahan pangan, menu bergizi, analisis gizi, jadwal kerja, keuangan, pembelian, inventory, personil, data penerima, dokumen kerja sama, dan kontak darurat.

Stack utama:

- Laravel 13
- Livewire 4
- Tailwind CSS 4
- Alpine.js
- MySQL / MariaDB atau PostgreSQL
- Redis untuk queue, cache, dan session
- Laravel Reverb untuk realtime notification bila diperlukan
- Laravel Horizon untuk monitoring queue
- Laravel Pulse untuk observability aplikasi
- Laravel Telescope untuk debugging development

> Catatan nakal tapi penting: jangan langsung membangun semua fitur seperti memborong isi pasar. MVP harus tajam dulu: bahan pangan, menu bergizi, analisis gizi, purchase, inventory, dan laporan dasar. Fitur lain naik bertahap.

---

---

## 2. Tujuan Aplikasi

Tujuan utama aplikasi adalah menyediakan sistem digital terpadu untuk:

1. Mengelola database bahan pangan berdasarkan data komposisi gizi.
2. Membuat dan menghitung menu bergizi berdasarkan gramasi bahan.
3. Menyusun jadwal kerja, periode produksi, PIC, dan menu harian.
4. Mengelola daftar kebutuhan bahan dari menu yang sudah direncanakan.
5. Membuat purchase order ke supplier.
6. Mengelola inventory bahan pangan, aset, dan barang operasional.
7. Mencatat data personil, jabatan, shift, dan presensi.
8. Mengelola data penerima manfaat, lokasi, jumlah porsi, dan dokumen kerja sama.
9. Menjadwalkan dan memantau distribusi makanan menggunakan kendaraan dan personil yang tersedia.
10. Mencatat transaksi keuangan operasional.
11. Menyediakan kontak darurat dan informasi penting.
12. Menyediakan dashboard manajemen untuk monitoring operasional dapur.

---

---

## 3. Prinsip Pengembangan

### 3.1 Prinsip Produk

- **Operational first**: fitur harus membantu pekerjaan dapur harian, bukan hanya terlihat cantik.
- **Data-driven kitchen**: menu, kebutuhan bahan, PO, inventory, dan biaya harus saling terhubung.
- **Mobile-friendly**: banyak user lapangan akan memakai ponsel.
- **Audit-ready**: setiap perubahan penting harus punya riwayat.
- **Role-based access**: tidak semua user boleh melihat atau mengubah semua data.
- **Modular**: fitur dibuat dalam modul agar mudah dikembangkan bertahap.
- **Offline-aware**: beberapa input lapangan idealnya bisa disiapkan untuk mode koneksi tidak stabil pada fase berikutnya.

### 3.2 Prinsip Teknis

- Gunakan Laravel service layer untuk proses bisnis penting.
- Gunakan Livewire untuk UI interaktif tanpa SPA kompleks.
- Gunakan Form Request / Livewire validation untuk validasi input.
- Gunakan Policy dan Permission untuk otorisasi.
- Gunakan queue untuk proses berat seperti generate PDF, rekap, export, dan notifikasi.
- Gunakan database transaction pada proses purchase, inventory movement, dan jurnal keuangan.
- Gunakan migration, seeder, dan factory sejak awal.
- Gunakan test minimal untuk proses kritis: hitung gizi, stok, PO, dan jurnal.

---

---

## 17. Deliverables

## 17.1 Deliverable Teknis

- Source code Laravel.
- Database migration.
- Seeder master data.
- Dokumentasi setup.
- Dokumentasi deployment.
- Test dasar.
- File `.env.example`.
- Backup strategy.
- User manual singkat.

## 17.2 Deliverable Fitur MVP

- Dashboard.
- Auth dan role.
- Bahan pangan.
- Menu bergizi.
- Analisis gizi.
- Jadwal menu.
- Penerima.
- Kebutuhan bahan.
- Supplier.
- Purchase order.
- Inventory bahan.
- Distribusi dasar.
- Laporan dasar.

---

---

## 23. Kesimpulan

Aplikasi Sistem Informasi Dapur Sehat harus dikembangkan sebagai sistem operasional dapur yang kuat, bukan sekadar katalog menu. Inti sistem adalah hubungan antar-data:

```text
Bahan Pangan
→ Menu Bergizi
→ Analisis Gizi
→ Jadwal Menu
→ Kebutuhan Bahan
→ Purchase Order
→ Inventory
→ Distribusi
→ Keuangan
→ Dashboard Manajemen
```

Selama rantai data ini rapi, aplikasi akan punya nilai nyata. Kalau rantai ini putus, aplikasi cuma jadi album foto makanan dengan kalkulator yang sok sibuk.
