<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVillageAndExpandEnums extends Migration
{
    public function up()
    {
        // Add 'village' column
        $this->forge->addColumn('properties', [
            'village' => [
                'type'       => 'VARCHAR',
                'constraint' => 80,
                'null'       => true,
                'after'      => 'district',
            ],
        ]);

        // Expand price_period enum to include 'daily'
        $this->db->query("ALTER TABLE `properties` MODIFY COLUMN `price_period` ENUM('-','monthly','yearly','daily') NOT NULL DEFAULT '-'");

        // Expand legal_status to allow lowercase values too (loose matching)
        $this->db->query("ALTER TABLE `properties` MODIFY COLUMN `legal_status` ENUM('SHM','AJB','Girik','Waris','HGB','SHGB','shm','ajb','girik','waris','hgb','shgb','other') NULL");
    }

    public function down()
    {
        $this->forge->dropColumn('properties', 'village');
        $this->db->query("ALTER TABLE `properties` MODIFY COLUMN `price_period` ENUM('-','monthly','yearly') NOT NULL DEFAULT '-'");
        $this->db->query("ALTER TABLE `properties` MODIFY COLUMN `legal_status` ENUM('SHM','AJB','Girik','Waris','HGB','SHGB') NULL");
    }
}
