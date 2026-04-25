<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopups extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'BIGINT', 'unsigned' => true],
            'transaction_id' => ['type' => 'VARCHAR', 'constraint' => 100],
            'amount_rp'      => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'points'         => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'qris'],
            'status'         => ['type' => 'ENUM', 'constraint' => ['pending', 'success', 'failed', 'expired', 'manual_confirm'], 'default' => 'pending'],
            'snap_token'     => ['type' => 'TEXT', 'null' => true],
            'note'           => ['type' => 'TEXT', 'null' => true],
            'paid_at'        => ['type' => 'DATETIME', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('transaction_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('topups');
    }

    public function down()
    {
        $this->forge->dropTable('topups');
    }
}
