<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    public function run()
    {
        $reports = [
            // --- 3 DATA KEHILANGAN ---
            [
                'code' => 'LOST-' . strtoupper(Str::random(5)),
                'resident_id' => 1,
                'type' => 'kehilangan',
                'title' => 'Kucing Anggora Putih Hilang',
                'description' => 'Hilang di sekitar taman, memakai kalung biru.',
                // Sesuai dengan format yang kamu inginkan
                'image' => 'assets/report/image/kucing_putih.jpg',
                'latitude' => '-6.8920',
                'longitude' => '107.6190',
                'address' => 'Jl. Sekeloa No. 12, Bandung',
                'last_seen_location' => 'Dekat Warung Makan',
                'pet_characteristics' => 'Bulu putih bersih, mata biru.',
                'pet_condition' => null,
                'status' => 'aktif',
                'created_at' => now(),
            ],
            [
                'code' => 'LOST-' . strtoupper(Str::random(5)),
                'resident_id' => 1,
                'type' => 'kehilangan',
                'title' => 'Anjing Husky Abu-abu',
                'description' => 'Terakhir terlihat lari ke arah pasar.',
                'image' => 'assets/report/image/husky_abu.jpg',
                'latitude' => '-6.8950',
                'longitude' => '107.6210',
                'address' => 'Jl. Dipati Ukur, Bandung',
                'last_seen_location' => 'Persimpangan lampu merah',
                'pet_characteristics' => 'Mata hitam sebelah, ramah.',
                'pet_condition' => null,
                'status' => 'aktif',
                'created_at' => now(),
            ],
            [
                'code' => 'LOST-' . strtoupper(Str::random(5)),
                'resident_id' => 1,
                'type' => 'kehilangan',
                'title' => 'Burung Lovebird Kuning',
                'description' => 'Lepas dari kandang saat dibersihkan.',
                'image' => 'assets/report/image/lovebird_kuning.jpg',
                'latitude' => '-6.8900',
                'longitude' => '107.6150',
                'address' => 'Coblong, Kota Bandung',
                'last_seen_location' => 'Pohon mangga depan rumah',
                'pet_characteristics' => 'Warna kuning cerah.',
                'pet_condition' => null,
                'status' => 'aktif',
                'created_at' => now(),
            ],

            // --- 3 DATA TEMUAN ---
            [
                'code' => 'FOUND-' . strtoupper(Str::random(5)),
                'resident_id' => 1,
                'type' => 'temuan',
                'title' => 'Ditemukan Kucing Orange',
                'description' => 'Ditemukan sedang berteduh di teras rumah warga.',
                'image' => 'assets/report/image/kucing_orange.jpg',
                'latitude' => '-6.8915',
                'longitude' => '107.6185',
                'address' => 'Jl. Lebak Gede, Bandung',
                'last_seen_location' => 'Teras Rumah No. 5',
                'pet_characteristics' => 'Kucing gemuk, tidak ada kalung.',
                'pet_condition' => 'Sehat dan lincah.',
                'status' => 'aktif',
                'created_at' => now(),
            ],
            [
                'code' => 'FOUND-' . strtoupper(Str::random(5)),
                'resident_id' => 1,
                'type' => 'temuan',
                'title' => 'Anjing Kecil Cokelat',
                'description' => 'Ditemukan di pinggir jalan raya.',
                'image' => 'assets/report/image/anjing_cokelat.jpg',
                'latitude' => '-6.8935',
                'longitude' => '107.6205',
                'address' => 'Jl. Sekeloa Utara, Bandung',
                'last_seen_location' => 'Samping minimarket',
                'pet_characteristics' => 'Bulu cokelat ikal.',
                'pet_condition' => 'Sedikit lemas.',
                'status' => 'aktif',
                'created_at' => now(),
            ],
            [
                'code' => 'FOUND-' . strtoupper(Str::random(5)),
                'resident_id' => 1,
                'type' => 'temuan',
                'title' => 'Ditemukan Kelinci Putih',
                'description' => 'Kelinci masuk ke area kebun sekolah.',
                'image' => 'assets/report/image/kelinci_putih.jpg',
                'latitude' => '-6.8910',
                'longitude' => '107.6170',
                'address' => 'Area SDN Sekeloa, Bandung',
                'last_seen_location' => 'Halaman belakang sekolah',
                'pet_characteristics' => 'Telinga tegak, sangat jinak.',
                'pet_condition' => 'Sangat baik.',
                'status' => 'aktif',
                'created_at' => now(),
            ],
        ];

        DB::table('reports')->insert($reports);
    }
}
