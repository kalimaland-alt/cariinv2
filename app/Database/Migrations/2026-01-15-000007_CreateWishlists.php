<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWishlists extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'BIGINT', 'unsigned' => true],
            'property_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'property_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('wishlists');
    }

    public function down()
    {
        $this->forge->dropTable('wishlists');
    }
}
