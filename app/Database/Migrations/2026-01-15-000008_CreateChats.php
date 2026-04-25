<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChats extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'property_id'     => ['type' => 'BIGINT', 'unsigned' => true],
            'buyer_id'        => ['type' => 'BIGINT', 'unsigned' => true],
            'seller_id'       => ['type' => 'BIGINT', 'unsigned' => true],
            'last_message_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['buyer_id', 'seller_id']);
        $this->forge->addUniqueKey(['property_id', 'buyer_id']);
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chats');
    }

    public function down()
    {
        $this->forge->dropTable('chats');
    }
}
