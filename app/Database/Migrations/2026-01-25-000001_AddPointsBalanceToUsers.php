<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPointsBalanceToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'points_balance' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'free_slot_used',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'points_balance');
    }
}
