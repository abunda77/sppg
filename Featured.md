# Fitur Sistem Informasi Dapur Sehat

[README](README.md) | [Planning](PLANNING.md) | [Fitur](Featured.md) | [Role](Role.md) | [Teknologi & Library](Technology_Library.md)

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
- Nomor polisi dan dokumen kendaraan bila aset berupa kendaraan.
- Kapasitas angkut bila aset berupa kendaraan.

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
- Kompetensi personil, termasuk status sebagai sopir atau tenaga pengiriman.
- Nomor dan masa berlaku SIM bila personil bertugas sebagai sopir.

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

## 4.13 Modul Distribusi

### Tujuan
Mengelola penjadwalan, penugasan kendaraan dan personil, manifest muatan, perjalanan, tracking, serta bukti serah terima makanan kepada penerima manfaat.

### Batas Modul

- Kendaraan tetap dikelola sebagai aset pada modul inventory aset.
- Sopir dan tenaga pengiriman tetap dikelola pada modul personil.
- Modul distribusi menghubungkan kendaraan, personil, jadwal produksi, menu harian, jumlah porsi, dan penerima manfaat dalam satu perjalanan pengiriman.

### Data Utama

- Nomor perjalanan.
- Tanggal dan jadwal keberangkatan.
- Rute dan urutan penerima.
- Kendaraan.
- Sopir.
- Tenaga pengiriman.
- Manifest menu dan jumlah porsi.
- Kapasitas dan total muatan.
- Waktu berangkat, tiba, serah terima, dan kembali.
- Status perjalanan.
- Check-point dan koordinat lokasi.
- Bukti serah terima.
- Catatan selisih, keterlambatan, dan kendala.

### Status Perjalanan

- Draft.
- Terjadwal.
- Pemuatan.
- Berangkat.
- Tiba.
- Diserahkan.
- Kembali.
- Dibatalkan.

### Fitur

- Membuat jadwal pengiriman dari jadwal menu dan data penerima.
- Menentukan rute dan urutan penerima.
- Menugaskan kendaraan, sopir, dan tenaga pengiriman.
- Validasi ketersediaan dan kapasitas kendaraan.
- Membuat manifest menu dan jumlah porsi per kendaraan.
- Memperbarui status perjalanan.
- Mencatat waktu keberangkatan, kedatangan, serah terima, dan kepulangan.
- Check-in lokasi dan tracking perjalanan berbasis koordinat.
- Upload foto, nama penerima, tanda tangan, dan catatan sebagai bukti serah terima.
- Mencatat porsi diterima, selisih porsi, keterlambatan, dan kegagalan pengiriman.
- Riwayat perjalanan kendaraan dan personil.
- Dashboard perjalanan aktif, terlambat, selesai, dan bermasalah.
- Export manifest dan laporan distribusi.

### Batas MVP

- Tracking MVP menggunakan status perjalanan, timestamp, dan check-in lokasi manual.
- GPS realtime dan pembaruan lokasi otomatis disiapkan untuk fase lanjutan.

### Library

- Leaflet.js untuk peta dan check-in lokasi.
- `spatie/laravel-medialibrary` untuk bukti serah terima.
- `barryvdh/laravel-dompdf` untuk manifest dan laporan.
- `spatie/laravel-activitylog` untuk audit perubahan perjalanan.
- `laravel/reverb` untuk pembaruan status realtime bila diperlukan.

---

## 4.14 Modul Keuangan

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

## 4.15 Modul Emergency Contact

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

## 4.16 Modul Dashboard dan Laporan

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
- Pengiriman aktif, terlambat, dan bermasalah.
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
12. Distribusi
13. Keuangan
14. Emergency
15. Laporan
16. Pengaturan

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

## 12.6 Distribusi

- Perjalanan hanya dapat dijadwalkan menggunakan kendaraan dan personil aktif.
- Kendaraan tidak boleh memiliki jadwal perjalanan yang bertabrakan.
- Sopir dan tenaga pengiriman tidak boleh memiliki penugasan yang bertabrakan.
- Total muatan tidak boleh melebihi kapasitas kendaraan tanpa override dan alasan dari Koordinator Distribusi.
- Perjalanan hanya dapat berangkat setelah manifest dan proses pemuatan dikonfirmasi.
- Setiap perubahan status perjalanan wajib mencatat waktu dan user pelaksana.
- Penyelesaian pengiriman wajib mencatat jumlah porsi diterima dan bukti serah terima.
- Selisih porsi, keterlambatan, pembatalan, dan kegagalan pengiriman wajib memiliki alasan.
- GPS realtime tidak menjadi syarat MVP; tracking MVP menggunakan status, timestamp, dan check-in lokasi.

---

---

## 13. Testing Plan

## 13.1 Unit Test

- NutritionCalculatorService.
- RequirementGeneratorService.
- StockMovementService.
- JournalService.
- PurchaseOrderService.
- DistributionScheduleService.
- TripTrackingService.

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
- Buat jadwal perjalanan distribusi.
- Cegah bentrok kendaraan dan personil.
- Perbarui status dan check-in perjalanan.
- Upload bukti serah terima.

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
13. Koordinator Distribusi membuat jadwal dan menugaskan kendaraan serta personil.
14. Sopir mengonfirmasi pemuatan dan memulai perjalanan.
15. Sopir atau tenaga pengiriman memperbarui status dan check-in lokasi.
16. Tenaga pengiriman mencatat jumlah porsi diterima dan bukti serah terima.
17. Keuangan mencatat pembayaran.
18. Manajemen melihat dashboard dan laporan.

---

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
14. GPS tracking realtime perjalanan distribusi.

---
