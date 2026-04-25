<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSellerRatings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'seller_id'   => ['type' => 'BIGINT', 'unsigned' => true],
            'reviewer_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'property_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'rating'      => ['type' => 'TINYINT', 'constraint' => 1],
            'review'      => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['reviewer_id', 'seller_id', 'property_id']);
        $this->forge->addKey('seller_id');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('reviewer_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('property_id', 'properties', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('seller_ratings');
    }

    public function down()
    {
        $this->forge->dropTable('seller_ratings');
    }
}
