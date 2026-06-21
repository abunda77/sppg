<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(JabatanSeeder::class);
        $this->call(DivisiSeeder::class);
        $this->call(BahanPanganSeeder::class);
        $this->call(MenuBergiziSeeder::class);

        if (! User::where('email', 'erieputranto@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'ERIE PUTRANTO',
                'email' => 'erieputranto@gmail.com',
            ]);
        }

        $roles = ['admin_unit', 'ahli_gizi', 'purchasing', 'gudang', 'keuangan', 'hr', 'koordinator_distribusi', 'sopir', 'manajemen'];

        foreach ($roles as $role) {
            $email = $role.'@example.com';

            if (! User::where('email', $email)->exists()) {
                User::factory()
                    ->create([
                        'name' => str($role)->replace('_', ' ')->title()->toString(),
                        'email' => $email,
                    ])
                    ->assignRole($role);
            }
        }
    }
}
