<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProperties extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'BIGINT', 'unsigned' => true],
            'category_id'   => ['type' => 'INT', 'unsigned' => true],
            'type'          => ['type' => 'ENUM', 'constraint' => ['sell', 'rent'], 'default' => 'sell'],
            'title'         => ['type' => 'VARCHAR', 'constraint' => 200],
            'slug'          => ['type' => 'VARCHAR', 'constraint' => 220],
            'description'   => ['type' => 'TEXT', 'null' => true],
            'price'         => ['type' => 'BIGINT', 'unsigned' => true, 'default' => 0],
            'price_period'  => ['type' => 'ENUM', 'constraint' => ['-', 'monthly', 'yearly'], 'default' => '-'],
            'legal_status'  => ['type' => 'ENUM', 'constraint' => ['SHM', 'AJB', 'Girik', 'Waris', 'HGB', 'SHGB'], 'null' => true],
            'doc_status'    => ['type' => 'ENUM', 'constraint' => ['on_hand', 'at_bank'], 'default' => 'on_hand'],
            'orientation'   => ['type' => 'ENUM', 'constraint' => ['N', 'S', 'E', 'W', 'NE', 'NW', 'SE', 'SW'], 'null' => true],
            'province'      => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'city'          => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'district'      => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'address'       => ['type' => 'TEXT', 'null' => true],
            'latitude'      => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'longitude'     => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'maps_url'      => ['type' => 'TEXT', 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['draft', 'pending_review', 'published', 'rejected', 'sold'], 'default' => 'pending_review'],
            'reject_reason' => ['type' => 'TEXT', 'null' => true],
            'views'         => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'is_paid_slot'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'published_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('user_id');
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');
        $this->forge->addKey(['type', 'status']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('properties');
    }

    public function down()
    {
        $this->forge->dropTable('properties');
    }
}
