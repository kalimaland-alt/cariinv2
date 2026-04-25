<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropertyDetails extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'property_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'key_name'    => ['type' => 'VARCHAR', 'constraint' => 60],
            'value'       => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['property_id', 'key_name']);
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('property_details');
    }

    public function down()
    {
        $this->forge->dropTable('property_details');
    }
}
