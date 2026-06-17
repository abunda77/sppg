# PLANNING.md — Sistem Informasi Dapur Sehat

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
9. Mencatat transaksi keuangan operasional.
10. Menyediakan kontak darurat dan informasi penting.
11. Menyediakan dashboard manajemen untuk monitoring operasional dapur.

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

## 4. Modul Utama Aplikasi

## 4.1 Modul Auth, Role, dan Multi-Unit

### Tujuan
Mengatur akses pengguna berdasarkan peran dan unit dapur/SPPG.

### Role Awal

1. **Super Admin**
   - Mengelola seluruh unit.
   - Mengatur master data global.
   - Mengatur user dan role.

2. **Admin Unit**
   - Mengelola data dapur/unit sendiri.
   - Melihat dashboard unit.
   - Mengelola user internal unit.

3. **Ahli Gizi**
   - Mengelola bahan pangan.
   - Membuat menu.
   - Melakukan analisis gizi.

4. **Purchasing**
   - Melihat kebutuhan bahan.
   - Membuat purchase order.
   - Mengelola supplier.

5. **Gudang / Inventory**
   - Menerima barang.
   - Mengelola stok masuk, stok keluar, koreksi stok.
   - Mengelola aset dan QR code.

6. **Keuangan**
   - Mencatat transaksi.
   - Melihat jurnal, buku besar, neraca sederhana.
   - Mengelola kategori akun.

7. **HR / Personil**
   - Mengelola data personil.
   - Mengelola jadwal, shift, dan presensi.

8. **Manajemen**
   - Melihat dashboard, laporan, dan rekap.
   - Tidak harus punya akses edit.

### Fitur

- Login/logout.
- Reset password.
- Manajemen user.
- Role dan permission.
- Unit dapur/SPPG.
- Aktivasi/nonaktivasi user.
- Audit log login dan perubahan data penting.

### Library

- `laravel/breeze` atau starter kit resmi Laravel + Livewire.
- `spatie/laravel-permission`
- `spatie/laravel-activitylog`

---

## 4.2 Modul Bahan Pangan

### Tujuan
Menyimpan database bahan pangan beserta kandungan gizi per 100 gram bagian dapat dimakan.

### Data Utama

- Kode bahan pangan.
- Nama bahan.
- Kategori bahan.
- Foto bahan.
- Satuan default.
- Energi / kalori.
- Protein.
- Lemak.
- Karbohidrat.
- Serat.
- Kalsium.
- Zat besi.
- Natrium.
- Vitamin A, B, C, dan komponen lain.
- Sumber data.
- Status aktif.
- Catatan alergi atau risiko.

### Fitur

- CRUD bahan pangan.
- Import bahan pangan dari Excel/CSV.
- Kategori bahan pangan.
- Pencarian cepat.
- Filter kategori.
- Detail gizi bahan.
- Riwayat perubahan komposisi.
- Upload foto bahan.
- Penandaan bahan lokal, bahan utama, bahan tambahan, dan bahan olahan.

### Validasi

- Nama bahan wajib.
- Nilai gizi tidak boleh negatif.
- Satuan wajib.
- Sumber data wajib untuk bahan referensi.

### Library

- `maatwebsite/excel`
- `spatie/laravel-medialibrary`
- `livewire/livewire`
- `rappasoft/laravel-livewire-tables` atau komponen table custom Livewire
- `barryvdh/laravel-dompdf` untuk cetak master data bila dibutuhkan

---

## 4.3 Modul Bahan Olahan / Resep Dasar

### Tujuan
Mencatat bahan olahan atau resep antara, misalnya ayam goreng tepung, tempe goreng, capcay, nasi matang, atau lauk siap saji.

### Data Utama

- Nama bahan olahan.
- Komposisi bahan dasar.
- Berat input.
- Berat hasil akhir.
- Faktor penyusutan.
- Kandungan gizi hasil olahan.
- Biaya bahan.
- Instruksi pengolahan.
- Foto hasil olahan.

### Fitur

- Membuat resep dasar dari beberapa bahan pangan.
- Menghitung kandungan gizi otomatis berdasarkan gramasi.
- Menghitung faktor penyusutan.
- Menentukan biaya per gram/porsi.
- Menyimpan hasil olahan sebagai bahan yang dapat dipakai di menu.
- Duplikasi resep.

### Rumus Dasar

```text
Gizi bahan dalam resep = (gramasi bahan / 100) x nilai gizi per 100 gram
Total gizi resep = jumlah seluruh gizi bahan
Gizi per gram hasil olahan = total gizi resep / berat hasil akhir
```

---

## 4.4 Modul Menu Bergizi

### Tujuan
Membuat menu makanan bergizi lengkap dengan foto, komposisi, ukuran porsi, dan kandungan gizi.

### Data Utama

- Nama menu.
- Kategori menu.
- Foto menu.
- Daftar bahan/menu komponen.
- Porsi kecil.
- Porsi besar.
- Target kalori.
- Kandungan gizi total.
- Catatan penyajian.
- Status publish.
- Versi menu.

### Fitur

- CRUD menu.
- Upload foto menu.
- Komposisi bahan berdasarkan gramasi.
- Hitung otomatis kalori, protein, lemak, karbohidrat, dan komponen lain.
- Target kalori per porsi.
- Penyesuaian gramasi otomatis/manual.
- Publish/unpublish menu.
- Galeri menu.
- Duplikasi menu.
- Riwayat revisi menu.
- Label menu: sarapan, makan siang, makan malam, snack, menu khusus.

### Library

- `spatie/laravel-medialibrary`
- `livewire/livewire`
- `alpinejs`
- `intervention/image-laravel`

---

## 4.5 Modul Analisis Gizi

### Tujuan
Memberikan perhitungan cepat kandungan gizi berdasarkan gramasi bahan dan target kalori.

### Fitur

- Kalkulator gizi per bahan.
- Kalkulator gizi menu.
- Target kalori per porsi.
- Perbandingan target vs realisasi.
- Penyesuaian gramasi bahan.
- Highlight bila kalori kurang/lebih.
- Simulasi perubahan bahan.
- Export hasil analisis ke PDF.
- Catatan ahli gizi.

### Output Analisis

- Total kalori.
- Protein.
- Lemak.
- Karbohidrat.
- Serat.
- Natrium.
- Kalsium.
- Zat besi.
- Vitamin.
- Persentase terhadap target.
- Rekomendasi penyesuaian gramasi.

### Catatan Teknis

Gunakan service khusus:

```text
App\Services\Nutrition\NutritionCalculatorService
App\Services\Nutrition\MenuAdjustmentService
```

Service ini wajib punya unit test, karena kalau hitungan gizi meleset, aplikasi berubah jadi kalkulator diet yang sedang bad mood.

---

## 4.6 Modul Jadwal Kerja dan Periode Produksi

### Tujuan
Mengelola periode produksi, menu harian, shift kerja, dan PIC personil.

### Data Utama

- Periode kerja.
- Tanggal mulai dan akhir.
- Durasi periode.
- Hari ke-n.
- Menu harian.
- Shift.
- Pos kerja.
- PIC/personil.
- Status jadwal.

### Fitur

- Membuat periode kerja.
- Menentukan menu per hari.
- Menentukan PIC per bagian.
- Menentukan shift kerja.
- Kalender jadwal.
- Progress periode.
- Perubahan jadwal.
- Riwayat perubahan jadwal.
- Rekap personil per hari.

### Pos Kerja Awal

- Persiapan.
- Pengolahan I.
- Pengolahan II.
- Pemorsian.
- Packing.
- Distribusi.
- Kebersihan.
- Maintenance.
- Admin.

### Library

- `livewire/livewire`
- `wire-elements/modal`
- `nesbot/carbon`
- `spatie/laravel-activitylog`

---

## 4.7 Modul Purchase dan Daftar Kebutuhan

### Tujuan
Menghasilkan daftar kebutuhan bahan dari rencana menu dan membuat purchase order ke supplier.

### Alur

1. Menu harian ditentukan.
2. Jumlah porsi ditentukan berdasarkan data penerima.
3. Sistem menghitung kebutuhan bahan.
4. Sistem mengelompokkan kebutuhan berdasarkan bahan dan supplier.
5. Purchasing membuat PO.
6. Supplier menerima PO dalam format PDF/print/share.
7. Barang diterima gudang.
8. Stok inventory bertambah.

### Data Utama

- Rencana kebutuhan bahan.
- Tanggal kebutuhan.
- Menu terkait.
- Jumlah porsi.
- Gramasi per porsi.
- Total kebutuhan.
- Supplier.
- Harga satuan.
- Pajak.
- Ongkir.
- Total PO.
- Status PO.

### Status PO

- Draft.
- Menunggu persetujuan.
- Disetujui.
- Dikirim ke supplier.
- Diterima sebagian.
- Diterima lengkap.
- Dibatalkan.

### Fitur

- Generate kebutuhan bahan dari menu.
- Rekap kebutuhan per tanggal.
- Rekap kebutuhan per supplier.
- Buat PO dari kebutuhan.
- Cetak PO PDF.
- QR code PO.
- Approval PO.
- Penerimaan barang dari PO.
- Riwayat harga supplier.

### Library

- `barryvdh/laravel-dompdf`
- `simplesoftwareio/simple-qrcode`
- `spatie/laravel-activitylog`
- `maatwebsite/excel`

---

## 4.8 Modul Supplier

### Tujuan
Mengelola data pemasok bahan pangan dan barang operasional.

### Data Utama

- Nama supplier.
- Kategori supplier.
- PIC.
- Nomor telepon.
- Email.
- Alamat.
- NPWP bila ada.
- Daftar bahan yang disuplai.
- Harga terakhir.
- Catatan pembayaran.
- Status aktif.

### Fitur

- CRUD supplier.
- Riwayat harga.
- Riwayat PO.
- Rating sederhana supplier.
- Catatan kualitas pengiriman.
- Export data supplier.

---

## 4.9 Modul Inventory Bahan Pangan

### Tujuan
Mengelola stok bahan pangan masuk, keluar, koreksi, dan stok minimum.

### Data Utama

- Item bahan.
- Satuan.
- Batch.
- Tanggal masuk.
- Tanggal kedaluwarsa.
- Jumlah masuk.
- Jumlah keluar.
- Saldo stok.
- Lokasi penyimpanan.
- Harga pokok.
- Supplier.
- Dokumen penerimaan.
- Status kualitas.

### Fitur

- Barang masuk dari PO.
- Barang keluar untuk produksi.
- Koreksi stok.
- Stok opname.
- Minimum stock alert.
- Expiry alert.
- Kartu stok.
- Mutasi stok.
- Laporan stok per tanggal.
- Export stok.

### Validasi Kritis

- Stok tidak boleh minus kecuali role tertentu mengizinkan override.
- Barang kedaluwarsa tidak boleh dipakai produksi tanpa approval.
- Semua koreksi stok wajib memiliki alasan.

### Library

- `maatwebsite/excel`
- `spatie/laravel-activitylog`
- `laravel/horizon` untuk proses export besar

---

## 4.10 Modul Inventory Aset dan QR Code

### Tujuan
Mencatat aset operasional seperti kendaraan, freezer, kompor, AC, genset, alat masak, dan barang non-pangan lain.

### Data Utama

- Kode aset.
- Nama aset.
- Kategori aset.
- Foto aset.
- Lokasi aset.
- Tanggal pembelian.
- Harga pembelian.
- Kondisi aset.
- QR code aset.
- Jadwal maintenance.
- Riwayat maintenance.

### Fitur

- CRUD aset.
- Generate QR code aset.
- Scan QR untuk melihat detail aset.
- Cetak label QR.
- Riwayat perawatan.
- Reminder maintenance.
- Upload foto aset.

### Library

- `simplesoftwareio/simple-qrcode`
- `spatie/laravel-medialibrary`
- `barryvdh/laravel-dompdf`

---

## 4.11 Modul Personil dan Presensi

### Tujuan
Mengelola data personil, jabatan, shift, dan presensi kerja.

### Data Utama

- Nama personil.
- Foto.
- Jabatan.
- Nomor telepon.
- Email.
- Alamat.
- Unit kerja.
- Status aktif.
- Riwayat presensi.
- Riwayat jabatan.

### Fitur

- CRUD personil.
- Import data personil.
- Presensi harian.
- Rekap presensi.
- Grafik presensi.
- Riwayat kehadiran per personil.
- Export rekap presensi.
- Penempatan personil dalam jadwal kerja.

### Opsi Presensi

- Manual oleh admin.
- QR check-in.
- PIN sederhana.
- GPS validasi lokasi, untuk fase lanjut.

### Library

- `maatwebsite/excel`
- `spatie/laravel-medialibrary`
- `simplesoftwareio/simple-qrcode`
- `laravel/reverb` untuk notifikasi realtime bila perlu

---

## 4.12 Modul Penerima Manfaat

### Tujuan
Mengelola data sekolah/lembaga/penerima, lokasi, jumlah porsi, dan dokumen kerja sama.

### Data Utama

- Nama penerima.
- Jenis penerima.
- Alamat.
- Koordinat lokasi.
- Kontak PIC.
- Jumlah siswa/penerima.
- Jumlah porsi kecil.
- Jumlah porsi besar.
- Jadwal distribusi.
- Dokumen MOU.
- Status kerja sama.

### Fitur

- CRUD penerima.
- Peta lokasi.
- Upload MOU digital.
- Detail jumlah porsi.
- Rute distribusi sederhana.
- Rekap porsi per hari.
- Export data penerima.

### Library

- `spatie/laravel-medialibrary`
- Leaflet.js atau Google Maps API
- `barryvdh/laravel-dompdf`

---

## 4.13 Modul Keuangan

### Tujuan
Mencatat transaksi keuangan operasional secara sederhana, aman, dan terintegrasi dengan purchase/inventory.

### Data Utama

- Chart of accounts.
- Jurnal umum.
- Buku besar.
- Neraca sederhana.
- Kas masuk.
- Kas keluar.
- Biaya pangan.
- Biaya operasional.
- Biaya langganan.
- Dokumen transaksi.
- Relasi ke PO bila ada.

### Fitur

- CRUD akun.
- Jurnal umum.
- Buku besar.
- Neraca sederhana.
- Kas masuk dan keluar.
- Upload bukti transaksi.
- Relasi pembayaran dengan PO.
- Export laporan PDF/Excel.
- Lock periode laporan.
- Audit log perubahan transaksi.

### Catatan Kritis

Untuk MVP, jangan membuat sistem akuntansi terlalu “enterprise”. Fokus dulu ke pencatatan operasional, jurnal dasar, buku besar, dan neraca sederhana. Kalau dari awal dipaksa jadi ERP, development bisa berubah jadi sinetron 200 episode.

### Library

- `spatie/laravel-medialibrary`
- `maatwebsite/excel`
- `barryvdh/laravel-dompdf`
- `spatie/laravel-activitylog`

---

## 4.14 Modul Emergency Contact

### Tujuan
Menyediakan kontak darurat internal dan eksternal yang bisa diakses cepat.

### Data Utama

- Nama kontak.
- Kategori.
- Nomor telepon.
- WhatsApp.
- Alamat.
- Catatan.
- Status aktif.

### Kategori Awal

- Admin.
- Pengelola.
- Kepolisian.
- Pemadam kebakaran.
- PLN.
- Ambulans.
- Supplier kritis.
- Maintenance kendaraan.
- Maintenance listrik/gas.

### Fitur

- Daftar kontak darurat.
- Tombol telepon/WhatsApp.
- Kategori kontak.
- Pengumuman darurat.
- Log penggunaan kontak darurat, opsional.

---

## 4.15 Modul Dashboard dan Laporan

### Tujuan
Memberikan ringkasan kondisi operasional dapur secara cepat.

### Widget Dashboard

- Jumlah menu aktif.
- Jumlah bahan pangan.
- Jadwal produksi hari ini.
- Jumlah porsi hari ini.
- Kebutuhan bahan hari ini.
- PO aktif.
- Stok kritis.
- Bahan mendekati kedaluwarsa.
- Personil hadir/absen.
- Pengeluaran hari ini/bulan ini.
- Kontak darurat.

### Laporan

- Laporan menu dan gizi.
- Laporan kebutuhan bahan.
- Laporan PO.
- Laporan stok.
- Laporan aset.
- Laporan presensi.
- Laporan penerima.
- Laporan keuangan.
- Laporan distribusi.

### Library

- ApexCharts atau Chart.js
- `maatwebsite/excel`
- `barryvdh/laravel-dompdf`

---

## 5. Arsitektur Aplikasi

## 5.1 Layer Aplikasi

```text
routes/
  web.php
  auth.php

app/
  Models/
  Livewire/
  Services/
  Actions/
  DTO/
  Policies/
  Observers/
  Jobs/
  Notifications/
  Exports/
  Imports/
  Support/

resources/
  views/
  css/
  js/

database/
  migrations/
  seeders/
  factories/

tests/
  Feature/
  Unit/
```

## 5.2 Pola Komponen Livewire

Gunakan struktur modular:

```text
app/Livewire/FoodItems/
  Index.php
  Create.php
  Edit.php
  Show.php

app/Livewire/Menus/
  Index.php
  Builder.php
  Show.php
  NutritionAnalysis.php

app/Livewire/PurchaseOrders/
  Index.php
  Create.php
  Show.php
  Approval.php
```

## 5.3 Service Layer Wajib

```text
App\Services\Nutrition\NutritionCalculatorService
App\Services\Menu\MenuCostingService
App\Services\Purchase\RequirementGeneratorService
App\Services\Purchase\PurchaseOrderService
App\Services\Inventory\StockMovementService
App\Services\Finance\JournalService
App\Services\Schedule\WorkPeriodService
App\Services\QrCode\QrCodeService
```

---

## 6. Rancangan Database Awal

## 6.1 Master dan Auth

- users
- roles
- permissions
- model_has_roles
- model_has_permissions
- units
- user_units
- activity_log

## 6.2 Bahan dan Gizi

- food_categories
- food_items
- nutrient_components
- food_item_nutrients
- processed_foods
- processed_food_ingredients

## 6.3 Menu

- menus
- menu_items
- menu_portions
- menu_nutrition_summaries
- menu_versions

## 6.4 Jadwal

- work_periods
- work_days
- work_shifts
- work_positions
- work_assignments
- daily_menus

## 6.5 Purchase dan Supplier

- suppliers
- supplier_items
- material_requirements
- purchase_orders
- purchase_order_items
- goods_receipts
- goods_receipt_items

## 6.6 Inventory

- inventory_items
- inventory_batches
- stock_movements
- stock_opnames
- assets
- asset_maintenance_logs

## 6.7 Personil

- personnel
- personnel_positions
- attendances
- attendance_summaries

## 6.8 Penerima

- recipients
- recipient_contacts
- recipient_documents
- distribution_schedules

## 6.9 Keuangan

- accounts
- journal_entries
- journal_entry_lines
- cash_transactions
- financial_periods

## 6.10 Emergency

- emergency_contacts
- emergency_announcements

---

## 7. Library dan Package yang Direkomendasikan

## 7.1 Backend Laravel

```bash
composer require livewire/livewire
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
composer require spatie/laravel-medialibrary
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
composer require simplesoftwareio/simple-qrcode
composer require intervention/image-laravel
composer require predis/predis
composer require laravel/horizon
composer require laravel/pulse
composer require laravel/telescope --dev
composer require pestphp/pest --dev
```

## 7.2 Frontend

```bash
npm install tailwindcss @tailwindcss/vite
npm install alpinejs
npm install apexcharts
npm install leaflet
npm install @vitejs/plugin-vue --save-dev
```

Catatan: `@vitejs/plugin-vue` hanya dipasang bila ada kebutuhan komponen Vue kecil. Untuk stack Livewire murni, tidak wajib.

## 7.3 Opsional

```bash
composer require laravel/reverb
composer require pusher/pusher-php-server
composer require wire-elements/modal
composer require filament/support
composer require laravel/sanctum
composer require darkaonline/l5-swagger --dev
```

### Kapan dipakai?

- `laravel/reverb`: bila butuh notifikasi realtime.
- `laravel/sanctum`: bila nanti dibuat mobile app/API.
- `l5-swagger`: bila API akan didokumentasikan.
- `filament/support`: bila butuh beberapa utilitas, bukan full admin panel.
- `wire-elements/modal`: modal Livewire lebih rapi.

---

## 8. Setup Awal Project

## 8.1 Buat Project

```bash
composer create-project laravel/laravel sids
cd sids
```

## 8.2 Install Livewire

```bash
composer require livewire/livewire
```

## 8.3 Install Tailwind CSS

```bash
npm install tailwindcss @tailwindcss/vite
npm install
npm run dev
```

## 8.4 Install Package Utama

```bash
composer require spatie/laravel-permission spatie/laravel-activitylog spatie/laravel-medialibrary
composer require maatwebsite/excel barryvdh/laravel-dompdf simplesoftwareio/simple-qrcode intervention/image-laravel
```

## 8.5 Publish Config

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
```

## 8.6 Migration

```bash
php artisan migrate
```

## 8.7 Seeder Awal

Seeder yang wajib dibuat:

```text
RolePermissionSeeder
UnitSeeder
FoodCategorySeeder
NutrientComponentSeeder
AccountSeeder
EmergencyContactSeeder
WorkPositionSeeder
```

---

## 9. Roadmap Pengembangan

## Fase 0 — Persiapan Teknis

Durasi: 2–3 hari

Output:

- Repository Git.
- Setup Laravel 13.
- Setup Livewire.
- Setup Tailwind CSS.
- Setup database.
- Setup auth.
- Setup role permission.
- Setup layout dashboard.
- Setup coding standard.
- Setup deployment awal.

Checklist:

- [ ] Repository dibuat.
- [ ] `.env.example` disiapkan.
- [ ] Database development dibuat.
- [ ] Auth berjalan.
- [ ] Role permission berjalan.
- [ ] Layout dashboard tersedia.
- [ ] CI sederhana tersedia.

---

## Fase 1 — Master Data dan Fondasi

Durasi: 5–7 hari

Output:

- Modul unit dapur.
- Modul user dan role.
- Modul bahan pangan.
- Modul kategori bahan.
- Import bahan pangan.
- Komponen nilai gizi.
- Media upload foto bahan.

Checklist:

- [ ] CRUD unit.
- [ ] CRUD user.
- [ ] CRUD role.
- [ ] CRUD bahan pangan.
- [ ] Import Excel bahan pangan.
- [ ] Detail gizi bahan.
- [ ] Upload foto bahan.
- [ ] Audit log aktif.

---

## Fase 2 — Menu dan Analisis Gizi

Durasi: 7–10 hari

Output:

- Modul bahan olahan.
- Modul menu bergizi.
- Kalkulator gizi.
- Target kalori.
- Penyesuaian gramasi.
- Export menu ke PDF.

Checklist:

- [ ] CRUD bahan olahan.
- [ ] Hitung gizi bahan olahan.
- [ ] CRUD menu.
- [ ] Menu builder.
- [ ] Hitung gizi menu.
- [ ] Target kalori.
- [ ] Simulasi gramasi.
- [ ] Export PDF menu.

---

## Fase 3 — Jadwal, Penerima, dan Kebutuhan Bahan

Durasi: 7–10 hari

Output:

- Modul penerima.
- Modul jadwal kerja.
- Modul periode produksi.
- Menu harian.
- Generate daftar kebutuhan bahan.

Checklist:

- [ ] CRUD penerima.
- [ ] Upload MOU.
- [ ] Data lokasi penerima.
- [ ] CRUD periode kerja.
- [ ] Set menu harian.
- [ ] Set jumlah porsi.
- [ ] Generate kebutuhan bahan.
- [ ] Rekap kebutuhan per tanggal.

---

## Fase 4 — Purchase dan Inventory

Durasi: 10–14 hari

Output:

- Modul supplier.
- Modul PO.
- Approval PO.
- Penerimaan barang.
- Inventory bahan.
- Inventory aset.
- QR code aset.

Checklist:

- [ ] CRUD supplier.
- [ ] Generate PO dari kebutuhan.
- [ ] Cetak PO PDF.
- [ ] Approval PO.
- [ ] Penerimaan barang.
- [ ] Stock movement.
- [ ] Stok minimum.
- [ ] Expiry alert.
- [ ] CRUD aset.
- [ ] QR code aset.

---

## Fase 5 — Personil, Presensi, dan Keuangan

Durasi: 10–14 hari

Output:

- Modul personil.
- Modul presensi.
- Rekap presensi.
- Modul jurnal umum.
- Buku besar.
- Neraca sederhana.
- Upload bukti transaksi.

Checklist:

- [ ] CRUD personil.
- [ ] Jadwal personil.
- [ ] Presensi.
- [ ] Grafik presensi.
- [ ] CRUD akun.
- [ ] Jurnal umum.
- [ ] Buku besar.
- [ ] Neraca sederhana.
- [ ] Upload bukti transaksi.

---

## Fase 6 — Dashboard, Laporan, dan Emergency

Durasi: 5–7 hari

Output:

- Dashboard manajemen.
- Laporan PDF/Excel.
- Kontak darurat.
- Notification sederhana.
- Final hardening.

Checklist:

- [ ] Dashboard operasional.
- [ ] Widget stok kritis.
- [ ] Widget PO aktif.
- [ ] Widget presensi.
- [ ] Widget keuangan.
- [ ] Laporan PDF.
- [ ] Laporan Excel.
- [ ] Emergency contact.
- [ ] Security review.
- [ ] UAT.

---

## 10. Prioritas MVP

## MVP Wajib

1. Auth dan role.
2. Unit dapur.
3. Bahan pangan.
4. Komponen gizi.
5. Menu bergizi.
6. Analisis gizi.
7. Penerima.
8. Jadwal menu harian.
9. Generate kebutuhan bahan.
10. Supplier.
11. Purchase order.
12. Inventory bahan.
13. Dashboard ringkas.

## MVP Jangan Dulu

1. Mobile app native.
2. AI recommendation.
3. Route optimization kompleks.
4. Akuntansi penuh.
5. Payroll.
6. IoT kitchen monitoring.
7. Offline mode penuh.
8. Integrasi WhatsApp resmi.
9. Multi-approval kompleks.
10. Marketplace supplier.

---

## 11. Rencana UI/UX

## 11.1 Layout Umum

- Sidebar menu.
- Header dengan unit aktif dan profil user.
- Breadcrumb.
- Main content.
- Quick action button.
- Notification indicator.
- Mobile bottom navigation untuk fitur sering dipakai.

## 11.2 Menu Sidebar

1. Dashboard
2. Bahan Pangan
3. Menu Bergizi
4. Analisis Gizi
5. Jadwal Kerja
6. Kebutuhan Bahan
7. Purchase Order
8. Inventory
9. Supplier
10. Personil
11. Penerima
12. Keuangan
13. Emergency
14. Laporan
15. Pengaturan

## 11.3 Komponen UI

- Card statistik.
- Data table.
- Filter panel.
- Modal form.
- Drawer detail.
- Badge status.
- Stepper approval.
- Toast notification.
- Empty state.
- Loading skeleton.
- QR preview.
- File upload preview.

## 11.4 Style

- Clean dashboard.
- Warna utama: hijau/toska untuk nuansa sehat.
- Warna pendukung: kuning/oranye untuk dapur/pangan.
- Status:
  - Hijau: aman/sukses.
  - Kuning: perhatian.
  - Merah: kritis.
  - Abu-abu: draft/nonaktif.

---

## 12. Aturan Bisnis Penting

## 12.1 Gizi

- Nilai gizi dihitung dari gramasi aktual.
- Nilai dasar mengacu per 100 gram.
- Menu bisa punya beberapa versi.
- Menu yang sudah dipakai dalam jadwal tidak boleh dihapus permanen.
- Perubahan menu setelah dipakai harus membuat versi baru.

## 12.2 Purchase

- PO hanya bisa dibuat dari kebutuhan bahan atau manual oleh role tertentu.
- PO di atas nilai tertentu wajib approval.
- PO yang sudah diterima tidak boleh diedit sembarangan.
- Pembatalan PO wajib alasan.

## 12.3 Inventory

- Barang masuk dari PO menambah stok.
- Barang keluar untuk produksi mengurangi stok.
- Koreksi stok wajib alasan.
- Stok minus harus dicegah.
- Barang expired diberi status tidak layak.
- Stok opname mengunci catatan pada tanggal tertentu.

## 12.4 Keuangan

- Transaksi yang sudah masuk periode terkunci tidak bisa diubah.
- Edit transaksi wajib audit log.
- Bukti transaksi wajib untuk pengeluaran di atas nominal tertentu.
- Pembayaran PO dapat membuat jurnal otomatis.

## 12.5 Personil

- Presensi bisa diinput manual pada MVP.
- Perubahan presensi wajib mencatat user pengubah.
- Personil nonaktif tidak muncul dalam jadwal baru.

---

## 13. Testing Plan

## 13.1 Unit Test

- NutritionCalculatorService.
- RequirementGeneratorService.
- StockMovementService.
- JournalService.
- PurchaseOrderService.

## 13.2 Feature Test

- Login.
- Role access.
- CRUD bahan pangan.
- Import bahan pangan.
- Buat menu.
- Hitung gizi menu.
- Generate kebutuhan bahan.
- Buat PO.
- Terima barang.
- Mutasi stok.
- Buat jurnal.

## 13.3 UAT Scenario

1. Admin membuat unit dan user.
2. Ahli gizi input bahan pangan.
3. Ahli gizi membuat menu.
4. Sistem menghitung gizi menu.
5. Admin membuat data penerima.
6. Admin membuat jadwal menu.
7. Sistem generate kebutuhan bahan.
8. Purchasing membuat PO.
9. Gudang menerima barang.
10. Stok bertambah.
11. Produksi menggunakan bahan.
12. Stok berkurang.
13. Keuangan mencatat pembayaran.
14. Manajemen melihat dashboard dan laporan.

---

## 14. Security Plan

- Role-based permission.
- Policy per model penting.
- Audit log.
- CSRF protection.
- Rate limiting login.
- Password hashing default Laravel.
- Validasi file upload.
- Batas ukuran file.
- Sanitasi input.
- Backup database.
- Environment variable aman.
- Tidak menyimpan credential di repository.
- HTTPS wajib di production.
- Session secure cookie di production.
- Queue worker dipantau.
- Log error tidak menampilkan data sensitif.

---

## 15. Deployment Plan

## 15.1 Server Requirement

- PHP 8.3+
- Composer 2+
- Node.js LTS
- MySQL 8+ / MariaDB 10.6+ / PostgreSQL 15+
- Redis
- Nginx
- Supervisor
- SSL certificate
- Cron enabled

## 15.2 Production Command

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link
php artisan queue:restart
```

## 15.3 Supervisor

Queue worker:

```text
php artisan queue:work --sleep=3 --tries=3 --timeout=120
```

Scheduler:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 16. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---:|---|
| Data gizi tidak valid | Tinggi | Gunakan sumber data jelas, audit perubahan, validasi import |
| Hitung gramasi salah | Tinggi | Unit test kalkulator gizi |
| Stok tidak sinkron | Tinggi | Stock movement wajib transaction |
| PO dan stok tidak nyambung | Tinggi | Goods receipt harus terhubung ke PO |
| Scope terlalu besar | Tinggi | MVP ketat, fitur lanjut ditunda |
| User lapangan bingung | Sedang | UI sederhana, mobile friendly |
| Laporan keuangan terlalu kompleks | Sedang | Mulai dari jurnal dasar |
| Upload file membengkak | Sedang | Kompres gambar, storage policy |
| Package belum kompatibel Laravel 13 | Sedang | Lock version, cek package sebelum install |
| Server kecil | Sedang | Queue, cache, optimasi query |

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
- Laporan dasar.

---

## 18. Struktur Branch Git

```text
main
develop
feature/auth-role
feature/food-items
feature/menu-nutrition
feature/schedule
feature/requirements
feature/purchase-order
feature/inventory
feature/personnel
feature/finance
feature/dashboard-report
hotfix/*
release/*
```

---

## 19. Coding Standard

- Gunakan Laravel Pint.
- Gunakan Pest untuk testing.
- Nama class jelas dan tidak disingkat berlebihan.
- Business logic tidak ditaruh di Blade.
- Livewire component tidak boleh menjadi “keranjang sampah logic”.
- Gunakan service untuk kalkulasi.
- Gunakan policy untuk akses.
- Gunakan enum untuk status.
- Gunakan migration yang reversible.
- Gunakan soft delete untuk data penting.
- Gunakan database transaction untuk proses multi-step.

---

## 20. Contoh Enum Status

```php
enum PurchaseOrderStatus: string
{
    case Draft = 'draft';
    case WaitingApproval = 'waiting_approval';
    case Approved = 'approved';
    case Sent = 'sent';
    case PartiallyReceived = 'partially_received';
    case Received = 'received';
    case Cancelled = 'cancelled';
}
```

```php
enum StockMovementType: string
{
    case In = 'in';
    case Out = 'out';
    case Adjustment = 'adjustment';
    case Opname = 'opname';
    case Waste = 'waste';
}
```

---

## 21. Definition of Done

Sebuah fitur dianggap selesai jika:

- Migration tersedia.
- Model dan relasi benar.
- UI Livewire berjalan.
- Validasi input tersedia.
- Role permission diterapkan.
- Audit log untuk aksi penting.
- Error handling tersedia.
- Empty state tersedia.
- Loading state tersedia.
- Test minimal tersedia untuk logic kritis.
- Bisa digunakan di desktop dan mobile.
- Sudah dicoba dengan data dummy.
- Tidak ada error di log.
- Sudah direview sebelum merge.

---

## 22. Catatan Lanjutan

Fitur lanjutan yang bisa dipertimbangkan setelah MVP:

1. API mobile app.
2. Offline input untuk presensi dan distribusi.
3. Integrasi WhatsApp notification.
4. AI rekomendasi menu berdasarkan target gizi.
5. Optimasi rute distribusi.
6. Barcode scanner bahan.
7. Forecast kebutuhan bahan.
8. Supplier portal.
9. Approval bertingkat.
10. Integrasi e-signature MOU.
11. Dashboard pusat multi-unit.
12. Integrasi IoT suhu freezer/chiller.
13. Predictive expiry dan waste reduction.

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
→ Keuangan
→ Dashboard Manajemen
```

Selama rantai data ini rapi, aplikasi akan punya nilai nyata. Kalau rantai ini putus, aplikasi cuma jadi album foto makanan dengan kalkulator yang sok sibuk.
