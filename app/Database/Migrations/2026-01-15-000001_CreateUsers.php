<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'google_id'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 150],
            'password_hash'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'name'            => ['type' => 'VARCHAR', 'constraint' => 150],
            'avatar_url'      => ['type' => 'TEXT', 'null' => true],
            'phone'           => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'role'            => ['type' => 'ENUM', 'constraint' => ['admin', 'member'], 'default' => 'member'],
            'status'          => ['type' => 'ENUM', 'constraint' => ['active', 'suspended'], 'default' => 'active'],
            'free_slot_used'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('google_id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
