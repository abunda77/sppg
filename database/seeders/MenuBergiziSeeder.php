<?php

namespace Database\Seeders;

use App\Models\MenuBergizi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuBergiziSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $menuBergizi = [
            ['nama' => 'NASI KUNING', 'deskripsi' => 'Nasi Kuning, Telur Iris, Lalapan, Semur Tempe.'],
            ['nama' => 'NASI AYAM WOKU', 'deskripsi' => 'Nasi Putih, Ayam Woku, Tumis Sawi Wortel, Perkedel Tahu.'],
            ['nama' => 'NASI IKAN DORI', 'deskripsi' => 'Nasi Putih, Ikan Dori Asam manis, Lalapan, Kacang Merah.'],
            ['nama' => 'POTATO WEDGES', 'deskripsi' => 'Potato Wedges, Chicken Katsu, Wortel Mayo, Kacang Merah.'],
            ['nama' => 'NASI TAMAGO', 'deskripsi' => 'Nasi Putih, Tamago, Kimchi, Edamame.'],
            ['nama' => 'NASI KEBULI', 'deskripsi' => 'Nasi Kebuli, Ayam Goreng, Acar, Susu.'],
            ['nama' => 'PECEL LELE', 'deskripsi' => 'Nasi Putih, Lele Kremes, Lalapan, Tempe Goreng.'],
            ['nama' => 'MIE AYAM', 'deskripsi' => 'Mie Ayam, Ayam Kecap, Caisim, Sup Tahu, Buah Anggur.'],
            ['nama' => 'NASI TELUR MADURA', 'deskripsi' => 'Nasi Putih, Telur Bumbu Madura, Tumis Buncis Wortel, Tahu Balado.'],
            ['nama' => 'NASI TELUR BALI', 'deskripsi' => 'Nasi Putih, Telur Bumbu Bali, Tumis Buncis Wortel, Tempe Mendoan.'],
            ['nama' => 'NASI JAGAL', 'deskripsi' => 'Nasi Putih, Daging Jagal, Lalapan, Tahu Crispy.'],
            ['nama' => 'NASI GORENG', 'deskripsi' => 'Nasi Goreng, Telur Mata Sapi, Acar, Kacang Polong.'],
            ['nama' => 'NASI AYAM CRISPY', 'deskripsi' => 'Nasi Putih, Ayam Crispy, Pokcoy Garlic, Tahu Saos Bistik.'],
            ['nama' => 'NASI AYAM KECAP', 'deskripsi' => 'Nasi Putih, Ayam Kecap, Tumis Buncis, Tahu Goreng.'],
            ['nama' => 'NASI AYAM KUNGPAO', 'deskripsi' => 'Nasi Putih, Ayam Kungpao, Mix Vegetables, Mapo Tahu.'],
            ['nama' => 'NASI TELUR SAOS TIRAM', 'deskripsi' => 'Nasi Putih, Telur Saos Tiram, Tumis Sawi Jagung, Tahu Cabe Garam.'],
            ['nama' => 'NASI HAINAN', 'deskripsi' => 'Nasi Hainan, Ayam Hainan, Timun, Tempe Bacem.'],
            ['nama' => 'NASI AYAM REMPAH', 'deskripsi' => 'Nasi Putih, Ayam Rempah, Tumis Buncis, Tahu Asam Manis, Pisang.'],
            ['nama' => 'NASI RAMES BALADO', 'deskripsi' => 'Nasi Putih, Telur Balado, Gado-gado, Tempe Goreng, Pisang.'],
            ['nama' => 'NASI AYAM TELUR ASIN', 'deskripsi' => 'Nasi Putih, Ayam Telur Asin, Tumis Sawi Jagung, Kacang Telur.'],
        ];

        MenuBergizi::query()->delete();

        foreach ($menuBergizi as $item) {
            MenuBergizi::query()->create($item);
        }
    }
}
