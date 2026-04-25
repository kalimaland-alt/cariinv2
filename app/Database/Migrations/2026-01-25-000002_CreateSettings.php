<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'key'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'value'      => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('key');
        $this->forge->createTable('settings');

        // Seed defaults
        $db = \Config\Database::connect();
        $defaults = [
            ['key' => 'footer_description', 'value' => 'Marketplace properti Indonesia yang menghubungkan penjual & pembeli dengan lebih cepat, aman, dan terpercaya.'],
            ['key' => 'footer_email',       'value' => 'hello@carin.id'],
            ['key' => 'footer_address',     'value' => 'Indonesia'],
            ['key' => 'footer_phone',       'value' => ''],
            ['key' => 'footer_facebook',    'value' => ''],
            ['key' => 'footer_instagram',   'value' => ''],
            ['key' => 'footer_twitter',     'value' => ''],
            ['key' => 'footer_copyright',   'value' => '© {year} CariIn. Semua hak dilindungi.'],
            ['key' => 'point_rate',         'value' => '1000'], // 1 point = Rp 1000
            ['key' => 'slot_price_points',  'value' => '20'],   // 20 points per slot
        ];
        $now = date('Y-m-d H:i:s');
        foreach ($defaults as $d) {
            $d['created_at'] = $now;
            $d['updated_at'] = $now;
            $db->table('settings')->insert($d);
        }
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
