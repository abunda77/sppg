# Perencanaan Sistem Informasi Dapur Sehat

[README](README.md) | [Planning](PLANNING.md) | [Fitur](Featured.md) | [Role](Role.md) | [Teknologi & Library](Technology_Library.md)

Dokumen ini menjadi indeks dan roadmap utama. Detail proyek dipisahkan agar setiap dokumen lebih ringan dan mudah dipelihara:

- [README.md](README.md): ringkasan proyek, tujuan, prinsip, deliverables, dan kesimpulan.
- [Featured.md](Featured.md): modul fitur, UI/UX, aturan bisnis, pengujian, risiko, dan pengembangan lanjutan.
- [Role.md](Role.md): autentikasi, role, permission, dan cakupan akses pengguna.
- [Technology_Library.md](Technology_Library.md): arsitektur, database, library, setup, keamanan, deployment, Git, dan coding standard.

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

## Fase 4 — Purchase, Inventory, dan Distribusi

Durasi: 12–16 hari

Output:

- Modul supplier.
- Modul PO.
- Approval PO.
- Penerimaan barang.
- Inventory bahan.
- Inventory aset.
- QR code aset.
- Modul distribusi.
- Penjadwalan dan manifest pengiriman.
- Tracking status perjalanan dan bukti serah terima.

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
- [ ] CRUD dan penjadwalan perjalanan distribusi.
- [ ] Penugasan kendaraan, sopir, dan tenaga pengiriman.
- [ ] Manifest porsi per kendaraan.
- [ ] Status perjalanan, timestamp, dan check-in lokasi.
- [ ] Bukti serah terima dan pencatatan kendala.

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
- [ ] Widget distribusi aktif, terlambat, dan bermasalah.
- [ ] Widget keuangan.
- [ ] Laporan PDF.
- [ ] Laporan Excel.
- [ ] Emergency contact.
- [ ] Security review.
- [ ] UAT.

---

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
13. Distribusi dasar: jadwal, manifest, status perjalanan, check-in, dan bukti serah terima.
14. Dashboard ringkas.

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
11. GPS tracking realtime dan optimasi rute otomatis.

---
