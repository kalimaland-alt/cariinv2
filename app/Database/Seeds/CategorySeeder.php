<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            ['name' => 'Rumah',     'slug' => 'rumah',     'form_type' => 'building', 'icon' => 'bi-house-door',       'sort_order' => 1],
            ['name' => 'Apartemen', 'slug' => 'apartemen', 'form_type' => 'building', 'icon' => 'bi-building',         'sort_order' => 2],
            ['name' => 'Ruko',      'slug' => 'ruko',      'form_type' => 'building', 'icon' => 'bi-shop',             'sort_order' => 3],
            ['name' => 'Kantor',    'slug' => 'kantor',    'form_type' => 'building', 'icon' => 'bi-briefcase',        'sort_order' => 4],
            ['name' => 'Gudang',    'slug' => 'gudang',    'form_type' => 'building', 'icon' => 'bi-box-seam',         'sort_order' => 5],
            ['name' => 'Tanah',     'slug' => 'tanah',     'form_type' => 'land',     'icon' => 'bi-pin-map',          'sort_order' => 6],
            ['name' => 'Kebun',     'slug' => 'kebun',     'form_type' => 'land',     'icon' => 'bi-tree',             'sort_order' => 7],
        ];

        foreach ($data as &$row) {
            $row['is_active']  = 1;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->db->table('categories')->ignore(true)->insertBatch($data);
    }
}
