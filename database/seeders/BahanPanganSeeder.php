<?php

namespace Database\Seeders;

use App\Models\BahanPangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BahanPanganSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $bahanPangan = [
            [
                'nama' => 'Serealia',
                'deskripsi' => 'Kelompok biji-bijian seperti beras, jagung, gandum, dan sorgum yang menjadi sumber utama karbohidrat.',
            ],
            [
                'nama' => 'Serealia Olahan',
                'deskripsi' => 'Produk hasil pengolahan serealia, seperti tepung, mi, roti, nasi, dan bubur.',
            ],
            [
                'nama' => 'Umbi Berpati',
                'deskripsi' => 'Kelompok umbi yang kaya pati, seperti singkong, ubi jalar, kentang, talas, dan sagu.',
            ],
            [
                'nama' => 'Umbi Berpati Olahan',
                'deskripsi' => 'Produk hasil pengolahan umbi berpati, seperti tapioka, gaplek, keripik, dan tepung umbi.',
            ],
            [
                'nama' => 'Kacang & Biji-bijian',
                'deskripsi' => 'Kelompok kacang dan biji yang menjadi sumber protein nabati, serat, vitamin, dan mineral.',
            ],
            [
                'nama' => 'Kacang & Biji-bijian Olahan',
                'deskripsi' => 'Produk hasil pengolahan kacang dan biji-bijian, seperti tahu, tempe, selai kacang, dan susu kedelai.',
            ],
            [
                'nama' => 'Sayuran',
                'deskripsi' => 'Berbagai jenis sayuran segar yang menjadi sumber serat, vitamin, mineral, dan senyawa bioaktif.',
            ],
            [
                'nama' => 'Sayuran Olahan',
                'deskripsi' => 'Produk sayuran yang telah dimasak, dikeringkan, difermentasi, dibekukan, atau diawetkan.',
            ],
            [
                'nama' => 'Buah',
                'deskripsi' => 'Berbagai jenis buah segar yang menjadi sumber vitamin, mineral, serat, dan antioksidan.',
            ],
            [
                'nama' => 'Buah Olahan',
                'deskripsi' => 'Produk hasil pengolahan buah, seperti jus, selai, manisan, buah kering, dan buah kaleng.',
            ],
            [
                'nama' => 'Daging dan Unggas',
                'deskripsi' => 'Daging segar dari ternak dan unggas yang menjadi sumber protein hewani, zat besi, dan vitamin B12.',
            ],
            [
                'nama' => 'Daging Olahan',
                'deskripsi' => 'Produk hasil pengolahan daging dan unggas, seperti sosis, bakso, abon, dendeng, dan nugget.',
            ],
            [
                'nama' => 'Ikan, Kerang, Udang',
                'deskripsi' => 'Kelompok hasil perairan segar yang menjadi sumber protein, mineral, dan asam lemak esensial.',
            ],
            [
                'nama' => 'Ikan, Kerang, Udang Olahan',
                'deskripsi' => 'Produk hasil pengolahan ikan, kerang, dan udang, seperti ikan asin, pindang, terasi, dan makanan beku.',
            ],
            [
                'nama' => 'Telur',
                'deskripsi' => 'Telur segar dari unggas yang menjadi sumber protein hewani, lemak, vitamin, dan mineral.',
            ],
            [
                'nama' => 'Telur Olahan',
                'deskripsi' => 'Produk hasil pengolahan telur, seperti telur asin, telur pindang, bubuk telur, dan makanan berbahan telur.',
            ],
            [
                'nama' => 'Susu',
                'deskripsi' => 'Susu segar yang menjadi sumber protein, kalsium, lemak, vitamin, dan mineral.',
            ],
            [
                'nama' => 'Susu Olahan',
                'deskripsi' => 'Produk hasil pengolahan susu, seperti yoghurt, keju, susu bubuk, susu kental, dan es krim.',
            ],
            [
                'nama' => 'Lemak dan Minyak',
                'deskripsi' => 'Lemak dan minyak pangan yang digunakan sebagai sumber energi serta bahan memasak.',
            ],
            [
                'nama' => 'Lemak, Minyak Olahan',
                'deskripsi' => 'Produk hasil pengolahan lemak dan minyak, seperti margarin, shortening, santan, dan minyak campuran.',
            ],
            [
                'nama' => 'Gula, Sirup, Konfeksioneri',
                'deskripsi' => 'Kelompok gula, sirup, dan produk konfeksioneri yang terutama menjadi sumber karbohidrat sederhana.',
            ],
            [
                'nama' => 'Gula, Sirup, Olahan',
                'deskripsi' => 'Produk olahan berbahan gula atau sirup, seperti karamel, fondan, selai gula, dan pemanis olahan.',
            ],
            [
                'nama' => 'Bumbu',
                'deskripsi' => 'Bahan penyedap alami seperti rempah, herba, garam, bawang, dan cabai yang digunakan dalam masakan.',
            ],
            [
                'nama' => 'Bumbu Olahan',
                'deskripsi' => 'Produk bumbu siap pakai atau hasil pengolahan, seperti saus, kecap, sambal, pasta bumbu, dan bumbu instan.',
            ],
            [
                'nama' => 'Minuman',
                'deskripsi' => 'Kelompok cairan siap konsumsi, seperti air minum, teh, kopi, dan minuman berbahan pangan.',
            ],
            [
                'nama' => 'Minuman Olahan',
                'deskripsi' => 'Produk minuman yang telah diformulasikan atau diproses, seperti minuman serbuk, konsentrat, dan minuman kemasan.',
            ],
        ];

        BahanPangan::query()->delete();

        foreach ($bahanPangan as $item) {
            BahanPangan::query()->create($item);
        }
    }
}
