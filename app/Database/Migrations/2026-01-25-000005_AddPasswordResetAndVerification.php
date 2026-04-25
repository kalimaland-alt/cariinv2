<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordResetAndVerification extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'email_verified_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'email',
            ],
            'verify_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'email_verified_at',
            ],
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'verify_token',
            ],
            'reset_expires' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'reset_token',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['email_verified_at', 'verify_token', 'reset_token', 'reset_expires']);
    }
}
