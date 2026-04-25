<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoPropertySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Create a demo seller user
        $existingSeller = $this->db->table('users')->where('email', 'demo@carin.local')->get()->getRow();
        if (! $existingSeller) {
            $this->db->table('users')->insert([
                'google_id'      => null,
                'email'          => 'demo@carin.local',
                'name'           => 'Budi Santoso',
                'phone'          => '081234567891',
                'role'           => 'member',
                'status'         => 'active',
                'free_slot_used' => 1,
                'avatar_url'     => 'https://i.pravatar.cc/150?img=13',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
            $sellerId = $this->db->insertID();
        } else {
            $sellerId = $existingSeller->id;
        }

        // Categories map
        $cats = [];
        foreach ($this->db->table('categories')->get()->getResultArray() as $c) {
            $cats[$c['slug']] = $c['id'];
        }

        $demo = [
            [
                'category' => 'rumah', 'type' => 'sell', 'price' => 1_850_000_000,
                'title' => 'Rumah Modern Minimalis 2 Lantai di BSD City',
                'city' => 'Tangerang Selatan', 'province' => 'Banten', 'district' => 'BSD',
                'lat' => -6.3028, 'lng' => 106.6538,
                'desc' => 'Rumah baru siap huni dengan design modern minimalis. Lokasi strategis di kawasan BSD City, dekat sekolah dan mall AEON.',
                'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&auto=format&fit=crop',
                'legal' => 'SHM', 'orientation' => 'N',
                'details' => ['land_area' => 120, 'building_area' => 180, 'bedrooms' => 4, 'bathrooms' => 3, 'floors' => 2, 'kitchens' => 1, 'front_yard' => 1, 'back_yard' => 1, 'fence' => 1],
            ],
            [
                'category' => 'apartemen', 'type' => 'rent', 'price' => 8_500_000, 'price_period' => 'monthly',
                'title' => 'Apartemen The Springlake View Summarecon Bekasi',
                'city' => 'Bekasi', 'province' => 'Jawa Barat', 'district' => 'Summarecon',
                'lat' => -6.2456, 'lng' => 106.9957,
                'desc' => 'Disewakan apartemen 2BR full furnished, lantai tinggi view danau. Fasilitas lengkap: pool, gym, mall, connected to mall.',
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&auto=format&fit=crop',
                'legal' => 'HGB', 'orientation' => 'E',
                'details' => ['land_area' => 45, 'building_area' => 45, 'bedrooms' => 2, 'bathrooms' => 1, 'floors' => 1, 'kitchens' => 1],
            ],
            [
                'category' => 'tanah', 'type' => 'sell', 'price' => 750_000_000,
                'title' => 'Tanah Strategis Pinggir Jalan Raya Serpong',
                'city' => 'Tangerang Selatan', 'province' => 'Banten', 'district' => 'Serpong',
                'lat' => -6.3201, 'lng' => 106.6682,
                'desc' => 'Tanah 500m² pinggir jalan raya, cocok untuk usaha ruko atau gudang. SHM ready balik nama. Akses mobil/truk mudah.',
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&auto=format&fit=crop',
                'legal' => 'SHM', 'orientation' => null,
                'details' => ['land_area' => 500, 'land_shape' => 'Persegi Panjang', 'road_width' => 12, 'road_access' => 'Truk'],
            ],
            [
                'category' => 'ruko', 'type' => 'sell', 'price' => 2_200_000_000,
                'title' => 'Ruko 3 Lantai Hoek di Boulevard Gading Serpong',
                'city' => 'Tangerang', 'province' => 'Banten', 'district' => 'Gading Serpong',
                'lat' => -6.2376, 'lng' => 106.6197,
                'desc' => 'Ruko hoek 3 lantai di lokasi ramai boulevard Gading Serpong. Cocok untuk usaha F&B, kantor, atau showroom.',
                'image' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=1200&auto=format&fit=crop',
                'legal' => 'HGB', 'orientation' => 'W',
                'details' => ['land_area' => 90, 'building_area' => 240, 'bedrooms' => 0, 'bathrooms' => 3, 'floors' => 3, 'kitchens' => 1],
            ],
            [
                'category' => 'rumah', 'type' => 'sell', 'price' => 950_000_000,
                'title' => 'Rumah Asri di Cluster Aman Bintaro',
                'city' => 'Tangerang Selatan', 'province' => 'Banten', 'district' => 'Bintaro',
                'lat' => -6.2730, 'lng' => 106.7277,
                'desc' => 'Rumah 1 lantai di dalam cluster 24 jam security. Lingkungan asri, dekat sekolah internasional dan RS.',
                'image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&auto=format&fit=crop',
                'legal' => 'SHM', 'orientation' => 'S',
                'details' => ['land_area' => 90, 'building_area' => 75, 'bedrooms' => 3, 'bathrooms' => 2, 'floors' => 1, 'kitchens' => 1, 'front_yard' => 1, 'fence' => 1],
            ],
            [
                'category' => 'gudang', 'type' => 'rent', 'price' => 120_000_000, 'price_period' => 'yearly',
                'title' => 'Gudang Besar Akses Tol Cikarang',
                'city' => 'Bekasi', 'province' => 'Jawa Barat', 'district' => 'Cikarang',
                'lat' => -6.2641, 'lng' => 107.1435,
                'desc' => 'Gudang 800m² dengan listrik 3 phase, akses container 40 feet, 5 menit dari pintu tol.',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1200&auto=format&fit=crop',
                'legal' => 'HGB',
                'details' => ['land_area' => 1000, 'building_area' => 800, 'floors' => 1],
            ],
            [
                'category' => 'kebun', 'type' => 'sell', 'price' => 450_000_000,
                'title' => 'Kebun Durian Produktif di Bogor',
                'city' => 'Bogor', 'province' => 'Jawa Barat', 'district' => 'Cisarua',
                'lat' => -6.7028, 'lng' => 106.9444,
                'desc' => 'Kebun durian 3000m² dengan 50 pohon produktif (montong, musangking). View pegunungan, udara sejuk.',
                'image' => 'https://images.unsplash.com/photo-1500076656116-558758c991c1?w=1200&auto=format&fit=crop',
                'legal' => 'Girik',
                'details' => ['land_area' => 3000, 'land_shape' => 'Tidak Beraturan', 'road_width' => 4, 'road_access' => 'Mobil'],
            ],
            [
                'category' => 'kantor', 'type' => 'rent', 'price' => 45_000_000, 'price_period' => 'monthly',
                'title' => 'Office Space Premium di SCBD Sudirman',
                'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'district' => 'SCBD',
                'lat' => -6.2255, 'lng' => 106.8087,
                'desc' => 'Ruang kantor 200m² lantai 30 dengan pemandangan kota Jakarta. Fully furnished, ready to move in.',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&auto=format&fit=crop',
                'legal' => 'HGB', 'orientation' => 'NE',
                'details' => ['land_area' => 200, 'building_area' => 200, 'bathrooms' => 2, 'floors' => 1],
            ],
        ];

        foreach ($demo as $d) {
            $slug = url_title($d['title'], '-', true);
            // Avoid duplicate
            if ($this->db->table('properties')->where('slug', $slug)->get()->getRow()) {
                continue;
            }
            $this->db->table('properties')->insert([
                'user_id'      => $sellerId,
                'category_id'  => $cats[$d['category']],
                'type'         => $d['type'],
                'title'        => $d['title'],
                'slug'         => $slug,
                'description'  => $d['desc'],
                'price'        => $d['price'],
                'price_period' => $d['price_period'] ?? '-',
                'legal_status' => $d['legal'] ?? 'SHM',
                'doc_status'   => 'on_hand',
                'orientation'  => $d['orientation'] ?? null,
                'province'     => $d['province'],
                'city'         => $d['city'],
                'district'     => $d['district'],
                'address'      => $d['district'] . ', ' . $d['city'],
                'latitude'     => $d['lat'],
                'longitude'    => $d['lng'],
                'status'       => 'published',
                'views'        => rand(50, 500),
                'is_paid_slot' => 0,
                'published_at' => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
            $pid = $this->db->insertID();

            // Save details
            foreach ($d['details'] as $k => $v) {
                $this->db->table('property_details')->insert([
                    'property_id' => $pid, 'key_name' => $k, 'value' => (string) $v,
                ]);
            }

            // Save 1 cover image (URL external, akan di-handle di view via property_image_url)
            $this->db->table('property_images')->insert([
                'property_id' => $pid,
                'file_name'   => $d['image'], // kita simpan URL langsung untuk demo
                'is_cover'    => 1,
                'sort_order'  => 0,
                'created_at'  => $now,
            ]);
        }
    }
}
