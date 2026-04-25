<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropertyImages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'property_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'file_name'   => ['type' => 'VARCHAR', 'constraint' => 200],
            'is_cover'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'sort_order'  => ['type' => 'INT', 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('property_id');
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('property_images');
    }

    public function down()
    {
        $this->forge->dropTable('property_images');
    }
}
