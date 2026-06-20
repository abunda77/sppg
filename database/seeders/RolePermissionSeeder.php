<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * @var array<string, list<string>>
     */
    private array $rolePermissions = [
        'super_admin' => ['*'],
        'admin_unit' => [
            'unit.view', 'unit.manage',
            'dashboard.view',
            'user.view', 'user.create', 'user.edit',
        ],
        'ahli_gizi' => [
            'bahan-pangan.view', 'bahan-pangan.create', 'bahan-pangan.edit', 'bahan-pangan.delete',
            'menu.view', 'menu.create', 'menu.edit', 'menu.delete',
            'analisis-gizi.view', 'analisis-gizi.create',
        ],
        'purchasing' => [
            'kebutuhan-bahan.view',
            'purchase-order.view', 'purchase-order.create', 'purchase-order.edit', 'purchase-order.delete',
            'supplier.view', 'supplier.create', 'supplier.edit', 'supplier.delete',
        ],
        'gudang' => [
            'penerimaan-barang.view', 'penerimaan-barang.create',
            'stok.view', 'stok.masuk', 'stok.keluar', 'stok.koreksi',
            'aset.view', 'aset.create', 'aset.edit', 'aset.delete',
        ],
        'keuangan' => [
            'transaksi.view', 'transaksi.create', 'transaksi.edit',
            'jurnal.view', 'buku-besar.view', 'neraca.view',
            'kategori-akun.view', 'kategori-akun.create', 'kategori-akun.edit', 'kategori-akun.delete',
        ],
        'hr' => [
            'personil.view', 'personil.create', 'personil.edit', 'personil.delete',
            'jadwal.view', 'jadwal.create', 'jadwal.edit',
            'shift.view', 'shift.create', 'shift.edit',
            'presensi.view', 'presensi.create',
        ],
        'koordinator_distribusi' => [
            'distribusi.view', 'distribusi.create', 'distribusi.edit',
            'jadwal-pengiriman.view', 'jadwal-pengiriman.create', 'jadwal-pengiriman.edit',
            'rute.view', 'rute.create', 'rute.edit',
            'kendaraan.view', 'kendaraan.assign',
            'pengiriman.verify',
        ],
        'sopir' => [
            'tugas-pengiriman.view',
            'manifest.view',
            'status-perjalanan.update',
            'bukti-serah-terima.upload',
            'kendala-pengiriman.create',
        ],
        'manajemen' => [
            'dashboard.view',
            'laporan.view',
            'rekap.view',
        ],
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = collect($this->rolePermissions)
            ->flatten()
            ->reject(fn (string $p): bool => $p === '*')
            ->unique()
            ->values();

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach ($this->rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($roleName === 'super_admin') {
                continue;
            }

            $role->syncPermissions($permissions);
        }

        $superAdmin = User::where('email', 'erieputranto@gmail.com')->first();

        if ($superAdmin && ! $superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole('super_admin');
        }
    }
}
