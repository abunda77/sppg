# Role dan Hak Akses

[README](README.md) | [Planning](PLANNING.md) | [Fitur](Featured.md) | [Role](Role.md) | [Teknologi & Library](Technology_Library.md)

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

8. **Koordinator Distribusi**
   - Menyusun jadwal dan rute pengiriman.
   - Menugaskan kendaraan, sopir, dan tenaga pengiriman.
   - Memantau perjalanan dan memverifikasi penyelesaian pengiriman.

9. **Sopir / Tenaga Pengiriman**
   - Melihat tugas dan manifest pengiriman sendiri.
   - Memperbarui status perjalanan dan check-in lokasi.
   - Mengunggah bukti serah terima dan mencatat kendala pengiriman.

10. **Manajemen**
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
