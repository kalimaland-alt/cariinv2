<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'BIGINT', 'unsigned' => true],
            'property_id'    => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'transaction_id' => ['type' => 'VARCHAR', 'constraint' => 100],
            'snap_token'     => ['type' => 'TEXT', 'null' => true],
            'amount'         => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'qris'],
            'status'         => ['type' => 'ENUM', 'constraint' => ['pending', 'success', 'failed', 'expired'], 'default' => 'pending'],
            'paid_at'        => ['type' => 'DATETIME', 'null' => true],
            'raw_response'   => ['type' => 'JSON', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('transaction_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments');
    }
}
